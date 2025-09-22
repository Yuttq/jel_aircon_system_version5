<?php
/**
 * Test PHPMailer Installation
 * Check if PHPMailer is properly installed and working
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>PHPMailer Installation Test</title>
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

echo "<h1>🔧 PHPMailer Installation Test</h1>";

// Test 1: Check if vendor directory exists
echo "<div class='test-box'>";
echo "<h2>1. Directory Structure Check</h2>";

$vendorDir = __DIR__ . '/vendor';
$phpmailerDir = $vendorDir . '/phpmailer/phpmailer';
$autoloadFile = $vendorDir . '/autoload.php';

if (is_dir($vendorDir)) {
    echo "<p class='success'>✅ vendor/ directory exists</p>";
} else {
    echo "<p class='error'>❌ vendor/ directory not found</p>";
}

if (is_dir($phpmailerDir)) {
    echo "<p class='success'>✅ vendor/phpmailer/phpmailer/ directory exists</p>";
} else {
    echo "<p class='error'>❌ vendor/phpmailer/phpmailer/ directory not found</p>";
}

if (file_exists($autoloadFile)) {
    echo "<p class='success'>✅ vendor/autoload.php exists</p>";
} else {
    echo "<p class='error'>❌ vendor/autoload.php not found</p>";
}

echo "</div>";

// Test 2: Check if PHPMailer classes can be loaded
echo "<div class='test-box'>";
echo "<h2>2. PHPMailer Class Loading Test</h2>";

try {
    require_once $autoloadFile;
    echo "<p class='success'>✅ Autoloader loaded successfully</p>";
    
    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        echo "<p class='success'>✅ PHPMailer class is available</p>";
    } else {
        echo "<p class='error'>❌ PHPMailer class not found</p>";
    }
    
    if (class_exists('PHPMailer\\PHPMailer\\SMTP')) {
        echo "<p class='success'>✅ SMTP class is available</p>";
    } else {
        echo "<p class='error'>❌ SMTP class not found</p>";
    }
    
    if (class_exists('PHPMailer\\PHPMailer\\Exception')) {
        echo "<p class='success'>✅ Exception class is available</p>";
    } else {
        echo "<p class='error'>❌ Exception class not found</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error loading PHPMailer: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Test 3: Test PHPMailer functionality
echo "<div class='test-box'>";
echo "<h2>3. PHPMailer Functionality Test</h2>";

try {
    require_once 'includes/config.php';
    require_once 'includes/notification_config.php';
    require_once 'includes/phpmailer_smtp.php';
    
    $testEmail = 'danielbalermo@gmail.com';
    $subject = 'PHPMailer Test - ' . date('Y-m-d H:i:s');
    $message = 'This is a test email using PHPMailer.';
    
    echo "<p class='info'>Testing PHPMailer email sending...</p>";
    echo "<p class='info'>Sending to: $testEmail</p>";
    
    $result = sendPHPMailerEmail($testEmail, $subject, $message, false);
    
    if ($result) {
        echo "<p class='success'>✅ PHPMailer email sent successfully!</p>";
        echo "<p class='info'>Check your inbox for the test email.</p>";
    } else {
        echo "<p class='error'>❌ PHPMailer email failed to send</p>";
        echo "<p class='info'>This might be due to SMTP configuration issues.</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ PHPMailer test error: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Test 4: Test Enhanced SMTP function
echo "<div class='test-box'>";
echo "<h2>4. Enhanced SMTP Function Test</h2>";

try {
    require_once 'includes/notifications.php';
    
    $testEmail = 'danielbalermo@gmail.com';
    $subject = 'Enhanced SMTP Test - ' . date('Y-m-d H:i:s');
    $message = 'This is a test email using the enhanced SMTP function.';
    
    echo "<p class='info'>Testing enhanced SMTP function...</p>";
    
    $result = sendEnhancedSMTPEmail($testEmail, $subject, $message, false);
    
    if ($result) {
        echo "<p class='success'>✅ Enhanced SMTP email sent successfully!</p>";
        echo "<p class='info'>This function automatically uses PHPMailer if available.</p>";
    } else {
        echo "<p class='error'>❌ Enhanced SMTP email failed to send</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Enhanced SMTP test error: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Test 5: Test Notification System
echo "<div class='test-box'>";
echo "<h2>5. Notification System Test</h2>";

try {
    $notificationSystem = new NotificationSystem($pdo);
    
    $testData = [
        'customer_name' => 'Test Customer',
        'service_name' => 'AC Cleaning Test',
        'booking_date' => date('Y-m-d', strtotime('+1 day')),
        'start_time' => '10:00:00',
        'booking_id' => 'TEST-' . time()
    ];
    
    echo "<p class='info'>Testing notification system...</p>";
    
    $result = $notificationSystem->sendTestEmail($testEmail, $testData);
    
    if ($result) {
        echo "<p class='success'>✅ Notification system email sent successfully!</p>";
        echo "<p class='info'>This uses the enhanced email system with PHPMailer.</p>";
    } else {
        echo "<p class='error'>❌ Notification system email failed to send</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Notification system test error: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Summary
echo "<div class='test-box'>";
echo "<h2>📊 Test Summary</h2>";

echo "<h3>What was tested:</h3>";
echo "<ul>";
echo "<li>✅ PHPMailer directory structure</li>";
echo "<li>✅ PHPMailer class loading</li>";
echo "<li>✅ PHPMailer email sending</li>";
echo "<li>✅ Enhanced SMTP function</li>";
echo "<li>✅ Notification system integration</li>";
echo "</ul>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li><strong>Check your email inbox</strong> - Look for test emails</li>";
echo "<li><strong>Check spam folder</strong> - Gmail might filter them</li>";
echo "<li><strong>Wait 1-5 minutes</strong> - Email delivery can be delayed</li>";
echo "<li><strong>If emails work</strong> - Your system is now using PHPMailer!</li>";
echo "<li><strong>If emails don't work</strong> - Check Gmail app password</li>";
echo "</ol>";

echo "<h3>Files Created:</h3>";
echo "<ul>";
echo "<li><code>vendor/autoload.php</code> - PHPMailer autoloader</li>";
echo "<li><code>vendor/phpmailer/phpmailer/src/PHPMailer.php</code> - Main PHPMailer class</li>";
echo "<li><code>vendor/phpmailer/phpmailer/src/SMTP.php</code> - SMTP class</li>";
echo "<li><code>vendor/phpmailer/phpmailer/src/Exception.php</code> - Exception class</li>";
echo "<li><code>includes/phpmailer_smtp.php</code> - PHPMailer integration</li>";
echo "</ul>";

echo "</div>";

echo "<p><a href='index.php'>← Back to Dashboard</a> | <a href='test_email_simple_fix.php'>Simple Test</a> | <a href='test_email_comprehensive.php'>Full Test</a></p>";
echo "</body></html>";
?>
