<?php
/**
 * System Settings Management - JEL Air Conditioning Services
 * Centralized settings configuration
 */

require_once 'includes/config.php';
checkAuth();

// Check if user is admin
if (!hasRole('admin')) {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    } else {
        die('Access denied. Admin privileges required. Your role: ' . ($_SESSION['user_role'] ?? 'Unknown') . '. <a href="index.php">Back to Dashboard</a>');
    }
}

$message = '';
$error = '';

// Handle settings updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_business_info') {
        $business_name = $_POST['business_name'] ?? '';
        $business_phone = $_POST['business_phone'] ?? '';
        $business_email = $_POST['business_email'] ?? '';
        $business_address = $_POST['business_address'] ?? '';
        $business_website = $_POST['business_website'] ?? '';
        
        // Update notification config with business info
        $config_content = file_get_contents('includes/notification_config.php');
        $config_content = preg_replace("/define\('BUSINESS_NAME', '[^']*'\);/", "define('BUSINESS_NAME', '$business_name');", $config_content);
        $config_content = preg_replace("/define\('BUSINESS_PHONE', '[^']*'\);/", "define('BUSINESS_PHONE', '$business_phone');", $config_content);
        $config_content = preg_replace("/define\('BUSINESS_EMAIL', '[^']*'\);/", "define('BUSINESS_EMAIL', '$business_email');", $config_content);
        $config_content = preg_replace("/define\('BUSINESS_ADDRESS', '[^']*'\);/", "define('BUSINESS_ADDRESS', '$business_address');", $config_content);
        $config_content = preg_replace("/define\('BUSINESS_WEBSITE', '[^']*'\);/", "define('BUSINESS_WEBSITE', '$business_website');", $config_content);
        
        if (file_put_contents('includes/notification_config.php', $config_content)) {
            $message = "✅ Business information updated successfully!";
        } else {
            $error = "❌ Failed to update business information.";
        }
    }
    
    if ($action === 'update_notification_settings') {
        $reminder_hours = (int)($_POST['reminder_hours'] ?? 24);
        $auto_reminders = isset($_POST['auto_reminders']) ? 'true' : 'false';
        $email_notifications = isset($_POST['email_notifications']) ? 'true' : 'false';
        $sms_notifications = isset($_POST['sms_notifications']) ? 'true' : 'false';
        
        // Update notification settings
        $config_content = file_get_contents('includes/notification_config.php');
        $config_content = preg_replace("/define\('EMAIL_NOTIFICATIONS', [^)]*\);/", "define('EMAIL_NOTIFICATIONS', $email_notifications);", $config_content);
        $config_content = preg_replace("/define\('SMS_NOTIFICATIONS', [^)]*\);/", "define('SMS_NOTIFICATIONS', $sms_notifications);", $config_content);
        $config_content = preg_replace("/define\('AUTO_REMINDERS_ENABLED', [^)]*\);/", "define('AUTO_REMINDERS_ENABLED', $auto_reminders);", $config_content);
        $config_content = preg_replace("/define\('REMINDER_HOURS_BEFORE', [^)]*\);/", "define('REMINDER_HOURS_BEFORE', $reminder_hours);", $config_content);
        
        if (file_put_contents('includes/notification_config.php', $config_content)) {
            $message = "✅ Notification settings updated successfully!";
        } else {
            $error = "❌ Failed to update notification settings.";
        }
    }
}

