<?php
/**
 * Calendar Data API for Dashboard Widget
 * Returns booking data for calendar display
 */

header('Content-Type: application/json');

// Include configuration
require_once '../../includes/config.php';

// Check authentication
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    // Get month and year from query parameters
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    
    // Validate month and year
    if ($month < 1 || $month > 12) $month = date('n');
    if ($year < 2020 || $year > 2030) $year = date('Y');
    
    // Calculate date range
    $startDate = "$year-$month-01";
    $endDate = date('Y-m-t', strtotime($startDate));
    
    // Get bookings for the month
    $stmt = $pdo->prepare("
        SELECT 
            b.booking_date,
            COUNT(*) as booking_count,
            GROUP_CONCAT(DISTINCT b.status) as statuses
        FROM bookings b 
        WHERE b.booking_date BETWEEN ? AND ? 
        AND b.status NOT IN ('cancelled')
        GROUP BY b.booking_date
        ORDER BY b.booking_date
    ");
    
    $stmt->execute([$startDate, $endDate]);
    $bookings = $stmt->fetchAll();
    
    // Format response
    $response = [
        'success' => true,
        'month' => $month,
        'year' => $year,
        'bookings' => $bookings
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    // Log error
    if (isset($security)) {
        $security->logSecurityEvent('calendar_data_error', [
            'error' => $e->getMessage()
        ]);
    }
    
    // Return empty data on error
    echo json_encode([
        'success' => false,
        'error' => 'Unable to load calendar data',
        'bookings' => []
    ]);
}
?>
