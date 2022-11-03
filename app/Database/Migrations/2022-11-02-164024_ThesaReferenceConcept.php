<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ThesaReferenceConcept extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_rfc' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rfc_concept' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'rfc_ref' => [
                'type'       => 'VARCHAR',
                'constraint'     => 50,
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
        $this->forge->addKey('id_rfc', true);
        $this->forge->createTable('thesa_references_concepts');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_references_concepts');
    }
}
