<?php

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table            = 'ratings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'fasilitas_id',
        'nama_pengguna',
        'rating',
        'ulasan',
        'platform',
        'created_at',
    ];

    protected $validationRules = [
        'fasilitas_id'  => 'required|integer',
        'nama_pengguna' => 'required|min_length[2]|max_length[100]',
        'rating'        => 'required|integer|greater_than[0]|less_than[6]',
        'ulasan'        => 'permit_empty|max_length[1000]',
        'platform'      => 'permit_empty|in_list[web,android]',
    ];

    protected $validationMessages = [
        'nama_pengguna' => [
            'required'   => 'Nama pengguna wajib diisi.',
            'min_length' => 'Nama minimal 2 karakter.',
        ],
        'rating' => [
            'required'     => 'Rating wajib diisi.',
            'integer'      => 'Rating harus berupa angka.',
            'greater_than' => 'Rating minimal 1.',
            'less_than'    => 'Rating maksimal 5.',
        ],
    ];

    /**
     * Ambil semua rating untuk fasilitas tertentu
     */
    public function getRatingsByFasilitas(int $fasilitasId): array
    {
        return $this
            ->where('fasilitas_id', $fasilitasId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Hitung rata-rata rating fasilitas tertentu
     */
    public function getAvgRating(int $fasilitasId): array
    {
        $row = $this->db->table('ratings')
            ->select('AVG(rating) as avg_rating, COUNT(*) as total_ulasan')
            ->where('fasilitas_id', $fasilitasId)
            ->get()
            ->getRowArray();

        return [
            'avg_rating'   => $row['avg_rating'] ? round((float)$row['avg_rating'], 1) : null,
            'total_ulasan' => (int)($row['total_ulasan'] ?? 0),
        ];
    }

    /**
     * Rata-rata rating untuk banyak fasilitas sekaligus (untuk listing)
     */
    public function getAvgRatingBulk(array $fasilitasIds): array
    {
        if (empty($fasilitasIds)) return [];

        $rows = $this->db->table('ratings')
            ->select('fasilitas_id, AVG(rating) as avg_rating, COUNT(*) as total_ulasan')
            ->whereIn('fasilitas_id', $fasilitasIds)
            ->groupBy('fasilitas_id')
            ->get()
            ->getResultArray();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['fasilitas_id']] = [
                'avg_rating'   => round((float)$row['avg_rating'], 1),
                'total_ulasan' => (int)$row['total_ulasan'],
            ];
        }
        return $result;
    }
}
