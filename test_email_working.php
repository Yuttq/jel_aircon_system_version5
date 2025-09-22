<?php
/**
 * Working Email Test
 * Simple test using the custom SMTP implementation that we know works
 */

require_once 'includes/config.php';
require_once 'includes/notification_config.php';
require_once 'includes/simple_smtp.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Working Email Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        .test-box { border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
        pre { background: #f5f5f5; padding: 10px; }
    </style>
</head>
<body>";

echo "<h1>✅ Working Email Test</h1>";

// Test 1: Configuration Check
echo "<div class='test-box'>";
echo "<h2>1. Configuration Check</h2>";

$configOk = true;
if (!defined('SMTP_HOST') || SMTP_HOST === '') {
    echo "<p class='error'>❌ SMTP_HOST not set</p>";
    $configOk = false;
} else {
    echo "<p class='success'>✅ SMTP_HOST: " . SMTP_HOST . "</p>";
}

if (!defined('SMTP_PORT') || SMTP_PORT === '') {
    echo "<p class='error'>❌ SMTP_PORT not set</p>";
    $configOk = false;
} else {
    echo "<p class='success'>✅ SMTP_PORT: " . SMTP_PORT . "</p>";
}

if (!defined('SMTP_USERNAME') || SMTP_USERNAME === '') {
    echo "<p class='error'>❌ SMTP_USERNAME not set</p>";
    $configOk = false;
} else {
    echo "<p class='success'>✅ SMTP_USERNAME: " . SMTP_USERNAME . "</p>";
}

if (!defined('SMTP_PASSWORD') || SMTP_PASSWORD === '') {
    echo "<p class='error'>❌ SMTP_PASSWORD not set</p>";
    $configOk = false;
} else {
    echo "<p class='success'>✅ SMTP_PASSWORD: Set (" . strlen(SMTP_PASSWORD) . " chars)</p>";
}

if (!defined('SMTP_ENCRYPTION') || SMTP_ENCRYPTION === '') {
    echo "<p class='error'>❌ SMTP_ENCRYPTION not set</p>";
    $configOk = false;
} else {
    echo "<p class='success'>✅ SMTP_ENCRYPTION: " . SMTP_ENCRYPTION . "</p>";
}

if ($configOk) {
    echo "<p class='success'>✅ All SMTP settings are configured correctly</p>";
} else {
    echo "<p class='error'>❌ Some SMTP settings are missing</p>";
}
echo "</div>";

// Test 2: Connection Test
echo "<div class='test-box'>";
echo "<h2>2. SMTP Connection Test</h2>";

try {
    $connection = @fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 10);
    if ($connection) {
        echo "<p class='success'>✅ Successfully connected to " . SMTP_HOST . ":" . SMTP_PORT . "</p>";
        fclose($connection);
    } else {
        echo "<p class='error'>❌ Failed to connect: $errstr ($errno)</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Connection error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 3: Simple Email Test
echo "<div class='test-box'>";
echo "<h2>3. Simple Email Test</h2>";

$testEmail = 'danielbalermo@gmail.com';
$subject = 'Working Email Test - ' . date('Y-m-d H:i:s');
$message = 'This is a test email from your JEL Air Conditioning System using the working SMTP implementation.';

echo "<p class='info'>Sending test email to: $testEmail</p>";
echo "<p class='info'>Subject: $subject</p>";

try {
    $result = sendSMTPEmail($testEmail, $subject, $message, false);
    
    if ($result) {
        echo "<p class='success'>✅ Email sent successfully!</p>";
        echo "<p class='info'>Check your inbox at <strong>danielbalermo@gmail.com</strong></p>";
        echo "<p class='info'>Also check your spam folder</p>";
    } else {
        echo "<p class='error'>❌ Email failed to send</p>";
        echo "<p class='info'>This might be due to Gmail app password issues</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Email error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 4: HTML Email Test
echo "<div class='test-box'>";
echo "<h2>4. HTML Email Test</h2>";

$htmlSubject = 'HTML Email Test - ' . date('Y-m-d H:i:s');
$htmlMessage = '
<html>
<head><title>HTML Test</title></head>
<body>
    <h2>🎉 HTML Email Test</h2>
    <p>This is an <strong>HTML test email</strong> from your JEL Air Conditioning System.</p>
    <p><strong>Time:</strong> ' . date('Y-m-d H:i:s') . '</p>
    <p><strong>System:</strong> JEL Air Conditioning Services</p>
    <hr>
    <p><em>If you received this email, your HTML email system is working!</em></p>
</body>
</html>';

echo "<p class='info'>Sending HTML email to: $testEmail</p>";

try {
    $result = sendSMTPEmail($testEmail, $htmlSubject, $htmlMessage, true);
    
    if ($result) {
        echo "<p class='success'>✅ HTML email sent successfully!</p>";
        echo "<p class='info'>Check your inbox for the formatted HTML email</p>";
    } else {
        echo "<p class='error'>❌ HTML email failed to send</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ HTML email error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 5: Notification System Test
echo "<div class='test-box'>";
echo "<h2>5. Notification System Test</h2>";

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
    
    echo "<p class='info'>Testing notification system with test data...</p>";
    
    $result = $notificationSystem->sendTestEmail($testEmail, $testData);
    
    if ($result) {
        echo "<p class='success'>✅ Notification system email sent successfully!</p>";
        echo "<p class='info'>This tests the complete booking email system</p>";
    } else {
        echo "<p class='error'>❌ Notification system email failed to send</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Notification system error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Summary
echo "<div class='test-box'>";
echo "<h2>📊 Test Summary</h2>";

echo "<h3>What was tested:</h3>";
echo "<ul>";
echo "<li>✅ SMTP Configuration</li>";
echo "<li>✅ SMTP Connection</li>";
echo "<li>✅ Simple Email Sending</li>";
echo "<li>✅ HTML Email Sending</li>";
echo "<li>✅ Notification System</li>";
echo "</ul>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li><strong>Check your email inbox</strong> - Look for test emails</li>";
echo "<li><strong>Check spam folder</strong> - Gmail might filter them</li>";
echo "<li><strong>Wait 1-5 minutes</strong> - Email delivery can be delayed</li>";
echo "<li><strong>If emails work</strong> - Your email system is working!</li>";
echo "<li><strong>If emails don't work</strong> - Check Gmail app password</li>";
echo "</ol>";

echo "<h3>Gmail App Password Check:</h3>";
echo "<p>If emails aren't working, your Gmail app password might be incorrect:</p>";
echo "<ol>";
echo "<li>Go to <a href='https://myaccount.google.com/security' target='_blank'>Google Account Security</a></li>";
echo "<li>Click '2-Step Verification' → 'App passwords'</li>";
echo "<li>Generate a new 16-character app password</li>";
echo "<li>Update <code>includes/notification_config.php</code> with the new password</li>";
echo "</ol>";

echo "</div>";

echo "<p><a href='index.php'>← Back to Dashboard</a> | <a href='test_phpmailer_install.php'>PHPMailer Test</a> | <a href='test_email_comprehensive.php'>Full Test</a></p>";
echo "</body></html>";
?>
