<?php
// Sprawdź czy sesja jest już uruchomiona
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sprawdź czy użytkownik jest zalogowany
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
$userAvatar = $_SESSION['user_avatar'] ?? 'default.jpg';
$userRole = $_SESSION['user_role'] ?? '';
$isAdmin = ($userRole == 'Administrator');
?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-4"
     style="background:rgba(0,0,0,0.9); backdrop-filter:blur(10px); border-bottom: 2px solid #ff0033; height: 80px;">
    
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php" 
           style="font-family: 'Orbitron', sans-serif; font-size: 28px; font-weight: 800; letter-spacing: 2px;">
            RACE<span style="color: #ff0033;">PEDIA</span>
        </a>

        <!-- Przycisk hamburger dla mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                
                <?php if($isLoggedIn): ?>
                    <!-- Menu dla zalogowanych -->
                    
                    <!-- Baza wiedzy - 3 pionowe kafelki -->
                    <li class="nav-item mx-3">
                        <a class="nav-link d-flex align-items-center" href="baza.php" 
                           style="font-family: 'Montserrat', sans-serif; font-weight: 500; font-size: 16px; color: white;">
                            <div style="display: flex; flex-direction: column; gap: 3px; margin-right: 10px; width: 20px;">
                                <div style="width: 100%; height: 5px; background: #ff0033; border-radius: 2px;"></div>
                                <div style="width: 100%; height: 5px; background: #ff0033; border-radius: 2px;"></div>
                                <div style="width: 100%; height: 5px; background: #ff0033; border-radius: 2px;"></div>
                            </div>
                            <span>Baza wiedzy</span>
                        </a>
                    </li>
                    
                    <!-- Forum - 2 nachodzące dymki -->
                    <li class="nav-item mx-3">
                        <a class="nav-link d-flex align-items-center" href="forum.php" 
                           style="font-family: 'Montserrat', sans-serif; font-weight: 500; font-size: 16px; color: white;">
                            <div style="position: relative; width: 30px; height: 24px; margin-right: 10px;">
                                <i class="fas fa-comment" style="position: absolute; left: 0; top: 0; color: #ff0033; font-size: 20px; z-index: 2; filter: drop-shadow(0 0 5px rgba(255,0,51,0.5));"></i>
                                <i class="fas fa-comment" style="position: absolute; left: 8px; top: 4px; color: #ff0033; font-size: 16px; z-index: 1; opacity: 0.7;"></i>
                            </div>
                            <span>Forum</span>
                        </a>
                    </li>
                    
                    <!-- Konto - dropdown z menu -->
                    <li class="nav-item dropdown mx-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                           style="font-family: 'Montserrat', sans-serif; font-weight: 500; font-size: 16px; color: white;">
                            <img src="assets/avatars/<?= htmlspecialchars($userAvatar) ?>" 
                                 alt="Avatar" 
                                 style="width: 35px; height: 35px; border-radius: 50%; margin-right: 8px; border: 2px solid #ff0033; object-fit: cover;">
                            <?= htmlspecialchars($username) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background: #111; border: 1px solid #ff0033; margin-top: 10px;">
                            <li>
                                <a class="dropdown-item" href="konto.php" style="color: white; padding: 10px 20px; transition: all 0.3s;">
                                    <i class="fas fa-user me-2" style="color: #ff0033;"></i>Mój profil
                                </a>
                            </li>
                            
                            <?php if($isAdmin): ?>
                            <li>
                                <a class="dropdown-item" href="admin.php" style="color: white; padding: 10px 20px; transition: all 0.3s;">
                                    <i class="fas fa-crown me-2" style="color: #ff0033;"></i>Panel administratora
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider" style="background: #ff003350;"></li>
                            <li>
                                <a class="dropdown-item" href="logout.php" style="color: white; padding: 10px 20px; transition: all 0.3s;">
                                    <i class="fas fa-sign-out-alt me-2" style="color: #ff0033;"></i>Wyloguj
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                <?php else: ?>
                    <!-- Menu dla niezalogowanych -->
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white px-3" href="index.php" 
                           style="font-family: 'Montserrat', sans-serif; font-weight: 500; font-size: 16px;">
                            Strona główna
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white px-3" href="login.php" 
                           style="font-family: 'Montserrat', sans-serif; font-weight: 500; font-size: 16px;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link px-4 py-2" href="register.php" 
                           style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 16px; color: #ff0033; border: 1px solid #ff0033; border-radius: 5px;">
                            <i class="fas fa-user-plus me-2"></i>Rejestracja
                        </a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </div>
</nav>

<!-- Miejsce na treść strony (odstęp od fixed navbar) -->
<div style="height: 100px;"></div>

<style>
/* Dodatkowe style dla navbara */
.navbar {
    box-shadow: 0 2px 15px rgba(255, 0, 51, 0.3);
    z-index: 1000;
}

.nav-link {
    transition: all 0.3s ease;
    position: relative;
    padding: 8px 0 !important;
}

.nav-link:hover {
    color: #ff0033 !important;
    transform: translateY(-2px);
}

.nav-link:not(.dropdown-toggle):hover::after {
    content: '';
    position: absolute;
    width: 80%;
    height: 2px;
    bottom: 0;
    left: 50%;
    background: #ff0033;
    transform: translateX(-50%);
}

.nav-link img {
    transition: all 0.3s ease;
}

.nav-link:hover img {
    transform: scale(1.1);
    border-color: white !important;
}

.nav-link:hover .nav-link div div {
    background: white !important;
}

.nav-link:hover i {
    color: white !important;
}

.dropdown-menu {
    border-radius: 10px;
    padding: 8px 0;
    box-shadow: 0 5px 20px rgba(255, 0, 51, 0.3);
}

.dropdown-item {
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background: #ff0033 !important;
    color: white !important;
}

.dropdown-item:hover i {
    color: white !important;
}

@media (max-width: 991px) {
    .navbar-nav {
        background: rgba(0,0,0,0.95);
        padding: 20px;
        border-radius: 10px;
        margin-top: 10px;
        border: 1px solid #ff0033;
    }
    
    .nav-item {
        margin: 8px 0 !important;
    }
    
    .nav-link {
        justify-content: center !important;
    }
    
    .dropdown-menu {
        background: #1a1a1a !important;
        margin-top: 5px !important;
    }
}
</style>