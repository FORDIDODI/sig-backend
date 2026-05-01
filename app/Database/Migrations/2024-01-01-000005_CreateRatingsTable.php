<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRatingsTable extends Migration
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
            'fasilitas_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => false,
            ],
            'nama_pengguna' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'rating' => [
                'type'       => 'SMALLINT',
                'null'       => false,
                'comment'    => '1-5',
            ],
            'ulasan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
                'default'    => 'web',
                'comment'    => 'web atau android',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('fasilitas_id');
        $this->forge->createTable('ratings');
    }

    public function down()
    {
        $this->forge->dropTable('ratings');
    }
}
