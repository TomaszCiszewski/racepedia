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

// Pobierz podstawowe statystyki
$driversCount = getCount($conn, 'drivers');
$teamsCount = getCount($conn, 'teams');
$tracksCount = getCount($conn, 'tracks');
$racesCount = getCount($conn, 'f1_races_2026');

// Pobierz klasyfikację kierowców
$driverStandings = $conn->query("
    SELECT fs.*, d.full_name as driver_name, t.name as team_name
    FROM f1_driver_standings fs
    JOIN drivers d ON fs.driver_id = d.id
    JOIN teams t ON d.team_id = t.id
    WHERE fs.season = 2026
    ORDER BY fs.position ASC
");

// Pobierz klasyfikację konstruktorów
$constructorStandings = $conn->query("
    SELECT cs.*, t.name as team_name
    FROM f1_constructor_standings cs
    JOIN teams t ON cs.team_id = t.id
    WHERE cs.season = 2026
    ORDER BY cs.position ASC
");

// Pobierz kalendarz wyścigów
$races = $conn->query("
    SELECT r.*, t.name as circuit_name, t.country
    FROM f1_races_2026 r
    JOIN tracks t ON r.circuit = t.name
    ORDER BY r.date ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Panel centrum F1 2026 - Racepedia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    background: #0a0a0a;
    color: white;
    font-family: 'Montserrat', sans-serif;
    padding: 20px;
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
    margin-bottom: 30px;
}

.table-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 20px;
    color: #ff0033;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ff003350;
    display: flex;
    align-items: center;
    gap: 10px;
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
.btn-f1 {
    background: #ff0033;
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.btn-f1:hover {
    background: #ff1a47;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 0, 51, 0.3);
}

.btn-outline-f1 {
    background: transparent;
    border: 1px solid #ff0033;
    color: #ff0033;
    padding: 8px 16px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.btn-outline-f1:hover {
    background: #ff0033;
    color: white;
}

/* Nagłówek */
.page-header {
    margin-bottom: 30px;
    border-bottom: 2px solid #ff003350;
    padding-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h2 {
    font-family: 'Orbitron', sans-serif;
    color: #ff0033;
    margin: 0;
}

/* Badge */
.position-1 { color: gold; font-weight: 700; }
.position-2 { color: silver; font-weight: 700; }
.position-3 { color: #cd7f32; font-weight: 700; }

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
        F1<span>CENTRUM</span>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="?tab=dashboard" class="<?= $activeTab == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="?tab=calendar" class="<?= $activeTab == 'calendar' ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Kalendarz</span>
            </a>
        </li>
        <li>
            <a href="?tab=drivers" class="<?= $activeTab == 'drivers' ? 'active' : '' ?>">
                <i class="fas fa-crown"></i>
                <span>Mistrzostwa kierowców</span>
            </a>
        </li>
        <li>
            <a href="?tab=teams" class="<?= $activeTab == 'teams' ? 'active' : '' ?>">
                <i class="fas fa-building"></i>
                <span>Mistrzostwa konstruktorów</span>
            </a>
        </li>
        <li>
            <a href="?tab=results" class="<?= $activeTab == 'results' ? 'active' : '' ?>">
                <i class="fas fa-flag-checkered"></i>
                <span>Wyniki wyścigów</span>
            </a>
        </li>
        <li>
            <a href="?tab=sensor" class="<?= $activeTab == 'sensor' ? 'active' : '' ?>">
                <i class="fas fa-satellite-dish"></i>
                <span>F1 Sensor</span>
            </a>
        </li>
        <li style="margin-top: 30px;">
            <a href="centrum_f1_2026.php">
                <i class="fas fa-arrow-left"></i>
                <span>Wróć do centrum</span>
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
        <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard F1 2026</h2>
        <span class="badge bg-danger">Sezon 2026</span>
    </div>
    
    <div class="row g-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-flag-checkered"></i></div>
                <div class="stat-value"><?= $racesCount ?></div>
                <div class="stat-label">Wyścigów</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-crown"></i></div>
                <div class="stat-value"><?= $driverStandings->num_rows ?></div>
                <div class="stat-label">Kierowców</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-building"></i></div>
                <div class="stat-value"><?= $constructorStandings->num_rows ?></div>
                <div class="stat-label">Zespołów</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-road"></i></div>
                <div class="stat-value"><?= $tracksCount ?></div>
                <div class="stat-label">Torów</div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="table-container">
                <div class="table-title">
                    <i class="fas fa-crown"></i> Top 5 kierowców
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Poz.</th>
                            <th>Kierowca</th>
                            <th>Zespół</th>
                            <th>Punkty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $driverStandings->data_seek(0);
                        $topDrivers = 0;
                        while($d = $driverStandings->fetch_assoc()): 
                            if($topDrivers++ >= 5) break;
                        ?>
                        <tr>
                            <td class="<?= 'position-' . $d['position'] ?>"><?= $d['position'] ?></td>
                            <td><?= e($d['driver_name']) ?></td>
                            <td><?= e($d['team_name']) ?></td>
                            <td><strong><?= $d['points'] ?></strong></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-container">
                <div class="table-title">
                    <i class="fas fa-building"></i> Top 5 konstruktorów
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Poz.</th>
                            <th>Zespół</th>
                            <th>Punkty</th>
                            <th>Zwycięstwa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $constructorStandings->data_seek(0);
                        $topTeams = 0;
                        while($t = $constructorStandings->fetch_assoc()): 
                            if($topTeams++ >= 5) break;
                        ?>
                        <tr>
                            <td class="<?= 'position-' . $t['position'] ?>"><?= $t['position'] ?></td>
                            <td><?= e($t['team_name']) ?></td>
                            <td><strong><?= $t['points'] ?></strong></td>
                            <td><?= $t['wins'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ========== KALENDARZ ========== -->
    <?php if($activeTab == 'calendar'): ?>
    <div class="page-header">
        <h2><i class="fas fa-calendar-alt me-2"></i>Kalendarz F1 2026</h2>
        <button class="btn btn-f1" data-bs-toggle="modal" data-bs-target="#addRaceModal">
            <i class="fas fa-plus me-2"></i>Dodaj wyścig
        </button>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Runda</th>
                    <th>Grand Prix</th>
                    <th>Tor</th>
                    <th>Kraj</th>
                    <th>Data</th>
                    <th>Sprint</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php while($race = $races->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $race['round'] ?></td>
                    <td><?= e($race['grand_prix']) ?></td>
                    <td><?= e($race['circuit']) ?></td>
                    <td><?= e($race['country']) ?></td>
                    <td><?= date('d.m.Y', strtotime($race['date'])) ?></td>
                    <td>
                        <?php if($race['sprint_date']): ?>
                            <span class="badge bg-warning text-dark">TAK</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">NIE</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($race['status'] == 'completed'): ?>
                            <span class="badge bg-success">Zakończony</span>
                        <?php elseif($race['date'] < date('Y-m-d')): ?>
                            <span class="badge bg-danger">Minął</span>
                        <?php else: ?>
                            <span class="badge bg-primary">Nadchodzący</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-f1 me-1">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- ========== MISTRZOSTWA KIEROWCÓW ========== -->
    <?php if($activeTab == 'drivers'): ?>
        <div class="page-header">
            <h2><i class="fas fa-crown me-2"></i>Mistrzostwa Kierowców 2026</h2>
            <button class="btn btn-f1" data-bs-toggle="modal" data-bs-target="#addDriverStandingModal">
                <i class="fas fa-plus me-2"></i>Dodaj pozycję
            </button>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Poz.</th>
                        <th>Kierowca</th>
                        <th>Zespół</th>
                        <th>Punkty</th>
                        <th>Zwycięstwa</th>
                        <th>Podia</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
        <?php 
        $driverStandings->data_seek(0);
        while($d = $driverStandings->fetch_assoc()): 
        ?>
        <tr>
            <td class="<?= 'position-' . $d['position'] ?>">
                <strong><?= $d['position'] ?></strong>
            </td>
            <td><?= e($d['driver_name']) ?></td>
            <td><?= e($d['team_name']) ?></td>
            <td>
                <form method="POST" action="backend/update_driver_standing.php" class="d-flex gap-2">
                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                    <input type="number" name="points" value="<?= $d['points'] ?>" class="form-control form-control-sm" style="width: 80px;" step="0.5">
                    <button type="submit" class="btn btn-sm btn-outline-f1">
                        <i class="fas fa-save"></i>
                    </button>
                </form>
            </td>
            <td>
                <form method="POST" action="backend/update_driver_wins.php" class="d-flex gap-2">
                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                    <input type="number" name="wins" value="<?= $d['wins'] ?>" class="form-control form-control-sm" style="width: 70px;" min="0">
                    <button type="submit" class="btn btn-sm btn-outline-f1">
                        <i class="fas fa-save"></i>
                    </button>
                </form>
            </td>
            <td>
                <form method="POST" action="backend/update_driver_podiums.php" class="d-flex gap-2">
                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                    <input type="number" name="podiums" value="<?= $d['podiums'] ?>" class="form-control form-control-sm" style="width: 70px;" min="0">
                    <button type="submit" class="btn btn-sm btn-outline-f1">
                        <i class="fas fa-save"></i>
                    </button>
                </form>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteDriverStanding(<?= $d['id'] ?>)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- ========== MISTRZOSTWA KONSTRUKTORÓW ========== -->
    <?php if($activeTab == 'teams'): ?>
    <div class="page-header">
        <h2><i class="fas fa-building me-2"></i>Mistrzostwa Konstruktorów 2026</h2>
        <button class="btn btn-f1" data-bs-toggle="modal" data-bs-target="#addTeamStandingModal">
            <i class="fas fa-plus me-2"></i>Dodaj pozycję
        </button>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Poz.</th>
                    <th>Zespół</th>
                    <th>Punkty</th>
                    <th>Zwycięstwa</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
    <?php 
    $constructorStandings->data_seek(0);
    while($t = $constructorStandings->fetch_assoc()): 
    ?>
    <tr>
        <td class="<?= 'position-' . $t['position'] ?>">
            <strong><?= $t['position'] ?></strong>
        </td>
        <td><?= e($t['team_name']) ?></td>
        <td>
            <form method="POST" action="backend/update_team_standing.php" class="d-flex gap-2">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <input type="number" name="points" value="<?= $t['points'] ?>" class="form-control form-control-sm" style="width: 80px;" step="0.5">
                <button type="submit" class="btn btn-sm btn-outline-f1">
                    <i class="fas fa-save"></i>
                </button>
            </form>
        </td>
        <td>
            <form method="POST" action="backend/update_team_wins.php" class="d-flex gap-2">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <input type="number" name="wins" value="<?= $t['wins'] ?>" class="form-control form-control-sm" style="width: 70px;" min="0">
                <button type="submit" class="btn btn-sm btn-outline-f1">
                    <i class="fas fa-save"></i>
                </button>
            </form>
        </td>
        <td>
            <button class="btn btn-sm btn-outline-danger" onclick="deleteTeamStanding(<?= $t['id'] ?>)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- ========== F1 SENSOR ========== -->
    <?php if($activeTab == 'sensor'): ?>
    <div class="page-header">
        <h2><i class="fas fa-satellite-dish me-2"></i>F1 Sensor - status</h2>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="table-container">
                <div class="table-title">
                    <i class="fas fa-info-circle"></i> Status połączenia
                </div>
                <?php
                $f1DataPath = __DIR__ . '/backend/f1_data/';
                $files = glob($f1DataPath . '*.json');
                $latestFile = 0;
                foreach ($files as $file) {
                    $mtime = filemtime($file);
                    if ($mtime > $latestFile) $latestFile = $mtime;
                }
                $isLive = (time() - $latestFile) < 30;
                ?>
                <table class="table">
                    <tr>
                        <td>Status:</td>
                        <td>
                            <?php if($isLive): ?>
                                <span class="badge bg-success">AKTYWNY</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">NIEAKTYWNY</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Ostatnia aktualizacja:</td>
                        <td><?= $latestFile > 0 ? date('H:i:s', $latestFile) : 'BRAK' ?></td>
                    </tr>
                    <tr>
                        <td>Liczba plików:</td>
                        <td><?= count($files) ?></td>
                    </tr>
                    <tr>
                        <td>Ścieżka danych:</td>
                        <td><small><?= $f1DataPath ?></small></td>
                    </tr>
                </table>
                
                <button class="btn btn-f1 mt-3" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Odśwież status
                </button>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-container">
                <div class="table-title">
                    <i class="fas fa-file"></i> Pliki danych
                </div>
                <ul class="list-group list-group-flush" style="background: transparent;">
                    <?php foreach($files as $file): ?>
                    <li class="list-group-item" style="background: #1a1a1a; color: white; border-color: #333;">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-file-code me-2" style="color: #ff0033;"></i><?= basename($file) ?></span>
                            <span class="text-secondary"><?= date('H:i:s', filemtime($file)) ?></span>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
</div>

<!-- Modal dodawania wyścigu -->
<div class="modal fade" id="addRaceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#111; border:1px solid #ff0033;">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#ff0033;">Dodaj wyścig</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_f1_race.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Runda</label>
                        <input type="number" name="round" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grand Prix</label>
                        <input type="text" name="grand_prix" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tor</label>
                        <select name="circuit" class="form-select" required>
                            <option value="">Wybierz tor</option>
                            <?php 
                            $tracks = $conn->query("SELECT name FROM tracks ORDER BY name");
                            while($track = $tracks->fetch_assoc()): 
                            ?>
                            <option value="<?= e($track['name']) ?>"><?= e($track['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data wyścigu</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data kwalifikacji</label>
                        <input type="date" name="qualifying_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data sprintu (opcjonalnie)</label>
                        <input type="date" name="sprint_date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-f1 w-100">Dodaj wyścig</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal dodawania pozycji kierowcy -->
<div class="modal fade" id="addDriverStandingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#111; border:1px solid #ff0033;">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#ff0033;">Dodaj pozycję w mistrzostwach kierowców</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_driver_standing.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Kierowca</label>
                        <select name="driver_id" class="form-select" required>
                            <option value="">Wybierz kierowcę</option>
                            <?php 
                            $drivers = $conn->query("SELECT id, full_name FROM drivers WHERE is_active = 1 ORDER BY full_name");
                            while($driver = $drivers->fetch_assoc()): 
                            ?>
                            <option value="<?= $driver['id'] ?>"><?= e($driver['full_name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pozycja</label>
                        <input type="number" name="position" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Punkty</label>
                        <input type="number" name="points" class="form-control" required step="0.5" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zwycięstwa</label>
                        <input type="number" name="wins" class="form-control" value="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Podia</label>
                        <input type="number" name="podiums" class="form-control" value="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sezon</label>
                        <input type="number" name="season" class="form-control" value="2026" readonly>
                    </div>
                    <button type="submit" class="btn btn-f1 w-100">Dodaj pozycję</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal dodawania pozycji konstruktora -->
<div class="modal fade" id="addTeamStandingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#111; border:1px solid #ff0033;">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#ff0033;">Dodaj pozycję w mistrzostwach konstruktorów</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_team_standing.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Zespół</label>
                        <select name="team_id" class="form-select" required>
                            <option value="">Wybierz zespół</option>
                            <?php 
                            $teams = $conn->query("SELECT id, name FROM teams WHERE is_active = 1 ORDER BY name");
                            while($team = $teams->fetch_assoc()): 
                            ?>
                            <option value="<?= $team['id'] ?>"><?= e($team['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pozycja</label>
                        <input type="number" name="position" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Punkty</label>
                        <input type="number" name="points" class="form-control" required step="0.5" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zwycięstwa</label>
                        <input type="number" name="wins" class="form-control" value="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sezon</label>
                        <input type="number" name="season" class="form-control" value="2026" readonly>
                    </div>
                    <button type="submit" class="btn btn-f1 w-100">Dodaj pozycję</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function deleteDriverStanding(id) {
    if(confirm('Czy na pewno chcesz usunąć tę pozycję z klasyfikacji kierowców?')) {
        window.location.href = 'backend/delete_driver_standing.php?id=' + id;
    }
}

function deleteTeamStanding(id) {
    if(confirm('Czy na pewno chcesz usunąć tę pozycję z klasyfikacji konstruktorów?')) {
        window.location.href = 'backend/delete_team_standing.php?id=' + id;
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>