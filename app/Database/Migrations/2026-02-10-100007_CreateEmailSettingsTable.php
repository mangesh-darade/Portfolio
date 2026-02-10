<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailSettingsTable extends Migration
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
            'protocol' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'smtp', // smtp, mail, sendmail
            ],
            'smtp_host' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'smtp_user' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'smtp_pass' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'smtp_port' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 587,
            ],
            'smtp_crypto' => [
                'type'       => 'VARCHAR', // tls, ssl
                'constraint' => '10',
                'default'    => 'tls',
            ],
            'from_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'from_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('email_settings', true);

        // Seed initial data (sandbox)
        $data = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'sandbox.smtp.mailtrap.io',
            'smtp_user'   => '',
            'smtp_pass'   => '',
            'smtp_port'   => 2525,
            'smtp_crypto' => 'tls',
            'from_email'  => 'admin@example.com',
            'from_name'   => 'Portfolio Admin'
        ];
        $this->db->table('email_settings')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('email_settings');
    }
}
