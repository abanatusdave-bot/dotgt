<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Danisat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-gray-100">
  <div class="flex h-screen">

    <!-- Sidebar -->
    <div class="w-72 bg-gray-900 text-white p-6">
      <div class="flex items-center gap-3 mb-10">
        <div class="text-4xl">☀️</div>
        <h1 class="text-2xl font-bold">Danisat Admin</h1>
      </div>
      
      <nav class="space-y-2">
        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-green-600 rounded-2xl">
          <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="services.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl">
          <i class="fas fa-list"></i> Manage Services
        </a>
        <a href="products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl">
          <i class="fas fa-box"></i> Manage Products
        </a>
        <a href="pending-products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl">
          <i class="fas fa-clock"></i> Pending Products
        </a>
        <a href="users.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl">
          <i class="fas fa-users"></i> Manage Users
        </a>
        <a href="testimonials.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl">
          <i class="fas fa-star"></i> Testimonials
        </a>
      </nav>

      <a href="../logout.php" 
         class="absolute bottom-8 flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-gray-800 rounded-2xl w-[calc(100%-3rem)]">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-10 overflow-auto">
      <h1 class="text-4xl font-bold mb-8">Welcome back, Admin</h1>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-3xl shadow">
          <h3 class="text-green-600">Total Services</h3>
          <?php 
            $count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
            echo "<p class='text-5xl font-bold mt-4'>$count</p>";
          ?>
        </div>
        
        <div class="bg-white p-8 rounded-3xl shadow">
          <h3 class="text-blue-600">Approved Products</h3>
          <?php 
            $count = $pdo->query("SELECT COUNT(*) FROM products WHERE status='approved'")->fetchColumn();
            echo "<p class='text-5xl font-bold mt-4'>$count</p>";
          ?>
        </div>
        
        <div class="bg-white p-8 rounded-3xl shadow">
          <h3 class="text-yellow-600">Registered Users</h3>
          <?php 
            $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            echo "<p class='text-5xl font-bold mt-4'>$count</p>";
          ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>