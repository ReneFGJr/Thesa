<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class RDFvocabularyVC extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_vc' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'vc_resource' => [
                'type' => 'INT',
                'null' => true,
                'default' => 0,
            ],
            'vc_prefix' => [
                'type' => 'INT',
                'null' => true,
                'default' => 0,
            ],
            'vc_label' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'vc_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
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
        $this->forge->addKey('id_vc', true);
        $this->forge->createTable('OWL_vocabulary_vc');
    }

    public function down()
    {
        $this->forge->dropTable('OWL_vocabulary_vc');
    }
}
