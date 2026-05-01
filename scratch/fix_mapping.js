const fs = require('fs');

const order = [
    "Medan Kota", "Medan Barat", "Medan Timur", "Medan Petisah", "Medan Polonia",
    "Medan Helvetia", "Medan Baru", "Medan Perjuangan", "Medan Maimun", "Medan Selayang",
    "Medan Tuntungan", "Medan Johor", "Medan Amplas", "Medan Denai", "Medan Area",
    "Medan Tembung", "Medan Sunggal", "Medan Deli", "Medan Labuhan", "Medan Marelan",
    "Medan Belawan"
];

// Read the update SQL that has the real GeoJSON data
const updateSql = fs.readFileSync('update_kecamatan.sql', 'utf8');
const geojsonMap = {};
const matches = [...updateSql.matchAll(/UPDATE kecamatan SET geojson = '(.*?)' WHERE nama = '(.*?)';/gs)];
matches.forEach(m => {
    geojsonMap[m[2]] = m[1];
});

// Build INSERT VALUES in the specific order
const values = order.map(name => {
    const geojson = geojsonMap[name] || '{"type":"Polygon","coordinates":[]}';
    return `('${name}', '${geojson}')`;
}).join(',\n');

const insertBlock = `INSERT INTO kecamatan (nama, geojson) VALUES \n${values};\n`;

// Generate live SQL
const liveSql = `TRUNCATE TABLE kecamatan RESTART IDENTITY CASCADE;\n${insertBlock}`;
fs.writeFileSync('insert_kecamatan_live.sql', liveSql);

// Update database_setup.sql
let setup = fs.readFileSync('../database_setup.sql', 'utf8');
setup = setup.replace(
    /INSERT INTO kecamatan \(nama, geojson\) VALUES[\s\S]*?;/,
    insertBlock
);
fs.writeFileSync('../database_setup.sql', setup);

console.log('Fixed mapping and updated files.');
