<?php
/**
 * Email Diagnostic Tool for JEL Air Conditioning System
 * Comprehensive SMTP testing and troubleshooting
 */

require_once 'includes/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$testResults = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_diagnostics'])) {
    $testEmail = $_POST['test_email'] ?? '';
    
    if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Run comprehensive diagnostics
        $testResults = runEmailDiagnostics($testEmail);
    }
}

function runEmailDiagnostics($testEmail) {
    $results = [];
    
    // Test 1: Check SMTP Configuration
    $results['config'] = checkSMTPConfig();
    
    // Test 2: Test SMTP Connection
    $results['connection'] = testSMTPConnection();
    
    // Test 3: Test Simple Email
    $results['simple_email'] = testSimpleEmail($testEmail);
    
    // Test 4: Test HTML Email
    $results['html_email'] = testHTMLEmail($testEmail);
    
    // Test 5: Test Booking Email
    $results['booking_email'] = testBookingEmail($testEmail);
    
    return $results;
}

function checkSMTPConfig() {
    $config = [
        'SMTP_HOST' => defined('SMTP_HOST') ? SMTP_HOST : 'Not defined',
        'SMTP_PORT' => defined('SMTP_PORT') ? SMTP_PORT : 'Not defined',
        'SMTP_USERNAME' => defined('SMTP_USERNAME') ? SMTP_USERNAME : 'Not defined',
        'SMTP_PASSWORD' => defined('SMTP_PASSWORD') ? (strlen(SMTP_PASSWORD) > 0 ? 'Set (' . strlen(SMTP_PASSWORD) . ' chars)' : 'Empty') : 'Not defined',
        'SMTP_ENCRYPTION' => defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'Not defined',
        'EMAIL_FROM' => defined('EMAIL_FROM') ? EMAIL_FROM : 'Not defined',
        'EMAIL_FROM_NAME' => defined('EMAIL_FROM_NAME') ? EMAIL_FROM_NAME : 'Not defined'
    ];
    
    $issues = [];
    if (!defined('SMTP_HOST') || SMTP_HOST === '') $issues[] = 'SMTP_HOST not configured';
    if (!defined('SMTP_PORT') || SMTP_PORT === '') $issues[] = 'SMTP_PORT not configured';
    if (!defined('SMTP_USERNAME') || SMTP_USERNAME === '') $issues[] = 'SMTP_USERNAME not configured';
    if (!defined('SMTP_PASSWORD') || SMTP_PASSWORD === '') $issues[] = 'SMTP_PASSWORD not configured';
    if (!defined('SMTP_ENCRYPTION') || SMTP_ENCRYPTION === '') $issues[] = 'SMTP_ENCRYPTION not configured';
    
    return [
        'status' => empty($issues) ? 'success' : 'error',
        'message' => empty($issues) ? 'All SMTP settings are configured' : 'Issues found: ' . implode(', ', $issues),
        'config' => $config
    ];
}

function testSMTPConnection() {
    try {
        if (!defined('SMTP_HOST') || !defined('SMTP_PORT')) {
            return ['status' => 'error', 'message' => 'SMTP settings not configured'];
        }
        
        $connection = @fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 10);
        
        if (!$connection) {
            return ['status' => 'error', 'message' => "Cannot connect to " . SMTP_HOST . ":" . SMTP_PORT . " - $errstr ($errno)"];
        }
        
        fclose($connection);
        return ['status' => 'success', 'message' => 'Successfully connected to SMTP server'];
        
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Connection test failed: ' . $e->getMessage()];
    }
}

function testSimpleEmail($testEmail) {
    try {
        $subject = 'Simple Test Email - ' . date('Y-m-d H:i:s');
        $message = 'This is a simple test email to verify basic SMTP functionality.';
        
        $headers = "From: " . EMAIL_FROM . "\r\n";
        $headers .= "Reply-To: " . EMAIL_FROM . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        $result = mail($testEmail, $subject, $message, $headers);
        
        return [
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Simple email sent successfully' : 'Simple email failed to send'
        ];
        
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Simple email test failed: ' . $e->getMessage()];
    }
}

function testHTMLEmail($testEmail) {
    try {
        $subject = 'HTML Test Email - ' . date('Y-m-d H:i:s');
        $message = '
        <html>
        <head><title>HTML Test</title></head>
        <body>
            <h2>HTML Email Test</h2>
            <p>This is an HTML test email.</p>
            <p><strong>Time:</strong> ' . date('Y-m-d H:i:s') . '</p>
        </body>
        </html>';
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . EMAIL_FROM . "\r\n";
        $headers .= "Reply-To: " . EMAIL_FROM . "\r\n";
        
        $result = mail($testEmail, $subject, $message, $headers);
        
        return [
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'HTML email sent successfully' : 'HTML email failed to send'
        ];
        
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'HTML email test failed: ' . $e->getMessage()];
    }
}

