<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $skillsModel = new \App\Models\SkillsModel();
        $projectsModel = new \App\Models\ProjectsModel();
        $profileModel = new \App\Models\UserProfileModel();
        $contactModel = new \App\Models\ContactModel();

        // Increment Views
        $profile = $profileModel->first();
        if ($profile) {
            // Check if column exists, if not add it (Self-healing)
            $db = \Config\Database::connect();
            if (!$db->fieldExists('total_views', 'profile')) {
                $db->query("ALTER TABLE profile ADD COLUMN total_views INT DEFAULT 0 AFTER profile_image");
            }
            
            $profileModel->update($profile['id'], [
                'total_views' => ($profile['total_views'] ?? 0) + 1
            ]);
        }

        $data = [
            'skills' => $skillsModel->where('status', 1)->orderBy('category', 'ASC')->orderBy('display_order', 'ASC')->findAll(),
            'projects' => $projectsModel->where('status', 1)->orderBy('created_at', 'DESC')->findAll(),
            'experience' => (new \App\Models\ExperienceModel())->orderBy('is_current', 'DESC')->orderBy('start_date', 'DESC')->findAll(),
            'education' => (new \App\Models\EducationModel())->orderBy('year_end', 'DESC')->findAll(),
            'services' => (new \App\Models\ServicesModel())->orderBy('created_at', 'ASC')->findAll(),
            'testimonials' => (new \App\Models\TestimonialsModel())->orderBy('created_at', 'DESC')->findAll(),
            'seo' => (new \App\Models\SeoModel())->first() ?? [],
            'profile' => $profileModel->first() ?? [],
            'contact' => $contactModel->first() ?? [],
            'features' => (new \App\Models\FeatureSettingsModel())->getFeaturesMap(),
            'theme' => (new \App\Models\ThemeSettingsModel())->getActiveTheme()
        ];

        return view('home', $data);
    }
    
    /**
     * Handle contact form submission
     */
    public function submit_contact()
    {
        // Validation rules
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'message' => 'required|min_length[10]|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        // Save message to database
        $messagesModel = new \App\Models\MessagesModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'message' => $this->request->getPost('message'),
            'is_read' => 0
        ];

        if ($messagesModel->insert($data)) {
            // Send email notification to admin
            $this->sendEmailNotification($data);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to send message. Please try again later.'
            ]);
        }
    }

    /**
     * Optional: Send email notification method
     * Uncomment and configure email settings in app/Config/Email.php
     */
    /**
     * Send email notifications
     */
    /**
     * Send email notifications
     */
    private function sendEmailNotification($data)
    {
        // Fetch Admin Email from Profile (where Resume is)
        $profileModel = new \App\Models\UserProfileModel();
        $profile = $profileModel->first();
        $adminEmail = $profile['email'] ?? 'admin@example.com';
        
        // Load Email Settings from DB
        $emailSettingsModel = new \App\Models\EmailSettingsModel();
        $settings = $emailSettingsModel->first();
        
        // Configure Email Service
        $config = [
            'protocol' => $settings['protocol'] ?? 'mail',
            'SMTPHost' => $settings['smtp_host'] ?? '',
            'SMTPUser' => $settings['smtp_user'] ?? '',
            'SMTPPass' => $settings['smtp_pass'] ?? '',
            'SMTPPort' => (int)($settings['smtp_port'] ?? 25),
            'SMTPCrypto' => $settings['smtp_crypto'] ?? 'tls',
            'mailType' => 'html',
            'charset'  => 'utf-8',
            'newline'  => "\r\n"
        ];
        
        $email = \Config\Services::email();
        $email->initialize($config);
        
        $fromEmail = $settings['from_email'] ?? 'no-reply@example.com';
        $fromName = $settings['from_name'] ?? 'Portfolio Admin';
        
        // 1. Send to Admin (New Lead Notification)
        // Use the visitor's email as the "From" address
        $email->setFrom($data['email'], $data['name']); 
        $email->setReplyTo($data['email'], $data['name']); 
        $email->setTo($adminEmail);
        $email->setSubject('New Contact Form Submission: ' . $data['name']);
        
        $message = "
            <html>
            <head>
                <title>New Contact Message</title>
            </head>
            <body>
                <h3>New Message from Portfolio Website</h3>
                <p><strong>Name:</strong> {$data['name']}</p>
                <p><strong>Email:</strong> {$data['email']}</p>
                <p><strong>Message:</strong><br>{$data['message']}</p>
                <hr>
                <p><small>This email was sent from your portfolio contact form.</small></p>
            </body>
            </html>
        ";
        
        $email->setMessage($message);
        if (!$email->send()) {
            log_message('error', 'Email Send Error: ' . $email->printDebugger(['headers']));
        }
        
        // 2. Send to User (Auto-reply)
        $email->clear(); // Clear previous settings
        
        $email->setFrom($fromEmail, $fromName);
        $email->setReplyTo($adminEmail, $profile['full_name'] ?? 'Portfolio Admin');
        $email->setTo($data['email']);
        $email->setSubject('Thank you for contacting me!');
        
        $userMessage = "
            <html>
            <head>
                <title>Thank You</title>
            </head>
            <body>
                <h3>Hi {$data['name']},</h3>
                <p>Thanks for reaching out! I have received your message and will get back to you as soon as possible.</p>
                <br>
                <p><strong>Your Message:</strong></p>
                <blockquote>{$data['message']}</blockquote>
                <br>
                <p>Best regards,</p>
                <p>{$fromName}</p>
            </body>
            </html>
        ";
        
        $email->setMessage($userMessage);
        if (!$email->send()) {
             log_message('error', 'Auto-reply Send Error: ' . $email->printDebugger(['headers']));
        }
    }
}
