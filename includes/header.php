<?php 
session_start(); 
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Danisat OneTouch Global Technologies</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    .hero-bg { 
      background-image: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)), url('https://source.unsplash.com/random/1920x1080/?solar-panels'); 
      background-size: cover; 
      background-position: center;
    }
    .marquee { animation: marquee 25s linear infinite; }
    @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

    /* Mobile Menu */
    #mobileMenu {
      transition: all 0.4s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-50 font-sans">

  <!-- Navigation Bar -->
  <nav class="bg-white shadow-xl sticky top-0 z-50 border-b-4 border-green-700">
    <div class="max-w-7xl mx-auto px-5 py-5 flex items-center justify-between">
      
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-blue-700 rounded-3xl flex items-center justify-center text-4xl shadow-lg"><img src="\danisat-website\uploads\logo.png" alt=""></div>
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Danisat</h1>
          <p class="text-[10px] text-yellow-600 tracking-widest">ONETOUCH GLOBAL</p>
        </div>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center gap-8 text-base font-medium">
        <a href="index.php" class="hover:text-green-600 transition">Home</a>
        <a href="about.php" class="hover:text-green-600 transition">About Us</a>
        <a href="services.php" class="hover:text-green-600 transition">Services</a>
        <a href="products.php" class="hover:text-green-600 transition">Products</a>
        <a href="testimonials.php" class="hover:text-green-600 transition">Testimonials</a>
        <a href="contact.php" class="hover:text-green-600 transition">Contact</a>
      </div>

      <!-- Right Side -->
      <div class="flex items-center gap-4">
        <?php if (isset($_SESSION['user_id'])): ?>
          <span class="hidden sm:block text-sm font-medium text-gray-700">
            Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
          </span>
        <?php endif; ?>

        <a href="contact.php" class="hidden md:block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-6 py-3 rounded-2xl transition text-sm">
          Get Quote
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="logout.php" class="text-red-600 hover:text-red-700 text-sm font-medium">Logout</a>
        <?php else: ?>
          <a href="login.php" class="hidden sm:block text-sm font-medium hover:text-green-600">Login</a>
          <a href="register.php" class="bg-green-600 text-white px-5 py-2.5 rounded-2xl text-sm hover:bg-green-700 transition">Register</a>
        <?php endif; ?>

        <!-- Hamburger Button -->
        <button id="menuBtn" class="md:hidden text-3xl text-green-700 focus:outline-none">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t py-6 px-5">
      <div class="flex flex-col gap-5 text-lg font-medium">
        <a href="index.php" class="py-2 hover:text-green-600">Home</a>
        <a href="about.php" class="py-2 hover:text-green-600">About Us</a>
        <a href="services.php" class="py-2 hover:text-green-600">Services</a>
        <a href="products.php" class="py-2 hover:text-green-600">Products</a>
        <a href="testimonials.php" class="py-2 hover:text-green-600">Testimonials</a>
        <a href="contact.php" class="py-2 hover:text-green-600">Contact</a>
        
        <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="login.php" class="py-2 text-green-600">Login</a>
          <a href="register.php" class="py-3 bg-green-600 text-white text-center rounded-2xl">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <script>
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      const icon = menuBtn.querySelector('i');
      if (icon.classList.contains('fa-bars')) {
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-times');
      } else {
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
      }
    });
  </script>