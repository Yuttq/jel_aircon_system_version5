<?php
/**
 * Simple Email Debug Test
 * Test the email functionality step by step
 */

require_once 'includes/config.php';
require_once 'includes/notification_config.php';
require_once 'includes/simple_smtp.php';

echo "<h1>Email Debug Test</h1>";

// Test 1: Check Configuration
echo "<h2>1. Configuration Check</h2>";
echo "SMTP_HOST: " . (defined('SMTP_HOST') ? SMTP_HOST : 'NOT DEFINED') . "<br>";
echo "SMTP_PORT: " . (defined('SMTP_PORT') ? SMTP_PORT : 'NOT DEFINED') . "<br>";
echo "SMTP_USERNAME: " . (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'NOT DEFINED') . "<br>";
echo "SMTP_PASSWORD: " . (defined('SMTP_PASSWORD') ? 'SET (' . strlen(SMTP_PASSWORD) . ' chars)' : 'NOT DEFINED') . "<br>";
echo "SMTP_ENCRYPTION: " . (defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'NOT DEFINED') . "<br>";
echo "EMAIL_FROM: " . (defined('EMAIL_FROM') ? EMAIL_FROM : 'NOT DEFINED') . "<br>";

// Test 2: Test SMTP Connection
echo "<h2>2. SMTP Connection Test</h2>";
try {
    $connection = @fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 10);
    if ($connection) {
        echo "✅ Successfully connected to " . SMTP_HOST . ":" . SMTP_PORT . "<br>";
        fclose($connection);
    } else {
        echo "❌ Failed to connect: $errstr ($errno)<br>";
    }
} catch (Exception $e) {
    echo "❌ Connection error: " . $e->getMessage() . "<br>";
}

// Test 3: Test Simple Email
echo "<h2>3. Simple Email Test</h2>";
$testEmail = 'danielbalermo@gmail.com'; // Use your own email for testing
$subject = 'Debug Test Email - ' . date('Y-m-d H:i:s');
$message = 'This is a debug test email.';

try {
    $result = sendSMTPEmail($testEmail, $subject, $message, false);
    if ($result) {
        echo "✅ SMTP email sent successfully!<br>";
    } else {
        echo "❌ SMTP email failed to send<br>";
    }
} catch (Exception $e) {
    echo "❌ SMTP email error: " . $e->getMessage() . "<br>";
}

// Test 4: Test with PHP mail() as fallback
echo "<h2>4. PHP mail() Fallback Test</h2>";
$headers = "From: " . EMAIL_FROM . "\r\n";
$headers .= "Reply-To: " . EMAIL_FROM . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$result = mail($testEmail, $subject . ' (PHP mail)', $message, $headers);
if ($result) {
    echo "✅ PHP mail() function worked<br>";
} else {
    echo "❌ PHP mail() function failed<br>";
}

echo "<h2>Test Complete</h2>";
echo "<p>Check your email inbox for the test emails.</p>";
?>
