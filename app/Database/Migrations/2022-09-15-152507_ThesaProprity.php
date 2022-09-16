<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaConecptPropity extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_p' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'p_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'p_description' => [
                'type'       => 'TEXT',
            ],
            'p_part_1' => [
                'type' => 'INT',
                'null' => true,
            ],
            'p_part_2' => [
                'type' => 'INT',
                'null' => true,
            ],
            'p_part_3' => [
                'type' => 'INT',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_p', true);
        $this->forge->createTable('Thesa_property');
    }

    public function down()
    {
        $this->forge->dropTable('Thesa_property');
    }

}
