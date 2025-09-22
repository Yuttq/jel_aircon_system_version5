<?php
/**
 * Simple Email Test - No PHPMailer Required
 * Tests the current system and provides fixes
 */

require_once 'includes/config.php';
require_once 'includes/notification_config.php';
require_once 'includes/simple_smtp.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Simple Email Test</title>
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

echo "<h1>🔧 Simple Email Test (No PHPMailer Required)</h1>";

// Test 1: Configuration
echo "<div class='test-box'>";
echo "<h2>1. Current Configuration</h2>";
echo "<p><strong>SMTP Host:</strong> " . (defined('SMTP_HOST') ? SMTP_HOST : 'NOT SET') . "</p>";
echo "<p><strong>SMTP Port:</strong> " . (defined('SMTP_PORT') ? SMTP_PORT : 'NOT SET') . "</p>";
echo "<p><strong>SMTP Username:</strong> " . (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'NOT SET') . "</p>";
echo "<p><strong>SMTP Password:</strong> " . (defined('SMTP_PASSWORD') ? 'SET (' . strlen(SMTP_PASSWORD) . ' chars)' : 'NOT SET') . "</p>";
echo "<p><strong>SMTP Encryption:</strong> " . (defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'NOT SET') . "</p>";
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

// Test 3: Email Test
echo "<div class='test-box'>";
echo "<h2>3. Email Sending Test</h2>";

$testEmail = 'danielbalermo@gmail.com';
$subject = 'Test Email - ' . date('Y-m-d H:i:s');
$message = 'This is a test email from JEL Air Conditioning System.';

echo "<p class='info'>Sending test email to: $testEmail</p>";

try {
    $result = sendSMTPEmail($testEmail, $subject, $message, false);
    if ($result) {
        echo "<p class='success'>✅ Email sent successfully!</p>";
        echo "<p class='info'>Check your inbox (and spam folder) for the test email.</p>";
    } else {
        echo "<p class='error'>❌ Email failed to send</p>";
        echo "<p class='info'>This might be due to Gmail app password issues.</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Email error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 4: Gmail App Password Check
echo "<div class='test-box'>";
echo "<h2>4. Gmail App Password Check</h2>";
echo "<p class='info'>Your current app password: " . (defined('SMTP_PASSWORD') ? SMTP_PASSWORD : 'NOT SET') . "</p>";

if (defined('SMTP_PASSWORD') && strlen(SMTP_PASSWORD) == 16) {
    echo "<p class='success'>✅ App password format looks correct (16 characters)</p>";
} else {
    echo "<p class='error'>❌ App password might be incorrect</p>";
    echo "<p class='info'>Gmail app passwords should be exactly 16 characters long.</p>";
}

echo "<h3>How to get a new Gmail App Password:</h3>";
echo "<ol>";
echo "<li>Go to <a href='https://myaccount.google.com/security' target='_blank'>Google Account Security</a></li>";
echo "<li>Click '2-Step Verification' (must be enabled)</li>";
echo "<li>Scroll down to 'App passwords'</li>";
echo "<li>Select 'Mail' and 'Other (custom name)'</li>";
echo "<li>Enter 'JEL Air Conditioning System'</li>";
echo "<li>Copy the 16-character password</li>";
echo "<li>Update the password in <code>includes/notification_config.php</code></li>";
echo "</ol>";
echo "</div>";

// Test 5: Alternative SMTP Settings
echo "<div class='test-box'>";
echo "<h2>5. Alternative SMTP Settings to Try</h2>";
echo "<p class='info'>If the current settings don't work, try these alternatives:</p>";

echo "<h3>Option A: Port 465 with SSL</h3>";
echo "<pre>";
echo "define('SMTP_HOST', 'smtp.gmail.com');\n";
echo "define('SMTP_PORT', 465);\n";
echo "define('SMTP_USERNAME', 'danielbalermo@gmail.com');\n";
echo "define('SMTP_PASSWORD', 'your-16-char-app-password');\n";
echo "define('SMTP_ENCRYPTION', 'ssl');";
echo "</pre>";

echo "<h3>Option B: Port 587 with TLS (Current)</h3>";
echo "<pre>";
echo "define('SMTP_HOST', 'smtp.gmail.com');\n";
echo "define('SMTP_PORT', 587);\n";
echo "define('SMTP_USERNAME', 'danielbalermo@gmail.com');\n";
echo "define('SMTP_PASSWORD', 'your-16-char-app-password');\n";
echo "define('SMTP_ENCRYPTION', 'tls');";
echo "</pre>";
echo "</div>";

// Test 6: Quick Fix Button
echo "<div class='test-box'>";
echo "<h2>6. Quick Fix</h2>";
echo "<p class='info'>If you want to try a different approach, here's a simple fix:</p>";

if (isset($_POST['test_alternative'])) {
    // Test with alternative settings
    $original_port = SMTP_PORT;
    $original_encryption = SMTP_ENCRYPTION;
    
    // Temporarily change settings
    define('SMTP_PORT_ALT', 465);
    define('SMTP_ENCRYPTION_ALT', 'ssl');
    
    echo "<p class='info'>Testing with port 465 and SSL...</p>";
    
    // This would require modifying the sendSMTPEmail function
    echo "<p class='info'>To test this, manually change the settings in notification_config.php</p>";
}

echo "<form method='POST'>";
echo "<button type='submit' name='test_alternative' class='btn' style='background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer;'>Test Alternative Settings</button>";
echo "</form>";
echo "</div>";

echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li><strong>Check your email inbox</strong> - Look for the test email</li>";
echo "<li><strong>Check spam folder</strong> - Gmail might filter it</li>";
echo "<li><strong>Wait 1-5 minutes</strong> - Email delivery can be delayed</li>";
echo "<li><strong>If no email arrives</strong> - Try getting a new Gmail app password</li>";
echo "<li><strong>For better reliability</strong> - Install PHPMailer using Composer</li>";
echo "</ol>";

echo "<p><a href='index.php'>← Back to Dashboard</a> | <a href='test_email_comprehensive.php'>Run Full Test</a></p>";
echo "</body></html>";
?>
