<?php if(session_status() === PHP_SESSION_NONE){ session_start(); } ?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-4"
     style="background:rgba(0,0,0,0.9); backdrop-filter:blur(10px);">

  <a class="navbar-brand" href="index.php"
     style="font-family:Orbitron; color:#ff0033; font-weight:800;">
     Racepedia
  </a>

  <div class="ms-auto">

  <?php if(isset($_SESSION['user'])): ?>

      <a href="baza.php" class="btn btn-outline-light me-2">Baza wiedzy</a>
      <a href="forum.php" class="btn btn-outline-light me-2">Forum</a>

      <a href="index.php" class="btn btn-outline-light me-2">
          👤 Konto
      </a>

      <a href="logout.php" class="btn btn-danger">Wyloguj</a>

  <?php else: ?>

      <a href="login.php" class="btn btn-outline-light me-2">Login</a>
      <a href="register.php" class="btn btn-danger">Rejestracja</a>

  <?php endif; ?>

  </div>
</nav>
