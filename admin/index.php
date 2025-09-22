<?php
/**
 * Admin Directory Index
 * Redirect to admin login
 */

require_once '../includes/config.php';

// Check if user is already logged in as admin
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: ../index.php');
    exit();
}

// Redirect to admin login
header('Location: admin_login.php');
exit();
?>
