<?php
/**
 * Test Script for JEL Air Conditioning Notification System
 * This script tests all notification types to ensure they work correctly
 * 
 * Usage: Run this script from your browser or command line
 * Make sure you have test data in your database before running
 */

require_once 'includes/config.php';
require_once 'includes/notifications.php';

// Set execution time limit
set_time_limit(60);

echo "<h1>JEL Air Conditioning - Notification System Test</h1>";
echo "<hr>";

try {
    // Initialize notification system
    $notificationSystem = new NotificationSystem($pdo);
    
    // Test 1: Check if we have test data
    echo "<h2>Test 1: Checking for Test Data</h2>";
    
    $testBookingStmt = $pdo->prepare("
        SELECT b.id, c.first_name, c.last_name, c.email, s.name as service_name
        FROM bookings b 
        JOIN customers c ON b.customer_id = c.id 
        JOIN services s ON b.service_id = s.id 
        LIMIT 1
    ");
    $testBookingStmt->execute();
    $testBooking = $testBookingStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$testBooking) {
        echo "<p style='color: red;'>❌ No test booking found. Please create a booking first.</p>";
        echo "<p>You can create a test booking through the admin panel or add sample data to your database.</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ Test booking found: Booking #{$testBooking['id']} - {$testBooking['first_name']} {$testBooking['last_name']}</p>";
    echo "<p>Customer Email: {$testBooking['email']}</p>";
    echo "<p>Service: {$testBooking['service_name']}</p>";
    
    $testBookingId = $testBooking['id'];
    
    // Test 2: Test Booking Confirmation
    echo "<h2>Test 2: Booking Confirmation</h2>";
    $result = $notificationSystem->sendBookingConfirmation($testBookingId);
    if ($result) {
        echo "<p style='color: green;'>✅ Booking confirmation sent successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to send booking confirmation</p>";
    }
    
    // Test 3: Test Status Update
    echo "<h2>Test 3: Status Update</h2>";
    $result = $notificationSystem->sendStatusUpdate($testBookingId, 'confirmed');
    if ($result) {
        echo "<p style='color: green;'>✅ Status update sent successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to send status update</p>";
    }
    
    // Test 4: Test Payment Confirmation
    echo "<h2>Test 4: Payment Confirmation</h2>";
    $result = $notificationSystem->sendPaymentConfirmation($testBookingId, 1500.00);
    if ($result) {
        echo "<p style='color: green;'>✅ Payment confirmation sent successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to send payment confirmation</p>";
    }
    
    // Test 5: Test Technician Assignment (if technician exists)
    echo "<h2>Test 5: Technician Assignment</h2>";
    $techStmt = $pdo->prepare("SELECT id FROM technicians LIMIT 1");
    $techStmt->execute();
    $testTech = $techStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($testTech) {
        $result = $notificationSystem->sendTechnicianAssignment($testBookingId, $testTech['id']);
        if ($result) {
            echo "<p style='color: green;'>✅ Technician assignment sent successfully!</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to send technician assignment</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ No technicians found. Skipping technician assignment test.</p>";
    }
    
    // Test 6: Test Reminder
    echo "<h2>Test 6: Booking Reminder</h2>";
    $result = $notificationSystem->sendReminder($testBookingId);
    if ($result) {
        echo "<p style='color: green;'>✅ Booking reminder sent successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to send booking reminder</p>";
    }
    
    // Test 7: Check Notification Logs
    echo "<h2>Test 7: Notification Logs</h2>";
    $logStmt = $pdo->prepare("
        SELECT type, email_sent, sent_at, notes 
        FROM notifications 
        WHERE booking_id = ? 
        ORDER BY sent_at DESC 
        LIMIT 5
    ");
    $logStmt->execute([$testBookingId]);
    $logs = $logStmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($logs) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Type</th><th>Email Sent</th><th>Sent At</th><th>Notes</th></tr>";
        foreach ($logs as $log) {
            $status = $log['email_sent'] ? '✅ Sent' : '❌ Failed';
            echo "<tr>";
            echo "<td>" . ucfirst(str_replace('_', ' ', $log['type'])) . "</td>";
            echo "<td>$status</td>";
            echo "<td>" . $log['sent_at'] . "</td>";
            echo "<td>" . htmlspecialchars($log['notes']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>❌ No notification logs found</p>";
    }
    
    // Test 8: Test Pending Reminders
    echo "<h2>Test 8: Pending Reminders</h2>";
    $pendingReminders = $notificationSystem->getBookingsForReminder();
    echo "<p>Found " . count($pendingReminders) . " bookings that need reminders</p>";
    
    if (count($pendingReminders) > 0) {
        echo "<p style='color: green;'>✅ Reminder system is working correctly</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ No pending reminders found (this is normal if no bookings are scheduled for tomorrow)</p>";
    }
    
    // Test 9: Configuration Check
    echo "<h2>Test 9: Configuration Check</h2>";
    echo "<p>Email Notifications: " . (EMAIL_NOTIFICATIONS ? '✅ Enabled' : '❌ Disabled') . "</p>";
    echo "<p>Notification System: " . (NOTIFICATION_ENABLED ? '✅ Enabled' : '❌ Disabled') . "</p>";
    echo "<p>Auto Reminders: " . (AUTO_REMINDERS_ENABLED ? '✅ Enabled' : '❌ Disabled') . "</p>";
    echo "<p>SMTP Host: " . SMTP_HOST . "</p>";
    echo "<p>Email From: " . EMAIL_FROM . "</p>";
    
    // Test 10: Template Check
    echo "<h2>Test 10: Email Template Check</h2>";
    $templates = [
        'booking_confirmation' => 'templates/emails/booking_confirmation.html',
        'booking_reminder' => 'templates/emails/booking_reminder.html',
        'status_update' => 'templates/emails/status_update.html',
        'payment_confirmation' => 'templates/emails/payment_confirmation.html',
        'technician_assignment' => 'templates/emails/technician_assignment.html',
        'booking_cancelled' => 'templates/emails/booking_cancelled.html',
        'service_completed' => 'templates/emails/service_completed.html'
    ];
    
    foreach ($templates as $name => $path) {
        if (file_exists($path)) {
            echo "<p style='color: green;'>✅ $name template exists</p>";
        } else {
            echo "<p style='color: red;'>❌ $name template missing: $path</p>";
        }
    }
    
    echo "<hr>";
    echo "<h2>Test Summary</h2>";
    echo "<p>All notification tests completed. Check the results above for any issues.</p>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>Configure your SMTP settings in includes/notification_config.php</li>";
    echo "<li>Set up automated reminders using scheduled_tasks.php</li>";
    echo "<li>Test with real email addresses</li>";
    echo "<li>Monitor notification logs through the admin dashboard</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error during testing: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><em>Test completed at " . date('Y-m-d H:i:s') . "</em></p>";
?>
