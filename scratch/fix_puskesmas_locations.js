const https = require('https');
const fs = require('fs');

const missing = [
    "Puskesmas Tuntungan", "Puskesmas Simalingkar", "Puskesmas Kedai Durian", "Puskesmas Medan Johor",
    "Puskesmas Amplas", "Puskesmas Bromo", "Puskesmas Tegal Sari", "Puskesmas Desa Binjai",
    "Puskesmas Medan Denai", "Puskesmas Medan Area Selatan", "Puskesmas Kota Matsum", "Puskesmas Pasar Merah",
    "Puskesmas Simpang Limun", "Puskesmas Sei Agul", "Puskesmas Sering", "Puskesmas Mandala",
    "Puskesmas Titi Papan", "Puskesmas Martubung", "Puskesmas Rengas Pulau", "Puskesmas Desa Terjun"
];

async function fetchFromOSM(name) {
    return new Promise((resolve) => {
        // Try without "Puskesmas" prefix or with "UPT"
        const terms = [name + " Medan", "UPT " + name + " Medan", name.replace("Puskesmas ", "") + " Medan"];
        
        let found = null;
        async function tryTerms(index) {
            if (index >= terms.length) { resolve(null); return; }
            
            const options = {
                hostname: 'nominatim.openstreetmap.org',
                path: `/search?q=${encodeURIComponent(terms[index])}&format=json&limit=1&addressdetails=1`,
                method: 'GET',
                headers: { 'User-Agent': 'NodeJS/1.0 MedanGIS/1.0' }
            };

            const req = https.request(options, (res) => {
                let data = '';
                res.on('data', chunk => data += chunk);
                res.on('end', () => {
                    try {
                        const json = JSON.parse(data);
                        if (json.length > 0) {
                            resolve(json[0]);
                        } else {
                            setTimeout(() => tryTerms(index + 1), 1000);
                        }
                    } catch (e) { setTimeout(() => tryTerms(index + 1), 1000); }
                });
            });
            req.end();
        }
        tryTerms(0);
    });
}

async function run() {
    console.log("Searching for 20 missing Puskesmas...");
    const results = [];
    for (const name of missing) {
        console.log(`Searching: ${name}`);
        const result = await fetchFromOSM(name);
        if (result) {
            results.push({
                origName: name,
                foundName: result.display_name,
                lat: result.lat,
                lon: result.lon,
                address: result.address
            });
        }
    }

    let sql = fs.readFileSync('fix_puskesmas_osm.sql', 'utf8');
    results.forEach(res => {
        const street = res.address.road || res.address.suburb || res.address.neighbourhood || "Jl. di Medan";
        const addr = `${street}, ${res.address.city_district || ''}, Medan`.replace(/, ,/g, ',');
        sql += `UPDATE fasilitas SET latitude = ${res.lat}, longitude = ${res.lon}, alamat = '${addr.replace(/'/g, "''")}' WHERE nama = '${res.origName.replace(/'/g, "''")}';\n`;
    });

    fs.writeFileSync('fix_puskesmas_osm.sql', sql);
    console.log(`Updated fix_puskesmas_osm.sql with total found results.`);
}

run();
