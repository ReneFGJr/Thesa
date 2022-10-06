<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaConecptTerms extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ct' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'ct_th' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_concept' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_propriety' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_concept_2' => [
                'type' => 'INT',
                'default' => 0,
                'null' => true,
            ],
            'ct_resource' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_literal' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_use' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_ct', true);
        $this->forge->addForeignKey('ct_th', 'Thesa', 'id_th');
        $this->forge->createTable('thesa_concept_term');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_concept_term');
    }
}
