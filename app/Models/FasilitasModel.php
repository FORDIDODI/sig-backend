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

    protected $allowedFields = [
        'nama',
        'jenis',
        'alamat',
        'latitude',
        'longitude',
    ];

    protected $useTimestamps = false;

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
     * Ambil semua fasilitas, bisa filter berdasarkan jenis
     */
    public function getFasilitas(?string $jenis = null): array
    {
        if ($jenis !== null && in_array($jenis, ['puskesmas', 'damkar', 'taman'])) {
            return $this->where('jenis', $jenis)->findAll();
        }

        return $this->findAll();
    }
}
