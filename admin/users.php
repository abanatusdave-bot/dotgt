<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'")->execute([$id]);
    echo "<script>alert('User deleted successfully!'); window.location='users.php';</script>";
}

// Handle Edit (Update)
if ($_POST && isset($_POST['update_user'])) {
    $id = (int)$_POST['user_id'];
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, role = ? WHERE id = ? AND role != 'admin'");
    $stmt->execute([$full_name, $email, $phone, $role, $id]);
    echo "<script>alert('User updated successfully!'); window.location='users.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users - Danisat Admin</title>
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
        <a href="add-service.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-list"></i> Services</a>
        <a href="add-product.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-box"></i> Add Product</a>
        <a href="pending-products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-clock"></i> Pending Products</a>
        <a href="users.php" class="flex items-center gap-3 px-4 py-3 bg-green-600 rounded-2xl"><i class="fas fa-users"></i> Manage Users</a>
      </nav>
      <a href="../logout.php" class="absolute bottom-8 flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-gray-800 rounded-2xl w-[calc(100%-3rem)]">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-auto">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Manage Users</h1>
        <input type="text" id="searchInput" 
               class="w-80 p-4 border rounded-2xl focus:outline-none focus:border-green-600" 
               placeholder="Search users...">
      </div>

      <table class="w-full bg-white rounded-3xl shadow overflow-hidden" id="usersTable">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-5 text-left">Name</th>
            <th class="p-5 text-left">Email</th>
            <th class="p-5 text-left">Phone</th>
            <th class="p-5 text-left">Role</th>
            <th class="p-5 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
          while ($user = $stmt->fetch()) {
            echo "<tr class='border-t hover:bg-gray-50 user-row'>
              <td class='p-5'>" . htmlspecialchars($user['full_name']) . "</td>
              <td class='p-5'>" . htmlspecialchars($user['email']) . "</td>
              <td class='p-5'>" . htmlspecialchars($user['phone'] ?? 'N/A') . "</td>
              <td class='p-5'>
                <span class='px-4 py-1 rounded-full text-xs font-medium " . ($user['role'] == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700') . "'>
                  " . strtoupper($user['role']) . "
                </span>
              </td>
              <td class='p-5 text-center'>
                <button onclick='editUser(".json_encode($user).")' 
                        class='text-blue-600 hover:text-blue-800 mx-2'>
                  <i class='fas fa-edit'></i>
                </button>
                " . ($user['role'] != 'admin' ? 
                  "<a href='?delete={$user['id']}' onclick=\"return confirm('Delete this user?')\" 
                     class='text-red-600 hover:text-red-800 mx-2'><i class='fas fa-trash'></i></a>" : 
                  "<span class='text-gray-400'>Protected</span>") . "
              </td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div id="editModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full mx-4">
      <h2 class="text-2xl font-bold mb-6">Edit User</h2>
      <form method="POST">
        <input type="hidden" name="user_id" id="edit_user_id">
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Full Name</label>
          <input type="text" name="full_name" id="edit_full_name" required class="w-full p-4 border rounded-2xl">
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Email</label>
          <input type="email" name="email" id="edit_email" required class="w-full p-4 border rounded-2xl">
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Phone</label>
          <input type="text" name="phone" id="edit_phone" class="w-full p-4 border rounded-2xl">
        </div>
        
        <div class="mb-6">
          <label class="block text-sm font-medium mb-1">Role</label>
          <select name="role" id="edit_role" class="w-full p-4 border rounded-2xl">
            <option value="customer">Customer</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div class="flex gap-4">
          <button type="button" onclick="closeModal()" 
                  class="flex-1 py-4 border rounded-2xl font-medium">Cancel</button>
          <button type="submit" name="update_user" 
                  class="flex-1 bg-green-600 text-white py-4 rounded-2xl font-semibold">Update User</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function editUser(user) {
      document.getElementById('edit_user_id').value = user.id;
      document.getElementById('edit_full_name').value = user.full_name;
      document.getElementById('edit_email').value = user.email;
      document.getElementById('edit_phone').value = user.phone || '';
      document.getElementById('edit_role').value = user.role;
      document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('editModal').classList.add('hidden');
    }

    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
      let filter = this.value.toLowerCase();
      let rows = document.querySelectorAll('.user-row');
      rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  </script>
</body>
</html>