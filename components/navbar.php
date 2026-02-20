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

// Dla celów debugowania (możesz usunąć później)
// error_log("User role: " . $userRole . ", isAdmin: " . ($isAdmin ? 'true' : 'false'));
?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" 
     style="background: rgba(10, 10, 10, 0.95); backdrop-filter: blur(10px); border-bottom: 2px solid #ff0033; height: 80px; z-index: 9999;">
    
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php" 
           style="font-family: 'Orbitron', sans-serif; font-size: 28px; font-weight: 800; letter-spacing: 2px;">
            RACE<span style="color: #ff0033;">PEDIA</span>
        </a>

        <!-- Przycisk hamburger dla mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation"
                style="border: 1px solid #ff0033;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                
                <?php if($isLoggedIn): ?>
                    <!-- Menu dla zalogowanych -->
                     
                    <!-- NOWY PRZYCISK: Centrum F1 2026 -->
                    <li class="nav-item mx-2">
                        <a class="nav-link d-flex align-items-center px-3" href="/racepedia/centrum_f1_2026.php">
                            <div class="f1-icon-container me-2">
                                <i class="fas fa-flag-checkered f1-flag-icon"></i>
                                <span class="f1-year-badge">2026</span>
                            </div>
                            <span>Centrum F1</span>
                        </a>
                    </li>

                    <!-- Baza wiedzy - 3 pionowe kafelki -->
                    <li class="nav-item mx-2">
                        <a class="nav-link d-flex align-items-center px-3" href="/racepedia/baza.php">
                            <div class="menu-icon-stack me-2">
                                <div class="stack-bar"></div>
                                <div class="stack-bar"></div>
                                <div class="stack-bar"></div>
                            </div>
                            <span>Baza wiedzy</span>
                        </a>
                    </li>
                    
                    <!-- Forum - 2 nachodzące dymki -->
                    <li class="nav-item mx-2">
                        <a class="nav-link d-flex align-items-center px-3" href="/racepedia/forum/index.php">
                            <div class="forum-icon-stack me-2">
                                <i class="fas fa-comment forum-icon-main"></i>
                                <i class="fas fa-comment forum-icon-overlay"></i>
                                <!-- Fallback gdy Font Awesome nie działa -->
                                <span class="forum-fallback" style="display: none;">📄</span>
                            </div>
                            <span>Forum</span>
                        </a>
                    </li>
                    
                    <!-- Konto - dropdown z menu -->
                    <li class="nav-item dropdown mx-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                        role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <img src="assets/avatars/<?= e($userAvatar) ?>" 
                                alt="Avatar" 
                                class="user-avatar me-2">
                            <?= e($username) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="/racepedia/konto.php">
                                    <i class="fas fa-user me-2" style="color: #ff0033; width: 20px;"></i>
                                    Moje konto
                                </a>
                            </li>
                            
                            <?php if($isAdmin): ?>
                            <!-- Panel administratora -->
                            <li>
                                <a class="dropdown-item" href="/racepedia/admin.php">
                                    <i class="fas fa-crown me-2" style="color: #ff0033; width: 20px;"></i>
                                    Panel administratora
                                </a>
                            </li>
                            <!-- NOWY PRZYCISK: Panel centrum F1 -->
                            <li>
                                <a class="dropdown-item" href="/racepedia/admin_f1_center.php">
                                    <i class="fas fa-flag-checkered me-2" style="color: #ff0033; width: 20px;"></i>
                                    Panel centrum F1
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="/racepedia/logout.php">
                                    <i class="fas fa-sign-out-alt me-2" style="color: #ff0033; width: 20px;"></i>
                                    Wyloguj
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                <?php else: ?>
                    <!-- Menu dla niezalogowanych -->
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3" href="/racepedia/index.php">
                            Strona główna
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3" href="/racepedia/login.php">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                    <li class="nav-item ms-1">
                        <a class="nav-link register-btn px-4 py-2" href="/racepedia/register.php">
                            <i class="fas fa-user-plus me-2"></i>Rejestracja
                        </a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </div>
</nav>

<!-- Miejsce na treść strony (odstęp od fixed navbar) -->
<div style="height: 80px;"></div>

<style>
/* Podstawowe style dla navbaru */
.navbar {
    box-shadow: 0 2px 20px rgba(255, 0, 51, 0.3);
    transition: all 0.3s ease;
}

/* Style dla linków */
.nav-link {
    font-family: 'Montserrat', sans-serif;
    font-weight: 500;
    font-size: 16px;
    color: white !important;
    padding: 8px 16px !important;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    color: #ff0033 !important;
    background: rgba(255, 0, 51, 0.1);
    transform: translateY(-2px);
}

/* Ikony w menu */
.menu-icon-stack {
    display: flex;
    flex-direction: column;
    gap: 3px;
    width: 20px;
}

