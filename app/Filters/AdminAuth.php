<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip for login page to avoid infinite redirect
        $path = $request->getUri()->getPath();
        if (strpos($path, 'admin/login') !== false || strpos($path, 'auth/do_login') !== false) {
            return;
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
