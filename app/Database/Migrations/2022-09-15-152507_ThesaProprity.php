<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaConecptPropity extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_p' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'p_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'p_reverse' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'p_equivalente' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'p_range' => [
                'type' => 'INT',
                'null' => true,
                'default' => 0,
            ],
            'p_group' => [
                'type' => 'INT',
                'null' => true,
                'default' => 0,
            ],
            'p_description' => [
                'type'       => 'TEXT',
            ],
            'p_th' => [
                'type' => 'INT',
                'null' => true,
            ],
            'p_global' => [
                'type' => 'INT',
                'null' => true,
            ],
            'p_part_1' => [
                'type' => 'INT',
                'null' => true,
            ],
            'p_part_2' => [
                'type' => 'INT',
                'null' => true,
            ],
            'p_part_3' => [
                'type' => 'INT',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_p', true);
        $this->forge->createTable('Thesa_property');

        $data = array();
        $data['p_name'] = 'broader';
        $data['p_reverse'] = 'narrower';
        $data['p_description'] = '';
        $data['p_group'] = 10;
        $data['p_range'] = 2;
        $data['p_th'] = 0;
        $data['p_global'] = 1;
        $data['p_part_1'] = 0;
        $data['p_part_2'] = 0;
        $data['p_part_3'] = 0;
        $this->db->table('Thesa_property')->insert($data);

        /* Remove Reverses */
        $data['p_reverse'] = '';

        $labels = array('prefLabel','altLabel','hiddenLabel');
        for ($r = 0; $r < count($labels); $r++) {
            $data['p_range'] = 1;
            $data['p_name'] = $labels[$r];
            $data['p_group'] = 20 + $r;
            $this->db->table('Thesa_property')->insert($data);
        }


        $notes = array('labels', 'scopeNote','notation','note','changeNote', 'editorialNote', 'example', 'historyNote');
        for ($r=0;$r < count($notes);$r++)
            {
                $data['p_range'] = 3;
                $data['p_name'] = $notes[$r];
                $data['p_group'] = 50+$r;
                $this->db->table('Thesa_property')->insert($data);
            }


    }

    public function down()
    {
        $this->forge->dropTable('Thesa_property');
    }

}
