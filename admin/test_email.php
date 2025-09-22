<?php
/**
 * Email Test Script
 * Test the email functionality
 */

require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die('Access denied. Admin privileges required.');
}

$result = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_email = $_POST['test_email'];
    
    if (filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
        try {
            require_once '../includes/notifications.php';
            $notificationSystem = new NotificationSystem($pdo);
            
            $subject = "Test Email from JEL Air Conditioning System";
            $message = "
                <html>
                <body>
                    <h2>Test Email</h2>
                    <p>This is a test email from your JEL Air Conditioning Services Management System.</p>
                    <p>If you received this email, your SMTP configuration is working correctly!</p>
                    <hr>
                    <p><small>Sent on: " . date('Y-m-d H:i:s') . "</small></p>
                </body>
                </html>
            ";
            
            if ($notificationSystem->sendEmail($test_email, $subject, $message, true)) {
                $result = "✅ Test email sent successfully to $test_email!";
            } else {
                $error = "❌ Failed to send test email. Check your SMTP configuration.";
            }
        } catch (Exception $e) {
            $error = "❌ Error: " . $e->getMessage();
        }
    } else {
        $error = "❌ Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Test - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h2">Email Test</h1>
                <p class="text-muted">Test your SMTP email configuration</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-envelope me-2"></i>Send Test Email</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($result): ?>
                            <div class="alert alert-success"><?php echo $result; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="test_email" class="form-label">Test Email Address</label>
                                <input type="email" class="form-control" id="test_email" name="test_email" 
                                       value="<?php echo htmlspecialchars($_POST['test_email'] ?? ''); ?>" required>
                                <div class="form-text">Enter an email address to send a test email to.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Test Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cog me-2"></i>Current Configuration</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>SMTP Host:</strong></td>
                                <td><?php echo defined('SMTP_HOST') ? SMTP_HOST : 'Not set'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP Port:</strong></td>
                                <td><?php echo defined('SMTP_PORT') ? SMTP_PORT : 'Not set'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP Username:</strong></td>
                                <td><?php echo defined('SMTP_USERNAME') ? SMTP_USERNAME : 'Not set'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP Encryption:</strong></td>
                                <td><?php echo defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'Not set'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>From Email:</strong></td>
                                <td><?php echo defined('EMAIL_FROM') ? EMAIL_FROM : 'Not set'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <a href="../index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
                <a href="../system_test.php" class="btn btn-outline-primary">
                    <i class="fas fa-check-circle me-2"></i>System Test
                </a>
            </div>
        </div>
    </div>
</body>
</html>
