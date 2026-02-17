<?php
include "backend/config.php";

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

// Pobierz wszystkie wyścigi
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

// Grupuj wyścigi po miesiącach
$racesByMonth = [];
while ($race = $allRaces->fetch_assoc()) {
    $month = date('F Y', strtotime($race['date']));
    $racesByMonth[$month][] = $race;
}

// Pobierz klasyfikacje
$driverStandings = $conn->query("
    SELECT fs.*, d.full_name as driver_name, t.name as team_name
    FROM f1_driver_standings fs
    JOIN drivers d ON fs.driver_id = d.id
    JOIN teams t ON d.team_id = t.id
    WHERE fs.season = 2026
    ORDER BY fs.position ASC
    LIMIT 5
");

$constructorStandings = $conn->query("
    SELECT cs.*, t.name as team_name
    FROM f1_constructor_standings cs
    JOIN teams t ON cs.team_id = t.id
    WHERE cs.season = 2026
    ORDER BY cs.position ASC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Centrum F1 2026 - Racepedia Mobile</title>
    
    <!-- Bootstrap 5 mobile-first -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icon-css/css/flag-icons.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #0a0a0a;
            color: white;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.4;
            padding-bottom: 30px;
        }
        
        /* Kontener mobilny */
        .mobile-container {
            padding: 15px;
            max-width: 100%;
            overflow-x: hidden;
        }
        
        /* Header */
        .mobile-header {
            text-align: center;
            margin: 20px 0 30px 0;
        }
        
        .mobile-header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: #ff0033;
            margin-bottom: 5px;
        }
        
        .mobile-header p {
            font-size: 14px;
            color: #ccc;
        }
        
        /* Hero z najbliższym wyścigiem */
        .mobile-hero {
            background: linear-gradient(135deg, #111 0%, #1a1a1a 100%);
            border: 2px solid #ff0033;
            border-radius: 15px;
            padding: 25px 15px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(255,0,51,0.2);
        }
        
        .mobile-hero::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255,0,51,0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 3s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.3); opacity: 0.1; }
            100% { transform: scale(1); opacity: 0.3; }
        }
        
        .hero-flag {
            font-size: 60px;
            text-align: center;
            margin-bottom: 15px;
            filter: drop-shadow(0 0 15px rgba(255,0,51,0.5));
        }
        
        .hero-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #ff0033;
            text-align: center;
            margin-bottom: 5px;
        }
        
        .hero-circuit {
            font-size: 14px;
            color: #ccc;
            text-align: center;
            margin-bottom: 20px;
        }
        
        /* Countdown mobilny */
        .mobile-countdown {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        
        .countdown-item {
            background: rgba(0,0,0,0.5);
            border: 1px solid #ff0033;
            border-radius: 10px;
            padding: 10px 5px;
            min-width: 60px;
            text-align: center;
        }
        
        .countdown-number {
            font-family: 'Orbitron', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #ff0033;
            line-height: 1;
        }
        
        .countdown-label {
            font-size: 10px;
            color: #ccc;
            text-transform: uppercase;
        }
        
        /* Przyciski akcji */
        .mobile-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 25px 0;
            flex-wrap: wrap;
        }
        
        .mobile-btn {
            flex: 1;
            min-width: 120px;
            background: transparent;
            border: 2px solid #ff0033;
            color: #ff0033;
            padding: 12px 10px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .mobile-btn.active,
        .mobile-btn:active {
            background: #ff0033;
            color: white;
        }
        
        .mobile-btn.live {
            background: #ff0033;
            color: white;
            animation: pulse-live 2s infinite;
        }
        
        @keyframes pulse-live {
            0% { box-shadow: 0 0 0 0 rgba(255,0,51,0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255,0,51,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,0,51,0); }
        }
        
        /* Sekcje ukryte */
        .mobile-section {
            display: none;
            margin-top: 20px;
        }
        
        .mobile-section.show {
            display: block;
        }
        
        /* Przełączniki tabel */
        .table-switchers {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .table-switcher {
            background: transparent;
            border: 1px solid #ff0033;
            color: #ff0033;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .table-switcher.active {
            background: #ff0033;
            color: white;
        }
        
        /* Karty wyścigów */
        .race-card {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }
        
        .race-card:active {
            transform: scale(0.98);
            background: #1a1a1a;
        }
        
        .race-card.today {
            border: 2px solid #ff0033;
            animation: glow 2s infinite;
        }
        
        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(255,0,51,0.3); }
            50% { box-shadow: 0 0 15px rgba(255,0,51,0.6); }
            100% { box-shadow: 0 0 5px rgba(255,0,51,0.3); }
        }
        
        .race-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .race-flag {
            font-size: 30px;
        }
        
        .race-title {
            flex: 1;
        }
        
        .race-round {
            font-size: 12px;
            color: #ff0033;
            font-family: 'Orbitron', sans-serif;
        }
        
        .race-name {
            font-size: 16px;
            font-weight: 600;
            color: white;
        }
        
        .race-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
        }
        
        .race-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #ccc;
        }
        
        .race-detail i {
            color: #ff0033;
            width: 20px;
        }
        
        .sprint-badge {
            background: #ff0033;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 8px;
            text-transform: uppercase;
        }
        
        /* Tabele klasyfikacji */
        .standings-table {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .table-header {
            font-family: 'Orbitron', sans-serif;
            color: #ff0033;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ff0033;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .standing-row {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }
        
        .standing-row:last-child {
            border-bottom: none;
        }
        
        .standing-pos {
            width: 40px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 16px;
        }
        
        .pos-1 { color: gold; }
        .pos-2 { color: silver; }
        .pos-3 { color: #cd7f32; }
        
        .standing-driver {
            flex: 2;
            font-weight: 500;
        }
        
        .standing-team {
            flex: 1;
            font-size: 11px;
            color: #ccc;
        }
        
        .standing-points {
            font-weight: 700;
            color: #ff0033;
            min-width: 50px;
            text-align: right;
        }
        
        /* Filtry kalendarza */
        .filter-buttons {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 10px 0;
            margin-bottom: 15px;
            -webkit-overflow-scrolling: touch;
        }
        
        .filter-btn {
            background: transparent;
            border: 1px solid #ff0033;
            color: #ff0033;
            padding: 8px 15px;
            font-size: 13px;
            border-radius: 20px;
            white-space: nowrap;
            cursor: pointer;
        }
        
        .filter-btn.active {
            background: #ff0033;
            color: white;
        }
        
        /* Miesiące */
        .month-divider {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            color: #ff0033;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid rgba(255,0,51,0.3);
        }
        
        /* View all link */
        .view-all {
            text-align: center;
            margin-top: 15px;
        }
        
        .view-all a {
            color: #ff0033;
            text-decoration: none;
            font-size: 13px;
        }
        
        /* Powrót do pełnej wersji */
        .full-site-link {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: #111;
            border-top: 1px solid #ff0033;
            border-bottom: 1px solid #ff0033;
        }
        
        .full-site-link a {
            color: #ff0033;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="mobile-container">
    
    <!-- Header -->
    <div class="mobile-header">
        <h1>F1 2026</h1>
        <p>Centrum sezonu</p>
    </div>
    
    <?php if($nextRace): ?>
    <!-- Hero z najbliższym wyścigiem -->
    <div class="mobile-hero">
        <div class="hero-flag">
            <span class="fi fi-<?= $nextRace['flag_code'] ?>"></span>
        </div>
        <div class="hero-title">
            Runda <?= $nextRace['round'] ?>
        </div>
        <div class="hero-title" style="font-size: 18px; color: white;">
            <?= $nextRace['grand_prix'] ?>
        </div>
        <div class="hero-circuit">
            <?= $nextRace['circuit'] ?>
        </div>
        
        <!-- Countdown -->
        <div class="mobile-countdown" id="countdown">
            <div class="countdown-item">
                <div class="countdown-number" id="days">0</div>
                <div class="countdown-label">Dni</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="hours">0</div>
                <div class="countdown-label">Godz</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="minutes">0</div>
                <div class="countdown-label">Min</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="seconds">0</div>
                <div class="countdown-label">Sek</div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Przyciski akcji -->
    <div class="mobile-actions">
        <button class="mobile-btn" onclick="toggleSection('calendar')">
            <i class="fas fa-calendar-alt"></i>
            Kalendarz
        </button>
        <button class="mobile-btn" onclick="showStandings()">
            <i class="fas fa-table"></i>
            Tabela
        </button>
        <a href="live.php" class="mobile-btn live">
            <i class="fas fa-broadcast-tower"></i>
            Na żywo
        </a>
    </div>
    
    <!-- Sekcja kalendarza -->
    <div id="calendarSection" class="mobile-section">
        <!-- Filtry -->
        <div class="filter-buttons">
            <button class="filter-btn active" onclick="filterRaces('all')">Wszystkie</button>
            <button class="filter-btn" onclick="filterRaces('upcoming')">Nadchodzące</button>
            <button class="filter-btn" onclick="filterRaces('completed')">Zakończone</button>
            <button class="filter-btn" onclick="filterRaces('sprint')">Sprint</button>
        </div>
        
        <!-- Kalendarz -->
        <div id="calendar-container">
            <?php foreach($racesByMonth as $month => $races): ?>
            <div class="month-divider"><?= $month ?></div>
            
            <?php foreach($races as $race): ?>
            <div class="race-card <?= $race['race_status'] ?>" data-status="<?= $race['race_status'] ?>" data-sprint="<?= $race['sprint_date'] ? 'sprint' : 'no-sprint' ?>">
                <div class="race-header">
                    <div class="race-flag">
                        <span class="fi fi-<?= $race['flag_code'] ?>"></span>
                    </div>
                    <div class="race-title">
                        <div class="race-round">Runda <?= $race['round'] ?></div>
                        <div class="race-name"><?= $race['grand_prix'] ?></div>
                    </div>
                </div>
                
                <div class="race-details">
                    <div class="race-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= $race['circuit'] ?></span>
                    </div>
                    <div class="race-detail">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?= date('d.m.Y', strtotime($race['date'])) ?></span>
                        <?php if($race['sprint_date']): ?>
                            <span class="sprint-badge">Sprint</span>
                        <?php endif; ?>
                    </div>
                    <div class="race-detail">
                        <i class="fas fa-clock"></i>
                        <span>Kwalifikacje: <?= date('d.m.Y', strtotime($race['qualifying_date'])) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Sekcja tabel -->
    <div id="standingsSection" class="mobile-section">
        <!-- Przełączniki -->
        <div class="table-switchers">
            <button class="table-switcher active" onclick="switchStandings('drivers')">
                <i class="fas fa-crown"></i> Kierowcy
            </button>
            <button class="table-switcher" onclick="switchStandings('constructors')">
                <i class="fas fa-building"></i> Konstruktorzy
            </button>
        </div>
        
        <!-- Klasyfikacja kierowców -->
        <div id="driversStanding" class="standings-table">
            <div class="table-header">
                <i class="fas fa-crown"></i> Mistrzostwa Kierowców
            </div>
            
            <?php while($d = $driverStandings->fetch_assoc()): ?>
            <div class="standing-row">
                <div class="standing-pos pos-<?= $d['position'] ?>"><?= $d['position'] ?></div>
                <div class="standing-driver"><?= e($d['driver_name']) ?></div>
                <div class="standing-team"><?= e($d['team_name']) ?></div>
                <div class="standing-points"><?= $d['points'] ?></div>
            </div>
            <?php endwhile; ?>
            
            <div class="view-all">
                <a href="centrum_f1_2026.php?tab=drivers">Zobacz pełną klasyfikację →</a>
            </div>
        </div>
        
        <!-- Klasyfikacja konstruktorów -->
        <div id="constructorsStanding" class="standings-table" style="display: none;">
            <div class="table-header">
                <i class="fas fa-building"></i> Mistrzostwa Konstruktorów
            </div>
            
            <?php while($t = $constructorStandings->fetch_assoc()): ?>
            <div class="standing-row">
                <div class="standing-pos pos-<?= $t['position'] ?>"><?= $t['position'] ?></div>
                <div class="standing-driver"><?= e($t['team_name']) ?></div>
                <div class="standing-team">—</div>
                <div class="standing-points"><?= $t['points'] ?></div>
            </div>
            <?php endwhile; ?>
            
            <div class="view-all">
                <a href="centrum_f1_2026.php?tab=constructors">Zobacz pełną klasyfikację →</a>
            </div>
        </div>
    </div>
    
    <!-- Link do pełnej wersji -->
    <div class="full-site-link">
        <a href="centrum_f1_2026.php?fullsite=1">
            <i class="fas fa-desktop"></i>
            Przejdź do pełnej wersji
        </a>
    </div>
</div>

<script>
// Countdown
function updateCountdown() {
    const raceDate = new Date('<?= $nextRace['date'] ?>').getTime();
    const now = new Date().getTime();
    const distance = raceDate - now;
    
    if (distance < 0) {
        document.getElementById('countdown').innerHTML = '<div style="color:#ff0033;">Wyścig trwa!</div>';
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

// Pokaż/ukryj sekcje
function toggleSection(section) {
    document.getElementById('calendarSection').classList.toggle('show');
    document.getElementById('standingsSection').classList.remove('show');
}

function showStandings() {
    document.getElementById('standingsSection').classList.add('show');
    document.getElementById('calendarSection').classList.remove('show');
}

// Przełączanie klasyfikacji
function switchStandings(type) {
    document.querySelectorAll('.table-switcher').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (type === 'drivers') {
        document.getElementById('driversStanding').style.display = 'block';
        document.getElementById('constructorsStanding').style.display = 'none';
    } else {
        document.getElementById('driversStanding').style.display = 'none';
        document.getElementById('constructorsStanding').style.display = 'block';
    }
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
                card.style.display = card.dataset.status === 'upcoming' ? 'block' : 'none';
                break;
            case 'completed':
                card.style.display = (card.dataset.status === 'past' || card.dataset.status === 'today') ? 'block' : 'none';
                break;
            case 'sprint':
                card.style.display = card.dataset.sprint === 'sprint' ? 'block' : 'none';
                break;
        }
    });
}

// Auto-odświeżanie countdown
<?php if($nextRace && $nextRace['days_until'] > 0): ?>
setInterval(updateCountdown, 1000);
updateCountdown();
<?php endif; ?>
</script>

</body>
</html>