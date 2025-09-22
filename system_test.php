<?php
/**
 * System Test Script for JEL Air Conditioning Services
 * This script tests all major components of the system
 */

require_once 'includes/config.php';

echo "<h1>JEL Air Conditioning System Test</h1>";

// Test 1: Database Connection
echo "<h2>1. Database Connection Test</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers");
    $result = $stmt->fetch();
    echo "✅ Database connection successful. Found {$result['count']} customers.<br>";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}

// Test 2: Authentication System
echo "<h2>2. Authentication System Test</h2>";
if (function_exists('login')) {
    echo "✅ Authentication functions loaded.<br>";
} else {
    echo "❌ Authentication functions not found.<br>";
}

// Test 3: Email Configuration
echo "<h2>3. Email Configuration Test</h2>";
if (defined('SMTP_HOST') && defined('SMTP_USERNAME')) {
    echo "✅ Email configuration loaded.<br>";
    echo "SMTP Host: " . SMTP_HOST . "<br>";
    echo "SMTP Username: " . SMTP_USERNAME . "<br>";
    echo "SMTP Port: " . (defined('SMTP_PORT') ? SMTP_PORT : 'Not set') . "<br>";
    echo "SMTP Encryption: " . (defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'Not set') . "<br>";
} else {
    echo "❌ Email configuration not found.<br>";
}

// Test 4: Database Tables
echo "<h2>4. Database Tables Test</h2>";
$tables = ['users', 'customers', 'services', 'technicians', 'bookings', 'payments', 'feedback', 'notifications'];
foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch();
        echo "✅ Table '$table' exists with {$result['count']} records.<br>";
    } catch (Exception $e) {
        echo "❌ Table '$table' error: " . $e->getMessage() . "<br>";
    }
}

// Test 5: File Permissions
echo "<h2>5. File Permissions Test</h2>";
$directories = ['assets', 'templates', 'emails', 'logs'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✅ Directory '$dir' is writable.<br>";
        } else {
            echo "⚠️ Directory '$dir' exists but is not writable.<br>";
        }
    } else {
        echo "❌ Directory '$dir' does not exist.<br>";
    }
}

// Test 6: Sample Data
echo "<h2>6. Sample Data Test</h2>";
try {
    // Check for default admin user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    if ($admin) {
        echo "✅ Default admin user exists.<br>";
    } else {
        echo "❌ Default admin user not found.<br>";
    }
    
    // Check for sample services
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM services");
    $result = $stmt->fetch();
    if ($result['count'] > 0) {
        echo "✅ Sample services found ({$result['count']} services).<br>";
    } else {
        echo "❌ No services found.<br>";
    }
} catch (Exception $e) {
    echo "❌ Sample data test failed: " . $e->getMessage() . "<br>";
}

// Test 7: System URLs
echo "<h2>7. System URLs Test</h2>";
$base_url = BASE_URL;
echo "✅ Base URL configured: $base_url<br>";
echo "Main System: <a href='$base_url'>$base_url</a><br>";
echo "Customer Portal: <a href='{$base_url}customer_portal/'>{$base_url}customer_portal/</a><br>";
echo "Data Migration: <a href='{$base_url}data_migration.php'>{$base_url}data_migration.php</a><br>";

// Test 8: PHP Configuration
echo "<h2>8. PHP Configuration Test</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Available' : '❌ Not available') . "<br>";
echo "Session Support: " . (function_exists('session_start') ? '✅ Available' : '❌ Not available') . "<br>";
echo "Mail Function: " . (function_exists('mail') ? '✅ Available' : '❌ Not available') . "<br>";

echo "<h2>Test Complete!</h2>";
echo "<p>If all tests show ✅, your system is ready to use.</p>";
echo "<p><a href='index.php'>Go to Main Dashboard</a> | <a href='customer_portal/'>Go to Customer Portal</a></p>";
?>
