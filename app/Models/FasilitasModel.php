<?php

namespace App\Models;

use CodeIgniter\Model;

class FasilitasModel extends Model
{
    protected $table            = 'fasilitas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'nama',
        'jenis',
        'alamat',
        'kecamatan_id',
        'latitude',
        'longitude',
        'deskripsi',
        'jam_operasional',
        'telepon',
        'foto_url',
        'created_at',
    ];

    protected $validationRules = [
        'nama'      => 'required|min_length[3]|max_length[100]',
        'jenis'     => 'required|in_list[puskesmas,damkar,taman]',
        'alamat'    => 'permit_empty|max_length[500]',
        'latitude'  => 'required|decimal',
        'longitude' => 'required|decimal',
    ];

    protected $validationMessages = [
        'nama' => [
            'required'   => 'Nama fasilitas wajib diisi.',
            'min_length' => 'Nama fasilitas minimal 3 karakter.',
            'max_length' => 'Nama fasilitas maksimal 100 karakter.',
        ],
        'jenis' => [
            'required' => 'Jenis fasilitas wajib diisi.',
            'in_list'  => 'Jenis fasilitas harus salah satu dari: puskesmas, damkar, taman.',
        ],
        'latitude' => [
            'required' => 'Latitude wajib diisi.',
            'decimal'  => 'Latitude harus berupa angka desimal.',
        ],
        'longitude' => [
            'required' => 'Longitude wajib diisi.',
            'decimal'  => 'Longitude harus berupa angka desimal.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Ambil semua fasilitas dengan JOIN kecamatan.
     * Support filter jenis dan/atau kecamatan_id.
     */
    public function getFasilitas(?string $jenis = null, ?int $kecamatanId = null): array
    {
        $builder = $this->db->table('fasilitas f')
            ->select('f.*, k.nama AS nama_kecamatan')
            ->join('kecamatan k', 'k.id = f.kecamatan_id', 'left');

        if ($jenis !== null && in_array($jenis, ['puskesmas', 'damkar', 'taman'])) {
            $builder->where('f.jenis', $jenis);
        }

        if ($kecamatanId !== null) {
            $builder->where('f.kecamatan_id', $kecamatanId);
        }

        $builder->orderBy('f.nama', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Ambil detail satu fasilitas dengan nama kecamatan.
     */
    public function getFasilitasDetail(int $id): ?array
    {
        $result = $this->db->table('fasilitas f')
            ->select('f.*, k.nama AS nama_kecamatan')
            ->join('kecamatan k', 'k.id = f.kecamatan_id', 'left')
            ->where('f.id', $id)
            ->get()
            ->getRowArray();

        return $result ?: null;
    }

    /**
     * Ambil fasilitas berdasarkan kecamatan_id
     */
    public function getByKecamatan(int $kecamatanId): array
    {
        return $this->db->table('fasilitas f')
            ->select('f.*, k.nama AS nama_kecamatan')
            ->join('kecamatan k', 'k.id = f.kecamatan_id', 'left')
            ->where('f.kecamatan_id', $kecamatanId)
            ->orderBy('f.nama', 'ASC')
            ->get()
            ->getResultArray();
    }
}
