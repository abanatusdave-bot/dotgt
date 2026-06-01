<?php
session_start();
include '../includes/db.php';

$error = '';

if ($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Danisat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="max-w-md w-full mx-4 bg-white rounded-3xl shadow-2xl p-10">
    <div class="text-center mb-8">
      <div class="w-20 h-20 mx-auto bg-gradient-to-br from-green-600 to-blue-700 rounded-3xl flex items-center justify-center text-5xl">
        ☀️
      </div>
      <h1 class="text-3xl font-bold mt-6">Admin Login</h1>
    </div>

    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-4 rounded-2xl mb-6 text-center">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <input type="email" name="email" value="admin@danisat.com" required
             class="w-full p-4 border rounded-2xl mb-4">
      
      <input type="password" name="password" value="password" required
             class="w-full p-4 border rounded-2xl mb-6">
      
      <button type="submit" 
              class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl text-lg font-semibold">
        Login
      </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
      Email: <strong>admin@danisat.com</strong><br>
      Password: <strong>password</strong>
    </p>
  </div>
</body>
</html>