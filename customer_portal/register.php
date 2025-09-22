<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['customer_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!$security->validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $first_name = $security->sanitizeInput($_POST['first_name']);
        $last_name = $security->sanitizeInput($_POST['last_name']);
        $email = $security->sanitizeInput($_POST['email']);
        $phone = $security->sanitizeInput($_POST['phone']);
        $address = $security->sanitizeInput($_POST['address']);
        
        // Enhanced validation
        if (empty($first_name) || strlen($first_name) < 2) {
            $error = 'First name must be at least 2 characters.';
        } elseif (empty($last_name) || strlen($last_name) < 2) {
            $error = 'Last name must be at least 2 characters.';
        } elseif (empty($email) || !$security->validateEmail($email)) {
            $error = 'Please enter a valid email address.';
        } elseif (empty($phone) || !$security->validatePhone($phone)) {
            $error = 'Please enter a valid Philippine phone number.';
        } else {
            try {
                // Check if customer already exists
                $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ? OR phone = ?");
                $stmt->execute([$email, $phone]);
                $existing = $stmt->fetch();
                
                if ($existing) {
                    $error = 'A customer with this email or phone number already exists.';
                } else {
                    // Create new customer
                    $stmt = $pdo->prepare("INSERT INTO customers (first_name, last_name, email, phone, address) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$first_name, $last_name, $email, $phone, $address]);
                    
                    $customerId = $pdo->lastInsertId();
                    
                    // Log successful registration
                    $security->logSecurityEvent('customer_registration', [
                        'customer_id' => $customerId,
                        'email' => $email
                    ]);
                    
                    $success = 'Registration successful! You can now login to the customer portal.';
                    
                    // Clear form data
                    $_POST = [];
                }
            } catch (Exception $e) {
                $security->logSecurityEvent('customer_registration_error', [
                    'error' => $e->getMessage(),
                    'email' => $email
                ]);
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            max-width: 600px;
            width: 90%;
        }
        .brand-logo {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }
        .alert {
            border-radius: 10px;
            border: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="text-center mb-4">
            <i class="fas fa-snowflake fa-3x text-primary mb-3"></i>
            <h2 class="brand-logo">JEL Air Conditioning</h2>
            <p class="text-muted">Customer Registration</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <form method="POST" id="registrationForm">
            <input type="hidden" name="csrf_token" value="<?php echo $security->generateCSRFToken(); ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">
                        <i class="fas fa-user me-1"></i>First Name *
                    </label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" 
                           required minlength="2" maxlength="50">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">
                        <i class="fas fa-user me-1"></i>Last Name *
                    </label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" 
                           required minlength="2" maxlength="50">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1"></i>Email Address *
                </label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                       required maxlength="100">
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">
                    <i class="fas fa-phone me-1"></i>Phone Number *
                </label>
                <input type="tel" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" 
                       required placeholder="09XX XXX XXXX">
                <div class="form-text">Enter Philippine mobile number (e.g., 09123456789)</div>
            </div>
            
            <div class="mb-4">
                <label for="address" class="form-label">
                    <i class="fas fa-map-marker-alt me-1"></i>Address
                </label>
                <textarea class="form-control" id="address" name="address" rows="3" 
                          maxlength="500" placeholder="Enter complete address"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                <div class="form-text">Optional: Complete address for service delivery</div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-register w-100">
                <i class="fas fa-user-plus me-2"></i>Register Account
            </button>
        </form>
        
        <div class="text-center mt-4">
            <p class="mb-0">Already have an account? <a href="login.php" class="text-decoration-none">Login here</a></p>
            <p class="mt-2"><a href="../" class="text-decoration-none">← Back to main website</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const phoneInput = document.getElementById('phone');
            
            // Phone number formatting
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                e.target.value = value;
            });
            
            // Form validation
            form.addEventListener('submit', function(e) {
                const firstName = document.getElementById('first_name').value.trim();
                const lastName = document.getElementById('last_name').value.trim();
                const email = document.getElementById('email').value.trim();
                const phone = document.getElementById('phone').value.trim();
                
                if (!firstName || !lastName || !email || !phone) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
                
                if (firstName.length < 2 || lastName.length < 2) {
                    e.preventDefault();
                    alert('Names must be at least 2 characters long.');
                    return false;
                }
                
                if (!JELAircon.validateEmail(email)) {
                    e.preventDefault();
                    alert('Please enter a valid email address.');
                    return false;
                }
                
                if (!JELAircon.validatePhone(phone)) {
                    e.preventDefault();
                    alert('Please enter a valid Philippine phone number.');
                    return false;
                }
            });
            
            // Auto-dismiss alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
