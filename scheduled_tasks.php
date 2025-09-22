<?php
/**
 * Scheduled Tasks for JEL Air Conditioning Services
 * This file handles automated tasks like sending reminders
 * 
 * To set up automated execution:
 * 1. Add this to your crontab (Linux/Mac): 0 * * * * /usr/bin/php /path/to/your/project/scheduled_tasks.php
 * 2. Or use Windows Task Scheduler to run this file every hour
 * 3. Or set up a web cron service to call this URL periodically
 */

require_once 'includes/config.php';
require_once 'includes/notifications.php';

// Set execution time limit
set_time_limit(300); // 5 minutes

// Log function
function logTask($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    file_put_contents('logs/scheduled_tasks.log', $logMessage, FILE_APPEND | LOCK_EX);
    echo $logMessage;
}

// Create logs directory if it doesn't exist
if (!file_exists('logs')) {
    mkdir('logs', 0755, true);
}

logTask("Starting scheduled tasks execution");

try {
    // Initialize notification system
    $notificationSystem = new NotificationSystem($pdo);
    
    // Task 1: Send booking reminders
    logTask("Checking for bookings that need reminders...");
    $remindersSent = $notificationSystem->sendPendingReminders();
    logTask("Sent $remindersSent reminder notifications");
    
    // Task 2: Check for overdue bookings (optional)
    logTask("Checking for overdue bookings...");
    $overdueBookings = checkOverdueBookings($pdo);
    logTask("Found " . count($overdueBookings) . " overdue bookings");
    
    // Task 3: Clean up old notification logs (optional)
    logTask("Cleaning up old notification logs...");
    $cleanedLogs = cleanupOldLogs($pdo);
    logTask("Cleaned up $cleanedLogs old notification logs");
    
    logTask("Scheduled tasks completed successfully");
    
} catch (Exception $e) {
    logTask("Error in scheduled tasks: " . $e->getMessage());
    error_log("Scheduled tasks error: " . $e->getMessage());
}

/**
 * Check for overdue bookings
 */
function checkOverdueBookings($pdo) {
    try {
        $sql = "SELECT b.*, c.first_name, c.last_name, c.email, s.name as service_name
                FROM bookings b 
                JOIN customers c ON b.customer_id = c.id 
                JOIN services s ON b.service_id = s.id
                WHERE b.status IN ('pending', 'confirmed') 
                AND b.booking_date < CURDATE()";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        logTask("Error checking overdue bookings: " . $e->getMessage());
        return [];
    }
}

/**
 * Clean up old notification logs (older than 90 days)
 */
function cleanupOldLogs($pdo) {
    try {
        $sql = "DELETE FROM notifications WHERE sent_at < DATE_SUB(NOW(), INTERVAL 90 DAY)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->rowCount();
    } catch (Exception $e) {
        logTask("Error cleaning up old logs: " . $e->getMessage());
        return 0;
    }
}

/**
 * Send test notification (for testing purposes)
 * Uncomment the lines below to test the notification system
 */
/*
try {
    $notificationSystem = new NotificationSystem($pdo);
    
    // Test with a specific booking ID (replace with actual booking ID)
    $testBookingId = 1;
    
    logTask("Sending test booking confirmation...");
    $result = $notificationSystem->sendBookingConfirmation($testBookingId);
    logTask("Test booking confirmation result: " . ($result ? "Success" : "Failed"));
    
} catch (Exception $e) {
    logTask("Test notification error: " . $e->getMessage());
}
*/

logTask("Scheduled tasks execution finished");
?>