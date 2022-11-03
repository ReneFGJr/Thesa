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
    }

    public function down()
    {
        $this->forge->dropTable('thesa_property_custom');
    }
}
