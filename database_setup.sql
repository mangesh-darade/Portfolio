-- Portfolio Database Setup
-- Run this SQL script in phpMyAdmin or your MySQL client

-- Drop existing tables if they exist (in reverse dependency order)
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `education`;
DROP TABLE IF EXISTS `contact_settings`;
DROP TABLE IF EXISTS `profile`;

-- Create Profile Table
CREATE TABLE `profile` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create Contact Settings Table
CREATE TABLE `contact_settings` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `map_iframe` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create Education Table
CREATE TABLE `education` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `degree` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `year_start` varchar(4) NOT NULL,
  `year_end` varchar(4) NOT NULL,
  `description` text DEFAULT NULL,
  `gpa` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create Messages Table
CREATE TABLE `messages` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Sample Profile Data
INSERT INTO `profile` (`full_name`, `bio`, `email`, `phone`, `address`, `linkedin`, `github`, `twitter`, `created_at`, `updated_at`) 
VALUES ('John Doe', 'Full Stack Developer with 5+ years of experience building modern web applications', 'john.doe@example.com', '+1 (555) 123-4567', 'San Francisco, CA, USA', 'https://linkedin.com/in/johndoe', 'https://github.com/johndoe', 'https://twitter.com/johndoe', NOW(), NOW());

-- Insert Sample Contact Settings
INSERT INTO `contact_settings` (`email`, `phone`, `address`, `created_at`, `updated_at`) 
VALUES ('contact@johndoe.com', '+1 (555) 987-6543', '123 Tech Street, San Francisco, CA 94102', NOW(), NOW());

-- Insert Sample Skills
INSERT INTO `skills` (`skill_name`, `category`, `skill_level`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
('PHP & CodeIgniter', 'Backend', 90, 1, 1, NOW(), NOW()),
('JavaScript & React', 'Frontend', 85, 2, 1, NOW(), NOW()),
('MySQL & Database Design', 'Database', 88, 3, 1, NOW(), NOW()),
('REST API Development', 'Backend', 92, 4, 1, NOW(), NOW()),
('HTML5 & CSS3', 'Frontend', 95, 5, 1, NOW(), NOW()),
('Git & Version Control', 'Tools', 87, 6, 1, NOW(), NOW());

-- Insert Sample Projects
INSERT INTO `projects` (`project_name`, `description`, `technologies`, `status`, `created_at`, `updated_at`) VALUES
('E-Commerce Platform', 'A full-featured online shopping platform with payment integration, inventory management, and customer analytics.', 'PHP, CodeIgniter, MySQL, Bootstrap, Stripe API', 1, NOW(), NOW()),
('Task Management System', 'Real-time collaborative task management application with team messaging, file sharing, and progress tracking.', 'React, Node.js, MongoDB, Socket.io, AWS', 1, NOW(), NOW()),
('Restaurant Booking App', 'Mobile-first restaurant reservation system with table management, menu display, and real-time availability.', 'Vue.js, Laravel, PostgreSQL, Twilio, PWA', 1, NOW(), NOW()),
('Portfolio CMS', 'Content management system for photographers and creative professionals to showcase their work with gallery management.', 'CodeIgniter 4, MySQL, jQuery, Dropzone.js', 1, NOW(), NOW());

-- Insert Sample Education
INSERT INTO `education` (`degree`, `institution`, `year_start`, `year_end`, `description`, `gpa`, `created_at`, `updated_at`) VALUES
('Bachelor of Science in Computer Science', 'Stanford University', '2015', '2019', 'Focused on software engineering, algorithms, and web technologies.', '3.8', NOW(), NOW()),
('Master of Computer Applications', 'MIT', '2019', '2021', 'Specialized in cloud computing and distributed systems.', '3.9', NOW(), NOW());

-- Insert Sample Messages
INSERT INTO `messages` (`name`, `email`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
('Sarah Johnson', 'sarah.j@company.com', 'Hi! I came across your portfolio and I\'m impressed with your work. Would you be interested in discussing a potential project collaboration?', 0, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
('Michael Chen', 'mchen@techstartup.io', 'We are looking for a senior developer to join our team. Your experience with CodeIgniter and React looks like a great fit!', 0, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
('Emma Williams', 'emma.w@design.co', 'Love the clean design of your portfolio! Can you share what framework you used?', 1, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY));