.stack-bar {
    width: 100%;
    height: 3px;
    background: #ff0033;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.nav-link:hover .stack-bar {
    background: white;
}

.forum-icon-stack {
    position: relative;
    width: 30px;
    height: 24px;
}

.forum-icon-main {
    position: absolute;
    left: 0;
    top: 0;
    color: #ff0033;
    font-size: 20px;
    z-index: 2;
    filter: drop-shadow(0 0 5px rgba(255,0,51,0.5));
    transition: all 0.3s ease;
}

.forum-icon-overlay {
    position: absolute;
    left: 8px;
    top: 4px;
    color: #ff0033;
    font-size: 16px;
    z-index: 1;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.nav-link:hover .forum-icon-main,
.nav-link:hover .forum-icon-overlay {
    color: white;
}

/* Avatar */
.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid #ff0033;
    object-fit: cover;
    transition: all 0.3s ease;
}

.nav-link:hover .user-avatar {
    transform: scale(1.1);
    border-color: white;
}

/* Dropdown menu */
.dropdown-menu {
    background: #111;
    border: 1px solid #ff0033;
    border-radius: 12px;
    padding: 8px 0;
    margin-top: 12px !important;
    box-shadow: 0 10px 30px rgba(255, 0, 51, 0.3);
    min-width: 220px;
    z-index: 10000 !important;
}

.dropdown-item {
    color: white;
    padding: 10px 20px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background: #ff0033;
    color: white;
    transform: translateX(5px);
}

.dropdown-item:hover i {
    color: white !important;
}

.dropdown-divider {
    background: rgba(255, 0, 51, 0.3);
    margin: 8px 0;
}

/* Przycisk rejestracji dla niezalogowanych */
.register-btn {
    color: #ff0033 !important;
    border: 1px solid #ff0033;
    border-radius: 5px;
    background: transparent;
}

.register-btn:hover {
    background: #ff0033 !important;
    color: white !important;
    border-color: #ff0033;
}

/* Responsywność dla mobile */
@media (max-width: 991px) {
    .navbar-collapse {
        background: rgba(10, 10, 10, 0.98);
        backdrop-filter: blur(10px);
        padding: 20px;
        border-radius: 0 0 15px 15px;
        border: 1px solid #ff0033;
        border-top: none;
        margin-top: 10px;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }
    
    .navbar-nav {
        gap: 10px;
    }
    
    .nav-item {
        width: 100%;
        margin: 5px 0 !important;
    }
    
    .nav-link {
        justify-content: flex-start !important;
        padding: 12px 20px !important;
    }
    
    .dropdown-menu {
        background: #1a1a1a !important;
        border: 1px solid #ff0033;
        margin-left: 20px !important;
        width: calc(100% - 40px);
        position: static !important;
        float: none;
        box-shadow: none;
    }
    
    .dropdown-menu.show {
        display: block;
    }
}

/* Małe ekrany */
@media (max-width: 576px) {
    .navbar-brand {
        font-size: 24px !important;
    }
    
    .nav-link {
        font-size: 14px !important;
    }
}

/* Style dla nowego przycisku */
.nav-link .fa-flag-checkered {
    transition: all 0.3s ease;
}

.nav-link:hover .fa-flag-checkered {
    color: white !important;
    transform: scale(1.1);
}

.nav-link span[style*="position: absolute"] {
    transition: all 0.3s ease;
}

.nav-link:hover span[style*="position: absolute"] {
    background: white !important;
    color: #ff0033 !important;
}

/* Styl dla ikonki Centrum F1 */
.f1-icon-container {
    position: relative;
    width: 30px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.f1-flag-icon {
    color: #ff0033;
    font-size: 20px;
    transition: all 0.3s ease;
}

.f1-year-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff0033;
    color: white;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 4px;
    border-radius: 8px;
    line-height: 1;
    font-family: 'Orbitron', sans-serif;
    border: 1px solid white;
    transition: all 0.3s ease;
}

.nav-link:hover .f1-flag-icon {
    color: white !important;
    transform: scale(1.1);
}

.nav-link:hover .f1-year-badge {
    background: white !important;
    color: #ff0033 !important;
    border-color: #ff0033;
}
</style>

<!-- Dodatkowy skrypt dla dropdown na mobile -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicjalizacja wszystkich dropdown
    var dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(function(dropdown) {
        new bootstrap.Dropdown(dropdown);
    });
    
    // Zapewnij, że dropdown zamyka się po kliknięciu poza nim
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown')) {
            var openDropdowns = document.querySelectorAll('.dropdown-menu.show');
            openDropdowns.forEach(function(menu) {
                var dropdown = bootstrap.Dropdown.getInstance(menu.previousElementSibling);
                if (dropdown) {
                    dropdown.hide();
                }
            });
        }
    });
});
</script>