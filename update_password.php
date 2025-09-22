<?php
/**
 * Password Update Helper
 * Simple form to update Gmail app password
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Update Gmail Password</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-box { border: 1px solid #ddd; padding: 20px; margin: 20px 0; max-width: 500px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        input[type='password'] { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .instructions { background: #f0f8ff; padding: 15px; margin: 10px 0; }
    </style>
</head>
<body>";

echo "<h1>🔑 Update Gmail App Password</h1>";

// Show current password (masked)
echo "<div class='info'>";
echo "<h2>Current Password (Masked)</h2>";
echo "<p>Current password: " . str_repeat('*', 16) . " (17 chars - should be 16)</p>";
echo "</div>";

// Instructions
echo "<div class='instructions'>";
echo "<h2>📋 How to Get New App Password</h2>";
echo "<ol>";
echo "<li>Go to <a href='https://myaccount.google.com/security' target='_blank'>Google Account Security</a></li>";
echo "<li>Click '2-Step Verification' → 'App passwords'</li>";
echo "<li>Select 'Mail' → 'Other (custom name)'</li>";
echo "<li>Enter: 'JEL Air Conditioning System'</li>";
echo "<li>Click 'Generate'</li>";
echo "<li>Copy the 16-character password (like: abcd efgh ijkl mnop)</li>";
echo "</ol>";
echo "</div>";

// Update form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $newPassword = trim($_POST['new_password']);
    
    if (strlen($newPassword) === 16) {
        // Update the config file
        $configFile = 'includes/notification_config.php';
        $configContent = file_get_contents($configFile);
        
        // Replace the password line
        $newConfigContent = preg_replace(
            "/define\('SMTP_PASSWORD', '[^']*'\);/",
            "define('SMTP_PASSWORD', '$newPassword');",
            $configContent
        );
        
        if (file_put_contents($configFile, $newConfigContent)) {
            echo "<div class='success'>";
            echo "<h2>✅ Password Updated Successfully!</h2>";
            echo "<p>Your Gmail app password has been updated.</p>";
            echo "<p><a href='test_email_working.php'>Test Email System Now</a></p>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<h2>❌ Failed to Update Password</h2>";
            echo "<p>Could not write to config file. Please update manually.</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='error'>";
        echo "<h2>❌ Invalid Password Length</h2>";
        echo "<p>Gmail app passwords must be exactly 16 characters long.</p>";
        echo "<p>Your password is " . strlen($newPassword) . " characters.</p>";
        echo "</div>";
    }
}

echo "<div class='form-box'>";
echo "<h2>🔧 Update Password</h2>";
echo "<form method='POST'>";
echo "<p><strong>Enter your new 16-character Gmail app password:</strong></p>";
echo "<input type='password' name='new_password' placeholder='Enter 16-character password' maxlength='16' required>";
echo "<br><br>";
echo "<button type='submit'>Update Password</button>";
echo "</form>";
echo "</div>";

echo "<div class='info'>";
echo "<h2>⚠️ Manual Update (if form doesn't work)</h2>";
echo "<p>If the form doesn't work, manually edit <code>includes/notification_config.php</code>:</p>";
echo "<pre>";
echo "define('SMTP_PASSWORD', 'your-new-16-char-password'); // Your app password";
echo "</pre>";
echo "</div>";

echo "<p><a href='test_email_working.php'>← Back to Email Test</a> | <a href='index.php'>Dashboard</a></p>";
echo "</body></html>";
?>
