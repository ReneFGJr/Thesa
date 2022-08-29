<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaStatus extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_status' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'status_description' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_status', true);
        $this->forge->createTable('Thesa_Status');
    }

    public function down()
    {
        //
    }
}
