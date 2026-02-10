<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        if (! session()->get('isLoggedIn')) {
            // We can't return a redirect here in initController in CI4 easily that stops execution the same way
            // So we rely on a Filter or check in each method. 
            // For simplicity in this quick setup, I'll check in methods or constructor.
            // But best practice is Filters.
        }
    }

    // Auth is now handled by AdminAuth filter in Config/Routes.php
    private function checkAuth() {
        return true; 
    }

    /**
     * Helper method to render admin views with standard layout
     * @param string $viewName Name of the view file (without path)
     * @param array $data Data to pass to the view
     */
    private function renderAdminView($viewName, $data = [])
    {
        // Fetch Unread Messages for Notifications
        $messagesModel = new \App\Models\MessagesModel();
        $data['unread_messages'] = $messagesModel->where('is_read', 0)->orderBy('created_at', 'DESC')->limit(5)->findAll();
        $data['unread_count'] = $messagesModel->where('is_read', 0)->countAllResults();
        
        // Fetch Profile for header
        $profileModel = new \App\Models\UserProfileModel();
        $data['admin_profile'] = $profileModel->first();
        
        echo view('admin/layout/header', $data);
        echo view('admin/layout/sidebar', $data);
        echo view($viewName, $data);
        echo view('admin/layout/footer', $data);
    }

    public function index()
    {
        $this->checkAuth();
        return redirect()->to('/admin/dashboard');
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }

    public function dashboard()
    {
        $this->checkAuth();
        
        $skillsModel = new \App\Models\SkillsModel();
        $projectsModel = new \App\Models\ProjectsModel();
        $profileModel = new \App\Models\UserProfileModel();
        
        $profile = $profileModel->first();
        
        $data = [
            'page_title' => 'Dashboard',
            'active_menu' => 'dashboard',
            'total_skills' => $skillsModel->where('status', 1)->countAllResults(),
            'total_projects' => $projectsModel->where('status', 1)->countAllResults(),
            'total_views' => $profile['total_views'] ?? 0
        ];
        
        $this->renderAdminView('admin/dashboard', $data);
    }

    public function profile()
    {
        $this->checkAuth();
        $model = new \App\Models\UserProfileModel();
        
        $data = [
            'page_title' => 'Profile Settings',
            'active_menu' => 'profile',
            'profile' => $model->first() ?? []
        ];
        
        $this->renderAdminView('admin/profile', $data);
    }

    public function update_profile()
    {
        $model = new \App\Models\UserProfileModel();
        $profile = $model->first();
        
        $rules = [
            'full_name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }
        
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'bio' => $this->request->getPost('bio'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'github' => $this->request->getPost('github'),
            'linkedin' => $this->request->getPost('linkedin'),
            'twitter' => $this->request->getPost('twitter'),
        ];
        
        // Handle Profile Image Upload
        $file = $this->request->getFile('profile_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate file type
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid file type. Only JPG, PNG, and GIF images are allowed.'
                ]);
            }
            
            // Validate file size (2MB max)
            if ($file->getSize() > 2048000) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'File size too large. Maximum allowed size is 2MB.'
                ]);
            }
            
            // Ensure upload directory exists
            $uploadPath = FCPATH . 'uploads/profile';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['profile_image'] = $newName;
            
            // Delete old image if exists
            if ($profile && isset($profile['profile_image']) && $profile['profile_image']) {
                $oldImagePath = FCPATH . 'uploads/profile/' . $profile['profile_image'];
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }
        }
        
        // Handle Resume Upload
        $resume = $this->request->getFile('resume');
        if ($resume && $resume->isValid() && !$resume->hasMoved()) {
            // Validate file type (PDF)
            if ($resume->getMimeType() !== 'application/pdf') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid file type. Only PDF files are allowed for upload.'
                ]);
            }
            
            // Validate file size (10MB max)
            if ($resume->getSize() > 10240000) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'File size too large. Maximum allowed size is 10MB.'
                ]);
            }
            
            // Ensure upload directory exists
            $uploadPath = FCPATH . 'uploads/resume';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $newName = $resume->getRandomName();
            $resume->move($uploadPath, $newName);
            $data['resume'] = $newName;
            
            // Delete old resume if exists
            if ($profile && isset($profile['resume']) && $profile['resume']) {
                $oldPath = FCPATH . 'uploads/resume/' . $profile['resume'];
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }
        
        try {
            if ($profile) {
                $result = $model->update($profile['id'], $data);
            } else {
                $result = $model->insert($data);
            }

            if ($result) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Profile updated successfully!']);
            } else {
                log_message('error', 'Failed to update profile. Data: ' . json_encode($data));
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update profile.']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Profile update exception: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'An error occurred while updating profile.']);
        }
    }
    
    // Add other methods (skills, projects) as placeholders
    public function skills()
    {
        $this->checkAuth();
        $model = new \App\Models\SkillsModel();
        
        $data = [
            'page_title' => 'Skills',
            'active_menu' => 'skills',
            'skills' => $model->orderBy('category', 'ASC')->orderBy('display_order', 'ASC')->findAll()
        ];
        
        $this->renderAdminView('admin/skills', $data);
    }

    public function add_skill()
    {
        if ($this->request->getMethod() !== 'post') {
            $uri = $this->request->getUri()->getPath();
            return "Skill addition requires POST. Method used: " . $this->request->getMethod() . ". Request URI: " . $uri . ". <br>Tip: If you see this, a redirect converted your POST to GET. Check for trailing slashes or BaseURL mismatch.";
        }
        
        log_message('debug', 'Admin::add_skill POST called.');
        
        $validation = \Config\Services::validation();
        $rules = [
            'skill_name' => 'required',
            'category' => 'required',
            'skill_level' => 'required|numeric|less_than_equal_to[100]',
            'display_order' => 'numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new \App\Models\SkillsModel();
        $data = [
            'skill_name' => $this->request->getPost('skill_name'),
            'category' => $this->request->getPost('category'),
            'skill_level' => $this->request->getPost('skill_level'),
            'display_order' => $this->request->getPost('display_order') ?? 0,
            'status' => 1
        ];

        if ($model->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Skill added successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to add skill.'
            ]);
        }
    }

    public function get_skill($id)
    {
        $this->checkAuth();
        $model = new \App\Models\SkillsModel();
        $skill = $model->find($id);
        
        if ($skill) {
            return $this->response->setJSON(['status' => 'success', 'data' => $skill]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Skill not found']);
    }

    public function update_skill()
    {
        $this->checkAuth();
        
        $model = new \App\Models\SkillsModel();
        $id = $this->request->getPost('id');
        
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid ID']);
        }

        // Validation similar to add
        $rules = [
            'skill_name' => 'required',
            'category' => 'required',
            'skill_level' => 'required|numeric|less_than_equal_to[100]',
            'display_order' => 'numeric'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $data = [
            'skill_name' => $this->request->getPost('skill_name'),
            'category' => $this->request->getPost('category'),
            'skill_level' => $this->request->getPost('skill_level'),
            'display_order' => $this->request->getPost('display_order')
        ];

        if ($model->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Skill updated successfully!'
            ]);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update skill']);
    }

    public function delete_skill($id)
    {
        $this->checkAuth();
        $model = new \App\Models\SkillsModel();
        
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Skill deleted successfully!']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete skill']);
    }

    public function projects()
    {
        $this->checkAuth();
        $model = new \App\Models\ProjectsModel();
        
        $data = [
            'page_title' => 'Projects',
            'active_menu' => 'projects',
            'projects' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/projects', $data);
    }

    public function add_project()
    {
        if ($this->request->getMethod() !== 'post') {
            return "Project addition requires POST. Method used: " . $this->request->getMethod() . ". Check for redirects or form method issues.";
        }
        
        log_message('debug', 'Admin::add_project POST called.');
        
        $validation = \Config\Services::validation();
        $rules = [
            'project_name' => 'required',
            'description' => 'required',
            'technologies' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        // Handle File Upload with Validation
        $imgName = null;
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate file type
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid file type. Only JPG, PNG, and GIF images are allowed.'
                ]);
            }
            
            // Validate file size (5MB max for projects)
            if ($file->getSize() > 5120000) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'File size too large. Maximum allowed size is 5MB.'
                ]);
            }
            
            // Ensure upload directory exists
            $uploadPath = FCPATH . 'uploads/projects';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $imgName = $newName;
        }

        $model = new \App\Models\ProjectsModel();
        $data = [
            'project_name' => $this->request->getPost('project_name'),
            'description' => $this->request->getPost('description'),
            'technologies' => $this->request->getPost('technologies'),
            'image' => $imgName,
            'status' => 1
        ];

        try {
            if ($model->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Project added successfully!'
                ]);
            } else {
                log_message('error', 'Failed to insert project. Data: ' . json_encode($data));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to add project.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Project insert exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'An error occurred while adding the project.'
            ]);
        }
    }

    public function get_project($id)
    {
        $this->checkAuth();
        $model = new \App\Models\ProjectsModel();
        $project = $model->find($id);
        
        if ($project) {
            return $this->response->setJSON(['status' => 'success', 'data' => $project]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Project not found']);
    }

    public function update_project()
    {
        $this->checkAuth();
        $model = new \App\Models\ProjectsModel();
        $id = $this->request->getPost('id');
        
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid ID']);
        }

        $rules = [
            'project_name' => 'required',
            'description' => 'required',
            'technologies' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $data = [
            'project_name' => $this->request->getPost('project_name'),
            'description' => $this->request->getPost('description'),
            'technologies' => $this->request->getPost('technologies'),
        ];

        // Handle Image Update with Validation
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate file type
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid file type. Only JPG, PNG, and GIF images are allowed.'
                ]);
            }
            
            // Validate file size (5MB max)
            if ($file->getSize() > 5120000) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'File size too large. Maximum allowed size is 5MB.'
                ]);
            }
            
            // Ensure upload directory exists
            $uploadPath = FCPATH . 'uploads/projects';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['image'] = $newName;
            
            // Delete old image if exists
            $oldProject = $model->find($id);
            if ($oldProject && isset($oldProject['image']) && $oldProject['image']) {
                $oldImagePath = FCPATH . 'uploads/projects/' . $oldProject['image'];
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }
        }

        try {
            if ($model->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Project updated successfully!'
                ]);
            }
            
            log_message('error', 'Failed to update project. ID: ' . $id . ', Data: ' . json_encode($data));
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update project']);
        } catch (\Exception $e) {
            log_message('error', 'Project update exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'An error occurred while updating the project.'
            ]);
        }
    }

    public function delete_project($id)
    {
        $this->checkAuth();
        $model = new \App\Models\ProjectsModel();
        
        // Get image to delete file
        $project = $model->find($id);
        if ($project['image'] && file_exists(FCPATH . 'uploads/projects/' . $project['image'])) {
            unlink(FCPATH . 'uploads/projects/' . $project['image']);
        }
        
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Project deleted successfully!']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete project']);
    }

    public function messages()
    {
        $this->checkAuth();
        $model = new \App\Models\MessagesModel();
        
        $data = [
            'page_title' => 'Inbox',
            'active_menu' => 'messages',
            'messages' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/messages', $data);
    }

    public function mark_message_read($id)
    {
        $this->checkAuth();
        $model = new \App\Models\MessagesModel();
        $model->update($id, ['is_read' => 1]);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function check_notifications()
    {
        $this->checkAuth();
        $model = new \App\Models\MessagesModel();
        
        $unread_count = $model->where('is_read', 0)->countAllResults();
        $latest = $model->where('is_read', 0)->orderBy('created_at', 'DESC')->first();
        
        return $this->response->setJSON([
            'unread_count' => $unread_count,
            'latest_name' => $latest ? $latest['name'] : null,
            'latest_msg' => $latest ? substr($latest['message'], 0, 50) . '...' : null
        ]);
    }

    public function delete_message($id)
    {
        $this->checkAuth();
        $model = new \App\Models\MessagesModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Message deleted successfully!']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete message']);
    }

    public function contact()
    {
        $this->checkAuth();
        $model = new \App\Models\ContactModel();
        
        $data = [
            'page_title' => 'Contact Info',
            'active_menu' => 'contact',
            'contact' => $model->first()
        ];
        
        $this->renderAdminView('admin/contact', $data);
    }

    public function update_contact()
    {
        $this->checkAuth();
        $model = new \App\Models\ContactModel();
        
        $data = [
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'map_iframe' => $this->request->getPost('map_iframe'),
        ];
        
        $contact = $model->first();
        if ($contact) {
            $model->update($contact['id'], $data);
        } else {
            $model->insert($data);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Contact information updated successfully!'
        ]);
    }

    // Experience Section
    public function experience()
    {
        $this->checkAuth();
        $model = new \App\Models\ExperienceModel();
        
        $data = [
            'page_title' => 'Experience',
            'active_menu' => 'experience',
            'experiences' => $model->orderBy('is_current', 'DESC')->orderBy('start_date', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/experience', $data);
    }

    public function add_experience()
    {
        $this->checkAuth();
        
        $rules = [
            'job_title' => 'required',
            'company' => 'required',
            'start_date' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'errors' => \Config\Services::validation()->getErrors()]);
        }

        $model = new \App\Models\ExperienceModel();
        
        $data = [
            'job_title' => $this->request->getPost('job_title'),
            'company' => $this->request->getPost('company'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'description' => $this->request->getPost('description'),
            'is_current' => $this->request->getPost('is_current') ? 1 : 0
        ];

        if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Experience added successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add experience']);
    }

    public function get_experience($id)
    {
        $this->checkAuth();
        $model = new \App\Models\ExperienceModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
    }

    public function update_experience()
    {
        $this->checkAuth();
        $id = $this->request->getPost('id');
        
        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID missing']);
        
        $model = new \App\Models\ExperienceModel();
        
        $data = [
            'id' => $id,
            'job_title' => $this->request->getPost('job_title'),
            'company' => $this->request->getPost('company'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'description' => $this->request->getPost('description'),
            'is_current' => $this->request->getPost('is_current') ? 1 : 0
        ];

         if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Experience updated successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update experience']);
    }

    public function delete_experience($id)
    {
        $this->checkAuth();
        $model = new \App\Models\ExperienceModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Deleted successfully']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete']);
    }

    // Education Section
    public function education()
    {
        $this->checkAuth();
        $model = new \App\Models\EducationModel();
        
        $data = [
            'page_title' => 'Education',
            'active_menu' => 'education',
            'educations' => $model->orderBy('year_end', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/education', $data);
    }

    public function add_education()
    {
        $this->checkAuth();
        
        $rules = [
            'degree' => 'required',
            'institution' => 'required',
            'year_start' => 'required',
            'year_end' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'errors' => \Config\Services::validation()->getErrors()]);
        }

        $model = new \App\Models\EducationModel();
        
        $data = [
            'degree' => $this->request->getPost('degree'),
            'institution' => $this->request->getPost('institution'),
            'year_start' => $this->request->getPost('year_start'),
            'year_end' => $this->request->getPost('year_end'),
            'description' => $this->request->getPost('description'),
            'gpa' => $this->request->getPost('gpa'),
        ];

        if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Education added successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add education']);
    }

    public function get_education($id)
    {
        $this->checkAuth();
        $model = new \App\Models\EducationModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
    }

    public function update_education()
    {
        $this->checkAuth();
        $id = $this->request->getPost('id');
        
        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID missing']);
        
        $model = new \App\Models\EducationModel();
        
        $data = [
            'id' => $id,
            'degree' => $this->request->getPost('degree'),
            'institution' => $this->request->getPost('institution'),
            'year_start' => $this->request->getPost('year_start'),
            'year_end' => $this->request->getPost('year_end'),
            'description' => $this->request->getPost('description'),
            'gpa' => $this->request->getPost('gpa'),
        ];

         if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Education updated successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update education']);
    }

    public function delete_education($id)
    {
        $this->checkAuth();
        $model = new \App\Models\EducationModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Deleted successfully']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete']);
    }

    // Services Section
    public function services()
    {
        $this->checkAuth();
        $model = new \App\Models\ServicesModel();
        
        $data = [
            'page_title' => 'Services (Offerings)',
            'active_menu' => 'services',
            'services' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/services', $data);
    }

    public function add_service()
    {
        $this->checkAuth();
        
        $rules = [
            'title' => 'required',
            'description' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'errors' => \Config\Services::validation()->getErrors()]);
        }

        $model = new \App\Models\ServicesModel();
        
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'icon' => $this->request->getPost('icon') ?: 'fas fa-cogs',
        ];

        if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Service added successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add service']);
    }

    public function get_service($id)
    {
        $this->checkAuth();
        $model = new \App\Models\ServicesModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
    }

    public function update_service()
    {
        $this->checkAuth();
        $id = $this->request->getPost('id');
        
        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID missing']);
        
        $model = new \App\Models\ServicesModel();
        
        $data = [
            'id' => $id,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'icon' => $this->request->getPost('icon') ?: 'fas fa-cogs',
        ];

         if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Service updated successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update service']);
    }

    public function delete_service($id)
    {
        $this->checkAuth();
        $model = new \App\Models\ServicesModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Deleted successfully']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete']);
    }

    // Testimonials Section
    public function testimonials()
    {
        $this->checkAuth();
        $model = new \App\Models\TestimonialsModel();
        
        $data = [
            'page_title' => 'Testimonials',
            'active_menu' => 'testimonials',
            'testimonials' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/testimonials', $data);
    }

    public function add_testimonial()
    {
        $this->checkAuth();
        
        $rules = [
            'name' => 'required',
            'role' => 'required',
            'quote' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'errors' => \Config\Services::validation()->getErrors()]);
        }

        $model = new \App\Models\TestimonialsModel();
        
        $data = [
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'company' => $this->request->getPost('company'),
            'quote' => $this->request->getPost('quote'),
        ];
        
        // Handle Image
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid image format.']);
            }
            // Ensure upload directory exists
            $uploadPath = FCPATH . 'uploads/testimonials';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['image'] = $newName;
        }

        if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Testimonial added successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add testimonial']);
    }

    public function get_testimonial($id)
    {
        $this->checkAuth();
        $model = new \App\Models\TestimonialsModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
    }

    public function update_testimonial()
    {
        $this->checkAuth();
        $id = $this->request->getPost('id');
        
        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID missing']);
        
        $model = new \App\Models\TestimonialsModel();
        
        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'company' => $this->request->getPost('company'),
            'quote' => $this->request->getPost('quote'),
        ];
        
        // Handle Image
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid image format.']);
            }
            // Ensure upload directory exists
             $uploadPath = FCPATH . 'uploads/testimonials';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['image'] = $newName;
            
             // Delete old image
            $oldInfo = $model->find($id);
            if ($oldInfo && isset($oldInfo['image']) && $oldInfo['image']) {
                $oldPath = $uploadPath . '/' . $oldInfo['image'];
                if (file_exists($oldPath)) @unlink($oldPath);
            }
        }

         if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Testimonial updated successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update testimonial']);
    }

    public function delete_testimonial($id)
    {
        $this->checkAuth();
        $model = new \App\Models\TestimonialsModel();
        
        // Delete image first
        $info = $model->find($id);
        if ($info && isset($info['image']) && $info['image']) {
            $path = FCPATH . 'uploads/testimonials/' . $info['image'];
            if (file_exists($path)) @unlink($path);
        }
        
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Deleted successfully']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete']);
    }

    // SEO Settings
    public function seo()
    {
        $this->checkAuth();
        $model = new \App\Models\SeoModel();
        
        $data = [
            'page_title' => 'SEO Settings',
            'active_menu' => 'seo',
            'seo' => $model->first()
        ];
        
        $this->renderAdminView('admin/seo', $data);
    }

    public function update_seo()
    {
        $this->checkAuth();
        
        $model = new \App\Models\SeoModel();
        $seo = $model->first();
        $id = $seo ? $seo['id'] : null;
        
        $data = [
            'site_title' => $this->request->getPost('site_title'),
            'site_description' => $this->request->getPost('site_description'),
            'site_keywords' => $this->request->getPost('site_keywords'),
            'site_author' => $this->request->getPost('site_author'),
        ];
        
        // Handle OG Image
        $file = $this->request->getFile('og_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid image format.']);
            }
            // Ensure upload directory exists
            $uploadPath = FCPATH . 'uploads/seo';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['og_image'] = $newName;
            
             // Delete old image
            if ($seo && isset($seo['og_image']) && $seo['og_image']) {
                $oldPath = $uploadPath . '/' . $seo['og_image'];
                if (file_exists($oldPath)) @unlink($oldPath);
            }
        }
        
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->save($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'SEO settings updated successfully']);
    }

    // Email Settings
    public function email_settings()
    {
        $this->checkAuth();
        $model = new \App\Models\EmailSettingsModel();
        
        $data = [
            'page_title' => 'Email Settings',
            'active_menu' => 'email_settings',
            'settings' => $model->first()
        ];
        
        $this->renderAdminView('admin/email_settings', $data);
    }

    public function update_email_settings()
    {
        $this->checkAuth();
        $model = new \App\Models\EmailSettingsModel();
        $settings = $model->first();
        $id = $settings ? $settings['id'] : null;
        
        $data = [
            'protocol' => $this->request->getPost('protocol'),
            'smtp_host' => $this->request->getPost('smtp_host'),
            'smtp_user' => $this->request->getPost('smtp_user'),
            'smtp_pass' => $this->request->getPost('smtp_pass'),
            'smtp_port' => $this->request->getPost('smtp_port'),
            'smtp_crypto' => $this->request->getPost('smtp_crypto'),
            'from_email' => $this->request->getPost('from_email'),
            'from_name' => $this->request->getPost('from_name'),
        ];
        
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->save($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Email settings updated successfully']);
    }

    // Display Features Management
    public function features()
    {
        $this->checkAuth();
        $model = new \App\Models\FeatureSettingsModel();
        
        $data = [
            'page_title' => 'Display Features',
            'active_menu' => 'features',
            'features' => $model->orderBy('display_order', 'ASC')->findAll()
        ];
        
        $this->renderAdminView('admin/features', $data);
    }

    public function update_features()
    {
        $this->checkAuth();
        $model = new \App\Models\FeatureSettingsModel();
        $featuresData = $this->request->getPost('features');
        
        if ($featuresData) {
            foreach ($featuresData as $id => $data) {
                $updateData = [
                    'display_order' => $data['order'],
                    'is_enabled' => isset($data['enabled']) ? 1 : 0
                ];
                $model->update($id, $updateData);
            }
            return $this->response->setJSON(['status' => 'success', 'message' => 'Display features updated successfully']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'No data received']);
    }

    // Theme Settings
    public function theme_settings()
    {
        $this->checkAuth();
        $model = new \App\Models\ThemeSettingsModel();
        
        $data = [
            'page_title' => 'Theme Customizer',
            'active_menu' => 'theme',
            'theme' => $model->getActiveTheme()
        ];
        
        $this->renderAdminView('admin/theme_settings', $data);
    }

    public function update_theme()
    {
        $this->checkAuth();
        $model = new \App\Models\ThemeSettingsModel();
        $theme = $model->first();
        $id = $theme ? $theme['id'] : null;
        
        $data = [
            'theme_style' => $this->request->getPost('theme_style'),
            'primary_color' => $this->request->getPost('primary_color'),
            'secondary_color' => $this->request->getPost('secondary_color'),
            'bg_color' => $this->request->getPost('bg_color'),
            'text_color' => $this->request->getPost('text_color'),
            'font_family' => $this->request->getPost('font_family'),
            'border_radius' => $this->request->getPost('border_radius'),
            'glass_opacity' => $this->request->getPost('glass_opacity'),
            'card_blur' => $this->request->getPost('card_blur'),
            'custom_css' => $this->request->getPost('custom_css'),
        ];
        
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->save($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Theme settings updated successfully']);
    }
}
