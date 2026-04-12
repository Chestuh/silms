<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: learning.php'); exit; }
$pdo = getDB();
$stmt = $pdo->prepare('SELECT lm.*, c.code AS course_code FROM learning_materials lm LEFT JOIN courses c ON lm.course_id = c.id WHERE lm.id = ?');
$stmt->execute([$id]);
$m = $stmt->fetch();
if (!$m) { header('Location: learning.php'); exit; }

$pageTitle = $m['title'];
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><?= htmlspecialchars($m['title']) ?></h2>
<div class="card mb-3">
    <div class="card-body">
        <span class="badge bg-secondary"><?= htmlspecialchars($m['format']) ?></span>
        <span class="badge badge-difficulty-<?= $m['difficulty_level'] ?>"><?= htmlspecialchars($m['difficulty_level']) ?></span>
        <?php if ($m['course_code']): ?><span class="text-muted"><?= htmlspecialchars($m['course_code']) ?></span><?php endif; ?>
        <?php if ($m['description']): ?><p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($m['description'])) ?></p><?php endif; ?>
    </div>
</div>
<?php if ($m['url']): ?>
<div class="card">
    <div class="card-body">
        <a href="<?= htmlspecialchars($m['url']) ?>" target="_blank" rel="noopener" class="btn btn-primary">Open external link <i class="bi bi-box-arrow-up-right"></i></a>
    </div>
</div>
<?php elseif ($m['file_path']): ?>
<div class="card">
    <div class="card-body">
        <a href="<?= htmlspecialchars(BASE_URL . 'uploads/' . $m['file_path']) ?>" target="_blank" class="btn btn-primary">Download / View file</a>
    </div>
</div>
<?php else: ?>
<div class="alert alert-secondary">Content view not configured for this material. Link or file can be added by instructor.</div>
<?php endif; ?>
<p class="mt-3"><a href="learning.php" class="btn btn-outline-secondary">Back to Learning Materials</a></p>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
