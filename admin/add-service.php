<?php 
session_start();
if (!isset($_SESSION['admin_id'])) header("Location: login.php");
include '../includes/db.php';

if ($_POST) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $icon = $_POST['icon'];

    $stmt = $pdo->prepare("INSERT INTO services (title, description, icon) VALUES (?, ?, ?)");
    $stmt->execute([$title, $desc, $icon]);
    $success = "Service added successfully!";
}
?>

<div class="p-10">
  <h2 class="text-3xl font-bold mb-8">Add New Service</h2>
  <?php if (isset($success)) echo "<p class='text-green-600 mb-4'>$success</p>"; ?>
  
  <form method="POST" class="max-w-2xl bg-white p-8 rounded-3xl shadow">
    <input type="text" name="title" placeholder="Service Title" required class="w-full p-4 border rounded-2xl mb-4">
    <textarea name="description" placeholder="Description" rows="5" required class="w-full p-4 border rounded-2xl mb-4"></textarea>
    <input type="text" name="icon" placeholder="Icon (Emoji or FontAwesome class)" required class="w-full p-4 border rounded-2xl mb-6">
    <button type="submit" class="bg-green-600 text-white px-10 py-4 rounded-2xl">Add Service</button>
  </form>
</div>