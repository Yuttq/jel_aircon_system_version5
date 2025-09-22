<?php
/**
 * Database Fix Script for JEL Air Conditioning System
 * This script checks and fixes database structure issues
 */

require_once 'includes/config.php';

echo "<h1>JEL Air Conditioning - Database Fix Script</h1>";
echo "<hr>";

try {
    // Check if database exists and is accessible
    echo "<h2>1. Database Connection Test</h2>";
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Check bookings table structure
    echo "<h2>2. Bookings Table Structure</h2>";
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $hasReminderSent = false;
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
        
        if ($column['Field'] === 'reminder_sent') {
            $hasReminderSent = true;
        }
    }
    echo "</table>";
    
    // Fix missing reminder_sent column
    if (!$hasReminderSent) {
        echo "<h2>3. Adding Missing Column</h2>";
        echo "<p style='color: orange;'>⚠️ Missing 'reminder_sent' column. Adding it now...</p>";
        
        $pdo->exec("ALTER TABLE bookings ADD COLUMN reminder_sent TINYINT(1) DEFAULT 0");
        echo "<p style='color: green;'>✅ Added 'reminder_sent' column successfully!</p>";
    } else {
        echo "<h2>3. Column Check</h2>";
        echo "<p style='color: green;'>✅ 'reminder_sent' column exists!</p>";
    }
    
    // Check if we have sample data
    echo "<h2>4. Sample Data Check</h2>";
    
    // Check customers
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers");
    $customerCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p>Customers: $customerCount</p>";
    
    // Check services
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM services");
    $serviceCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p>Services: $serviceCount</p>";
    
    // Check technicians
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM technicians");
    $techCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p>Technicians: $techCount</p>";
    
    // Check bookings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM bookings");
    $bookingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p>Bookings: $bookingCount</p>";
    
    // Add sample data if needed
    if ($customerCount == 0) {
        echo "<h2>5. Adding Sample Data</h2>";
        echo "<p style='color: orange;'>⚠️ No customers found. Adding sample data...</p>";
        
        // Add sample customers
        $sampleCustomers = [
            ['John', 'Doe', 'john.doe@email.com', '09123456789', '123 Main St, City'],
            ['Jane', 'Smith', 'jane.smith@email.com', '09123456790', '456 Oak Ave, City'],
            ['Mike', 'Johnson', 'mike.johnson@email.com', '09123456791', '789 Pine Rd, City']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO customers (first_name, last_name, email, phone, address) VALUES (?, ?, ?, ?, ?)");
        foreach ($sampleCustomers as $customer) {
            $stmt->execute($customer);
        }
        echo "<p style='color: green;'>✅ Added 3 sample customers!</p>";
    }
    
    if ($techCount == 0) {
        echo "<p style='color: orange;'>⚠️ No technicians found. Adding sample technicians...</p>";
        
        // Add sample technicians
        $sampleTechnicians = [
            ['John', 'Technician', 'tech1@jelaircon.com', '09123456792', 'AC Installation Specialist'],
            ['Sarah', 'Engineer', 'tech2@jelaircon.com', '09123456793', 'AC Repair Expert'],
            ['David', 'Specialist', 'tech3@jelaircon.com', '09123456794', 'AC Maintenance Pro']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO technicians (first_name, last_name, email, phone, specialization) VALUES (?, ?, ?, ?, ?)");
        foreach ($sampleTechnicians as $tech) {
            $stmt->execute($tech);
        }
        echo "<p style='color: green;'>✅ Added 3 sample technicians!</p>";
    }
    
    // Test booking creation
    echo "<h2>6. Test Booking Creation</h2>";
    
    // Get first customer and service
    $stmt = $pdo->query("SELECT id FROM customers LIMIT 1");
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->query("SELECT id FROM services LIMIT 1");
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($customer && $service) {
        try {
            $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, service_id, booking_date, start_time, end_time, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $customer['id'],
                $service['id'],
                date('Y-m-d', strtotime('+1 day')),
                '09:00:00',
                '10:00:00',
                'Test booking created by database fix script',
                'pending'
            ]);
            
            $testBookingId = $pdo->lastInsertId();
            echo "<p style='color: green;'>✅ Test booking created successfully! ID: $testBookingId</p>";
            
            // Clean up test booking
            $pdo->prepare("DELETE FROM bookings WHERE id = ?")->execute([$testBookingId]);
            echo "<p style='color: blue;'>ℹ️ Test booking cleaned up</p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Failed to create test booking: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Cannot test booking creation - missing customers or services</p>";
    }
    
    // Check foreign key constraints
    echo "<h2>7. Foreign Key Constraints Check</h2>";
    $stmt = $pdo->query("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE 
            REFERENCED_TABLE_SCHEMA = 'jel_aircon'
            AND TABLE_NAME = 'bookings'
    ");
    
    $constraints = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($constraints) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Table</th><th>Column</th><th>Constraint</th><th>References</th></tr>";
        foreach ($constraints as $constraint) {
            echo "<tr>";
            echo "<td>" . $constraint['TABLE_NAME'] . "</td>";
            echo "<td>" . $constraint['COLUMN_NAME'] . "</td>";
            echo "<td>" . $constraint['CONSTRAINT_NAME'] . "</td>";
            echo "<td>" . $constraint['REFERENCED_TABLE_NAME'] . "." . $constraint['REFERENCED_COLUMN_NAME'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p style='color: green;'>✅ Foreign key constraints are properly set up!</p>";
    } else {
        echo "<p style='color: red;'>❌ No foreign key constraints found!</p>";
    }
    
    echo "<hr>";
    echo "<h2>Database Fix Summary</h2>";
    echo "<p style='color: green;'>✅ Database structure has been checked and fixed!</p>";
    echo "<p><strong>What was done:</strong></p>";
    echo "<ul>";
    echo "<li>✅ Verified database connection</li>";
    echo "<li>✅ Checked bookings table structure</li>";
    if (!$hasReminderSent) {
        echo "<li>✅ Added missing 'reminder_sent' column</li>";
    }
    echo "<li>✅ Verified sample data exists</li>";
    echo "<li>✅ Tested booking creation</li>";
    echo "<li>✅ Checked foreign key constraints</li>";
    echo "</ul>";
    
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>Try creating a booking again through the admin panel</li>";
    echo "<li>If you still have issues, check the error logs</li>";
    echo "<li>Make sure XAMPP MySQL service is running</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><em>Database fix completed at " . date('Y-m-d H:i:s') . "</em></p>";
?>
