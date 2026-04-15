<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

// Upcoming study reminders (for notifier)
$stmt = $pdo->prepare('SELECT id, title, remind_at FROM study_reminders WHERE student_id = ? AND remind_at >= NOW() ORDER BY remind_at LIMIT 5');
$stmt->execute([$sid]);
$upcomingReminders = $stmt->fetchAll();
$upcomingCount = count($upcomingReminders);
// Quick stats
$stmt = $pdo->prepare('SELECT COUNT(*) FROM enrollments WHERE student_id = ? AND status = "enrolled"');
$stmt->execute([$sid]);
$enrolled = $stmt->fetchColumn();
$stmt = $pdo->prepare('SELECT COUNT(*) FROM learning_progress lp JOIN learning_materials lm ON lp.material_id = lm.id WHERE lp.student_id = ? AND lp.progress_percent = 100');
$stmt->execute([$sid]);
$completed = $stmt->fetchColumn();
$stmt = $pdo->prepare('SELECT COUNT(*) FROM fees WHERE student_id = ? AND status = "pending"');
$stmt->execute([$sid]);
$pendingFees = $stmt->fetchColumn();
$stmt = $pdo->prepare('SELECT AVG((g.midterm_grade + g.final_grade)/2) FROM grades g JOIN enrollments e ON g.enrollment_id = e.id WHERE e.student_id = ?');
$stmt->execute([$sid]);
$avgGrade = $stmt->fetchColumn();

// Get recent courseS with enrollment status
$stmt = $pdo->prepare('SELECT c.id, c.course_code, c.course_name, e.id as enrollment_id FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE e.student_id = ? ORDER BY e.created_at DESC LIMIT 6');
$stmt->execute([$sid]);
$recentCourses = $stmt->fetchAll();

// Get recent learning progress
$stmt = $pdo->prepare('SELECT lm.title, lp.progress_percent, lp.updated_at FROM learning_progress lp JOIN learning_materials lm ON lp.material_id = lm.id WHERE lp.student_id = ? ORDER BY lp.updated_at DESC LIMIT 5');
$stmt->execute([$sid]);
$recentProgress = $stmt->fetchAll();

$pageTitle = 'Student Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="card mb-4 text-white" style="background: linear-gradient(90deg,#0d6efd,#1755cc); border-radius:12px;">
    <div class="card-body d-flex align-items-center justify-content-between">
        <div>
            <h2 class="mb-1">Welcome back, <?= htmlspecialchars($user['full_name']) ?></h2>
            <div class="small opacity-75"><?= htmlspecialchars($user['program'] ?? '') ?></div>
        </div>
        <div class="text-end">
            <div class="dropdown">
                <button class="btn btn-light btn-sm position-relative" type="button" id="reminderDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell-fill"></i>
                    <?php if (!empty($upcomingCount)): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $upcomingCount ?></span><?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reminderDropdown" style="min-width:320px;">
                    <li class="dropdown-header">Upcoming reminders</li>
                    <?php if (!empty($upcomingReminders)): ?>
                        <?php foreach ($upcomingReminders as $r): ?>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-start" href="reminders.php#rem-<?= (int)$r['id'] ?>">
                                    <div>
                                        <div><?= htmlspecialchars($r['title']) ?></div>
                                        <div class="small text-muted"><?= date('M j, Y H:i', strtotime($r['remind_at'])) ?></div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="reminders.php">View all reminders</a></li>
                    <?php else: ?>
                        <li><span class="dropdown-item text-muted">No upcoming reminders</span></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <h6 class="text-muted mb-1">Enrolled Courses</h6>
                <h3 class="mb-0"><?= (int)$enrolled ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card success h-100">
            <div class="card-body">
                <h6 class="text-muted mb-1">Materials Completed</h6>
                <h3 class="mb-0"><?= (int)$completed ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <h6 class="text-muted mb-1">Average Grade</h6>
                <h3 class="mb-0"><?= $avgGrade ? number_format($avgGrade, 1) : '—' ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card warning h-100">
            <div class="card-body">
                <h6 class="text-muted mb-1">Pending Fees</h6>
                <h3 class="mb-0"><?= (int)$pendingFees ?></h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-book me-2"></i>My Courses
            </div>
            <div class="list-group list-group-flush">
                <?php if (!empty($recentCourses)): ?>
                    <?php foreach ($recentCourses as $course): ?>
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($course['course_code']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($course['course_name']) ?></div>
                            </div>
                            <span class="badge bg-success">Active</span>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="list-group-item text-muted">No courses enrolled yet</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="bi bi-lightning-charge me-2"></i>Learning Progress
            </div>
            <div class="list-group list-group-flush">
                <?php if (!empty($recentProgress)): ?>
                    <?php foreach ($recentProgress as $prog): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="fw-bold"><?= htmlspecialchars($prog['title']) ?></div>
                                <span class="badge bg-<?= $prog['progress_percent'] == 100 ? 'success' : ($prog['progress_percent'] >= 50 ? 'warning' : 'secondary') ?>"><?= (int)$prog['progress_percent'] ?>%</span>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-<?= $prog['progress_percent'] == 100 ? 'success' : ($prog['progress_percent'] >= 50 ? 'warning' : 'secondary') ?>" role="progressbar" style="width: <?= (int)$prog['progress_percent'] ?>%" aria-valuenow="<?= (int)$prog['progress_percent'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="small text-muted mt-1">Updated: <?= date('M j, Y', strtotime($prog['updated_at'])) ?></div>
                        </div>
</div>
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">Quick Links</div>
            <div class="list-group list-group-flush">
                <a href="gwa.php" class="list-group-item list-group-item-action"><i class="bi bi-calculator me-2"></i>GWA & Grades</a>
                <a href="academic-status.php" class="list-group-item list-group-item-action"><i class="bi bi-clipboard-check me-2"></i>Academic Status</a>
                <a href="learning.php" class="list-group-item list-group-item-action"><i class="bi bi-journal-book me-2"></i>Learning Materials</a>
                <a href="progress.php" class="list-group-item list-group-item-action"><i class="bi bi-graph-up me-2"></i>Learning Progress</a>
                <a href="messages.php" class="list-group-item list-group-item-action"><i class="bi bi-chat-dots me-2"></i>Messages</a>
                <a href="fees.php" class="list-group-item list-group-item-action"><i class="bi bi-credit-card me-2"></i>Fees & Payments</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">My Info</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span>Student No.</span><strong><?= htmlspecialchars($user['student_number'] ?? '—') ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Program</span><strong><?= htmlspecialchars($user['program'] ?? '—') ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Year Level</span><strong><?= (int)($user['year_level'] ?? 0) ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Status</span><span class="badge bg-success"><?= htmlspecialchars($user['student_status'] ?? 'active') ?></span></li>
            </ul>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
