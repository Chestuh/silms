<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT * FROM admission_records WHERE student_id = ? ORDER BY date_processed DESC');
$stmt->execute([$sid]);
$records = $stmt->fetchAll();

$pageTitle = 'Admission Records';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-file-earmark-text me-2"></i>Admission Records</h2>
<p class="text-muted">Transfer and re-admission handling records.</p>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Type</th><th>Date Processed</th><th>Notes</th></tr></thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td><span class="badge bg-info"><?= htmlspecialchars($r['record_type']) ?></span></td>
                    <td><?= $r['date_processed'] ? date('M j, Y', strtotime($r['date_processed'])) : '—' ?></td>
                    <td><?= htmlspecialchars($r['notes'] ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($records)): ?>
                <tr><td colspan="3" class="text-center text-muted">No admission/transfer/re-admission records.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
