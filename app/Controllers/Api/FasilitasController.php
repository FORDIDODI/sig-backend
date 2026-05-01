<?php

namespace App\Controllers\Api;

use App\Models\FasilitasModel;
use App\Models\RatingModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class FasilitasController extends ResourceController
{
    protected $modelName = FasilitasModel::class;
    protected $format    = 'json';

    /**
     * GET /api/fasilitas
     * GET /api/fasilitas?jenis=puskesmas
     * GET /api/fasilitas?kecamatan_id=4
     * GET /api/fasilitas?jenis=puskesmas&kecamatan_id=4
     */
    public function index(): ResponseInterface
    {
        try {
            $jenis       = $this->request->getGet('jenis');
            $kecamatanId = $this->request->getGet('kecamatan_id');

            $fasilitas = $this->model->getFasilitas(
                $jenis       ? trim($jenis) : null,
                $kecamatanId ? (int)$kecamatanId : null
            );

            // Tambahkan avg_rating & total_ulasan ke setiap fasilitas
            $ratingModel = new RatingModel();
            $ids = array_column($fasilitas, 'id');
            $ratings = $ratingModel->getAvgRatingBulk($ids);

            foreach ($fasilitas as &$f) {
                $r = $ratings[$f['id']] ?? null;
                $f['avg_rating']   = $r ? $r['avg_rating'] : null;
                $f['total_ulasan'] = $r ? $r['total_ulasan'] : 0;
            }
            unset($f);

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
     */
    public function create(): ResponseInterface
    {
        try {
            $json = $this->request->getJSON(true);

            if (empty($json)) {
                return $this->failValidationError('Request body tidak boleh kosong. Kirim data dalam format JSON.');
            }

            $data = [
                'nama'            => trim($json['nama'] ?? ''),
                'jenis'           => trim($json['jenis'] ?? ''),
                'alamat'          => trim($json['alamat'] ?? ''),
                'kecamatan_id'    => isset($json['kecamatan_id']) ? (int)$json['kecamatan_id'] : null,
                'latitude'        => $json['latitude'] ?? null,
                'longitude'       => $json['longitude'] ?? null,
                'deskripsi'       => trim($json['deskripsi'] ?? ''),
                'jam_operasional' => trim($json['jam_operasional'] ?? ''),
                'telepon'         => trim($json['telepon'] ?? ''),
                'foto_url'        => trim($json['foto_url'] ?? ''),
                'created_at'      => date('Y-m-d H:i:s'),
            ];

            if (! $this->model->validate($data)) {
                return $this->failValidationErrors($this->model->errors());
            }

            $id = $this->model->insert($data, true);

            if ($id === false) {
                return $this->failServerError('Gagal menyimpan data ke database.');
            }

            $saved = $this->model->getFasilitasDetail($id);

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
            $fasilitas = $this->model->getFasilitasDetail((int)$id);

            if ($fasilitas === null) {
                return $this->failNotFound("Fasilitas dengan ID {$id} tidak ditemukan.");
            }

            // Tambahkan avg_rating
            $ratingModel   = new RatingModel();
            $ratingData    = $ratingModel->getAvgRating((int)$id);
            $fasilitas['avg_rating']   = $ratingData['avg_rating'];
            $fasilitas['total_ulasan'] = $ratingData['total_ulasan'];

            return $this->respond([
                'status'  => 200,
                'message' => 'Data fasilitas ditemukan.',
                'data'    => $fasilitas,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    /**
     * GET /api/fasilitas/{id}/ratings
     */
    public function ratings($id = null): ResponseInterface
    {
        try {
            $ratingModel = new RatingModel();

            // Cek fasilitas ada
            $fasilitas = $this->model->find((int)$id);
            if (!$fasilitas) {
                return $this->failNotFound("Fasilitas dengan ID {$id} tidak ditemukan.");
            }

            $ratings   = $ratingModel->getRatingsByFasilitas((int)$id);
            $ratingAvg = $ratingModel->getAvgRating((int)$id);

            return $this->respond([
                'status'       => 200,
                'message'      => 'Data rating berhasil diambil.',
                'avg_rating'   => $ratingAvg['avg_rating'],
                'total_ulasan' => $ratingAvg['total_ulasan'],
                'data'         => $ratings,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/fasilitas/{id}/ratings
     */
    public function createRating($id = null): ResponseInterface
    {
        try {
            // Cek fasilitas ada
            $fasilitas = $this->model->find((int)$id);
            if (!$fasilitas) {
                return $this->failNotFound("Fasilitas dengan ID {$id} tidak ditemukan.");
            }

            $json = $this->request->getJSON(true);

            if (empty($json)) {
                return $this->failValidationError('Request body tidak boleh kosong.');
            }

            $data = [
                'fasilitas_id'  => (int)$id,
                'nama_pengguna' => trim($json['nama_pengguna'] ?? ''),
                'rating'        => isset($json['rating']) ? (int)$json['rating'] : null,
                'ulasan'        => trim($json['ulasan'] ?? ''),
                'platform'      => in_array($json['platform'] ?? '', ['web','android'])
                                    ? $json['platform']
                                    : 'web',
                'created_at'    => date('Y-m-d H:i:s'),
            ];

            $ratingModel = new RatingModel();

            if (! $ratingModel->validate($data)) {
                return $this->failValidationErrors($ratingModel->errors());
            }

            $newId = $ratingModel->insert($data, true);

            if ($newId === false) {
                return $this->failServerError('Gagal menyimpan rating.');
            }

            $saved = $ratingModel->find($newId);

            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Rating berhasil ditambahkan. Terima kasih atas ulasan Anda!',
                'data'    => $saved,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }
}
