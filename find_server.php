<?php
/**
 * Find Server Information
 * This will help you determine the correct URL to use
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Server Information</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .info { background: #e7f3ff; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
        .success { background: #e8f5e8; padding: 15px; margin: 10px 0; border-left: 4px solid #4CAF50; }
        .warning { background: #fff3cd; padding: 15px; margin: 10px 0; border-left: 4px solid #ffc107; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>";

echo "<h1>🔍 Server Information & URL Finder</h1>";

echo "<div class='info'>";
echo "<h2>Current Server Information</h2>";
echo "<p><strong>Server Name:</strong> " . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'Not available') . "</p>";
echo "<p><strong>Server Address:</strong> " . (isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'Not available') . "</p>";
echo "<p><strong>Server Port:</strong> " . (isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 'Not available') . "</p>";
echo "<p><strong>Request URI:</strong> " . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'Not available') . "</p>";
echo "<p><strong>HTTP Host:</strong> " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'Not available') . "</p>";
echo "<p><strong>Document Root:</strong> " . (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'Not available') . "</p>";
echo "</div>";

echo "<div class='success'>";
echo "<h2>✅ Correct URLs to Use</h2>";
echo "<p>Based on your server configuration, use these URLs:</p>";

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$uri = $_SERVER['REQUEST_URI'] ?? '';

// Remove the current filename from the URI
$basePath = dirname($uri);
if ($basePath === '.') {
    $basePath = '';
}

$baseUrl = $protocol . '://' . $host . $basePath;

echo "<h3>Test Files:</h3>";
echo "<ul>";
echo "<li><a href='{$baseUrl}/test_phpmailer_install.php' target='_blank'>{$baseUrl}/test_phpmailer_install.php</a></li>";
echo "<li><a href='{$baseUrl}/test_email_comprehensive.php' target='_blank'>{$baseUrl}/test_email_comprehensive.php</a></li>";
echo "<li><a href='{$baseUrl}/test_email_simple_fix.php' target='_blank'>{$baseUrl}/test_email_simple_fix.php</a></li>";
echo "<li><a href='{$baseUrl}/index.php' target='_blank'>{$baseUrl}/index.php</a></li>";
echo "</ul>";

echo "<h3>Copy these URLs:</h3>";
echo "<pre>";
echo "{$baseUrl}/test_phpmailer_install.php\n";
echo "{$baseUrl}/test_email_comprehensive.php\n";
echo "{$baseUrl}/test_email_simple_fix.php\n";
echo "{$baseUrl}/index.php";
echo "</pre>";
echo "</div>";

echo "<div class='warning'>";
echo "<h2>⚠️ If URLs Don't Work</h2>";
echo "<p>If the URLs above don't work, try these common alternatives:</p>";
echo "<ul>";
echo "<li><code>http://localhost/jel_aircon_system/test_phpmailer_install.php</code></li>";
echo "<li><code>http://127.0.0.1/jel_aircon_system/test_phpmailer_install.php</code></li>";
echo "<li><code>http://localhost:8080/test_phpmailer_install.php</code></li>";
echo "<li><code>http://localhost:3000/test_phpmailer_install.php</code></li>";
echo "</ul>";
echo "</div>";

echo "<div class='info'>";
echo "<h2>📋 Step-by-Step Instructions</h2>";
echo "<ol>";
echo "<li><strong>Copy one of the URLs above</strong></li>";
echo "<li><strong>Paste it into your browser address bar</strong></li>";
echo "<li><strong>Press Enter</strong></li>";
echo "<li><strong>If it works:</strong> You'll see the test page</li>";
echo "<li><strong>If it doesn't work:</strong> Try the next URL in the list</li>";
echo "</ol>";
echo "</div>";

echo "<div class='success'>";
echo "<h2>🎯 Quick Test</h2>";
echo "<p>Click this button to test if the email system is working:</p>";
echo "<form method='POST'>";
echo "<button type='submit' name='quick_test' style='background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px;'>Run Quick Email Test</button>";
echo "</form>";
echo "</div>";

if (isset($_POST['quick_test'])) {
    echo "<div class='info'>";
    echo "<h2>🧪 Quick Test Results</h2>";
    
    try {
        require_once 'includes/config.php';
        require_once 'includes/notification_config.php';
        require_once 'includes/simple_smtp.php';
        
        $testEmail = 'danielbalermo@gmail.com';
        $subject = 'Quick Test - ' . date('Y-m-d H:i:s');
        $message = 'This is a quick test email from your JEL Air Conditioning System.';
        
        echo "<p>Testing email sending...</p>";
        
        $result = sendSMTPEmail($testEmail, $subject, $message, false);
        
        if ($result) {
            echo "<p style='color: green; font-weight: bold;'>✅ Email sent successfully!</p>";
            echo "<p>Check your inbox at <strong>danielbalermo@gmail.com</strong></p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ Email failed to send</p>";
            echo "<p>This might be due to Gmail app password issues.</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red; font-weight: bold;'>❌ Error: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
}

echo "<p><strong>Need help?</strong> If none of these URLs work, let me know what error message you see and I'll help you troubleshoot!</p>";
echo "</body></html>";
?>
