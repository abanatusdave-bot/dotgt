<?php 
include 'includes/header.php';
include 'includes/db.php';

$success = false;
$error = '';

if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $service = $_POST['service'];
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, service, message) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $service, $message]);

        // Send Email Notification
        $to = "info@danisat.com"; // ← CHANGE THIS TO YOUR REAL EMAIL
        $subject = "New Contact Form Submission from $name";
        $body = "Name: $name\n";
        $body .= "Email: $email\n";
        $body .= "Phone: $phone\n";
        $body .= "Service Interested: $service\n\n";
        $body .= "Message:\n$message\n";

        $headers = "From: noreply@danisat.com";

        if (mail($to, $subject, $body, $headers)) {
            $success = true;
        } else {
            $success = true; // Still show success even if email fails
        }
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<section class="py-20 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid md:grid-cols-2 gap-16">
      
      <!-- Contact Form -->
      <div>
        <h2 class="text-4xl font-bold text-gray-900 mb-8">Get In Touch</h2>
        <p class="text-gray-600 mb-10">Ready to power your home or secure your property? Reach out to us.</p>

        <?php if ($success): ?>
          <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl mb-8">
            ✅ Thank you! Your message has been sent successfully. We will contact you soon.
          </div>
        <?php endif; ?>

        <?php if ($error): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-2xl mb-8">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
          <div>
            <input type="text" name="name" required 
                   class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600"
                   placeholder="Your Full Name">
          </div>
          
          <div>
            <input type="email" name="email" required 
                   class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600"
                   placeholder="Email Address">
          </div>
          
          <div>
            <input type="tel" name="phone" required 
                   class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600"
                   placeholder="Phone Number (e.g. 08012345678)">
          </div>

          <div>
            <select name="service" required 
                    class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600">
              <option value="">Select Service</option>
              <option value="Solar Installation">Solar Installation</option>
              <option value="CCTV Installation">CCTV Installation</option>
              <option value="Smart Home Automation">Smart Home Automation</option>
              <option value="Electric Gate Motor">Electric Gate Motor Installation</option>
              <option value="Electric Fencing">Electric Fencing</option>
              <option value="Palisade & Mesh">Palisade, Barbed Wire & Mesh</option>
              <option value="Security Devices">Security Devices Supply</option>
            </select>
          </div>

          <div>
            <textarea name="message" rows="6" required 
                      class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600"
                      placeholder="Tell us more about your project..."></textarea>
          </div>

          <button type="submit" 
                  class="w-full bg-green-600 hover:bg-green-700 text-white py-5 rounded-3xl text-xl font-semibold transition">
            Send Message
          </button>
        </form>
      </div>

      <!-- Contact Info & Map -->
      <div class="space-y-10">
        <div>
          <h3 class="text-2xl font-semibold mb-6">Contact Information</h3>
          
          <div class="space-y-6">
            <div class="flex gap-4">
              <div class="text-4xl">📍</div>
              <div>
                <p class="font-medium">Address</p>
                <p class="text-gray-600">Plot 5, Coatal Road, Orudu New Town, Opp. Baba Adisa Bus Stop, Km 53, Lekki Epe Exp. Way Ibeju Lekki, Lagos, Nigeria</p>
              </div>
            </div>
            
            <div class="flex gap-4">
              <div class="text-4xl">📞</div>
              <div>
                <p class="font-medium">Phone</p>
                <p class="text-gray-600">+234 814 991 5176</p>
                <p class="text-gray-600">+234 814 226 9702</p>
              </div>
            </div>
            
            <div class="flex gap-4">
              <div class="text-4xl">✉️</div>
              <div>
                <p class="font-medium">Email</p>
                <p class="text-gray-600">info@danisat.com</p>
              </div>
            </div>
          </div>
        </div>

        <!-- WhatsApp CTA -->
        <a href="https://wa.me/2348149915176" target="_blank"
           class="block bg-green-500 hover:bg-green-600 text-white text-center py-6 rounded-3xl text-xl font-semibold transition">
          💬 Chat with us on WhatsApp
        </a>

        <!-- Google Map Placeholder -->
        <div class="bg-gray-200 h-80 rounded-3xl flex items-center justify-center text-gray-500">
          <div class="text-center">
            <p class="text-6xl mb-4">📍</p>
            <p>Google Map Embed Here</p>
            <p class="text-sm">(Paste your Google Map iframe below)</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>