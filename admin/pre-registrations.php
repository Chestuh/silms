<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');
$user = currentUser();
$pdo = getDB();

$status_filter = $_GET['status'] ?? 'pending';
$valid_statuses = ['pending', 'approved', 'rejected'];
if (!in_array($status_filter, $valid_statuses)) {
    $status_filter = 'pending';
}

// Get pre-registrations
$stmt = $pdo->prepare('SELECT * FROM pre_registrations WHERE status = ? ORDER BY created_at DESC');
$stmt->execute([$status_filter]);
$pre_registrations = $stmt->fetchAll();

// Count by status
$counts = [];
foreach ($valid_statuses as $s) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM pre_registrations WHERE status = ?');
    $stmt->execute([$s]);
    $counts[$s] = $stmt->fetchColumn();
}

$pageTitle = 'Pre-Registrations Management';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pre-Registrations Management</h2>
</div>

<!-- Status Filter Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link <?= $status_filter === 'pending' ? 'active' : '' ?>" href="?status=pending">
            Pending <span class="badge bg-warning"><?= $counts['pending'] ?></span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link <?= $status_filter === 'approved' ? 'active' : '' ?>" href="?status=approved">
            Approved <span class="badge bg-success"><?= $counts['approved'] ?></span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link <?= $status_filter === 'rejected' ? 'active' : '' ?>" href="?status=rejected">
            Rejected <span class="badge bg-danger"><?= $counts['rejected'] ?></span>
        </a>
    </li>
</ul>

<?php if ($pre_registrations): ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Year Level</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pre_registrations as $reg): ?>
                        <tr>
                            <td class="fw-500"><?= htmlspecialchars($reg['full_name']) ?></td>
                            <td><?= htmlspecialchars($reg['email']) ?></td>
                            <td><?= htmlspecialchars($reg['program']) ?></td>
                            <td><span class="badge bg-info"><?= (int)$reg['year_level'] ?></span></td>
                            <td><?= date('M d, Y', strtotime($reg['created_at'])) ?></td>
                            <td>
                                <?php if ($reg['status'] === 'pending'): ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php elseif ($reg['status'] === 'approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>admin/view-pre-registration.php?id=<?= (int)$reg['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <?php if ($reg['status'] === 'pending'): ?>
                                    <a href="<?= BASE_URL ?>admin/process-pre-registration.php?id=<?= (int)$reg['id'] ?>&action=approve" class="btn btn-sm btn-success" onclick="return confirm('Approve this pre-registration?')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="rejectModal(<?= (int)$reg['id'] ?>)">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        No <?= htmlspecialchars($status_filter) ?> pre-registrations at this time.
    </div>
<?php endif; ?>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Pre-Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST" action="<?= BASE_URL ?>admin/process-pre-registration.php">
                <div class="modal-body">
                    <input type="hidden" name="id" id="rejectId">
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Explain why this pre-registration is being rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
function rejectModal(id) {
    document.getElementById('rejectId').value = id;
    rejectModal.show();
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
