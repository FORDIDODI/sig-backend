<?php

namespace App\Controllers\Api;

use App\Models\KecamatanModel;
use App\Models\FasilitasModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class KecamatanController extends ResourceController
{
    protected $format = 'json';

    /**
     * GET /api/kecamatan
     * Daftar semua kecamatan beserta polygon GeoJSON
     */
    public function index(): ResponseInterface
    {
        try {
            $model     = new KecamatanModel();
            $kecamatan = $model->getAllKecamatan();

            // Parse geojson string menjadi object agar response JSON bersih
            foreach ($kecamatan as &$k) {
                if (!empty($k['geojson'])) {
                    $k['geojson'] = json_decode($k['geojson']);
                }
            }
            unset($k);

            return $this->respond([
                'status'  => 200,
                'message' => 'Data kecamatan berhasil diambil.',
                'total'   => count($kecamatan),
                'data'    => $kecamatan,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    /**
     * GET /api/kecamatan/{id}/fasilitas
     * Fasilitas dalam kecamatan tertentu
     */
    public function fasilitas(int $id): ResponseInterface
    {
        try {
            $kecamatanModel  = new KecamatanModel();
            $fasilitasModel  = new FasilitasModel();

            $kecamatan = $kecamatanModel->find($id);
            if (!$kecamatan) {
                return $this->failNotFound("Kecamatan dengan ID {$id} tidak ditemukan.");
            }

            $fasilitas = $fasilitasModel->getByKecamatan($id);

            return $this->respond([
                'status'     => 200,
                'message'    => 'Data fasilitas kecamatan berhasil diambil.',
                'kecamatan'  => $kecamatan['nama'],
                'total'      => count($fasilitas),
                'data'       => $fasilitas,
            ]);
        } catch (\Throwable $e) {
            return $this->failServerError('Terjadi kesalahan server: ' . $e->getMessage());
        }
    }
}
