<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

// Handle Add New Product
if ($_POST && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $status = $_POST['status'];

    // Image Upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/products/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $image = "uploads/products/" . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image, category, status) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $image, $category, $status]);

    echo "<script>alert('Product added successfully!'); window.location='products.php';</script>";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    echo "<script>alert('Product deleted!'); window.location='products.php';</script>";
}

// Handle Edit
if ($_POST && isset($_POST['update_product'])) {
    $id = (int)$_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, description=?, category=?, status=? WHERE id=?");
    $stmt->execute([$name, $price, $description, $category, $status, $id]);
    echo "<script>alert('Product updated successfully!'); window.location='products.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products - Danisat Admin</title>
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
        <a href="products.php" class="flex items-center gap-3 px-4 py-3 bg-green-600 rounded-2xl"><i class="fas fa-box"></i> Manage Products</a>
        <a href="pending-products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-clock"></i> Pending Products</a>
        <a href="users.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-users"></i> Manage Users</a>
      </nav>
      <a href="../logout.php" class="absolute bottom-8 flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-gray-800 rounded-2xl w-[calc(100%-3rem)]">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-auto">
      <h1 class="text-4xl font-bold mb-8">Manage Products</h1>

      <!-- Add New Product Form -->
      <div class="bg-white rounded-3xl p-8 mb-10 shadow">
        <h2 class="text-2xl font-bold mb-6">Add New Product</h2>
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium mb-2">Product Name</label>
            <input type="text" name="name" required class="w-full p-4 border rounded-2xl">
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Price (₦)</label>
            <input type="number" name="price" required class="w-full p-4 border rounded-2xl">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Description</label>
            <textarea name="description" rows="3" required class="w-full p-4 border rounded-2xl"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Category</label>
            <select name="category" required class="w-full p-4 border rounded-2xl">
              <option value="Solar">Solar Equipment</option>
              <option value="Security">Security Devices</option>
              <option value="Fencing">Fencing Materials</option>
              <option value="Others">Others</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <select name="status" required class="w-full p-4 border rounded-2xl">
              <option value="approved">Approved</option>
              <option value="pending">Pending</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Product Image</label>
            <input type="file" name="image" accept="image/*" class="w-full p-4 border rounded-2xl">
          </div>
          <div class="md:col-span-2">
            <button type="submit" name="add_product" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl text-lg font-semibold">
              Add Product
            </button>
          </div>
        </form>
      </div>

      <!-- Products List -->
      <h2 class="text-2xl font-bold mb-4">All Products</h2>
      <table class="w-full bg-white rounded-3xl shadow overflow-hidden">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-5 text-left">Image</th>
            <th class="p-5 text-left">Product Name</th>
            <th class="p-5 text-left">Price</th>
            <th class="p-5 text-left">Category</th>
            <th class="p-5 text-left">Status</th>
            <th class="p-5 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
          while ($product = $stmt->fetch()) {
            $img = $product['image'] ? "<img src='../".$product['image']."' class='w-14 h-14 object-cover rounded-xl'>" : "📦";
            echo "<tr class='border-t hover:bg-gray-50'>
              <td class='p-5'>$img</td>
              <td class='p-5 font-medium'>".htmlspecialchars($product['name'])."</td>
              <td class='p-5 font-bold text-green-600'>₦" . number_format($product['price'], 2) . "</td>
              <td class='p-5'>".htmlspecialchars($product['category'])."</td>
              <td class='p-5'>".ucfirst($product['status'])."</td>
              <td class='p-5 text-center'>
                <button onclick='editProduct(".json_encode($product).")' class='text-blue-600 mx-3'><i class='fas fa-edit'></i></button>
                <a href='?delete={$product['id']}' onclick=\"return confirm('Delete this product?')\" class='text-red-600'><i class='fas fa-trash'></i></a>
              </td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Edit Modal (Same as before) -->
  <!-- ... (I can add it if needed, but to save space, you can reuse from previous version) -->

</body>
</html>