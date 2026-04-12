<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('instructor');
$user = currentUser();
$pageTitle = 'Instructor Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4">Instructor Dashboard</h2>
<p class="lead">Welcome, <?= htmlspecialchars($user['full_name']) ?>.</p>
<div class="card">
    <div class="card-body">
        <p class="mb-0 text-muted">Instructor features: manage courses, link learning materials, view student progress, rubrics-based grading, and communicate with students. Expand this area with course and material management pages as needed.</p>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
