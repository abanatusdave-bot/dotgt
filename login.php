<?php 
// ALL PHP logic MUST come BEFORE including header
include 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start();   // Start session here only if needed
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect email or password";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="py-20 bg-gray-50 min-h-screen">
  <div class="max-w-md mx-auto px-6">
    <div class="bg-white rounded-3xl shadow-xl p-10">
      <div class="flex justify-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-blue-700 rounded-2xl flex items-center justify-center text-4xl">☀️</div>
      </div>
      
      <h2 class="text-3xl font-bold text-center mb-8">Welcome Back</h2>
      
      <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-2xl mb-8 text-center">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-6">
        <input type="email" name="email" placeholder="Email Address" required 
               class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600">
        
        <input type="password" name="password" placeholder="Password" required 
               class="w-full p-5 border border-gray-300 rounded-3xl focus:outline-none focus:border-green-600">
        
        <button type="submit" 
                class="w-full bg-green-600 hover:bg-green-700 text-white py-5 rounded-3xl text-xl font-semibold transition">
          Login
        </button>
      </form>
      <p class="text-center mt-4">
  <a href="forgot-password.php" class="text-green-600 hover:underline">Forgot Password?</a>
</p>
      
      <p class="text-center mt-8 text-gray-600">
        Don't have an account? 
        <a href="register.php" class="text-green-600 font-medium hover:underline">Register here</a>
      </p>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>