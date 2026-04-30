<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFasilitasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => ['puskesmas', 'damkar', 'taman'],
                'null'       => false,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => false,
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('fasilitas');
    }

    public function down()
    {
        $this->forge->dropTable('fasilitas');
    }
}
