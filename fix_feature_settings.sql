-- Fix: Seed feature_settings table so homepage sections are visible
-- Run this in phpMyAdmin on your `portfolio` database

INSERT INTO `feature_settings` (`feature_key`, `feature_name`, `is_enabled`, `display_order`, `created_at`, `updated_at`) VALUES
('hero',         'Hero / Introduction',  1, 1, NOW(), NOW()),
('skills',       'Skills Section',       1, 2, NOW(), NOW()),
('projects',     'Projects Section',     1, 3, NOW(), NOW()),
('experience',   'Experience (Resume)',  1, 4, NOW(), NOW()),
('education',    'Education (Resume)',   1, 5, NOW(), NOW()),
('services',     'Services Section',     1, 6, NOW(), NOW()),
('testimonials', 'Testimonials Section', 1, 7, NOW(), NOW()),
('contact',      'Contact Section',      1, 8, NOW(), NOW())
ON DUPLICATE KEY UPDATE `is_enabled` = VALUES(`is_enabled`);
