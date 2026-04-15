<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('instructor');
$user = currentUser();
$pdo = getDB();
$instructorId = $user['id'] ?? null;

// Get courses taught by this instructor
$stmt = $pdo->prepare('SELECT id, course_code, course_name FROM courses WHERE instructor_id = ? LIMIT 8');
$stmt->execute([$instructorId]);
$courses = $stmt->fetchAll();
$totalCourses = count($courses);

// Get total students across all courses
$stmt = $pdo->prepare('SELECT COUNT(DISTINCT e.student_id) FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE c.instructor_id = ?');
$stmt->execute([$instructorId]);
$totalStudents = $stmt->fetchColumn();

// Get total learning materials
$stmt = $pdo->prepare('SELECT COUNT(*) FROM learning_materials WHERE instructor_id = ?');
$stmt->execute([$instructorId]);
$totalMaterials = $stmt->fetchColumn();

// Get pending submissions
$stmt = $pdo->prepare('SELECT COUNT(*) FROM submissions s WHERE s.course_id IN (SELECT id FROM courses WHERE instructor_id = ?) AND s.status = "pending"');
$stmt->execute([$instructorId]);
$pendingSubmissions = $stmt->fetchColumn();

// Get recent student messages
$stmt = $pdo->prepare('SELECT m.id, m.sender_id, u.full_name, m.subject, m.created_at FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.recipient_id = ? ORDER BY m.created_at DESC LIMIT 5');
$stmt->execute([$instructorId]);
$recentMessages = $stmt->fetchAll();

$pageTitle = 'Instructor Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="card mb-4 text-white" style="background: linear-gradient(90deg,#198754,#126545); border-radius:12px;">
    <div class="card-body d-flex align-items-center justify-content-between">
        <div>
            <h2 class="mb-1">Welcome back, <?= htmlspecialchars($user['full_name']) ?></h2>
            <div class="small opacity-75"><?= htmlspecialchars($user['department'] ?? 'Instructor') ?></div>
        </div>
    </div>
</div>

<h3 class="mb-4">Overview</h3>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <h6 class="text-muted mb-1">Active Courses</h6>
                <h3 class="mb-0"><?= (int)$totalCourses ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card primary h-100">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Students</h6>
                <h3 class="mb-0"><?= (int)$totalStudents ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card success h-100">
            <div class="card-body">
                <h6 class="text-muted mb-1">Learning Materials</h6>
                <h3 class="mb-0"><?= (int)$totalMaterials ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card warning h-100">
            <div class="card-body">
                <h6 class="text-muted mb-1">Pending Submissions</h6>
                <h3 class="mb-0"><?= (int)$pendingSubmissions ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-book me-2"></i>My Courses
            </div>
            <div class="list-group list-group-flush">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($course['course_code']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($course['course_name']) ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="list-group-item text-muted">No courses assigned</div>
                <?php endif; ?>
            </div>
            <?php if (!empty($courses)): ?>
                <div class="card-footer">
                    <a href="javascript:void(0)" class="text-decoration-none">View all courses</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="bi bi-chat-dots me-2"></i>Recent Messages
            </div>
            <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                <?php if (!empty($recentMessages)): ?>
                    <?php foreach ($recentMessages as $msg): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($msg['full_name']) ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($msg['subject']) ?></div>
                                </div>
                            </div>
                            <div class="small text-muted mt-1"><?= date('M j, Y H:i', strtotime($msg['created_at'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="list-group-item text-muted">No messages yet</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="bi bi-task-list me-2"></i>Quick Actions
            </div>
            <div class="list-group list-group-horizontal list-group-flush">
                <a href="javascript:void(0)" class="list-group-item list-group-item-action text-center py-3">
                    <i class="bi bi-plus-circle d-block mb-2" style="font-size: 1.5rem;"></i>
                    <div>Manage Courses</div>
                </a>
                <a href="javascript:void(0)" class="list-group-item list-group-item-action text-center py-3">
                    <i class="bi bi-file-text d-block mb-2" style="font-size: 1.5rem;"></i>
                    <div>Upload Materials</div>
                </a>
                <a href="javascript:void(0)" class="list-group-item list-group-item-action text-center py-3">
                    <i class="bi bi-graph-up d-block mb-2" style="font-size: 1.5rem;"></i>
                    <div>View Progress</div>
                </a>
                <a href="javascript:void(0)" class="list-group-item list-group-item-action text-center py-3">
                    <i class="bi bi-star d-block mb-2" style="font-size: 1.5rem;"></i>
                    <div>Rubrics & Grading</div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
