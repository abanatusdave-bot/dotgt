<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

// Handle Approve
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $pdo->prepare("UPDATE products SET status = 'approved' WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Product Approved Successfully!'); window.location='pending-products.php';</script>";
}

// Handle Reject/Delete
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    echo "<script>alert('Product Rejected and Removed!'); window.location='pending-products.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Products - Danisat Admin</title>
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
        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-home"></i> Dashboard</a>
        <a href="services.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-list"></i> Manage Services</a>
        <a href="products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-box"></i> Manage Products</a>
        <a href="pending-products.php" class="flex items-center gap-3 px-4 py-3 bg-green-600 rounded-2xl"><i class="fas fa-clock"></i> Pending Products</a>
        <a href="users.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-users"></i> Manage Users</a>
      </nav>
      <a href="../logout.php" class="absolute bottom-8 flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-gray-800 rounded-2xl w-[calc(100%-3rem)]">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-auto">
      <h1 class="text-4xl font-bold mb-8">Pending Products for Approval</h1>

      <?php
      $stmt = $pdo->query("SELECT p.*, u.full_name as uploaded_by_name 
                          FROM products p 
                          LEFT JOIN users u ON p.uploaded_by = u.id 
                          WHERE p.status = 'pending' 
                          ORDER BY p.created_at DESC");
      
      if ($stmt->rowCount() == 0):
      ?>
        <div class="bg-white rounded-3xl p-20 text-center">
          <div class="text-6xl mb-6">🎉</div>
          <h3 class="text-2xl font-semibold text-gray-600">No Pending Products</h3>
          <p class="text-gray-500 mt-2">All customer products have been reviewed.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <?php while ($product = $stmt->fetch()): ?>
            <div class="bg-white rounded-3xl shadow overflow-hidden">
              <div class="h-56 bg-gray-100 flex items-center justify-center relative">
                <?php if ($product['image']): ?>
                  <img src="../<?= htmlspecialchars($product['image']) ?>" 
                       class="w-full h-full object-cover">
                <?php else: ?>
                  <div class="text-8xl">📦</div>
                <?php endif; ?>
              </div>

              <div class="p-8">
                <h3 class="text-2xl font-semibold mb-2"><?= htmlspecialchars($product['name']) ?></h3>
                <p class="text-green-600 font-bold text-3xl mb-4">
                  ₦<?= number_format($product['price'], 2) ?>
                </p>
                
                <p class="text-gray-600 mb-6 line-clamp-3">
                  <?= htmlspecialchars($product['description']) ?>
                </p>

                <div class="flex justify-between items-center text-sm">
                  <div>
                    <span class="text-gray-500">Category:</span> 
                    <strong><?= htmlspecialchars($product['category']) ?></strong>
                  </div>
                  <div>
                    <span class="text-gray-500">Uploaded by:</span> 
                    <strong><?= htmlspecialchars($product['uploaded_by_name'] ?? 'Customer') ?></strong>
                  </div>
                </div>

                <div class="mt-8 flex gap-4">
                  <a href="?approve=<?= $product['id'] ?>" 
                     class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-5 rounded-2xl font-semibold transition">
                    ✅ Approve
                  </a>
                  <a href="?reject=<?= $product['id'] ?>" 
                     onclick="return confirm('Reject and delete this product?')"
                     class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-5 rounded-2xl font-semibold transition">
                    ❌ Reject
                  </a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>