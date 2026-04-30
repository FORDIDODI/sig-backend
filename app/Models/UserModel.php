<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'username',
        'password',
        'role',
        'nama_lengkap',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * Cari user berdasarkan username dan verifikasi password
     */
    public function findByCredentials(string $username, string $password): ?array
    {
        $user = $this->where('username', $username)->first();

        if ($user === null) return null;
        if (! password_verify($password, $user['password'])) return null;

        // Jangan kembalikan password ke client
        unset($user['password']);
        return $user;
    }
}