function testBookingEmail($testEmail) {
    try {
        require_once 'includes/notifications.php';
        
        $testData = [
            'customer_name' => 'Test Customer',
            'service_name' => 'AC Cleaning Test',
            'booking_date' => date('Y-m-d', strtotime('+1 day')),
            'start_time' => '10:00:00',
            'booking_id' => 'TEST-' . time()
        ];
        
        $notificationSystem = new NotificationSystem($GLOBALS['pdo']);
        $result = $notificationSystem->sendTestEmail($testEmail, $testData);
        
        return [
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Booking email sent successfully' : 'Booking email failed to send'
        ];
        
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Booking email test failed: ' . $e->getMessage()];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Diagnostics - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/enhanced-style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-stethoscope me-2"></i>Email Diagnostics & Troubleshooting
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        
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
                                                <p><strong>SMTP Host:</strong> <?php echo defined('SMTP_HOST') ? SMTP_HOST : 'Not defined'; ?></p>
                                                <p><strong>SMTP Port:</strong> <?php echo defined('SMTP_PORT') ? SMTP_PORT : 'Not defined'; ?></p>
                                                <p><strong>Encryption:</strong> <?php echo defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'Not defined'; ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>From Email:</strong> <?php echo defined('EMAIL_FROM') ? EMAIL_FROM : 'Not defined'; ?></p>
                                                <p><strong>From Name:</strong> <?php echo defined('EMAIL_FROM_NAME') ? EMAIL_FROM_NAME : 'Not defined'; ?></p>
                                                <p><strong>Username:</strong> <?php echo defined('SMTP_USERNAME') ? SMTP_USERNAME : 'Not defined'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Diagnostic Form -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="test_email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Test Email Address
                                </label>
                                <input type="email" class="form-control" id="test_email" name="test_email" 
                                       placeholder="Enter email address for comprehensive testing" required>
                                <div class="form-text">This will run multiple email tests to identify the exact issue.</div>
                            </div>
                            
                            <button type="submit" name="run_diagnostics" class="btn btn-primary">
                                <i class="fas fa-play me-2"></i>Run Email Diagnostics
                            </button>
                        </form>

                        <!-- Diagnostic Results -->
                        <?php if (!empty($testResults)): ?>
                            <div class="mt-5">
                                <h6>Diagnostic Results</h6>
                                
                                <?php foreach ($testResults as $testName => $result): ?>
                                    <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <?php
                                                $icons = [
                                                    'config' => 'fas fa-cog',
                                                    'connection' => 'fas fa-network-wired',
                                                    'simple_email' => 'fas fa-envelope',
                                                    'html_email' => 'fas fa-code',
                                                    'booking_email' => 'fas fa-calendar-check'
                                                ];
                                                $names = [
                                                    'config' => 'SMTP Configuration',
                                                    'connection' => 'SMTP Connection',
                                                    'simple_email' => 'Simple Email Test',
                                                    'html_email' => 'HTML Email Test',
                                                    'booking_email' => 'Booking Email Test'
                                                ];
                                                ?>
                                                <i class="<?php echo $icons[$testName] ?? 'fas fa-question'; ?> me-2"></i>
                                                <?php echo $names[$testName] ?? ucfirst($testName); ?>
                                            </h6>
                                            <span class="badge bg-<?php echo $result['status'] === 'success' ? 'success' : 'danger'; ?>">
                                                <?php echo $result['status'] === 'success' ? 'PASS' : 'FAIL'; ?>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-2"><?php echo htmlspecialchars($result['message']); ?></p>
                                            
                                            <?php if ($testName === 'config' && isset($result['config'])): ?>
                                                <div class="mt-3">
                                                    <h6>Configuration Details:</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <?php foreach ($result['config'] as $key => $value): ?>
                                                                <tr>
                                                                    <td><strong><?php echo $key; ?>:</strong></td>
                                                                    <td><?php echo htmlspecialchars($value); ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Troubleshooting Guide -->
                        <div class="mt-5">
                            <h6>Common Solutions</h6>
                            <div class="card">
                                <div class="card-body">
                                    <div class="accordion" id="troubleshootingAccordion">
                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading1">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                                    <i class="fas fa-key me-2"></i>Gmail App Password Issues
                                                </button>
                                            </h2>
                                            <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                                <div class="accordion-body">
                                                    <ol>
                                                        <li><strong>Enable 2-Factor Authentication</strong> on your Gmail account</li>
                                                        <li><strong>Generate App Password:</strong>
                                                            <ul>
                                                                <li>Go to Google Account → Security</li>
                                                                <li>Click "2-Step Verification" → "App passwords"</li>
                                                                <li>Select "Mail" → "Other (custom name)"</li>
                                                                <li>Enter "JEL Air Conditioning System"</li>
                                                                <li>Copy the 16-character password</li>
                                                            </ul>
                                                        </li>
                                                        <li><strong>Update Configuration:</strong> Replace SMTP_PASSWORD with the app password</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading2">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                                    <i class="fas fa-network-wired me-2"></i>Connection Issues
                                                </button>
                                            </h2>
                                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li><strong>Check Firewall:</strong> Ensure port 587 is not blocked</li>
                                                        <li><strong>Check Internet:</strong> Verify internet connection</li>
                                                        <li><strong>Try Different Port:</strong> Test with port 465 (SSL) instead of 587 (TLS)</li>
                                                        <li><strong>Check Hosting:</strong> Some hosting providers block SMTP ports</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading3">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                                    <i class="fas fa-envelope me-2"></i>Email Delivery Issues
                                                </button>
                                            </h2>
                                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li><strong>Check Spam Folder:</strong> Emails might be filtered as spam</li>
                                                        <li><strong>Check Email Address:</strong> Verify the recipient email is correct</li>
                                                        <li><strong>Check Gmail Settings:</strong> Ensure "Less secure app access" is enabled (if using regular password)</li>
                                                        <li><strong>Wait for Delivery:</strong> Gmail can take 1-5 minutes to deliver emails</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading4">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                                    <i class="fas fa-code me-2"></i>Configuration Issues
                                                </button>
                                            </h2>
                                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                                <div class="accordion-body">
                                                    <p><strong>Verify these settings in <code>includes/notification_config.php</code>:</strong></p>
                                                    <pre class="bg-light p-3 rounded">
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-16-character-app-password');
define('SMTP_ENCRYPTION', 'tls');
                                                    </pre>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                            <a href="gmail_test.php" class="btn btn-outline-primary ms-2">
                                <i class="fas fa-envelope me-2"></i>Simple Email Test
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
