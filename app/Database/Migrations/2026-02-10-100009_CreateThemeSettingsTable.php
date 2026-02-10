<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateThemeSettingsTable extends Migration
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
            'theme_style' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'modern_dark', // modern_dark, minimalist_light, glassmorphism, gradient_vibrant
            ],
            'primary_color' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => '#6c5ce7',
            ],
            'secondary_color' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => '#a29bfe',
            ],
            'bg_color' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => '#0f0c29',
            ],
            'text_color' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => '#ffffff',
            ],
            'font_family' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Outfit',
            ],
            'border_radius' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 15,
            ],
            'glass_opacity' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,2',
                'default'    => 0.10,
            ],
            'card_blur' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 10,
            ],
            'custom_css' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('theme_settings');

        // Insert default theme
        $db = \Config\Database::connect();
        $db->table('theme_settings')->insert([
            'theme_style' => 'modern_dark',
            'primary_color' => '#6c5ce7',
            'secondary_color' => '#a29bfe',
            'bg_color' => '#0f0c29',
            'text_color' => '#ffffff',
            'font_family' => 'Outfit',
            'border_radius' => 20,
            'glass_opacity' => 0.1,
            'card_blur' => 10,
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('theme_settings');
    }
}
