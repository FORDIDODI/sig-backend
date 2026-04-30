<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'     => 'admin',
                'password'     => password_hash('admin123', PASSWORD_BCRYPT),
                'role'         => 'admin',
                'nama_lengkap' => 'Administrator',
                'created_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'user1',
                'password'     => password_hash('user123', PASSWORD_BCRYPT),
                'role'         => 'user',
                'nama_lengkap' => 'Pengguna Biasa',
                'created_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);

        echo "✅ Akun default berhasil dibuat:\n";
        echo "   Admin  → username: admin   | password: admin123\n";
        echo "   User   → username: user1   | password: user123\n";
    }
}
