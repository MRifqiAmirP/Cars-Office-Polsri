<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableServiceRequest extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'kendaraan_id' => [
                'type' => 'INT', 
                'constraint' => 11,
                'unsigned' => true,
            ],
            'bengkel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'unsigned' => true
            ],
            'keluhan' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM("pending", "proses", "selesai")',
                'default' => 'pending',
            ],
            'file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kendaraan_id', 'cars', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('bengkel_id', 'mitra_bengkel', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('service_request');
    }

    public function down()
    {
        $this->forge->dropTable('service_request');
    }
}
