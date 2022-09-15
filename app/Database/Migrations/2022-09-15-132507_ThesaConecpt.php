<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaConecpt extends Migration
{
    public function up()
        {
         $this->forge->addField([
            'id_c' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'c_concept' => [
                'type' => 'INT',
                'null' => true,
            ],
            'c_th' => [
                'type' => 'INT',
                'null' => true,
            ],
            'c_ativo' => [
                'type' => 'INT',
                'null' => true,
            ],
            'c_agency' => [
                'type' => 'INT',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_c', true);
        $this->forge->addForeignKey('c_th', 'Thesa', 'id_th');
        $this->forge->createTable('Thesa_concept');
    }

    public function down()
    {
        $this->forge->dropTable('Thesa_concept');
    }
}
