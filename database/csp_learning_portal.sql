```sql
-- ============================================
-- CSP LEARNING PORTAL - FULL DEMO SEED (ORDERED)
-- ============================================

USE csp_learning_portal;

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------
-- CLEAN TABLES (SAFE FOR DEMO)
-- --------------------------------------------
TRUNCATE TABLE activity_log;
TRUNCATE TABLE grades;
TRUNCATE TABLE enrollments;
TRUNCATE TABLE learning_progress;
TRUNCATE TABLE material_ratings;
TRUNCATE TABLE student_progress_analytics;
TRUNCATE TABLE job_aids;
TRUNCATE TABLE self_assessments;
TRUNCATE TABLE study_reminders;
TRUNCATE TABLE transfer_requests;
TRUNCATE TABLE credential_requests;
TRUNCATE TABLE messages;
TRUNCATE TABLE disciplinary_records;
TRUNCATE TABLE admission_records;
TRUNCATE TABLE academic_honors;
TRUNCATE TABLE fees;
TRUNCATE TABLE learning_materials;
TRUNCATE TABLE courses;
TRUNCATE TABLE students;
TRUNCATE TABLE users;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------
-- 1. USERS
-- --------------------------------------------
INSERT INTO users (id, name, email, password, role)
VALUES
(1, 'Admin User', 'admin@test.com', '123456', 'admin'),
(15, 'Student User', 'student@test.com', '123456', 'student'),
(21, 'Instructor User', 'teacher@test.com', '123456', 'instructor');

-- --------------------------------------------
-- 2. STUDENTS
-- --------------------------------------------
INSERT INTO students (id, school_id, user_id, student_number, program, year_level, admission_date, status, academic_status)
VALUES
(10, '2027-00010', 15, 'G7-0001', 'Grade 7', 7, '2026-06-01', 'active', 'regular'),
(11, '2027-00011', 15, 'G8-0001', 'Grade 8', 8, '2026-06-01', 'active', 'regular'),
(12, '2027-00012', 15, 'G9-0001', 'Grade 9', 9, '2026-06-01', 'active', 'regular'),
(13, '2027-00013', 15, 'G10-0001', 'Grade 10', 10, '2026-06-01', 'active', 'regular'),
(14, '2027-00014', 15, 'G11-0001', 'ABM', 11, '2026-06-01', 'active', 'regular'),
(15, '2027-00015', 15, 'G12-0001', 'STEM', 12, '2026-06-01', 'active', 'regular');

-- --------------------------------------------
-- 3. COURSES (SHS SAMPLE)
-- --------------------------------------------
INSERT INTO courses (id, code, title, units, grade_level, semester)
VALUES
(55, 'G11-ABM-01', 'Oral Communication', 3, '11', '1st Semester'),
(57, 'G11-ABM-02', 'Business Math', 3, '11', '1st Semester'),
(63, 'G11-STEM-01', 'General Math', 3, '11', '1st Semester'),
(71, 'G12-STEM-01', 'Physics', 3, '12', '1st Semester'),
(79, 'G12-TVL-ICT-01', 'Programming', 3, '12', '1st Semester');

-- --------------------------------------------
-- 4. LEARNING MATERIALS
-- --------------------------------------------
INSERT INTO learning_materials (id, course_id, title, format, order_index, approval_status)
VALUES
(1, 55, 'Lesson 1', 'document', 1, 'approved'),
(2, 55, 'Video 1', 'video', 2, 'approved'),
(3, 57, 'Lesson 1', 'document', 1, 'approved'),
(4, 63, 'Lesson 1', 'document', 1, 'approved'),
(5, 71, 'Lesson 1', 'document', 1, 'approved');

-- --------------------------------------------
-- 5. ENROLLMENTS
-- --------------------------------------------
INSERT INTO enrollments (id, student_id, course_id, semester, school_year, status)
VALUES
(1, 10, 55, '1st Semester', '2026-2027', 'enrolled'),
(2, 10, 57, '1st Semester', '2026-2027', 'enrolled'),
(3, 11, 63, '1st Semester', '2026-2027', 'enrolled'),
(4, 12, 71, '1st Semester', '2026-2027', 'enrolled'),
(5, 13, 79, '1st Semester', '2026-2027', 'enrolled');

-- --------------------------------------------
-- 6. GRADES
-- --------------------------------------------
INSERT INTO grades (enrollment_id, midterm_grade, final_grade, gwa_contribution)
VALUES
(1, 85, 88, 1.25),
(2, 87, 90, 1.20),
(3, 88, 89, 1.18),
(4, 75, 78, 1.75),
(5, 92, 94, 1.05);

-- --------------------------------------------
-- 7. FEES
-- --------------------------------------------
INSERT INTO fees (student_id, fee_type, amount, due_date, status)
VALUES
(10, 'Tuition', 5000, '2026-07-01', 'paid'),
(11, 'Tuition', 5200, '2026-07-01', 'pending');

-- --------------------------------------------
-- 8. HONORS
-- --------------------------------------------
INSERT INTO academic_honors (student_id, honor_type, semester, school_year)
VALUES
(10, 'With Honors', '1st Semester', '2026-2027');

-- --------------------------------------------
-- 9. DISCIPLINARY
-- --------------------------------------------
INSERT INTO disciplinary_records (student_id, incident_date, description, status)
VALUES
(11, '2026-08-10', 'Late submission', 'resolved');

-- --------------------------------------------
-- 10. ADMISSION
-- --------------------------------------------
INSERT INTO admission_records (student_id, record_type, date_processed)
VALUES
(10, 'admission', '2026-06-01');

-- --------------------------------------------
-- 11. LEARNING PROGRESS
-- --------------------------------------------
INSERT INTO learning_progress (student_id, material_id, progress_percent)
VALUES
(10, 1, 100),
(10, 2, 80);

-- --------------------------------------------
-- 12. ANALYTICS
-- --------------------------------------------
INSERT INTO student_progress_analytics (student_id, course_id, completion_rate, current_grade, at_risk_status)
VALUES
(10, 55, 100, 88, 'on_track'),
(11, 63, 60, 78, 'at_risk');

-- --------------------------------------------
-- DONE
-- --------------------------------------------
```
