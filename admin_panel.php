<?php
/**
 * Comprehensive Admin Panel - JEL Air Conditioning Services
 * Centralized access to all admin tools and settings
 */

require_once 'includes/config.php';
checkAuth();

// Check if user is admin
if (!hasRole('admin')) {
    die('Access denied. Admin privileges required.');
}

// Get system statistics
$stats = [];
$stats['customers'] = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$stats['technicians'] = $pdo->query("SELECT COUNT(*) FROM technicians")->fetchColumn();
$stats['services'] = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$stats['bookings'] = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$stats['payments'] = $pdo->query("SELECT COUNT(*) FROM payments")->fetchColumn();
$stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Get recent activity
$recentBookings = $pdo->query("
    SELECT b.*, c.first_name, c.last_name, s.name as service_name 
    FROM bookings b 
    LEFT JOIN customers c ON b.customer_id = c.id 
    LEFT JOIN services s ON b.service_id = s.id 
    ORDER BY b.created_at DESC LIMIT 5
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - JEL Air Conditioning Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .tool-card {
            border-left: 4px solid #007bff;
        }
        .settings-card {
            border-left: 4px solid #28a745;
        }
        .maintenance-card {
            border-left: 4px solid #ffc107;
        }
        .reports-card {
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2">Admin Panel</h1>
                        <p class="text-muted">Complete system management and configuration</p>
                    </div>
                    <div>
                        <a href="index.php" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Statistics -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card stat-card admin-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h3><?php echo $stats['customers']; ?></h3>
                        <p class="mb-0">Customers</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card stat-card admin-card">
                    <div class="card-body text-center">
                        <i class="fas fa-tools fa-2x mb-2"></i>
                        <h3><?php echo $stats['technicians']; ?></h3>
                        <p class="mb-0">Technicians</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card stat-card admin-card">
                    <div class="card-body text-center">
                        <i class="fas fa-cog fa-2x mb-2"></i>
                        <h3><?php echo $stats['services']; ?></h3>
                        <p class="mb-0">Services</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card stat-card admin-card">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar fa-2x mb-2"></i>
                        <h3><?php echo $stats['bookings']; ?></h3>
                        <p class="mb-0">Bookings</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card stat-card admin-card">
                    <div class="card-body text-center">
                        <i class="fas fa-credit-card fa-2x mb-2"></i>
                        <h3><?php echo $stats['payments']; ?></h3>
                        <p class="mb-0">Payments</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card stat-card admin-card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-shield fa-2x mb-2"></i>
                        <h3><?php echo $stats['users']; ?></h3>
                        <p class="mb-0">Users</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card admin-card">
                    <div class="card-header">
                        <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <a href="modules/bookings/add.php" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2"></i>New Booking
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/customers/add.php" class="btn btn-success w-100">
                                    <i class="fas fa-user-plus me-2"></i>Add Customer
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/technicians/add.php" class="btn btn-info w-100">
                                    <i class="fas fa-tools me-2"></i>Add Technician
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/services/add.php" class="btn btn-warning w-100">
                                    <i class="fas fa-cog me-2"></i>Add Service
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/payments/add.php" class="btn btn-secondary w-100">
                                    <i class="fas fa-credit-card me-2"></i>Record Payment
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/reports/" class="btn btn-dark w-100">
                                    <i class="fas fa-chart-bar me-2"></i>View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Tools -->
        <div class="row mb-4">
            <!-- System Settings -->
            <div class="col-lg-6 mb-4">
                <div class="card settings-card admin-card">
                    <div class="card-header">
                        <h5><i class="fas fa-cog me-2"></i>System Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="admin/email_config.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-envelope me-2"></i>Email Configuration
                                <span class="badge bg-primary float-end">SMTP</span>
                            </a>
                            <a href="admin/data_migration.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-database me-2"></i>Data Migration
                                <span class="badge bg-info float-end">Import</span>
                            </a>
                            <a href="admin/view_database.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-eye me-2"></i>Database Viewer
                                <span class="badge bg-success float-end">View</span>
                            </a>
                            <a href="test_email_simple.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-paper-plane me-2"></i>Test Email (SMTP)
                                <span class="badge bg-warning float-end">Test</span>
                            </a>
                            <a href="test_notifications.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-bell me-2"></i>Test Notifications
                                <span class="badge bg-info float-end">Test</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Maintenance -->
            <div class="col-lg-6 mb-4">
                <div class="card maintenance-card admin-card">
                    <div class="card-header">
                        <h5><i class="fas fa-wrench me-2"></i>System Maintenance</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="system_test.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-check-circle me-2"></i>System Test
                                <span class="badge bg-success float-end">Health</span>
                            </a>
                            <a href="fix_database.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-database me-2"></i>Database Fix
                                <span class="badge bg-warning float-end">Repair</span>
                            </a>
                            <a href="scheduled_tasks.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-clock me-2"></i>Scheduled Tasks
                                <span class="badge bg-info float-end">Cron</span>
                            </a>
                            <a href="modules/notifications/" class="list-group-item list-group-item-action">
                                <i class="fas fa-bell me-2"></i>Notification Logs
                                <span class="badge bg-secondary float-end">Logs</span>
                            </a>
                            <a href="admin/test_email.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-envelope-open me-2"></i>Email Test (Admin)
                                <span class="badge bg-primary float-end">Admin</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Development & Deployment -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card reports-card admin-card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Reports & Analytics</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="modules/reports/bookings.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-calendar me-2"></i>Booking Reports
                            </a>
                            <a href="modules/reports/customers.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-users me-2"></i>Customer Analytics
                            </a>
                            <a href="modules/reports/revenue.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-peso-sign me-2"></i>Revenue Reports
                            </a>
                            <a href="modules/reports/technicians.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-tools me-2"></i>Technician Reports
                            </a>
                            <a href="modules/reports/services.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-cog me-2"></i>Service Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card tool-card admin-card">
                    <div class="card-header">
                        <h5><i class="fas fa-code me-2"></i>Development & Deployment</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="PRODUCTION_DEPLOYMENT_CHECKLIST.md" class="list-group-item list-group-item-action" target="_blank">
                                <i class="fas fa-list-check me-2"></i>Production Checklist
                                <span class="badge bg-danger float-end">PDF</span>
                            </a>
                            <a href="PHASE_6_DEPLOYMENT_GUIDE.md" class="list-group-item list-group-item-action" target="_blank">
                                <i class="fas fa-rocket me-2"></i>Deployment Guide
                                <span class="badge bg-info float-end">Guide</span>
                            </a>
                            <a href="USER_TRAINING_GUIDE.md" class="list-group-item list-group-item-action" target="_blank">
                                <i class="fas fa-graduation-cap me-2"></i>User Training Guide
                                <span class="badge bg-success float-end">Training</span>
                            </a>
                            <a href="GMAIL_SETUP_GUIDE.md" class="list-group-item list-group-item-action" target="_blank">
                                <i class="fas fa-envelope me-2"></i>Gmail Setup Guide
                                <span class="badge bg-warning float-end">Setup</span>
                            </a>
                            <a href="index_public.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-globe me-2"></i>Public Website
                                <span class="badge bg-primary float-end">Public</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-12">
                <div class="card admin-card">
                    <div class="card-header">
                        <h5><i class="fas fa-history me-2"></i>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($recentBookings) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Customer</th>
                                            <th>Service</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentBookings as $booking): ?>
                                            <tr>
                                                <td>#<?php echo $booking['id']; ?></td>
                                                <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                                <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        switch ($booking['status']) {
                                                            case 'pending': return 'warning';
                                                            case 'confirmed': return 'info';
                                                            case 'in-progress': return 'primary';
                                                            case 'completed': return 'success';
                                                            case 'cancelled': return 'danger';
                                                            default: return 'secondary';
                                                        }
                                                    ?>">
                                                        <?php echo ucfirst($booking['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M j, Y g:i A', strtotime($booking['created_at'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5>No recent activity</h5>
                                <p class="text-muted">No bookings have been created yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links Footer -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card admin-card">
                    <div class="card-header">
                        <h5><i class="fas fa-link me-2"></i>Quick Access Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <strong>System Management:</strong><br>
                                <a href="admin/email_config.php" class="text-decoration-none">Email Config</a><br>
                                <a href="admin/data_migration.php" class="text-decoration-none">Data Migration</a><br>
                                <a href="admin/view_database.php" class="text-decoration-none">Database Viewer</a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>Testing & Maintenance:</strong><br>
                                <a href="system_test.php" class="text-decoration-none">System Test</a><br>
                                <a href="test_email_simple.php" class="text-decoration-none">Email Test</a><br>
                                <a href="fix_database.php" class="text-decoration-none">Database Fix</a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>Documentation:</strong><br>
                                <a href="PRODUCTION_DEPLOYMENT_CHECKLIST.md" class="text-decoration-none" target="_blank">Production Checklist</a><br>
                                <a href="USER_TRAINING_GUIDE.md" class="text-decoration-none" target="_blank">User Guide</a><br>
                                <a href="GMAIL_SETUP_GUIDE.md" class="text-decoration-none" target="_blank">Gmail Setup</a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>Public Access:</strong><br>
                                <a href="index_public.php" class="text-decoration-none">Public Website</a><br>
                                <a href="customer_portal/" class="text-decoration-none">Customer Portal</a><br>
                                <a href="book_service.php" class="text-decoration-none">Book Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
