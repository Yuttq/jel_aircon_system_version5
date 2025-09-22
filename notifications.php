<?php
/**
 * Notification Management - JEL Air Conditioning Services
 * Manage email notifications and settings
 */

require_once 'includes/config.php';
checkAuth();

$message = '';
$error = '';

// Handle notification updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'test_email') {
        $testEmail = $_POST['test_email'] ?? '';
        
        if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            try {
                require_once 'includes/notifications.php';
                $notificationSystem = new NotificationSystem($pdo);
                
                $testData = [
                    'customer_name' => 'Test Customer',
                    'service_name' => 'AC Cleaning Test',
                    'booking_date' => date('Y-m-d', strtotime('+1 day')),
                    'start_time' => '10:00:00',
                    'booking_id' => 'TEST-' . time()
                ];
                
                if ($notificationSystem->sendTestEmail($testEmail, $testData)) {
                    $message = "✅ Test email sent successfully to {$testEmail}! Check your inbox (and spam folder).";
                } else {
                    $error = "❌ Failed to send test email. Check your SMTP configuration.";
                }
            } catch (Exception $e) {
                $error = "❌ Error: " . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update_email_settings') {
        $smtp_host = $_POST['smtp_host'] ?? '';
        $smtp_port = (int)($_POST['smtp_port'] ?? 587);
        $smtp_username = $_POST['smtp_username'] ?? '';
        $smtp_password = $_POST['smtp_password'] ?? '';
        $smtp_encryption = $_POST['smtp_encryption'] ?? 'tls';
        $email_from = $_POST['email_from'] ?? '';
        $email_from_name = $_POST['email_from_name'] ?? '';
        
        // Update notification config
        $config_content = file_get_contents('includes/notification_config.php');
        $config_content = preg_replace("/define\('SMTP_HOST', '[^']*'\);/", "define('SMTP_HOST', '$smtp_host');", $config_content);
        $config_content = preg_replace("/define\('SMTP_PORT', [^']*\);/", "define('SMTP_PORT', $smtp_port);", $config_content);
        $config_content = preg_replace("/define\('SMTP_USERNAME', '[^']*'\);/", "define('SMTP_USERNAME', '$smtp_username');", $config_content);
        $config_content = preg_replace("/define\('SMTP_PASSWORD', '[^']*'\);/", "define('SMTP_PASSWORD', '$smtp_password');", $config_content);
        $config_content = preg_replace("/define\('SMTP_ENCRYPTION', '[^']*'\);/", "define('SMTP_ENCRYPTION', '$smtp_encryption');", $config_content);
        $config_content = preg_replace("/define\('EMAIL_FROM', '[^']*'\);/", "define('EMAIL_FROM', '$email_from');", $config_content);
        $config_content = preg_replace("/define\('EMAIL_FROM_NAME', '[^']*'\);/", "define('EMAIL_FROM_NAME', '$email_from_name');", $config_content);
        
        if (file_put_contents('includes/notification_config.php', $config_content)) {
            $message = "✅ Email settings updated successfully!";
        } else {
            $error = "❌ Failed to update email settings.";
        }
    }
}

