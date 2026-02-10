<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('contact/submit', 'Home::submit_contact');

$routes->group('admin', ['filter' => 'adminauth'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('login', 'Auth::login');
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('profile', 'Admin::profile');
    $routes->post('profile/update', 'Admin::update_profile');
    
    // Skills
    $routes->get('skills', 'Admin::skills');
    $routes->match(['get', 'post'], 'skills/add', 'Admin::add_skill');
    $routes->match(['get', 'post'], 'skills/add/', 'Admin::add_skill');
    $routes->get('skills/get/(:num)', 'Admin::get_skill/$1');
    $routes->post('skills/update', 'Admin::update_skill');
    $routes->post('skills/update/', 'Admin::update_skill');
    $routes->post('skills/delete/(:num)', 'Admin::delete_skill/$1');
    
    // Projects
    $routes->get('projects', 'Admin::projects');
    $routes->match(['get', 'post'], 'projects/add', 'Admin::add_project');
    $routes->match(['get', 'post'], 'projects/add/', 'Admin::add_project');
    $routes->get('projects/get/(:num)', 'Admin::get_project/$1');
    $routes->post('projects/update', 'Admin::update_project');
    $routes->post('projects/update/', 'Admin::update_project');
    $routes->post('projects/delete/(:num)', 'Admin::delete_project/$1');
    
    // Placeholders for now
    $routes->get('messages', 'Admin::messages');
    $routes->post('messages/read/(:num)', 'Admin::mark_message_read/$1');
    $routes->post('messages/delete/(:num)', 'Admin::delete_message/$1');
    $routes->get('check-notifications', 'Admin::check_notifications');
    
    $routes->get('settings', 'Admin::dashboard');
    $routes->get('contact', 'Admin::contact');
    $routes->post('contact/update', 'Admin::update_contact');
    $routes->get('site-settings', 'Admin::dashboard');
    // Experience
    $routes->get('experience', 'Admin::experience');
    $routes->post('experience/add', 'Admin::add_experience');
    $routes->get('experience/get/(:num)', 'Admin::get_experience/$1');
    $routes->post('experience/update', 'Admin::update_experience');
    $routes->post('experience/delete/(:num)', 'Admin::delete_experience/$1');

    // Education
    $routes->get('education', 'Admin::education');
    $routes->post('education/add', 'Admin::add_education');
    $routes->get('education/get/(:num)', 'Admin::get_education/$1');
    $routes->post('education/update', 'Admin::update_education');
    // Services
    $routes->get('services', 'Admin::services');
    $routes->post('services/add', 'Admin::add_service');
    $routes->get('services/get/(:num)', 'Admin::get_service/$1');
    $routes->post('services/update', 'Admin::update_service');
    // Testimonials
    $routes->get('testimonials', 'Admin::testimonials');
    $routes->post('testimonials/add', 'Admin::add_testimonial');
    $routes->get('testimonials/get/(:num)', 'Admin::get_testimonial/$1');
    $routes->post('testimonials/update', 'Admin::update_testimonial');
    // SEO
    $routes->get('seo', 'Admin::seo');
    // Email Settings
    $routes->get('email-settings', 'Admin::email_settings');
    $routes->post('email-settings/update', 'Admin::update_email_settings');
    // Display Features
    $routes->get('features', 'Admin::features');
    $routes->post('features/update', 'Admin::update_features');
    // Theme Settings
    $routes->get('theme', 'Admin::theme_settings');
    $routes->post('theme/update', 'Admin::update_theme');
});

$routes->post('auth/do_login', 'Auth::do_login');
$routes->get('auth/logout', 'Auth::logout');