// Get current settings
$current_settings = [
    'business_name' => defined('BUSINESS_NAME') ? BUSINESS_NAME : 'JEL Air Conditioning Services',
    'business_phone' => defined('BUSINESS_PHONE') ? BUSINESS_PHONE : '(123) 456-7890',
    'business_email' => defined('BUSINESS_EMAIL') ? BUSINESS_EMAIL : 'info@jelaircon.com',
    'business_address' => defined('BUSINESS_ADDRESS') ? BUSINESS_ADDRESS : '123 Service Road, City, State 12345',
    'business_website' => defined('BUSINESS_WEBSITE') ? BUSINESS_WEBSITE : 'https://jelaircon.com',
    'email_notifications' => defined('EMAIL_NOTIFICATIONS') ? EMAIL_NOTIFICATIONS : true,
    'sms_notifications' => defined('SMS_NOTIFICATIONS') ? SMS_NOTIFICATIONS : false,
    'auto_reminders' => defined('AUTO_REMINDERS_ENABLED') ? AUTO_REMINDERS_ENABLED : true,
    'reminder_hours' => defined('REMINDER_HOURS_BEFORE') ? REMINDER_HOURS_BEFORE : 24,
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - JEL Air Conditioning Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .settings-card {
            border-left: 4px solid #007bff;
            transition: transform 0.2s;
        }
        .settings-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2">System Settings</h1>
                        <p class="text-muted">Configure system-wide settings and preferences</p>
                    </div>
                    <div>
                        <a href="admin_panel.php" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Admin Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Business Information -->
            <div class="col-lg-6 mb-4">
                <div class="card settings-card">
                    <div class="card-header">
                        <h5><i class="fas fa-building me-2"></i>Business Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_business_info">
                            
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" 
                                       value="<?php echo htmlspecialchars($current_settings['business_name']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="business_phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="business_phone" name="business_phone" 
                                       value="<?php echo htmlspecialchars($current_settings['business_phone']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="business_email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="business_email" name="business_email" 
                                       value="<?php echo htmlspecialchars($current_settings['business_email']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="business_address" class="form-label">Address</label>
                                <textarea class="form-control" id="business_address" name="business_address" rows="3" required><?php echo htmlspecialchars($current_settings['business_address']); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="business_website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="business_website" name="business_website" 
                                       value="<?php echo htmlspecialchars($current_settings['business_website']); ?>">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Business Info
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="col-lg-6 mb-4">
                <div class="card settings-card">
                    <div class="card-header">
                        <h5><i class="fas fa-bell me-2"></i>Notification Settings</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_notification_settings">
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" 
                                           <?php echo $current_settings['email_notifications'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="email_notifications">
                                        Enable Email Notifications
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" 
                                           <?php echo $current_settings['sms_notifications'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="sms_notifications">
                                        Enable SMS Notifications (Not implemented yet)
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_reminders" name="auto_reminders" 
                                           <?php echo $current_settings['auto_reminders'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="auto_reminders">
                                        Enable Automatic Reminders
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reminder_hours" class="form-label">Reminder Hours Before Service</label>
                                <input type="number" class="form-control" id="reminder_hours" name="reminder_hours" 
                                       value="<?php echo $current_settings['reminder_hours']; ?>" min="1" max="168">
                                <div class="form-text">How many hours before the service to send reminders (1-168 hours)</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Notification Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Configuration -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card settings-card">
                    <div class="card-header">
                        <h5><i class="fas fa-cog me-2"></i>System Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="admin/email_config.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-envelope me-2"></i>Email Configuration
                                <span class="badge bg-primary float-end">SMTP</span>
                            </a>
                            <a href="admin/data_migration.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-database me-2"></i>Data Migration
                                <span class="badge bg-info float-end">Import</span>
                            </a>
                            <a href="admin/view_database.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-eye me-2"></i>Database Viewer
                                <span class="badge bg-success float-end">View</span>
                            </a>
                            <a href="system_test.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-check-circle me-2"></i>System Test
                                <span class="badge bg-warning float-end">Test</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card settings-card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle me-2"></i>System Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>PHP Version:</strong></td>
                                <td><?php echo PHP_VERSION; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Database:</strong></td>
                                <td>MySQL/MariaDB</td>
                            </tr>
                            <tr>
                                <td><strong>Base URL:</strong></td>
                                <td><?php echo BASE_URL; ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP Host:</strong></td>
                                <td><?php echo defined('SMTP_HOST') ? SMTP_HOST : 'Not configured'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email From:</strong></td>
                                <td><?php echo defined('EMAIL_FROM') ? EMAIL_FROM : 'Not configured'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>System Status:</strong></td>
                                <td><span class="badge bg-success">Online</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
