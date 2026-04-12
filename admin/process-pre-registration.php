<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if (!$id || !in_array($action, ['approve', 'reject'])) {
    header('Location: ' . BASE_URL . 'admin/pre-registrations.php');
    exit;
}

try {
    $pdo = getDB();
    
    // Get pre-registration
    $stmt = $pdo->prepare('SELECT * FROM pre_registrations WHERE id = ?');
    $stmt->execute([$id]);
    $reg = $stmt->fetch();
    
    if (!$reg) {
        die('Pre-registration not found.');
    }
    
    if ($action === 'approve') {
        // Create user account
        $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, role, full_name) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $reg['email'],
            $reg['password_hash'],
            'student',
            $reg['full_name']
        ]);
        $user_id = $pdo->lastInsertId();
        
        // Generate school ID (format: YYYY-00001)
        $current_year = 2027; // Starting year
        $prefix = $current_year . '-';
        
        // Get the last school_id with this prefix to determine next number
        $stmt = $pdo->prepare('SELECT school_id FROM students WHERE school_id LIKE ? ORDER BY school_id DESC LIMIT 1');
        $stmt->execute([$prefix . '%']);
        $last_school_id = $stmt->fetchColumn();
        
        if ($last_school_id) {
            // Extract number from last school_id and increment
            $last_number = (int)substr($last_school_id, strlen($prefix));
            $next_number = $last_number + 1;
        } else {
            $next_number = 1;
        }
        
        $school_id = $prefix . str_pad($next_number, 5, '0', STR_PAD_LEFT);
        
        // Create student record
        $stmt = $pdo->prepare('INSERT INTO students (user_id, school_id, program, year_level, admission_date, status) VALUES (?, ?, ?, ?, CURDATE(), ?)');
        $stmt->execute([
            $user_id,
            $school_id,
            $reg['program'],
            $reg['year_level'],
            'active'
        ]);
        $student_id = $pdo->lastInsertId();
        
        // Auto-credit courses for the student's grade level
        // Determine grade level from applicant_category
        $grade_level = null;
        if (!empty($reg['applicant_category'])) {
            if ($reg['applicant_category'] === 'grade7') {
                $grade_level = '7';
            } elseif ($reg['applicant_category'] === 'grade11') {
                $grade_level = '11';
            } elseif ($reg['applicant_category'] === 'grade12') {
                $grade_level = '12';
            }
        }
        
        // If a grade level is determined, enroll in all courses for that grade
        if ($grade_level) {
            // Get current semester and school year (default to 1st semester and 2024-2025)
            $semester = '1st';
            $school_year = '2024-2025';
            
            try {
                // Get all courses for the determined grade level
                $stmt = $pdo->prepare('SELECT id FROM courses WHERE grade_level = ?');
                $stmt->execute([$grade_level]);
                $courses = $stmt->fetchAll();
                
                // Create enrollment for each course
                if (!empty($courses)) {
                    foreach ($courses as $course) {
                        // Check if enrollment already exists
                        $checkStmt = $pdo->prepare('SELECT id FROM enrollments WHERE student_id = ? AND course_id = ? AND semester = ? AND school_year = ?');
                        $checkStmt->execute([$student_id, $course['id'], $semester, $school_year]);
                        
                        if (!$checkStmt->fetch()) {
                            // Create new enrollment
                            $enrollStmt = $pdo->prepare('INSERT INTO enrollments (student_id, course_id, semester, school_year, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
                            $enrollStmt->execute([$student_id, $course['id'], $semester, $school_year, 'enrolled']);
                        }
                    }
                }
            } catch (PDOException $e) {
                // Log the error but don't fail the student creation
                error_log('Auto-credit enrollment failed: ' . $e->getMessage());
            }
        }
        
        // Update pre-registration status
        $stmt = $pdo->prepare('UPDATE pre_registrations SET status = ?, approved_at = NOW() WHERE id = ?');
        $stmt->execute(['approved', $id]);
        
        header('Location: ' . BASE_URL . 'admin/view-pre-registration.php?id=' . $id . '&msg=approved');
        
    } elseif ($action === 'reject') {
        $rejection_reason = $_POST['rejection_reason'] ?? '';
        
        if (!$rejection_reason) {
            die('Rejection reason is required.');
        }
        
        // Update pre-registration status
        $stmt = $pdo->prepare('UPDATE pre_registrations SET status = ?, rejection_reason = ?, rejected_at = NOW() WHERE id = ?');
        $stmt->execute(['rejected', $rejection_reason, $id]);
        
        header('Location: ' . BASE_URL . 'admin/view-pre-registration.php?id=' . $id . '&msg=rejected');
    }
} catch (PDOException $e) {
    die('Error: ' . htmlspecialchars($e->getMessage()));
}
