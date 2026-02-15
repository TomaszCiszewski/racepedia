<?php
include "backend/config.php";
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Racepedia Mobile</title>
<link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
:root {
    --bg-primary: #0a0a0a;
    --accent-red: #ff0033;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: var(--bg-primary);
    color: white;
    font-family: 'Montserrat', sans-serif;
    line-height: 1.4;
}

/* Uproszczony navbar mobilny */
.mobile-nav {
    background: rgba(10, 10, 10, 0.98);
    backdrop-filter: blur(10px);
    border-bottom: 2px solid var(--accent-red);
    padding: 15px;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mobile-logo {
    font-family: 'Orbitron', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: white;
    text-decoration: none;
}

.mobile-logo span {
    color: var(--accent-red);
}

.mobile-menu-btn {
    background: none;
    border: 1px solid var(--accent-red);
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 14px;
}

.mobile-menu {
    display: none;
    position: fixed;
    top: 70px;
    left: 0;
    width: 100%;
    background: #111;
    border-bottom: 2px solid var(--accent-red);
    padding: 15px;
    z-index: 999;
}

.mobile-menu.show {
    display: block;
}

.mobile-menu a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 12px 0;
    border-bottom: 1px solid #333;
    font-size: 16px;
}

.mobile-menu a i {
    color: var(--accent-red);
    width: 30px;
}

/* Hero sekcja */
.mobile-hero {
    height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('assets/hero.jpg') center/cover;
    margin-top: 70px;
    padding: 20px;
}

.mobile-hero h1 {
    font-family: 'Orbitron', sans-serif;
    font-size: 2.5rem;
    color: var(--accent-red);
    margin-bottom: 10px;
}

.mobile-hero p {
    font-size: 1rem;
    opacity: 0.9;
}

/* Kafelki */
.mobile-cards {
    padding: 30px 15px;
}

.mobile-card {
    background: #111;
    border: 1px solid rgba(255, 0, 51, 0.3);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s;
}

.mobile-card:active {
    transform: scale(0.98);
    border-color: var(--accent-red);
}

.mobile-card h3 {
    font-family: 'Orbitron', sans-serif;
    color: var(--accent-red);
    font-size: 1.3rem;
    margin-bottom: 8px;
}

.mobile-card p {
    font-size: 0.9rem;
    color: #ccc;
    margin: 0;
}

/* Footer */
.mobile-footer {
    background: #111;
    border-top: 1px solid rgba(255, 0, 51, 0.3);
    padding: 30px 15px;
    text-align: center;
}

.mobile-footer p {
    margin: 8px 0;
    font-size: 0.9rem;
}

.mobile-footer a {
    color: var(--accent-red);
    text-decoration: none;
}

.mobile-footer input {
    width: 100%;
    padding: 12px;
    background: #222;
    border: 1px solid #333;
    border-radius: 5px;
    color: white;
    margin-bottom: 10px;
}

.mobile-footer button {
    width: 100%;
    padding: 12px;
    background: var(--accent-red);
    border: none;
    border-radius: 5px;
    color: white;
    font-weight: 600;
}

/* Przycisk powrotu do pełnej wersji */
.full-site-link {
    text-align: center;
    padding: 20px 15px;
    background: #111;
    border-top: 1px solid rgba(255, 0, 51, 0.3);
}

.full-site-link a {
    color: var(--accent-red);
    text-decoration: none;
    font-size: 0.9rem;
}
</style>
</head>
<body>

<!-- Uproszczony navbar mobilny -->
<div class="mobile-nav">
    <a href="index.php" class="mobile-logo">RACE<span>PEDIA</span></a>
    <button class="mobile-menu-btn" onclick="toggleMenu()">
        <i class="fas fa-bars"></i> Menu
    </button>
</div>

<!-- Menu mobilne -->
<div class="mobile-menu" id="mobileMenu">
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="baza.php"><i class="fas fa-database"></i> Baza wiedzy</a>
        <a href="forum.php"><i class="fas fa-comments"></i> Forum</a>
        <a href="konto.php"><i class="fas fa-user"></i> Moje konto</a>
        <?php if($_SESSION['user_role'] == 'Administrator'): ?>
            <a href="admin.php"><i class="fas fa-crown"></i> Panel admina</a>
        <?php endif; ?>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Wyloguj</a>
    <?php else: ?>
        <a href="index.php"><i class="fas fa-home"></i> Strona główna</a>
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
        <a href="register.php"><i class="fas fa-user-plus"></i> Rejestracja</a>
    <?php endif; ?>
</div>

<!-- Hero sekcja -->
<section class="mobile-hero">
    <div>
        <h1>RACEPEDIA</h1>
        <p>Motorsport Knowledge Base & Community</p>
    </div>
</section>

<!-- Kafelki informacyjne -->
<section class="mobile-cards">
    <div class="mobile-card">
        <h3>F1</h3>
        <p>Najwyższa klasa wyścigowa jednomiejscowych bolidów.</p>
    </div>
    
    <div class="mobile-card">
        <h3>WRC</h3>
        <p>Rajdowe mistrzostwa świata – prędkość i precyzja.</p>
    </div>
    
    <div class="mobile-card">
        <h3>WEC</h3>
        <p>Długodystansowe wyścigi, w tym 24h Le Mans. Aktualnym mistrzem jest Robert Kubica.</p>
    </div>
</section>

<!-- Link do pełnej wersji -->
<div class="full-site-link">
    <a href="?fullsite=1"><i class="fas fa-desktop me-2"></i>Przejdź do pełnej wersji</a>
</div>

<!-- Footer -->
<footer class="mobile-footer">
    <p>📸 <a href="#">Instagram</a> | 👍 <a href="#">Facebook</a></p>
    <p>📩 kontakt@racepedia.pl</p>
    
    <form>
        <input type="email" placeholder="Twój email">
        <button type="submit">Wyślij</button>
    </form>
    
    <p class="mt-4" style="color: #666;">© 2026 Racepedia Mobile</p>
</footer>

<script>
function toggleMenu() {
    document.getElementById('mobileMenu').classList.toggle('show');
}

// Zamknij menu po kliknięciu w link
document.querySelectorAll('.mobile-menu a').forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.remove('show');
    });
});

// Zamknij menu po kliknięciu poza nim
document.addEventListener('click', (e) => {
    if (!e.target.closest('.mobile-nav') && !e.target.closest('.mobile-menu')) {
        document.getElementById('mobileMenu').classList.remove('show');
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>