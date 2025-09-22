<?php
include '../../includes/config.php';
checkAuth();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!$security->validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors['csrf'] = 'Invalid request. Please try again.';
    } else {
        $firstName = $security->sanitizeInput($_POST['first_name']);
        $lastName = $security->sanitizeInput($_POST['last_name']);
        $email = $security->sanitizeInput($_POST['email']);
        $phone = $security->sanitizeInput($_POST['phone']);
        $address = $security->sanitizeInput($_POST['address']);

        // Enhanced validation
        if (empty($firstName)) {
            $errors['first_name'] = 'First name is required';
        } elseif (strlen($firstName) < 2) {
            $errors['first_name'] = 'First name must be at least 2 characters';
        }
        
        if (empty($lastName)) {
            $errors['last_name'] = 'Last name is required';
        } elseif (strlen($lastName) < 2) {
            $errors['last_name'] = 'Last name must be at least 2 characters';
        }
        
        if (empty($phone)) {
            $errors['phone'] = 'Phone number is required';
        } elseif (!$security->validatePhone($phone)) {
            $errors['phone'] = 'Please enter a valid Philippine phone number';
        }
        
        if (!empty($email) && !$security->validateEmail($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        // Check for duplicate phone/email
        if (empty($errors)) {
            try {
                $checkStmt = $pdo->prepare("SELECT id FROM customers WHERE phone = ? OR email = ?");
                $checkStmt->execute([$phone, $email]);
                $existing = $checkStmt->fetch();
                
                if ($existing) {
                    $errors['duplicate'] = 'A customer with this phone number or email already exists';
                }
            } catch (PDOException $e) {
                $security->logSecurityEvent('customer_duplicate_check_error', [
                    'error' => $e->getMessage()
                ]);
                $errors['database'] = 'Unable to verify customer information. Please try again.';
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO customers (first_name, last_name, email, phone, address) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$firstName, $lastName, $email, $phone, $address]);
                
                $customerId = $pdo->lastInsertId();
                
                // Log successful customer creation
                $security->logSecurityEvent('customer_created', [
                    'customer_id' => $customerId,
                    'created_by' => $_SESSION['user_id']
                ]);
                
                // Redirect with success message
                redirectWithMessage('index.php', 'Customer added successfully!', 'success');
                
            } catch (PDOException $e) {
                $security->logSecurityEvent('customer_creation_error', [
                    'error' => $e->getMessage(),
                    'data' => ['first_name' => $firstName, 'last_name' => $lastName]
                ]);
                $errors['database'] = 'Error adding customer. Please try again.';
            }
        }
    }
}

// Get flash message if redirected
$flashMessage = getFlashMessage();
if ($flashMessage) {
    $success = $flashMessage['message'];
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Add New Customer
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($errors['database'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($errors['database']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="customerForm">
                        <input type="hidden" name="csrf_token" value="<?php echo $security->generateCSRFToken(); ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>First Name *
                                </label>
                                <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" 
                                       id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" 
                                       required minlength="2" maxlength="50">
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo htmlspecialchars($errors['first_name']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Last Name *
                                </label>
                                <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                                       id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" 
                                       required minlength="2" maxlength="50">
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo htmlspecialchars($errors['last_name']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address
                                </label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       maxlength="100">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo htmlspecialchars($errors['email']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Phone Number *
                                </label>
                                <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                                       id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" 
                                       required placeholder="09XX XXX XXXX">
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo htmlspecialchars($errors['phone']); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="form-text">Enter Philippine mobile number (e.g., 09123456789)</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Address
                            </label>
                            <textarea class="form-control" id="address" name="address" rows="3" 
                                      maxlength="500" placeholder="Enter complete address"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                            <div class="form-text">Optional: Complete address for service delivery</div>
                        </div>

                        <?php if (isset($errors['duplicate'])): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($errors['duplicate']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Customers
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Add Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation and enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('customerForm');
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
        let isValid = true;
        
        // Clear previous validation
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        // Validate required fields
        const requiredFields = ['first_name', 'last_name', 'phone'];
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        // Validate email if provided
        const emailField = document.getElementById('email');
        if (emailField.value && !emailField.checkValidity()) {
            emailField.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            // Show toast notification
            showToast('Please fill in all required fields correctly.', 'error');
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

// Toast notification function
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>

<?php include '../../includes/footer.php'; ?>