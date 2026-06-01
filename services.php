<?php 
include 'includes/header.php';
include 'includes/db.php';
?>

<section class="py-20 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-5xl font-bold text-gray-900 mb-4">Our Professional Services</h2>
      <p class="text-xl text-gray-600 max-w-2xl mx-auto">
        Reliable Solar & Security Solutions across Nigeria
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php
      $stmt = $pdo->query("SELECT * FROM services ORDER BY id DESC");
      while ($service = $stmt->fetch()) {
        echo "
        <div class='bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group'>
          <div class='h-64 bg-gradient-to-br from-green-600 to-blue-700 flex items-center justify-center text-8xl transition-transform group-hover:scale-110'>
            " . htmlspecialchars($service['icon']) . "
          </div>
          <div class='p-8'>
            <h3 class='text-2xl font-semibold mb-3 text-gray-900'>" . htmlspecialchars($service['title']) . "</h3>
            <p class='text-gray-600 leading-relaxed'>" . nl2br(htmlspecialchars($service['description'])) . "</p>
            
            <div class='mt-8 flex gap-3'>
              <a href='contact.php' 
                 class='flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-4 rounded-2xl font-medium transition'>
                Request Service
              </a>
              <a href='https://wa.me/2348149915176' target='_blank'
                 class='flex-1 border border-green-600 text-green-600 hover:bg-green-50 text-center py-4 rounded-2xl font-medium transition'>
                WhatsApp Us
              </a>
            </div>
          </div>
        </div>";
      }

      // If no services yet, show default ones
      if ($stmt->rowCount() == 0) {
        echo "
        <div class='col-span-3 text-center py-20 text-gray-500'>
         
        </div>";
      }
      ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>