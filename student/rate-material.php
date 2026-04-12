<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: learning.php'); exit; }
$pdo = getDB();
$stmt = $pdo->prepare('SELECT id, title FROM learning_materials WHERE id = ?');
$stmt->execute([$id]);
$m = $stmt->fetch();
if (!$m) { header('Location: learning.php'); exit; }
$sid = $user['student_id'];
$saved = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    if ($rating < 1 || $rating > 5) {
        $error = 'Please choose a rating 1 to 5.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO material_ratings (student_id, material_id, rating, comment) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating), comment = VALUES(comment)');
        $stmt->execute([$sid, $id, $rating, $comment]);
        $saved = true;
    }
}
$stmt = $pdo->prepare('SELECT rating, comment FROM material_ratings WHERE student_id = ? AND material_id = ?');
$stmt->execute([$sid, $id]);
$current = $stmt->fetch();
$pageTitle = 'Rate Material';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4">Rate Learning Material</h2>
<p class="text-muted"><?= htmlspecialchars($m['title']) ?></p>
<?php if ($saved): ?>
<div class="alert alert-success">Rating saved.</div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post" class="card p-4" style="max-width: 400px;">
    <div class="mb-3">
        <label class="form-label">Rating (1 to 5)</label>
        <select name="rating" class="form-select" required>
            <option value="1" <?= ($current['rating'] ?? 0) == 1 ? 'selected' : '' ?>>1 star</option>
            <option value="2" <?= ($current['rating'] ?? 0) == 2 ? 'selected' : '' ?>>2 stars</option>
            <option value="3" <?= ($current['rating'] ?? 0) == 3 ? 'selected' : '' ?>>3 stars</option>
            <option value="4" <?= ($current['rating'] ?? 0) == 4 ? 'selected' : '' ?>>4 stars</option>
            <option value="5" <?= ($current['rating'] ?? 0) == 5 ? 'selected' : '' ?>>5 stars</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Comment (optional)</label>
        <textarea name="comment" class="form-control" rows="3"><?= htmlspecialchars($current['comment'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Rating</button>
</form>
<p class="mt-3"><a href="learning.php">Back to Learning Materials</a></p>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
