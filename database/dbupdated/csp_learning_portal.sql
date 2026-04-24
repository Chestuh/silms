-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2026 at 06:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `csp_learning_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_honors`
--

CREATE TABLE `academic_honors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `honor_type` varchar(100) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `school_year` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admission_records`
--

CREATE TABLE `admission_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `record_type` enum('admission','transfer','readmission','leave') NOT NULL,
  `date_processed` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_templates`
--

CREATE TABLE `assessment_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `learning_material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `assessment_type` enum('quiz','exam','assignment','project') NOT NULL DEFAULT 'quiz',
  `number_of_questions` int(11) NOT NULL DEFAULT 0,
  `passing_score` decimal(5,2) DEFAULT NULL,
  `time_limit_minutes` int(11) DEFAULT NULL,
  `questions_json` longtext NOT NULL,
  `status` enum('pending_review','approved','rejected','published') NOT NULL DEFAULT 'pending_review',
  `instructor_feedback` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `generation_metadata` text DEFAULT NULL,
  `ai_generated` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_learning_aids`
--

CREATE TABLE `auto_learning_aids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `release_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','scheduled','available','expired') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 3,
  `grade_level` enum('7','8','9','10','11','12') DEFAULT NULL,
  `semester` enum('1st Semester','2nd Semester') DEFAULT NULL,
  `completion_status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `code`, `title`, `units`, `grade_level`, `semester`, `completion_status`, `instructor_id`, `created_at`, `updated_at`) VALUES
