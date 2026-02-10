<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Admin User
        $data = [
            'username' => 'admin',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('admin_users')->insert($data);

        // Profile
        $data = [
            'full_name' => 'Your Name',
            'title'     => 'Full Stack Developer',
            'about'     => 'Passionate developer creating amazing web experiences.',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('profile')->insert($data);

        // Contact Settings
        $data = [
            'email'    => 'admin@example.com',
            'phone'    => '+1234567890',
            'location' => 'New York, USA',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('contact_settings')->insert($data);

        // Skills (Sample)
        $skills = [
            [
                'skill_name' => 'PHP',
                'category'   => 'Backend',
                'skill_level' => 90,
                'display_order' => 1,
            ],
            [
                'skill_name' => 'CodeIgniter 4',
                'category'   => 'Backend',
                'skill_level' => 85,
                'display_order' => 2,
            ],
            [
                'skill_name' => 'JavaScript',
                'category'   => 'Frontend',
                'skill_level' => 80,
                'display_order' => 3,
            ],
        ];
        $this->db->table('skills')->insertBatch($skills);
    }
}
