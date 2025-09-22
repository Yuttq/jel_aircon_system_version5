<?php
require_once 'includes/config.php';

echo "<h2>Users in Database</h2>";
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll();

if (count($users) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Status</th><th>Full Name</th></tr>";
    foreach($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "<td>" . $user['status'] . "</td>";
        echo "<td>" . $user['full_name'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No users found in database.</p>";
}

echo "<hr>";
echo "<p><a href='login.php'>Go to Login</a></p>";
echo "<p><a href='check_login.php'>Check Login Status</a></p>";
?>
