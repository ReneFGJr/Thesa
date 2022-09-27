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

        $data = array();
        $data['vc_resource'] = 1;
        $data['vc_prefix'] = 1;
        $data['vc_label'] = 'isInstanceOf';
        $this->db->table('OWL_vocabulary_vc')->insert($data);

        $data['vc_prefix'] = 2;
        $data['vc_resource'] = 2;
        $data['vc_label'] = 'Concept';
        $this->db->table('OWL_vocabulary_vc')->insert($data);

        $data['vc_label'] = 'prefLabel';
        $this->db->table('OWL_vocabulary_vc')->insert($data);

        $data['vc_label'] = 'altLabel';
        $this->db->table('OWL_vocabulary_vc')->insert($data);

        $data['vc_label'] = 'hiddenLabel';
        $this->db->table('OWL_vocabulary_vc')->insert($data);

        $data['vc_label'] = 'broader';
        $this->db->table('OWL_vocabulary_vc')->insert($data);

        $data['vc_label'] = 'narrower';
        $this->db->table('OWL_vocabulary_vc')->insert($data);

        $data['vc_label'] = 'related';
        $this->db->table('OWL_vocabulary_vc')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('OWL_vocabulary_vc');
    }
}
