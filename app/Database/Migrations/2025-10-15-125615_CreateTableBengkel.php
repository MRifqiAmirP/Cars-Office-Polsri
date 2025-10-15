<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableBengkel extends Migration
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
            'nama_bengkel' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'alamat_bengkel' => [
                'type' => 'TEXT',
            ],
            'telepon_bengkel' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_kontak_bengkel' => [
                'type' => 'VARCHAR',
                'constraint' => 40,
            ],
            'telepon_kontak_bengkel' => [
                'type' => 'VARCHAR',
                'constraint' => 20
            ],
            'latitude' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true,
            ],
            'longitude' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => true,
            ],
            'file_siup' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'file_situ' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'file_perjanjian_kerjasama' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'nilai_kelayakan' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Nilai kelayakan dari PPA (0-100)',
            ],
            'status_kelayakan' => [
                'type' => 'ENUM',
                'constraint' => ['layak', 'tidak_layak', 'proses_penilaian'],
                'default' => 'proses_penilaian',
            ],
            'tanggal_penilaian' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'keterangan_penilaian' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status_aktif' => [
                'type' => 'ENUM',
                'constraint' => ['aktif', 'nonaktif', 'proses'],
                'default' => 'proses',
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('email');
        $this->forge->addKey('status_aktif');
        $this->forge->addKey('status_kelayakan');
        $this->forge->createTable('mitra_bengkel');
    }

    public function down()
    {
        $this->forge->dropTable('mitra_bengkel');
    }
}