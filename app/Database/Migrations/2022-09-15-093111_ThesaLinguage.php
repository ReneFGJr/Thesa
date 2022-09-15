<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaLinguage extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_lg' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lg_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
            ],
            'lg_language' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'lg_order' => [
                'type' => 'INT',
                'null' => true,
            ],
            'lg_active' => [
                'type' => 'INT',
                'null' => true,
            ],
            'lg_cod_marc' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
            ],
        ]);
        $this->forge->addKey('id_lg', true);
        $this->forge->createTable('language');
    }

    public function down()
    {
        $this->forge->dropTable('language');
    }
}
