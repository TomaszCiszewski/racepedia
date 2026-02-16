<?php
include "backend/config.php";
include "backend/f1_sensor_data.php";

// Sprawdź czy to mobile
if (isMobile() && !isset($_GET['fullsite'])) {
    if (file_exists('live_mobile.php')) {
        include 'live_mobile.php';
        exit();
    }
}

// Sprawdzenie logowania
if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit();
}

// Inicjalizacja F1 Sensor
$f1 = new F1SensorData();
$isLive = $f1->isLive();
$positions = $f1->getDriverPositions();
$raceControl = $f1->getRaceControl();
$pitStops = $f1->getPitStops();
$tireStats = $f1->getTireStatistics();
$session = $f1->getCurrentSession();
$lastUpdate = $f1->getLastUpdate();

// Pobierz najbliższy wyścig z bazy (jeśli nie ma live)
if (!$isLive) {
    $nextRace = $conn->query("
        SELECT r.*, t.name as circuit_name, t.country, t.image_path 
        FROM f1_races_2026 r
        JOIN tracks t ON r.circuit = t.name
        WHERE r.date >= CURDATE()
        ORDER BY r.date ASC
        LIMIT 1
    ")->fetch_assoc();
}

// Pobierz historyczne dane kierowców (dla zdjęć)
$drivers = [];
$driversQuery = $conn->query("SELECT full_name, image_path, team_id FROM drivers");
while ($d = $driversQuery->fetch_assoc()) {
    $drivers[$d['full_name']] = $d;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Na żywo - F1 2026 - Racepedia</title>
    
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
    
    <style>
        :root {
            --live-red: #ff0033;
            --live-red-dim: rgba(255, 0, 51, 0.3);
        }
        
        body {
            background: #0a0a0a;
            color: white;
        }
        
        /* Live header */
        .live-header {
            background: linear-gradient(135deg, #111 0%, #1a1a1a 100%);
            border: 2px solid var(--live-red);
            border-radius: 20px;
            padding: 20px 30px;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 30px var(--live-red-dim);
        }
        
        .live-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, var(--live-red-dim) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 3s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.5); opacity: 0.1; }
            100% { transform: scale(1); opacity: 0.3; }
        }
        
        .live-badge {
            display: inline-block;
            background: var(--live-red);
            color: white;
            font-size: 16px;
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 30px;
            margin-right: 15px;
            animation: blink 1s infinite;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        
        .session-info {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .session-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ccc;
        }
        
        .session-item i {
            color: var(--live-red);
            font-size: 20px;
        }
        
        .update-time {
            color: #ccc;
            font-size: 14px;
            padding: 5px 15px;
            background: #0a0a0a;
            border-radius: 20px;
            border: 1px solid var(--live-red-dim);
        }
        
        /* Tabela pozycji */
        .position-table {
            background: #111;
            border: 2px solid var(--live-red-dim);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .position-table:hover {
            border-color: var(--live-red);
            box-shadow: 0 0 30px var(--live-red-dim);
        }
        
        .table-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 24px;
            color: var(--live-red);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--live-red-dim);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .position-row {
            display: flex;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #333;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .position-row:hover {
            background: rgba(255, 0, 51, 0.1);
            transform: translateX(5px);
        }
        
        .position-row:last-child {
            border-bottom: none;
        }
        
        .position-number {
            font-family: 'Orbitron', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--live-red);
            width: 70px;
            text-align: center;
        }
        
        .position-1 .position-number { 
            color: gold; 
            text-shadow: 0 0 20px gold;
        }
        .position-2 .position-number { 
            color: silver; 
            text-shadow: 0 0 20px silver;
        }
        .position-3 .position-number { 
            color: #cd7f32; 
            text-shadow: 0 0 20px #cd7f32;
        }
        
        .driver-info {
            flex: 2;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .driver-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--live-red);
            object-fit: cover;
            object-position: center 1%;  /* Przesuń focus na twarz */
            transition: all 0.3s ease;
            background: #1a1a1a;  /* Tło w razie problemów */
        }
                
        .position-row:hover .driver-avatar {
            transform: scale(1.1);
            border-color: white;
        }
        
        .driver-name {
            font-weight: 700;
            font-size: 18px;
            color: white;
            margin-bottom: 3px;
        }
        
        .team-name {
            font-size: 12px;
            color: #ccc;
        }
        
        .position-stats {
            flex: 3;
            display: flex;
            gap: 30px;
            justify-content: flex-end;
        }
        
        .stat-item {
            text-align: center;
            min-width: 90px;
        }
        
        .stat-label {
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: 600;
            color: white;
            font-family: 'Orbitron', sans-serif;
        }
        
        .stat-value.delta {
            color: var(--live-red);
        }
        
        .stat-value.pit {
            color: #ff0;
        }
        
        .pit-badge {
            background: var(--live-red);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            margin-left: 8px;
            text-transform: uppercase;
            animation: blink 1s infinite;
        }
        
        .out-badge {
            background: #666;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            margin-left: 8px;
        }
        
        /* Race control */
        .race-control-container {
            background: #111;
            border: 2px solid var(--live-red-dim);
            border-radius: 20px;
            padding: 20px;
            height: 100%;
        }
        
        .control-item {
            background: #0a0a0a;
            border-left: 4px solid var(--live-red);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .control-item:hover {
            transform: translateX(5px);
            border-left-width: 6px;
        }
        
        .control-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 5px;
        }
        
        .flag-display {
            font-size: 28px;
            width: 40px;
            text-align: center;
        }
        
        .flag-yellow { color: #ff0; text-shadow: 0 0 10px #ff0; }
        .flag-green { color: #0f0; text-shadow: 0 0 10px #0f0; }
        .flag-red { color: #f00; text-shadow: 0 0 10px #f00; }
        .flag-blue { color: #00f; text-shadow: 0 0 10px #00f; }
        .flag-white { color: #fff; text-shadow: 0 0 10px #fff; }
        .flag-sc { color: var(--live-red); text-shadow: 0 0 10px var(--live-red); }
        .flag-vsc { color: var(--live-red); text-shadow: 0 0 10px var(--live-red); }
        
        .control-message {
            font-weight: 600;
            color: white;
            font-size: 16px;
        }
        
        .control-time {
            font-size: 12px;
            color: #999;
            display: block;
            margin-top: 5px;
        }
        
        /* Pit stops */
        .pit-container {
            background: #111;
            border: 2px solid var(--live-red-dim);
            border-radius: 20px;
            padding: 20px;
            height: 100%;
        }
        
        .pit-item {
            background: linear-gradient(135deg, #0a0a0a 0%, #111 100%);
            border: 1px solid var(--live-red-dim);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .pit-item:hover {
            border-color: var(--live-red);
            transform: translateX(5px);
        }
        
        .pit-driver {
            font-weight: 700;
            color: white;
            font-size: 16px;
        }
        
        .pit-lap {
            color: #ccc;
            font-size: 12px;
        }
        
        .pit-time {
            font-family: 'Orbitron', sans-serif;
            color: var(--live-red);
            font-weight: 700;
            font-size: 18px;
        }
        
        .pit-delta {
            color: #ff0;
            font-size: 12px;
        }
        
        /* Tire statistics */
        .tire-container {
            background: #111;
            border: 2px solid var(--live-red-dim);
            border-radius: 20px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .tire-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .tire-card {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 15px;
            padding: 20px 15px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .tire-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px var(--live-red-dim);
        }
        
        .tire-soft { border-top: 5px solid #ff0; }
        .tire-medium { border-top: 5px solid #ffa500; }
        .tire-hard { border-top: 5px solid #fff; }
        .tire-inter { border-top: 5px solid #0f0; }
        .tire-wet { border-top: 5px solid #00f; }
        
        .tire-compound {
            font-size: 14px;
            color: #ccc;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .tire-time {
            font-family: 'Orbitron', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }
        
        .tire-driver {
            font-size: 12px;
            color: var(--live-red);
        }
        
        /* Mini dashboard */
        .mini-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .dashboard-card {
            background: #111;
            border: 1px solid var(--live-red-dim);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }
        
        .dashboard-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--live-red);
            margin: 10px 0;
        }
        
        .dashboard-label {
            color: #ccc;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        /* Construction page (gdy brak live) */
        .construction-container {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 50px 20px;
        }
        
        .construction-card {
            background: #111;
            border: 2px solid var(--live-red);
            border-radius: 20px;
            padding: 50px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 0 50px var(--live-red-dim);
            animation: glow 2s infinite;
        }
        
        @keyframes glow {
            0% { box-shadow: 0 0 30px var(--live-red-dim); }
            50% { box-shadow: 0 0 60px var(--live-red-dim); }
            100% { box-shadow: 0 0 30px var(--live-red-dim); }
        }
        
        .construction-icon {
            font-size: 80px;
            color: var(--live-red);
            margin-bottom: 30px;
            animation: spin 4s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .construction-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 36px;
            font-weight: 800;
            color: var(--live-red);
            margin-bottom: 20px;
        }
        
        .race-info {
            background: rgba(255, 0, 51, 0.1);
            border: 1px solid var(--live-red);
            border-radius: 15px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .race-info-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            color: white;
            font-size: 18px;
        }
        
        .btn-construction {
            background: transparent;
            border: 2px solid var(--live-red);
            color: var(--live-red);
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        
        .btn-construction:hover {
            background: var(--live-red);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px var(--live-red-dim);
        }
        
        @media (max-width: 768px) {
            .position-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .position-stats {
                width: 100%;
                justify-content: space-between;
                flex-wrap: wrap;
            }
            
            .stat-item {
                min-width: 70px;
            }
            
            .tire-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .session-info {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<?php include "components/navbar.php"; ?>

<div class="container mt-5 pt-5">
    
    <?php if($isLive && !empty($positions)): ?>
    
    <!-- Live Header -->
    <div class="live-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="live-badge">
                    <i class="fas fa-circle me-2"></i>NA ŻYWO
                </span>
                <span class="update-time">
                    <i class="fas fa-sync-alt me-2"></i>Odświeżono: <?= date('H:i:s', $lastUpdate) ?>
                </span>
            </div>
            
            <?php if($session): ?>
            <div class="session-info">
                <div class="session-item">
                    <i class="fas fa-flag-checkered"></i>
                    <span><?= $session['session_name'] ?? 'Wyścig' ?></span>
                </div>
                <div class="session-item">
                    <i class="fas fa-clock"></i>
                    <span>Okrążenie <?= $session['current_lap'] ?? '0' ?>/<?= $session['total_laps'] ?? '?' ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Mini Dashboard -->
    <div class="mini-dashboard">
        <div class="dashboard-card">
            <i class="fas fa-flag-checkered" style="color: var(--live-red); font-size: 24px;"></i>
            <div class="dashboard-value"><?= $positions[0]['driver_name'] ?? '-' ?></div>
            <div class="dashboard-label">Lider wyścigu</div>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-tachometer-alt" style="color: var(--live-red); font-size: 24px;"></i>
            <div class="dashboard-value"><?= $positions[0]['last_lap'] ?? '--:--.---' ?></div>
            <div class="dashboard-label">Najszybsze okrążenie</div>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-wrench" style="color: var(--live-red); font-size: 24px;"></i>
            <div class="dashboard-value"><?= count($pitStops) ?></div>
            <div class="dashboard-label">Pit stopy</div>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-flag" style="color: var(--live-red); font-size: 24px;"></i>
            <div class="dashboard-value"><?= count($raceControl) ?></div>
            <div class="dashboard-label">Komunikaty</div>
        </div>
    </div>
    
    <!-- Main Race Table -->
    <div class="position-table">
        <div class="table-title">
            <i class="fas fa-list-ol"></i>
            Kolejność w wyścigu
        </div>
        
        <?php foreach($positions as $pos): 
            $driverImg = $drivers[$pos['driver_name']]['image_path'] ?? 'default.jpg';
        ?>
        <div class="position-row <?= 'position-' . $pos['position'] ?>">
            <div class="position-number"><?= $pos['position'] ?></div>
            
            <div class="driver-info">
                <img src="assets/drivers/<?= $driverImg ?>" 
                     alt="<?= $pos['driver_name'] ?>"
                     class="driver-avatar"
                     onerror="this.src='assets/avatars/default.jpg'">
                <div>
                    <div class="driver-name">
                        <?= $pos['driver_name'] ?>
                        <?php if($pos['pit_in'] ?? false): ?>
                            <span class="pit-badge">PIT</span>
                        <?php endif; ?>
                        <?php if($pos['retired'] ?? false): ?>
                            <span class="out-badge">OUT</span>
                        <?php endif; ?>
                    </div>
                    <div class="team-name"><?= $pos['team_name'] ?? '' ?></div>
                </div>
            </div>
            
            <div class="position-stats">
                <div class="stat-item">
                    <div class="stat-label">Czas</div>
                    <div class="stat-value"><?= $pos['time'] ?? '--:--.---' ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Strata</div>
                    <div class="stat-value delta"><?= $pos['gap'] ?? '+0.000' ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Ostatnie okr.</div>
                    <div class="stat-value"><?= $pos['last_lap'] ?? '--:--.---' ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Pitty</div>
                    <div class="stat-value <?= ($pos['pit_stops'] ?? 0) > 0 ? 'pit' : '' ?>">
                        <?= $pos['pit_stops'] ?? 0 ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="row">
        <!-- Race Control -->
        <div class="col-md-6 mb-4">
            <div class="race-control-container">
                <div class="table-title">
                    <i class="fas fa-flag"></i>
                    Komunikaty wyścigowe
                </div>
                
                <?php if(!empty($raceControl)): ?>
                    <?php foreach(array_slice($raceControl, 0, 8) as $msg): ?>
                    <div class="control-item">
                        <div class="control-header">
                            <div class="flag-display flag-<?= $msg['flag'] ?? 'yellow' ?>">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div>
                                <div class="control-message"><?= $msg['message'] ?? '' ?></div>
                                <span class="control-time">
                                    <i class="far fa-clock me-1"></i><?= date('H:i:s', strtotime($msg['timestamp'] ?? 'now')) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-secondary text-center py-4">Brak komunikatów wyścigowych</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Pit Stops -->
        <div class="col-md-6 mb-4">
            <div class="pit-container">
                <div class="table-title">
                    <i class="fas fa-wrench"></i>
                    Pit stopy
                </div>
                
                <?php if(!empty($pitStops)): ?>
                    <?php foreach(array_slice($pitStops, 0, 8) as $pit): ?>
                    <div class="pit-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="pit-driver">
                                    <?= $pit['driver_name'] ?>
                                    <span class="pit-lap ms-2">(okr. <?= $pit['lap'] ?>)</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="pit-time"><?= $pit['pit_time'] ?>s</div>
                                <?php if($pit['pit_delta'] ?? false): ?>
                                    <div class="pit-delta">delta <?= $pit['pit_delta'] ?>s</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-secondary text-center py-4">Brak pit stopów w tym wyścigu</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Tire Statistics -->
    <?php if(!empty($tireStats)): ?>
    <div class="tire-container">
        <div class="table-title">
            <i class="fas fa-circle"></i>
            Statystyki opon - najszybsze czasy
        </div>
        
        <div class="tire-grid">
            <?php 
            $compounds = [
                'soft' => 'Miękkie',
                'medium' => 'Średnie',
                'hard' => 'Twarde',
                'intermediate' => 'Pośrednie',
                'wet' => 'Deszczowe'
            ];
            foreach($compounds as $key => $label): 
                $stat = $tireStats[$key] ?? null;
            ?>
            <div class="tire-card tire-<?= $key ?>">
                <div class="tire-compound"><?= $label ?></div>
                <div class="tire-time"><?= $stat['fastest_time'] ?? '--:--.---' ?></div>
                <div class="tire-driver"><?= $stat['fastest_driver'] ?? '-' ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Auto-refresh co 10 sekund -->
    <script>
        setTimeout(function() {
            location.reload();
        }, 10000);
    </script>
    
    <?php else: ?>
    
    <!-- Strona w budowie / Brak live -->
    <div class="construction-container">
        <div class="construction-card">
            <div class="construction-icon">
                <i class="fas fa-satellite-dish"></i>
            </div>
            
            <h1 class="construction-title">
                AKTUALNY WYŚCIG
            </h1>
            
            <p class="construction-text">
                Sekcja śledzenia wyścigów na żywo zostanie aktywowana podczas pierwszego wyścigu sezonu 2026.
            </p>
            
            <?php if($nextRace): ?>
            <div class="race-info">
                <div class="race-info-item">
                    <i class="fas fa-flag-checkered" style="font-size: 24px;"></i>
                    <div>
                        <div style="font-size: 20px; font-weight: 700;"><?= $nextRace['grand_prix'] ?></div>
                        <div style="color: #ccc;"><?= $nextRace['circuit'] ?>, <?= $nextRace['country'] ?></div>
                        <div style="color: #ff0033; margin-top: 10px;">
                            <i class="far fa-calendar-alt me-2"></i><?= date('d.m.Y', strtotime($nextRace['date'])) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="feature-list mt-4 text-start">
                <h5 style="color: #ff0033;">Dostępne na żywo:</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Kolejność kierowców</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Czasy okrążeń i odstępy</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Pit stopy i komunikaty</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Statystyki opon</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Flagi i safety car</li>
                </ul>
            </div>
            
            <a href="centrum_f1_2026.php" class="btn-construction">
                <i class="fas fa-arrow-left me-2"></i>Wróć do centrum F1
            </a>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>