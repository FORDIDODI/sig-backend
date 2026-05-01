const https = require('https');
const osmtogeojson = require('osmtogeojson');
const fs = require('fs');

const query = `
[out:json];
area["name"="Kota Medan"]->.searchArea;
relation["admin_level"="6"](area.searchArea);
out geom;
`;

const options = {
  hostname: 'overpass-api.de',
  port: 443,
  path: '/api/interpreter?data=' + encodeURIComponent(query),
  method: 'GET',
  headers: {
    'User-Agent': 'NodeJS/14.0 MedanGeojsonScript/1.0',
    'Accept': 'application/json'
  }
};

const req = https.request(options, (res) => {
  let data = '';

  res.on('data', (chunk) => {
    data += chunk;
  });

  res.on('end', () => {
    if (res.statusCode !== 200) {
      console.error('Request failed with status code', res.statusCode);
      return;
    }
    
    try {
      const json = JSON.parse(data);
      const geojson = osmtogeojson(json);
      
      let sql = '';
      let count = 0;
      geojson.features.forEach(feature => {
        if (feature.properties && feature.properties.name) {
           let name = feature.properties.name;
           if (name.startsWith('Kecamatan ')) {
               name = name.replace('Kecamatan ', '');
           }
           const geomStr = JSON.stringify(feature.geometry).replace(/'/g, "''");
           sql += `UPDATE kecamatan SET geojson = '${geomStr}' WHERE nama = '${name}';\n`;
           count++;
        }
      });

      fs.writeFileSync('update_kecamatan.sql', sql);
      console.log(`Generated update_kecamatan.sql with ${count} records`);
    } catch (e) {
      console.error(e);
    }
  });
});

req.on('error', (e) => {
  console.error(e);
});

req.end();
