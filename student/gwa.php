<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

$stmt = $pdo->prepare('SELECT c.code, c.title, c.units, e.semester, e.school_year, g.midterm_grade, g.final_grade FROM enrollments e JOIN courses c ON e.course_id = c.id LEFT JOIN grades g ON g.enrollment_id = e.id WHERE e.student_id = ? AND e.status IN ("enrolled","completed") ORDER BY e.school_year DESC, e.semester DESC, c.code');
$stmt->execute([$sid]);
$grades = $stmt->fetchAll();

$totalUnits = 0;
$weightedSum = 0;
foreach ($grades as $g) {
    $mid = $g['midterm_grade'] !== null ? (float)$g['midterm_grade'] : 0;
    $fin = $g['final_grade'] !== null ? (float)$g['final_grade'] : 0;
    $avg = ($mid + $fin) / 2;
    $u = (float)$g['units'];
    if ($avg > 0 && $u > 0) {
        $totalUnits += $u;
        $weightedSum += $avg * $u;
    }
}
$gwa = $totalUnits > 0 ? $weightedSum / $totalUnits : null;

$pageTitle = 'GWA & Grades';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4"><i class="bi bi-calculator me-2"></i>GWA & Academic Records</h2>
<div class="card mb-4">
    <div class="card-body">
        <h5>Grade Weighted Average (GWA)</h5>
        <p class="display-6 mb-0"><?= $gwa !== null ? number_format($gwa, 2) : '—' ?></p>
        <?php
        if ($gwa === null) {
            $gwaStatus = 'INC';
        } elseif ($gwa >= 75) {
            $gwaStatus = 'Passed';
        } elseif ($gwa >= 70) {
            $gwaStatus = 'At-Risk';
        } else {
            $gwaStatus = 'Failed';
        }
        ?>
        <p class="mb-1"><strong>Status:</strong> <?= htmlspecialchars($gwaStatus) ?></p>
        <small class="text-muted">Based on enrolled/completed courses with grades.</small>
    </div>
</div>
<div class="card">
    <div class="card-header">Grades by Course</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Course</th><th>Title</th><th>Units</th><th>Semester</th><th>SY</th><th>Midterm</th><th>Final</th><th>Average</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($grades as $g): 
                    $avg = ($g['midterm_grade'] !== null && $g['final_grade'] !== null) ? (($g['midterm_grade'] + $g['final_grade']) / 2) : null;
                    if ($avg === null) {
                        $status = 'INC';
                    } elseif ($avg >= 75) {
                        $status = 'Passed';
                    } elseif ($avg >= 70) {
                        $status = 'At-Risk';
                    } else {
                        $status = 'Failed';
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($g['code']) ?></td>
                    <td><?= htmlspecialchars($g['title']) ?></td>
                    <td><?= htmlspecialchars($g['units']) ?></td>
                    <td><?= htmlspecialchars($g['semester'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($g['school_year'] ?? '—') ?></td>
                    <td><?= $g['midterm_grade'] !== null ? number_format($g['midterm_grade'], 1) : '—' ?></td>
                    <td><?= $g['final_grade'] !== null ? number_format($g['final_grade'], 1) : '—' ?></td>
                    <td><?= $avg !== null ? number_format($avg, 1) : '—' ?></td>
                    <td><?= htmlspecialchars($status) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($grades)): ?>
                <tr><td colspan="9" class="text-center text-muted">No grade records yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
