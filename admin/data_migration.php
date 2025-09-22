<?php
/**
 * Data Migration Script for JEL Air Conditioning Services
 * This script helps migrate data from Excel/CSV files to the database
 */

require_once '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die('Access denied. Admin privileges required.');
}

// Function to import customers from CSV
function importCustomers($csvFile) {
    global $pdo;
    
    if (!file_exists($csvFile)) {
        return "CSV file not found: $csvFile";
    }
    
    $handle = fopen($csvFile, 'r');
    $imported = 0;
    $errors = [];
    
    // Skip header row
    fgetcsv($handle);
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO customers (first_name, last_name, email, phone, address) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data[0] ?? '', // first_name
                $data[1] ?? '', // last_name
                $data[2] ?? '', // email
                $data[3] ?? '', // phone
                $data[4] ?? ''  // address
            ]);
            $imported++;
        } catch (Exception $e) {
            $errors[] = "Row " . ($imported + 1) . ": " . $e->getMessage();
        }
    }
    
    fclose($handle);
    
    return "Imported $imported customers. Errors: " . count($errors);
}

// Function to import technicians from CSV
function importTechnicians($csvFile) {
    global $pdo;
    
    if (!file_exists($csvFile)) {
        return "CSV file not found: $csvFile";
    }
    
    $handle = fopen($csvFile, 'r');
    $imported = 0;
    $errors = [];
    
    // Skip header row
    fgetcsv($handle);
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO technicians (first_name, last_name, email, phone, specialization) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data[0] ?? '', // first_name
                $data[1] ?? '', // last_name
                $data[2] ?? '', // email
                $data[3] ?? '', // phone
                $data[4] ?? ''  // specialization
            ]);
            $imported++;
        } catch (Exception $e) {
            $errors[] = "Row " . ($imported + 1) . ": " . $e->getMessage();
        }
    }
    
    fclose($handle);
    
    return "Imported $imported technicians. Errors: " . count($errors);
}

// Function to import services from CSV
function importServices($csvFile) {
    global $pdo;
    
    if (!file_exists($csvFile)) {
        return "CSV file not found: $csvFile";
    }
    
    $handle = fopen($csvFile, 'r');
    $imported = 0;
    $errors = [];
    
    // Skip header row
    fgetcsv($handle);
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO services (name, description, price, duration) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $data[0] ?? '', // name
                $data[1] ?? '', // description
                $data[2] ?? 0,  // price
                $data[3] ?? 60  // duration
            ]);
            $imported++;
        } catch (Exception $e) {
            $errors[] = "Row " . ($imported + 1) . ": " . $e->getMessage();
        }
    }
    
    fclose($handle);
    
    return "Imported $imported services. Errors: " . count($errors);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Migration - JEL Air Conditioning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h2">Data Migration Tool</h1>
                <p class="text-muted">Import data from Excel/CSV files</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-users me-2"></i>Import Customers</h5>
                    </div>
                    <div class="card-body">
                        <p>CSV Format: first_name, last_name, email, phone, address</p>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="file" class="form-control" name="customers_csv" accept=".csv">
                            </div>
                            <button type="submit" name="import_customers" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Import Customers
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tools me-2"></i>Import Technicians</h5>
                    </div>
                    <div class="card-body">
                        <p>CSV Format: first_name, last_name, email, phone, specialization</p>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="file" class="form-control" name="technicians_csv" accept=".csv">
                            </div>
                            <button type="submit" name="import_technicians" class="btn btn-info">
                                <i class="fas fa-upload me-2"></i>Import Technicians
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cog me-2"></i>Import Services</h5>
                    </div>
                    <div class="card-body">
                        <p>CSV Format: name, description, price, duration</p>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="file" class="form-control" name="services_csv" accept=".csv">
                            </div>
                            <button type="submit" name="import_services" class="btn btn-warning">
                                <i class="fas fa-upload me-2"></i>Import Services
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if ($_POST) {
            echo '<div class="row mt-4">';
            echo '<div class="col-12">';
            echo '<div class="alert alert-info">';
            
            if (isset($_POST['import_customers']) && isset($_FILES['customers_csv'])) {
                $result = importCustomers($_FILES['customers_csv']['tmp_name']);
                echo "<strong>Customers Import:</strong> $result<br>";
            }
            
            if (isset($_POST['import_technicians']) && isset($_FILES['technicians_csv'])) {
                $result = importTechnicians($_FILES['technicians_csv']['tmp_name']);
                echo "<strong>Technicians Import:</strong> $result<br>";
            }
            
            if (isset($_POST['import_services']) && isset($_FILES['services_csv'])) {
                $result = importServices($_FILES['services_csv']['tmp_name']);
                echo "<strong>Services Import:</strong> $result<br>";
            }
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Instructions</h5>
                    </div>
                    <div class="card-body">
                        <h6>How to prepare your Excel data:</h6>
                        <ol>
                            <li>Export your Excel file as CSV format</li>
                            <li>Ensure column headers match the required format</li>
                            <li>Remove any empty rows</li>
                            <li>Save as UTF-8 encoding</li>
                        </ol>
                        
                        <h6>CSV Format Requirements:</h6>
                        <ul>
                            <li><strong>Customers:</strong> first_name, last_name, email, phone, address</li>
                            <li><strong>Technicians:</strong> first_name, last_name, email, phone, specialization</li>
                            <li><strong>Services:</strong> name, description, price, duration</li>
                        </ul>
                        
                        <div class="alert alert-warning">
                            <strong>Important:</strong> Always backup your database before importing data!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <a href="../index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
