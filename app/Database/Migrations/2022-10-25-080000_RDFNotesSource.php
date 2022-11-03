<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class RDFNotesSource extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ns' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ns_th' => [
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
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null' => true,
            ],

        ]);
        $this->forge->addKey('id_ns', true);
        $this->forge->createTable('thesa_notes_source');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_notes_source');
    }
}
