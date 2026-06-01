<?php
include 'includes/db.php';

$pdo->exec("DELETE FROM users WHERE email = 'admin@danisat.com'");

$email = 'admin@danisat.com';
$plain_password = 'password123';           // Changed to something clearer
$hashed = password_hash($plain_password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (email, password, full_name, role) VALUES (?, ?, ?, 'admin')");
$stmt->execute([$email, $hashed, 'Danisat Admin']);

echo "<h3 style='color:green'>✅ New Admin Created!</h3>";
echo "<p>Email: <strong>admin@danisat.com</strong></p>";
echo "<p>Password: <strong>password123</strong></p>";
echo "<br><a href='admin/login.php'>→ Go to Login</a>";
?>