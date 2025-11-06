<?php ?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IAU Syrian Community - Game</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
  <div class="brand">الجالية السورية</div>
  <nav>
    <a class="active" href="index.php">اللعبة</a>
    <a href="admin.php">إدارة اللعبة</a>
    <a href="links.php">الروابط</a>
  </nav>
</header>

<main class="game-area">
  <div class="floating-shapes">
    <div class="logo-rain logo-1">🎯</div>
    <div class="logo-rain logo-2">🎲</div>
    <div class="logo-rain logo-3">🎮</div>
    <div class="logo-rain logo-4">🎪</div>
    <div class="logo-rain logo-5">🎨</div>
    <div class="logo-rain logo-6">🎭</div>
    <div class="logo-rain logo-7">🎯</div>
    <div class="logo-rain logo-8">🎲</div>
    <div class="logo-rain logo-9">🎮</div>
    <div class="logo-rain logo-10">🎪</div>
  </div>
  <div class="hint">قم بالضغط على الدائرة</div>
  <div class="wheel-container">
    <div class="pointer"></div>
    <div class="wheel-wrap">
      <canvas id="wheel" width="500" height="500"></canvas>
    </div>
  </div>
</main>

<div id="questionModal" class="modal hidden">
  <div class="modal-content">
    <div class="modal-title">سؤال ثقافي</div>
    <div id="questionText" class="modal-body">...</div>
    <button onclick="closeModal()">إغلاق</button>
  </div>
</div>

<script src="assets/js/ui.js"></script>
<script src="assets/js/wheel.js"></script>
</body>
</html>
