<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ThesaPropertyRange extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_rg' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rg_class' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'rg_range' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'rg_descrition' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'rg_th' => [
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
        $this->forge->addKey('id_rg', true);
        $this->forge->createTable('Thesa_property_range');

        $data = array();
        $data['rg_class'] = '*';
        $data['rg_range'] = 'Literal';
        $data['rg_descrition'] = 'Valores Literales';
        $data['rg_th'] = 0;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $this->db->table('Thesa_property_range')->insert($data);

        $data['rg_range'] = 'Concept';
        $data['rg_descrition'] = 'Conceitos';
        $this->db->table('Thesa_property_range')->insert($data);

        $data['rg_range'] = 'Text';
        $data['rg_descrition'] = 'Textos descritivos';
        $this->db->table('Thesa_property_range')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('Thesa_property_range');
    }
}
