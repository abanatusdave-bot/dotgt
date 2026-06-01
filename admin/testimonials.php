<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

// Handle Approve Testimonial
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $pdo->prepare("UPDATE testimonials SET status = 'approved' WHERE id = ?")->execute([$id]);
    echo "<script>alert('Testimonial Approved!'); window.location='testimonials.php';</script>";
}

// Handle Reject/Delete
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $pdo->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$id]);
    echo "<script>alert('Testimonial Rejected and Deleted!'); window.location='testimonials.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Testimonials - Danisat Admin</title>
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
        <a href="pending-products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-clock"></i> Pending Products</a>
        <a href="users.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-users"></i> Manage Users</a>
        <a href="testimonials.php" class="flex items-center gap-3 px-4 py-3 bg-green-600 rounded-2xl"><i class="fas fa-star"></i> Testimonials</a>
      </nav>
      <a href="../logout.php" class="absolute bottom-8 flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-gray-800 rounded-2xl w-[calc(100%-3rem)]">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-auto">
      <h1 class="text-4xl font-bold mb-8">Testimonials Approval</h1>

      <?php
      $stmt = $pdo->query("SELECT * FROM testimonials WHERE status = 'pending' ORDER BY id DESC");
      
      if ($stmt->rowCount() == 0):
      ?>
        <div class="bg-white rounded-3xl p-20 text-center">
          <div class="text-6xl mb-6">🎉</div>
          <h3 class="text-2xl font-semibold">No Pending Testimonials</h3>
          <p class="text-gray-500 mt-3">All testimonials have been reviewed.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <?php while ($testimonial = $stmt->fetch()): ?>
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
              <div class="p-8">
                <div class="flex items-center gap-4 mb-6">
                  <div class="w-16 h-16 bg-gray-200 rounded-2xl overflow-hidden flex-shrink-0">
                    <?php if ($testimonial['photo']): ?>
                      <img src="../<?= htmlspecialchars($testimonial['photo']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                      <div class="w-full h-full flex items-center justify-center text-4xl">👤</div>
                    <?php endif; ?>
                  </div>
                  <div>
                    <h3 class="font-semibold text-xl"><?= htmlspecialchars($testimonial['client_name']) ?></h3>
                    <div class="text-yellow-400 text-2xl">
                      <?= str_repeat('⭐', $testimonial['rating']) ?>
                    </div>
                  </div>
                </div>

                <p class="text-gray-700 italic text-lg leading-relaxed mb-8">
                  “<?= htmlspecialchars($testimonial['message']) ?>”
                </p>

                <div class="flex gap-4">
                  <a href="?approve=<?= $testimonial['id'] ?>" 
                     class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-5 rounded-2xl font-semibold transition">
                    ✅ Approve
                  </a>
                  <a href="?reject=<?= $testimonial['id'] ?>" 
                     onclick="return confirm('Reject this testimonial?')"
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