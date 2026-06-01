<?php 
session_start();
if (!isset($_SESSION['admin_id'])) header("Location: login.php");
include '../includes/db.php';

if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $pdo->prepare("UPDATE products SET status='approved' WHERE id = ?")->execute([$id]);
}

if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
}
?>

<div class="p-10">
  <h2 class="text-3xl font-bold mb-8">Pending Products for Approval</h2>
  
  <table class="w-full bg-white rounded-3xl shadow overflow-hidden">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-4 text-left">Product</th>
        <th class="p-4 text-left">Price</th>
        <th class="p-4 text-left">Uploaded By</th>
        <th class="p-4">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = $pdo->query("SELECT * FROM products WHERE status='pending'");
      while ($row = $stmt->fetch()) {
        echo "<tr class='border-t'>
          <td class='p-4'>{$row['name']}</td>
          <td class='p-4'>₦" . number_format($row['price'], 2) . "</td>
          <td class='p-4'>Customer #{$row['uploaded_by']}</td>
          <td class='p-4'>
            <a href='?approve={$row['id']}' class='bg-green-600 text-white px-5 py-2 rounded-xl text-sm'>Approve</a>
            <a href='?reject={$row['id']}' class='bg-red-600 text-white px-5 py-2 rounded-xl text-sm'>Reject</a>
          </td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
</div>