<?php
/**
 * Email Configuration Helper
 * Test and configure SMTP settings
 */

require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die('Access denied. Admin privileges required.');
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $smtp_host = $_POST['smtp_host'];
    $smtp_port = $_POST['smtp_port'];
    $smtp_username = $_POST['smtp_username'];
    $smtp_password = $_POST['smtp_password'];
    $smtp_encryption = $_POST['smtp_encryption'];
    $test_email = $_POST['test_email'];
    
    // Update notification config file
    $config_content = "<?php
// Notification Configuration
define('NOTIFICATION_ENABLED', true);
define('EMAIL_NOTIFICATIONS', true);
define('SMS_NOTIFICATIONS', false); // Disabled by default until SMS gateway is configured

// Email Configuration - SMTP Settings
define('SMTP_HOST', '$smtp_host'); // Change to your SMTP server
define('SMTP_PORT', $smtp_port);
define('SMTP_USERNAME', '$smtp_username'); // Your email
define('SMTP_PASSWORD', '$smtp_password'); // Your app password
define('SMTP_ENCRYPTION', '$smtp_encryption'); // tls or ssl

// Email Settings
define('EMAIL_FROM', 'noreply@jelaircon.com');
define('EMAIL_FROM_NAME', 'JEL Air Conditioning Services');
define('EMAIL_REPLY_TO', 'support@jelaircon.com');

// SMS Configuration (These would be replaced with actual SMS gateway credentials)
define('SMS_API_KEY', 'your_sms_api_key');
define('SMS_API_SECRET', 'your_sms_api_secret');
define('SMS_FROM_NUMBER', 'JELAirCon');

// Notification Settings
define('REMINDER_HOURS_BEFORE', 24); // Send reminder 24 hours before
define('AUTO_REMINDERS_ENABLED', true);
define('MAX_RETRY_ATTEMPTS', 3);

// Notification Templates
\$notification_templates = [
    'booking_confirmation' => [
        'email_subject' => 'Booking Confirmation - JEL Air Conditioning Services',
        'email_template' => 'emails/booking_confirmation.html',
        'sms_template' => 'Your booking for {service} on {date} at {time} is confirmed. Booking ID: {booking_id}'
    ],
    'booking_reminder' => [
        'email_subject' => 'Reminder: Upcoming Service - JEL Air Conditioning',
        'email_template' => 'emails/booking_reminder.html',
        'sms_template' => 'Reminder: {service} tomorrow at {time}. Please be available.'
    ],
    'status_update' => [
        'email_subject' => 'Booking Status Update - JEL Air Conditioning',
        'email_template' => 'emails/status_update.html',
        'sms_template' => 'Your booking status: {service} is now {status}.'
    ],
    'payment_confirmation' => [
        'email_subject' => 'Payment Received - JEL Air Conditioning',
        'email_template' => 'emails/payment_confirmation.html',
        'sms_template' => 'Payment of ₱{amount} received. Thank you!'
    ],
    'technician_assignment' => [
        'email_subject' => 'Technician Assigned - JEL Air Conditioning',
        'email_template' => 'emails/technician_assignment.html',
        'sms_template' => 'Your technician {technician_name} will arrive at {time}.'
    ],
    'booking_cancelled' => [
        'email_subject' => 'Booking Cancelled - JEL Air Conditioning',
        'email_template' => 'emails/booking_cancelled.html',
        'sms_template' => 'Your booking for {service} has been cancelled.'
    ],
    'service_completed' => [
        'email_subject' => 'Service Completed - JEL Air Conditioning',
        'email_template' => 'emails/service_completed.html',
        'sms_template' => 'Your {service} has been completed. Please rate our service.'
    ]
];

// Email Template Directory
define('EMAIL_TEMPLATE_DIR', __DIR__ . '/../templates/');

