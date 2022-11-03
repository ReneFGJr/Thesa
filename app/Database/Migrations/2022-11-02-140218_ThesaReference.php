<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ThesaReference extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ref' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ref_th' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'ref_cite' => [
                'type'       => 'VARCHAR',
                'constraint'     => 50,
                'null' => true,
            ],
            'ref_content' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'ref_status' => [
                'type'       => 'TEXT',
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
        $this->forge->addKey('id_ref', true);
        $this->forge->createTable('thesa_references');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_references');
    }
}
