-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 10, 2026 at 04:03 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$EPTd2E4ceEO1dtyw9Ax7huK7ga6sGVWYlXmQ0YYp57gOt.RKRxDgG', NULL, '2026-02-10 04:55:57', '2026-02-10 10:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `contact_settings`
--

DROP TABLE IF EXISTS `contact_settings`;
CREATE TABLE IF NOT EXISTS `contact_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `map_iframe` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_settings`
--

INSERT INTO `contact_settings` (`id`, `email`, `phone`, `address`, `map_iframe`, `created_at`, `updated_at`) VALUES
(1, 'contact@johndoe.com', '+1 (555) 987-6543', '123 Tech Street, San Francisco, CA 94102', NULL, '2026-02-10 16:34:53', '2026-02-10 16:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

DROP TABLE IF EXISTS `education`;
CREATE TABLE IF NOT EXISTS `education` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `degree` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `year_start` varchar(4) NOT NULL,
  `year_end` varchar(4) NOT NULL,
  `description` text,
  `gpa` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `degree`, `institution`, `year_start`, `year_end`, `description`, `gpa`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor of Science in Computer Science', 'Stanford University', '2015', '2019', 'Focused on software engineering, algorithms, and web technologies.', '3.8', '2026-02-10 16:34:53', '2026-02-10 16:34:53'),
(2, 'Master of Computer Applications', 'MIT', '2019', '2021', 'Specialized in cloud computing and distributed systems.', '3.9', '2026-02-10 16:34:53', '2026-02-10 16:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

DROP TABLE IF EXISTS `email_settings`;
CREATE TABLE IF NOT EXISTS `email_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `protocol` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'smtp',
  `smtp_host` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_user` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_pass` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `smtp_port` int NOT NULL DEFAULT '587',
  `smtp_crypto` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'tls',
  `from_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `from_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_settings`
--

INSERT INTO `email_settings` (`id`, `protocol`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `smtp_crypto`, `from_email`, `from_name`, `updated_at`) VALUES
(1, 'smtp', 'smtp.gmail.com', 'sateri.mangesh@gmail.com', 'bdnp wwnm inkd ijoh', 465, 'ssl', 'admin@example.com', 'Portfolio Admin', '2026-02-10 14:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

DROP TABLE IF EXISTS `experience`;
CREATE TABLE IF NOT EXISTS `experience` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `end_date` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feature_settings`
--

DROP TABLE IF EXISTS `feature_settings`;
CREATE TABLE IF NOT EXISTS `feature_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `feature_key` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `feature_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `feature_key` (`feature_key`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feature_settings`
--

INSERT INTO `feature_settings` (`id`, `feature_key`, `feature_name`, `is_enabled`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'hero', 'Hero Section', 1, 1, '2026-02-10 15:28:42', '2026-02-10 10:24:47'),
(2, 'skills', 'Skills Section', 1, 2, '2026-02-10 15:28:42', '2026-02-10 10:24:47'),
(3, 'experience', 'Experience Section', 1, 3, '2026-02-10 15:28:42', '2026-02-10 10:24:47'),
(4, 'education', 'Education Section', 1, 4, '2026-02-10 15:28:42', '2026-02-10 10:24:47'),
(5, 'services', 'Services Section', 1, 5, '2026-02-10 15:28:42', '2026-02-10 10:24:47'),
(6, 'projects', 'Projects Section', 1, 6, '2026-02-10 15:28:42', '2026-02-10 10:24:47'),
(7, 'testimonials', 'Testimonials Section', 1, 7, '2026-02-10 15:28:42', '2026-02-10 10:24:47'),
(8, 'contact', 'Contact Section', 1, 8, '2026-02-10 15:28:42', '2026-02-10 10:24:47');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(4, 'sumit', 'sateri.mangesh@gmail.com', 'jkgfdzxhjb', 1, '2026-02-10 12:03:15', '2026-02-10 12:11:09'),
(5, 'sumit', 'kharde@gmail.com', 'jhcgfcvjhvgb vhbj', 1, '2026-02-10 12:08:48', '2026-02-10 12:11:03'),
(7, 'Mangesh sateri', 'mangeshdarade9552@gmail.com', 'test ghvhvhvhgvgvvh', 1, '2026-02-10 14:50:47', '2026-02-10 14:52:11'),
(8, 'Mangesh Darade', 'mangeshdarade9552@gmail.com', 'Test gygyguygug', 1, '2026-02-10 15:55:52', '2026-02-10 15:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-02-10-100000', 'App\\Database\\Migrations\\InitialSetup', 'default', 'App', 1770719142, 1),
(2, '2025-02-10-100000', 'App\\Database\\Migrations\\CreateProfileTable', 'default', 'App', 1770733153, 2),
(3, '2025-02-10-100001', 'App\\Database\\Migrations\\AdditionalTables', 'default', 'App', 1770733153, 2),
(4, '2025-02-10-100002', 'App\\Database\\Migrations\\CreateContactSettingsTable', 'default', 'App', 1770733153, 2),
(5, '2026-02-10-100003', 'App\\Database\\Migrations\\CreateExperienceTable', 'default', 'App', 1770733153, 2),
(6, '2026-02-10-100004', 'App\\Database\\Migrations\\CreateServicesTable', 'default', 'App', 1770733570, 3),
(7, '2026-02-10-100005', 'App\\Database\\Migrations\\CreateTestimonialsTable', 'default', 'App', 1770733731, 4),
(8, '2026-02-10-100006', 'App\\Database\\Migrations\\CreateSeoSettingsTable', 'default', 'App', 1770734029, 5),
(9, '2026-02-10-100007', 'App\\Database\\Migrations\\CreateEmailSettingsTable', 'default', 'App', 1770734443, 6),
(10, '2026-02-10-100008', 'App\\Database\\Migrations\\CreateFeatureSettingsTable', 'default', 'App', 1770737322, 7),
(11, '2026-02-10-100009', 'App\\Database\\Migrations\\CreateThemeSettingsTable', 'default', 'App', 1770738634, 8);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `bio` text,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `total_views` int DEFAULT '0',
  `resume` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `full_name`, `bio`, `email`, `phone`, `address`, `profile_image`, `total_views`, `resume`, `linkedin`, `github`, `twitter`, `created_at`, `updated_at`) VALUES
(1, 'Mangesh Darade', 'Full Stack Developer with 3+ years of experience building modern web applications', 'sateri.mangesh@gmail.com', '+1 (555) 123-4567', 'San Francisco, CA, USA', NULL, 45, NULL, 'https://linkedin.com/in/johndoe', 'https://github.com/johndoe', 'https://twitter.com/johndoe', '2026-02-10 16:34:53', '2026-02-10 16:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `technologies` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `description`, `image`, `technologies`, `status`, `created_at`, `updated_at`) VALUES
(1, 'E-Commerce Platform', 'A full-featured online shopping platform with payment integration, inventory management, and customer analytics.', '1770732631_d524fe48ceab7ea1508f.jpeg', 'PHP, CodeIgniter, MySQL, Bootstrap, Stripe API', 1, '2026-02-10 11:04:53', '2026-02-10 08:40:31'),
(2, 'Task Management System', 'Real-time collaborative task management application with team messaging, file sharing, and progress tracking.', NULL, 'React, Node.js, MongoDB, Socket.io, AWS', 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53'),
(3, 'Restaurant Booking App', 'Mobile-first restaurant reservation system with table management, menu display, and real-time availability.', NULL, 'Vue.js, Laravel, PostgreSQL, Twilio, PWA', 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53'),
(4, 'Portfolio CMS', 'Content management system for photographers and creative professionals to showcase their work with gallery management.', NULL, 'CodeIgniter 4, MySQL, jQuery, Dropzone.js', 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53');

-- --------------------------------------------------------

--
-- Table structure for table `seo_settings`
--

DROP TABLE IF EXISTS `seo_settings`;
CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'My Portfolio',
  `site_description` text COLLATE utf8mb4_general_ci,
  `site_keywords` text COLLATE utf8mb4_general_ci,
  `site_author` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seo_settings`
--

INSERT INTO `seo_settings` (`id`, `site_title`, `site_description`, `site_keywords`, `site_author`, `og_image`, `updated_at`) VALUES
(1, 'My Portfolio - Creative Developer', 'Welcome to my professional portfolio showcasing my projects and skills.', 'portfolio, developer, web design, coding', 'Admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `icon` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'fas fa-cogs',
  `price` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `skill_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `skill_level` int NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill_name`, `category`, `skill_level`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PHP', 'Backend', 90, 1, 1, '2026-02-10 10:25:57', '2026-02-10 10:25:57'),
(2, 'CodeIgniter 4', 'Backend', 85, 2, 1, '2026-02-10 10:25:57', '2026-02-10 10:25:57'),
(3, 'JavaScript', 'Frontend', 80, 3, 1, '2026-02-10 10:25:57', '2026-02-10 10:25:57'),
(4, 'PHP & CodeIgniter', 'Backend', 90, 1, 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53'),
(5, 'JavaScript & React', 'Frontend', 85, 2, 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53'),
(6, 'MySQL & Database Design', 'Database', 88, 3, 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53'),
(7, 'REST API Development', 'Backend', 92, 4, 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53'),
(8, 'HTML5 & CSS3', 'Frontend', 95, 5, 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53'),
(9, 'Git & Version Control', 'Tools', 87, 6, 1, '2026-02-10 11:04:53', '2026-02-10 11:04:53'),
(10, 'sad', 'Backend', 50, 0, 1, '2026-02-10 06:08:53', '2026-02-10 06:11:42');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `company` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quote` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theme_settings`
--

DROP TABLE IF EXISTS `theme_settings`;
CREATE TABLE IF NOT EXISTS `theme_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `theme_style` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'modern_dark',
  `primary_color` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#6c5ce7',
  `secondary_color` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#a29bfe',
  `bg_color` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#0f0c29',
  `text_color` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#ffffff',
  `font_family` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Outfit',
  `border_radius` int NOT NULL DEFAULT '15',
  `glass_opacity` decimal(3,2) NOT NULL DEFAULT '0.10',
  `card_blur` int NOT NULL DEFAULT '10',
  `custom_css` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theme_settings`
--

INSERT INTO `theme_settings` (`id`, `theme_style`, `primary_color`, `secondary_color`, `bg_color`, `text_color`, `font_family`, `border_radius`, `glass_opacity`, `card_blur`, `custom_css`, `created_at`, `updated_at`) VALUES
(1, 'modern_dark', '#6c5ce7', '#a29bfe', '#0f0c29', '#ffffff', 'Outfit', 20, 0.10, 10, '', '2026-02-10 15:50:34', '2026-02-10 10:24:17');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
