<?php
/**
 * Gmail Test Script for JEL Air Conditioning System
 * Tests email functionality and provides setup guide
 */

require_once 'includes/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$testResult = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email'])) {
    $testEmail = $_POST['test_email'] ?? '';
    
    if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Include notification system
            require_once 'includes/notifications.php';
            
            // Create a test booking for email testing
            $testBookingData = [
                'customer_email' => $testEmail,
                'customer_name' => 'Test Customer',
                'service_name' => 'AC Cleaning Test',
                'booking_date' => date('Y-m-d', strtotime('+1 day')),
                'start_time' => '10:00:00',
                'booking_id' => 'TEST-' . time()
            ];
            
            // Send test email
            $notificationSystem = new NotificationSystem($pdo);
            $result = $notificationSystem->sendTestEmail($testEmail, $testBookingData);
            
            if ($result) {
                $testResult = '✅ Test email sent successfully! Check your inbox (and spam folder).';
            } else {
                $error = '❌ Failed to send test email. Check your SMTP configuration.';
            }
            
        } catch (Exception $e) {
            $error = '❌ Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gmail Test - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/enhanced-style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-envelope me-2"></i>Gmail Email Test
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if ($testResult): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo htmlspecialchars($testResult); ?>
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

                        <!-- Current Configuration -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6>Current Email Configuration</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>SMTP Host:</strong> <?php echo SMTP_HOST; ?></p>
                                                <p><strong>SMTP Port:</strong> <?php echo SMTP_PORT; ?></p>
                                                <p><strong>Encryption:</strong> <?php echo SMTP_ENCRYPTION; ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>From Email:</strong> <?php echo EMAIL_FROM; ?></p>
                                                <p><strong>From Name:</strong> <?php echo EMAIL_FROM_NAME; ?></p>
                                                <p><strong>Username:</strong> <?php echo SMTP_USERNAME; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Test Email Form -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="test_email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Test Email Address
                                </label>
                                <input type="email" class="form-control" id="test_email" name="test_email" 
                                       placeholder="Enter email address to test" required>
                                <div class="form-text">Enter your email address to receive a test booking confirmation email.</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Test Email
                            </button>
                        </form>

                        <!-- Gmail Setup Guide -->
                        <div class="mt-5">
                            <h6>Gmail Setup Guide</h6>
                            <div class="card">
                                <div class="card-body">
                                    <h6>Step 1: Enable 2-Factor Authentication</h6>
                                    <ol>
                                        <li>Go to your Google Account settings</li>
                                        <li>Navigate to Security → 2-Step Verification</li>
                                        <li>Enable 2-factor authentication</li>
                                    </ol>
                                    
                                    <h6 class="mt-3">Step 2: Generate App Password</h6>
                                    <ol>
                                        <li>Go to Google Account → Security</li>
                                        <li>Under "2-Step Verification", click "App passwords"</li>
                                        <li>Select "Mail" and "Other (custom name)"</li>
                                        <li>Enter "JEL Air Conditioning System"</li>
                                        <li>Copy the generated 16-character password</li>
                                        <li>Update <code>SMTP_PASSWORD</code> in <code>includes/notification_config.php</code></li>
                                    </ol>
                                    
                                    <h6 class="mt-3">Step 3: Update Configuration</h6>
                                    <p>Make sure these settings are correct in <code>includes/notification_config.php</code>:</p>
                                    <pre class="bg-light p-3 rounded">
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-16-character-app-password');
define('SMTP_ENCRYPTION', 'tls');
                                    </pre>
                                    
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Important:</strong> Never use your regular Gmail password. Always use an App Password for SMTP authentication.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Troubleshooting -->
                        <div class="mt-4">
                            <h6>Troubleshooting</h6>
                            <div class="card">
                                <div class="card-body">
                                    <h6>Common Issues:</h6>
                                    <ul>
                                        <li><strong>Authentication failed:</strong> Check your App Password</li>
                                        <li><strong>Connection timeout:</strong> Check your internet connection</li>
                                        <li><strong>Email not received:</strong> Check spam folder</li>
                                        <li><strong>SMTP error:</strong> Verify SMTP settings</li>
                                    </ul>
                                    
                                    <h6 class="mt-3">Check Logs:</h6>
                                    <p>Check these files for error details:</p>
                                    <ul>
                                        <li><code>logs/security.log</code> - Security events</li>
                                        <li><code>logs/php_errors.log</code> - PHP errors</li>
                                        <li><code>emails/email_log.txt</code> - Email logs</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
