<?php

namespace App\Controllers;

class Admin extends BaseController
{
    /**
     * Auth is handled by AdminAuth filter registered in Routes.php.
     * No checkAuth() needed per-method — filter runs first.
     */

    /**
     * Unified JSON response helper.
     */
    private function jsonResponse(string $status, string $message, array $extra = [])
    {
        return $this->response->setJSON(array_merge(['status' => $status, 'message' => $message], $extra));
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
        $skillsModel   = new \App\Models\SkillsModel();
        $projectsModel = new \App\Models\ProjectsModel();
        $profileModel  = new \App\Models\UserProfileModel();
        $messagesModel = new \App\Models\MessagesModel();

        $profile = $profileModel->first();

        $data = [
            'page_title'     => 'Dashboard',
            'active_menu'    => 'dashboard',
            'total_skills'   => $skillsModel->where('status', 1)->countAllResults(),
            'total_projects' => $projectsModel->where('status', 1)->countAllResults(),
            'total_views'    => $profile['total_views'] ?? 0,
            'last_login'     => session()->get('last_login'),
            'total_messages' => $messagesModel->countAllResults(),
        ];

        $this->renderAdminView('admin/dashboard', $data);
    }

    public function profile()
    {
        $model = new \App\Models\UserProfileModel();

        $data = [
            'page_title'  => 'Profile Settings',
            'active_menu' => 'profile',
            'profile'     => $model->first() ?? [],
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
                mkdir($uploadPath, 0755, true);
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
    
    // ─── Change Password ──────────────────────────────────────────────────────
    public function change_password()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse('error', implode(' ', \Config\Services::validation()->getErrors()));
        }

        $db   = \Config\Database::connect();
        $user = $db->table('admin_users')->where('id', session()->get('id'))->get()->getRow();

        if (!$user || !password_verify($this->request->getPost('current_password'), $user->password)) {
            return $this->jsonResponse('error', 'Current password is incorrect.');
        }

        $newHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        $db->table('admin_users')->where('id', $user->id)->update(['password' => $newHash]);

        log_message('info', 'Admin password changed for user ID: ' . $user->id);
        return $this->jsonResponse('success', 'Password changed successfully! Please login again.');
    }

    // ─── Skills ──────────────────────────────────────────────────────────────
    public function skills()
    {
        $model = new \App\Models\SkillsModel();

        $data = [
            'page_title'  => 'Skills',
            'active_menu' => 'skills',
            'skills'      => $model->orderBy('category', 'ASC')->orderBy('display_order', 'ASC')->findAll(),
        ];

        $this->renderAdminView('admin/skills', $data);
    }

    public function add_skill()
    {
        
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
        if ((int)$id <= 0) return $this->jsonResponse('error', 'Invalid ID');
        $model = new \App\Models\SkillsModel();
        $skill = $model->find((int)$id);
        if ($skill) {
            return $this->response->setJSON(['status' => 'success', 'data' => $skill]);
        }
        return $this->jsonResponse('error', 'Skill not found');
    }

    public function update_skill()
    {        
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
        if ((int)$id <= 0) return $this->jsonResponse('error', 'Invalid ID');
        $model = new \App\Models\SkillsModel();
        if ($model->delete((int)$id)) {
            return $this->jsonResponse('success', 'Skill deleted successfully!');
        }
        return $this->jsonResponse('error', 'Failed to delete skill');
    }

