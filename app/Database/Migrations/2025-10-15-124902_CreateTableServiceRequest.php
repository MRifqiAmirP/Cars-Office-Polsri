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
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'kendaraan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'bengkel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'keluhan' => [
                'type' => 'TEXT'
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
