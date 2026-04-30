<?php

namespace App\Controllers\Api;

use App\Models\FasilitasModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class FasilitasController extends ResourceController
{
    protected $modelName = FasilitasModel::class;
    protected $format    = 'json';

    /**
     * GET /api/fasilitas
     * GET /api/fasilitas?jenis=puskesmas
     */
    public function index(): ResponseInterface
    {
        try {
            $jenis     = $this->request->getGet('jenis');
            $fasilitas = $this->model->getFasilitas($jenis ?: null);

            return $this->respond([
                'status'  => 200,
                'message' => 'Data fasilitas berhasil diambil.',
                'total'   => count($fasilitas),
                'data'    => $fasilitas,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/fasilitas
     * Body (JSON): { nama, jenis, alamat, latitude, longitude }
     */
    public function create(): ResponseInterface
    {
        try {
            $json = $this->request->getJSON(true);

            if (empty($json)) {
                return $this->failValidationError('Request body tidak boleh kosong. Kirim data dalam format JSON.');
            }

            $data = [
                'nama'      => trim($json['nama'] ?? ''),
                'jenis'     => trim($json['jenis'] ?? ''),
                'alamat'    => trim($json['alamat'] ?? ''),
                'latitude'  => $json['latitude'] ?? null,
                'longitude' => $json['longitude'] ?? null,
            ];

            // Jalankan validasi manual
            if (! $this->model->validate($data)) {
                return $this->failValidationErrors($this->model->errors());
            }

            $id = $this->model->insert($data, true);

            if ($id === false) {
                return $this->failServerError('Gagal menyimpan data ke database.');
            }

            $saved = $this->model->find($id);

            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Data fasilitas berhasil ditambahkan.',
                'data'    => $saved,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    /**
     * GET /api/fasilitas/{id}
     */
    public function show($id = null): ResponseInterface
    {
        try {
            $fasilitas = $this->model->find($id);

            if ($fasilitas === null) {
                return $this->failNotFound("Fasilitas dengan ID {$id} tidak ditemukan.");
            }

            return $this->respond([
                'status'  => 200,
                'message' => 'Data fasilitas ditemukan.',
                'data'    => $fasilitas,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }
}
