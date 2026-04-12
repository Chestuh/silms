-- Pre-Registration System Update
-- Run this SQL to add pre-registration support to your existing database

-- Create pre_registrations table
CREATE TABLE IF NOT EXISTS pre_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    program VARCHAR(255),
    year_level INT DEFAULT 1,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT,
    approved_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add school_id column to students table (if not already present)
ALTER TABLE students ADD COLUMN school_id VARCHAR(50) UNIQUE AFTER id;

-- Update demo student with school_id
UPDATE students SET school_id = '2027-00001' WHERE id = 1;
