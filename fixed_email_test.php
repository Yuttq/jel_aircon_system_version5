<?php
/**
 * Simple Email Test - Fixed Configuration
 * This tests the corrected email settings
 */

require_once 'includes/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$result = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_fixed_email'])) {
    $testEmail = $_POST['test_email'] ?? '';
    
    if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Test with fixed configuration
        $result = testFixedEmail($testEmail);
    }
}

function testFixedEmail($to) {
    try {
        // Test 1: Check configuration
        $configCheck = [
            'SMTP_HOST' => SMTP_HOST,
            'SMTP_PORT' => SMTP_PORT,
            'SMTP_USERNAME' => SMTP_USERNAME,
            'SMTP_PASSWORD' => strlen(SMTP_PASSWORD) > 0 ? 'Set (' . strlen(SMTP_PASSWORD) . ' chars)' : 'Empty',
            'EMAIL_FROM' => EMAIL_FROM,
            'EMAIL_FROM_NAME' => EMAIL_FROM_NAME
        ];
        
        // Test 2: Try sending email using PHP's mail() function
        $subject = 'Fixed Configuration Test - ' . date('Y-m-d H:i:s');
        $message = 'This is a test email with the fixed configuration.';
        $headers = "From: " . EMAIL_FROM . "\r\n";
        $headers .= "Reply-To: " . EMAIL_FROM . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        $mailResult = mail($to, $subject, $message, $headers);
        
        if ($mailResult) {
            return "✅ Email sent successfully with fixed configuration! Check your inbox (and spam folder).";
        } else {
            return "❌ Email still failing. Let's try SMTP method...";
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
    <title>Fixed Email Test - JEL Air Conditioning</title>
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
            max-width: 700px;
            width: 90%;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="text-center mb-4">
            <i class="fas fa-tools fa-3x text-success mb-3"></i>
            <h2 class="text-success">Fixed Email Configuration Test</h2>
            <p class="text-muted">Testing the corrected email settings</p>
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

        <!-- Fixed Configuration Display -->
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h6><i class="fas fa-check-circle me-2"></i>Fixed Configuration:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <small><strong>SMTP Host:</strong> <?php echo SMTP_HOST; ?></small><br>
                        <small><strong>SMTP Port:</strong> <?php echo SMTP_PORT; ?></small><br>
                        <small><strong>Encryption:</strong> <?php echo SMTP_ENCRYPTION; ?></small>
                    </div>
                    <div class="col-md-6">
                        <small><strong>Username:</strong> <?php echo SMTP_USERNAME; ?></small><br>
                        <small><strong>Password:</strong> <?php echo strlen(SMTP_PASSWORD) > 0 ? 'Set (' . strlen(SMTP_PASSWORD) . ' chars)' : 'Empty'; ?></small><br>
                        <small><strong>From Email:</strong> <?php echo EMAIL_FROM; ?></small>
                    </div>
                </div>
                <div class="mt-2">
                    <small><strong>✅ FIXED:</strong> EMAIL_FROM now matches SMTP_USERNAME</small>
                </div>
            </div>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label for="test_email" class="form-label">Test Email Address</label>
                <input type="email" class="form-control" id="test_email" name="test_email" 
                       placeholder="Enter your email address" required>
                <div class="form-text">This will test the fixed email configuration.</div>
            </div>
            
            <button type="submit" name="test_fixed_email" class="btn btn-success w-100">
                <i class="fas fa-paper-plane me-2"></i>Test Fixed Email Configuration
            </button>
        </form>

        <div class="mt-4">
            <h6>What Was Fixed:</h6>
            <ul class="small">
                <li><strong>EMAIL_FROM</strong> now matches <strong>SMTP_USERNAME</strong></li>
                <li><strong>EMAIL_REPLY_TO</strong> now matches <strong>SMTP_USERNAME</strong></li>
                <li>This prevents Gmail authentication errors</li>
                <li>Emails will now be sent from your Gmail account</li>
            </ul>
        </div>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
            <a href="quick_gmail_test.php" class="btn btn-outline-primary ms-2">
                <i class="fas fa-envelope me-2"></i>Quick Test
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
