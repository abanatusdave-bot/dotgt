<?php 
include 'includes/header.php';
include 'includes/db.php';

// Handle Product Upload by Customer
if ($_POST && isset($_SESSION['user_id'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $uploaded_by = $_SESSION['user_id'];

    // Image Upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $image = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image, category, uploaded_by, status) 
                          VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$name, $price, $description, $image, $category, $uploaded_by]);

    echo "<script>alert('Product submitted for admin approval!');</script>";
}
?>

<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex justify-between items-center mb-10">
      <h2 class="text-4xl font-bold text-gray-900">Products & Equipment</h2>
      <?php if (isset($_SESSION['user_id'])): ?>
        <button onclick="showSellModal()" 
                class="bg-yellow-400 hover:bg-yellow-500 px-8 py-4 rounded-2xl font-semibold flex items-center gap-2">
          <i class="fas fa-plus"></i> Sell Your Item
        </button>
      <?php else: ?>
        <a href="login.php" class="bg-yellow-400 hover:bg-yellow-500 px-8 py-4 rounded-2xl font-semibold">Login to Sell</a>
      <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="flex gap-4 mb-8">
      <select id="categoryFilter" class="p-4 border rounded-2xl">
        <option value="">All Categories</option>
        <option value="Solar">Solar Equipment</option>
        <option value="Security">Security Devices</option>
        <option value="Fencing">Fencing Materials</option>
      </select>
    </div>

    <!-- Products Grid -->
    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-8" id="productsGrid">
      <?php
      $stmt = $pdo->query("SELECT * FROM products WHERE status = 'approved' ORDER BY created_at DESC");
      while ($product = $stmt->fetch()) {
        echo "
        <div class='bg-white border border-gray-100 rounded-3xl overflow-hidden hover:shadow-2xl transition'>
          " . ($product['image'] ? "<img src='{$product['image']}' class='w-full h-48 object-cover'>" : "<div class='h-48 bg-gray-200 flex items-center justify-center text-6xl'>📦</div>") . "
          <div class='p-6'>
            <h3 class='font-semibold text-xl mb-1'>{$product['name']}</h3>
            <p class='text-green-600 font-bold text-2xl mb-3'>₦" . number_format($product['price'], 2) . "</p>
            <p class='text-gray-600 text-sm line-clamp-2 mb-4'>{$product['description']}</p>
            <button onclick='buyProduct({$product['id']})' 
                    class='w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-medium'>
              Buy Now
            </button>
          </div>
        </div>";
      }
      ?>
    </div>
  </div>
</section>

<!-- Sell Product Modal -->
<div id="sellModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
  <div class="bg-white rounded-3xl max-w-lg w-full mx-4 p-8">
    <h3 class="text-2xl font-bold mb-6">Sell Your Product</h3>
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <input type="text" name="name" placeholder="Product Name" required class="w-full p-4 border rounded-2xl">
      <input type="number" name="price" placeholder="Price (₦)" required class="w-full p-4 border rounded-2xl">
      <select name="category" class="w-full p-4 border rounded-2xl">
        <option value="Solar">Solar Equipment</option>
        <option value="Security">Security Devices</option>
        <option value="Fencing">Fencing & Mesh</option>
      </select>
      <textarea name="description" placeholder="Description" rows="4" required class="w-full p-4 border rounded-2xl"></textarea>
      <input type="file" name="image" accept="image/*" class="w-full p-4 border rounded-2xl">
      <div class="flex gap-4">
        <button type="button" onclick="hideSellModal()" 
                class="flex-1 py-4 border rounded-2xl font-medium">Cancel</button>
        <button type="submit" 
                class="flex-1 bg-green-600 text-white py-4 rounded-2xl font-semibold">Submit for Approval</button>
      </div>
    </form>
  </div>
</div>

<script>
function showSellModal() { document.getElementById('sellModal').classList.remove('hidden'); }
function hideSellModal() { document.getElementById('sellModal').classList.add('hidden'); }

function buyProduct(id) {
  alert("Product #" + id + " - Contact admin via WhatsApp for purchase.");
  // You can expand this to a full checkout later
}
</script>

<?php include 'includes/footer.php'; ?>