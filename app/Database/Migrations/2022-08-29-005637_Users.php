<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_us' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'us_nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'us_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'us_image' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'us_genero' => [
                'type'       => 'VARCHAR',
                'constraint' => '1',
            ],
            'us_verificado' => [
                'type'       => 'VARCHAR',
                'constraint' => '1',
            ],
            'us_login' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'us_password' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            '	us_password_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
            ],
            '	us_lastaccess' => [
                'type'       => 'DATETIME',
            ],
            'us_apikey' => [
                'type'       => 'VARCHAR',
                'constraint' => '40',
            ],
            'us_apikey_active' => [
                'type'           => 'INT',
                'constraint'     => 5,
            ],
            'us_apikey_date' => [
                'type'       => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id_us', true);
        $this->forge->createTable('Users');
    }

    public function down()
    {
        //
    }
}
