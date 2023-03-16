<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class UsersPerfil extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pe' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pe_abrev' => [
                'type'       => 'VARCHAR',
                'constraint'     => 4,
                'null' => true,
            ],
            'pe_descricao' => [
                'type'       => 'VARCHAR',
                'constraint'     => 100,
                'null' => true,
            ],
            'pe_nivel' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'created_ar' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_pe', true);
        $this->forge->createTable('users_perfil');
    }

    public function down()
    {
        $this->forge->dropTable('users_perfil');
    }
}
