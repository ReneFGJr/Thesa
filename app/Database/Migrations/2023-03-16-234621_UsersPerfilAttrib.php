<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class UsersPerfilAttrib extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pa' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pa_user' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'pa_perfil' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'pa_check' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'pa_created' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_pa', true);
        $this->forge->createTable('users_perfil_attrib');
    }

    public function down()
    {
        $this->forge->dropTable('users_perfil_attrib');
    }
}
