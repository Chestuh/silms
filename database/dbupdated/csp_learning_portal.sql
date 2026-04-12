-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2026 at 08:42 AM
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
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `code`, `title`, `units`, `grade_level`, `instructor_id`, `created_at`, `updated_at`) VALUES
(8, 'G7-MATH-201', 'G7 Mathematics - Algebra', 3, '7', 6, '2026-03-18 23:25:42', '2026-03-22 23:13:35'),
(9, 'G7-SCI-201', 'G7 Science - General', 3, '7', 6, '2026-03-18 23:25:42', '2026-03-22 23:13:35'),
(10, 'G7-MATH-101', 'Grade 7 Mathematics', 3, '7', 6, '2026-03-22 23:06:52', '2026-03-22 23:13:35'),
(11, 'G7-ENG-101', 'Grade 7 English Language', 3, '7', 6, '2026-03-22 23:06:52', '2026-03-22 23:13:35'),
(12, 'G7-SCI-101', 'Grade 7 Science', 3, '7', 6, '2026-03-22 23:06:52', '2026-03-22 23:13:35'),
(13, 'G7-SST-101', 'Grade 7 Social Studies', 3, '7', 6, '2026-03-22 23:06:52', '2026-03-22 23:13:35'),
(14, 'G7-FIL-101', 'Filipino', 2, '7', 6, '2026-03-22 23:06:52', '2026-03-22 23:13:35'),
(15, 'G7-PE-101', 'Physical Education', 2, '7', 6, '2026-03-22 23:06:52', '2026-03-22 23:13:35'),
(16, 'G7-ART-101', 'Arts', 2, '7', 6, '2026-03-22 23:06:52', '2026-03-22 23:13:35'),
(17, 'G7-MUS-101', 'Music', 2, '7', 6, '2026-03-22 23:06:52', '2026-03-22 23:13:35');

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
(1, 1, 8, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19'),
(2, 1, 9, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19'),
(3, 1, 10, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19'),
(4, 1, 11, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19'),
(5, 1, 12, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19'),
(6, 1, 13, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19'),
(7, 1, 14, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19'),
(8, 1, 15, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19'),
(9, 1, 16, '1st Semester', '2025-2026', 'enrolled', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19');

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
(6, 10, '7', '2026-03-22 22:51:07', '2026-03-22 22:51:07');

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
  `release_date` date DEFAULT NULL,
  `difficulty_level` enum('easy','medium','hard') NOT NULL DEFAULT 'medium',
  `order_index` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `approval_status` varchar(20) NOT NULL DEFAULT 'pending',
  `admin_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(46, '2025_02_16_000007_add_payment_fields_to_fees', 2),
(47, '2025_02_16_100000_add_academic_status_to_students_table', 2),
(48, '2025_02_16_100001_add_leave_to_admission_record_type', 2),
(50, '2025_02_16_200000_create_learning_path_rules_table', 2),
(51, '2025_02_21_000000_create_pre_registrations_table', 2),
(52, '2025_02_21_000001_add_school_id_to_students_table', 2),
(53, '2025_02_21_000002_expand_pre_registrations_table', 2),
(54, '2025_02_21_100000_add_grade_level_to_courses_table', 2),
(55, '2026_02_23_000001_change_units_to_integer_in_courses_table', 2),
(56, '2026_02_23_000002_create_transfer_requests_table', 2),
(57, '2026_03_04_000000_add_transferee_grade_to_pre_registrations', 2),
(58, '2026_03_04_000001_add_test_g7_courses', 2),
(59, '2026_03_19_000001_create_learning_aids_table', 2),
(60, '2026_03_19_000002_create_curriculum_standards_table', 2),
(61, '2026_03_19_000003_create_learning_outcomes_table', 2),
(62, '2026_03_19_000004_create_course_curriculum_alignments_table', 3),
(63, '2026_03_19_000005_create_student_progress_analytics_table', 3),
(64, '2026_03_19_000006_create_assessment_templates_table', 3),
(65, '2026_03_19_000007_create_job_aids_table', 3),
(66, '2026_03_19_000008_create_learning_aid_interactions_table', 3),
(67, '2026_03_22_000001_add_release_date_to_learning_materials', 4),
(70, '2026_03_22_000001_create_auto_generated_learning_aids_table', 5),
(71, '2025_02_16_100002_create_user_dashboard_preferences_table', 6);

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
  `applicant_category` enum('grade7','grade11','grade12','transferee','returnee') DEFAULT NULL,
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
(1, 'chizuu@csp.edu', '$2y$12$nQZWkkr/CxBTyh0ZZmXcEuA.T.SnT3.Y3HVBuCWn9xfmC542DWyU.', 'Chester John Duran Flores', 'Grade 7', 1, 'grade7', NULL, NULL, 'Flores', 'Chester John', 'Duran', 0, NULL, 'Male', '2002-03-28', 'Polomolok, South Cotabato', 'Single', 'N/A', '09632637862', 'Polomolok, South Cotabato', 'Polomolok, South Cotabato', 'Filipino', NULL, 'father,mother,spouse', '{\"father\":{\"last_name\":\"Flores\",\"first_name\":\"Adrian\",\"middle_name\":\"Lolo\",\"telephone\":\"N\\/A\",\"mobile\":\"09123854231\",\"occupation\":\"DOLE\",\"deceased\":false},\"mother\":{\"last_name\":\"Duran\",\"first_name\":\"Roleen Joy\",\"middle_name\":\"Samiana\",\"telephone\":\"N\\/A\",\"mobile\":\"09283746147\",\"occupation\":\"OFW\",\"deceased\":false},\"spouse\":{\"last_name\":\"Ya\\u00f1ez\",\"first_name\":\"Janha Jone\",\"middle_name\":\"Ampong\",\"telephone\":\"N\\/A\",\"mobile\":\"09283746123\",\"occupation\":\"Housewife\"}}', '2015-2016', NULL, NULL, 'Joselyn Duran', 'Polomolok, South Cotabato', '09709887251', 'Guardian', 'joselynduran@gmail.com', 'rejected', 'a', NULL, '2026-04-09 19:27:33', '2026-04-09 10:56:14', '2026-04-09 11:27:33');

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

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`) VALUES
('max_units_per_semester', '46'),
('test_key', 'test_value');

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
  `academic_status` varchar(32) DEFAULT NULL COMMENT 'regular, irregular, probationary, at-risk',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `school_id`, `user_id`, `student_number`, `program`, `year_level`, `admission_date`, `status`, `academic_status`, `created_at`, `updated_at`) VALUES
(1, '2027-00001', 15, '2027-00001', NULL, 1, '2026-04-09', 'active', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19');

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
(1, 'Principal', 'admin@csp.edu', NULL, '$2y$12$QqvTA2ymEzJ7FfQKIvaW1e0g8e3TDdT0q0QC4m01fmrZoX0ES/Xk.', 'admin', NULL, '2026-02-15 20:57:28', '2026-03-27 09:53:31'),
(10, 'Chester John Flores', 'cheezyflrs@csp.edu', NULL, '$2y$12$PUxEzBzB7Yg7xJCOYZ9xpupBEC2AwnBI/lY7Ip8rImyDJfEhRLDqC', 'instructor', NULL, '2026-03-22 22:51:07', '2026-03-22 22:51:07'),
(15, 'Chester John Duran Flores', 'chizuu@csp.edu', '2026-04-09 11:25:19', '$2y$12$nQZWkkr/CxBTyh0ZZmXcEuA.T.SnT3.Y3HVBuCWn9xfmC542DWyU.', 'student', NULL, '2026-04-09 11:25:19', '2026-04-09 11:25:19');

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
  ADD KEY `grades_enrollment_id_foreign` (`enrollment_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_progress_analytics`
--
ALTER TABLE `student_progress_analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_reminders`
--
ALTER TABLE `study_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_requests`
--
ALTER TABLE `transfer_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  ADD CONSTRAINT `assessment_templates_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assessment_templates_learning_material_id_foreign` FOREIGN KEY (`learning_material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `grades_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `learning_path_rules_source_course_id_foreign` FOREIGN KEY (`source_course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_path_rules_source_material_id_foreign` FOREIGN KEY (`source_material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_path_rules_target_course_id_foreign` FOREIGN KEY (`target_course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_path_rules_target_material_id_foreign` FOREIGN KEY (`target_material_id`) REFERENCES `learning_materials` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `transfer_requests`
--
ALTER TABLE `transfer_requests`
  ADD CONSTRAINT `transfer_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_dashboard_preferences`
--
ALTER TABLE `user_dashboard_preferences`
  ADD CONSTRAINT `user_dashboard_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
