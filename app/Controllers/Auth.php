<?php

namespace App\Controllers;

use App\Models\UserModel; // Assuming you might create a model later, but query builder for now is fine

class Auth extends BaseController
{
    public function index()
    {
        return redirect()->to('/admin/login');
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }

    public function do_login()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please check your input.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        $builder = $db->table('admin_users');
        $user = $builder->where('username', $username)->get()->getRow();

        if ($user) {
            if (password_verify($password, $user->password)) {
                $sessionData = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'isLoggedIn' => true
                ];
                session()->set($sessionData);
                return redirect()->to('/admin/dashboard');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Invalid login credentials.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
}
