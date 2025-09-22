<?php
/**
 * Admin - Scheduled Tasks
 * - Run reminders on-demand
 * - Show last run output
 * - Show recent email logs
 */

require_once '../includes/config.php';

// Require admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die('Access denied. Admin privileges required.');
}

$runOutput = '';
$runTime = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_now'])) {
    // Capture output of the scheduled task script
    $runTime = date('Y-m-d H:i:s');
    try {
        ob_start();
        // Ensure script exists
        $taskFile = realpath(__DIR__ . '/../scheduled_tasks.php');
        if ($taskFile && file_exists($taskFile)) {
            include $taskFile;
        } else {
            echo "[" . date('Y-m-d H:i:s') . "] scheduled_tasks.php not found";
        }
        $runOutput = ob_get_clean();
    } catch (Throwable $t) {
        $error = 'Run failed: ' . $t->getMessage();
        if (ob_get_length()) { ob_end_clean(); }
    }
}

function readTail($filePath, $maxLines = 100) {
    if (!file_exists($filePath)) return '';
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) return '';
    $total = count($lines);
    $start = max(0, $total - $maxLines);
    return implode("\n", array_slice($lines, $start));
}

$emailLogPath = realpath(__DIR__ . '/../emails/email_log.txt') ?: (__DIR__ . '/../emails/email_log.txt');
$recentEmailLog = readTail($emailLogPath, 80);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Tasks - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Scheduled Tasks</h1>
        <div>
            <a href="../index.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Run Reminders</strong>
            <form method="post" class="m-0">
                <button type="submit" name="run_now" class="btn btn-primary">Run Reminders Now</button>
            </form>
        </div>
        <div class="card-body">
            <p class="text-muted mb-2">Runs the same logic as scheduled_tasks.php (send due reminders, cleanup).</p>
            <?php if ($runTime): ?>
                <div class="mb-2"><strong>Last Run:</strong> <?= htmlspecialchars($runTime) ?></div>
            <?php endif; ?>
            <?php if ($runOutput !== ''): ?>
                <label class="form-label">Last Run Output</label>
                <pre class="bg-light p-3" style="white-space:pre-wrap; max-height: 320px; overflow:auto;"><?= htmlspecialchars($runOutput) ?></pre>
            <?php else: ?>
                <div class="text-muted">No run output yet. Click "Run Reminders Now".</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Recent Email Log</strong></div>
        <div class="card-body">
            <div class="mb-2 small text-muted">Source: emails/email_log.txt</div>
            <?php if ($recentEmailLog !== ''): ?>
                <pre class="bg-light p-3" style="white-space:pre-wrap; max-height: 360px; overflow:auto;"><?= htmlspecialchars($recentEmailLog) ?></pre>
            <?php else: ?>
                <div class="text-muted">No recent email log entries.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php /* EOF */ ?>


