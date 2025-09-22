<?php
/**
 * Comprehensive Email Test
 * Test all email functionality with detailed debugging
 */

require_once 'includes/config.php';
require_once 'includes/notification_config.php';
require_once 'includes/simple_smtp.php';
require_once 'includes/phpmailer_smtp.php';

$testResults = [];
$testEmail = 'danielbalermo@gmail.com'; // Your email for testing

echo "<!DOCTYPE html>
<html>
<head>
    <title>Email Test Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>";

echo "<h1>🔧 JEL Air Conditioning - Email System Test</h1>";

// Test 1: Configuration Check
echo "<div class='test-section'>";
echo "<h2>1. Configuration Check</h2>";

$configIssues = [];
if (!defined('SMTP_HOST') || SMTP_HOST === '') $configIssues[] = 'SMTP_HOST not set';
if (!defined('SMTP_PORT') || SMTP_PORT === '') $configIssues[] = 'SMTP_PORT not set';
if (!defined('SMTP_USERNAME') || SMTP_USERNAME === '') $configIssues[] = 'SMTP_USERNAME not set';
if (!defined('SMTP_PASSWORD') || SMTP_PASSWORD === '') $configIssues[] = 'SMTP_PASSWORD not set';
if (!defined('SMTP_ENCRYPTION') || SMTP_ENCRYPTION === '') $configIssues[] = 'SMTP_ENCRYPTION not set';

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Setting</th><th>Value</th><th>Status</th></tr>";
echo "<tr><td>SMTP_HOST</td><td>" . (defined('SMTP_HOST') ? SMTP_HOST : 'NOT SET') . "</td><td>" . (defined('SMTP_HOST') && SMTP_HOST !== '' ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td></tr>";
echo "<tr><td>SMTP_PORT</td><td>" . (defined('SMTP_PORT') ? SMTP_PORT : 'NOT SET') . "</td><td>" . (defined('SMTP_PORT') && SMTP_PORT !== '' ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td></tr>";
echo "<tr><td>SMTP_USERNAME</td><td>" . (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'NOT SET') . "</td><td>" . (defined('SMTP_USERNAME') && SMTP_USERNAME !== '' ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td></tr>";
echo "<tr><td>SMTP_PASSWORD</td><td>" . (defined('SMTP_PASSWORD') ? 'SET (' . strlen(SMTP_PASSWORD) . ' chars)' : 'NOT SET') . "</td><td>" . (defined('SMTP_PASSWORD') && SMTP_PASSWORD !== '' ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td></tr>";
echo "<tr><td>SMTP_ENCRYPTION</td><td>" . (defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'NOT SET') . "</td><td>" . (defined('SMTP_ENCRYPTION') && SMTP_ENCRYPTION !== '' ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td></tr>";
echo "<tr><td>EMAIL_FROM</td><td>" . (defined('EMAIL_FROM') ? EMAIL_FROM : 'NOT SET') . "</td><td>" . (defined('EMAIL_FROM') && EMAIL_FROM !== '' ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td></tr>";
echo "</table>";

if (empty($configIssues)) {
    echo "<p class='success'>✅ All SMTP settings are configured correctly</p>";
} else {
    echo "<p class='error'>❌ Configuration issues: " . implode(', ', $configIssues) . "</p>";
}
echo "</div>";

// Test 2: SMTP Connection Test
echo "<div class='test-section'>";
echo "<h2>2. SMTP Connection Test</h2>";

try {
    $connection = @fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 10);
    if ($connection) {
        echo "<p class='success'>✅ Successfully connected to " . SMTP_HOST . ":" . SMTP_PORT . "</p>";
        fclose($connection);
        $testResults['connection'] = true;
    } else {
        echo "<p class='error'>❌ Failed to connect to " . SMTP_HOST . ":" . SMTP_PORT . " - $errstr ($errno)</p>";
        $testResults['connection'] = false;
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Connection error: " . $e->getMessage() . "</p>";
    $testResults['connection'] = false;
}
echo "</div>";

// Test 3: PHPMailer Availability
echo "<div class='test-section'>";
echo "<h2>3. PHPMailer Availability Check</h2>";

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "<p class='success'>✅ PHPMailer is available</p>";
    $testResults['phpmailer'] = true;
} else {
    echo "<p class='warning'>⚠️ PHPMailer not installed. Install with: composer install</p>";
    echo "<p class='info'>ℹ️ Will use custom SMTP implementation as fallback</p>";
    $testResults['phpmailer'] = false;
}
echo "</div>";

// Test 4: Custom SMTP Test
echo "<div class='test-section'>";
echo "<h2>4. Custom SMTP Test</h2>";

$subject = 'Custom SMTP Test - ' . date('Y-m-d H:i:s');
$message = 'This is a test email using the custom SMTP implementation.';

try {
    $result = sendSMTPEmail($testEmail, $subject, $message, false);
    if ($result) {
        echo "<p class='success'>✅ Custom SMTP email sent successfully!</p>";
        $testResults['custom_smtp'] = true;
    } else {
        echo "<p class='error'>❌ Custom SMTP email failed to send</p>";
        $testResults['custom_smtp'] = false;
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Custom SMTP error: " . $e->getMessage() . "</p>";
    $testResults['custom_smtp'] = false;
}
echo "</div>";

// Test 5: PHPMailer Test (if available)
if ($testResults['phpmailer']) {
    echo "<div class='test-section'>";
    echo "<h2>5. PHPMailer Test</h2>";
    
    $subject = 'PHPMailer Test - ' . date('Y-m-d H:i:s');
    $message = 'This is a test email using PHPMailer.';
    
    try {
        $result = sendPHPMailerEmail($testEmail, $subject, $message, false);
        if ($result) {
            echo "<p class='success'>✅ PHPMailer email sent successfully!</p>";
            $testResults['phpmailer_test'] = true;
        } else {
            echo "<p class='error'>❌ PHPMailer email failed to send</p>";
            $testResults['phpmailer_test'] = false;
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ PHPMailer error: " . $e->getMessage() . "</p>";
        $testResults['phpmailer_test'] = false;
    }
    echo "</div>";
}

// Test 6: Enhanced SMTP Test
echo "<div class='test-section'>";
echo "<h2>6. Enhanced SMTP Test (with fallback)</h2>";

$subject = 'Enhanced SMTP Test - ' . date('Y-m-d H:i:s');
$message = 'This is a test email using the enhanced SMTP function with automatic fallback.';

try {
    $result = sendEnhancedSMTPEmail($testEmail, $subject, $message, false);
    if ($result) {
        echo "<p class='success'>✅ Enhanced SMTP email sent successfully!</p>";
        $testResults['enhanced_smtp'] = true;
    } else {
        echo "<p class='error'>❌ Enhanced SMTP email failed to send</p>";
        $testResults['enhanced_smtp'] = false;
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Enhanced SMTP error: " . $e->getMessage() . "</p>";
    $testResults['enhanced_smtp'] = false;
}
echo "</div>";

// Test 7: Notification System Test
echo "<div class='test-section'>";
echo "<h2>7. Notification System Test</h2>";

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
    
    $result = $notificationSystem->sendTestEmail($testEmail, $testData);
    if ($result) {
        echo "<p class='success'>✅ Notification system email sent successfully!</p>";
        $testResults['notification_system'] = true;
    } else {
        echo "<p class='error'>❌ Notification system email failed to send</p>";
        $testResults['notification_system'] = false;
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Notification system error: " . $e->getMessage() . "</p>";
    $testResults['notification_system'] = false;
}
echo "</div>";

// Summary
echo "<div class='test-section'>";
echo "<h2>📊 Test Summary</h2>";

$passedTests = 0;
$totalTests = count($testResults);

foreach ($testResults as $test => $result) {
    $status = $result ? '<span class="success">PASS</span>' : '<span class="error">FAIL</span>';
    echo "<p><strong>" . ucfirst(str_replace('_', ' ', $test)) . ":</strong> $status</p>";
    if ($result) $passedTests++;
}

echo "<h3>Overall Result: $passedTests/$totalTests tests passed</h3>";

if ($passedTests == $totalTests) {
    echo "<p class='success'>🎉 All email tests passed! Your email system is working correctly.</p>";
} else {
    echo "<p class='error'>⚠️ Some tests failed. Check the individual test results above for details.</p>";
}
echo "</div>";

// Recommendations
echo "<div class='test-section'>";
echo "<h2>💡 Recommendations</h2>";

if (!$testResults['phpmailer']) {
    echo "<p class='warning'>• Install PHPMailer for more reliable email sending: <code>composer install</code></p>";
}

if (!$testResults['custom_smtp'] && !$testResults['phpmailer_test']) {
    echo "<p class='error'>• Check your Gmail app password - it might be incorrect or expired</p>";
    echo "<p class='info'>• Verify that 2-factor authentication is enabled on your Gmail account</p>";
    echo "<p class='info'>• Generate a new app password from Google Account settings</p>";
}

if ($testResults['connection'] && !$testResults['custom_smtp'] && !$testResults['phpmailer_test']) {
    echo "<p class='warning'>• SMTP connection works but authentication fails - check credentials</p>";
}

echo "<p class='info'>• Check your email inbox (including spam folder) for test emails</p>";
echo "<p class='info'>• Test emails may take 1-5 minutes to arrive</p>";
echo "</div>";

echo "<p><a href='index.php'>← Back to Dashboard</a></p>";
echo "</body></html>";
?>
