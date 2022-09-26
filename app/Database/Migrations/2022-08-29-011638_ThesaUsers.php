<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_th_us' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'th_us_user' => [
                'type' => 'INT',
                'null' => true,
            ],
            'th_us_th' => [
                'type' => 'INT',
                'null' => true,
            ],
            'th_us_perfil' => [
                'type' => 'INT',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_th_us', true);
        $this->forge->addForeignKey('th_us_user', 'Users', 'id_us');
        $this->forge->addForeignKey('th_us_th', 'Thesa', 'id_th');
        $this->forge->addForeignKey('th_us_perfil', 'Thesa_Perfil', 'id_perfil');
        $this->forge->createTable('Thesa_Users');

        $data = array(
            'th_us_user' => '1',
            'th_us_th' => '1',
            'th_us_perfil' => '1',
        );
        $this->db->table('thesa_users')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('Thesa_Users');
    }
}
