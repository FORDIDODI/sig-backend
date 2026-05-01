<?php

namespace App\Models;

use CodeIgniter\Model;

class KecamatanModel extends Model
{
    protected $table            = 'kecamatan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = ['nama', 'geojson', 'created_at'];

    /**
     * Ambil semua kecamatan (id + nama + geojson)
     */
    public function getAllKecamatan(): array
    {
        return $this->select('id, nama, geojson')->orderBy('nama', 'ASC')->findAll();
    }

    /**
     * Ambil kecamatan berdasarkan nama (case-insensitive)
     */
    public function getByNama(string $nama): ?array
    {
        return $this->where('LOWER(nama)', strtolower($nama))->first();
    }
}
