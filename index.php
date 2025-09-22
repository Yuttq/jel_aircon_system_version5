<?php
include 'includes/config.php';
checkAuth();
?>

<?php include 'includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h2">Dashboard</h1>
            <p class="text-muted">Welcome, <?php echo $_SESSION['full_name']; ?>!</p>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Today's Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE()");
                                $stmt->execute();
                                echo $stmt->fetchColumn();
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="assets/images/dashboard/bookings.svg" alt="Bookings" style="width: 60px; height: 60px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pending Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE status = 'pending'");
                                $stmt->execute();
                                echo $stmt->fetchColumn();
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM customers");
                                $stmt->execute();
                                echo $stmt->fetchColumn();
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="assets/images/dashboard/customers.svg" alt="Customers" style="width: 60px; height: 60px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Revenue (Monthly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₱
                                <?php
                                $stmt = $pdo->prepare("
                                    SELECT COALESCE(SUM(p.amount), 0) 
                                    FROM payments p 
                                    JOIN bookings b ON p.booking_id = b.id 
                                    WHERE p.status = 'completed' 
                                    AND MONTH(p.payment_date) = MONTH(CURDATE())
                                    AND YEAR(p.payment_date) = YEAR(CURDATE())
                                ");
                                $stmt->execute();
                                echo number_format($stmt->fetchColumn(), 2);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="assets/images/dashboard/system-overview.svg" alt="Revenue" style="width: 60px; height: 60px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="modules/bookings/add.php" class="btn btn-primary btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 12px 16px; font-weight: 500;">
                                <i class="fas fa-plus me-2"></i>New Booking
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="modules/customers/add.php" class="btn btn-success btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 12px 16px; font-weight: 500;">
                                <i class="fas fa-user-plus me-2"></i>Add Customer
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="modules/technicians/add.php" class="btn btn-info btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 12px 16px; font-weight: 500;">
                                <i class="fas fa-tools me-2"></i>Add Technician
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="modules/services/add.php" class="btn btn-warning btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 12px 16px; font-weight: 500;">
                                <i class="fas fa-cog me-2"></i>Add Service
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="modules/payments/add.php" class="btn btn-secondary btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 12px 16px; font-weight: 500;">
                                <i class="fas fa-credit-card me-2"></i>Record Payment
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="modules/reports/" class="btn btn-dark btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 12px 16px; font-weight: 500;">
                                <i class="fas fa-chart-bar me-2"></i>View Reports
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-2 mb-3">
                            <a href="admin_panel.php" class="btn btn-outline-primary btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-cogs me-2"></i>Admin Panel
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="settings.php" class="btn btn-outline-secondary btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="system_test.php" class="btn btn-outline-info btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-check-circle me-2"></i>System Test
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="admin/email_config.php" class="btn btn-outline-warning btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-envelope me-2"></i>Email Config
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="admin/data_migration.php" class="btn btn-outline-success btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-database me-2"></i>Data Migration
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="admin/view_database.php" class="btn btn-outline-danger btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-eye me-2"></i>Database Viewer
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-2 mb-3">
                            <a href="development_links.php" class="btn btn-outline-dark btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-link me-2"></i>Dev Links
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="test_email_simple.php" class="btn btn-outline-info btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-paper-plane me-2"></i>Email Test
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="test_notifications.php" class="btn btn-outline-warning btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-bell me-2"></i>Test Notifications
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="scheduled_tasks.php" class="btn btn-outline-secondary btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-clock me-2"></i>Scheduled Tasks
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="fix_database.php" class="btn btn-outline-danger btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-database me-2"></i>Fix Database
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="book_service.php" class="btn btn-outline-success btn-block w-100 shadow-sm" style="border-radius: 8px; padding: 10px 16px; font-weight: 500; border-width: 2px;">
                                <i class="fas fa-calendar-plus me-2"></i>Book Service
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Calendar Widget -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Calendar View</h5>
                    <a href="modules/bookings/calendar.php" class="btn btn-sm btn-outline-primary">Full Calendar</a>
                </div>
                <div class="card-body p-0">
                    <div id="mini-calendar"></div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                    <a href="modules/bookings/" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="recentBookings" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Date & Time</th>
                                    <th>Technician</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Since we don't have bookings yet, let's modify this to handle empty state
                                $stmt = $pdo->prepare("
                                    SELECT b.*, c.first_name, c.last_name, s.name as service_name, t.first_name as tech_first, t.last_name as tech_last
                                    FROM bookings b
                                    JOIN customers c ON b.customer_id = c.id
                                    JOIN services s ON b.service_id = s.id
                                    LEFT JOIN technicians t ON b.technician_id = t.id
                                    ORDER BY b.created_at DESC LIMIT 5
                                ");
                                
                                try {
                                    $stmt->execute();
                                    $bookings = $stmt->fetchAll();
                                    
                                    if (count($bookings) > 0) {
                                        foreach ($bookings as $booking) {
                                            echo "<tr>";
                                            echo "<td>{$booking['first_name']} {$booking['last_name']}</td>";
                                            echo "<td>{$booking['service_name']}</td>";
                                            echo "<td>{$booking['booking_date']} {$booking['start_time']}</td>";
                                            echo "<td>";
                                            if ($booking['tech_first']) {
                                                echo "{$booking['tech_first']} {$booking['tech_last']}";
                                            } else {
                                                echo "<span class='text-muted'>Not assigned</span>";
                                            }
                                            echo "</td>";
                                            echo "<td><span class='badge bg-".getStatusBadge($booking['status'])."'>".ucfirst($booking['status'])."</span></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center py-3'>No bookings found. <a href='modules/bookings/add.php'>Create your first booking</a></td></tr>";
                                    }
                                    
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='5' class='text-center py-3'>No bookings data available yet.</td></tr>";
                                }
                                
                                function getStatusBadge($status) {
                                    switch ($status) {
                                        case 'pending': return 'warning';
                                        case 'confirmed': return 'info';
                                        case 'in-progress': return 'primary';
                                        case 'completed': return 'success';
                                        case 'cancelled': return 'danger';
                                        default: return 'secondary';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mini Calendar Widget
document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('mini-calendar');
    if (calendar) {
        // Create a simple calendar widget
        const today = new Date();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();
        
        // Get bookings for current month
        fetch('modules/bookings/get_calendar_data.php?month=' + (currentMonth + 1) + '&year=' + currentYear)
            .then(response => response.json())
            .then(data => {
                renderMiniCalendar(calendar, currentMonth, currentYear, data.bookings || []);
            })
            .catch(error => {
                console.log('Calendar data not available, showing basic calendar');
                renderMiniCalendar(calendar, currentMonth, currentYear, []);
            });
    }
});

function renderMiniCalendar(container, month, year, bookings) {
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();
    
    let html = `
        <div class="calendar-header text-center py-2 bg-light">
            <h6 class="mb-0">${monthNames[month]} ${year}</h6>
        </div>
        <div class="calendar-grid">
            <div class="calendar-weekdays">
                <div class="calendar-day-header">S</div>
                <div class="calendar-day-header">M</div>
                <div class="calendar-day-header">T</div>
                <div class="calendar-day-header">W</div>
                <div class="calendar-day-header">T</div>
                <div class="calendar-day-header">F</div>
                <div class="calendar-day-header">S</div>
            </div>
            <div class="calendar-days">
    `;
    
    // Empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        html += '<div class="calendar-day empty"></div>';
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const hasBooking = bookings.some(booking => booking.booking_date === dateStr);
        const isToday = day === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear();
        
        html += `<div class="calendar-day ${isToday ? 'today' : ''} ${hasBooking ? 'has-booking' : ''}" data-date="${dateStr}">
            ${day}
        </div>`;
    }
    
    html += `
            </div>
        </div>
    `;
    
    container.innerHTML = html;
    
    // Add click handlers
    container.querySelectorAll('.calendar-day.has-booking').forEach(day => {
        day.addEventListener('click', function() {
            const date = this.dataset.date;
            window.location.href = `modules/bookings/calendar.php?date=${date}`;
        });
    });
}

// Add calendar styles
const style = document.createElement('style');
style.textContent = `
    .calendar-grid {
        padding: 10px;
    }
    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        margin-bottom: 5px;
    }
    .calendar-day-header {
        text-align: center;
        font-weight: 600;
        font-size: 12px;
        color: #666;
        padding: 5px;
    }
    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
    }
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        border-radius: 3px;
        transition: all 0.2s ease;
    }
    .calendar-day:hover {
        background-color: #f8f9fa;
    }
    .calendar-day.today {
        background-color: #2563eb;
        color: white;
        font-weight: 600;
    }
    .calendar-day.has-booking {
        background-color: #10b981;
        color: white;
        font-weight: 500;
    }
    .calendar-day.has-booking:hover {
        background-color: #059669;
    }
    .calendar-day.empty {
        cursor: default;
    }
`;
document.head.appendChild(style);
</script>

<?php include 'includes/footer.php'; ?>