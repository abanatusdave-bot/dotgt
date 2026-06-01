<?php 
include 'includes/header.php';
include 'includes/db.php';
include 'includes/email_config.php';

$success = '';
$error = '';

if ($_POST) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(50));
            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

            $stmt = $pdo->prepare("INSERT INTO users 
                (full_name, email, phone, password, verification_token, verification_expires, email_verified, role) 
                VALUES (?, ?, ?, ?, ?, ?, '0', 'customer')");
            
            if ($stmt->execute([$full_name, $email, $phone, $hashed_password, $token, $expires])) {
                
                $verify_link = "http://localhost/danisat-website/verify-email.php?token=$token";

                $subject = "Verify Your Email - Danisat OneTouch";
                $body = "
                <h2>Welcome to Danisat OneTouch, {$full_name}!</h2>
                <p>Thank you for registering. Please click the button below to verify your email address:</p>
                <p style='text-align:center; margin:30px 0;'>
                    <a href='{$verify_link}' 
                       style='background:#15803d; color:white; padding:15px 30px; border-radius:8px; text-decoration:none; font-weight:bold;'>
                        Verify My Email
                    </a>
                </p>
                <p>This link will expire in 24 hours.</p>
                <p>Best regards,<br><strong>Danisat Team</strong></p>
                ";

                if (sendEmail($email, $subject, $body)) {
                    $success = "Registration successful!<br>Please check your email to verify your account.";
                } else {
                    $error = "Registration completed, but failed to send verification email.";
                }
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<section class="py-20 bg-gray-50">
  <div class="max-w-md mx-auto px-6">
    <div class="bg-white rounded-3xl shadow-xl p-10">
      <div class="flex justify-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-blue-700 rounded-2xl flex items-center justify-center text-4xl">☀️</div>
      </div>
      
      <h2 class="text-3xl font-bold text-center mb-8">Create Account</h2>

      <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 p-6 rounded-2xl mb-6 text-center">
          <?= $success ?>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 p-6 rounded-2xl mb-6">
          <?= $error ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-6">
        <input type="text" name="full_name" placeholder="Full Name" required 
               class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600">
        
        <input type="email" name="email" placeholder="Email Address" required 
               class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600">
        
        <input type="tel" name="phone" placeholder="Phone Number" required 
               class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600">
        
        <input type="password" name="password" placeholder="Password (min 6 characters)" required 
               class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600">
        
        <input type="password" name="confirm_password" placeholder="Confirm Password" required 
               class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600">
        
        <button type="submit" 
                class="w-full bg-green-600 hover:bg-green-700 text-white py-5 rounded-3xl text-xl font-semibold transition">
          Register
        </button>
      </form>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>