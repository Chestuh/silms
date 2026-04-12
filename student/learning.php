<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT lm.*, c.code AS course_code, c.title AS course_title, lp.progress_percent, lp.time_spent_minutes, lp.completed_at FROM learning_materials lm LEFT JOIN courses c ON lm.course_id = c.id LEFT JOIN learning_progress lp ON lp.material_id = lm.id AND lp.student_id = ? WHERE lm.archived = 0 ORDER BY c.code, lm.order_index, lm.id');
$stmt->execute([$sid]);
$materials = $stmt->fetchAll();

$pageTitle = 'Learning Materials';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-journal-book me-2"></i>Learning Materials</h2>
<p class="text-muted">Multi-format support. Track progress and rate materials.</p>
<div class="row g-3">
    <?php foreach ($materials as $m): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($m['format']) ?></span>
                <span class="badge badge-difficulty-<?= $m['difficulty_level'] ?> mb-2"><?= htmlspecialchars($m['difficulty_level']) ?></span>
                <h6 class="card-title"><?= htmlspecialchars($m['title']) ?></h6>
                <p class="small text-muted mb-2"><?= htmlspecialchars($m['course_code'] ?? 'General') ?> — <?= htmlspecialchars($m['course_title'] ?? '') ?></p>
                <?php if ($m['description']): ?><p class="small mb-2"><?= htmlspecialchars(substr($m['description'], 0, 80)) ?>...</p><?php endif; ?>
                <div class="mb-2">
                    <small>Progress</small>
                    <div class="progress"><div class="progress-bar" style="width:<?= (int)($m['progress_percent'] ?? 0) ?>%"><?= (int)($m['progress_percent'] ?? 0) ?>%</div></div>
                </div>
                <small class="text-muted">Time spent: <?= (int)($m['time_spent_minutes'] ?? 0) ?> min</small>
            </div>
            <div class="card-footer bg-transparent">
                <a href="material.php?id=<?= (int)$m['id'] ?>" class="btn btn-sm btn-primary">Open</a>
                <a href="rate-material.php?id=<?= (int)$m['id'] ?>" class="btn btn-sm btn-outline-secondary">Rate</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php if (empty($materials)): ?>
<div class="alert alert-info">No learning materials available yet.</div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
