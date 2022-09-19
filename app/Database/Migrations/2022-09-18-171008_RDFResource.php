<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class RDFResource extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_rs' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rs_prefix' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            'rs_namespace' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            'rs_url' => [
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
        $this->forge->addKey('id_rs', true);
        $this->forge->createTable('OWL_resource');

        $data = array();
        $data['rs_prefix'] = 1;
        $data['rs_url'] = '';
        $data['rs_namespace'] = 'isInstanceOf';
        $this->db->table('OWL_resource')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('OWL_resource');
    }
}
