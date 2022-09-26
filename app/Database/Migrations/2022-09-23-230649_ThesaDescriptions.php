<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ThesaDescriptions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ds' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ds_prop' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'ds_descrition' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'ds_th' => [
                'type'       => 'INT',
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
        $this->forge->addKey('id_ds', true);
        $this->forge->createTable('thesa_descriptions');
    }

    public function down()
    {
        $this->forge->dropTable('thesa_descriptions');
    }
}
