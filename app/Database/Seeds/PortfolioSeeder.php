<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run()
    {
        // Seed Profile Data
        $profileData = [
            'full_name' => 'John Doe',
            'bio' => 'Full Stack Developer with 5+ years of experience building modern web applications',
            'email' => 'john.doe@example.com',
            'phone' => '+1 (555) 123-4567',
            'address' => 'San Francisco, CA, USA',
            'linkedin' => 'https://linkedin.com/in/johndoe',
            'github' => 'https://github.com/johndoe',
            'twitter' => 'https://twitter.com/johndoe',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('profile')->insert($profileData);

        // Seed Contact Settings
        $contactData = [
            'email' => 'contact@johndoe.com',
            'phone' => '+1 (555) 987-6543',
            'address' => '123 Tech Street, San Francisco, CA 94102',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('contact_settings')->insert($contactData);

        // Seed Skills
        $skills = [
            [
                'skill_name' => 'PHP & CodeIgniter',
                'category' => 'Backend',
                'skill_level' => 90,
                'display_order' => 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'skill_name' => 'JavaScript & React',
                'category' => 'Frontend',
                'skill_level' => 85,
                'display_order' => 2,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'skill_name' => 'MySQL & Database Design',
                'category' => 'Database',
                'skill_level' => 88,
                'display_order' => 3,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'skill_name' => 'REST API Development',
                'category' => 'Backend',
                'skill_level' => 92,
                'display_order' => 4,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'skill_name' => 'HTML5 & CSS3',
                'category' => 'Frontend',
                'skill_level' => 95,
                'display_order' => 5,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'skill_name' => 'Git & Version Control',
                'category' => 'Tools',
                'skill_level' => 87,
                'display_order' => 6,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('skills')->insertBatch($skills);

        // Seed Projects
        $projects = [
            [
                'project_name' => 'E-Commerce Platform',
                'description' => 'A full-featured online shopping platform with payment integration, inventory management, and customer analytics.',
                'technologies' => 'PHP, CodeIgniter, MySQL, Bootstrap, Stripe API',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'project_name' => 'Task Management System',
                'description' => 'Real-time collaborative task management application with team messaging, file sharing, and progress tracking.',
                'technologies' => 'React, Node.js, MongoDB, Socket.io, AWS',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'project_name' => 'Restaurant Booking App',
                'description' => 'Mobile-first restaurant reservation system with table management, menu display, and real-time availability.',
                'technologies' => 'Vue.js, Laravel, PostgreSQL, Twilio, PWA',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'project_name' => 'Portfolio CMS',
                'description' => 'Content management system for photographers and creative professionals to showcase their work with gallery management.',
                'technologies' => 'CodeIgniter 4, MySQL, jQuery, Dropzone.js',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('projects')->insertBatch($projects);

        // Seed Education
        $education = [
            [
                'degree' => 'Bachelor of Science in Computer Science',
                'institution' => 'Stanford University',
                'year_start' => '2015',
                'year_end' => '2019',
                'description' => 'Focused on software engineering, algorithms, and web technologies.',
                'gpa' => '3.8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'degree' => 'Master of Computer Applications',
                'institution' => 'MIT',
                'year_start' => '2019',
                'year_end' => '2021',
                'description' => 'Specialized in cloud computing and distributed systems.',
                'gpa' => '3.9',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('education')->insertBatch($education);

        // Seed Sample Messages
        $messages = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@company.com',
                'message' => 'Hi! I came across your portfolio and I\'m impressed with your work. Would you be interested in discussing a potential project collaboration?',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'mchen@techstartup.io',
                'message' => 'We are looking for a senior developer to join our team. Your experience with CodeIgniter and React looks like a great fit!',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'name' => 'Emma Williams',
                'email' => 'emma.w@design.co',
                'message' => 'Love the clean design of your portfolio! Can you share what framework you used?',
                'is_read' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
        ];
        $this->db->table('messages')->insertBatch($messages);

        echo "✅ All sample data inserted successfully!\n";
    }
}
