<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class RDFNotes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_nt' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nt_concept' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'nt_prop' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'nt_lang' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'nt_content' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            /*
                        'nt_content' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            */

            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_nt', true);
        $this->forge->createTable('thesa_notes');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_notes');
    }
}
