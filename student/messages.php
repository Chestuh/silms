<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$uid = $user['id'];

$stmt = $pdo->prepare('SELECT m.*, u.full_name AS sender_name FROM messages m JOIN users u ON u.id = m.sender_id WHERE m.receiver_id = ? ORDER BY m.created_at DESC LIMIT 50');
$stmt->execute([$uid]);
$inbox = $stmt->fetchAll();

$pageTitle = 'Messages';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-chat-dots me-2"></i>Student &ndash; Instructor Communication</h2>
<div class="card">
    <div class="card-header">Inbox</div>
    <div class="list-group list-group-flush">
        <?php foreach ($inbox as $msg): ?>
        <div class="list-group-item">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1"><?= htmlspecialchars($msg['subject'] ?: '(No subject)') ?></h6>
                <small><?= date('M j, g:i A', strtotime($msg['created_at'])) ?></small>
            </div>
            <p class="mb-1 small">From: <?= htmlspecialchars($msg['sender_name']) ?></p>
            <p class="mb-0"><?= nl2br(htmlspecialchars(substr($msg['body'], 0, 200))) ?><?= strlen($msg['body']) > 200 ? '...' : '' ?></p>
        </div>
        <?php endforeach; ?>
        <?php if (empty($inbox)): ?>
        <div class="list-group-item text-muted">No messages yet.</div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
