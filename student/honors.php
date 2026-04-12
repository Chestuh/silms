<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT * FROM academic_honors WHERE student_id = ? ORDER BY school_year DESC, semester');
$stmt->execute([$sid]);
$honors = $stmt->fetchAll();

$pageTitle = 'Academic Honors';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-trophy me-2"></i>Academic Honors</h2>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Honor Type</th><th>Semester</th><th>School Year</th></tr></thead>
            <tbody>
                <?php foreach ($honors as $h): ?>
                <tr>
                    <td><?= htmlspecialchars($h['honor_type']) ?></td>
                    <td><?= htmlspecialchars($h['semester'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($h['school_year'] ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($honors)): ?>
                <tr><td colspan="3" class="text-center text-muted">No academic honors recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
