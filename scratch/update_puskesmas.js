const fs = require('fs');

const kecMapping = {
    "Medan Kota": 1, "Medan Barat": 2, "Medan Timur": 3, "Medan Petisah": 4, "Medan Polonia": 5,
    "Medan Helvetia": 6, "Medan Baru": 7, "Medan Perjuangan": 8, "Medan Maimun": 9, "Medan Selayang": 10,
    "Medan Tuntungan": 11, "Medan Johor": 12, "Medan Amplas": 13, "Medan Denai": 14, "Medan Area": 15,
    "Medan Tembung": 16, "Medan Sunggal": 17, "Medan Deli": 18, "Medan Labuhan": 19, "Medan Marelan": 20,
    "Medan Belawan": 21
};

const accuratePuskesmas = [
    { nama: "Puskesmas Padang Bulan", lat: 3.5607826995411918, lon: 98.66211781005646, kec: "Medan Baru", addr: "Medan Baru" },
    { nama: "Puskesmas Polonia Medan", lat: 3.569433629119114, lon: 98.66802482354746, kec: "Medan Polonia", addr: "Medan Polonia" },
    { nama: "Puskesmas Darussalam", lat: 3.5870067136921957, lon: 98.65279408518431, kec: "Medan Baru", addr: "Medan Baru" },
    { nama: "Puskesmas Bestari", lat: 3.5899263607173983, lon: 98.66536137776629, kec: "Medan Petisah", addr: "Medan Petisah" },
    { nama: "Puskesmas Tanjung Rejo", lat: 3.5754188366383497, lon: 98.64302958518431, kec: "Medan Sunggal", addr: "Medan Sunggal" },
    { nama: "Puskesmas Sentosa Baru", lat: 3.6012416737177397, lon: 98.70163996191148, kec: "Medan Perjuangan", addr: "Medan Perjuangan" },
    { nama: "Puskesmas Kampung Baru", lat: 3.551652423580065, lon: 98.6872196238026, kec: "Medan Maimun", addr: "Medan Maimun" },
    { nama: "Puskesmas Glugur Darat", lat: 3.6158077484995013, lon: 98.68167791587516, kec: "Medan Timur", addr: "Medan Timur" },
    { nama: "Puskesmas Pemb Tanjung Sari", lat: 3.559592841879692, lon: 98.63526558407517, kec: "Medan Selayang", addr: "Medan Selayang" },
    { nama: "Puskesmas Medan Sunggal", lat: 3.5767804016991804, lon: 98.61171212724672, kec: "Medan Sunggal", addr: "Medan Sunggal" },
    { nama: "Puskesmas Rantang", lat: 3.593647063218205, lon: 98.65570690026492, kec: "Medan Petisah", addr: "Medan Petisah" },
    { nama: "Puskesmas Teladan", lat: 3.563588785678069, lon: 98.69337415001016, kec: "Medan Kota", addr: "Medan Kota" },
    { nama: "Puskesmas Glugur Kota", lat: 3.6066546580182717, lon: 98.67361941190127, kec: "Medan Barat", addr: "Medan Barat" },
    { nama: "Puskesmas Helvetia", lat: 3.6125124609889347, lon: 98.63202423888312, kec: "Medan Helvetia", addr: "Medan Helvetia" },
    { nama: "Puskesmas PB Selayang II", lat: 3.5521025784523768, lon: 98.63862390026489, kec: "Medan Selayang", addr: "Medan Selayang" },
    { nama: "Puskesmas Pulo Brayan", lat: 3.62302576624265, lon: 98.6711059532098, kec: "Medan Barat", addr: "Medan Barat" }
];

