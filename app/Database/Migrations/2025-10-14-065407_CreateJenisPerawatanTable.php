<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJenisPerawatanTable extends Migration
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
            'jenis_perawatan' => [
                'type' => 'VARCHAR',
                'constraint' => 50
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
        $this->forge->createTable('jenis_perawatan');
    }

    public function down()
    {
        $this->forge->dropTable('jenis_perawatan');
    }
}
