<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ThesaPropertyCustom extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pcst' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pcst_class' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'pcst_type' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'pcst_th' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'pcst_achronic' => [
                'type'       => 'VARCHAR',
                'constraint' => '1200',
                'null' => true,
            ],
            'pcst_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'pcst_description' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'pcst_part_1' => [
                'type'       => 'INT',
                'default' => '20',
                'null' => true,
            ],
            'pcst_part_2' => [
                'type'       => 'INT',
                'default' => '0',
                'null' => true,
            ],
            'pcst_part_3' => [
                'type'       => 'INT',
                'default' => '0',
                'null' => true,
            ],
            'pcst_public' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'pcst_aplicable' => [
                'type'       => 'INT',
                'null' => true,
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
        $this->forge->addKey('id_pcst', true);
        $this->forge->createTable('thesa_property_custom');

        $data = array(
            'pcst_class' => 1,
            'pcst_type' => 1,
            'pcst_th' => '1',
            'pcst_achronic' => 'abbreviation_of',
            'pcst_name' => 'é abreviatura de',
            'pcst_description' => '-',
            'pcst_part_1' => 1,
            'pcst_part_2' => 7,
            'pcst_part_3' => 13,
            'pcst_public' => 1,
            'pcst_aplicable' => 4,
        );
        $this->db->table('thesa_property_custom')->insert($data);

        $data['pcst_achronic'] = 'is_synonymous';
        $data['pcst_name'] = 'é sinonimo de';
        $data['pcst_part_2'] = 11;
        $this->db->table('thesa_property_custom')->insert($data);

        $data['pcst_achronic'] = 'hasAcronym';
        $data['pcst_name'] = 'é sigla de';
        $data['pcst_part_2'] = 11;
        $this->db->table('thesa_property_custom')->insert($data);

        $data['pcst_achronic'] = 'is_equivalent_another_language';
        $data['pcst_name'] = 'tradução de';
        $data['pcst_part_1'] = 2;
        $data['pcst_part_2'] = 9;
        $this->db->table('thesa_property_custom')->insert($data);

        $data['pcst_achronic'] = 'coordinationOfCauseEffect';
        $data['pcst_name'] = 'Relação causa/efeito';
        $data['pcst_class'] = 2;
        $data['pcst_part_1'] = 2;
        $data['pcst_part_2'] = 9;
        $data['pcst_aplicable'] = 8;
        $this->db->table('thesa_property_custom')->insert($data);

        $data['pcst_achronic'] = 'coordinationOfKinship';
        $data['pcst_name'] = 'Parente de';
        $this->db->table('thesa_property_custom')->insert($data);

    }

    public function down()
    {
        $this->forge->dropTable('thesa_property_custom');
    }
}
