<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaConecptPropityType extends Migration
{
    public function up()
        {
         $this->forge->addField([
            'id_pt' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pt_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'pt_name_reverse' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'pt_description' => [
                'type'       => 'TEXT',
            ],
            'pt_part_1' => [
                'type' => 'INT',
                'null' => true,
            ],
            'pt_part_2' => [
                'type' => 'INT',
                'null' => true,
            ],
            'pt_part_3' => [
                'type' => 'INT',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_pt', true);
        $this->forge->createTable('thesa_property_type');

        $data = array(
            'pt_name' => 'has',
            'pt_name_reverse' => 'has',
            'pt_description' => 'administrador',
            'pt_part_1' => 1,
            'pt_part_2' => 0,
            'pt_part_3' => 0,
        );
        $this->db->table('thesa_property_type')->insert($data);
        /*************** IS */
        $data['pt_name'] = 'is';
        $data['pt_name_reverse'] = 'is';
        $this->db->table('thesa_property_type')->insert($data);



        /*************** INSTANCE */
        $data['pt_part_1'] = 0;
        $data['pt_part_2'] = 1;
        $data['pt_part_3'] = 0;
        $data['pt_name'] = 'instance';
        $data['pt_name_reverse'] = 'instance';
        $this->db->table('thesa_property_type')->insert($data);

        $data['pt_name'] = 'part';
        $data['pt_name_reverse'] = 'part';
        $this->db->table('thesa_property_type')->insert($data);

        $data['pt_name'] = 'image';
        $data['pt_name_reverse'] = 'image';
        $this->db->table('thesa_property_type')->insert($data);


        /*************** INSTANCE */
        $data['pt_part_1'] = 0;
        $data['pt_part_2'] = 0;
        $data['pt_part_3'] = 1;
        $data['pt_name'] = 'of';
        $data['pt_name_reverse'] = 'of';
        $this->db->table('thesa_property_type')->insert($data);


    }

    public function down()
    {
        $this->forge->dropTable('thesa_property_type');
    }

}