    public function projects()
    {        $model = new \App\Models\ProjectsModel();
        
        $data = [
            'page_title' => 'Projects',
            'active_menu' => 'projects',
            'projects' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/projects', $data);
    }

    public function add_project()
    {
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
    {        $model = new \App\Models\ProjectsModel();
        $project = $model->find($id);
        
        if ($project) {
            return $this->response->setJSON(['status' => 'success', 'data' => $project]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Project not found']);
    }

    public function update_project()
    {        $model = new \App\Models\ProjectsModel();
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
        if ((int)$id <= 0) return $this->jsonResponse('error', 'Invalid ID');
        $model   = new \App\Models\ProjectsModel();
        $project = $model->find((int)$id);
        if ($project && !empty($project['image'])) {
            $imgPath = FCPATH . 'uploads/projects/' . $project['image'];
            if (file_exists($imgPath)) {
                @unlink($imgPath);
            }
        }
        if ($model->delete((int)$id)) {
            return $this->jsonResponse('success', 'Project deleted successfully!');
        }
        return $this->jsonResponse('error', 'Failed to delete project');
    }

    public function messages()
    {        $model = new \App\Models\MessagesModel();
        
        $data = [
            'page_title' => 'Inbox',
            'active_menu' => 'messages',
            'messages' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/messages', $data);
    }

    public function mark_message_read($id)
    {        $model = new \App\Models\MessagesModel();
        $model->update($id, ['is_read' => 1]);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function check_notifications()
    {        $model = new \App\Models\MessagesModel();
        
        $unread_count = $model->where('is_read', 0)->countAllResults();
        $latest = $model->where('is_read', 0)->orderBy('created_at', 'DESC')->first();
        
        return $this->response->setJSON([
            'unread_count' => $unread_count,
            'latest_name' => $latest ? $latest['name'] : null,
            'latest_msg' => $latest ? substr($latest['message'], 0, 50) . '...' : null
        ]);
    }

    public function delete_message($id)
    {        $model = new \App\Models\MessagesModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Message deleted successfully!']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete message']);
    }

    public function contact()
    {        $model = new \App\Models\ContactModel();
        
        $data = [
            'page_title' => 'Contact Info',
            'active_menu' => 'contact',
            'contact' => $model->first()
        ];
        
        $this->renderAdminView('admin/contact', $data);
    }

    public function update_contact()
    {        $model = new \App\Models\ContactModel();
        
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
    {        $model = new \App\Models\ExperienceModel();
        
        $data = [
            'page_title' => 'Experience',
            'active_menu' => 'experience',
            'experiences' => $model->orderBy('is_current', 'DESC')->orderBy('start_date', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/experience', $data);
    }

    public function add_experience()
    {        
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
    {        $model = new \App\Models\ExperienceModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
    }

    public function update_experience()
    {        $id = $this->request->getPost('id');
        
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
    {        $model = new \App\Models\ExperienceModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Deleted successfully']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete']);
    }

    // Education Section
    public function education()
    {        $model = new \App\Models\EducationModel();
        
        $data = [
            'page_title' => 'Education',
            'active_menu' => 'education',
            'educations' => $model->orderBy('year_end', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/education', $data);
    }

    public function add_education()
    {        
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
    {        $model = new \App\Models\EducationModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
    }

    public function update_education()
    {        $id = $this->request->getPost('id');
        
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
    {        $model = new \App\Models\EducationModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Deleted successfully']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete']);
    }

    // Services Section
    public function services()
    {        $model = new \App\Models\ServicesModel();
        
        $data = [
            'page_title' => 'Services (Offerings)',
            'active_menu' => 'services',
            'services' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/services', $data);
    }

    public function add_service()
    {        
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
    {        $model = new \App\Models\ServicesModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
    }

    public function update_service()
    {        $id = $this->request->getPost('id');
        
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
    {        $model = new \App\Models\ServicesModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Deleted successfully']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete']);
    }

    // Testimonials Section
    public function testimonials()
    {        $model = new \App\Models\TestimonialsModel();
        
        $data = [
            'page_title' => 'Testimonials',
            'active_menu' => 'testimonials',
            'testimonials' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        $this->renderAdminView('admin/testimonials', $data);
    }

    public function add_testimonial()
    {        
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
    {        $model = new \App\Models\TestimonialsModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
    }

    public function update_testimonial()
    {        $id = $this->request->getPost('id');
        
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
    {        $model = new \App\Models\TestimonialsModel();
        
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
    {        $model = new \App\Models\SeoModel();
        
        $data = [
            'page_title' => 'SEO Settings',
            'active_menu' => 'seo',
            'seo' => $model->first()
        ];
        
        $this->renderAdminView('admin/seo', $data);
    }

    public function update_seo()
    {        
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

    // ─── Email Settings ───────────────────────────────────────────────────────
    public function email_settings()
    {
        $model = new \App\Models\EmailSettingsModel();
        $data  = [
            'page_title'  => 'Email Settings',
            'active_menu' => 'email_settings',
            'settings'    => $model->first(),
        ];
        $this->renderAdminView('admin/email_settings', $data);
    }

    public function update_email_settings()
    {
        $rules = [
            'smtp_host'  => 'required',
            'smtp_user'  => 'required|valid_email',
            'smtp_pass'  => 'required',
            'smtp_port'  => 'required|numeric',
            'from_email' => 'required|valid_email',
            'from_name'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'errors' => \Config\Services::validation()->getErrors()]);
        }

        $model    = new \App\Models\EmailSettingsModel();
        $settings = $model->first();
        $id       = $settings ? $settings['id'] : null;

        $data = [
            'protocol'    => $this->request->getPost('protocol'),
            'smtp_host'   => $this->request->getPost('smtp_host'),
            'smtp_user'   => $this->request->getPost('smtp_user'),
            'smtp_pass'   => $this->request->getPost('smtp_pass'),
            'smtp_port'   => (int) $this->request->getPost('smtp_port'),
            'smtp_crypto' => $this->request->getPost('smtp_crypto'),
            'from_email'  => $this->request->getPost('from_email'),
            'from_name'   => $this->request->getPost('from_name'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($id) {
            $model->update($id, $data);
        } else {
            $model->save($data);
        }

        return $this->jsonResponse('success', 'Email settings updated successfully.');
    }

    public function test_email()
    {
        $model    = new \App\Models\EmailSettingsModel();
        $settings = $model->first();

        if (!$settings) {
            return $this->jsonResponse('error', 'Email settings are not configured yet.');
        }

        $config = [
            'protocol'   => $settings['protocol'] ?? 'smtp',
            'SMTPHost'   => $settings['smtp_host'],
            'SMTPUser'   => $settings['smtp_user'],
            'SMTPPass'   => $settings['smtp_pass'],
            'SMTPPort'   => (int)($settings['smtp_port'] ?? 587),
            'SMTPCrypto' => $settings['smtp_crypto'] ?? 'tls',
            'mailType'   => 'html',
            'charset'    => 'utf-8',
        ];

        $email = \Config\Services::email();
        $email->initialize($config);
        $email->setFrom($settings['from_email'], $settings['from_name']);
        $email->setTo($settings['smtp_user']);
        $email->setSubject('✅ Portfolio Admin — Email Test');
        $email->setMessage('<h3>Email test successful!</h3><p>Your SMTP configuration is working correctly.</p><p>Sent at: ' . date('Y-m-d H:i:s') . '</p>');

        if ($email->send()) {
            return $this->jsonResponse('success', 'Test email sent to ' . $settings['smtp_user'] . '. Please check your inbox.');
        } else {
            $debugInfo = $email->printDebugger(['headers']);
            log_message('error', 'Test email failed: ' . $debugInfo);
            return $this->jsonResponse('error', 'Failed to send test email. Check your SMTP credentials. Error logged.');
        }
    }

    // Display Features Management
    public function features()
    {        $model = new \App\Models\FeatureSettingsModel();
        
        $data = [
            'page_title' => 'Display Features',
            'active_menu' => 'features',
            'features' => $model->orderBy('display_order', 'ASC')->findAll()
        ];
        
        $this->renderAdminView('admin/features', $data);
    }

    public function update_features()
    {        $model = new \App\Models\FeatureSettingsModel();
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
    {        $model = new \App\Models\ThemeSettingsModel();
        
        $data = [
            'page_title' => 'Theme Customizer',
            'active_menu' => 'theme',
            'theme' => $model->getActiveTheme()
        ];
        
        $this->renderAdminView('admin/theme_settings', $data);
    }

    public function update_theme()
    {        $model = new \App\Models\ThemeSettingsModel();
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

        return $this->jsonResponse('success', 'Theme settings updated successfully.');
    }

    // ─── Internal upload helper ───────────────────────────────────────────────
    /**
     * Handles file upload with MIME + size validation.
     *
     * @param  string   $fieldName     HTML input name
     * @param  string   $uploadSubDir  e.g. 'projects', 'profile'
     * @param  array    $allowedMimes  Allowed MIME types
     * @param  int      $maxSizeBytes  Max file size in bytes
     * @return array ['ok' => bool, 'name' => string, 'error' => string]
     */
    private function handleUpload(string $fieldName, string $uploadSubDir, array $allowedMimes, int $maxSizeBytes = 2097152): array
    {
        $file = $this->request->getFile($fieldName);

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return ['ok' => false, 'skip' => true]; // No file uploaded — skip silently
        }

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return ['ok' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', $allowedMimes)];
        }

        if ($file->getSize() > $maxSizeBytes) {
            $maxMb = round($maxSizeBytes / 1048576, 1);
            return ['ok' => false, 'error' => "File too large. Maximum {$maxMb}MB allowed."];
        }

        $uploadPath = FCPATH . 'uploads/' . $uploadSubDir;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        return ['ok' => true, 'name' => $newName];
    }
}