(1, 'G7-MATH-201', 'G7 Mathematics - Algebra', 3, '7', NULL, 'pending', 1, '2026-04-23 06:38:58', '2026-04-23 06:38:58'),
(2, 'G7-SCI-201', 'G7 Science - General', 3, '7', NULL, 'pending', 1, '2026-04-23 06:38:58', '2026-04-23 06:38:58'),
(3, 'G7 - ENG', 'English 7', 3, '7', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(4, 'G7 - FIL', 'Filipino 7', 2, '7', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(5, 'G7 - MATH', 'Mathematics 7', 3, '7', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(6, 'G7 - SCI', 'Science 7', 3, '7', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(7, 'G7 - AP', 'Araling Panlipunan 7', 3, '7', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(8, 'G7 - ESP', 'Edukasyon sa Pagpapakatao 7', 2, '7', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(9, 'G7 - MAPEH', 'MAPEH 7 (Music, Arts, PE, Health)', 2, '7', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(10, 'G7 - TLE', 'Technology and Livelihood Education 7', 2, '7', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(11, 'G8 - ENG', 'English 8', 3, '8', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(12, 'G8 - FIL', 'Filipino 8', 2, '8', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(13, 'G8 - MATH', 'Mathematics 8 (Algebra)', 3, '8', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(14, 'G8 - SCI', 'Science 8 (Biology)', 3, '8', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(15, 'G8 - AP', 'Araling Panlipunan 8 (Asian Studies)', 3, '8', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(16, 'G8 - ESP', 'Edukasyon sa Pagpapakatao 8', 2, '8', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(17, 'G8 - MAPEH', 'MAPEH 8 (Music, Arts, PE, Health)', 2, '8', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(18, 'G8 - TLE', 'Technology and Livelihood Education 8', 2, '8', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(19, 'G9 - ENG', 'English 9', 3, '9', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(20, 'G9 - FIL', 'Filipino 9', 2, '9', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(21, 'G9 - MATH', 'Mathematics 9 (Geometry)', 3, '9', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(22, 'G9 - SCI', 'Science 9 (Chemistry)', 3, '9', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(23, 'G9 - AP', 'Araling Panlipunan 9 (Economics)', 3, '9', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(24, 'G9 - ESP', 'Edukasyon sa Pagpapakatao 9', 2, '9', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(25, 'G9 - MAPEH', 'MAPEH 9 (Music, Arts, PE, Health)', 2, '9', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(26, 'G9 - TLE', 'Technology and Livelihood Education 9', 2, '9', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(27, 'G10 - ENG', 'English 10', 3, '10', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(28, 'G10 - FIL', 'Filipino 10', 2, '10', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(29, 'G10 - MATH', 'Mathematics 10 (Statistics & Probability / Advanced Algebra)', 3, '10', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(30, 'G10 - SCI', 'Science 10 (Physics)', 3, '10', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(31, 'G10 - AP', 'Araling Panlipunan 10 (Contemporary Issues)', 3, '10', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(32, 'G10 - ESP', 'Edukasyon sa Pagpapakatao 10', 2, '10', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(33, 'G10 - MAPEH', 'MAPEH 10 (Music, Arts, PE, Health)', 2, '10', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(34, 'G10 - TLE', 'Technology and Livelihood Education 10', 2, '10', NULL, 'pending', 1, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(35, 'CS101', 'Introduction to Programming', 3, NULL, NULL, 'pending', 2, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(36, 'CS102', 'Data Structures', 3, NULL, NULL, 'pending', 2, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(37, 'CS201', 'Web Development Fundamentals', 3, NULL, NULL, 'pending', 2, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(38, 'CS202', 'Database Management Systems', 4, NULL, NULL, 'pending', 2, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(39, 'CS301', 'Software Engineering', 3, NULL, NULL, 'pending', 2, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(40, 'CS302', 'Advanced Algorithms', 3, NULL, NULL, 'pending', 2, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(41, 'CS303', 'Object-Oriented Programming', 3, NULL, NULL, 'pending', 2, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(42, 'CS304', 'Mobile App Development', 3, NULL, NULL, 'pending', 2, '2026-04-23 06:39:02', '2026-04-23 06:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `course_curriculum_alignments`
--

CREATE TABLE `course_curriculum_alignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `learning_material_id` bigint(20) UNSIGNED NOT NULL,
  `learning_outcome_id` bigint(20) UNSIGNED DEFAULT NULL,
  `curriculum_standard_id` bigint(20) UNSIGNED DEFAULT NULL,
  `competency` varchar(255) DEFAULT NULL,
  `alignment_strength` int(11) NOT NULL DEFAULT 1,
  `alignment_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credential_requests`
--

CREATE TABLE `credential_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `credential_type` varchar(100) DEFAULT NULL,
  `status` enum('pending','processing','ready','released') NOT NULL DEFAULT 'pending',
  `letter_path` varchar(500) DEFAULT NULL,
  `payment_cleared_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `curriculum_standards`
--

CREATE TABLE `curriculum_standards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `subject_area` varchar(255) NOT NULL,
  `grade_level` varchar(255) DEFAULT NULL,
  `competencies` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disciplinary_records`
--

CREATE TABLE `disciplinary_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `incident_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sanction` varchar(255) DEFAULT NULL,
  `status` enum('pending','resolved','appealed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `school_year` varchar(20) DEFAULT NULL,
  `status` enum('enrolled','dropped','completed') NOT NULL DEFAULT 'enrolled',
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `semester`, `school_year`, `status`, `attachments`, `created_at`, `updated_at`) VALUES
(1, 2, 39, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(2, 2, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(3, 2, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(4, 3, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(5, 3, 39, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(6, 3, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(7, 3, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(8, 4, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(9, 4, 37, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(10, 4, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(11, 5, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(12, 5, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(13, 5, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(14, 6, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(15, 6, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(16, 6, 37, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(17, 6, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(18, 6, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(19, 7, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(20, 7, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(21, 7, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(22, 7, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(23, 8, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(24, 8, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(25, 8, 37, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(26, 8, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(27, 8, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(28, 9, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(29, 9, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(30, 9, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(31, 10, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(32, 10, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(33, 10, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(34, 10, 39, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(35, 10, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(36, 10, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(37, 11, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(38, 11, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(39, 11, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(40, 12, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(41, 12, 37, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(42, 12, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(43, 12, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(44, 12, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(45, 13, 37, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(46, 13, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(47, 13, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(48, 14, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(49, 14, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(50, 14, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(51, 14, 39, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(52, 14, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(53, 14, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(54, 15, 37, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(55, 15, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(56, 15, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(57, 16, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(58, 16, 39, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(59, 16, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(60, 16, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(61, 17, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(62, 17, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(63, 17, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(64, 18, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(65, 18, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(66, 18, 37, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(67, 18, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(68, 18, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(69, 19, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(70, 19, 39, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(71, 19, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(72, 19, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(73, 19, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(74, 20, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(75, 20, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(76, 20, 39, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(77, 20, 40, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(78, 20, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(79, 21, 35, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(80, 21, 36, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(81, 21, 38, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(82, 21, 39, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(83, 21, 41, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(84, 21, 42, '1st', '2024-2025', 'enrolled', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(85, 22, 1, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(86, 22, 2, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(87, 22, 3, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(88, 22, 4, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(89, 22, 5, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(90, 22, 6, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(91, 22, 7, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(92, 22, 8, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(93, 22, 9, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30'),
(94, 22, 10, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `fee_type` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','paid','overdue') NOT NULL DEFAULT 'pending',
  `payment_method` enum('gcash','cash','bank_transfer') DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `payment_proof_path` varchar(500) DEFAULT NULL,
  `payment_status` enum('pending','verified','rejected') DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `midterm_grade` decimal(5,2) DEFAULT NULL,
  `final_grade` decimal(5,2) DEFAULT NULL,
  `gwa_contribution` decimal(6,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rubric_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `enrollment_id`, `midterm_grade`, `final_grade`, `gwa_contribution`, `created_at`, `updated_at`, `rubric_id`) VALUES
(1, 1, 85.00, 86.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(2, 2, 78.00, 96.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(3, 3, 86.00, 87.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(4, 4, 82.00, 95.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(5, 5, 88.00, 97.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(6, 6, 93.00, 80.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(7, 7, 94.00, 81.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(8, 8, 91.00, 92.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(9, 9, 95.00, 93.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(10, 10, 90.00, 89.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(11, 11, 96.00, 97.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(12, 12, 82.00, 88.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(13, 13, 82.00, 79.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(14, 14, 86.00, 96.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(15, 15, 96.00, 81.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(16, 16, 75.00, 82.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(17, 17, 81.00, 81.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(18, 18, 85.00, 91.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(19, 19, 77.00, 82.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(20, 20, 91.00, 85.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(21, 21, 81.00, 96.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(22, 22, 79.00, 87.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(23, 23, 96.00, 98.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(24, 24, 87.00, 80.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(25, 25, 95.00, 95.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(26, 26, 79.00, 93.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(27, 27, 90.00, 98.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(28, 28, 95.00, 89.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(29, 29, 94.00, 98.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(30, 30, 95.00, 90.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(31, 31, 82.00, 90.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(32, 32, 86.00, 90.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(33, 33, 76.00, 77.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(34, 34, 84.00, 96.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(35, 35, 75.00, 86.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(36, 36, 94.00, 76.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(37, 37, 80.00, 84.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(38, 38, 77.00, 97.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(39, 39, 75.00, 98.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(40, 40, 88.00, 99.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(41, 41, 78.00, 99.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(42, 42, 86.00, 81.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(43, 43, 85.00, 90.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(44, 44, 89.00, 93.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(45, 45, 91.00, 95.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(46, 46, 92.00, 89.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(47, 47, 91.00, 82.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(48, 48, 90.00, 79.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(49, 49, 88.00, 87.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(50, 50, 82.00, 83.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(51, 51, 86.00, 99.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(52, 52, 89.00, 93.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(53, 53, 81.00, 78.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(54, 54, 80.00, 94.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(55, 55, 95.00, 78.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(56, 56, 96.00, 95.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(57, 57, 94.00, 83.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(58, 58, 89.00, 89.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(59, 59, 77.00, 80.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(60, 60, 88.00, 92.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(61, 61, 83.00, 96.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(62, 62, 96.00, 83.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(63, 63, 83.00, 78.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(64, 64, 85.00, 85.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(65, 65, 98.00, 83.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(66, 66, 92.00, 95.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(67, 67, 77.00, 96.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(68, 68, 88.00, 87.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(69, 69, 81.00, 96.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(70, 70, 77.00, 83.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(71, 71, 75.00, 78.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(72, 72, 78.00, 81.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(73, 73, 75.00, 80.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(74, 74, 83.00, 93.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(75, 75, 83.00, 76.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(76, 76, 79.00, 79.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(77, 77, 96.00, 86.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(78, 78, 82.00, 99.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(79, 79, 77.00, 79.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(80, 80, 76.00, 97.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(81, 81, 78.00, 91.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(82, 82, 79.00, 96.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(83, 83, 77.00, 97.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL),
(84, 84, 82.00, 77.00, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `user_id`, `department`, `created_at`, `updated_at`) VALUES
(1, 1, '7', '2026-04-23 06:38:58', '2026-04-23 06:38:58'),
(2, 4, 'Grade 7', '2026-04-23 06:39:02', '2026-04-23 06:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `job_aids`
--

CREATE TABLE `job_aids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `aid_type` enum('study_guide','tutorial','career_guidance','skill_guide','reference') NOT NULL DEFAULT 'study_guide',
  `content` longtext NOT NULL,
  `metadata` text DEFAULT NULL,
  `topic_focus` varchar(255) NOT NULL,
  `career_connections` text DEFAULT NULL,
  `relevance_score` decimal(3,2) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `useful_count` int(11) NOT NULL DEFAULT 0,
  `last_viewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_aids`
--

CREATE TABLE `learning_aids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `aid_type` enum('summary','quiz','flashcard','reviewer','study_guide') NOT NULL DEFAULT 'summary',
  `content` text NOT NULL,
  `metadata` text DEFAULT NULL,
  `generation_tokens_used` int(11) NOT NULL DEFAULT 0,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_aid_interactions`
--

CREATE TABLE `learning_aid_interactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `learning_aid_id` bigint(20) UNSIGNED NOT NULL,
  `interaction_type` enum('view','bookmark','flag_difficult','share','request_more') NOT NULL DEFAULT 'view',
  `time_spent_seconds` int(11) NOT NULL DEFAULT 0,
  `quiz_score` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `format` enum('pdf','video','link','document','quiz') NOT NULL DEFAULT 'document',
  `file_path` varchar(500) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `difficulty_level` enum('easy','medium','hard') NOT NULL DEFAULT 'medium',
  `order_index` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `release_date` timestamp NULL DEFAULT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `approval_status` varchar(20) NOT NULL DEFAULT 'pending',
  `completion_status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `admin_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `learning_materials`
--

INSERT INTO `learning_materials` (`id`, `course_id`, `title`, `description`, `format`, `file_path`, `url`, `difficulty_level`, `order_index`, `release_date`, `archived`, `approval_status`, `completion_status`, `admin_comment`, `created_at`, `updated_at`) VALUES
(1, 35, 'Welcome and Syllabus', 'Course overview and requirements', 'document', NULL, NULL, 'easy', 1, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(2, 35, 'Variables and Data Types', 'Video lesson on basics', 'video', NULL, NULL, 'easy', 2, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(3, 35, 'Control Flow and Loops', 'Interactive module on conditionals', 'video', NULL, NULL, 'easy', 3, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(4, 35, 'Functions and Scope', 'Comprehensive guide with examples', 'document', NULL, NULL, 'medium', 4, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(5, 36, 'Arrays and Lists', 'Introduction to linear structures', 'document', NULL, NULL, 'medium', 1, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(6, 36, 'Linked Lists Implementation', 'Video tutorial with live coding', 'video', NULL, NULL, 'medium', 2, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(7, 36, 'Trees and Graphs', 'Advanced data structures guide', 'document', NULL, NULL, 'hard', 3, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(8, 36, 'Sorting Algorithms', 'Complete algorithm reference', 'document', NULL, NULL, 'hard', 4, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(9, 37, 'HTML & CSS Basics', 'Frontend fundamentals', 'video', NULL, NULL, 'easy', 1, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(10, 37, 'Responsive Design', 'Mobile-first approach guide', 'document', NULL, NULL, 'medium', 2, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(11, 37, 'JavaScript Essentials', 'Client-side scripting tutorial', 'video', NULL, NULL, 'medium', 3, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(12, 38, 'Database Design', 'Conceptual and logical models', 'document', NULL, NULL, 'medium', 1, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(13, 38, 'SQL Fundamentals', 'Query language tutorial', 'video', NULL, NULL, 'medium', 2, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(14, 39, 'SDLC Methodologies', 'Development life cycle approaches', 'document', NULL, NULL, 'hard', 1, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(15, 39, 'Design Patterns', 'Reusable solutions guide', 'video', NULL, NULL, 'hard', 2, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(16, 40, 'Algorithm Complexity Analysis', 'Big O notation explained', 'document', NULL, NULL, 'hard', 1, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(17, 40, 'Dynamic Programming', 'Advanced techniques tutorial', 'video', NULL, NULL, 'hard', 2, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(18, 41, 'OOP Principles', 'Encapsulation, inheritance, polymorphism', 'video', NULL, NULL, 'medium', 1, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(19, 41, 'Class Design and Relationships', 'UML and class hierarchies', 'document', NULL, NULL, 'medium', 2, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(20, 42, 'Mobile Development Framework', 'Getting started guide', 'video', NULL, NULL, 'medium', 1, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(21, 42, 'Building User Interfaces', 'UI/UX best practices', 'document', NULL, NULL, 'medium', 2, NULL, 0, 'pending', 'pending', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `learning_outcomes`
--

CREATE TABLE `learning_outcomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `bloom_level` enum('remember','understand','apply','analyze','evaluate','create') NOT NULL DEFAULT 'understand',
  `assessment_criteria` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_path_rules`
--

CREATE TABLE `learning_path_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `source_course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `source_material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_progress`
--

CREATE TABLE `learning_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `progress_percent` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `time_spent_minutes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `learning_progress`
--

INSERT INTO `learning_progress` (`id`, `student_id`, `material_id`, `progress_percent`, `time_spent_minutes`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 14, 79, 165, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(2, 2, 15, 94, 203, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(3, 2, 18, 41, 149, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(4, 2, 19, 89, 163, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(5, 2, 20, 86, 112, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(6, 2, 21, 97, 187, NULL, '2026-04-23 06:39:10', '2026-04-17 06:39:10'),
(7, 3, 12, 87, 205, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(8, 3, 13, 91, 31, NULL, '2026-04-23 06:39:10', '2026-04-21 06:39:10'),
(9, 3, 14, 92, 123, NULL, '2026-04-23 06:39:10', '2026-04-22 06:39:10'),
(10, 3, 15, 95, 190, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(11, 3, 16, 93, 164, NULL, '2026-04-23 06:39:10', '2026-04-15 06:39:10'),
(12, 3, 17, 91, 193, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(13, 3, 18, 92, 187, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(14, 3, 19, 83, 124, NULL, '2026-04-23 06:39:10', '2026-04-16 06:39:10'),
(15, 4, 5, 89, 169, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(16, 4, 6, 90, 24, NULL, '2026-04-23 06:39:10', '2026-04-16 06:39:10'),
(17, 4, 7, 87, 170, NULL, '2026-04-23 06:39:10', '2026-04-19 06:39:10'),
(18, 4, 8, 99, 163, NULL, '2026-04-23 06:39:10', '2026-04-18 06:39:10'),
(19, 4, 9, 52, 89, NULL, '2026-04-23 06:39:10', '2026-04-18 06:39:10'),
(20, 4, 10, 86, 216, NULL, '2026-04-23 06:39:10', '2026-04-19 06:39:10'),
(21, 4, 11, 94, 26, NULL, '2026-04-23 06:39:10', '2026-04-16 06:39:10'),
(22, 4, 12, 47, 193, NULL, '2026-04-23 06:39:10', '2026-04-17 06:39:10'),
(23, 4, 13, 89, 88, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(24, 5, 5, 90, 70, NULL, '2026-04-23 06:39:10', '2026-04-21 06:39:10'),
(25, 5, 6, 99, 147, NULL, '2026-04-23 06:39:10', '2026-04-14 06:39:10'),
(26, 5, 7, 93, 177, NULL, '2026-04-23 06:39:10', '2026-04-16 06:39:10'),
(27, 5, 8, 90, 52, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(28, 5, 12, 36, 36, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(29, 5, 13, 79, 167, NULL, '2026-04-23 06:39:10', '2026-04-14 06:39:10'),
(30, 5, 20, 49, 113, NULL, '2026-04-23 06:39:10', '2026-04-18 06:39:10'),
(31, 5, 21, 61, 229, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(32, 6, 1, 100, 177, '2026-04-22 06:39:10', '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(33, 6, 2, 99, 194, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(34, 6, 3, 67, 191, NULL, '2026-04-23 06:39:10', '2026-04-19 06:39:10'),
(35, 6, 4, 91, 189, NULL, '2026-04-23 06:39:10', '2026-04-19 06:39:10'),
(36, 6, 5, 85, 83, NULL, '2026-04-23 06:39:10', '2026-04-17 06:39:10'),
(37, 6, 6, 87, 114, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(38, 6, 7, 99, 61, NULL, '2026-04-23 06:39:10', '2026-04-22 06:39:10'),
(39, 6, 8, 49, 222, NULL, '2026-04-23 06:39:10', '2026-04-14 06:39:10'),
(40, 6, 9, 96, 220, NULL, '2026-04-23 06:39:10', '2026-04-14 06:39:10'),
(41, 6, 10, 51, 45, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(42, 6, 11, 91, 171, NULL, '2026-04-23 06:39:10', '2026-04-18 06:39:10'),
(43, 6, 16, 85, 106, NULL, '2026-04-23 06:39:10', '2026-04-15 06:39:10'),
(44, 6, 17, 85, 163, NULL, '2026-04-23 06:39:10', '2026-04-22 06:39:10'),
(45, 6, 18, 44, 63, NULL, '2026-04-23 06:39:10', '2026-04-14 06:39:10'),
(46, 6, 19, 95, 114, NULL, '2026-04-23 06:39:10', '2026-04-17 06:39:10'),
(47, 7, 1, 89, 59, NULL, '2026-04-23 06:39:10', '2026-04-17 06:39:10'),
(48, 7, 2, 87, 192, NULL, '2026-04-23 06:39:10', '2026-04-14 06:39:10'),
(49, 7, 3, 95, 152, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(50, 7, 4, 88, 190, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(51, 7, 5, 100, 22, '2026-04-16 06:39:10', '2026-04-23 06:39:10', '2026-04-16 06:39:10'),
(52, 7, 6, 93, 137, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(53, 7, 7, 99, 109, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(54, 7, 8, 92, 51, NULL, '2026-04-23 06:39:10', '2026-04-15 06:39:10'),
(55, 7, 16, 97, 216, NULL, '2026-04-23 06:39:10', '2026-04-17 06:39:10'),
(56, 7, 17, 93, 227, NULL, '2026-04-23 06:39:10', '2026-04-17 06:39:10'),
(57, 7, 20, 53, 50, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(58, 7, 21, 92, 104, NULL, '2026-04-23 06:39:10', '2026-04-19 06:39:10'),
(59, 8, 1, 99, 136, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(60, 8, 2, 98, 76, NULL, '2026-04-23 06:39:10', '2026-04-17 06:39:10'),
(61, 8, 3, 96, 96, NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(62, 8, 4, 45, 137, NULL, '2026-04-23 06:39:10', '2026-04-15 06:39:10'),
(63, 8, 5, 86, 90, NULL, '2026-04-23 06:39:10', '2026-04-22 06:39:10'),
(64, 8, 6, 88, 200, NULL, '2026-04-23 06:39:10', '2026-04-13 06:39:10'),
(65, 8, 7, 93, 26, NULL, '2026-04-23 06:39:10', '2026-04-18 06:39:10'),
(66, 8, 8, 99, 226, NULL, '2026-04-23 06:39:10', '2026-04-15 06:39:10'),
(67, 8, 9, 89, 201, NULL, '2026-04-23 06:39:10', '2026-04-20 06:39:10'),
(68, 8, 10, 97, 73, NULL, '2026-04-23 06:39:10', '2026-04-15 06:39:10'),
(69, 8, 11, 49, 220, NULL, '2026-04-23 06:39:10', '2026-04-14 06:39:10'),
(70, 8, 12, 77, 96, NULL, '2026-04-23 06:39:10', '2026-04-16 06:39:10'),
(71, 8, 13, 98, 34, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(72, 8, 18, 95, 73, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(73, 8, 19, 96, 77, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(74, 9, 12, 98, 28, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(75, 9, 13, 90, 66, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(76, 9, 16, 99, 41, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(77, 9, 17, 87, 29, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(78, 9, 18, 91, 215, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(79, 9, 19, 93, 34, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(80, 10, 1, 97, 165, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(81, 10, 2, 94, 148, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(82, 10, 3, 96, 191, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(83, 10, 4, 96, 54, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(84, 10, 5, 97, 188, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(85, 10, 6, 88, 208, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(86, 10, 7, 87, 111, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(87, 10, 8, 85, 156, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(88, 10, 12, 88, 204, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(89, 10, 13, 96, 236, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(90, 10, 14, 94, 86, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(91, 10, 15, 32, 114, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(92, 10, 16, 89, 125, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(93, 10, 17, 86, 29, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(94, 10, 20, 88, 33, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(95, 10, 21, 41, 161, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(96, 11, 1, 94, 102, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(97, 11, 2, 92, 138, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(98, 11, 3, 53, 143, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(99, 11, 4, 43, 161, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(100, 11, 12, 93, 25, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(101, 11, 13, 95, 236, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(102, 11, 20, 34, 239, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(103, 11, 21, 95, 235, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(104, 12, 1, 92, 150, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(105, 12, 2, 100, 121, '2026-04-17 06:39:11', '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(106, 12, 3, 91, 178, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(107, 12, 4, 88, 68, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(108, 12, 9, 91, 194, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(109, 12, 10, 44, 54, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(110, 12, 11, 98, 162, NULL, '2026-04-23 06:39:11', '2026-04-16 06:39:11'),
(111, 12, 16, 95, 221, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(112, 12, 17, 87, 209, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(113, 12, 18, 97, 202, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(114, 12, 19, 85, 114, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(115, 12, 20, 87, 172, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(116, 12, 21, 97, 237, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(117, 13, 9, 89, 116, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(118, 13, 10, 84, 28, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(119, 13, 11, 88, 132, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(120, 13, 12, 57, 200, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(121, 13, 13, 100, 156, '2026-04-22 06:39:11', '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(122, 13, 18, 97, 174, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(123, 13, 19, 88, 217, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(124, 14, 1, 86, 35, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(125, 14, 2, 70, 62, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(126, 14, 3, 56, 238, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(127, 14, 4, 87, 61, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(128, 14, 5, 54, 185, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(129, 14, 6, 91, 127, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(130, 14, 7, 30, 119, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(131, 14, 8, 88, 125, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(132, 14, 12, 89, 186, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(133, 14, 13, 95, 64, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(134, 14, 14, 95, 39, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(135, 14, 15, 85, 141, NULL, '2026-04-23 06:39:11', '2026-04-16 06:39:11'),
(136, 14, 18, 94, 238, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(137, 14, 19, 95, 179, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(138, 14, 20, 85, 185, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(139, 14, 21, 86, 37, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(140, 15, 9, 96, 125, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(141, 15, 10, 77, 163, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(142, 15, 11, 84, 37, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(143, 15, 12, 92, 85, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(144, 15, 13, 86, 62, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(145, 15, 18, 97, 95, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(146, 15, 19, 85, 74, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(147, 16, 5, 90, 50, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(148, 16, 6, 93, 176, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(149, 16, 7, 95, 59, NULL, '2026-04-23 06:39:11', '2026-04-16 06:39:11'),
(150, 16, 8, 62, 206, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(151, 16, 14, 93, 161, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(152, 16, 15, 93, 238, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(153, 16, 16, 89, 240, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(154, 16, 17, 86, 91, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(155, 16, 18, 100, 203, '2026-04-19 06:39:11', '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(156, 16, 19, 78, 34, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(157, 17, 1, 87, 169, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(158, 17, 2, 93, 221, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(159, 17, 3, 91, 104, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(160, 17, 4, 96, 203, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(161, 17, 5, 86, 44, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(162, 17, 6, 99, 51, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(163, 17, 7, 87, 71, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(164, 17, 8, 94, 199, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(165, 17, 20, 91, 180, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(166, 17, 21, 92, 132, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(167, 18, 1, 94, 85, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(168, 18, 2, 96, 39, NULL, '2026-04-23 06:39:11', '2026-04-16 06:39:11'),
(169, 18, 3, 96, 55, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(170, 18, 4, 100, 166, '2026-04-17 06:39:11', '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(171, 18, 5, 87, 30, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(172, 18, 6, 96, 84, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(173, 18, 7, 100, 101, '2026-04-13 06:39:11', '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(174, 18, 8, 92, 220, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(175, 18, 9, 89, 228, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(176, 18, 10, 100, 131, '2026-04-16 06:39:11', '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(177, 18, 11, 85, 121, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(178, 18, 16, 87, 131, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(179, 18, 17, 88, 98, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(180, 18, 20, 88, 233, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(181, 18, 21, 88, 75, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(182, 19, 5, 91, 103, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(183, 19, 6, 86, 72, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(184, 19, 7, 88, 73, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(185, 19, 8, 100, 34, '2026-04-12 06:39:11', '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(186, 19, 14, 86, 90, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(187, 19, 15, 98, 110, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(188, 19, 16, 69, 24, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(189, 19, 17, 100, 88, '2026-04-14 06:39:11', '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(190, 19, 18, 94, 88, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(191, 19, 19, 85, 111, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(192, 19, 20, 87, 26, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(193, 19, 21, 87, 104, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(194, 20, 1, 90, 136, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(195, 20, 2, 90, 219, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(196, 20, 3, 75, 33, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(197, 20, 4, 97, 32, NULL, '2026-04-23 06:39:11', '2026-04-16 06:39:11'),
(198, 20, 5, 50, 38, NULL, '2026-04-23 06:39:11', '2026-04-16 06:39:11'),
(199, 20, 6, 75, 31, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(200, 20, 7, 98, 219, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11'),
(201, 20, 8, 99, 83, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(202, 20, 14, 99, 212, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(203, 20, 15, 89, 189, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(204, 20, 16, 89, 26, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(205, 20, 17, 74, 114, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(206, 20, 18, 61, 59, NULL, '2026-04-23 06:39:11', '2026-04-19 06:39:11'),
(207, 20, 19, 88, 149, NULL, '2026-04-23 06:39:11', '2026-04-21 06:39:11'),
(208, 21, 1, 93, 21, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(209, 21, 2, 93, 29, NULL, '2026-04-23 06:39:11', '2026-04-14 06:39:11'),
(210, 21, 3, 96, 135, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(211, 21, 4, 73, 147, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(212, 21, 5, 94, 57, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(213, 21, 6, 86, 237, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(214, 21, 7, 88, 195, NULL, '2026-04-23 06:39:11', '2026-04-23 06:39:11'),
(215, 21, 8, 87, 42, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(216, 21, 12, 99, 167, NULL, '2026-04-23 06:39:11', '2026-04-17 06:39:11'),
(217, 21, 13, 100, 161, '2026-04-21 06:39:11', '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(218, 21, 14, 95, 162, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(219, 21, 15, 98, 100, NULL, '2026-04-23 06:39:11', '2026-04-15 06:39:11'),
(220, 21, 18, 92, 73, NULL, '2026-04-23 06:39:11', '2026-04-13 06:39:11'),
(221, 21, 19, 94, 64, NULL, '2026-04-23 06:39:11', '2026-04-20 06:39:11'),
(222, 21, 20, 33, 179, NULL, '2026-04-23 06:39:11', '2026-04-18 06:39:11'),
(223, 21, 21, 65, 68, NULL, '2026-04-23 06:39:11', '2026-04-22 06:39:11');

-- --------------------------------------------------------

--
-- Table structure for table `material_ratings`
--

CREATE TABLE `material_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_1000000_create_learning_materials_table', 1),
(3, '2014_10_12_100000_create_students_table', 1),
(4, '2014_10_12_1100000_create_learning_progress_table', 1),
(5, '2014_10_12_1200000_create_material_ratings_table', 1),
(6, '2014_10_12_1300000_create_study_reminders_table', 1),
(7, '2014_10_12_1400000_create_rubrics_table', 1),
(8, '2014_10_12_1500000_create_messages_table', 1),
(9, '2014_10_12_1600000_create_credential_requests_table', 1),
(10, '2014_10_12_1700000_create_activity_log_table', 1),
(11, '2014_10_12_1800000_create_self_assessments_table', 1),
(12, '2014_10_12_200000_create_instructors_table', 1),
(13, '2014_10_12_300000_create_courses_table', 1),
(14, '2014_10_12_400000_create_enrollments_table', 1),
(15, '2014_10_12_500000_create_grades_table', 1),
(16, '2014_10_12_600000_create_admission_records_table', 1),
(17, '2014_10_12_700000_create_disciplinary_records_table', 1),
(18, '2014_10_12_800000_create_academic_honors_table', 1),
(19, '2014_10_12_900000_create_fees_table', 1),
(20, '2019_08_19_000000_create_failed_jobs_table', 1),
(21, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(22, '2024_01_01_000000_create_password_reset_tokens_table', 1),
(23, '2024_01_01_000001_create_sessions_table', 1),
(24, '2025_02_16_000001_add_approval_to_learning_materials', 1),
(25, '2025_02_16_000002_add_pending_and_attachments_to_enrollments', 1),
(26, '2025_02_16_000003_add_letter_path_to_credential_requests', 1),
(27, '2025_02_16_000004_add_payment_cleared_to_credential_requests', 1),
(28, '2025_02_16_000005_create_settings_table', 1),
(29, '2025_02_16_000006_add_cashier_to_users_role_enum', 1),
(30, '2025_02_16_000007_add_payment_fields_to_fees', 1),
(31, '2025_02_16_100000_add_academic_status_to_students_table', 1),
(32, '2025_02_16_100001_add_leave_to_admission_record_type', 1),
(33, '2025_02_16_100002_create_user_dashboard_preferences_table', 1),
(34, '2025_02_16_200000_create_learning_path_rules_table', 1),
(35, '2025_02_21_000000_create_pre_registrations_table', 1),
(36, '2025_02_21_000001_add_school_id_to_students_table', 1),
(37, '2025_02_21_000002_expand_pre_registrations_table', 1),
(38, '2025_02_21_100000_add_grade_level_to_courses_table', 1),
(39, '2026_02_23_000001_change_units_to_integer_in_courses_table', 1),
(40, '2026_02_23_000002_create_transfer_requests_table', 1),
(41, '2026_03_04_000000_add_transferee_grade_to_pre_registrations', 1),
(42, '2026_03_04_000001_add_test_g7_courses', 1),
(43, '2026_03_19_000001_create_learning_aids_table', 1),
(44, '2026_03_19_000002_create_curriculum_standards_table', 1),
(45, '2026_03_19_000003_create_learning_outcomes_table', 1),
(46, '2026_03_19_000004_create_course_curriculum_alignments_table', 1),
(47, '2026_03_19_000005_create_student_progress_analytics_table', 1),
(48, '2026_03_19_000006_create_assessment_templates_table', 1),
(49, '2026_03_19_000007_create_job_aids_table', 1),
(50, '2026_03_19_000008_create_learning_aid_interactions_table', 1),
(51, '2026_03_22_000001_create_auto_generated_learning_aids_table', 1),
(52, '2026_04_11_000001_add_rubric_id_to_grades_table', 1),
(53, '2026_04_11_000001_add_theme_and_display_columns_to_user_dashboard_preferences_table', 1),
(54, '2026_04_13_000001_add_semester_to_courses_table', 1),
(55, '2026_04_13_000002_update_applicant_category_enum', 1),
(56, '2026_04_16_000001_add_completion_status_to_learning_materials', 1),
(57, '2026_04_16_000002_add_completion_status_to_courses', 1),
(58, '2026_04_23_160024_create_notifications_table', 2),
(59, '2026_04_24_000001_add_release_date_to_learning_materials', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pre_registrations`
--

CREATE TABLE `pre_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `year_level` int(11) NOT NULL DEFAULT 1,
  `applicant_category` enum('grade7','grade8','grade9','grade10','grade11','grade12','transferee','returnee') DEFAULT NULL,
  `transferee_grade` varchar(255) DEFAULT NULL,
  `preferred_program` enum('ABM','GAS','STEM','TVL-Automotive','TVL-ICT','TVL-Cookery','TVL-HomeEc','TVL-IndustrialArts','TVL-AgriFishery') DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `has_no_middle_name` tinyint(1) NOT NULL DEFAULT 0,
  `extension_name` varchar(255) DEFAULT NULL,
  `sex` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `civil_status` enum('Single','Married','Divorced','Widowed') DEFAULT NULL,
  `telephone_number` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `citizenship` enum('Filipino','Dual','Other') DEFAULT NULL,
  `citizenship_other` varchar(255) DEFAULT NULL,
  `family_members_indicator` text DEFAULT NULL,
  `family_information` longtext DEFAULT NULL,
  `elementary_graduation_year` varchar(255) DEFAULT NULL,
  `junior_high_graduation_year` varchar(255) DEFAULT NULL,
  `high_school_graduation_year` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_address` text DEFAULT NULL,
  `emergency_contact_number` varchar(255) DEFAULT NULL,
  `emergency_contact_relationship` varchar(255) DEFAULT NULL,
  `emergency_contact_email` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_registrations`
--

INSERT INTO `pre_registrations` (`id`, `email`, `password_hash`, `full_name`, `program`, `year_level`, `applicant_category`, `transferee_grade`, `preferred_program`, `last_name`, `first_name`, `middle_name`, `has_no_middle_name`, `extension_name`, `sex`, `date_of_birth`, `place_of_birth`, `civil_status`, `telephone_number`, `mobile_number`, `permanent_address`, `current_address`, `citizenship`, `citizenship_other`, `family_members_indicator`, `family_information`, `elementary_graduation_year`, `junior_high_graduation_year`, `high_school_graduation_year`, `emergency_contact_name`, `emergency_contact_address`, `emergency_contact_number`, `emergency_contact_relationship`, `emergency_contact_email`, `status`, `rejection_reason`, `approved_at`, `rejected_at`, `created_at`, `updated_at`) VALUES
(1, 'sherynmelpanes@csp.edu', '$2y$12$9Nj/ybK4qaSQSlGKdzmxVeEZF0vZ26Cg5pzC/0uCVdDy2iG6V.p9y', 'Sheryn Mel M. Panes', 'N/A', 7, 'grade7', NULL, NULL, 'Panes', 'Sheryn Mel', 'M.', 0, NULL, 'Female', '2003-01-01', 'Tupi, South Cotabato', 'Single', 'N/A', '09325124251', 'Tupi, South Cotabato', 'Tupi, South Cotabato', 'Filipino', NULL, 'father', '{\"father\":{\"last_name\":\"Panes\",\"first_name\":\"Antonio\",\"middle_name\":\"L.\",\"telephone\":\"N\\/A\",\"mobile\":\"09265144521\",\"occupation\":\"OFW\",\"deceased\":false}}', '2015-2016', NULL, NULL, 'Antonio Panes', 'Tupi South Cotabato', '09265144521', 'Parent', 'antoniopanes@gmail.com', 'approved', NULL, '2026-04-23 15:38:30', NULL, '2026-04-23 07:33:30', '2026-04-23 07:38:30');

-- --------------------------------------------------------

--
-- Table structure for table `rubrics`
--

CREATE TABLE `rubrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `criteria_json` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `self_assessments`
--

CREATE TABLE `self_assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `responses_json` text DEFAULT NULL,
  `score` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `student_number` varchar(50) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL,
  `year_level` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `admission_date` date DEFAULT NULL,
  `status` enum('active','inactive','transferred','graduated') NOT NULL DEFAULT 'active',
  `academic_status` varchar(32) DEFAULT NULL COMMENT 'passed, failed, at-risk, inc, drop',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `school_id`, `user_id`, `student_number`, `program`, `year_level`, `admission_date`, `status`, `academic_status`, `created_at`, `updated_at`) VALUES
(1, NULL, 5, '2024-001', 'BS Computer Science', 1, '2026-04-23', 'active', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(2, NULL, 6, '2024-0001', 'BS Computer Science', 2, '2025-08-23', 'active', NULL, '2026-04-23 06:39:03', '2026-04-23 06:39:03'),
(3, NULL, 7, '2024-0002', 'BS Computer Science', 3, '2024-07-23', 'active', NULL, '2026-04-23 06:39:03', '2026-04-23 06:39:03'),
(4, NULL, 8, '2024-0003', 'BS Computer Science', 4, '2025-09-23', 'active', NULL, '2026-04-23 06:39:04', '2026-04-23 06:39:04'),
(5, NULL, 9, '2024-0004', 'BS Computer Science', 3, '2025-10-23', 'active', NULL, '2026-04-23 06:39:04', '2026-04-23 06:39:04'),
(6, NULL, 10, '2024-0005', 'BS Computer Science', 4, '2025-09-23', 'active', NULL, '2026-04-23 06:39:04', '2026-04-23 06:39:04'),
(7, NULL, 11, '2024-0006', 'BS Computer Science', 1, '2024-09-23', 'active', NULL, '2026-04-23 06:39:05', '2026-04-23 06:39:05'),
(8, NULL, 12, '2024-0007', 'BS Computer Science', 3, '2025-06-23', 'active', NULL, '2026-04-23 06:39:05', '2026-04-23 06:39:05'),
(9, NULL, 13, '2024-0008', 'BS Computer Science', 3, '2024-10-23', 'active', NULL, '2026-04-23 06:39:05', '2026-04-23 06:39:05'),
(10, NULL, 14, '2024-0009', 'BS Computer Science', 1, '2024-09-23', 'active', NULL, '2026-04-23 06:39:06', '2026-04-23 06:39:06'),
(11, NULL, 15, '2024-0010', 'BS Computer Science', 2, '2024-12-23', 'active', NULL, '2026-04-23 06:39:06', '2026-04-23 06:39:06'),
(12, NULL, 16, '2024-0011', 'BS Computer Science', 2, '2025-09-23', 'active', NULL, '2026-04-23 06:39:06', '2026-04-23 06:39:06'),
(13, NULL, 17, '2024-0012', 'BS Computer Science', 2, '2025-01-23', 'active', NULL, '2026-04-23 06:39:07', '2026-04-23 06:39:07'),
(14, NULL, 18, '2024-0013', 'BS Computer Science', 4, '2025-05-23', 'active', NULL, '2026-04-23 06:39:07', '2026-04-23 06:39:07'),
(15, NULL, 19, '2024-0014', 'BS Computer Science', 2, '2026-01-23', 'active', NULL, '2026-04-23 06:39:07', '2026-04-23 06:39:07'),
(16, NULL, 20, '2024-0015', 'BS Computer Science', 3, '2025-10-23', 'active', NULL, '2026-04-23 06:39:08', '2026-04-23 06:39:08'),
(17, NULL, 21, '2024-0016', 'BS Computer Science', 3, '2026-01-23', 'active', NULL, '2026-04-23 06:39:08', '2026-04-23 06:39:08'),
(18, NULL, 22, '2024-0017', 'BS Computer Science', 2, '2026-01-23', 'active', NULL, '2026-04-23 06:39:09', '2026-04-23 06:39:09'),
(19, NULL, 23, '2024-0018', 'BS Computer Science', 2, '2024-05-23', 'active', NULL, '2026-04-23 06:39:09', '2026-04-23 06:39:09'),
(20, NULL, 24, '2024-0019', 'BS Computer Science', 2, '2024-07-23', 'active', NULL, '2026-04-23 06:39:09', '2026-04-23 06:39:09'),
(21, NULL, 25, '2024-0020', 'BS Computer Science', 1, '2024-10-23', 'active', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(22, '2027-00001', 26, '2027-00001', NULL, 7, '2026-04-23', 'active', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30');

-- --------------------------------------------------------

--
-- Table structure for table `student_progress_analytics`
--

CREATE TABLE `student_progress_analytics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `completion_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `materials_completed` int(11) NOT NULL DEFAULT 0,
  `materials_total` int(11) NOT NULL DEFAULT 0,
  `total_time_minutes` int(11) NOT NULL DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT NULL,
  `current_grade` decimal(5,2) DEFAULT NULL,
  `quiz_average` decimal(5,2) DEFAULT NULL,
  `assessment_average` decimal(5,2) DEFAULT NULL,
  `at_risk_status` enum('on_track','at_risk','critical') NOT NULL DEFAULT 'on_track',
  `weak_topics` text DEFAULT NULL,
  `strong_topics` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `last_analyzed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_reminders`
--

CREATE TABLE `study_reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `remind_at` datetime NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `study_reminders`
--

INSERT INTO `study_reminders` (`id`, `student_id`, `material_id`, `title`, `remind_at`, `sent`, `created_at`, `updated_at`) VALUES
(1, 22, 16, 'aa', '2026-04-24 00:11:00', 0, '2026-04-23 08:10:09', '2026-04-23 08:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_requests`
--

CREATE TABLE `transfer_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `from_school` varchar(255) DEFAULT NULL,
  `to_school` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','instructor','admin','cashier') NOT NULL DEFAULT 'student',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'G7 Test Instructor', 'g7-instructor@example.test', NULL, '$2y$12$6z.YZWyWcwybdkvtErCekeZOS1lKRidVXrGfaiPXcRheb0NMV8oE.', 'instructor', NULL, '2026-04-23 06:38:58', '2026-04-23 06:38:58'),
(2, 'Portal Admin', 'admin@csp.edu', NULL, '$2y$12$/CaUlrJcTLibV.JMvZ3Nke0Hsahn448z/tSzE3cXTZrQg674FNJRq', 'admin', NULL, '2026-04-23 06:39:01', '2026-04-23 06:39:01'),
(3, 'Portal Cashier', 'cashier@csp.edu', NULL, '$2y$12$.trMYu39YXF7TfN7HlLlKuFCreJnZsrbID2C2DsJK3gJAbkoNMQ.6', 'cashier', NULL, '2026-04-23 06:39:01', '2026-04-23 06:39:01'),
(4, 'Chester John Flores', 'cheezyflrs@csp.edu', NULL, '$2y$12$WE9SFm1K7ZS1Hbir1N7qauGV55r5bCY336eYzltwUy0jdGFGePtOO', 'instructor', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(5, 'John Student', 'student@csp.edu', NULL, '$2y$12$OAmnOGVmM4R9G0eQ3I3mHeeq4oQEdsIXpMkYhSFUB2Up1EahSjCHa', 'student', NULL, '2026-04-23 06:39:02', '2026-04-23 06:39:02'),
(6, 'Maria Garcia', 'student1@csp.edu', NULL, '$2y$12$VvLklXS7faBThcjvZqRZnO.LhYh1s0Xq7xcqRStpjxCW3C3FuPmbm', 'student', NULL, '2026-04-23 06:39:03', '2026-04-23 06:39:03'),
(7, 'Juan Delgado', 'student2@csp.edu', NULL, '$2y$12$FF8s05EwrJqCXAf29yxHmeRqIXneC9AlZcShzLSC36rn2Mck8fwXG', 'student', NULL, '2026-04-23 06:39:03', '2026-04-23 06:39:03'),
(8, 'Ana Reyes', 'student3@csp.edu', NULL, '$2y$12$/aeILdmuG.poWzRowMqY/OPn70o67B7d0YanPvGpxfyktrJZsatYO', 'student', NULL, '2026-04-23 06:39:04', '2026-04-23 06:39:04'),
(9, 'Carlos Santos', 'student4@csp.edu', NULL, '$2y$12$yxDdkpFE82BIE93smftMQee4q773.Uw.G8pPMJ2GtCmldDjT7fFr2', 'student', NULL, '2026-04-23 06:39:04', '2026-04-23 06:39:04'),
(10, 'Rosa Mendoza', 'student5@csp.edu', NULL, '$2y$12$bpwYmJz0SGDtmJGG9uSt9eNQiLnpRIV0FYH5GI0wCU/HrsIclH1oa', 'student', NULL, '2026-04-23 06:39:04', '2026-04-23 06:39:04'),
(11, 'Luis Rodriguez', 'student6@csp.edu', NULL, '$2y$12$ikCP8iByFT2itWSNd9znn.52Tk8wgpAXjGj0VSTesrdgSi5jiBqF6', 'student', NULL, '2026-04-23 06:39:05', '2026-04-23 06:39:05'),
(12, 'Elena Torres', 'student7@csp.edu', NULL, '$2y$12$/3vVw69J8RzLJHeVS/XJWurgh6.ggBFTXyY0qapCWc6MrlpWp5n/m', 'student', NULL, '2026-04-23 06:39:05', '2026-04-23 06:39:05'),
(13, 'Miguel Herrera', 'student8@csp.edu', NULL, '$2y$12$dBs8IkW44UVfqbN6RJbSh.VsV0JSZ9awObiNyrkF0C3QzL9mrIaI6', 'student', NULL, '2026-04-23 06:39:05', '2026-04-23 06:39:05'),
(14, 'Sofia Ramirez', 'student9@csp.edu', NULL, '$2y$12$YqCWlPV1fHgdG0mwwuFJZ.FCIRMwHBZSBvyvYkszwYkgx5f02ffu2', 'student', NULL, '2026-04-23 06:39:06', '2026-04-23 06:39:06'),
(15, 'Diego Morales', 'student10@csp.edu', NULL, '$2y$12$IAN0/MOn6dDW3E8mLKMy0O.Cbg7DGIqowmLIQ62f9XoAbaGwqC1m2', 'student', NULL, '2026-04-23 06:39:06', '2026-04-23 06:39:06'),
(16, 'Isabella Cruz', 'student11@csp.edu', NULL, '$2y$12$d.LMTB4fY9HQ98gPGiHHvugModucRUPdV1.84J6euCHTFegHiwFEa', 'student', NULL, '2026-04-23 06:39:06', '2026-04-23 06:39:06'),
(17, 'Antonio Ortiz', 'student12@csp.edu', NULL, '$2y$12$5pkXhs2xM88ojgWvCTfddesGdKbRrHxYT9l390K0oE.lQiFlM9T7G', 'student', NULL, '2026-04-23 06:39:07', '2026-04-23 06:39:07'),
(18, 'Gabriela Ruiz', 'student13@csp.edu', NULL, '$2y$12$7.J6nA650T2RFWvfkO.8tOwXU8q4cZEOrfWDs1RjQFW52BcLZ/hBy', 'student', NULL, '2026-04-23 06:39:07', '2026-04-23 06:39:07'),
(19, 'Fernando Gutierrez', 'student14@csp.edu', NULL, '$2y$12$vd9kLKilaNozmFNmcFR5ueChPFiOr5Z6CErWlqvCN6JvjsW5w3n6S', 'student', NULL, '2026-04-23 06:39:07', '2026-04-23 06:39:07'),
(20, 'Carmen Flores', 'student15@csp.edu', NULL, '$2y$12$Q1XjTmVOCydRFrGLcclO.evX006ZIecW.z2el7kPIZXaBDQYJfleW', 'student', NULL, '2026-04-23 06:39:08', '2026-04-23 06:39:08'),
(21, 'Victor Moreno', 'student16@csp.edu', NULL, '$2y$12$bPP9Ej2fL./F7x7FXf62kObCe3.Y0IU7F6MhREXgb/l9Gz7KfC4Fa', 'student', NULL, '2026-04-23 06:39:08', '2026-04-23 06:39:08'),
(22, 'Valentina Vargas', 'student17@csp.edu', NULL, '$2y$12$fNlbpzm8H3cGNFnnD5vR2.r.Rqsv3RFQZEzDkmLx/tAfDrUZGYLhi', 'student', NULL, '2026-04-23 06:39:09', '2026-04-23 06:39:09'),
(23, 'Ricardo Perez', 'student18@csp.edu', NULL, '$2y$12$.g0pQ88an8laOhWrCDHLyuPFEYDxubg2gsaEr2w00zz.maKfGgWXK', 'student', NULL, '2026-04-23 06:39:09', '2026-04-23 06:39:09'),
(24, 'Alejandra Hernandez', 'student19@csp.edu', NULL, '$2y$12$AExAouP7l17et0bo/ztU7OmkH5gGvdVbLHlim8qJJnX20LpCUEa4G', 'student', NULL, '2026-04-23 06:39:09', '2026-04-23 06:39:09'),
(25, 'Andres Jimenez', 'student20@csp.edu', NULL, '$2y$12$0BpCt09utWxUDUKVB5HtPOUkj76C612ipwDDvskH5y7ULGNwAa1qS', 'student', NULL, '2026-04-23 06:39:10', '2026-04-23 06:39:10'),
(26, 'Sheryn Mel M. Panes', 'sherynmelpanes@csp.edu', '2026-04-23 07:38:30', '$2y$12$9Nj/ybK4qaSQSlGKdzmxVeEZF0vZ26Cg5pzC/0uCVdDy2iG6V.p9y', 'student', NULL, '2026-04-23 07:38:30', '2026-04-23 07:38:30');

-- --------------------------------------------------------

--
-- Table structure for table `user_dashboard_preferences`
--

CREATE TABLE `user_dashboard_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `layout` varchar(50) NOT NULL DEFAULT 'default' COMMENT 'default, compact, focus',
  `widgets` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'enabled widget keys or order' CHECK (json_valid(`widgets`)),
  `learning_focus` varchar(255) DEFAULT NULL COMMENT 'e.g. course code or goal',
  `theme_color` varchar(7) NOT NULL DEFAULT '#0d6efd',
  `show_quick_links` tinyint(1) NOT NULL DEFAULT 1,
  `show_my_info` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_honors`
--
ALTER TABLE `academic_honors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_honors_student_id_foreign` (`student_id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_user_id_foreign` (`user_id`);

--
-- Indexes for table `admission_records`
--
ALTER TABLE `admission_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admission_records_student_id_foreign` (`student_id`);

--
-- Indexes for table `assessment_templates`
--
ALTER TABLE `assessment_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_templates_course_id_foreign` (`course_id`),
  ADD KEY `assessment_templates_learning_material_id_foreign` (`learning_material_id`),
  ADD KEY `assessment_templates_instructor_id_foreign` (`instructor_id`);

--
-- Indexes for table `auto_learning_aids`
--
ALTER TABLE `auto_learning_aids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auto_learning_aids_material_id_foreign` (`material_id`),
  ADD KEY `auto_learning_aids_instructor_id_foreign` (`instructor_id`),
  ADD KEY `auto_learning_aids_course_id_foreign` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_code_unique` (`code`);

--
-- Indexes for table `course_curriculum_alignments`
--
ALTER TABLE `course_curriculum_alignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cca_course_material_unique` (`course_id`,`learning_material_id`),
  ADD KEY `course_curriculum_alignments_learning_material_id_foreign` (`learning_material_id`),
  ADD KEY `course_curriculum_alignments_learning_outcome_id_foreign` (`learning_outcome_id`),
  ADD KEY `course_curriculum_alignments_curriculum_standard_id_foreign` (`curriculum_standard_id`);

--
-- Indexes for table `credential_requests`
--
ALTER TABLE `credential_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credential_requests_student_id_foreign` (`student_id`);

--
-- Indexes for table `curriculum_standards`
--
ALTER TABLE `curriculum_standards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curriculum_standards_code_unique` (`code`);

--
-- Indexes for table `disciplinary_records`
--
ALTER TABLE `disciplinary_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disciplinary_records_student_id_foreign` (`student_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enrollments_student_id_course_id_semester_school_year_unique` (`student_id`,`course_id`,`semester`,`school_year`),
  ADD KEY `enrollments_course_id_foreign` (`course_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fees_student_id_foreign` (`student_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grades_enrollment_id_foreign` (`enrollment_id`),
  ADD KEY `grades_rubric_id_foreign` (`rubric_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructors_user_id_foreign` (`user_id`);

--
-- Indexes for table `job_aids`
--
ALTER TABLE `job_aids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_aids_student_id_foreign` (`student_id`),
  ADD KEY `job_aids_course_id_foreign` (`course_id`);

--
-- Indexes for table `learning_aids`
--
ALTER TABLE `learning_aids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `learning_aids_material_id_foreign` (`material_id`),
  ADD KEY `learning_aids_course_id_foreign` (`course_id`);

--
-- Indexes for table `learning_aid_interactions`
--
ALTER TABLE `learning_aid_interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `learning_aid_interactions_student_id_foreign` (`student_id`),
  ADD KEY `learning_aid_interactions_learning_aid_id_foreign` (`learning_aid_id`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learning_outcomes`
--
ALTER TABLE `learning_outcomes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `learning_outcomes_code_unique` (`code`),
  ADD KEY `learning_outcomes_course_id_foreign` (`course_id`);

--
-- Indexes for table `learning_path_rules`
--
ALTER TABLE `learning_path_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `learning_path_rules_source_course_id_foreign` (`source_course_id`),
  ADD KEY `learning_path_rules_target_course_id_foreign` (`target_course_id`),
  ADD KEY `learning_path_rules_source_material_id_foreign` (`source_material_id`),
  ADD KEY `learning_path_rules_target_material_id_foreign` (`target_material_id`);

--
-- Indexes for table `learning_progress`
--
ALTER TABLE `learning_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `learning_progress_student_id_material_id_unique` (`student_id`,`material_id`),
  ADD KEY `learning_progress_material_id_foreign` (`material_id`);

--
-- Indexes for table `material_ratings`
--
ALTER TABLE `material_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_ratings_student_id_material_id_unique` (`student_id`,`material_id`),
  ADD KEY `material_ratings_material_id_foreign` (`material_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pre_registrations`
--
ALTER TABLE `pre_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pre_registrations_email_unique` (`email`);

--
-- Indexes for table `rubrics`
--
ALTER TABLE `rubrics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `self_assessments`
--
ALTER TABLE `self_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `self_assessments_student_id_foreign` (`student_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_student_number_unique` (`student_number`),
  ADD UNIQUE KEY `students_school_id_unique` (`school_id`),
  ADD KEY `students_user_id_foreign` (`user_id`);

--
-- Indexes for table `student_progress_analytics`
--
ALTER TABLE `student_progress_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_progress_analytics_student_id_course_id_unique` (`student_id`,`course_id`),
  ADD KEY `student_progress_analytics_course_id_foreign` (`course_id`);

--
-- Indexes for table `study_reminders`
--
ALTER TABLE `study_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `study_reminders_student_id_foreign` (`student_id`);

--
-- Indexes for table `transfer_requests`
--
ALTER TABLE `transfer_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_requests_student_id_index` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_dashboard_preferences`
--
ALTER TABLE `user_dashboard_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_dashboard_preferences_user_id_unique` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_honors`
--
ALTER TABLE `academic_honors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admission_records`
--
ALTER TABLE `admission_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_templates`
--
ALTER TABLE `assessment_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_learning_aids`
--
ALTER TABLE `auto_learning_aids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `course_curriculum_alignments`
--
ALTER TABLE `course_curriculum_alignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credential_requests`
--
ALTER TABLE `credential_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `curriculum_standards`
--
ALTER TABLE `curriculum_standards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disciplinary_records`
--
ALTER TABLE `disciplinary_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_aids`
--
ALTER TABLE `job_aids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_aids`
--
ALTER TABLE `learning_aids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_aid_interactions`
--
ALTER TABLE `learning_aid_interactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `learning_outcomes`
--
ALTER TABLE `learning_outcomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_path_rules`
--
ALTER TABLE `learning_path_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_progress`
--
ALTER TABLE `learning_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT for table `material_ratings`
--
ALTER TABLE `material_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_registrations`
--
ALTER TABLE `pre_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rubrics`
--
ALTER TABLE `rubrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `self_assessments`
--
ALTER TABLE `self_assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `student_progress_analytics`
--
ALTER TABLE `student_progress_analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_reminders`
--
ALTER TABLE `study_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transfer_requests`
--
ALTER TABLE `transfer_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_dashboard_preferences`
--
ALTER TABLE `user_dashboard_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_honors`
--
ALTER TABLE `academic_honors`
  ADD CONSTRAINT `academic_honors_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admission_records`
--
ALTER TABLE `admission_records`
  ADD CONSTRAINT `admission_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessment_templates`
--
ALTER TABLE `assessment_templates`
  ADD CONSTRAINT `assessment_templates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessment_templates_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assessment_templates_learning_material_id_foreign` FOREIGN KEY (`learning_material_id`) REFERENCES `learning_materials` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `auto_learning_aids`
--
ALTER TABLE `auto_learning_aids`
  ADD CONSTRAINT `auto_learning_aids_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auto_learning_aids_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `auto_learning_aids_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_curriculum_alignments`
--
ALTER TABLE `course_curriculum_alignments`
  ADD CONSTRAINT `course_curriculum_alignments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_curriculum_alignments_curriculum_standard_id_foreign` FOREIGN KEY (`curriculum_standard_id`) REFERENCES `curriculum_standards` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `course_curriculum_alignments_learning_material_id_foreign` FOREIGN KEY (`learning_material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_curriculum_alignments_learning_outcome_id_foreign` FOREIGN KEY (`learning_outcome_id`) REFERENCES `learning_outcomes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `credential_requests`
--
ALTER TABLE `credential_requests`
  ADD CONSTRAINT `credential_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `disciplinary_records`
--
ALTER TABLE `disciplinary_records`
  ADD CONSTRAINT `disciplinary_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_rubric_id_foreign` FOREIGN KEY (`rubric_id`) REFERENCES `rubrics` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `instructors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_aids`
--
ALTER TABLE `job_aids`
  ADD CONSTRAINT `job_aids_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_aids_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_aids`
--
ALTER TABLE `learning_aids`
  ADD CONSTRAINT `learning_aids_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_aids_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_aid_interactions`
--
ALTER TABLE `learning_aid_interactions`
  ADD CONSTRAINT `learning_aid_interactions_learning_aid_id_foreign` FOREIGN KEY (`learning_aid_id`) REFERENCES `learning_aids` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_aid_interactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_outcomes`
--
ALTER TABLE `learning_outcomes`
  ADD CONSTRAINT `learning_outcomes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_path_rules`
--
ALTER TABLE `learning_path_rules`
  ADD CONSTRAINT `learning_path_rules_source_course_id_foreign` FOREIGN KEY (`source_course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `learning_path_rules_source_material_id_foreign` FOREIGN KEY (`source_material_id`) REFERENCES `learning_materials` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `learning_path_rules_target_course_id_foreign` FOREIGN KEY (`target_course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `learning_path_rules_target_material_id_foreign` FOREIGN KEY (`target_material_id`) REFERENCES `learning_materials` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `learning_progress`
--
ALTER TABLE `learning_progress`
  ADD CONSTRAINT `learning_progress_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_progress_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_ratings`
--
ALTER TABLE `material_ratings`
  ADD CONSTRAINT `material_ratings_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_ratings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `self_assessments`
--
ALTER TABLE `self_assessments`
  ADD CONSTRAINT `self_assessments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_progress_analytics`
--
ALTER TABLE `student_progress_analytics`
  ADD CONSTRAINT `student_progress_analytics_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_analytics_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `study_reminders`
--
ALTER TABLE `study_reminders`
  ADD CONSTRAINT `study_reminders_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_dashboard_preferences`
--
ALTER TABLE `user_dashboard_preferences`
  ADD CONSTRAINT `user_dashboard_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
