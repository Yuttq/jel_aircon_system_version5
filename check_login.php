<?php
session_start();
require_once 'includes/config.php';

echo "<h2>Login Status Check</h2>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Is Logged In:</strong> " . (isLoggedIn() ? 'Yes' : 'No') . "</p>";

if (isLoggedIn()) {
    echo "<p><strong>User ID:</strong> " . $_SESSION['user_id'] . "</p>";
    echo "<p><strong>Username:</strong> " . $_SESSION['username'] . "</p>";
    echo "<p><strong>Full Name:</strong> " . $_SESSION['full_name'] . "</p>";
    echo "<p><strong>User Role:</strong> " . $_SESSION['user_role'] . "</p>";
    echo "<p><strong>Is Admin:</strong> " . (hasRole('admin') ? 'Yes' : 'No') . "</p>";
} else {
    echo "<p><strong>Status:</strong> Not logged in</p>";
    echo "<p><a href='login.php'>Go to Login Page</a></p>";
}

echo "<hr>";
echo "<p><a href='index.php'>Back to Dashboard</a></p>";
echo "<p><a href='settings.php'>Try Settings Page</a></p>";
?>
