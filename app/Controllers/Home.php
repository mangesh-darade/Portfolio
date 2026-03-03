<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $skillsModel  = new \App\Models\SkillsModel();
        $projectsModel = new \App\Models\ProjectsModel();
        $profileModel  = new \App\Models\UserProfileModel();
        $contactModel  = new \App\Models\ContactModel();

        // Increment page view counter (safe — column existence managed by migration)
        $profile = $profileModel->first();
        if ($profile) {
            $profileModel->update($profile['id'], [
                'total_views' => ($profile['total_views'] ?? 0) + 1
            ]);
        }

        $data = [
            'skills'       => $skillsModel->where('status', 1)->orderBy('category', 'ASC')->orderBy('display_order', 'ASC')->findAll(),
            'projects'     => $projectsModel->where('status', 1)->orderBy('created_at', 'DESC')->findAll(),
            'experience'   => (new \App\Models\ExperienceModel())->orderBy('is_current', 'DESC')->orderBy('start_date', 'DESC')->findAll(),
            'education'    => (new \App\Models\EducationModel())->orderBy('year_end', 'DESC')->findAll(),
            'services'     => (new \App\Models\ServicesModel())->orderBy('created_at', 'ASC')->findAll(),
            'testimonials' => (new \App\Models\TestimonialsModel())->orderBy('created_at', 'DESC')->findAll(),
            'seo'          => (new \App\Models\SeoModel())->first() ?? [],
            'profile'      => $profileModel->first() ?? [],
            'contact'      => $contactModel->first() ?? [],
            'features'     => (new \App\Models\FeatureSettingsModel())->getFeaturesMap(),
            'theme'        => (new \App\Models\ThemeSettingsModel())->getActiveTheme(),
        ];

        return view('home', $data);
    }
    
    /**
     * Handle contact form submission
     */
    public function submit_contact()
    {
        // ── Honeypot spam check (hidden field must be empty) ───────────────────
        if ($this->request->getPost('website') !== '' && $this->request->getPost('website') !== null) {
            // Silently act like success to confuse bots
            return $this->response->setJSON(['status' => 'success', 'message' => 'Thank you! Your message has been sent.']);
        }

        // ── Rate limiting: max 3 contact submissions per IP per 10 minutes ─────
        $ipKey    = 'contact_' . md5($this->request->getIPAddress());
        $attempts = (int) session()->get($ipKey);
        $lockKey  = 'contact_lock_' . md5($this->request->getIPAddress());
        $lockTime = session()->get($lockKey);

        if ($lockTime && time() < $lockTime) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Too many messages sent. Please wait 10 minutes before trying again.']);
        }

        // ── Validation ────────────────────────────────────────────────────────
        $rules = [
            'name'    => 'required|min_length[3]|max_length[100]|alpha_space',
            'email'   => 'required|valid_email|max_length[100]',
            'message' => 'required|min_length[10]|max_length[2000]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        // ── Sanitize input ────────────────────────────────────────────────────
        $data = [
            'name'    => trim(strip_tags($this->request->getPost('name'))),
            'email'   => trim($this->request->getPost('email')),
            'message' => trim(strip_tags($this->request->getPost('message'))),
            'is_read' => 0,
        ];

        // ── Save to DB ────────────────────────────────────────────────────────
        $messagesModel = new \App\Models\MessagesModel();
        if ($messagesModel->insert($data)) {
            // Increment rate-limit counter
            $newAttempts = $attempts + 1;
            session()->set($ipKey, $newAttempts);
            if ($newAttempts >= 3) {
                session()->set($lockKey, time() + 600);
                session()->set($ipKey, 0);
            }

            // Fire email notification (non-blocking — errors are just logged)
            $this->sendEmailNotification($data);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Thank you! Your message has been sent successfully. I\'ll get back to you soon.'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to send message. Please try again later.']);
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
