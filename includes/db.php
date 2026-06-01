<?php
$host = 'localhost';
$db   = 'danisat_db';
$user = 'root';          // Change to your DB user
$pass = '';              // Change to your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>