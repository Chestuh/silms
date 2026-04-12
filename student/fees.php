<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT * FROM fees WHERE student_id = ? ORDER BY due_date, status');
$stmt->execute([$sid]);
$fees = $stmt->fetchAll();

$pageTitle = 'Fees & Payments';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-credit-card me-2"></i>Fees & Payment Review</h2>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Fee Type</th><th>Amount</th><th>Due Date</th><th>Status</th><th>Paid At</th></tr></thead>
            <tbody>
                <?php foreach ($fees as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['fee_type']) ?></td>
                    <td>₱<?= number_format($f['amount'], 2) ?></td>
                    <td><?= $f['due_date'] ? date('M j, Y', strtotime($f['due_date'])) : '—' ?></td>
                    <td><span class="badge bg-<?= $f['status'] === 'paid' ? 'success' : ($f['status'] === 'overdue' ? 'danger' : 'warning') ?>"><?= htmlspecialchars($f['status']) ?></span></td>
                    <td><?= $f['paid_at'] ? date('M j, Y g:i A', strtotime($f['paid_at'])) : '—' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($fees)): ?>
                <tr><td colspan="5" class="text-center text-muted">No fee records.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
