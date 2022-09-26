<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ThesaUsersPrefil extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pf' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pf_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'pf_nivel' => [
                'type'       => 'char',
                'constraint' => '20',
                'default' => '00000000000000000000',
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_pf', true);
        $this->forge->createTable('thesa_users_perfil');

        $data = array(
            'pf_name' => 'thesa.author',
            'pf_nivel' => '11111111111111111110',
            'updated_at'=>date("Y-m-d H:i:s"),
        );
        $this->db->table('thesa_users_perfil')->insert($data);

        $data = array(
            'pf_name' => 'thesa.author',
            'pf_nivel' => '11111111111111111110',
            'updated_at' => date("Y-m-d H:i:s"),
        );
        $this->db->table('thesa_users_perfil')->insert($data);

        $data = array(
            'pf_name' => 'thesa.collaborator',
            'pf_nivel' => '11111000000000000000',
            'updated_at' => date("Y-m-d H:i:s"),
        );
        $this->db->table('thesa_users_perfil')->insert($data);

        $data = array(
            'pf_name' => 'thesa.advisor',
            'pf_nivel' => '00000111111111100000',
            'updated_at' => date("Y-m-d H:i:s"),
        );
        $this->db->table('thesa_users_perfil')->insert($data);

        $data = array(
            'pf_name' => 'thesa.guest',
            'pf_nivel' => '00000111110000000000',
            'updated_at' => date("Y-m-d H:i:s"),
        );
        $this->db->table('thesa_users_perfil')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('thesa_users_perfil');
    }
}
