<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThesaLinguageTh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_lgt' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lgt_th' => [
                'type' => 'INT',
                'null' => true,
            ],
            'lgt_language' => [
                'type' => 'INT',
                'null' => true,
            ],
            'lgt_order' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_lgt', true);
        $this->forge->createTable('thesa_language');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_language');
    }
}
