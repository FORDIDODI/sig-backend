<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'      => 'Puskesmas Sukajadi',
                'jenis'     => 'puskesmas',
                'alamat'    => 'Jl. Sukajadi No. 12, Pekanbaru, Riau',
                'latitude'  => 0.50754000,
                'longitude' => 101.44475000,
            ],
            [
                'nama'      => 'Pemadam Kebakaran Kota Pekanbaru',
                'jenis'     => 'damkar',
                'alamat'    => 'Jl. Sudirman No. 45, Pekanbaru, Riau',
                'latitude'  => 0.52631000,
                'longitude' => 101.44783000,
            ],
            [
                'nama'      => 'Taman Kota Pekanbaru',
                'jenis'     => 'taman',
                'alamat'    => 'Jl. Diponegoro No. 1, Pekanbaru, Riau',
                'latitude'  => 0.51218000,
                'longitude' => 101.45123000,
            ],
        ];

        $this->db->table('fasilitas')->insertBatch($data);

        echo "✅ 3 data fasilitas dummy berhasil ditambahkan.\n";
    }
}