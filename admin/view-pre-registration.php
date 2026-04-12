<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');
$pdo = getDB();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ' . BASE_URL . 'admin/pre-registrations.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM pre_registrations WHERE id = ?');
$stmt->execute([$id]);
$reg = $stmt->fetch();

if (!$reg) {
    header('HTTP/1.1 404 Not Found');
    echo 'Pre-registration not found.';
    exit;
}

$pageTitle = 'View Pre-Registration';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-4">
            <h2 class="mb-0">Pre-Registration Details</h2>
            <a href="<?= BASE_URL ?>admin/pre-registrations.php" class="btn btn-secondary ms-auto">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Full Name</h6>
                        <p class="lead"><?= htmlspecialchars($reg['full_name']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Email</h6>
                        <p class="lead"><?= htmlspecialchars($reg['email']) ?></p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Program</h6>
                        <p class="lead"><?= htmlspecialchars($reg['program']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Year Level</h6>
                        <p class="lead"><?= (int)$reg['year_level'] ?></p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <p class="lead">
                            <?php if ($reg['status'] === 'pending'): ?>
                                <span class="badge bg-warning">Pending Review</span>
                            <?php elseif ($reg['status'] === 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Submitted</h6>
                        <p class="lead"><?= date('M d, Y H:i', strtotime($reg['created_at'])) ?></p>
                    </div>
                </div>

                <?php if ($reg['status'] === 'approved'): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Approved Date</h6>
                            <p class="lead"><?= date('M d, Y H:i', strtotime($reg['approved_at'])) ?></p>
                        </div>
                    </div>
                <?php elseif ($reg['status'] === 'rejected'): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Rejected Date</h6>
                            <p class="lead"><?= date('M d, Y H:i', strtotime($reg['rejected_at'])) ?></p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Rejection Reason</h6>
                            <p class="lead"><?= htmlspecialchars($reg['rejection_reason'] ?? 'No reason provided') ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($reg['status'] === 'pending'): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>
                    <p class="text-muted mb-3">Choose whether to approve or reject this pre-registration.</p>
                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>admin/process-pre-registration.php?id=<?= (int)$reg['id'] ?>&action=approve" class="btn btn-success btn-lg" onclick="return confirm('Approve this pre-registration? The applicant will be able to login and their school ID will be generated.')">
                            <i class="bi bi-check-lg"></i> Approve Pre-Registration
                        </a>
                        <button class="btn btn-danger btn-lg" onclick="rejectModal()">
                            <i class="bi bi-x-lg"></i> Reject Pre-Registration
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Pre-Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>admin/process-pre-registration.php">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= (int)$reg['id'] ?>">
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Explain why this pre-registration is being rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Pre-Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const rejectModalInstance = new bootstrap.Modal(document.getElementById('rejectModal'));
function rejectModal() {
    rejectModalInstance.show();
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
