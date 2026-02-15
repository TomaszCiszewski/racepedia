<?php
include "backend/config.php";

// Sprawdzenie czy użytkownik jest zalogowany i ma rolę administratora
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Administrator') { 
    header("Location: index.php"); 
    exit();
}

// Pobierz dane administratora
$stmt = $conn->prepare("SELECT username, email, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

// Pobierz aktywną zakładkę
$activeTab = $_GET['tab'] ?? 'dashboard';

// Funkcje pomocnicze dla statystyk
function getCount($conn, $table) {
    $result = $conn->query("SELECT COUNT(*) as count FROM $table");
    return $result->fetch_assoc()['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Panel Administratora - Racepedia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    background: #0a0a0a;
    color: white;
    font-family: 'Montserrat', sans-serif;
}

/* Sidebar */
.sidebar {
    background: #111;
    min-height: 100vh;
    border-right: 1px solid #ff003350;
    position: fixed;
    left: 0;
    top: 0;
    width: 280px;
    padding-top: 100px;
    z-index: 900;
}

.sidebar-logo {
    font-family: 'Orbitron', sans-serif;
    font-size: 24px;
    font-weight: 800;
    text-align: center;
    padding: 20px;
    border-bottom: 1px solid #ff003350;
    margin-bottom: 20px;
}

.sidebar-logo span {
    color: #ff0033;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
}

.sidebar-menu li {
    margin: 5px 15px;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #ccc;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.sidebar-menu a i {
    width: 30px;
    color: #ff0033;
    font-size: 18px;
}

.sidebar-menu a:hover {
    background: #ff0033;
    color: white;
    transform: translateX(5px);
}

.sidebar-menu a:hover i {
    color: white;
}

.sidebar-menu a.active {
    background: #ff0033;
    color: white;
}

.sidebar-menu a.active i {
    color: white;
}

/* Main content */
.main-content {
    margin-left: 280px;
    padding: 100px 30px 30px 30px;
}

/* Karty statystyk */
.stat-card {
    background: linear-gradient(145deg, #1a1a1a, #111);
    border: 1px solid #ff0033;
    border-radius: 15px;
    padding: 25px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: #ff0033;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(255, 0, 51, 0.3);
}

.stat-icon {
    font-size: 40px;
    color: #ff0033;
    margin-bottom: 15px;
}

.stat-value {
    font-size: 36px;
    font-weight: 700;
    font-family: 'Orbitron', sans-serif;
    color: #ff0033;
}

.stat-label {
    font-size: 14px;
    color: #ccc;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Tabele */
.table-container {
    background: #111;
    border: 1px solid #ff003350;
    border-radius: 15px;
    padding: 20px;
}

.table {
    color: white;
}

.table thead {
    background: #0a0a0a;
    color: #ff0033;
    font-family: 'Orbitron', sans-serif;
}

.table td, .table th {
    border-color: #ff003350;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: rgba(255, 0, 51, 0.1);
}

/* Formularze */
.form-control, .form-select {
    background: #1a1a1a !important;
    border: 1px solid #333 !important;
    color: white !important;
}

.form-control:focus, .form-select:focus {
    background: #222 !important;
    border-color: #ff0033 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 0, 51, 0.25);
}

/* Przyciski */
.btn-danger {
    background: #ff0033;
    border: none;
}

.btn-danger:hover {
    background: #ff1a47;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 0, 51, 0.3);
}

.btn-outline-danger {
    border-color: #ff0033;
    color: #ff0033;
}

.btn-outline-danger:hover {
    background: #ff0033;
    color: white;
}

/* Badge */
.badge {
    padding: 5px 10px;
    font-weight: 500;
}

/* Nagłówek */
.page-header {
    margin-bottom: 30px;
    border-bottom: 2px solid #ff003350;
    padding-bottom: 15px;
}

.page-header h2 {
    font-family: 'Orbitron', sans-serif;
    color: #ff0033;
    margin: 0;
}

/* Alerty */
.alert {
    background: #1a1a1a;
    border: 1px solid;
    color: white;
}

.alert-success {
    border-color: #00ff33;
}

.alert-danger {
    border-color: #ff0033;
}

/* Responsywność */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
        min-height: auto;
        padding-top: 80px;
    }
    
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
}
</style>
</head>
<body>

<?php include "components/navbar.php"; ?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-logo">
        ADMIN<span>PANEL</span>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="?tab=dashboard" class="<?= $activeTab == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="?tab=users" class="<?= $activeTab == 'users' ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Użytkownicy</span>
            </a>
        </li>
        <li>
            <a href="?tab=drivers" class="<?= $activeTab == 'drivers' ? 'active' : '' ?>">
                <i class="fas fa-helmet-safety"></i>
                <span>Kierowcy</span>
            </a>
        </li>
        <li>
            <a href="?tab=tracks" class="<?= $activeTab == 'tracks' ? 'active' : '' ?>">
                <i class="fas fa-flag-checkered"></i>
                <span>Tory</span>
            </a>
        </li>
        <li>
            <a href="?tab=races" class="<?= $activeTab == 'races' ? 'active' : '' ?>">
                <i class="fas fa-trophy"></i>
                <span>Wyścigi</span>
            </a>
        </li>
        <li>
            <a href="?tab=forum" class="<?= $activeTab == 'forum' ? 'active' : '' ?>">
                <i class="fas fa-comments"></i>
                <span>Forum</span>
            </a>
        </li>
        <li>
            <a href="?tab=stats" class="<?= $activeTab == 'stats' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Statystyki</span>
            </a>
        </li>
        <li>
            <a href="?tab=settings" class="<?= $activeTab == 'settings' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>Ustawienia</span>
            </a>
        </li>
        <li style="margin-top: 30px;">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i>
                <span>Powrót na stronę</span>
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    
    <!-- Komunikaty -->
    <?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= e($_GET['success']) ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i><?= e($_GET['error']) ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <!-- ========== DASHBOARD ========== -->
    <?php if($activeTab == 'dashboard'): ?>
    <div class="page-header">
        <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
        <p class="text-secondary">Witaj w panelu administratora, <?= e($admin['username']) ?>!</p>
    </div>
    
    <div class="row g-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-value"><?= getCount($conn, 'users') ?></div>
                <div class="stat-label">Użytkowników</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-helmet-safety"></i></div>
                <div class="stat-value"><?= getCount($conn, 'drivers') ?></div>
                <div class="stat-label">Kierowców</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-flag-checkered"></i></div>
                <div class="stat-value"><?= getCount($conn, 'tracks') ?></div>
                <div class="stat-label">Torów</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                <div class="stat-value"><?= getCount($conn, 'races') ?></div>
                <div class="stat-label">Wyścigów</div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3" style="color:#ff0033;">Ostatni zarejestrowani użytkownicy</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Login</th>
                            <th>Email</th>
                            <th>Rola</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recentUsers = $conn->query("SELECT username, email, role, join_date FROM users ORDER BY id DESC LIMIT 5");
                        while($u = $recentUsers->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= e($u['username']) ?></td>
                            <td><?= e($u['email']) ?></td>
                            <td><span class="badge bg-danger"><?= e($u['role']) ?></span></td>
                            <td><?= date('d.m.Y', strtotime($u['join_date'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3" style="color:#ff0033;">Ostatnie wyścigi</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center text-secondary">Brak danych</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ========== UŻYTKOWNICY ========== -->
    <?php if($activeTab == 'users'): ?>
    <div class="page-header d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-users me-2"></i>Zarządzanie użytkownikami</h2>
        <span class="badge bg-danger">Łącznie: <?= getCount($conn, 'users') ?></span>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Rola</th>
                    <th>Data dołączenia</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
                while($u = $users->fetch_assoc()):
                ?>
                <tr>
                    <td>#<?= $u['id'] ?></td>
                    <td>
                        <img src="assets/avatars/<?= e($u['avatar']) ?>" 
                             style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #ff0033; object-fit: cover;">
                    </td>
                    <td><?= e($u['username']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td>
                        <form method="POST" action="backend/admin_update_role.php" class="d-flex gap-2">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <select name="role" class="form-select form-select-sm" style="width: 150px;">
                                <option value="Noob" <?= $u['role'] == 'Noob' ? 'selected' : '' ?>>Noob</option>
                                <option value="Niedzielny Kierowca" <?= $u['role'] == 'Niedzielny Kierowca' ? 'selected' : '' ?>>Niedzielny Kierowca</option>
                                <option value="Dobry Kierowca" <?= $u['role'] == 'Dobry Kierowca' ? 'selected' : '' ?>>Dobry Kierowca</option>
                                <option value="Administrator" <?= $u['role'] == 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-save"></i>
                            </button>
                        </form>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($u['join_date'])) ?></td>
                    <td>
                        <?php if($u['id'] != $_SESSION['user_id']): ?>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $u['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                        <?php else: ?>
                        <span class="text-secondary">To Ty</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- ========== KIEROWCY ========== -->
    <?php if($activeTab == 'drivers'): ?>
    <div class="page-header d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-helmet-safety me-2"></i>Zarządzanie kierowcami</h2>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addDriverModal">
            <i class="fas fa-plus me-2"></i>Dodaj kierowcę
        </button>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Zdjęcie</th>
                    <th>Imię i nazwisko</th>
                    <th>Numer</th>
                    <th>Zespół</th>
                    <th>Kraj</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $drivers = $conn->query("SELECT d.*, t.name as team_name FROM drivers d LEFT JOIN teams t ON d.team_id = t.id ORDER BY d.number DESC");
                while($driver = $drivers->fetch_assoc()):
                ?>
                <tr>
                    <td>#<?= $driver['id'] ?></td>
                    <td>
                        <img src="assets/drivers/<?= e($driver['image_path'] ?? 'default.jpg') ?>" 
                             style="width: 50px; height: 50px; border-radius: 5px; object-fit: cover;">
                    </td>
                    <td><?= e($driver['full_name']) ?></td>
                    <td><span class="badge bg-danger"><?= e($driver['number']) ?></span></td>
                    <td><?= e($driver['team_name'] ?? 'Brak zespołu') ?></td>
                    <td><?= e($driver['country']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger me-1" onclick="editDriver(<?= $driver['id'] ?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteDriver(<?= $driver['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- ========== STATYSTYKI ========== -->
    <?php if($activeTab == 'stats'): ?>
    <div class="page-header">
        <h2><i class="fas fa-chart-bar me-2"></i>Statystyki</h2>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3" style="color:#ff0033;">Podsumowanie</h5>
                <canvas id="statsChart"></canvas>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3" style="color:#ff0033;">Najaktywniejsi użytkownicy</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Użytkownik</th>
                            <th>Rola</th>
                            <th>Dołączył</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $topUsers = $conn->query("SELECT username, role, join_date FROM users ORDER BY id DESC LIMIT 5");
                        while($u = $topUsers->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= e($u['username']) ?></td>
                            <td><span class="badge bg-danger"><?= e($u['role']) ?></span></td>
                            <td><?= date('d.m.Y', strtotime($u['join_date'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ========== USTAWIENIA ========== -->
    <?php if($activeTab == 'settings'): ?>
    <div class="page-header">
        <h2><i class="fas fa-cog me-2"></i>Ustawienia</h2>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3" style="color:#ff0033;">Ustawienia strony</h5>
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nazwa strony</label>
                        <input type="text" class="form-control" value="Racepedia">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opis strony</label>
                        <textarea class="form-control" rows="3">Baza wiedzy o motorsporcie</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email kontaktowy</label>
                        <input type="email" class="form-control" value="kontakt@racepedia.pl">
                    </div>
                    <button class="btn btn-danger">Zapisz ustawienia</button>
                </form>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-container">
                <h5 class="mb-3" style="color:#ff0033;">Informacje o systemie</h5>
                <table class="table">
                    <tr>
                        <td>Wersja PHP:</td>
                        <td><?= phpversion() ?></td>
                    </tr>
                    <tr>
                        <td>Serwer:</td>
                        <td><?= $_SERVER['SERVER_SOFTWARE'] ?></td>
                    </tr>
                    <tr>
                        <td>Baza danych:</td>
                        <td>MariaDB</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
</div>

<!-- Modal dodawania kierowcy -->
<div class="modal fade" id="addDriverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#111; border:1px solid #ff0033;">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#ff0033;">Dodaj nowego kierowcę</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_driver.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Imię i nazwisko</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Numer startowy</label>
                        <input type="number" name="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kraj</label>
                        <input type="text" name="country" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zespół</label>
                        <select name="team_id" class="form-select">
                            <option value="">Brak zespołu</option>
                            <?php
                            $teams = $conn->query("SELECT id, name FROM teams");
                            while($team = $teams->fetch_assoc()):
                            ?>
                            <option value="<?= $team['id'] ?>"><?= e($team['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zdjęcie</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opis</label>
                        <textarea name="bio" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Dodaj kierowcę</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Funkcje do zarządzania
function deleteUser(id) {
    if(confirm('Czy na pewno chcesz usunąć tego użytkownika? Ta operacja jest nieodwracalna.')) {
        window.location.href = 'backend/admin_delete_user.php?id=' + id;
    }
}

function deleteDriver(id) {
    if(confirm('Czy na pewno chcesz usunąć tego kierowcę?')) {
        window.location.href = 'backend/admin_delete_driver.php?id=' + id;
    }
}

function editDriver(id) {
    window.location.href = 'admin_edit_driver.php?id=' + id;
}

// Wykres dla statystyk
<?php if($activeTab == 'stats'): ?>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statsChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Użytkownicy', 'Kierowcy', 'Tory', 'Wyścigi'],
            datasets: [{
                data: [
                    <?= getCount($conn, 'users') ?>,
                    <?= getCount($conn, 'drivers') ?>,
                    <?= getCount($conn, 'tracks') ?>,
                    <?= getCount($conn, 'races') ?>
                ],
                backgroundColor: ['#ff0033', '#ff4d4d', '#ff9999', '#ffcccc'],
                borderColor: '#0a0a0a',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });
});
<?php endif; ?>
</script>

</body>
</html>