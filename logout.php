<?php 
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logged Out - Danisat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
  <div class="text-center">
    <div class="text-6xl mb-6">👋</div>
    <h2 class="text-3xl font-bold mb-4">You have been logged out</h2>
    <p class="text-gray-600 mb-8">Thank you for visiting Danisat OneTouch</p>
    <a href="index.php" 
       class="bg-green-600 text-white px-8 py-4 rounded-2xl inline-block hover:bg-green-700 transition">
      Return to Homepage
    </a>
  </div>
</body>
</html>