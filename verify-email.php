<?php 
include 'includes/header.php';
include 'includes/db.php';

$token = $_GET['token'] ?? '';
$message = '';

if ($token) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ? AND verification_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $pdo->prepare("UPDATE users SET email_verified = '1', verification_token = NULL, verification_expires = NULL WHERE id = ?")
             ->execute([$user['id']]);
        $message = "✅ Your email has been successfully verified! You can now login.";
    } else {
        $message = "❌ Invalid or expired verification link.";
    }
} else {
    $message = "No token provided.";
}
?>

<section class="py-20 bg-gray-50 min-h-screen flex items-center">
  <div class="max-w-md mx-auto px-6 text-center">
    <div class="bg-white rounded-3xl shadow-xl p-12">
      <div class="text-6xl mb-6"><?= strpos($message, '✅') !== false ? '🎉' : '⚠️' ?></div>
      <h2 class="text-3xl font-bold mb-6"><?= $message ?></h2>
      <a href="login.php" class="inline-block bg-green-600 text-white px-10 py-4 rounded-2xl font-semibold hover:bg-green-700">
        Go to Login
      </a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>