-- CSP Learning Portal - Database Schema
-- Run this in phpMyAdmin or MySQL to create the database and tables

CREATE DATABASE IF NOT EXISTS csp_learning_portal;
USE csp_learning_portal;

-- Users and roles
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'instructor', 'admin') NOT NULL DEFAULT 'student',
    full_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Pre-registrations (students awaiting admin approval)
CREATE TABLE pre_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    program VARCHAR(255),
    year_level INT DEFAULT 1,
    applicant_category ENUM('grade7', 'grade8', 'grade9', 'grade10', 'grade11', 'grade12', 'transferee', 'returnee') DEFAULT 'grade7',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT,
    approved_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Student profiles (extends users)
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    school_id VARCHAR(50) UNIQUE,
    student_number VARCHAR(50) UNIQUE,
    program VARCHAR(255),
    year_level INT DEFAULT 1,
    admission_date DATE,
    status ENUM('active', 'inactive', 'transferred', 'graduated') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Instructors
CREATE TABLE instructors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    department VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Courses
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    units DECIMAL(4,2) DEFAULT 3.00,
    grade_level ENUM('7', '8', '9', '10', '11', '12') DEFAULT NULL,
    instructor_id INT,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id)
);

-- Student enrollment
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    semester VARCHAR(20),
    school_year VARCHAR(20),
    status ENUM('enrolled', 'dropped', 'completed') DEFAULT 'enrolled',
    UNIQUE KEY (student_id, course_id, semester, school_year),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Grades and academic records
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL,
    midterm_grade DECIMAL(5,2),
    final_grade DECIMAL(5,2),
    gwa_contribution DECIMAL(6,4),
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id)
);

-- Admission records
CREATE TABLE admission_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    record_type ENUM('admission', 'transfer', 'readmission') NOT NULL,
    date_processed DATE,
    notes TEXT,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Disciplinary records
CREATE TABLE disciplinary_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    incident_date DATE,
    description TEXT,
    sanction VARCHAR(255),
    status ENUM('pending', 'resolved', 'appealed') DEFAULT 'pending',
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Academic honors
CREATE TABLE academic_honors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    honor_type VARCHAR(100),
    semester VARCHAR(20),
    school_year VARCHAR(20),
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Fees and payments
CREATE TABLE fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    fee_type VARCHAR(100),
    amount DECIMAL(10,2) NOT NULL,
    due_date DATE,
    status ENUM('pending', 'paid', 'overdue') DEFAULT 'pending',
    paid_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Learning materials (content)
CREATE TABLE learning_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    format ENUM('pdf', 'video', 'link', 'document', 'quiz') DEFAULT 'document',
    file_path VARCHAR(500),
    url VARCHAR(500),
    difficulty_level ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
    order_index INT DEFAULT 0,
    archived TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Student progress on materials
CREATE TABLE learning_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    material_id INT NOT NULL,
    progress_percent INT DEFAULT 0,
    time_spent_minutes INT DEFAULT 0,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (material_id) REFERENCES learning_materials(id),
    UNIQUE KEY (student_id, material_id)
);

-- Material ratings
CREATE TABLE material_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    material_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (student_id, material_id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (material_id) REFERENCES learning_materials(id)
);

-- Study reminders
CREATE TABLE study_reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    material_id INT,
    title VARCHAR(255),
    remind_at DATETIME NOT NULL,
    sent TINYINT(1) DEFAULT 0,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (material_id) REFERENCES learning_materials(id)
);

-- Rubrics and grading criteria
CREATE TABLE rubrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT,
    course_id INT,
    name VARCHAR(255),
    criteria_json TEXT,
    FOREIGN KEY (material_id) REFERENCES learning_materials(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Messages (student-instructor communication)
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(255),
    body TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);

-- Credential requests
CREATE TABLE credential_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    credential_type VARCHAR(100),
    status ENUM('pending', 'processing', 'ready', 'released') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Activity log for performance history
CREATE TABLE activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100),
    entity_type VARCHAR(50),
    entity_id INT,
    details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Self-assessment (checker)
CREATE TABLE self_assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT,
    title VARCHAR(255),
    responses_json TEXT,
    score INT,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Insert demo admin and sample data
INSERT INTO users (email, password_hash, role, full_name) VALUES
('admin@csp.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Portal Admin'),
('instructor@csp.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'Jane Instructor'),
('student@csp.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'John Student');
-- Password for all demo accounts: password

INSERT INTO students (user_id, school_id, student_number, program, year_level, admission_date, status)
SELECT id, '2027-00001', '2024-001', 'BS Computer Science', 1, CURDATE(), 'active' FROM users WHERE email = 'student@csp.edu';

INSERT INTO instructors (user_id, department)
SELECT id, 'Computer Science' FROM users WHERE email = 'instructor@csp.edu';

INSERT INTO courses (code, title, units, instructor_id) VALUES
('CS101', 'Introduction to Programming', 3.00, 1),
('CS102', 'Data Structures', 3.00, 1);

INSERT INTO learning_materials (course_id, title, description, format, difficulty_level, order_index) VALUES
(1, 'Welcome and Syllabus', 'Course overview and requirements', 'document', 'easy', 1),
(1, 'Variables and Data Types', 'Video lesson on basics', 'video', 'easy', 2),
(2, 'Arrays and Lists', 'Introduction to linear structures', 'document', 'medium', 1);

-- Enroll demo student in courses and add grades
INSERT INTO enrollments (student_id, course_id, semester, school_year, status) VALUES
(1, 1, '1st', '2024-2025', 'enrolled'),
(1, 2, '1st', '2024-2025', 'enrolled');
INSERT INTO grades (enrollment_id, midterm_grade, final_grade) VALUES
(1, 88, 90),
(2, 85, 87);
