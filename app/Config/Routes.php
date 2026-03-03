<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─── Public Routes ────────────────────────────────────────────────────────────
$routes->get('/', 'Home::index');
$routes->post('contact/submit', 'Home::submit_contact');

// ─── Admin Group (Protected by AdminAuth filter) ───────────────────────────────
$routes->group('admin', ['filter' => 'adminauth'], function ($routes) {

    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::dashboard');

    // ── Profile ──────────────────────────────────────────────────────────────
    $routes->get('profile', 'Admin::profile');
    $routes->post('profile/update', 'Admin::update_profile');
    $routes->post('profile/change-password', 'Admin::change_password');

    // ── Skills ───────────────────────────────────────────────────────────────
    $routes->get('skills', 'Admin::skills');
    $routes->post('skills/add', 'Admin::add_skill');
    $routes->get('skills/get/(:num)', 'Admin::get_skill/$1');
    $routes->post('skills/update', 'Admin::update_skill');
    $routes->post('skills/delete/(:num)', 'Admin::delete_skill/$1');

    // ── Projects ──────────────────────────────────────────────────────────────
    $routes->get('projects', 'Admin::projects');
    $routes->post('projects/add', 'Admin::add_project');
    $routes->get('projects/get/(:num)', 'Admin::get_project/$1');
    $routes->post('projects/update', 'Admin::update_project');
    $routes->post('projects/delete/(:num)', 'Admin::delete_project/$1');

    // ── Messages ─────────────────────────────────────────────────────────────
    $routes->get('messages', 'Admin::messages');
    $routes->post('messages/read/(:num)', 'Admin::mark_message_read/$1');
    $routes->post('messages/delete/(:num)', 'Admin::delete_message/$1');
    $routes->get('check-notifications', 'Admin::check_notifications');

    // ── Experience ───────────────────────────────────────────────────────────
    $routes->get('experience', 'Admin::experience');
    $routes->post('experience/add', 'Admin::add_experience');
    $routes->get('experience/get/(:num)', 'Admin::get_experience/$1');
    $routes->post('experience/update', 'Admin::update_experience');
    $routes->post('experience/delete/(:num)', 'Admin::delete_experience/$1');

    // ── Education ─────────────────────────────────────────────────────────────
    $routes->get('education', 'Admin::education');
    $routes->post('education/add', 'Admin::add_education');
    $routes->get('education/get/(:num)', 'Admin::get_education/$1');
    $routes->post('education/update', 'Admin::update_education');
    $routes->post('education/delete/(:num)', 'Admin::delete_education/$1'); // ← WAS MISSING!

    // ── Services ─────────────────────────────────────────────────────────────
    $routes->get('services', 'Admin::services');
    $routes->post('services/add', 'Admin::add_service');
    $routes->get('services/get/(:num)', 'Admin::get_service/$1');
    $routes->post('services/update', 'Admin::update_service');
    $routes->post('services/delete/(:num)', 'Admin::delete_service/$1'); // ← WAS MISSING!

    // ── Testimonials ──────────────────────────────────────────────────────────
    $routes->get('testimonials', 'Admin::testimonials');
    $routes->post('testimonials/add', 'Admin::add_testimonial');
    $routes->get('testimonials/get/(:num)', 'Admin::get_testimonial/$1');
    $routes->post('testimonials/update', 'Admin::update_testimonial');
    $routes->post('testimonials/delete/(:num)', 'Admin::delete_testimonial/$1'); // ← WAS MISSING!

    // ── Contact Info ──────────────────────────────────────────────────────────
    $routes->get('contact', 'Admin::contact');
    $routes->post('contact/update', 'Admin::update_contact');

    // ── SEO Settings ─────────────────────────────────────────────────────────
    $routes->get('seo', 'Admin::seo');
    $routes->post('seo/update', 'Admin::update_seo'); // ← WAS MISSING!

    // ── Email Settings ────────────────────────────────────────────────────────
    $routes->get('email-settings', 'Admin::email_settings');
    $routes->post('email-settings/update', 'Admin::update_email_settings');
    $routes->post('email-settings/test', 'Admin::test_email'); // ← NEW

    // ── Display Features ──────────────────────────────────────────────────────
    $routes->get('features', 'Admin::features');
    $routes->post('features/update', 'Admin::update_features');

    // ── Theme Settings ────────────────────────────────────────────────────────
    $routes->get('theme', 'Admin::theme_settings');
    $routes->post('theme/update', 'Admin::update_theme');

    // ── Site Settings (was pointing to dashboard — now proper) ────────────────
    $routes->get('settings', 'Admin::dashboard');
    $routes->get('site-settings', 'Admin::dashboard');
});

// ─── Auth Routes (No filter — handles own logic) ──────────────────────────────
$routes->get('admin/login', 'Auth::login');
$routes->post('auth/do_login', 'Auth::do_login');
$routes->get('auth/logout', 'Auth::logout');

// ─── Forgot / Reset Password ──────────────────────────────────────────────────
$routes->get('auth/forgot-password', 'Auth::forgot_password_form');
$routes->post('auth/forgot_password', 'Auth::forgot_password');
$routes->get('auth/reset/(:segment)', 'Auth::reset_password_form/$1');
$routes->post('auth/reset_password', 'Auth::reset_password');
$routes->get('auth/email-hint', 'Auth::email_hint');
