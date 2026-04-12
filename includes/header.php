<?php
if (!isset($pageTitle)) $pageTitle = APP_NAME;
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - <?= htmlspecialchars(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>index.php">
                    <img src="<?= BASE_URL ?>assets/icons/logo.jpg" alt="<?= htmlspecialchars(APP_NAME) ?> logo" class="navbar-logo me-2" style="width:24px; height:auto;">
                    <?= htmlspecialchars(APP_NAME) ?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navMain">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php">Home</a></li>
                        <?php if ($user): ?>
                    <?php if ($user['role'] === 'student'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Student Info</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/dashboard.php">Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/gwa.php">GWA & Grades</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/academic-status.php">Academic Status</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/admission.php">Admission Records</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/disciplinary.php">Disciplinary Records</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/honors.php">Academic Honors</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/fees.php">Fees & Payments</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Learning</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/learning.php">My Learning Materials</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/progress.php">Learning Progress</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/reminders.php">Study Reminders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-success fw-bold" href="<?= BASE_URL ?>student/learning-aids"><i class="bi bi-robot me-2"></i>AI Learning Aids</a></li>
                                <li><a class="dropdown-item text-success fw-bold" href="<?= BASE_URL ?>student/progress/dashboard"><i class="bi bi-graph-up me-2"></i>Progress Analytics</a></li>
                                <li><a class="dropdown-item text-success fw-bold" href="<?= BASE_URL ?>student/job-aids"><i class="bi bi-briefcase me-2"></i>Career Guidance</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Portal</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/messages.php">Messages</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/performance.php">Performance & Reports</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/credentials.php">Credential Request</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>student/self-assessment.php">Self-Assessment</a></li>
                            </ul>
                        </li>
                    <?php elseif ($user['role'] === 'instructor'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Teaching</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-success fw-bold" href="<?= BASE_URL ?>instructor/assessments"><i class="bi bi-file-earmark-text me-2"></i>AI Assessments</a></li>
                                <li><a class="dropdown-item text-success fw-bold" href="<?= BASE_URL ?>instructor/curriculum"><i class="bi bi-book me-2"></i>Curriculum Alignment</a></li>
                            </ul>
                        </li>
                    <?php elseif ($user['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/students.php">Students</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/instructors.php">Instructors</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/courses.php">Courses</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/materials.php">Materials</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/enrollments.php">Enrollments</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/credentials.php">Credentials</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/reports.php">Reports</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/academic-status.php">Academic Status</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/admission.php">Admission Records</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/disciplinary.php">Disciplinary Records</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/academic-load.php">Academic Load</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/progress.php">Learning Progress</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/learning-path.php">Learning Path</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/settings.php">System Defaults</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/transfer-requests.php">Transfer Requests</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/skill-gaps.php">Skill Gaps Report</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>register.php">Register</a></li>
                <?php endif; ?>
            </ul>
            <?php if ($user): ?>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($user['full_name']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text small text-muted"><?= htmlspecialchars(ucfirst($user['role'])) ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>profile.php"><i class="bi bi-person-gear me-2"></i>Account Center</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="container flex-grow-1 py-4">
