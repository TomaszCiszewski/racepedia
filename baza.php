<?php
include "backend/config.php";

// Sprawdź czy to mobile
if (isMobile() && !isset($_GET['fullsite'])) {
    if (file_exists('baza_mobile.php')) {
        include 'baza_mobile.php';
        exit();
    }
}

// Sprawdzenie logowania
if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit();
}

// Pobierz aktywną zakładkę
$activeTab = $_GET['tab'] ?? 'drivers';

// ========== POBIERANIE DANYCH Z BAZY ==========

// Pobierz kierowców z zespołami
$drivers = [];
$result = $conn->query("
    SELECT d.*, t.name as team_name 
    FROM drivers d 
    LEFT JOIN teams t ON d.team_id = t.id 
    WHERE d.is_active = 1 
    ORDER BY d.race_wins DESC, d.full_name ASC
");
while ($row = $result->fetch_assoc()) {
    $drivers[] = $row;
}

// Pobierz tory
$tracks = [];
$result = $conn->query("SELECT * FROM tracks WHERE is_active = 1 ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
    $tracks[] = $row;
}

// Pobierz wyścigi (jeśli tabela istnieje)
$races = [];
$checkRaces = $conn->query("SHOW TABLES LIKE 'races'");
if ($checkRaces->num_rows > 0) {
    $result = $conn->query("
        SELECT r.*, t.name as track_name, t.country as track_country,
               d.full_name as winner_name
        FROM races r
        LEFT JOIN tracks t ON r.track_id = t.id
        LEFT JOIN drivers d ON r.winner_driver_id = d.id
        WHERE r.status = 'completed'
        ORDER BY r.race_date DESC
        LIMIT 10
    ");
    while ($row = $result->fetch_assoc()) {
        $races[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baza Wiedzy - Racepedia</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Nasze style -->
    <?php include "components/styles.php"; ?>
</head>
<body>

<?php include "components/navbar.php"; ?>

<!-- Hero sekcja dla bazy wiedzy -->
<section class="knowledge-hero">
    <div class="container">
        <h1 class="knowledge-title">BAZA WIEDZY</h1>
        <p class="knowledge-subtitle">Kompendium wiedzy o świecie motorsportu</p>
    </div>
</section>

<div class="container mt-4 mb-5">
    <!-- Zakładki nawigacyjne -->
    <ul class="nav nav-tabs knowledge-tabs" id="knowledgeTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="?tab=drivers" class="nav-link <?= $activeTab == 'drivers' ? 'active' : '' ?>">
                <i class="fas fa-helmet-safety me-2"></i>Kierowcy
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="?tab=tracks" class="nav-link <?= $activeTab == 'tracks' ? 'active' : '' ?>">
                <i class="fas fa-flag-checkered me-2"></i>Tory
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="?tab=races" class="nav-link <?= $activeTab == 'races' ? 'active' : '' ?>">
                <i class="fas fa-trophy me-2"></i>Wyścigi
            </a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- KIEROWCY -->
        <div class="tab-pane fade show <?= $activeTab == 'drivers' ? 'active' : '' ?>" id="drivers">
            <div class="row g-4">
                <?php if(empty($drivers)): ?>
                    <div class="col-12 text-center text-secondary">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Brak kierowców w bazie danych.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($drivers as $driver): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="driver-card">
                            <div class="driver-image-wrapper" style="height: 280px; overflow: hidden; position: relative;">
                                <img src="assets/drivers/<?= e($driver['image_path'] ?? 'default.jpg') ?>" 
                                    alt="<?= e($driver['full_name']) ?>" 
                                    class="driver-image"
                                    style="width: 100%; height: auto; object-fit: cover; object-position: center 0%; margin-top: -10px;">
                                <div class="driver-number">
                                    #<?= e($driver['number'] ?? 'TBA') ?>
                                </div>
                            </div>
                            <div class="driver-info">
                                <h3 class="driver-name"><?= e($driver['full_name']) ?></h3>
                                <div class="driver-meta">
                                    <span class="driver-team">
                                        <i class="fas fa-car me-1"></i><?= e($driver['team_name'] ?? 'Bez zespołu') ?>
                                    </span>
                                    <span class="driver-country">
                                        <i class="fas fa-globe me-1"></i><?= e($driver['country']) ?>
                                    </span>
                                </div>
                                <?php if($driver['world_titles'] > 0): ?>
                                <div class="text-center mb-2">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-trophy me-1"></i><?= $driver['world_titles'] ?>x Mistrz Świata
                                    </span>
                                </div>
                                <?php endif; ?>
                                <p class="driver-description">
                                    <?= e($driver['bio'] ?? 'Brak opisu') ?>
                                </p>
                                <button class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#driverModal<?= $driver['id'] ?>">
                                    <i class="fas fa-info-circle me-2"></i>Więcej informacji
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal dla kierowcy -->
                    <div class="modal fade" id="driverModal<?= $driver['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content glass-card">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= e($driver['full_name']) ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="assets/drivers/<?= e($driver['image_path'] ?? 'default.jpg') ?>" 
                                                alt="<?= e($driver['full_name']) ?>" 
                                                class="img-fluid rounded"
                                                style="width: 100%; height: auto; object-fit: contain;">
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="text-accent"><?= e($driver['full_name']) ?></h4>
                                            <p><strong><i class="fas fa-car me-2"></i>Zespół:</strong> <?= e($driver['team_name'] ?? 'Brak zespołu') ?></p>
                                            <p><strong><i class="fas fa-globe me-2"></i>Kraj:</strong> <?= e($driver['country']) ?></p>
                                            <p><strong><i class="fas fa-hashtag me-2"></i>Numer:</strong> #<?= e($driver['number'] ?? 'TBA') ?></p>
                                            
                                            <?php if($driver['world_titles'] > 0): ?>
                                            <p><strong><i class="fas fa-trophy me-2"></i>Mistrzostwa świata:</strong> <?= $driver['world_titles'] ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if($driver['race_wins'] > 0): ?>
                                            <p><strong><i class="fas fa-flag-checkered me-2"></i>Zwycięstwa w GP:</strong> <?= $driver['race_wins'] ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if($driver['podiums'] > 0): ?>
                                            <p><strong><i class="fas fa-medal me-2"></i>Podia:</strong> <?= $driver['podiums'] ?></p>
                                            <?php endif; ?>
                                            
                                            <p class="mt-3"><?= e($driver['bio'] ?? 'Brak opisu') ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- TORY -->
        <div class="tab-pane fade show <?= $activeTab == 'tracks' ? 'active' : '' ?>" id="tracks">
            <div class="row g-4">
                <?php if(empty($tracks)): ?>
                    <div class="col-12 text-center text-secondary">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Brak torów w bazie danych.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($tracks as $track): ?>
                    <div class="col-12">
                        <div class="track-card-horizontal">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <div class="track-image-wrapper" style="height: 300px; overflow: hidden;">
                                        <img src="assets/tracks/<?= e($track['image_path'] ?? 'default.jpg') ?>" 
                                            alt="<?= e($track['name']) ?>" 
                                            class="track-image-horizontal"
                                            style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="track-info-horizontal p-4">
                                        <h3 class="track-name-horizontal"><?= e($track['name']) ?></h3>
                                        
                                        <div class="track-meta-horizontal mb-3">
                                            <span class="badge bg-danger me-2">
                                                <i class="fas fa-flag me-1"></i><?= e($track['country']) ?>
                                            </span>
                                            <?php if($track['city']): ?>
                                            <span class="badge bg-dark me-2">
                                                <i class="fas fa-city me-1"></i><?= e($track['city']) ?>
                                            </span>
                                            <?php endif; ?>
                                            <span class="badge bg-dark me-2">
                                                <i class="fas fa-road me-1"></i><?= e($track['length_km']) ?> km
                                            </span>
                                            <?php if($track['laps']): ?>
                                            <span class="badge bg-dark">
                                                <i class="fas fa-flag-checkered me-1"></i><?= $track['laps'] ?> okrążeń
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if($track['lap_record'] && $track['lap_record_driver']): ?>
                                        <div class="winner-info mb-3 p-2" style="background: rgba(255, 0, 51, 0.1); border-left: 4px solid var(--accent-red);">
                                            <i class="fas fa-clock text-accent me-2"></i>
                                            <strong>Rekord okrążenia:</strong> <?= e($track['lap_record']) ?> (<?= e($track['lap_record_driver']) ?>)
                                        </div>
                                        <?php endif; ?>
                                        
                                        <p class="track-description-horizontal"><?= e($track['bio'] ?? 'Brak opisu') ?></p>
                                        
                                        <button class="btn btn-outline-danger mt-3" data-bs-toggle="modal" data-bs-target="#trackModal<?= $track['id'] ?>">
                                            <i class="fas fa-info-circle me-2"></i>Więcej informacji
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal dla toru -->
                    <div class="modal fade" id="trackModal<?= $track['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content glass-card">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= e($track['name']) ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="assets/tracks/<?= e($track['image_path'] ?? 'default.jpg') ?>" 
                                         class="img-fluid rounded mb-3" alt="">
                                    <p><strong><i class="fas fa-flag me-2"></i>Kraj:</strong> <?= e($track['country']) ?></p>
                                    <?php if($track['city']): ?>
                                    <p><strong><i class="fas fa-city me-2"></i>Miasto:</strong> <?= e($track['city']) ?></p>
                                    <?php endif; ?>
                                    <p><strong><i class="fas fa-road me-2"></i>Długość:</strong> <?= e($track['length_km']) ?> km</p>
                                    <?php if($track['laps']): ?>
                                    <p><strong><i class="fas fa-flag-checkered me-2"></i>Liczba okrążeń:</strong> <?= $track['laps'] ?></p>
                                    <?php endif; ?>
                                    <?php if($track['first_gp']): ?>
                                    <p><strong><i class="fas fa-history me-2"></i>Pierwsze Grand Prix:</strong> <?= $track['first_gp'] ?></p>
                                    <?php endif; ?>
                                    <?php if($track['lap_record'] && $track['lap_record_driver']): ?>
                                    <p><strong><i class="fas fa-clock me-2"></i>Rekord okrążenia:</strong> <?= e($track['lap_record']) ?> (<?= e($track['lap_record_driver']) ?>)</p>
                                    <?php endif; ?>
                                    <p class="mt-3"><?= e($track['bio'] ?? 'Brak opisu') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- WYŚCIGI -->
        <div class="tab-pane fade show <?= $activeTab == 'races' ? 'active' : '' ?>" id="races">
            <div class="row g-4">
                <?php if(empty($races)): ?>
                    <div class="col-12 text-center text-secondary">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Brak wyścigów w bazie danych lub tabela races nie istnieje.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($races as $race): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="race-card">
                            <img src="<?= e($race['image_path'] ?? 'assets/races/default.jpg') ?>" 
                                alt="<?= e($race['name']) ?>" 
                                class="race-image"
                                style="width: 100%; height: 200px; object-fit: contain; background-color: #1a1a1a;">
                            <div class="race-overlay">
                                <span class="race-date">
                                    <i class="far fa-calendar me-2"></i><?= date('d.m.Y', strtotime($race['race_date'])) ?>
                                </span>
                            </div>
                            <div class="race-info">
                                <h3 class="race-name"><?= e($race['name']) ?></h3>
                                <p class="race-circuit">
                                    <i class="fas fa-map-marker-alt me-2"></i><?= e($race['track_name'] ?? 'Nieznany tor') ?>
                                </p>
                                <?php if($race['winner_name']): ?>
                                <p class="race-winner">
                                    <i class="fas fa-trophy me-2"></i>Zwycięzca: <?= e($race['winner_name']) ?>
                                </p>
                                <?php endif; ?>
                                <p class="race-description">
                                    Runda #<?= $race['round'] ?> sezonu <?= $race['season'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>