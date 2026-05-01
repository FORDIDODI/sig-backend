<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateFasilitasTable extends Migration
{
    public function up()
    {
        // Tambah kolom kecamatan_id (FK ke tabel kecamatan)
        $this->forge->addColumn('fasilitas', [
            'kecamatan_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => true,
                'after'    => 'alamat',
            ],
        ]);

        // Tambah kolom deskripsi
        $this->forge->addColumn('fasilitas', [
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'kecamatan_id',
            ],
        ]);

        // Tambah kolom jam_operasional
        $this->forge->addColumn('fasilitas', [
            'jam_operasional' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'deskripsi',
            ],
        ]);

        // Tambah kolom telepon
        $this->forge->addColumn('fasilitas', [
            'telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'jam_operasional',
            ],
        ]);

        // Tambah kolom foto_url
        $this->forge->addColumn('fasilitas', [
            'foto_url' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'telepon',
            ],
        ]);

        // Tambah kolom created_at
        $this->forge->addColumn('fasilitas', [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'foto_url',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('fasilitas', 'kecamatan_id');
        $this->forge->dropColumn('fasilitas', 'deskripsi');
        $this->forge->dropColumn('fasilitas', 'jam_operasional');
        $this->forge->dropColumn('fasilitas', 'telepon');
        $this->forge->dropColumn('fasilitas', 'foto_url');
        $this->forge->dropColumn('fasilitas', 'created_at');
    }
}
