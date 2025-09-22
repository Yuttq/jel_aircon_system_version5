<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/notifications.php';

// Check if user is logged in and has admin/manager role
if (!isLoggedIn() || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header('Location: ../../login.php');
    exit();
}

$notificationSystem = new NotificationSystem($pdo);

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'send_reminder':
                $bookingId = (int)$_POST['booking_id'];
                $result = $notificationSystem->sendReminder($bookingId);
                $message = $result ? 'Reminder sent successfully!' : 'Failed to send reminder.';
                break;
                
            case 'send_confirmation':
                $bookingId = (int)$_POST['booking_id'];
                $result = $notificationSystem->sendBookingConfirmation($bookingId);
                $message = $result ? 'Confirmation sent successfully!' : 'Failed to send confirmation.';
                break;
                
            case 'test_notification':
                $bookingId = (int)$_POST['booking_id'];
                $type = $_POST['notification_type'];
                $result = false;
                
                switch ($type) {
                    case 'confirmation':
                        $result = $notificationSystem->sendBookingConfirmation($bookingId);
                        break;
                    case 'reminder':
                        $result = $notificationSystem->sendReminder($bookingId);
                        break;
                    case 'status_update':
                        $result = $notificationSystem->sendStatusUpdate($bookingId, 'confirmed');
                        break;
                }
                
                $message = $result ? 'Test notification sent successfully!' : 'Failed to send test notification.';
                break;
        }
    }
}

// Get notification statistics
$stats = getNotificationStats($pdo);

// Get recent notifications
$recentNotifications = getRecentNotifications($pdo);

// Get bookings that need reminders
$pendingReminders = getBookingsForReminder($pdo);

function getNotificationStats($pdo) {
    try {
        $stats = [];
        
        // Total notifications sent today
        $sql = "SELECT COUNT(*) as count FROM notifications WHERE DATE(sent_at) = CURDATE()";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $stats['today'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total notifications sent this week
        $sql = "SELECT COUNT(*) as count FROM notifications WHERE sent_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $stats['week'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Failed notifications
        $sql = "SELECT COUNT(*) as count FROM notifications WHERE notes LIKE '%failed%' AND sent_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $stats['failed'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Notifications by type
        $sql = "SELECT type, COUNT(*) as count FROM notifications WHERE sent_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $stats['by_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    } catch (Exception $e) {
        return ['today' => 0, 'week' => 0, 'failed' => 0, 'by_type' => []];
    }
}

function getRecentNotifications($pdo) {
    try {
        $sql = "SELECT n.*, b.id as booking_id, c.first_name, c.last_name, s.name as service_name
                FROM notifications n
                JOIN bookings b ON n.booking_id = b.id
                JOIN customers c ON b.customer_id = c.id
                JOIN services s ON b.service_id = s.id
                ORDER BY n.sent_at DESC
                LIMIT 20";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function getBookingsForReminder($pdo) {
    try {
        $sql = "SELECT b.*, c.first_name, c.last_name, c.email, s.name as service_name,
                       CONCAT(c.first_name, ' ', c.last_name) as customer_name
                FROM bookings b 
                JOIN customers c ON b.customer_id = c.id 
                JOIN services s ON b.service_id = s.id
                WHERE b.status IN ('confirmed', 'pending') 
                AND b.reminder_sent = 0
                AND b.booking_date >= CURDATE()
                AND b.booking_date <= DATE_ADD(CURDATE(), INTERVAL 2 DAY)
                ORDER BY b.booking_date, b.start_time";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Management - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include '../../includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-bell"></i> Notification Management</h1>
                </div>
                
                <?php if (isset($message)): ?>
                    <div class="alert alert-<?php echo strpos($message, 'successfully') !== false ? 'success' : 'danger'; ?> alert-dismissible fade show">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo $stats['today']; ?></h4>
                                        <p class="card-text">Sent Today</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-paper-plane fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo $stats['week']; ?></h4>
                                        <p class="card-text">This Week</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo count($pendingReminders); ?></h4>
                                        <p class="card-text">Pending Reminders</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title"><?php echo $stats['failed']; ?></h4>
                                        <p class="card-text">Failed (7 days)</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Reminders -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-clock"></i> Pending Reminders</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($pendingReminders)): ?>
                                    <p class="text-muted">No pending reminders at this time.</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Service</th>
                                                    <th>Date & Time</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pendingReminders as $booking): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                                        <td>
                                                            <?php echo date('M j, Y', strtotime($booking['booking_date'])); ?><br>
                                                            <small class="text-muted"><?php echo date('g:i A', strtotime($booking['start_time'])); ?></small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $booking['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                                                <?php echo ucfirst($booking['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="action" value="send_reminder">
                                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                                <button type="submit" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-bell"></i> Send Reminder
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Notifications -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-history"></i> Recent Notifications</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Customer</th>
                                                <th>Service</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentNotifications as $notification): ?>
                                                <tr>
                                                    <td><?php echo date('M j, Y g:i A', strtotime($notification['sent_at'])); ?></td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            <?php echo ucfirst(str_replace('_', ' ', $notification['type'])); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($notification['first_name'] . ' ' . $notification['last_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($notification['service_name']); ?></td>
                                                    <td>
                                                        <?php if ($notification['email_sent']): ?>
                                                            <span class="badge bg-success">Sent</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Failed</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="action" value="test_notification">
                                                            <input type="hidden" name="booking_id" value="<?php echo $notification['booking_id']; ?>">
                                                            <input type="hidden" name="notification_type" value="<?php echo $notification['type']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-redo"></i> Resend
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
