<?php
include "backend/config.php";

// Sprawdzenie logowania - komentuję bo chcesz chyba żeby było publiczne?
// if(!isset($_SESSION['user'])) header("Location: login.php");

// Funkcja pomocnicza do escape'owaniu

// DANE KIEROWCÓW (rozszerzone)
$drivers = [
    [
        "name" => "Max Verstappen",
        "team" => "Red Bull Racing",
        "country" => "Holandia",
        "number" => "#1",
        "img" => "assets/drivers/verstappen.jpg",
        "info" => "4-krotny mistrz świata F1. Najmłodszy lider w historii F1."
    ],
    [
        "name" => "Lewis Hamilton",
        "team" => "Mercedes",
        "country" => "Wielka Brytania",
        "number" => "#44",
        "img" => "assets/drivers/hamilton.jpg",
        "info" => "7-krotny mistrz świata. Rekordzista w liczbie zwycięstw."
    ],
    [
        "name" => "Charles Leclerc",
        "team" => "Ferrari",
        "country" => "Monako",
        "number" => "#16",
        "img" => "assets/drivers/leclerc.jpg",
        "info" => "Utalentowany kierowca Ferrari, wielokrotny zwycięzca Grand Prix."
    ],
    [
        "name" => "Fernando Alonso",
        "team" => "Aston Martin",
        "country" => "Hiszpania",
        "number" => "#14",
        "img" => "assets/drivers/alonso.jpg",
        "info" => "2-krotny mistrz świata. Legenda F1, znany z niesamowitych startów."
    ],
    [
        "name" => "Sergio Perez",
        "team" => "Red Bull Racing",
        "country" => "Meksyk",
        "number" => "#11",
        "img" => "assets/drivers/perez.jpg",
        "info" => "Specjalista od opon, mistrz w obronie pozycji."
    ],
    [
        "name" => "Lando Norris",
        "team" => "McLaren",
        "country" => "Wielka Brytania",
        "number" => "#4",
        "img" => "assets/drivers/norris.jpg",
        "info" => "Młody talent McLarena, znany z szybkości i streamowania."
    ]
];

// DANE TORÓW
$tracks = [
    [
        "name" => "Monza",
        "country" => "Włochy",
        "length" => "5.793 km",
        "laps" => 53,
        "img" => "assets/tracks/monza.jpg",
        "info" => "Świątynia szybkości. Najszybszy tor w kalendarzu F1."
    ],
    [
        "name" => "Spa-Francorchamps",
        "country" => "Belgia",
        "length" => "7.004 km",
        "laps" => 44,
        "img" => "assets/tracks/spa.jpg",
        "info" => "Kultowe Eau Rouge. Najdłuższy tor w F1."
    ],
    [
        "name" => "Monako",
        "country" => "Monako",
        "length" => "3.337 km",
        "laps" => 78,
        "img" => "assets/tracks/monaco.jpg",
        "info" => "Perła koronna F1. Wąskie ulice, prestiż i glamour."
    ]
];

// DANE WYŚCIGÓW
$races = [
    [
        "name" => "Grand Prix Wielkiej Brytanii",
        "circuit" => "Silverstone",
        "date" => "2024-07-07",
        "winner" => "Max Verstappen",
        "img" => "assets/races/silverstone.jpg",
        "info" => "Historyczny wyścig na legendarnym torze Silverstone."
    ],
    [
        "name" => "Grand Prix Włoch",
        "circuit" => "Monza",
        "date" => "2024-09-01",
        "winner" => "Charles Leclerc",
        "img" => "assets/races/monza.jpg",
        "info" => "Ferrari na Monza - magia dla tifosi."
    ],
    [
        "name" => "Grand Prix Monako",
        "circuit" => "Monako",
        "date" => "2024-05-26",
        "winner" => "Sergio Perez",
        "img" => "assets/races/monaco.jpg",
        "info" => "Najbardziej prestiżowy wyścig w kalendarzu F1."
    ]
];

// Pobierz aktywną zakładkę
$activeTab = $_GET['tab'] ?? 'drivers';
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
    
    <!-- Font Awesome dla ikon -->
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
                <?php foreach($drivers as $driver): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="driver-card">
                      <div class="driver-image-wrapper">
                          <img src="<?= e($driver['img']) ?>" 
                              alt="<?= e($driver['name']) ?>" 
                              class="driver-image"
                              style="width: 100%; height: 280px; object-fit: contain; background-color: #1a1a1a;">
                          <div class="driver-number"><?= e($driver['number']) ?></div>
                      </div>
                        <div class="driver-info">
                            <h3 class="driver-name"><?= e($driver['name']) ?></h3>
                            <div class="driver-meta">
                                <span class="driver-team"><i class="fas fa-car me-1"></i><?= e($driver['team']) ?></span>
                                <span class="driver-country"><i class="fas fa-globe me-1"></i><?= e($driver['country']) ?></span>
                            </div>
                            <p class="driver-description"><?= e($driver['info']) ?></p>
                            <button class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#driverModal<?= md5($driver['name']) ?>">
                                <i class="fas fa-info-circle me-2"></i>Więcej informacji
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal dla kierowcy -->
                <div class="modal fade" id="driverModal<?= md5($driver['name']) ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content glass-card">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= e($driver['name']) ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <img src="<?= e($driver['img']) ?>" class="img-fluid rounded mb-3" alt="">
                                <p><strong>Zespół:</strong> <?= e($driver['team']) ?></p>
                                <p><strong>Kraj:</strong> <?= e($driver['country']) ?></p>
                                <p><strong>Numer:</strong> <?= e($driver['number']) ?></p>
                                <p><?= e($driver['info']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- TORY -->
        <div class="tab-pane fade show <?= $activeTab == 'tracks' ? 'active' : '' ?>" id="tracks">
            <div class="row g-4">
                <?php foreach($tracks as $track): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="track-card">
                        <img src="<?= e($track['img']) ?>" 
                            alt="<?= e($track['name']) ?>" 
                            class="track-image"
                            style="width: 100%; height: 200px; object-fit: contain; background-color: #1a1a1a;">
                        <div class="track-info">
                            <h3 class="track-name"><?= e($track['name']) ?></h3>
                            <div class="track-details">
                                <span><i class="fas fa-globe me-1"></i><?= e($track['country']) ?></span>
                                <span><i class="fas fa-road me-1"></i><?= e($track['length']) ?></span>
                                <span><i class="fas fa-flag-checkered me-1"></i><?= e($track['laps']) ?> okrążeń</span>
                            </div>
                            <p class="track-description"><?= e($track['info']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- WYŚCIGI -->
        <div class="tab-pane fade show <?= $activeTab == 'races' ? 'active' : '' ?>" id="races">
            <div class="row g-4">
                <?php foreach($races as $race): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="race-card">
                        <img src="<?= e($race['img']) ?>" 
                            alt="<?= e($race['name']) ?>" 
                            class="race-image"
                            style="width: 100%; height: 200px; object-fit: contain; background-color: #1a1a1a;">
                        <div class="race-overlay">
                            <span class="race-date"><i class="far fa-calendar me-2"></i><?= date('d.m.Y', strtotime($race['date'])) ?></span>
                        </div>
                        <div class="race-info">
                            <h3 class="race-name"><?= e($race['name']) ?></h3>
                            <p class="race-circuit"><i class="fas fa-map-marker-alt me-2"></i><?= e($race['circuit']) ?></p>
                            <p class="race-winner"><i class="fas fa-trophy me-2"></i>Zwycięzca: <?= e($race['winner']) ?></p>
                            <p class="race-description"><?= e($race['info']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>