const estimasiPuskesmas = [
    { nama: "Puskesmas Tuntungan", lat: 3.5012, lon: 98.6234, kec: "Medan Tuntungan" },
    { nama: "Puskesmas Simalingkar", lat: 3.5082, lon: 98.6189, kec: "Medan Tuntungan" },
    { nama: "Puskesmas Kedai Durian", lat: 3.5341, lon: 98.6512, kec: "Medan Johor" },
    { nama: "Puskesmas Medan Johor", lat: 3.5398, lon: 98.6623, kec: "Medan Johor" },
    { nama: "Puskesmas Amplas", lat: 3.5431, lon: 98.6891, kec: "Medan Amplas" },
    { nama: "Puskesmas Bromo", lat: 3.5612, lon: 98.7023, kec: "Medan Denai" },
    { nama: "Puskesmas Tegal Sari", lat: 3.5589, lon: 98.7089, kec: "Medan Denai" },
    { nama: "Puskesmas Desa Binjai", lat: 3.5534, lon: 98.7145, kec: "Medan Denai" },
    { nama: "Puskesmas Medan Denai", lat: 3.5556, lon: 98.7201, kec: "Medan Denai" },
    { nama: "Puskesmas Medan Area Selatan", lat: 3.5712, lon: 98.6934, kec: "Medan Area" },
    { nama: "Puskesmas Kota Matsum", lat: 3.5734, lon: 98.6867, kec: "Medan Area" },
    { nama: "Puskesmas Pasar Merah", lat: 3.5678, lon: 98.6812, kec: "Medan Kota" },
    { nama: "Puskesmas Simpang Limun", lat: 3.5645, lon: 98.6756, kec: "Medan Kota" },
    { nama: "Puskesmas Sei Agul", lat: 3.5923, lon: 98.6634, kec: "Medan Barat" },
    { nama: "Puskesmas Sering", lat: 3.5867, lon: 98.6923, kec: "Medan Timur" },
    { nama: "Puskesmas Mandala", lat: 3.5712, lon: 98.7312, kec: "Medan Tembung" },
    { nama: "Puskesmas Medan Deli", lat: 3.6234, lon: 98.6934, kec: "Medan Deli" },
    { nama: "Puskesmas Titi Papan", lat: 3.6312, lon: 98.6867, kec: "Medan Deli" },
    { nama: "Puskesmas Medan Labuhan", lat: 3.6712, lon: 98.7034, kec: "Medan Labuhan" },
    { nama: "Puskesmas Martubung", lat: 3.6834, lon: 98.7223, kec: "Medan Labuhan" },
    { nama: "Puskesmas Rengas Pulau", lat: 3.6923, lon: 98.7145, kec: "Medan Labuhan" },
    { nama: "Puskesmas Desa Terjun", lat: 3.6512, lon: 98.6534, kec: "Medan Marelan" },
    { nama: "Puskesmas Desa Lalang", lat: 3.5934, lon: 98.6123, kec: "Medan Sunggal" },
    { nama: "Puskesmas Belawan", lat: 3.7823, lon: 98.7034, kec: "Medan Belawan" },
    { nama: "Puskesmas Sicanang", lat: 3.7934, lon: 98.6923, kec: "Medan Belawan" },
    { nama: "Puskesmas Pekan Labuhan", lat: 3.7123, lon: 98.7089, kec: "Medan Labuhan" },
    { nama: "Puskesmas Sukaramai", lat: 3.5756, lon: 98.6978, kec: "Medan Area" }
];

const allPuskesmas = [...accuratePuskesmas, ...estimasiPuskesmas];

let sql = "-- Hapus semua puskesmas lama\nDELETE FROM fasilitas WHERE jenis = 'puskesmas';\n\n";
sql += "INSERT INTO fasilitas (nama, jenis, alamat, kecamatan_id, latitude, longitude, deskripsi, jam_operasional, foto_url) VALUES \n";

const values = allPuskesmas.map(p => {
    const kecId = kecMapping[p.kec];
    const addr = p.addr || `Kec. ${p.kec}, Kota Medan`;
    const foto = `https://placehold.co/600x400?text=${encodeURIComponent(p.nama)}`;
    return `('${p.nama.replace(/'/g, "''")}', 'puskesmas', '${addr.replace(/'/g, "''")}', ${kecId}, ${p.lat}, ${p.lon}, 'Pusat Kesehatan Masyarakat di ${p.kec}', 'Senin-Sabtu: 08:00 - 14:00', '${foto}')`;
});

sql += values.join(',\n') + ";";

fs.writeFileSync('update_puskesmas_user.sql', sql);
console.log('Generated update_puskesmas_user.sql with 43 records');
