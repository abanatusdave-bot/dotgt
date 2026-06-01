<?php
include 'includes/db.php';

echo "<h2>🔍 Admin Login Debug</h2>";

$stmt = $pdo->query("SELECT id, email, password, full_name, role FROM users WHERE role = 'admin'");
$admins = $stmt->fetchAll();

if (count($admins) == 0) {
    echo "<p style='color:red'>No admin account found!</p>";
} else {
    foreach ($admins as $admin) {
        echo "<hr>";
        echo "<p><strong>ID:</strong> " . $admin['id'] . "</p>";
        echo "<p><strong>Email:</strong> " . $admin['email'] . "</p>";
        echo "<p><strong>Role:</strong> " . $admin['role'] . "</p>";
        echo "<p><strong>Password Hash:</strong> " . substr($admin['password'], 0, 30) . "...</p>";
    }
}

echo "<br><a href='create-admin.php' style='background:green;color:white;padding:10px 20px;border-radius:8px;'>Recreate Admin</a>";
?>