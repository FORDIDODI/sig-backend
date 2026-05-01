const fs = require('fs');

// Read the update SQL that has the real GeoJSON data
const updateSql = fs.readFileSync('update_kecamatan.sql', 'utf8');
const matches = [...updateSql.matchAll(/UPDATE kecamatan SET geojson = '(.*?)' WHERE nama = '(.*?)';/gs)];

// Build INSERT VALUES
const values = matches.map(m => {
    const name = m[2];
    const geojson = m[1];
    return `('${name}', '${geojson}')`;
}).join(',\n');

const insertBlock = `INSERT INTO kecamatan (nama, geojson) VALUES \n${values};\n`;

// Read database_setup.sql
let setup = fs.readFileSync('../database_setup.sql', 'utf8');

// Replace the broken INSERT block (from "INSERT INTO kecamatan" up to "-- 5.")
setup = setup.replace(
    /INSERT INTO kecamatan \(nama, geojson\) VALUES[\s\S]*?;\s*\n\n-- 5\./,
    insertBlock + '\n-- 5.'
);

fs.writeFileSync('../database_setup.sql', setup);
console.log('Fixed database_setup.sql with ' + matches.length + ' kecamatan records');

// Also generate a standalone SQL to insert into the live DB right now
const liveSql = `TRUNCATE TABLE kecamatan RESTART IDENTITY CASCADE;\n${insertBlock}`;
fs.writeFileSync('insert_kecamatan_live.sql', liveSql);
console.log('Generated insert_kecamatan_live.sql for live DB');
