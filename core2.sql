-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 16, 2025 at 01:16 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `core2`
--

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

DROP TABLE IF EXISTS `providers`;
CREATE TABLE IF NOT EXISTS `providers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monthly_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_start` date NOT NULL,
  `contract_end` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`id`, `name`, `type`, `contact_person`, `contact_email`, `contact_phone`, `service_area`, `monthly_rate`, `status`, `contract_start`, `contract_end`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'asd', 'Transport', 'ddd', 'jc@gmail.com', '8794353545', 'asdsa', 123.00, 'Active', '2025-08-15', '2025-08-22', 'asd', '2025-08-15 07:33:59', '2025-08-15 07:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance` decimal(10,1) NOT NULL DEFAULT '0.0',
  `frequency` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_time` int NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
CREATE TABLE IF NOT EXISTS `schedules` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departure` time NOT NULL,
  `arrival` time NOT NULL,
  `frequency` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `capacity` int NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `name`, `route`, `vehicle_type`, `departure`, `arrival`, `frequency`, `status`, `start_date`, `end_date`, `capacity`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'asd', 'East-West Connector', 'Bus', '15:40:00', '04:41:00', 'Weekdays', 'Active', '2025-08-16', '2025-08-17', 12, 'asd', '2025-08-15 07:40:33', '2025-08-15 07:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `service_points`
--

DROP TABLE IF EXISTS `service_points`;
CREATE TABLE IF NOT EXISTS `service_points` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `services` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sops`
--

DROP TABLE IF EXISTS `sops`;
CREATE TABLE IF NOT EXISTS `sops` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review_date` date NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsibilities` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `procedures` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipment` text COLLATE utf8mb4_unicode_ci,
  `safety_notes` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sops`
--

INSERT INTO `sops` (`id`, `title`, `category`, `department`, `version`, `status`, `review_date`, `purpose`, `scope`, `responsibilities`, `procedures`, `equipment`, `safety_notes`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'asd', 'Safety', 'Operations', '1.0', 'Under Review', '2025-08-23', 'ad', 'adasdads', 'asdasd', 'asdasd', 'asdasd', 'asdasd', 'asdasda', '2025-08-15 07:32:06', '2025-08-15 07:32:06');

-- --------------------------------------------------------

--
-- Table structure for table `tariffs`
--

DROP TABLE IF EXISTS `tariffs`;
CREATE TABLE IF NOT EXISTS `tariffs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `per_km_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `per_hour_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `priority_multiplier` decimal(4,2) NOT NULL DEFAULT '1.00',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `effective_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tariffs`
--

INSERT INTO `tariffs` (`id`, `name`, `category`, `base_rate`, `per_km_rate`, `per_hour_rate`, `priority_multiplier`, `status`, `effective_date`, `expiry_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'asdasd', 'Transport', 223.00, 213.00, 123.00, 1.50, 'Inactive', '2025-08-15', '2025-08-30', 'asdasd', '2025-08-15 07:29:23', '2025-08-15 07:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `two_factor_auth`
--

DROP TABLE IF EXISTS `two_factor_auth`;
CREATE TABLE IF NOT EXISTS `two_factor_auth` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `secret_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `backup_codes` json DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `two_factor_auth`
--

INSERT INTO `two_factor_auth` (`id`, `user_id`, `secret_key`, `backup_codes`, `is_enabled`, `created_at`, `updated_at`) VALUES
(1, 1, 'GMZM6MUCYBCMERNBQBQ6DVJUSRWDPTOV', '[\"261968\", \"834101\", \"387541\", \"381460\", \"939217\", \"045932\", \"091571\", \"351879\"]', 0, '2025-08-15 13:07:14', '2025-08-15 13:07:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `email`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$b2nSQYAQiIEcQApQfzyKfeWCT8DeD6yqrYnZ/u.PoPG1551KhzkZi', 'admin@slate.com', 'admin', 1, '2025-08-15 13:54:25', '2025-08-15 13:28:14', '2025-08-15 13:54:25');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `two_factor_auth`
--
ALTER TABLE `two_factor_auth`
  ADD CONSTRAINT `two_factor_auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
