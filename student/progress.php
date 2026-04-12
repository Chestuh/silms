<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT lp.*, lm.title AS material_title, lm.format, lm.difficulty_level, c.code AS course_code FROM learning_progress lp JOIN learning_materials lm ON lp.material_id = lm.id LEFT JOIN courses c ON lm.course_id = c.id WHERE lp.student_id = ? ORDER BY lp.completed_at DESC, lp.progress_percent DESC');
$stmt->execute([$sid]);
$progress = $stmt->fetchAll();

$totalTime = 0;
$completed = 0;
foreach ($progress as $p) {
    $totalTime += (int)($p['time_spent_minutes'] ?? 0);
    if ((int)($p['progress_percent'] ?? 0) >= 100) $completed++;
}

$pageTitle = 'Learning Progress';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-graph-up me-2"></i>Learning Progress</h2>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Materials started</h6>
                <h4 class="mb-0"><?= count($progress) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted">Completed</h6>
                <h4 class="mb-0"><?= $completed ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total time spent</h6>
                <h4 class="mb-0"><?= $totalTime ?> min</h4>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Track time spent per activity</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Material</th><th>Course</th><th>Format</th><th>Difficulty</th><th>Progress</th><th>Time (min)</th><th>Completed</th></tr></thead>
            <tbody>
                <?php foreach ($progress as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['material_title']) ?></td>
                    <td><?= htmlspecialchars($p['course_code'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($p['format']) ?></td>
                    <td><span class="badge badge-difficulty-<?= $p['difficulty_level'] ?>"><?= htmlspecialchars($p['difficulty_level']) ?></span></td>
                    <td><div class="progress" style="width:80px"><div class="progress-bar" style="width:<?= (int)$p['progress_percent'] ?>%"><?= (int)$p['progress_percent'] ?>%</div></div></td>
                    <td><?= (int)$p['time_spent_minutes'] ?></td>
                    <td><?= $p['completed_at'] ? date('M j, Y', strtotime($p['completed_at'])) : '—' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($progress)): ?>
                <tr><td colspan="7" class="text-center text-muted">No progress recorded. Start from Learning Materials.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
