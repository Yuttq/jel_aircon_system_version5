<?php
/**
 * Development Links - JEL Air Conditioning Services
 * Comprehensive list of all development and management tools
 */

require_once 'includes/config.php';
checkAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development Links - JEL Air Conditioning Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .link-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .link-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .category-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
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
                        <h1 class="h2">Development Links</h1>
                        <p class="text-muted">Complete access to all system tools and resources</p>
                    </div>
                    <div>
                        <a href="index.php" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main System Access -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card link-card">
                    <div class="card-header category-header">
                        <h5><i class="fas fa-home me-2"></i>Main System Access</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="index.php" class="btn btn-primary w-100">
                                    <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                </a>
                                <small class="text-muted d-block mt-1">Main management interface</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="admin_panel.php" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-cogs me-2"></i>Admin Panel
                                </a>
                                <small class="text-muted d-block mt-1">Comprehensive admin tools</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="customer_portal/" class="btn btn-success w-100">
                                    <i class="fas fa-users me-2"></i>Customer Portal
                                </a>
                                <small class="text-muted d-block mt-1">Customer self-service portal</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index_public.php" class="btn btn-info w-100">
                                    <i class="fas fa-globe me-2"></i>Public Website
                                </a>
                                <small class="text-muted d-block mt-1">Public-facing website</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Configuration -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card link-card">
                    <div class="card-header category-header">
                        <h5><i class="fas fa-cog me-2"></i>System Configuration & Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="settings.php" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-cog me-2"></i>System Settings
                                </a>
                                <small class="text-muted d-block mt-1">Business info & notifications</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="admin/email_config.php" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-envelope me-2"></i>Email Configuration
                                </a>
                                <small class="text-muted d-block mt-1">SMTP settings & email setup</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="admin/data_migration.php" class="btn btn-outline-success w-100">
                                    <i class="fas fa-database me-2"></i>Data Migration
                                </a>
                                <small class="text-muted d-block mt-1">Import data from Excel/CSV</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="admin/view_database.php" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-eye me-2"></i>Database Viewer
                                </a>
                                <small class="text-muted d-block mt-1">View current database data</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testing & Maintenance -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card link-card">
                    <div class="card-header category-header">
                        <h5><i class="fas fa-wrench me-2"></i>Testing & Maintenance</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="system_test.php" class="btn btn-outline-info w-100">
                                    <i class="fas fa-check-circle me-2"></i>System Test
                                </a>
                                <small class="text-muted d-block mt-1">Comprehensive system health check</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="test_email_simple.php" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Email Test (SMTP)
                                </a>
                                <small class="text-muted d-block mt-1">Test SMTP email functionality</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="test_notifications.php" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-bell me-2"></i>Notification Test
                                </a>
                                <small class="text-muted d-block mt-1">Test all notification types</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="fix_database.php" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-database me-2"></i>Database Fix
                                </a>
                                <small class="text-muted d-block mt-1">Repair database issues</small>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3 mb-3">
                                <a href="scheduled_tasks.php" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-clock me-2"></i>Scheduled Tasks
                                </a>
                                <small class="text-muted d-block mt-1">Cron job management</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="admin/test_email.php" class="btn btn-outline-info w-100">
                                    <i class="fas fa-envelope-open me-2"></i>Email Test (Admin)
                                </a>
                                <small class="text-muted d-block mt-1">Admin email testing tool</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="modules/notifications/" class="btn btn-outline-success w-100">
                                    <i class="fas fa-bell me-2"></i>Notification Logs
                                </a>
                                <small class="text-muted d-block mt-1">View notification history</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="book_service.php" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-calendar-plus me-2"></i>Book Service
                                </a>
                                <small class="text-muted d-block mt-1">Public booking form</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentation & Guides -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card link-card">
                    <div class="card-header category-header">
                        <h5><i class="fas fa-book me-2"></i>Documentation & Guides</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="PRODUCTION_DEPLOYMENT_CHECKLIST.md" class="btn btn-outline-danger w-100" target="_blank">
                                    <i class="fas fa-list-check me-2"></i>Production Checklist
                                </a>
                                <small class="text-muted d-block mt-1">Complete deployment checklist</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="PHASE_6_DEPLOYMENT_GUIDE.md" class="btn btn-outline-info w-100" target="_blank">
                                    <i class="fas fa-rocket me-2"></i>Deployment Guide
                                </a>
                                <small class="text-muted d-block mt-1">Step-by-step deployment</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="USER_TRAINING_GUIDE.md" class="btn btn-outline-success w-100" target="_blank">
                                    <i class="fas fa-graduation-cap me-2"></i>User Training Guide
                                </a>
                                <small class="text-muted d-block mt-1">Complete user manual</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="GMAIL_SETUP_GUIDE.md" class="btn btn-outline-warning w-100" target="_blank">
                                    <i class="fas fa-envelope me-2"></i>Gmail Setup Guide
                                </a>
                                <small class="text-muted d-block mt-1">Email configuration guide</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Management -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card link-card">
                    <div class="card-header category-header">
                        <h5><i class="fas fa-puzzle-piece me-2"></i>Module Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <a href="modules/bookings/" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-calendar me-2"></i>Bookings
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/bookings/calendar.php" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-calendar-alt me-2"></i>Booking Calendar
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/customers/" class="btn btn-outline-success w-100">
                                    <i class="fas fa-users me-2"></i>Customers
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/technicians/" class="btn btn-outline-info w-100">
                                    <i class="fas fa-tools me-2"></i>Technicians
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/services/" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-cog me-2"></i>Services
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/payments/" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-credit-card me-2"></i>Payments
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <a href="modules/reports/" class="btn btn-outline-dark w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Reports
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="modules/notifications/" class="btn btn-outline-info w-100">
                                    <i class="fas fa-bell me-2"></i>Notifications
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access URLs -->
        <div class="row">
            <div class="col-12">
                <div class="card link-card">
                    <div class="card-header category-header">
                        <h5><i class="fas fa-link me-2"></i>Quick Access URLs</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Main System URLs:</h6>
                                <ul class="list-unstyled">
                                    <li><code>http://localhost/jel_aircon_system/</code> - Admin Dashboard</li>
                                    <li><code>http://localhost/jel_aircon_system/admin_panel.php</code> - Admin Panel</li>
                                    <li><code>http://localhost/jel_aircon_system/customer_portal/</code> - Customer Portal</li>
                                    <li><code>http://localhost/jel_aircon_system/index_public.php</code> - Public Website</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Configuration URLs:</h6>
                                <ul class="list-unstyled">
                                    <li><code>http://localhost/jel_aircon_system/admin/email_config.php</code> - Email Config</li>
                                    <li><code>http://localhost/jel_aircon_system/admin/data_migration.php</code> - Data Migration</li>
                                    <li><code>http://localhost/jel_aircon_system/admin/view_database.php</code> - Database Viewer</li>
                                    <li><code>http://localhost/jel_aircon_system/system_test.php</code> - System Test</li>
                                </ul>
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
