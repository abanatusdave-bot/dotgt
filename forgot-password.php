<?php 
include 'includes/header.php';
include 'includes/db.php';
include 'includes/email_config.php';

$success = '';
$error = '';

if ($_POST) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?")
             ->execute([$token, $expires, $email]);

        $reset_link = "http://localhost/danisat-website/reset-password.php?token=$token";

        $subject = "Reset Your Password - Danisat";
        $body = "
        <h2>Hello {$user['full_name']},</h2>
        <p>Click the button below to reset your password:</p>
        <p><a href='$reset_link' style='background:#15803d;color:white;padding:12px 25px;border-radius:8px;text-decoration:none;'>Reset Password</a></p>
        <p>This link expires in 1 hour.</p>
        ";

        if (sendEmail($email, $subject, $body)) {
            $success = "Password reset link has been sent to your email!";
        } else {
            $error = "Failed to send email. Please check your email settings.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>

<section class="py-20 bg-gray-50">
  <div class="max-w-md mx-auto px-6">
    <div class="bg-white rounded-3xl shadow-xl p-10">
      <h2 class="text-3xl font-bold text-center mb-8">Forgot Password?</h2>
      
      <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-6 rounded-2xl mb-6"><?= $success ?></div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-6 rounded-2xl mb-6"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required 
               class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600 mb-6">
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-5 rounded-3xl text-xl font-semibold">
          Send Reset Link
        </button>
      </form>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>