<?php
/**
 * Quick Gmail Test - Simple SMTP Test
 * This will help identify the exact issue
 */

require_once 'includes/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$result = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_smtp'])) {
    $testEmail = $_POST['test_email'] ?? '';
    
    if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Test SMTP connection directly
        $result = testSMTPDirect($testEmail);
    }
}

function testSMTPDirect($to) {
    try {
        // Test 1: Check if SMTP functions are available
        if (!function_exists('fsockopen')) {
            return "❌ fsockopen function not available. Contact your hosting provider.";
        }
        
        // Test 2: Test SMTP connection
        $host = SMTP_HOST;
        $port = SMTP_PORT;
        
        $connection = @fsockopen($host, $port, $errno, $errstr, 10);
        
        if (!$connection) {
            return "❌ Cannot connect to $host:$port - $errstr ($errno)";
        }
        
        fclose($connection);
        
        // Test 3: Try sending email using PHP's mail() function first
        $subject = 'Quick Test - ' . date('Y-m-d H:i:s');
        $message = 'This is a quick test email from JEL Air Conditioning System.';
        $headers = "From: " . EMAIL_FROM . "\r\n";
        
        $mailResult = mail($to, $subject, $message, $headers);
        
        if ($mailResult) {
            return "✅ Basic email sent successfully! Check your inbox (and spam folder).";
        } else {
            return "❌ Basic email failed. Trying SMTP method...";
        }
        
    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Gmail Test - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .test-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            max-width: 600px;
            width: 90%;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="text-center mb-4">
            <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
            <h2 class="text-primary">Quick Gmail Test</h2>
            <p class="text-muted">Let's identify the email issue quickly</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($result): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <?php echo htmlspecialchars($result); ?>
            </div>
        <?php endif; ?>

        <!-- Current Config Display -->
        <div class="card bg-light mb-4">
            <div class="card-body">
                <h6>Current Configuration:</h6>
                <div class="row">
                    <div class="col-6">
                        <small><strong>SMTP Host:</strong> <?php echo SMTP_HOST; ?></small><br>
                        <small><strong>SMTP Port:</strong> <?php echo SMTP_PORT; ?></small><br>
                        <small><strong>Encryption:</strong> <?php echo SMTP_ENCRYPTION; ?></small>
                    </div>
                    <div class="col-6">
                        <small><strong>Username:</strong> <?php echo SMTP_USERNAME; ?></small><br>
                        <small><strong>Password:</strong> <?php echo strlen(SMTP_PASSWORD) > 0 ? 'Set (' . strlen(SMTP_PASSWORD) . ' chars)' : 'Empty'; ?></small><br>
                        <small><strong>From:</strong> <?php echo EMAIL_FROM; ?></small>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label for="test_email" class="form-label">Test Email Address</label>
                <input type="email" class="form-control" id="test_email" name="test_email" 
                       placeholder="Enter your email address" required>
            </div>
            
            <button type="submit" name="test_smtp" class="btn btn-primary w-100">
                <i class="fas fa-paper-plane me-2"></i>Test Email Now
            </button>
        </form>

        <div class="mt-4">
            <h6>Quick Troubleshooting:</h6>
            <ul class="small">
                <li><strong>No email received?</strong> Check spam folder</li>
                <li><strong>Connection failed?</strong> Check internet/firewall</li>
                <li><strong>Authentication failed?</strong> Check Gmail app password</li>
                <li><strong>Still not working?</strong> Try the <a href="email_diagnostics.php">Full Diagnostics</a></li>
            </ul>
        </div>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
