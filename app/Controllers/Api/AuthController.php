<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    /**
     * POST /api/login
     * Body: { username, password }
     */
    public function login(): ResponseInterface
    {
        try {
            $json = $this->request->getJSON(true);

            if (empty($json)) {
                return $this->failValidationError('Request body tidak boleh kosong.');
            }

            $username = trim($json['username'] ?? '');
            $password = trim($json['password'] ?? '');

            if (empty($username) || empty($password)) {
                return $this->failValidationError('Username dan password wajib diisi.');
            }

            $model = new UserModel();
            $user  = $model->findByCredentials($username, $password);

            if ($user === null) {
                return $this->failUnauthorized('Username atau password salah.');
            }

            return $this->respond([
                'status'  => 200,
                'message' => 'Login berhasil.',
                'data'    => [
                    'id'           => $user['id'],
                    'username'     => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'role'         => $user['role'],
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }
}
