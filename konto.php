<?php
include "backend/config.php";

// Sprawdzenie logowania
if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit();
}

// Pobieramy dane użytkownika z bazy
$stmt = $conn->prepare("SELECT username, email, role, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Zapisz avatar w sesji dla navbara
$_SESSION['user_avatar'] = $user['avatar'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Konto - Racepedia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    background: #0a0a0a;
    color: white !important;
    font-family: 'Montserrat', sans-serif;
}

.container {
    color: white;
}

.card {
    background: #111 !important;
    border: 1px solid #ff003350 !important;
    border-radius: 15px;
    color: white !important;
}

.card-header {
    background: #0a0a0a !important;
    border-bottom: 1px solid #ff003350 !important;
    color: white !important;
}

.card-body {
    color: white !important;
}

.form-label {
    color: white !important;
    font-weight: 500;
}

.form-control {
    background: #1a1a1a !important;
    border: 1px solid #333 !important;
    color: white !important;
}

.form-control:focus {
    background: #222 !important;
    border-color: #ff0033 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 0, 51, 0.25);
    color: white !important;
}

hr {
    background: #ff003350 !important;
    height: 1px;
    border: none;
}

.avatar-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 3px solid #ff0033;
    object-fit: cover;
    margin-bottom: 20px;
}

.avatar-selector {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

.avatar-option {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid transparent;
    object-fit: cover;
    cursor: pointer;
    transition: all 0.3s ease;
}

.avatar-option:hover {
    transform: scale(1.1);
    border-color: #ff0033;
}

.avatar-option.selected {
    border-color: #ff0033;
    box-shadow: 0 0 15px #ff0033;
}
</style>
</head>
<body>

<?php include "components/navbar.php"; ?>

<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Komunikaty -->
            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" style="background: #0a2a0a; border: 1px solid #00ff33; color: white;">
                    <i class="fas fa-check-circle me-2"></i><?= e($_GET['success']) ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" style="background: #2a0a0a; border: 1px solid #ff0033; color: white;">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= e($_GET['error']) ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0" style="font-family:'Orbitron'; color:#ff0033;">
                        <i class="fas fa-user-circle me-2"></i>Panel konta
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="assets/avatars/<?= e($user['avatar']) ?>" 
                                 alt="Avatar" 
                                 class="avatar-preview"
                                 id="avatarPreview">
                            
                            <h5 style="color:#ff0033; font-family:'Orbitron';"><?= e($user['username']) ?></h5>
                            <span class="badge bg-danger mb-3"><?= e($user['role']) ?></span>
                        </div>
                        <div class="col-md-8">
                            <p style="color: white;"><strong><i class="fas fa-user me-2"></i>Login:</strong> <?= e($user['username']) ?></p>
                            <p style="color: white;"><strong><i class="fas fa-envelope me-2"></i>Email:</strong> <?= e($user['email']) ?></p>
                            <p style="color: white;"><strong><i class="fas fa-tag me-2"></i>Rola:</strong> 
                                <span class="badge bg-danger"><?= e($user['role']) ?></span>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Zmiana avatara -->
                    <h4 class="mt-4 mb-3" style="color:#ff0033;">Zdjęcie profilowe</h4>
                    <form method="POST" action="backend/update_avatar.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Wybierz własne zdjęcie</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-outline-danger mb-4">
                            <i class="fas fa-upload me-2"></i>Prześlij zdjęcie
                        </button>
                    </form>
                    
                    <div class="avatar-selector">
                        <img src="assets/avatars/avatar1.png" class="avatar-option" onclick="selectAvatar('avatar1.png')">
                        <img src="assets/avatars/avatar2.png" class="avatar-option" onclick="selectAvatar('avatar2.png')">
                        <img src="assets/avatars/avatar3.png" class="avatar-option" onclick="selectAvatar('avatar3.png')">
                        <img src="assets/avatars/avatar4.png" class="avatar-option" onclick="selectAvatar('avatar4.png')">
                        <img src="assets/avatars/avatar5.png" class="avatar-option" onclick="selectAvatar('avatar5.png')">
                    </div>
                    
                    <hr>
                    
                    <!-- Zmiana hasła -->
                    <h4 class="mt-4 mb-3" style="color:#ff0033;">Zmiana hasła</h4>
                    <form method="POST" action="backend/update_account.php">
                        <div class="mb-3">
                            <label class="form-label">Nowe hasło</label>
                            <input type="password" name="newpass" class="form-control" 
                                   placeholder="Wprowadź nowe hasło" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Potwierdź nowe hasło</label>
                            <input type="password" name="confirmpass" class="form-control" 
                                   placeholder="Potwierdź nowe hasło" required>
                        </div>
                        <button class="btn btn-danger">
                            <i class="fas fa-key me-2"></i>Zmień hasło
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function selectAvatar(filename) {
    // Wyślij do serwera
    fetch('backend/update_avatar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'avatar=' + filename
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Aktualizuj podgląd
            document.getElementById('avatarPreview').src = 'assets/avatars/' + filename;
            // Odśwież navbar (przeładowanie strony)
            location.reload();
        }
    });
    
    // Wizualne zaznaczenie
    document.querySelectorAll('.avatar-option').forEach(img => {
        img.classList.remove('selected');
    });
    event.target.classList.add('selected');
}
</script>

<!-- Skrypt do zamykania dropdown po kliknięciu -->
<script>
// Zamknij dropdown po kliknięciu poza menu
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        var dropdowns = document.querySelectorAll('.dropdown-menu.show');
        dropdowns.forEach(function(dropdown) {
            dropdown.classList.remove('show');
        });
    }
});
</script>

</body>
</html>