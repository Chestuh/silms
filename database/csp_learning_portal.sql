-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 07:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `units` decimal(4,2) NOT NULL DEFAULT 3.00,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `code`, `title`, `units`, `instructor_id`, `created_at`, `updated_at`) VALUES
(1, 'CS101', 'Introduction to Programming', 3.00, 1, '2026-02-15 20:57:29', '2026-02-15 20:57:29'),
(2, 'CS102', 'Data Structures', 3.00, 1, '2026-02-15 20:57:29', '2026-02-15 20:57:29');

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
(1, 1, 1, '1st', '2024-2025', 'enrolled', NULL, '2026-02-15 20:57:29', '2026-02-15 20:57:29'),
(2, 1, 2, '1st', '2024-2025', 'enrolled', NULL, '2026-02-15 20:57:29', '2026-02-15 20:57:29');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `enrollment_id`, `midterm_grade`, `final_grade`, `gwa_contribution`, `created_at`, `updated_at`) VALUES
(1, 1, 88.00, 90.00, NULL, '2026-02-15 20:57:29', '2026-02-15 20:57:29'),
(2, 2, 85.00, 87.00, NULL, '2026-02-15 20:57:29', '2026-02-15 20:57:29');

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
(1, 3, 'Computer Science', '2026-02-15 20:57:29', '2026-02-15 20:57:29');

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
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `approval_status` varchar(20) NOT NULL DEFAULT 'pending',
  `admin_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `learning_materials`
--

INSERT INTO `learning_materials` (`id`, `course_id`, `title`, `description`, `format`, `file_path`, `url`, `difficulty_level`, `order_index`, `archived`, `approval_status`, `admin_comment`, `created_at`, `updated_at`) VALUES
(1, 1, 'Welcome and Syllabus', 'Course overview and requirements', 'document', NULL, NULL, 'easy', 1, 0, 'pending', NULL, '2026-02-15 20:57:29', '2026-02-15 20:57:29'),
(2, 1, 'Variables and Data Types', 'Video lesson on basics', 'video', NULL, NULL, 'easy', 2, 0, 'pending', NULL, '2026-02-15 20:57:29', '2026-02-15 20:57:29'),
(3, 2, 'Arrays and Lists', 'Introduction to linear structures', 'document', NULL, NULL, 'medium', 1, 0, 'pending', NULL, '2026-02-15 20:57:29', '2026-02-15 20:57:29');

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

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `subject`, `body`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 4, 3, 'Testing', 'Test', '2026-02-15 22:23:25', '2026-02-15 21:50:35', '2026-02-15 22:23:25'),
(2, 3, 4, 'Test', 'Testing', '2026-02-15 21:56:51', '2026-02-15 21:51:04', '2026-02-15 21:56:51'),
(3, 4, 3, NULL, 'Test\r\n', '2026-02-15 22:23:25', '2026-02-15 21:56:54', '2026-02-15 22:23:25'),
(4, 4, 3, NULL, '1', '2026-02-15 22:23:25', '2026-02-15 21:56:59', '2026-02-15 22:23:25'),
(5, 4, 3, NULL, 'test\r\n', '2026-02-15 22:23:25', '2026-02-15 22:21:21', '2026-02-15 22:23:25'),
(6, 4, 3, NULL, 'a', '2026-02-15 22:23:25', '2026-02-15 22:22:30', '2026-02-15 22:23:25'),
(7, 4, 3, NULL, 'test\r\ntest', '2026-02-15 22:23:25', '2026-02-15 22:22:36', '2026-02-15 22:23:25'),
(8, 4, 3, NULL, 'hello\r\n', '2026-02-15 22:23:25', '2026-02-15 22:23:08', '2026-02-15 22:23:25');

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
(31, '2025_02_16_100000_add_academic_status_to_students_table', 2),
(32, '2025_02_16_100001_add_leave_to_admission_record_type', 2),
(33, '2025_02_16_100002_create_user_dashboard_preferences_table', 2),
(34, '2025_02_16_200000_create_learning_path_rules_table', 3);

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
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `student_number` varchar(50) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL,
  `year_level` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `admission_date` date DEFAULT NULL,
  `status` enum('active','inactive','transferred','graduated') NOT NULL DEFAULT 'active',
  `academic_status` varchar(32) DEFAULT NULL COMMENT 'regular, irregular, probationary, at-risk',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_number`, `program`, `year_level`, `admission_date`, `status`, `academic_status`, `created_at`, `updated_at`) VALUES
(1, 4, '2024-001', 'BS Computer Science', 1, '2026-02-16', 'active', NULL, '2026-02-15 20:57:29', '2026-02-15 20:57:29');

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
(1, 'Administrator', 'admin@csp.edu', NULL, '$2y$12$QqvTA2ymEzJ7FfQKIvaW1e0g8e3TDdT0q0QC4m01fmrZoX0ES/Xk.', 'admin', NULL, '2026-02-15 20:57:28', '2026-02-15 21:15:30'),
(2, 'Portal Cashier', 'cashier@csp.edu', NULL, '$2y$12$zt7jaqU5PsXV3k5nVZ0sY.zQ5vs8iRdxXRGZPrEivitpvM0cTiCJ2', 'cashier', NULL, '2026-02-15 20:57:28', '2026-02-15 20:57:28'),
(3, 'Jane Doe', 'instructor@csp.edu', NULL, '$2y$12$NyREKlI62Zn26GS8WSWvSebMATKqX46WJXWUGUT2Hvs9s166jM1FO', 'instructor', NULL, '2026-02-15 20:57:29', '2026-02-15 21:11:44'),
(4, 'John Doe', 'student@csp.edu', NULL, '$2y$12$8MJF4X3RYv8.iy01Gka0Ju9ipX4Kbl9h78au0kwSxBFu1aQHFhh66', 'student', NULL, '2026-02-15 20:57:29', '2026-02-15 20:59:45');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_dashboard_preferences`
--

INSERT INTO `user_dashboard_preferences` (`id`, `user_id`, `layout`, `widgets`, `learning_focus`, `created_at`, `updated_at`) VALUES
(1, 4, 'focus', NULL, NULL, '2026-02-15 21:45:06', '2026-02-15 21:45:15');

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
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_code_unique` (`code`);

--
-- Indexes for table `credential_requests`
--
ALTER TABLE `credential_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credential_requests_student_id_foreign` (`student_id`);

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
  ADD KEY `grades_enrollment_id_foreign` (`enrollment_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructors_user_id_foreign` (`user_id`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `students_user_id_foreign` (`user_id`);

--
-- Indexes for table `study_reminders`
--
ALTER TABLE `study_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `study_reminders_student_id_foreign` (`student_id`);

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
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `credential_requests`
--
ALTER TABLE `credential_requests`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `learning_path_rules`
--
ALTER TABLE `learning_path_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_progress`
--
ALTER TABLE `learning_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_ratings`
--
ALTER TABLE `material_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `study_reminders`
--
ALTER TABLE `study_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_dashboard_preferences`
--
ALTER TABLE `user_dashboard_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `grades_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `instructors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
