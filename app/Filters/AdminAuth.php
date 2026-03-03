<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip for login page and all public auth pages
        $path = $request->getUri()->getPath();
        $publicPaths = ['admin/login', 'auth/do_login', 'auth/forgot-password', 'auth/forgot_password', 'auth/reset', 'auth/reset_password', 'auth/email-hint'];
        foreach ($publicPaths as $pub) {
            if (strpos($path, $pub) !== false) {
                return;
            }
        }

        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            // Redirect to login page
            return redirect()->to(site_url('admin/login'))->with('error', 'Please login to access admin panel');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after request
    }
}
