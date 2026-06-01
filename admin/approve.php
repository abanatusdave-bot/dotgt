<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Handle Approval or Rejection
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE products SET status = 'approved' WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Product approved successfully!";
    } 
    elseif ($action === 'reject') {
        // Delete the product (or you can set status to 'rejected')
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Product rejected and removed.";
    }
    else {
        $message = "Invalid action.";
    }
} else {
    $message = "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Process Product - Danisat Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-10 text-center">
    <div class="mb-8">
      <?php if ($action === 'approve'): ?>
        <div class="text-7xl mb-4">✅</div>
      <?php else: ?>
        <div class="text-7xl mb-4">❌</div>
      <?php endif; ?>
    </div>
    
    <h2 class="text-2xl font-bold mb-6">Action Completed</h2>
    <p class="text-gray-600 mb-10"><?= htmlspecialchars($message) ?></p>
    
    <div class="flex gap-4 justify-center">
      <a href="pending-products.php" 
         class="px-8 py-4 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition">
        Back to Pending Products
      </a>
      <a href="dashboard.php" 
         class="px-8 py-4 border border-gray-300 rounded-2xl hover:bg-gray-50 transition">
        Go to Dashboard
      </a>
    </div>
  </div>
</body>
</html>