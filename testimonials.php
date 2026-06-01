<?php 
include 'includes/header.php';
include 'includes/db.php';

// Handle New Testimonial Submission
if ($_POST && isset($_SESSION['user_id'])) {
    $client_name = $_POST['client_name'] ?? $_SESSION['user_name'];
    $message = $_POST['message'];
    $rating = $_POST['rating'];

    // Photo Upload (optional)
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/testimonials/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $photo = $target_dir . time() . "_" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, message, rating, photo) VALUES (?, ?, ?, ?)");
    $stmt->execute([$client_name, $message, $rating, $photo]);

    echo "<script>alert('Thank you! Your testimonial has been submitted and is awaiting approval.');</script>";
}
?>

<section class="py-20 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-5xl font-bold text-gray-900 mb-4">Client Testimonials</h2>
      <p class="text-xl text-gray-600">Real stories from satisfied customers across Nigeria</p>
    </div>

    <!-- Testimonials Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php
      $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC");
      $hasTestimonials = false;

      while ($row = $stmt->fetch()) {
        $hasTestimonials = true;
        $stars = str_repeat('⭐', $row['rating']);
        echo "
        <div class='bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition'>
          <div class='flex items-center gap-4 mb-6'>
            <div class='w-16 h-16 bg-gray-200 rounded-2xl overflow-hidden flex-shrink-0'>
              " . ($row['photo'] ? "<img src='{$row['photo']}' class='w-full h-full object-cover'>" : "<div class='w-full h-full flex items-center justify-center text-4xl'>👤</div>") . "
            </div>
            <div>
              <h4 class='font-semibold text-xl'>" . htmlspecialchars($row['client_name']) . "</h4>
              <div class='text-yellow-400 text-2xl'>$stars</div>
            </div>
          </div>
          <p class='text-gray-600 italic leading-relaxed'>“" . htmlspecialchars($row['message']) . "”</p>
        </div>";
      }

      if (!$hasTestimonials) {
        echo "
        <div class='col-span-3 text-center py-20'>
          <p class='text-2xl text-gray-400'>No testimonials yet.</p>
          <p class='text-gray-500 mt-4'>Be the first to share your experience!</p>
        </div>";
      }
      ?>
    </div>

    <!-- Submit Testimonial Form -->
    <div class="mt-20 bg-white rounded-3xl shadow-xl p-10 max-w-2xl mx-auto">
      <h3 class="text-3xl font-bold text-center mb-8">Share Your Experience</h3>
      
      <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
          <input type="text" name="client_name" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" 
                 class="w-full p-4 border rounded-2xl" placeholder="Your Name">

          <div class="flex gap-2 justify-center text-4xl" id="starRating">
            <?php for($i=1; $i<=5; $i++): ?>
              <span onclick="setRating(<?= $i ?>)" class="cursor-pointer text-gray-300 hover:text-yellow-400">★</span>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="rating" id="ratingInput" value="5">

          <textarea name="message" rows="5" required 
                    class="w-full p-4 border rounded-2xl" 
                    placeholder="Tell us about your experience with Danisat..."></textarea>

          <input type="file" name="photo" accept="image/*" 
                 class="w-full p-4 border rounded-2xl">

          <button type="submit" 
                  class="w-full bg-green-600 hover:bg-green-700 text-white py-5 rounded-3xl text-xl font-semibold transition">
            Submit Testimonial
          </button>
        </form>
      <?php else: ?>
        <div class="text-center py-10">
          <p class="text-xl text-gray-600 mb-6">Please login to submit a testimonial</p>
          <a href="login.php" class="bg-green-600 text-white px-10 py-4 rounded-2xl inline-block">Login Now</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
let currentRating = 5;

function setRating(rating) {
  currentRating = rating;
  document.getElementById('ratingInput').value = rating;
  
  const stars = document.querySelectorAll('#starRating span');
  stars.forEach((star, index) => {
    star.classList.toggle('text-yellow-400', index < rating);
    star.classList.toggle('text-gray-300', index >= rating);
  });
}
</script>

<?php include 'includes/footer.php'; ?>