// Get current settings
$current_settings = [
    'SMTP_HOST' => defined('SMTP_HOST') ? SMTP_HOST : '',
    'SMTP_PORT' => defined('SMTP_PORT') ? SMTP_PORT : 587,
    'SMTP_USERNAME' => defined('SMTP_USERNAME') ? SMTP_USERNAME : '',
    'SMTP_PASSWORD' => defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '',
    'SMTP_ENCRYPTION' => defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'tls',
    'EMAIL_FROM' => defined('EMAIL_FROM') ? EMAIL_FROM : '',
    'EMAIL_FROM_NAME' => defined('EMAIL_FROM_NAME') ? EMAIL_FROM_NAME : '',
    'NOTIFICATION_ENABLED' => defined('NOTIFICATION_ENABLED') ? NOTIFICATION_ENABLED : false,
    'EMAIL_NOTIFICATIONS' => defined('EMAIL_NOTIFICATIONS') ? EMAIL_NOTIFICATIONS : false
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Management - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/enhanced-style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-bell me-2 text-primary"></i>Notification Management</h2>
                    <div>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        <a href="email_diagnostics.php" class="btn btn-outline-primary">
                            <i class="fas fa-stethoscope me-2"></i>Email Diagnostics
                        </a>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Email Settings -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-gradient-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-envelope me-2"></i>Email Configuration
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="update_email_settings">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="smtp_host" class="form-label">SMTP Host</label>
                                            <input type="text" class="form-control" id="smtp_host" name="smtp_host" 
                                                   value="<?php echo htmlspecialchars($current_settings['SMTP_HOST']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="smtp_port" class="form-label">SMTP Port</label>
                                            <select class="form-select" id="smtp_port" name="smtp_port">
                                                <option value="587" <?php echo $current_settings['SMTP_PORT'] == 587 ? 'selected' : ''; ?>>587 (TLS)</option>
                                                <option value="465" <?php echo $current_settings['SMTP_PORT'] == 465 ? 'selected' : ''; ?>>465 (SSL)</option>
                                                <option value="25" <?php echo $current_settings['SMTP_PORT'] == 25 ? 'selected' : ''; ?>>25 (No encryption)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="smtp_username" class="form-label">SMTP Username</label>
                                            <input type="email" class="form-control" id="smtp_username" name="smtp_username" 
                                                   value="<?php echo htmlspecialchars($current_settings['SMTP_USERNAME']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="smtp_password" class="form-label">SMTP Password</label>
                                            <input type="password" class="form-control" id="smtp_password" name="smtp_password" 
                                                   value="<?php echo htmlspecialchars($current_settings['SMTP_PASSWORD']); ?>" required>
                                            <div class="form-text">Use Gmail App Password for Gmail accounts</div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="smtp_encryption" class="form-label">Encryption</label>
                                            <select class="form-select" id="smtp_encryption" name="smtp_encryption">
                                                <option value="tls" <?php echo $current_settings['SMTP_ENCRYPTION'] == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                                <option value="ssl" <?php echo $current_settings['SMTP_ENCRYPTION'] == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                                <option value="" <?php echo empty($current_settings['SMTP_ENCRYPTION']) ? 'selected' : ''; ?>>None</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email_from" class="form-label">From Email</label>
                                            <input type="email" class="form-control" id="email_from" name="email_from" 
                                                   value="<?php echo htmlspecialchars($current_settings['EMAIL_FROM']); ?>" required>
                                            <div class="form-text">Must match SMTP Username for Gmail</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email_from_name" class="form-label">From Name</label>
                                        <input type="text" class="form-control" id="email_from_name" name="email_from_name" 
                                               value="<?php echo htmlspecialchars($current_settings['EMAIL_FROM_NAME']); ?>" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Email Settings
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-gradient-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-bolt me-2"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Test Email -->
                                <form method="POST" class="mb-3">
                                    <input type="hidden" name="action" value="test_email">
                                    <div class="mb-3">
                                        <label for="test_email" class="form-label">Test Email Address</label>
                                        <input type="email" class="form-control" id="test_email" name="test_email" 
                                               placeholder="Enter email to test" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Send Test Email
                                    </button>
                                </form>

                                <hr>

                                <!-- Quick Links -->
                                <div class="d-grid gap-2">
                                    <a href="email_diagnostics.php" class="btn btn-outline-primary">
                                        <i class="fas fa-stethoscope me-2"></i>Email Diagnostics
                                    </a>
                                    <a href="fixed_email_test.php" class="btn btn-outline-info">
                                        <i class="fas fa-tools me-2"></i>Fixed Email Test
                                    </a>
                                    <a href="quick_gmail_test.php" class="btn btn-outline-warning">
                                        <i class="fas fa-envelope me-2"></i>Quick Gmail Test
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-gradient-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Current Status
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Notifications:</strong> 
                                    <span class="badge bg-<?php echo $current_settings['NOTIFICATION_ENABLED'] ? 'success' : 'danger'; ?>">
                                        <?php echo $current_settings['NOTIFICATION_ENABLED'] ? 'Enabled' : 'Disabled'; ?>
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <strong>Email Notifications:</strong> 
                                    <span class="badge bg-<?php echo $current_settings['EMAIL_NOTIFICATIONS'] ? 'success' : 'danger'; ?>">
                                        <?php echo $current_settings['EMAIL_NOTIFICATIONS'] ? 'Enabled' : 'Disabled'; ?>
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <strong>SMTP Host:</strong> <?php echo htmlspecialchars($current_settings['SMTP_HOST']); ?>
                                </div>
                                <div class="mb-2">
                                    <strong>SMTP Port:</strong> <?php echo $current_settings['SMTP_PORT']; ?>
                                </div>
                                <div class="mb-2">
                                    <strong>Encryption:</strong> <?php echo strtoupper($current_settings['SMTP_ENCRYPTION']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
