<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

// Handle Add New Service
if ($_POST && isset($_POST['add_service'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/services/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $image = "uploads/services/" . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
    }

    $stmt = $pdo->prepare("INSERT INTO services (title, description, image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $image]);
    echo "<script>alert('Service added successfully!'); window.location='services.php';</script>";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
    echo "<script>alert('Service deleted successfully!'); window.location='services.php';</script>";
}

// Handle Edit Service
if ($_POST && isset($_POST['update_service'])) {
    $id = (int)$_POST['service_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Check if new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/services/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $image = "uploads/services/" . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);

        $stmt = $pdo->prepare("UPDATE services SET title=?, description=?, image=? WHERE id=?");
        $stmt->execute([$title, $description, $image, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE services SET title=?, description=? WHERE id=?");
        $stmt->execute([$title, $description, $id]);
    }

    echo "<script>alert('Service updated successfully!'); window.location='services.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Services - Danisat Admin</title>
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
        <a href="services.php" class="flex items-center gap-3 px-4 py-3 bg-green-600 rounded-2xl"><i class="fas fa-list"></i> Manage Services</a>
        <a href="products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-box"></i> Manage Products</a>
        <a href="pending-products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-clock"></i> Pending Products</a>
        <a href="users.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800 rounded-2xl"><i class="fas fa-users"></i> Manage Users</a>
      </nav>
      <a href="../logout.php" class="absolute bottom-8 flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-gray-800 rounded-2xl w-[calc(100%-3rem)]">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-auto">
      <h1 class="text-4xl font-bold mb-8">Manage Services</h1>

      <!-- Add New Service -->
      <div class="bg-white rounded-3xl p-8 mb-12 shadow">
        <h2 class="text-2xl font-bold mb-6">Add New Service</h2>
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Service Title</label>
            <input type="text" name="title" required class="w-full p-4 border rounded-2xl">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Description</label>
            <textarea name="description" rows="4" required class="w-full p-4 border rounded-2xl"></textarea>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Service Image</label>
            <input type="file" name="image" accept="image/*" class="w-full p-4 border rounded-2xl">
          </div>
          <div class="md:col-span-2">
            <button type="submit" name="add_service" class="w-full bg-green-600 hover:bg-green-700 text-white py-5 rounded-2xl text-lg font-semibold">
              Add Service
            </button>
          </div>
        </form>
      </div>

      <!-- Services List -->
      <h2 class="text-2xl font-bold mb-6">All Services</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $stmt = $pdo->query("SELECT * FROM services ORDER BY id DESC");
        while ($service = $stmt->fetch()) {
          $img = $service['image'] ? "../" . htmlspecialchars($service['image']) : "https://via.placeholder.com/400x250?text=No+Image";
          echo "
          <div class='bg-white rounded-3xl shadow overflow-hidden'>
            <img src='$img' class='w-full h-52 object-cover'>
            <div class='p-6'>
              <h3 class='text-xl font-semibold mb-3'>" . htmlspecialchars($service['title']) . "</h3>
              <p class='text-gray-600 text-sm line-clamp-4 mb-6'>" . htmlspecialchars($service['description']) . "</p>
              <div class='flex gap-3'>
                <button onclick='editService(".json_encode($service).")' 
                        class='flex-1 bg-blue-600 text-white py-3 rounded-2xl hover:bg-blue-700 transition'>
                  Edit
                </button>
                <a href='?delete={$service['id']}' onclick=\"return confirm('Delete this service?')\" 
                   class='flex-1 text-center py-3 text-red-600 border border-red-200 rounded-2xl hover:bg-red-50'>
                  Delete
                </a>
              </div>
            </div>
          </div>";
        }
        ?>
      </div>
    </div>
  </div>

  <!-- Edit Service Modal -->
  <div id="editModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full mx-4">
      <h2 class="text-2xl font-bold mb-6">Edit Service</h2>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="service_id" id="edit_id">

        <div class="mb-5">
          <label class="block text-sm font-medium mb-2">Service Title</label>
          <input type="text" name="title" id="edit_title" required class="w-full p-4 border rounded-2xl">
        </div>

        <div class="mb-5">
          <label class="block text-sm font-medium mb-2">Description</label>
          <textarea name="description" id="edit_description" rows="5" required class="w-full p-4 border rounded-2xl"></textarea>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium mb-2">Current Image</label>
          <div id="currentImage" class="mb-4"></div>
          <label class="block text-sm font-medium mb-2">Upload New Image (Optional)</label>
          <input type="file" name="image" accept="image/*" class="w-full p-4 border rounded-2xl">
        </div>

        <div class="flex gap-4">
          <button type="button" onclick="closeModal()" class="flex-1 py-4 border rounded-2xl font-medium">Cancel</button>
          <button type="submit" name="update_service" class="flex-1 bg-green-600 text-white py-4 rounded-2xl font-semibold">Update Service</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function editService(service) {
      document.getElementById('edit_id').value = service.id;
      document.getElementById('edit_title').value = service.title;
      document.getElementById('edit_description').value = service.description;

      // Show current image
      let imgHTML = service.image 
        ? `<img src="../${service.image}" class="w-40 h-40 object-cover rounded-2xl">` 
        : `<p class="text-gray-500">No image uploaded</p>`;
      document.getElementById('currentImage').innerHTML = imgHTML;

      document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('editModal').classList.add('hidden');
    }
  </script>
</body>
</html>