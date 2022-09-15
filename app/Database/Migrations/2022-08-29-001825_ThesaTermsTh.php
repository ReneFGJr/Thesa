<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaTermsTh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'term_th_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'term_th_thesa' => [
                'type' => 'INT',
                'null' => true,
            ],
            'term_th_term' => [
                'type' => 'INT',
                'null' => true,
            ],
            'term_th_concept' => [
                'type' => 'INT',
                'null' => true,
                'default' => 0,
            ],

        ]);
        $this->forge->addKey('term_th_id', true);
        $this->forge->addForeignKey('term_th_term', 'Thesa_Terms', 'term_id');
        $this->forge->addForeignKey('term_th_thesa', 'Thesa', 'id_th');
        $this->forge->createTable('Thesa_Terms_Th');
    }

    public function down()
    {
        $this->forge->dropTable('Thesa_Terms_Th');
    }
}
