-- ============================================================================
-- CSP Learning Portal - Grade 7 Auto-Crediting Setup
-- ============================================================================
-- Run this script to enable Grade 7 student auto-crediting on approval.
-- This adds the necessary columns and courses to support the feature.
-- ============================================================================

-- NOTE: applicant_category and grade_level columns should already exist
-- from previous migrations. If they don't, uncomment the ALTER commands below.

-- UNCOMMENT THESE IF COLUMNS DON'T EXIST:
-- ALTER TABLE pre_registrations 
-- ADD COLUMN applicant_category ENUM('grade7', 'grade11', 'grade12', 'transferee', 'returnee') 
-- DEFAULT 'grade7' AFTER year_level;
-- 
-- ALTER TABLE courses 
-- ADD COLUMN grade_level ENUM('7', '8', '9', '10', '11', '12') 
-- DEFAULT NULL AFTER units;

-- Step 1: Create Grade 7 Courses
-- Get or create a default instructor
SET @instructor_id = (SELECT id FROM instructors LIMIT 1);

-- Insert Grade 7 Courses
INSERT INTO courses (code, title, units, grade_level, instructor_id) VALUES
('G7-MATH-101', 'Grade 7 Mathematics', 3.00, '7', @instructor_id),
('G7-ENG-101', 'Grade 7 English Language', 3.00, '7', @instructor_id),
('G7-SCI-101', 'Grade 7 Science', 3.00, '7', @instructor_id),
('G7-SST-101', 'Grade 7 Social Studies', 3.00, '7', @instructor_id),
('G7-FIL-101', 'Filipino', 2.00, '7', @instructor_id),
('G7-PE-101', 'Physical Education', 2.00, '7', @instructor_id),
('G7-ART-101', 'Arts', 2.00, '7', @instructor_id),
('G7-MUS-101', 'Music', 2.00, '7', @instructor_id)
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    units = VALUES(units),
    grade_level = VALUES(grade_level);

-- Step 4: Verify the setup
SELECT 
    'Grade 7 Setup Complete' as Setup_Status,
    (SELECT COUNT(*) FROM courses WHERE grade_level = '7') as Grade_7_Courses,
    (SELECT COUNT(*) FROM pre_registrations WHERE applicant_category = 'grade7') as Grade_7_Students;

-- ============================================================================
-- TESTING: Create a test Grade 7 student and verify auto-crediting
-- Uncomment below to test (adjust values as needed)
-- ============================================================================
/*
-- Create test pre-registration
INSERT INTO pre_registrations (email, password_hash, full_name, program, year_level, applicant_category, status)
VALUES ('grade7test@csp.edu', PASSWORD('test123'), 'Test Grade 7 Student', 'Grade 7 Program', 1, 'grade7', 'pending');

-- Approve the test pre-registration (this would usually be done by admin)
-- After approval, the system will automatically enroll the student in all Grade 7 courses

-- Check the results:
-- SELECT * FROM enrollments WHERE student_id = (SELECT id FROM students WHERE school_id LIKE '2027-%' ORDER BY id DESC LIMIT 1);
*/

-- ============================================================================
-- How It Works
-- ============================================================================
-- 1. Student fills out pre-registration form, selecting applicant_category = 'grade7'
-- 2. Admin reviews and clicks "Approve" 
-- 3. System creates:
--    - User account
--    - Student record with School ID (e.g., 2027-00001)
--    - Enrollments in all courses marked with grade_level = '7'
-- 4. Grade 7 student can immediately access their courses
-- 5. First semester courses are credited automatically
