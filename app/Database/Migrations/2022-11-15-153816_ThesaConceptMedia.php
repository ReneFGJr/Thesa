<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ThesaConceptMedia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_mid' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mid_th' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'mid_concept' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'mid_file_size' => [
                'type'       => 'BIGINT',
                'default' => '0',
                'null' => true,
            ],
            'mid_name' => [
                'type'       => 'VARCHAR',
                'constraint'     => 50,
                'null' => true,
            ],
            'mid_directory' => [
                'type'       => 'VARCHAR',
                'constraint'     => 50,
                'null' => true,
            ],
            'mid_content_type' => [
                'type'       => 'VARCHAR',
                'constraint'     => 30,
                'null' => true,
            ],
            'mid_file' => [
                'type'       => 'VARCHAR',
                'constraint'     => 100,
                'null' => true,
            ],
            'mid_status' => [
                'type'       => 'INT',
                'default' => '1',
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
        $this->forge->addKey('id_mid', true);
        $this->forge->createTable('thesa_midias');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_references');
    }
}
