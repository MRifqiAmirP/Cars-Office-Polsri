<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicePerawatanPivotTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'service_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jenis_perawatan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);

        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jenis_perawatan_id', 'jenis_perawatan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('service_jenis_perawatan_pivots');
    }

    public function down()
    {
        $this->forge->dropTable('service_jenis_perawatan_pivots');
    }
}
