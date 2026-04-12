<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT sa.*, c.code AS course_code FROM self_assessments sa LEFT JOIN courses c ON sa.course_id = c.id WHERE sa.student_id = ? ORDER BY sa.completed_at DESC');
$stmt->execute([$sid]);
$assessments = $stmt->fetchAll();

$pageTitle = 'Self-Assessment';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>Student Self-Assessment Checker</h2>
<div class="card">
    <div class="card-body">
        <p class="text-muted mb-0">Self-assessment forms may be assigned by your instructor. Completed assessments and scores are listed below.</p>
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">My self-assessments</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Title</th><th>Course</th><th>Score</th><th>Completed</th></tr></thead>
            <tbody>
                <?php foreach ($assessments as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['title'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($a['course_code'] ?? '—') ?></td>
                    <td><?= $a['score'] !== null ? (int)$a['score'] : '—' ?></td>
                    <td><?= $a['completed_at'] ? date('M j, Y', strtotime($a['completed_at'])) : '—' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($assessments)): ?>
                <tr><td colspan="4" class="text-center text-muted">No self-assessments yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
