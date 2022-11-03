<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

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
            'ul_access' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null' => true,
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