// Business Information
define('BUSINESS_NAME', 'JEL Air Conditioning Services');
define('BUSINESS_PHONE', '(123) 456-7890');
define('BUSINESS_EMAIL', 'info@jelaircon.com');
define('BUSINESS_ADDRESS', '123 Service Road, City, State 12345');
define('BUSINESS_WEBSITE', 'https://jelaircon.com');
?>";

    if (file_put_contents('../includes/notification_config.php', $config_content)) {
        $message = "✅ Email configuration updated successfully!";
        
        // Test email if provided
        if ($test_email && filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
            try {
                require_once '../includes/notifications.php';
                $notificationSystem = new NotificationSystem($pdo);
                
                $subject = "Test Email from JEL Air Conditioning System";
                $message_content = "
                    <html>
                    <body>
                        <h2>Test Email</h2>
                        <p>This is a test email from your JEL Air Conditioning Services Management System.</p>
                        <p>If you received this email, your SMTP configuration is working correctly!</p>
                        <hr>
                        <p><small>Sent on: " . date('Y-m-d H:i:s') . "</small></p>
                    </body>
                    </html>
                ";
                
                if ($notificationSystem->sendEmail($test_email, $subject, $message_content, true)) {
                    $message .= " Test email sent successfully to $test_email!";
                } else {
                    $error = "❌ Configuration saved but test email failed. Check your SMTP settings.";
                }
            } catch (Exception $e) {
                $error = "❌ Configuration saved but test email failed: " . $e->getMessage();
            }
        }
    } else {
        $error = "❌ Failed to update configuration file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h2">Email Configuration</h1>
                <p class="text-muted">Configure SMTP settings for email notifications</p>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cog me-2"></i>SMTP Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="smtp_host" class="form-label">SMTP Host</label>
                                    <input type="text" class="form-control" id="smtp_host" name="smtp_host" 
                                           value="<?php echo defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com'; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="smtp_port" class="form-label">SMTP Port</label>
                                    <input type="number" class="form-control" id="smtp_port" name="smtp_port" 
                                           value="<?php echo defined('SMTP_PORT') ? SMTP_PORT : '587'; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="smtp_username" class="form-label">SMTP Username (Email)</label>
                                <input type="email" class="form-control" id="smtp_username" name="smtp_username" 
                                       value="<?php echo defined('SMTP_USERNAME') ? SMTP_USERNAME : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="smtp_password" class="form-label">SMTP Password (App Password)</label>
                                <input type="password" class="form-control" id="smtp_password" name="smtp_password" 
                                       value="<?php echo defined('SMTP_PASSWORD') ? SMTP_PASSWORD : ''; ?>" required>
                                <div class="form-text">For Gmail, use App Password (not your regular password)</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="smtp_encryption" class="form-label">Encryption</label>
                                <select class="form-control" id="smtp_encryption" name="smtp_encryption" required>
                                    <option value="tls" <?php echo (defined('SMTP_ENCRYPTION') && SMTP_ENCRYPTION === 'tls') ? 'selected' : ''; ?>>TLS</option>
                                    <option value="ssl" <?php echo (defined('SMTP_ENCRYPTION') && SMTP_ENCRYPTION === 'ssl') ? 'selected' : ''; ?>>SSL</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="test_email" class="form-label">Test Email Address</label>
                                <input type="email" class="form-control" id="test_email" name="test_email" 
                                       placeholder="Enter email to test configuration">
                                <div class="form-text">Optional: Send a test email to verify configuration</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Configuration
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Gmail Setup Guide</h5>
                    </div>
                    <div class="card-body">
                        <ol class="small">
                            <li>Enable 2-Factor Authentication on your Gmail account</li>
                            <li>Go to Google Account Settings</li>
                            <li>Security → 2-Step Verification</li>
                            <li>App Passwords → Generate new password</li>
                            <li>Use the generated password (not your regular password)</li>
                        </ol>
                        
                        <div class="alert alert-info">
                            <strong>Gmail Settings:</strong><br>
                            Host: smtp.gmail.com<br>
                            Port: 587<br>
                            Encryption: TLS
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <a href="../index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
                <a href="test_email.php" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2"></i>Test Email
                </a>
            </div>
        </div>
    </div>
</body>
</html>
