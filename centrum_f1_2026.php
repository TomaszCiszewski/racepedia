<?php
include "backend/config.php";

// Sprawdź czy to mobile
if (isMobile() && !isset($_GET['fullsite'])) {
    if (file_exists('centrum_f1_2026_mobile.php')) {
        include 'centrum_f1_2026_mobile.php';
        exit();
    }
}

// Sprawdzenie logowania
if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit();
}

// Pobierz najbliższy wyścig
$nextRace = $conn->query("
    SELECT r.*, 
           DATEDIFF(r.date, CURDATE()) as days_until
    FROM f1_races_2026 r
    WHERE r.date >= CURDATE()
    ORDER BY r.date ASC
    LIMIT 1
")->fetch_assoc();

// Pobierz ostatni wyścig (dla tabeli po-wyścigowej)
$lastRace = $conn->query("
    SELECT r.*, 
           t.name as circuit_name
    FROM f1_races_2026 r
    JOIN tracks t ON r.circuit = t.name
    WHERE r.date < CURDATE()
    ORDER BY r.date DESC
    LIMIT 1
")->fetch_assoc();

// Pobierz wyniki ostatniego wyścigu
$lastRaceResults = [];
if ($lastRace) {
    $results = $conn->query("
        SELECT fr.*, d.full_name as driver_name, t.name as team_name
        FROM f1_race_results fr
        JOIN drivers d ON fr.driver_id = d.id
        JOIN teams t ON fr.team_id = t.id
        WHERE fr.race_id = {$lastRace['id']}
        ORDER BY fr.position ASC
    ");
    while ($row = $results->fetch_assoc()) {
        $lastRaceResults[] = $row;
    }
}

// Pobierz mistrzostwo kierowców
$driverStandings = $conn->query("
    SELECT fs.*, d.full_name as driver_name, t.name as team_name
    FROM f1_driver_standings fs
    JOIN drivers d ON fs.driver_id = d.id
    JOIN teams t ON d.team_id = t.id
    WHERE fs.season = 2026
    ORDER BY fs.position ASC
");

// Pobierz mistrzostwo konstruktorów
$constructorStandings = $conn->query("
    SELECT cs.*, t.name as team_name
    FROM f1_constructor_standings cs
    JOIN teams t ON cs.team_id = t.id
    WHERE cs.season = 2026
    ORDER BY cs.position ASC
");

// Pobierz dane o torach
$tracksData = [];
$tracksQuery = $conn->query("SELECT name, country, length_km, laps, first_gp, lap_record, lap_record_driver, lap_record_year, bio FROM tracks");
if ($tracksQuery) {
    while($track = $tracksQuery->fetch_assoc()) {
        $tracksData[$track['name']] = [
            'length' => $track['length_km'] . ' km',
            'laps' => $track['laps'],
            'first_gp' => $track['first_gp'] ?? 'Brak danych',
            'lap_record' => $track['lap_record'] ?? 'Brak danych',
            'record_driver' => $track['lap_record_driver'] ?? 'Brak danych',
            'record_year' => $track['lap_record_year'] ?? '',
            'description' => $track['bio'] ?? 'Brak opisu.'
        ];
    }
} else {
    echo "Błąd zapytania: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centrum F1 2026 - Racepedia</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- ZAMIĘŃ flag-icon-css na: -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css">
    <!-- Nasze style -->
    <?php include "components/styles.php"; ?>
    
    <style>
        :root {
            --hero-background: url('assets/hero_f1.png');
        }
        
        /* Hero z najbliższym wyścigiem */
        .next-race-hero {
            background: linear-gradient(135deg, rgba(17, 17, 17, 0.95) 0%, rgba(26, 26, 26, 0.95) 100%), var(--hero-background) center/cover fixed;
            border: 2px solid #ff0033;
            border-radius: 20px;
            padding: 40px;
            margin: 30px 0 50px 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(255, 0, 51, 0.2);
            backdrop-filter: blur(5px);
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .next-race-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,0,51,0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 3s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.5); opacity: 0.1; }
            100% { transform: scale(1); opacity: 0.3; }
        }
        
        .next-race-flag {
            font-size: 80px;
            line-height: 1;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 20px rgba(255,0,51,0.5));
        }
        
        .next-race-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 36px;
            font-weight: 800;
            color: #ff0033;
            margin-bottom: 10px;
            text-shadow: 0 0 20px rgba(255,0,51,0.5);
        }
        
        .next-race-circuit {
            font-size: 18px;
            color: #fff;
            margin-bottom: 20px;
            text-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        
        .countdown-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 30px 0;
        }
        
        .countdown-box {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(5px);
            border: 1px solid #ff0033;
            border-radius: 10px;
            padding: 20px;
            min-width: 100px;
            text-align: center;
            box-shadow: 0 0 20px rgba(255,0,51,0.2);
        }
        
        .countdown-number {
            font-family: 'Orbitron', sans-serif;
            font-size: 36px;
            font-weight: 800;
            color: #ff0033;
            line-height: 1;
        }
        
        .countdown-label {
            font-size: 12px;
            color: #ccc;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Przyciski akcji */
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .action-btn {
            background: transparent;
            border: 2px solid #ff0033;
            color: #ff0033;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .action-btn:hover,
        .action-btn.active {
            background: #ff0033;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255,0,51,0.4);
        }
        
        /* Sekcje ukryte */
        .hidden-section {
            display: none;
            margin-top: 40px;
            animation: slideDown 0.5s ease;
        }
        
        .hidden-section.show {
            display: block;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Przełączniki tabel */
        .table-switchers {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .table-switcher {
            background: transparent;
            border: 2px solid #ff0033;
            color: #ff0033;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .table-switcher:hover,
        .table-switcher.active {
            background: #ff0033;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255,0,51,0.3);
        }
        
        .table-switcher i {
            font-size: 18px;
        }
        
        /* Style tabel */
        .table-container {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .table-container.active-table {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .table-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 24px;
            color: #ff0033;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255,0,51,0.3);
        }
        
        .custom-table {
            width: 100%;
            color: white;
        }
        
        .custom-table th {
            font-family: 'Orbitron', sans-serif;
            color: #ff0033;
            padding: 15px 10px;
            border-bottom: 2px solid #ff0033;
        }
        
        .custom-table td {
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255,0,51,0.2);
        }
        
        .custom-table tr:hover {
            background: rgba(255,0,51,0.1);
        }
        
        .position-1 { color: gold; font-weight: 700; }
        .position-2 { color: silver; font-weight: 700; }
        .position-3 { color: #cd7f32; font-weight: 700; }
        
        .fastest-lap-badge {
            background: #ff0033;
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 10px;
            margin-left: 5px;
        }
        
        /* Kalendarz */
        .calendar-section {
            background: linear-gradient(135deg, rgba(17, 17, 17, 0.97) 0%, rgba(26, 26, 26, 0.97) 100%), url('assets/calendar_bg.jpg') center/cover fixed;
            padding: 40px 0;
            border-radius: 20px;
            margin: 30px 0;
        }
        
        .month-divider {
            font-family: 'Orbitron', sans-serif;
            font-size: 28px;
            color: #ff0033;
            margin: 40px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255,0,51,0.3);
        }
        
        .race-card {
            background: rgba(17, 17, 17, 0.9);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,0,51,0.3);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .race-card:hover {
            transform: translateY(-5px);
            border-color: #ff0033;
            box-shadow: 0 10px 30px rgba(255,0,51,0.2);
        }
        
        .race-card.completed {
            opacity: 0.8;
        }
        
        .race-card.today {
            border: 2px solid #ff0033;
            animation: glow 2s infinite;
        }
        
        @keyframes glow {
            0% { box-shadow: 0 0 10px rgba(255,0,51,0.3); }
            50% { box-shadow: 0 0 30px rgba(255,0,51,0.6); }
            100% { box-shadow: 0 0 10px rgba(255,0,51,0.3); }
        }
        
        .race-round {
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            color: #ff0033;
            margin-bottom: 5px;
        }
        
        .race-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }
        
        .flag-icon {
            font-size: 32px;
            margin-right: 15px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .filter-btn {
            background: transparent;
            border: 1px solid #ff0033;
            color: #ff0033;
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: #ff0033;
            color: white;
        }
        
        .sprint-badge {
            background: #ff0033;
            color: white;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            margin-left: 10px;
            text-transform: uppercase;
        }
        
        @media (max-width: 768px) {
            .next-race-title {
                font-size: 24px;
            }
            
            .countdown-box {
                min-width: 70px;
                padding: 10px;
            }
            
            .countdown-number {
                font-size: 24px;
            }
            
            .action-btn {
                padding: 12px 25px;
                font-size: 16px;
            }
            
            .table-switcher {
                padding: 10px 15px;
                font-size: 14px;
            }
        }

        /* Przycisk na żywo */
        .live-btn {
            background: #ff0033;
            color: white;
            border-color: #ff0033;
            position: relative;
            animation: pulse-live 2s infinite;
            text-decoration: none;
        }

        .live-btn:hover {
            background: white;
            color: #ff0033;
            border-color: #ff0033;
            animation: none;
        }

        .live-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            margin-left: 8px;
            animation: blink 1s infinite;
        }

        .live-btn:hover .live-dot {
            background: #ff0033;
        }

        @keyframes pulse-live {
            0% { box-shadow: 0 0 0 0 rgba(255, 0, 51, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 0, 51, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 0, 51, 0); }
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .flag-icon {
            font-size: 32px;
            filter: drop-shadow(0 0 5px rgba(255,0,51,0.5));
        }
    </style>
</head>
<body>

<?php include "components/navbar.php"; ?>

<div class="container mt-5 pt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 style="font-family: 'Orbitron'; font-size: 48px; font-weight: 800; color: #ff0033;">
            <i class="fas fa-flag-checkered me-3"></i>F1 2026
        </h1>
        <p class="lead text-secondary">Centrum sezonu Formuły 1</p>
    </div>
    
    <?php if($nextRace): ?>
    <!-- Najbliższy wyścig -->
    <div class="next-race-hero text-center">
        <div>
            <div class="next-race-flag">
                <span class="fi fi-<?= $nextRace['flag_code'] ?>"></span>
            </div>
            <div class="next-race-title">
                Runda <?= $nextRace['round'] ?>: <?= $nextRace['grand_prix'] ?>
            </div>
            <div class="next-race-circuit">
                <i class="fas fa-road me-2"></i><?= $nextRace['circuit'] ?>
            </div>
            
            <!-- Countdown -->
            <div class="countdown-container" id="countdown">
                <div class="countdown-box">
                    <div class="countdown-number" id="days">0</div>
                    <div class="countdown-label">Dni</div>
                </div>
                <div class="countdown-box">
                    <div class="countdown-number" id="hours">0</div>
                    <div class="countdown-label">Godzin</div>
                </div>
                <div class="countdown-box">
                    <div class="countdown-number" id="minutes">0</div>
                    <div class="countdown-label">Minut</div>
                </div>
                <div class="countdown-box">
                    <div class="countdown-number" id="seconds">0</div>
                    <div class="countdown-label">Sekund</div>
                </div>
            </div>
            
            <!-- Przyciski akcji -->
            <div class="action-buttons">
                <button class="action-btn" onclick="toggleSection('calendar')">
                    <i class="fas fa-calendar-alt"></i>
                    Kalendarz
                </button>
                
                <button class="action-btn" onclick="showTablesSection()">
                    <i class="fas fa-table"></i>
                    Tabela
                </button>
                
                <!-- NOWY PRZYCISK: Na żywo -->
                <a href="live.php" class="action-btn live-btn">
                    <i class="fas fa-broadcast-tower"></i>
                    Na żywo
                    <span class="live-dot"></span>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Sekcja kalendarza (ukryta domyślnie) -->
    <div id="calendarSection" class="hidden-section">
        <div class="calendar-section">
            <div class="container">
                <!-- Filtry -->
                <div class="filter-buttons justify-content-center">
                    <button class="filter-btn active" onclick="filterRaces('all')">Wszystkie</button>
                    <button class="filter-btn" onclick="filterRaces('upcoming')">Nadchodzące</button>
                    <button class="filter-btn" onclick="filterRaces('completed')">Zakończone</button>
                    <button class="filter-btn" onclick="filterRaces('sprint')">Sprint weekendy</button>
                </div>
                
                <!-- Kalendarz -->
                <div id="calendar-container">
                    <?php
                    // Pobierz wszystkie wyścigi dla kalendarza
                    $allRaces = $conn->query("
                        SELECT r.*, 
                               CASE 
                                   WHEN r.date < CURDATE() THEN 'past'
                                   WHEN r.date = CURDATE() THEN 'today'
                                   ELSE 'upcoming'
                               END as race_status
                        FROM f1_races_2026 r
                        ORDER BY r.date ASC
                    ");
                    
                    $racesByMonth = [];
                    while ($race = $allRaces->fetch_assoc()) {
                        $month = date('F Y', strtotime($race['date']));
                        $racesByMonth[$month][] = $race;
                    }
                    
                    foreach($racesByMonth as $month => $races): 
                    ?>
                    <div class="month-divider"><?= $month ?></div>
                    
                    <?php foreach($races as $race): ?>
                        <div class="race-card <?= $race['race_status'] ?>" data-status="<?= $race['race_status'] ?>" data-sprint="<?= $race['sprint_date'] ? 'sprint' : 'no-sprint' ?>">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <span class="fi fi-<?= $race['flag_code'] ?> flag-icon"></span>
                                </div>
                                <div class="col-md-2">
                                    <div class="race-round">Runda <?= $race['round'] ?></div>
                                    <div class="race-name"><?= $race['grand_prix'] ?></div>
                                </div>
                                <div class="col-md-3">
                                    <i class="fas fa-map-marker-alt me-2" style="color: #ff0033;"></i>
                                    <?= $race['circuit'] ?>
                                </div>
                                <div class="col-md-3">
                                    <i class="fas fa-calendar-alt me-2" style="color: #ff0033;"></i>
                                    <?= date('d.m.Y', strtotime($race['date'])) ?>
                                    <?php if($race['sprint_date']): ?>
                                        <span class="sprint-badge">SPRINT</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end gap-2">
                                    <?php if($race['race_status'] == 'today'): ?>
                                        <span class="badge bg-danger">DZISIAJ!</span>
                                    <?php elseif($race['race_status'] == 'past'): ?>
                                        <span class="badge bg-secondary">Zakończony</span>
                                    <?php else: ?>
                                        <span class="badge bg-dark">Nadchodzący</span>
                                    <?php endif; ?>
                                    
                                    <!-- NOWY PRZYCISK: Informacje o torze -->
                                    <button class="btn btn-sm btn-outline-f1" onclick='showTrackInfo(<?= json_encode([
                                        'name' => $race['circuit'],
                                        'country' => $race['country'],
                                        'flag' => $race['flag_code'],
                                        'length' => $trackData[$race['circuit']]['length'] ?? '5.278 km',
                                        'laps' => $trackData[$race['circuit']]['laps'] ?? 58,
                                        'first_gp' => $trackData[$race['circuit']]['first_gp'] ?? '1996',
                                        'lap_record' => $trackData[$race['circuit']]['lap_record'] ?? '1:20.235',
                                        'record_driver' => $trackData[$race['circuit']]['record_driver'] ?? 'M. Verstappen',
                                        'description' => $trackData[$race['circuit']]['description'] ?? 'Klasyczny tor wyścigowy.'
                                    ]) ?>)'>
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sekcja tabel (ukryta domyślnie) -->
    <div id="tablesSection" class="hidden-section">
        <!-- Przełączniki tabel -->
        <div class="table-switchers">
            <button class="table-switcher active" onclick="switchTable('lastRace')">
                <i class="fas fa-flag-checkered"></i>
                Ostatni wyścig
            </button>
            <button class="table-switcher" onclick="switchTable('driverStandings')">
                <i class="fas fa-crown"></i>
                Mistrzostwa kierowców
            </button>
            <button class="table-switcher" onclick="switchTable('constructorStandings')">
                <i class="fas fa-building"></i>
                Mistrzostwa konstruktorów
            </button>
        </div>
        
        <!-- Tabela ostatniego wyścigu -->
        <div id="lastRaceTable" class="table-container active-table">
            <div class="table-title">
                <i class="fas fa-flag-checkered me-2"></i>
                Wyniki: <?= $lastRace ? $lastRace['grand_prix'] : 'Brak wyścigu' ?>
            </div>
            
            <?php if(!empty($lastRaceResults)): ?>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Poz.</th>
                        <th>Kierowca</th>
                        <th>Zespół</th>
                        <th>Start</th>
                        <th>Punkty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lastRaceResults as $result): ?>
                    <tr>
                        <td class="<?= $result['position'] <= 3 ? 'position-' . $result['position'] : '' ?>">
                            <?= $result['position'] ?>
                            <?php if($result['fastest_lap']): ?>
                                <span class="fastest-lap-badge">FL</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $result['driver_name'] ?></td>
                        <td><?= $result['team_name'] ?></td>
                        <td><?= $result['grid'] ?></td>
                        <td><strong><?= $result['points'] ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-center text-secondary">Brak wyników dla ostatniego wyścigu.</p>
            <?php endif; ?>
        </div>
        
        <!-- Tabela mistrzostwa kierowców -->
        <div id="driverStandingsTable" class="table-container">
            <div class="table-title">
                <i class="fas fa-crown me-2"></i>
                Mistrzostwa Kierowców 2026
            </div>
            
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Poz.</th>
                        <th>Kierowca</th>
                        <th>Zespół</th>
                        <th>Punkty</th>
                        <th>Zwycięstwa</th>
                        <th>Podia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Odśwież zapytanie
                    $driverStandings = $conn->query("
                        SELECT fs.*, d.full_name as driver_name, t.name as team_name
                        FROM f1_driver_standings fs
                        JOIN drivers d ON fs.driver_id = d.id
                        JOIN teams t ON d.team_id = t.id
                        WHERE fs.season = 2026
                        ORDER BY fs.position ASC
                    ");
                    while($standing = $driverStandings->fetch_assoc()): 
                    ?>
                    <tr>
                        <td class="<?= $standing['position'] <= 3 ? 'position-' . $standing['position'] : '' ?>">
                            <?= $standing['position'] ?>
                        </td>
                        <td><?= $standing['driver_name'] ?></td>
                        <td><?= $standing['team_name'] ?></td>
                        <td><strong><?= $standing['points'] ?></strong></td>
                        <td><?= $standing['wins'] ?></td>
                        <td><?= $standing['podiums'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Tabela mistrzostwa konstruktorów -->
        <div id="constructorStandingsTable" class="table-container">
            <div class="table-title">
                <i class="fas fa-building me-2"></i>
                Mistrzostwa Konstruktorów 2026
            </div>
            
            <table class="custom-table">
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
                    // Odśwież zapytanie
                    $constructorStandings = $conn->query("
                        SELECT cs.*, t.name as team_name
                        FROM f1_constructor_standings cs
                        JOIN teams t ON cs.team_id = t.id
                        WHERE cs.season = 2026
                        ORDER BY cs.position ASC
                    ");
                    while($standing = $constructorStandings->fetch_assoc()): 
                    ?>
                    <tr>
                        <td class="<?= $standing['position'] <= 3 ? 'position-' . $standing['position'] : '' ?>">
                            <?= $standing['position'] ?>
                        </td>
                        <td><?= $standing['team_name'] ?></td>
                        <td><strong><?= $standing['points'] ?></strong></td>
                        <td><?= $standing['wins'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Countdown timer
function updateCountdown() {
    const raceDate = new Date('<?= $nextRace['date'] ?>').getTime();
    const now = new Date().getTime();
    const distance = raceDate - now;
    
    if (distance < 0) {
        document.getElementById('countdown').innerHTML = '<h3>Wyścig się rozpoczął!</h3>';
        return;
    }
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    document.getElementById('days').textContent = days;
    document.getElementById('hours').textContent = hours;
    document.getElementById('minutes').textContent = minutes;
    document.getElementById('seconds').textContent = seconds;
}

// Pokaż/ukryj sekcję kalendarza
function toggleSection(section) {
    const calendarSection = document.getElementById('calendarSection');
    const tablesSection = document.getElementById('tablesSection');
    const calendarBtn = document.querySelector('.action-btn:first-child');
    const tablesBtn = document.querySelector('.action-btn:last-child');
    
    if (section === 'calendar') {
        calendarSection.classList.toggle('show');
        tablesSection.classList.remove('show');
        calendarBtn.classList.toggle('active');
        tablesBtn.classList.remove('active');
    }
}

// Pokaż sekcję tabel (domyślnie z ostatnim wyścigiem)
function showTablesSection() {
    const tablesSection = document.getElementById('tablesSection');
    const calendarSection = document.getElementById('calendarSection');
    const tablesBtn = document.querySelector('.action-btn:last-child');
    const calendarBtn = document.querySelector('.action-btn:first-child');
    
    tablesSection.classList.add('show');
    calendarSection.classList.remove('show');
    tablesBtn.classList.add('active');
    calendarBtn.classList.remove('active');
    
    // Domyślnie pokaż tabelę ostatniego wyścigu
    switchTable('lastRace');
}

// Przełączanie między tabelami
function switchTable(table) {
    // Ukryj wszystkie tabele
    document.getElementById('lastRaceTable').classList.remove('active-table');
    document.getElementById('driverStandingsTable').classList.remove('active-table');
    document.getElementById('constructorStandingsTable').classList.remove('active-table');
    
    // Usuń active ze wszystkich przełączników
    document.querySelectorAll('.table-switcher').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Pokaż wybraną tabelę
    document.getElementById(table + 'Table').classList.add('active-table');
    
    // Dodaj active do klikniętego przycisku
    event.target.classList.add('active');
}

// Filtrowanie wyścigów
function filterRaces(filter) {
    const cards = document.querySelectorAll('.race-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    cards.forEach(card => {
        switch(filter) {
            case 'all':
                card.style.display = 'block';
                break;
            case 'upcoming':
                if(card.dataset.status === 'upcoming') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
                break;
            case 'completed':
                if(card.dataset.status === 'past' || card.dataset.status === 'today') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
                break;
            case 'sprint':
                if(card.dataset.sprint === 'sprint') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
                break;
        }
    });
}

// Aktualizuj countdown co sekundę
<?php if($nextRace && $nextRace['days_until'] > 0): ?>
setInterval(updateCountdown, 1000);
updateCountdown();
<?php endif; ?>
</script>

<!-- Modal informacji o torze -->
<div class="modal fade" id="trackInfoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #ff0033;" id="trackModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <span id="trackModalFlag" style="font-size: 48px;"></span>
                    <h4 id="trackModalCountry" class="mt-2" style="color: white;"></h4>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="track-stat-card">
                            <i class="fas fa-road" style="color: #ff0033; font-size: 20px;"></i>
                            <div style="font-size: 12px; color: #ccc;">Długość</div>
                            <div style="font-size: 18px; font-weight: 700;" id="trackModalLength"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="track-stat-card">
                            <i class="fas fa-flag-checkered" style="color: #ff0033; font-size: 20px;"></i>
                            <div style="font-size: 12px; color: #ccc;">Okrążenia</div>
                            <div style="font-size: 18px; font-weight: 700;" id="trackModalLaps"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="track-stat-card">
                            <i class="fas fa-calendar-alt" style="color: #ff0033; font-size: 20px;"></i>
                            <div style="font-size: 12px; color: #ccc;">Pierwsze GP</div>
                            <div style="font-size: 18px; font-weight: 700;" id="trackModalFirstGP"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="track-stat-card">
                            <i class="fas fa-trophy" style="color: #ff0033; font-size: 20px;"></i>
                            <div style="font-size: 12px; color: #ccc;">Rekord okr.</div>
                            <div style="font-size: 18px; font-weight: 700;" id="trackModalRecord"></div>
                        </div>
                    </div>
                </div>
                
                <div class="track-record-info p-3 mb-3" style="background: rgba(255,0,51,0.1); border-left: 4px solid #ff0033; border-radius: 5px;">
                    <i class="fas fa-stopwatch me-2" style="color: #ff0033;"></i>
                    <strong>Rekordzista:</strong> <span id="trackModalRecordDriver"></span>
                </div>
                
                <div class="track-description p-3" style="background: #0a0a0a; border-radius: 10px;">
                    <p style="color: #ccc; line-height: 1.6;" id="trackModalDescription"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-f1" data-bs-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

<style>
.track-stat-card {
    background: #0a0a0a;
    border: 1px solid #ff0033;
    border-radius: 10px;
    padding: 15px 10px;
    text-align: center;
    transition: all 0.3s ease;
}

.track-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(255,0,51,0.3);
}

.modal-content {
    background: #111 !important;
    border: 2px solid #ff0033 !important;
}

.modal-header {
    border-bottom: 1px solid #ff0033 !important;
}

.modal-footer {
    border-top: 1px solid #ff0033 !important;
}

.btn-f1 {
    background: #ff0033;
    color: white;
    border: none;
    padding: 8px 25px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.btn-f1:hover {
    background: #ff1a47;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,0,51,0.4);
}
</style>

<script>
function showTrackInfo(trackData) {
    // Wypełnij modal danymi
    document.getElementById('trackModalTitle').textContent = trackData.name;
    document.getElementById('trackModalFlag').innerHTML = `<span class="fi fi-${trackData.flag}"></span>`;
    document.getElementById('trackModalCountry').textContent = trackData.country;
    document.getElementById('trackModalLength').textContent = trackData.length;
    document.getElementById('trackModalLaps').textContent = trackData.laps + ' okr.';
    document.getElementById('trackModalFirstGP').textContent = trackData.first_gp;
    document.getElementById('trackModalRecord').textContent = trackData.lap_record;
    document.getElementById('trackModalRecordDriver').textContent = trackData.record_driver;
    document.getElementById('trackModalDescription').textContent = trackData.description;
    
    // Pokaż modal
    new bootstrap.Modal(document.getElementById('trackInfoModal')).show();
}
</script>

</body>
</html>