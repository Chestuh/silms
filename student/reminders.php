<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('student');
$user = currentUser();
$pdo = getDB();
$sid = $user['student_id'];

// fetch upcoming reminders for notifier (limit 5)
$stmt = $pdo->prepare('SELECT id, title, remind_at FROM study_reminders WHERE student_id = ? AND remind_at >= NOW() ORDER BY remind_at LIMIT 5');
$stmt->execute([$sid]);
$upcomingReminders = $stmt->fetchAll();
$upcomingCount = count($upcomingReminders);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title = trim($_POST['title'] ?? '');
    $remind_at = $_POST['remind_at'] ?? '';
    $material_id = !empty($_POST['material_id']) ? (int)$_POST['material_id'] : null;
    if ($title && $remind_at) {
        $stmt = $pdo->prepare('INSERT INTO study_reminders (student_id, material_id, title, remind_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([$sid, $material_id, $title, $remind_at]);
        // set a session flash so notifier can show it immediately after redirect
        $lastId = $pdo->lastInsertId();
        $_SESSION['flash_reminder_id'] = $lastId;
        header('Location: reminders.php?added=1');
        exit;
    }
}
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM study_reminders WHERE id = ? AND student_id = ?');
    $stmt->execute([$_GET['delete'], $sid]);
    header('Location: reminders.php');
    exit;
}

$stmt = $pdo->prepare('SELECT sr.*, lm.title AS material_title FROM study_reminders sr LEFT JOIN learning_materials lm ON sr.material_id = lm.id WHERE sr.student_id = ? ORDER BY sr.remind_at');
$stmt->execute([$sid]);
$reminders = $stmt->fetchAll();
$materials = $pdo->query('SELECT id, title FROM learning_materials WHERE archived = 0 ORDER BY title')->fetchAll();

$pageTitle = 'Study Reminders';
require_once __DIR__ . '/../includes/header.php';
// If a reminder was just added, fetch it and prepend to upcoming list for immediate notifier display
if (!empty($_SESSION['flash_reminder_id'])) {
    $fid = (int)$_SESSION['flash_reminder_id'];
    $fstmt = $pdo->prepare('SELECT id, title, remind_at FROM study_reminders WHERE id = ? AND student_id = ?');
    $fstmt->execute([$fid, $sid]);
    $fresh = $fstmt->fetch();
    if ($fresh) {
        array_unshift($upcomingReminders, $fresh);
        $upcomingCount = count($upcomingReminders);
    }
    unset($_SESSION['flash_reminder_id']);
}
?>
<div class="card mb-4">
    <div class="card-body d-flex align-items-center justify-content-between">
        <div>
            <h2 class="mb-1"><i class="bi bi-alarm me-2"></i>Study Reminder Scheduler</h2>
            <div class="small text-muted">Schedule study reminders for your learning materials</div>
        </div>
        <div class="text-end">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm position-relative" type="button" id="reminderDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell-fill"></i>
                    <?php if (!empty($upcomingCount)): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $upcomingCount ?></span><?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reminderDropdown" style="min-width:320px;">
                    <li class="dropdown-header">Upcoming reminders</li>
                    <?php if (!empty($upcomingReminders)): ?>
                        <?php foreach ($upcomingReminders as $r): ?>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-start" href="#rem-<?= (int)$r['id'] ?>">
                                    <div>
                                        <div><?= htmlspecialchars($r['title']) ?></div>
                                        <div class="small text-muted"><?= date('M j, Y H:i', strtotime($r['remind_at'])) ?></div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="reminders.php">View all reminders</a></li>
                    <?php else: ?>
                        <li><span class="dropdown-item text-muted">No upcoming reminders</span></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($_GET['added'])): ?><div class="alert alert-success">Reminder added.</div><?php endif; ?>
<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">Add reminder</div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="add" value="1">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link to material (optional)</label>
                        <select name="material_id" class="form-select">
                            <option value="">— None —</option>
                            <?php foreach ($materials as $mat): ?>
                            <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remind at</label>
                        <input type="datetime-local" name="remind_at" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Reminder</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Your reminders</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($reminders as $r): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($r['title']) ?></strong>
                        <?php if ($r['material_title']): ?><br><small class="text-muted"><?= htmlspecialchars($r['material_title']) ?></small><?php endif; ?>
                        <br><small><?= date('M j, Y g:i A', strtotime($r['remind_at'])) ?></small>
                    </div>
                    <a href="?delete=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</a>
                </li>
                <?php endforeach; ?>
                <?php if (empty($reminders)): ?>
                <li class="list-group-item text-muted">No reminders. Add one above.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
