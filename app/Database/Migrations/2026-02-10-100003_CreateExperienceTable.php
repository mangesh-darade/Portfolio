<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExperienceTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'job_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'company' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'start_date' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // e.g., "Jan 2020"
            ],
            'end_date' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // e.g., "Present", "Dec 2021"
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_current' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('experience', true);
    }

    public function down()
    {
        $this->forge->dropTable('experience');
    }
}
