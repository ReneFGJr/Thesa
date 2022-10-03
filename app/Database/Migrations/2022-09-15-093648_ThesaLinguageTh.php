<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaLinguageTh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_lgt' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lgt_th' => [
                'type' => 'INT',
                'null' => true,
            ],
            'lgt_language' => [
                'type' => 'INT',
                'null' => true,
            ],
            'lgt_order' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_lgt', true);
        $this->forge->createTable('thesa_language');

        $data = array();
        $data['lgt_th'] = 1;
        $data['lgt_language'] = '3';
        $data['lgt_order'] = '1';
        $this->db->table('thesa_language')->insert($data);

        $data = array();
        $data['lgt_language'] = '1';
        $data['lgt_order'] = '2';
        $this->db->table('thesa_language')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('thesa_language');
    }
}
