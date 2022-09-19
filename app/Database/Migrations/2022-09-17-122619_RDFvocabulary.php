<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class RDFvocabulary extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_owl' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'owl_prefix' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null' => true,
            ],
            'endpoint' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            'spaceName' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'owl_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'owl_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            'owl_description' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'owl_status' => [
                'type' => 'INT',
                'default' => 0,
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
        $this->forge->addKey('id_owl', true);
        $this->forge->createTable('OWL_vocabulary');

        $data = array();
        $data['owl_url'] = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
        $data['owl_title'] = 'rdf';
        $data['owl_prefix'] = 'rdf';
        $data['spaceName'] = 'rdf';
        $data['owl_description'] = '';
        $data['owl_status'] = 1;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $this->db->table('OWL_vocabulary')->insert($data);

        //$data['owl_url'] = 'http://www.w3.org/TR/skos-reference/skos.rdf';
        $data['owl_url'] = 'http://www.w3.org/2009/08/skos-reference/skos.rdf';
        $data['owl_title'] = 'SKOS';
        $data['owl_prefix'] = 'skos';
        $data['spaceName'] = 'skos';
        $data['owl_description'] = 'Simple Knowledge Organization System';
        $data['owl_status'] = 1;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $this->db->table('OWL_vocabulary')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('OWL_vocabulary');
    }
}
