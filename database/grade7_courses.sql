-- ============================================================================
-- Grade 7 Courses Setup Script
-- ============================================================================
-- This script adds Grade 7 subjects to the courses table.
-- These courses will be automatically credited to Grade 7 students upon 
-- pre-registration approval.
-- ============================================================================

-- First, ensure the grade_level column exists in courses table
-- (It should exist from the migration, but this is safe to run)
ALTER TABLE courses ADD COLUMN grade_level ENUM('7', '8', '9', '10', '11', '12') DEFAULT NULL 
AFTER units;

-- Get or create a default instructor for Grade 7 courses
-- If no instructor exists, we'll insert NULL for instructor_id
SET @instructor_id = (SELECT id FROM instructors LIMIT 1);

-- Insert Grade 7 Courses
-- These courses are mandatory for all Grade 7 students
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

-- Verify the courses were created
SELECT 'Grade 7 Courses Successfully Added' as Status, COUNT(*) as Total_Count
FROM courses WHERE grade_level = '7';
