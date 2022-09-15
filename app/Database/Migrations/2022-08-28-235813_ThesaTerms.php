<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaTerms extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $this->forge->addField([
            'id_term' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'term_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'term_lang' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_term', true);
        $this->forge->addForeignKey('term_lang', 'language', 'id_lg');
        $this->forge->createTable('Thesa_Terms');
    }

    public function down()
    {
        $this->forge->dropTable('Thesa_Terms');
    }
}
