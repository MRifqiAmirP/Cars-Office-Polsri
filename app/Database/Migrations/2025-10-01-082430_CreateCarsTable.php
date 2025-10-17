<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCarsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'         => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'foto_kendaraan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'nopol'           => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
            ],
            'merk'            => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'type'            => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'no_bpkb'         => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'no_mesin'        => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'no_rangka'       => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'tahun_pembuatan' => [
                'type'       => 'INT',
                'constraint' => 4,
            ],
            'keterangan'      => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at'      => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'      => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at'      => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cars');
    }

    public function down()
    {
        $this->forge->dropTable('cars');
    }
}
