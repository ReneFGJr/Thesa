<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaPerfil extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_perfil' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'perfil_description' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'perfil_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_perfil', true);
        $this->forge->createTable('Thesa_Perfil');
    }

    public function down()
    {
        //
    }
}
