<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeatureSettingsTable extends Migration
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
            'feature_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'feature_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'is_enabled' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'display_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
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
        $this->forge->createTable('feature_settings');

        // Insert initial data
        $db = \Config\Database::connect();
        $builder = $db->table('feature_settings');
        $builder->insertBatch([
            ['feature_key' => 'hero', 'feature_name' => 'Hero Section', 'is_enabled' => 1, 'display_order' => 1],
            ['feature_key' => 'skills', 'feature_name' => 'Skills Section', 'is_enabled' => 1, 'display_order' => 2],
            ['feature_key' => 'experience', 'feature_name' => 'Experience Section', 'is_enabled' => 1, 'display_order' => 3],
            ['feature_key' => 'education', 'feature_name' => 'Education Section', 'is_enabled' => 1, 'display_order' => 4],
            ['feature_key' => 'services', 'feature_name' => 'Services Section', 'is_enabled' => 1, 'display_order' => 5],
            ['feature_key' => 'projects', 'feature_name' => 'Projects Section', 'is_enabled' => 1, 'display_order' => 6],
            ['feature_key' => 'testimonials', 'feature_name' => 'Testimonials Section', 'is_enabled' => 1, 'display_order' => 7],
            ['feature_key' => 'contact', 'feature_name' => 'Contact Section', 'is_enabled' => 1, 'display_order' => 8],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('feature_settings');
    }
}
