const https = require('https');
const fs = require('fs');

function isPointInPolygon(point, vs) {
    const x = point[0], y = point[1];
    let inside = false;
    for (let i = 0, j = vs.length - 1; i < vs.length; j = i++) {
        const xi = vs[i][0], yi = vs[i][1];
        const xj = vs[j][0], yj = vs[j][1];
        const intersect = ((yi > y) !== (yj > y))
            && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
        if (intersect) inside = !inside;
    }
    return inside;
}

function isPointInMultiPolygon(point, multi) {
    return multi.some(poly => isPointInPolygon(point, poly[0]));
}

async function run() {
    const { execSync } = require('child_process');
    const psqlCmd = `psql -U postgres -d sig_fasilitas -t -c "SELECT id, nama, geojson FROM kecamatan"`;
    process.env.PGPASSWORD = 'postgres123';
    const output = execSync(psqlCmd).toString();
    const kecamatans = output.trim().split('\n').map(line => {
        const parts = line.split('|');
        if (parts.length < 3) return null;
        return {
            id: parseInt(parts[0].trim()),
            nama: parts[1].trim(),
            geojson: JSON.parse(parts[2].trim())
        };
    }).filter(k => k);

    const query = `
    [out:json];
    area["name"="Kota Medan"]->.searchArea;
    (
      node["amenity"~"clinic|hospital|fire_station"](area.searchArea);
      node["leisure"~"park|playground|garden"](area.searchArea);
      way["amenity"~"clinic|hospital|fire_station"](area.searchArea);
      way["leisure"~"park|playground|garden"](area.searchArea);
    );
    out center;
    `;

    const options = {
        hostname: 'overpass-api.de',
        path: '/api/interpreter?data=' + encodeURIComponent(query),
        method: 'GET',
        headers: { 'User-Agent': 'NodeJS/1.0', 'Accept': 'application/json' }
    };

    const req = https.request(options, (res) => {
        let data = '';
        res.on('data', chunk => data += chunk);
        res.on('end', () => {
            if (res.statusCode !== 200) return;
            const json = JSON.parse(data);
            const facilities = [];
            
            json.elements.forEach(el => {
                const lat = el.lat || (el.center ? el.center.lat : null);
                const lon = el.lon || (el.center ? el.center.lon : null);
                if (!lat || !lon) return;

                let name = el.tags.name || el.tags.official_name;
                if (!name) return; // Skip nameless

                let jenis = 'taman';
                if (el.tags.amenity === 'fire_station' || name.toLowerCase().includes('pemadam')) jenis = 'damkar';
                else if (el.tags.amenity === 'clinic' || el.tags.amenity === 'hospital' || name.toLowerCase().includes('puskesmas')) jenis = 'puskesmas';
                else if (el.tags.leisure === 'park' || el.tags.leisure === 'garden' || name.toLowerCase().includes('taman') || name.toLowerCase().includes('lapangan')) jenis = 'taman';
                else return; // Skip others

                let kecId = null;
                for (const kec of kecamatans) {
                    const coords = kec.geojson.coordinates;
                    if (kec.geojson.type === 'Polygon') {
                        if (isPointInPolygon([lon, lat], coords[0])) { kecId = kec.id; break; }
                    } else if (kec.geojson.type === 'MultiPolygon') {
                        if (isPointInMultiPolygon([lon, lat], coords)) { kecId = kec.id; break; }
                    }
                }

                if (!kecId) return;

                facilities.push({
                    nama: name,
                    jenis,
                    alamat: el.tags['addr:full'] || el.tags['addr:street'] || `Jl. di ${name}`,
                    kecamatan_id: kecId,
                    latitude: lat,
                    longitude: lon,
                    deskripsi: el.tags.description || `Layanan ${jenis} publik di ${name}.`,
                    telepon: el.tags.phone || el.tags['contact:phone'] || null
                });
            });

            // Final Deduplicate
            const unique = [];
            const seen = new Set();
            facilities.forEach(f => {
                const key = f.nama.toLowerCase() + f.latitude.toFixed(3) + f.longitude.toFixed(3);
                if (!seen.has(key)) {
                    seen.add(key);
                    unique.push(f);
                }
            });

            let sql = "TRUNCATE TABLE fasilitas RESTART IDENTITY CASCADE;\n";
            sql += "INSERT INTO fasilitas (nama, jenis, alamat, kecamatan_id, latitude, longitude, deskripsi, jam_operasional, telepon, foto_url) VALUES \n";
            
            const values = unique.map(f => {
                const name = f.nama.replace(/'/g, "''");
                const addr = f.alamat.replace(/'/g, "''");
                const desc = f.deskripsi.replace(/'/g, "''");
                const tel = f.telepon ? `'${f.telepon.replace(/'/g, "")}'` : "NULL";
                const foto = `https://placehold.co/600x400?text=${encodeURIComponent(f.nama)}`;
                return `('${name}', '${f.jenis}', '${addr}', ${f.kecamatan_id}, ${f.latitude}, ${f.longitude}, '${desc}', '24 Jam / Sesuai Ketentuan', ${tel}, '${foto}')`;
            });

            sql += values.join(',\n') + ";";
            fs.writeFileSync('update_fasilitas_real.sql', sql);
            console.log(`Updated facilities: ${unique.length}`);
        });
    });
    req.end();
}

run();
