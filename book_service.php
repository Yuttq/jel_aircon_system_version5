<?php
/**
 * Public Booking Form - JEL Air Conditioning Services
 * Simple booking form accessible to public
 */

require_once 'includes/config.php';

$errors = [];
$success = '';

// Get services for the form
$serviceStmt = $pdo->prepare("SELECT id, name, price, duration FROM services WHERE status = 1 ORDER BY name");
$serviceStmt->execute();
$services = $serviceStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $service_id = $_POST['service_id'];
    $booking_date = $_POST['booking_date'];
    $preferred_time = $_POST['preferred_time'];
    $notes = trim($_POST['notes']);
    
    // Validation
    if (empty($first_name)) $errors[] = 'First name is required';
    if (empty($last_name)) $errors[] = 'Last name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($phone)) $errors[] = 'Phone number is required';
    if (empty($address)) $errors[] = 'Address is required';
    if (empty($service_id)) $errors[] = 'Please select a service';
    if (empty($booking_date)) $errors[] = 'Please select a booking date';
    if (empty($preferred_time)) $errors[] = 'Please select a preferred time';
    
    if (empty($errors)) {
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Check if customer exists, if not create one
            $customerStmt = $pdo->prepare("SELECT id FROM customers WHERE email = ? AND phone = ?");
            $customerStmt->execute([$email, $phone]);
            $customer = $customerStmt->fetch();
            
            if (!$customer) {
                // Create new customer
                $customerStmt = $pdo->prepare("INSERT INTO customers (first_name, last_name, email, phone, address) VALUES (?, ?, ?, ?, ?)");
                $customerStmt->execute([$first_name, $last_name, $email, $phone, $address]);
                $customer_id = $pdo->lastInsertId();
            } else {
                $customer_id = $customer['id'];
            }
            
            // Get service details
            $serviceStmt = $pdo->prepare("SELECT name, duration FROM services WHERE id = ?");
            $serviceStmt->execute([$service_id]);
            $service = $serviceStmt->fetch();
            
            // Calculate end time
            $start_time = $preferred_time;
            $end_time = date('H:i:s', strtotime($start_time . ' + ' . $service['duration'] . ' minutes'));
            
            // Create booking
            $bookingStmt = $pdo->prepare("INSERT INTO bookings (customer_id, service_id, booking_date, start_time, end_time, status, notes) VALUES (?, ?, ?, ?, ?, 'pending', ?)");
            $bookingStmt->execute([$customer_id, $service_id, $booking_date, $start_time, $end_time, $notes]);
            $booking_id = $pdo->lastInsertId();
            
            // Commit transaction
            $pdo->commit();
            
            $success = "Booking created successfully! Booking ID: #$booking_id. We'll contact you soon to confirm your appointment.";
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Failed to create booking. Please try again.';
            error_log("Booking error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .booking-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px 0;
        }
        .booking-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 15px;
        }
        .service-card {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .service-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        .service-card.selected {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card booking-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <img src="assets/images/logo.svg" alt="JEL Air Conditioning" height="80" class="mb-3">
                                <h2 class="card-title">Book Your Service</h2>
                                <p class="text-muted">Schedule your air conditioning service appointment</p>
                            </div>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                                </div>
                                <div class="text-center">
                                    <a href="index_public.php" class="btn btn-primary shadow-sm me-3" style="border-radius: 8px; padding: 10px 20px; font-weight: 500;">
                                        <i class="fas fa-home me-2"></i>Back to Home
                                    </a>
                                    <a href="customer_portal/login.php" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px; padding: 10px 20px; font-weight: 500; border-width: 2px;">
                                        <i class="fas fa-user me-2"></i>Customer Portal
                                    </a>
                                </div>
                            <?php else: ?>
                                
                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <?php foreach ($errors as $error): ?>
                                                <li><?php echo $error; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" id="bookingForm">
                                    <!-- Personal Information -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h4 class="mb-3"><i class="fas fa-user me-2"></i>Personal Information</h4>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name" class="form-label">First Name *</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                                   value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="last_name" class="form-label">Last Name *</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                                   value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="address" class="form-label">Service Address *</label>
                                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Service Selection -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h4 class="mb-3"><i class="fas fa-tools me-2"></i>Select Service</h4>
                                        </div>
                                        <?php 
                                        $serviceImages = [
                                            'AC Installation' => 'ac-installation.svg',
                                            'AC Cleaning' => 'ac-cleaning.svg',
                                            'AC Repair' => 'ac-repair.svg'
                                        ];
                                        foreach ($services as $service): 
                                            $imageFile = $serviceImages[$service['name']] ?? 'ac-installation.svg';
                                        ?>
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card service-card h-100" onclick="selectService(<?php echo $service['id']; ?>, '<?php echo htmlspecialchars($service['name']); ?>', <?php echo $service['price']; ?>)">
                                                    <div class="card-body text-center">
                                                        <div class="mb-3">
                                                            <img src="assets/images/services/<?php echo $imageFile; ?>" alt="<?php echo htmlspecialchars($service['name']); ?>" class="img-fluid" style="max-height: 100px;">
                                                        </div>
                                                        <h5 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h5>
                                                        <p class="text-muted">Duration: <?php echo $service['duration']; ?> minutes</p>
                                                        <h4 class="text-primary">₱<?php echo number_format($service['price'], 2); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <input type="hidden" id="service_id" name="service_id" required>
                                    </div>
                                    
                                    <!-- Date and Time -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h4 class="mb-3"><i class="fas fa-calendar me-2"></i>Schedule Appointment</h4>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="booking_date" class="form-label">Preferred Date *</label>
                                            <input type="date" class="form-control" id="booking_date" name="booking_date" 
                                                   value="<?php echo $_POST['booking_date'] ?? ''; ?>" 
                                                   min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="preferred_time" class="form-label">Preferred Time *</label>
                                            <select class="form-control" id="preferred_time" name="preferred_time" required>
                                                <option value="">Select Time</option>
                                                <option value="08:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '08:00:00' ? 'selected' : ''; ?>>8:00 AM</option>
                                                <option value="09:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '09:00:00' ? 'selected' : ''; ?>>9:00 AM</option>
                                                <option value="10:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '10:00:00' ? 'selected' : ''; ?>>10:00 AM</option>
                                                <option value="11:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '11:00:00' ? 'selected' : ''; ?>>11:00 AM</option>
                                                <option value="13:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '13:00:00' ? 'selected' : ''; ?>>1:00 PM</option>
                                                <option value="14:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '14:00:00' ? 'selected' : ''; ?>>2:00 PM</option>
                                                <option value="15:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '15:00:00' ? 'selected' : ''; ?>>3:00 PM</option>
                                                <option value="16:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '16:00:00' ? 'selected' : ''; ?>>4:00 PM</option>
                                                <option value="17:00:00" <?php echo ($_POST['preferred_time'] ?? '') === '17:00:00' ? 'selected' : ''; ?>>5:00 PM</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="notes" class="form-label">Additional Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any specific requirements or additional information..."><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" style="border-radius: 10px; font-weight: 500;">
                                            <i class="fas fa-calendar-check me-2"></i>Book Service
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectService(serviceId, serviceName, price) {
            // Remove previous selection
            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection to clicked card
            event.currentTarget.classList.add('selected');
            
            // Set hidden input value
            document.getElementById('service_id').value = serviceId;
            
            // Optional: Show selected service info
            console.log('Selected:', serviceName, 'Price: ₱' + price.toFixed(2));
        }
    </script>
</body>
</html>
