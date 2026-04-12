<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT * FROM disciplinary_records WHERE student_id = ? ORDER BY incident_date DESC');
$stmt->execute([$sid]);
$records = $stmt->fetchAll();

$pageTitle = 'Disciplinary Records';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-shield-exclamation me-2"></i>Disciplinary Records</h2>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Incident Date</th><th>Description</th><th>Sanction</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td><?= $r['incident_date'] ? date('M j, Y', strtotime($r['incident_date'])) : '—' ?></td>
                    <td><?= htmlspecialchars($r['description'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['sanction'] ?? '—') ?></td>
                    <td><span class="badge bg-<?= $r['status'] === 'resolved' ? 'success' : ($r['status'] === 'appealed' ? 'warning' : 'secondary') ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($records)): ?>
                <tr><td colspan="4" class="text-center text-muted">No disciplinary records.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
