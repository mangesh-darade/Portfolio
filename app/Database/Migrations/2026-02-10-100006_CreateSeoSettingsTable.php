<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeoSettingsTable extends Migration
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
            'site_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'My Portfolio',
            ],
            'site_description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'site_keywords' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'site_author' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'og_image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
             'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('seo_settings', true);

         // Seed initial data
        $data = [
            'site_title' => 'My Portfolio - Creative Developer',
            'site_description' => 'Welcome to my professional portfolio showcasing my projects and skills.',
            'site_keywords' => 'portfolio, developer, web design, coding',
            'site_author' => 'Admin'
        ];
        $this->db->table('seo_settings')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('seo_settings');
    }
}
