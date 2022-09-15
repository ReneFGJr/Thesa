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
            'ct_concept' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_th' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_term' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_use' => [
                'type' => 'INT',
                'null' => true,
            ],
            'ct_propriety' => [
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
