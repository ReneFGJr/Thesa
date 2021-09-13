<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThThesaurus extends Migration
{
    public function up()
    {
$this->forge->addField([
            'id_pa' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'pa_name' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ],
            'pa_icone' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'pa_description' => [
                'type' => 'TEXT',
			],
            'pa_status' => [
                'type' => 'INT',
                'default' => '0'
			],
            'pa_classe' => [
                'type' => 'INT',
                'default' => '0'
			],
            'pa_creator' => [
                'type' => 'INT',
                'default' => '0'
			],
            'pa_hidden' => [
                'type' => 'INT',
                'default' => '0'
			],
            'pa_avaliacao' => [
                'type' => 'INT',
                'default' => '0'
			],
            'pa_introdution' => [
                'type' => 'TEXT',
                'default' => ''
			],
             'pa_methodology' => [
                'type' => 'TEXT',
                'default' => ''
			],
            'pa_header' => [
                'type' => 'TEXT',
                'default' => ''
			],
            'pa_audience' => [
                'type' => 'TEXT',
                'default' => ''
			],
            'pa_type' => [
                'type' => 'INT',
                'default' => '0'
			],
            'pa_language' => [
                'type' => 'INT',
                'default' => '0'
			],   
            'pa_multi_language' => [
                'type' => 'INT',
                'default' => '0'
			],                                                                      	
            																			
            'pa_created datetime default current_timestamp',
            'pa_update datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_pa', true);
        $this->forge->createTable('th_thesaurus');
    }

    public function down()
    {
        //
    }
}
