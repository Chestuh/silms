<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT e.*, c.code, c.title, c.units FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE e.student_id = ? ORDER BY e.school_year DESC, e.semester');
$stmt->execute([$sid]);
$enrollments = $stmt->fetchAll();

$pageTitle = 'Academic Status';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>Academic Status</h2>
<div class="card mb-3">
    <div class="card-body">
        <p class="mb-0"><strong>Overall status:</strong> <span class="badge bg-success"><?= htmlspecialchars($user['student_status'] ?? 'active') ?></span></p>
    </div>
</div>
<div class="card">
    <div class="card-header">Enrollment & Academic Load</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Course</th><th>Title</th><th>Units</th><th>Semester</th><th>School Year</th><th>Status</th></tr></thead>
            <tbody>
                <?php 
                $totalUnits = 0;
                foreach ($enrollments as $e): 
                    if ($e['status'] === 'enrolled') $totalUnits += (float)$e['units'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($e['code']) ?></td>
                    <td><?= htmlspecialchars($e['title']) ?></td>
                    <td><?= htmlspecialchars($e['units']) ?></td>
                    <td><?= htmlspecialchars($e['semester'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($e['school_year'] ?? '—') ?></td>
                    <td><span class="badge bg-<?= $e['status'] === 'enrolled' ? 'primary' : ($e['status'] === 'completed' ? 'success' : 'secondary') ?>"><?= htmlspecialchars($e['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($enrollments)): ?>
                <tr><td colspan="6" class="text-center text-muted">No enrollment records.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($totalUnits > 0): ?>
    <div class="card-footer"><strong>Current load (enrolled):</strong> <?= number_format($totalUnits, 1) ?> units</div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
