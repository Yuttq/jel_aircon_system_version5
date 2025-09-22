<?php
/**
 * Admin Login Helper
 * Quick access to admin functions
 */

require_once '../includes/config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        $message = "✅ Login successful! Redirecting to dashboard...";
        echo "<script>setTimeout(function(){ window.location.href = '../index.php'; }, 2000);</script>";
    } else {
        $error = '❌ Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card login-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                                <h2 class="card-title">Admin Login</h2>
                                <p class="text-muted">JEL Air Conditioning Management System</p>
                            </div>
                            
                            <?php if ($message): ?>
                                <div class="alert alert-success"><?php echo $message; ?></div>
                            <?php endif; ?>
                            
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="admin" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Enter password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login as Admin
                                </button>
                            </form>
                            
                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">Default credentials:</p>
                                <small class="text-muted">Username: <strong>admin</strong></small><br>
                                <small class="text-muted">Password: <strong>password</strong></small>
                            </div>
                            
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="mb-2">Quick Links:</h6>
                                <div class="d-grid gap-2">
                                    <a href="../customer_portal/login.php" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-user me-1"></i>Customer Portal
                                    </a>
                                    <a href="../system_test.php" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-check-circle me-1"></i>System Test
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
