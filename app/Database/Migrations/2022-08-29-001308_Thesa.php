<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Thesa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_th' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'th_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'th_achronic' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'th_description' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'th_status' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'th_terms' => [
                'type' => 'INT',
                'null' => true,
            ],
            'th_version' => [
                'type' => 'FLOAT',
                'null' => true,
            ],
            'th_icone' => [
                'type' => 'INT',
                'null' => true,
            ],
            'th_type' => [
                'type' => 'INT',
                'null' => true,
            ],
            'th_own' => [
                'type' => 'INT',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id_th', true);
        $this->forge->addForeignKey('th_status', 'Thesa_Status', 'id_status');
        $this->forge->addForeignKey('th_own', 'Users', 'id_us');
        $this->forge->createTable('Thesa');

        $data = array(
            'th_name' => 'Thesauro Demo',
            'th_achronic' => 'demo',
            'th_status' => 1,
            'th_type' => 1,
            'th_description' => '',
            'th_terms' => '0',
            'th_version' => '1'
        );
        $this->db->table('Thesa')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('Thesa');
    }
}
