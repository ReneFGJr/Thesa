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

        $data = array(
            'lg_code' => 'eng',
            'lg_language' => 'English',
            'lg_order' => 2,
            'lg_active' => 1,
            'lg_cod_marc' => 'eng'
        );
        $this->db->table('language')->insert($data);

        $data = array(
            'lg_code' => 'ger',
            'lg_language' => 'Alemão / German',
            'lg_order' => 9,
            'lg_active' => 1,
            'lg_cod_marc' => 'ger'
        );
        $this->db->table('language')->insert($data);

        $data = array(
            'lg_code' => 'por',
            'lg_language' => 'Portuguese(Brazil)',
            'lg_order' => 1,
            'lg_active' => 1,
            'lg_cod_marc' => 'por'
        );
        $this->db->table('language')->insert($data);

        $data = array(
            'lg_code' => 'fre',
            'lg_language' => 'French',
            'lg_order' => 6,
            'lg_active' => 1,
            'lg_cod_marc' => 'fre'
        );
        $this->db->table('language')->insert($data);

        $data = array(
            'lg_code' => 'spa',
            'lg_language' => 'Español',
            'lg_order' => 2,
            'lg_active' => 1,
            'lg_cod_marc' => 'spa'
        );
        $this->db->table('language')->insert($data);



    }

    public function down()
    {
        $this->forge->dropTable('language');
    }
}
