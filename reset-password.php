<?php 
include 'includes/header.php';
include 'includes/db.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

if (empty($token)) {
    $error = "Invalid reset link.";
} else {
    // Check if token is valid
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "This reset link has expired or is invalid.";
    }

    if ($_POST && $user) {
        $new_password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if ($new_password === $confirm && strlen($new_password) >= 6) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $stmt->execute([$hashed, $user['id']]);

            $success = "Password reset successful! You can now login.";
        } else {
            $error = "Passwords do not match or too short.";
        }
    }
}
?>

<section class="py-20 bg-gray-50">
  <div class="max-w-md mx-auto px-6">
    <div class="bg-white rounded-3xl shadow-xl p-10">
      <h2 class="text-3xl font-bold text-center mb-8">Reset Password</h2>

      <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-6 rounded-2xl mb-6 text-center">
          <?= $success ?><br><br>
          <a href="login.php" class="text-green-600 font-semibold">Go to Login →</a>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-6 rounded-2xl mb-6"><?= $error ?></div>
      <?php endif; ?>

      <?php if (!$success && !$error || $user): ?>
        <form method="POST">
          <input type="password" name="password" placeholder="New Password" required 
                 class="w-full p-5 border border-gray-300 rounded-3xl mb-4">
          <input type="password" name="confirm_password" placeholder="Confirm New Password" required 
                 class="w-full p-5 border border-gray-300 rounded-3xl mb-6">
          
          <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-5 rounded-3xl text-xl font-semibold">
            Reset Password
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>