-- ============================================================================
-- Grade 7 Courses Setup Script (Courses Only)
-- ============================================================================
-- This script inserts the 8 Grade 7 courses into the database.
-- Run this if applicant_category and grade_level columns already exist.
-- ============================================================================

-- Get default instructor
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

-- Verify the setup
SELECT 'Grade 7 Setup Complete' as Setup_Status,
    (SELECT COUNT(*) FROM courses WHERE grade_level = '7') as Grade_7_Courses,
    (SELECT COUNT(*) FROM pre_registrations WHERE applicant_category = 'grade7') as Grade_7_Students;
