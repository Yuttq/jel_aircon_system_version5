<?php
/**
 * Simple Email Test
 * Quick test for email functionality
 */

require_once 'includes/config.php';

$result = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_email = $_POST['test_email'];
    
    if (filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
        try {
            require_once 'includes/notifications.php';
            $notificationSystem = new NotificationSystem($pdo);
            
            $subject = "Test Email from JEL Air Conditioning System";
            $message = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2 style='color: #667eea;'>Test Email Success!</h2>
                        <p>This is a test email from your JEL Air Conditioning Services Management System.</p>
                        <p>If you received this email, your SMTP configuration is working correctly!</p>
                        
                        <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                            <h4>System Information:</h4>
                            <ul>
                                <li>Sent on: " . date('Y-m-d H:i:s') . "</li>
                                <li>SMTP Host: " . (defined('SMTP_HOST') ? SMTP_HOST : 'Not configured') . "</li>
                                <li>SMTP Port: " . (defined('SMTP_PORT') ? SMTP_PORT : 'Not configured') . "</li>
                                <li>From Email: " . (defined('EMAIL_FROM') ? EMAIL_FROM : 'Not configured') . "</li>
                            </ul>
                        </div>
                        
                        <p>Your email system is ready for production use!</p>
                        
                        <hr style='margin: 30px 0;'>
                        <p style='color: #666; font-size: 12px;'>
                            This is an automated test email from JEL Air Conditioning Services Management System.
                        </p>
                    </div>
                </body>
                </html>
            ";
            
            if ($notificationSystem->sendEmail($test_email, $subject, $message, true)) {
                $result = "✅ Test email sent successfully to $test_email! Check your inbox.";
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .test-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px 0;
        }
        .test-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card test-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                <h2 class="card-title">Email Test</h2>
                                <p class="text-muted">Test your email configuration</p>
                            </div>
                            
                            <?php if ($result): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i><?php echo $result; ?>
                                </div>
                                <div class="text-center">
                                    <a href="index_public.php" class="btn btn-primary">Back to Home</a>
                                    <a href="admin/email_config.php" class="btn btn-outline-primary">Email Config</a>
                                </div>
                            <?php else: ?>
                                
                                <?php if ($error): ?>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="test_email" class="form-label">Test Email Address</label>
                                        <input type="email" class="form-control" id="test_email" name="test_email" 
                                               value="<?php echo htmlspecialchars($_POST['test_email'] ?? ''); ?>" 
                                               placeholder="Enter your email address" required>
                                        <div class="form-text">We'll send a test email to verify your configuration.</div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100 py-2">
                                        <i class="fas fa-paper-plane me-2"></i>Send Test Email
                                    </button>
                                </form>
                                
                                <div class="mt-4 p-3 bg-light rounded">
                                    <h6 class="mb-2">Current Configuration:</h6>
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <td><strong>SMTP Host:</strong></td>
                                            <td><?php echo defined('SMTP_HOST') ? SMTP_HOST : 'Not configured'; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>SMTP Port:</strong></td>
                                            <td><?php echo defined('SMTP_PORT') ? SMTP_PORT : 'Not configured'; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>SMTP Username:</strong></td>
                                            <td><?php echo defined('SMTP_USERNAME') ? SMTP_USERNAME : 'Not configured'; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Encryption:</strong></td>
                                            <td><?php echo defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'Not configured'; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <a href="admin/email_config.php" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-cog me-1"></i>Configure Email
                                    </a>
                                    <a href="GMAIL_SETUP_GUIDE.md" class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="fas fa-book me-1"></i>Gmail Setup Guide
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
