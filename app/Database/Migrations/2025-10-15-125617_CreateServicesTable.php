<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'kendaraan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true
            ],
            'bengkel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned'   => true
            ],
            'tanggal' => [
                'type' => 'DATE'
            ],
            'speedometer_yang_lalu' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'speedometer_saat_ini' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'total_harga' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true
            ],
            'file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'foto_nota' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'created_at'      => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'      => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bengkel_id', 'mitra_bengkel', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('services');
    }

    public function down()
    {
        $this->forge->dropTable('services');
    }
}
