<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersLog extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ul' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ul_user' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'ul_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => '40',
            ],
        ]);
        $this->forge->addKey('id_ul', true);
        $this->forge->createTable('Users_log');
    }

    public function down()
    {
        $this->forge->dropTable('Users_log');
    }
}
