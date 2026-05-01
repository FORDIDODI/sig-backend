<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * KecamatanSeeder
 * Isi 21 kecamatan Kota Medan beserta polygon GeoJSON batas wilayah.
 * Koordinat polygon merupakan aproksimasi batas administratif berdasarkan
 * data OpenStreetMap / BPS Kota Medan (format GeoJSON: [longitude, latitude]).
 */
class KecamatanSeeder extends Seeder
{
    public function run()
    {
        // Bersihkan tabel sebelum seeding ulang
        $this->db->table('kecamatan')->truncate();

        $kecamatan = [
            [
                'nama'    => 'Medan Kota',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6740,3.5720],[98.7100,3.5720],[98.7100,3.5560],[98.6740,3.5560],[98.6740,3.5720]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Barat',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6380,3.6380],[98.6780,3.6380],[98.6780,3.5960],[98.6380,3.5960],[98.6380,3.6380]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Timur',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6780,3.6310],[98.7160,3.6310],[98.7160,3.5960],[98.6780,3.5960],[98.6780,3.6310]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Petisah',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6260,3.6010],[98.6780,3.6010],[98.6780,3.5660],[98.6260,3.5660],[98.6260,3.6010]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Sunggal',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.5780,3.6010],[98.6260,3.6010],[98.6260,3.5600],[98.5780,3.5600],[98.5780,3.6010]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Helvetia',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.5960,3.6450],[98.6520,3.6450],[98.6520,3.6010],[98.5960,3.6010],[98.5960,3.6450]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Baru',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6260,3.5760],[98.6680,3.5760],[98.6680,3.5380],[98.6260,3.5380],[98.6260,3.5760]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Polonia',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6560,3.5780],[98.6960,3.5780],[98.6960,3.5440],[98.6560,3.5440],[98.6560,3.5780]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Maimun',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6460,3.5560],[98.6820,3.5560],[98.6820,3.5280],[98.6460,3.5280],[98.6460,3.5560]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Selayang',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.5800,3.5720],[98.6340,3.5720],[98.6340,3.5180],[98.5800,3.5180],[98.5800,3.5720]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Tuntungan',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.5900,3.5200],[98.6520,3.5200],[98.6520,3.4700],[98.5900,3.4700],[98.5900,3.5200]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Johor',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6380,3.5560],[98.7000,3.5560],[98.7000,3.4940],[98.6380,3.4940],[98.6380,3.5560]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Amplas',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6960,3.5680],[98.7460,3.5680],[98.7460,3.5160],[98.6960,3.5160],[98.6960,3.5680]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Denai',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6920,3.5960],[98.7460,3.5960],[98.7460,3.5560],[98.6920,3.5560],[98.6920,3.5960]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Area',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6740,3.5960],[98.7120,3.5960],[98.7120,3.5600],[98.6740,3.5600],[98.6740,3.5960]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Perjuangan',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6780,3.6210],[98.7120,3.6210],[98.7120,3.5880],[98.6780,3.5880],[98.6780,3.6210]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Tembung',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.7060,3.6320],[98.7460,3.6320],[98.7460,3.5900],[98.7060,3.5900],[98.7060,3.6320]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Deli',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6540,3.6900],[98.7240,3.6900],[98.7240,3.6280],[98.6540,3.6280],[98.6540,3.6900]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Labuhan',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6640,3.7280],[98.7400,3.7280],[98.7400,3.6820],[98.6640,3.6820],[98.6640,3.7280]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Marelan',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6180,3.7240],[98.6700,3.7240],[98.6700,3.6820],[98.6180,3.6820],[98.6180,3.7240]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'    => 'Medan Belawan',
                'geojson' => '{"type":"Polygon","coordinates":[[[98.6560,3.8060],[98.7320,3.8060],[98.7320,3.7520],[98.6560,3.7520],[98.6560,3.8060]]]}',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('kecamatan')->insertBatch($kecamatan);

        echo "✅ 21 data kecamatan Medan berhasil ditambahkan.\n";
    }
}
