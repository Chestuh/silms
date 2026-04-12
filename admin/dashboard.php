<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');
$user = currentUser();
$pdo = getDB();
$stats = [
    'users' => $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'students' => $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn(),
    'courses' => $pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn(),
    'materials' => $pdo->query('SELECT COUNT(*) FROM learning_materials')->fetchColumn(),
    'pending_pre_registrations' => $pdo->query('SELECT COUNT(*) FROM pre_registrations WHERE status = "pending"')->fetchColumn(),
];

$userRoleStats = $pdo->query('SELECT role, COUNT(*) as total FROM users GROUP BY role')->fetchAll(PDO::FETCH_KEY_PAIR);
$studentStatusStats = $pdo->query('SELECT status, COUNT(*) as total FROM students GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
$materialFormatStats = $pdo->query('SELECT format, COUNT(*) as total FROM learning_materials GROUP BY format')->fetchAll(PDO::FETCH_KEY_PAIR);
$enrollmentStatusStats = $pdo->query('SELECT status, COUNT(*) as total FROM enrollments GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
$feeStatusStats = $pdo->query('SELECT status, COUNT(*) as total FROM fees GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);

$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-hero p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap">
        <div>
            <h2>Admin Dashboard</h2>
            <p class="subtitle mb-1">Hi, <?= htmlspecialchars($user['full_name']) ?> — your operations summary is ready.</p>
            <p class="small text-muted mb-0">Last refreshed: <?= date('F j, Y g:i A') ?></p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary">System Performance</span>
            <span class="badge bg-success">User Friendly</span>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-2 col-md-4">
        <div class="kpi-card p-3 users">
            <div class="kpi-label">Users</div>
            <div class="kpi-value"><?= $stats['users'] ?></div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="kpi-card p-3 students">
            <div class="kpi-label">Studes</div>
            <div class="kpi-value"><?= $stats['students'] ?></div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="kpi-card p-3 courses">
            <div class="kpi-label">Courses</div>
            <div class="kpi-value"><?= $stats['courses'] ?></div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="kpi-card p-3 materials">
            <div class="kpi-label">Materials</div>
            <div class="kpi-value"><?= $stats['materials'] ?></div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="kpi-card p-3 pending">
            <div class="kpi-label">Pending Pre-Regs</div>
            <div class="kpi-value"><?= $stats['pending_pre_registrations'] ?></div>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-lg-8">
        <div class="dashboard-panel">
            <div class="panel-header">Progress Summary</div>
            <div class="panel-body">
                <p class="small text-muted">These figures are intended to guide your priority actions and emulates the reference UI.</p>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1"><span class="gauge-label">Enrollment Completion</span><span class="gauge-value">78%</span></div>
                    <div class="progress progress-sm"><div class="progress-bar bg-success" role="progressbar" style="width: 78%"></div></div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1"><span class="gauge-label">Material Availability</span><span class="gauge-value">88%</span></div>
                    <div class="progress progress-sm"><div class="progress-bar bg-primary" role="progressbar" style="width: 88%"></div></div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1"><span class="gauge-label">Pending Approvals</span><span class="gauge-value"><?= $stats['pending_pre_registrations'] ?></span></div>
                    <div class="progress progress-sm"><div class="progress-bar bg-warning" role="progressbar" style="width: <?= min(100, max(10, (int)$stats['pending_pre_registrations'] * 2)) ?>%"></div></div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1"><span class="gauge-label">Fee Collection</span><span class="gauge-value">62%</span></div>
                    <div class="progress progress-sm"><div class="progress-bar bg-danger" role="progressbar" style="width: 62%"></div></div>
                </div>
                <div class="chart-widget mt-3">
                    <div class="widget-title mb-2">Budget & Capacity</div>
                    <canvas id="mainDashboardChart" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="dashboard-panel h-100">
            <div class="panel-header">Risk & Alerts</div>
            <div class="panel-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-2"><span class="badge bg-warning me-2">Attention</span> Pre-registration backlog is above target. <a href="<?= BASE_URL ?>admin/pre-registrations.php?status=pending">Review now</a></li>
                    <li class="list-group-item px-0 py-2"><span class="badge bg-success me-2">Good</span> Enrollment pipeline is stable.</li>
                    <li class="list-group-item px-0 py-2"><span class="badge bg-danger me-2">Critical</span> Fee overdue ratio needs action.</li>
                    <li class="list-group-item px-0 py-2"><span class="badge bg-info me-2">Info</span> New system release due.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-lg-3 col-sm-6">
        <div class="dashboard-panel chart-widget h-100"><div class="panel-header">Student Status</div><div class="panel-body"><canvas id="chartStudents" height="140"></canvas></div></div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="dashboard-panel chart-widget h-100"><div class="panel-header">Material Format</div><div class="panel-body"><canvas id="chartMaterials" height="140"></canvas></div></div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="dashboard-panel chart-widget h-100"><div class="panel-header">Enrollment Status</div><div class="panel-body"><canvas id="chartEnrollment" height="140"></canvas></div></div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="dashboard-panel chart-widget h-100"><div class="panel-header">Fee Status</div><div class="panel-body"><canvas id="chartFees" height="140"></canvas></div></div>
    </div>
</div>

<div class="dashboard-panel mt-3">
    <div class="panel-header">Management Tools</div>
    <div class="panel-body">
        <div class="btn-group" role="group" aria-label="Admin quick actions">
            <a href="<?= BASE_URL ?>admin/pre-registrations.php?status=pending" class="btn btn-outline-primary"><i class="bi bi-person-check me-1"></i> Pre-Registrations</a>
            <a href="<?= BASE_URL ?>admin/students.php" class="btn btn-outline-success"><i class="bi bi-people me-1"></i> Students</a>
            <a href="<?= BASE_URL ?>admin/fees.php" class="btn btn-outline-warning"><i class="bi bi-wallet2 me-1"></i> Fees</a>
        </div>
        <p class="small text-muted mt-3">All insights are aligned for a clean, light workspace that mirrors reference dashboard clarity.</p>
    </div>
</div>

<?php
$extraScripts = '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>\n<script>\n';
$extraScripts .= 'const coreStats = ' . json_encode([
    'users' => $stats['users'],
    'students' => $stats['students'],
    'courses' => $stats['courses'],
    'materials' => $stats['materials'],
    'pendingPreRegistrations' => $stats['pending_pre_registrations']
]) . ';\n';
$extraScripts .= 'const userRoleStats = ' . json_encode($userRoleStats) . ';\n';
$extraScripts .= 'const studentStatusStats = ' . json_encode($studentStatusStats) . ';\n';
$extraScripts .= 'const materialFormatStats = ' . json_encode($materialFormatStats) . ';\n';
$extraScripts .= 'const enrollmentStatusStats = ' . json_encode($enrollmentStatusStats) . ';\n';
$extraScripts .= 'const feeStatusStats = ' . json_encode($feeStatusStats) . ';\n';
$extraScripts .= 'function makeChart(canvasId, type, labels, data, colors, title) {\n';
$extraScripts .= '    const element = document.getElementById(canvasId);\n';
$extraScripts .= '    if (!element) return;\n';
$extraScripts .= '    new Chart(element.getContext("2d"), {\n';
$extraScripts .= '        type: type,\n';
$extraScripts .= '        data: { labels: labels, datasets: [{ label: title, data: data, backgroundColor: colors, borderColor: "#ffffff", borderWidth: 1 }] },\n';
$extraScripts .= '        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: "bottom" }, title: { display: true, text: title } } }\n';
$extraScripts .= '    });\n';
$extraScripts .= '}\n';
$extraScripts .= 'window.addEventListener("DOMContentLoaded", () => {\n';
$extraScripts .= '    makeChart("mainDashboardChart", "bar", ["Users","Students","Courses","Materials","Pending"], [coreStats.users, coreStats.students, coreStats.courses, coreStats.materials, coreStats.pendingPreRegistrations], ["#0d6efd", "#198754", "#fd7e14", "#6610f2", "#dc3545"], "Resource Usage");\n';
$extraScripts .= '    makeChart("chartStudents", "pie", Object.keys(studentStatusStats), Object.values(studentStatusStats), ["#0d6efd", "#6c757d", "#fd7e14", "#198754", "#0dcaf0"], "Student Status");\n';
$extraScripts .= '    makeChart("chartMaterials", "doughnut", Object.keys(materialFormatStats), Object.values(materialFormatStats), ["#198754", "#0d6efd", "#fd7e14", "#6610f2", "#0dcaf0"], "Material Format");\n';
$extraScripts .= '    makeChart("chartEnrollment", "pie", Object.keys(enrollmentStatusStats), Object.values(enrollmentStatusStats), ["#0d6efd", "#198754", "#ffc107", "#dc3545"], "Enrollment Status");\n';
$extraScripts .= '    makeChart("chartFees", "polarArea", Object.keys(feeStatusStats), Object.values(feeStatusStats), ["#ffc107", "#198754", "#dc3545"], "Fee Collection");\n';
$extraScripts .= '});\n';
$extraScripts .= '</script>';
require_once __DIR__ . '/../includes/footer.php';
?>
