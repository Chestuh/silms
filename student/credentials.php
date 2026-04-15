<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['credential_type'])) {
    $stmt = $pdo->prepare('INSERT INTO credential_requests (student_id, credential_type) VALUES (?, ?)');
    $stmt->execute([$sid, trim($_POST['credential_type'])]);
    header('Location: credentials.php?requested=1');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM credential_requests WHERE student_id = ? ORDER BY requested_at DESC');
$stmt->execute([$sid]);
$requests = $stmt->fetchAll();

$pageTitle = 'Credential Request';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-file-earmark-check me-2"></i>School Credential Request</h2>
<?php if (!empty($_GET['requested'])): ?>
<div class="alert alert-success">Request submitted.</div>
<?php endif; ?>
<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-header">New request</div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Credential type</label>
                        <select name="credential_type" class="form-select" required>
                            <option value="" selected disabled>Select credential type</option>
                            <option value="Good Moral">Good Moral</option>
                            <option value="Form 137-A">Form 137-A</option>
                            <option value="Transcript of Records">Transcript of Records</option>
                            <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Your requests</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Type</th><th>Status</th><th>Requested</th></tr></thead>
                    <tbody>
                        <?php foreach ($requests as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['credential_type']) ?></td>
                            <td><span class="badge bg-<?= $r['status'] === 'released' ? 'success' : ($r['status'] === 'ready' ? 'info' : 'secondary') ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                            <td><?= date('M j, Y', strtotime($r['requested_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($requests)): ?>
                        <tr><td colspan="3" class="text-center text-muted">No requests yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
