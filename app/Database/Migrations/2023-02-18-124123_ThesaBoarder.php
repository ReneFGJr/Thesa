<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ThesaBoarder extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_b' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'b_th' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'b_concept_boader' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'b_concept_narrow' => [
                'type'       => 'INT',
                'null' => true,
            ],
            'b_concept_master' => [
                'type'       => 'INT',
                'null' => false,
                'default'=> 0
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
        $this->forge->addKey('id_b', true);
        $this->forge->createTable('thesa_broader');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_broader');
    }
}
