<?php
include "backend/config.php";

// Sprawdź czy to mobile
if (isMobile() && !isset($_GET['fullsite'])) {
    if (file_exists('aktualny_wyscig_mobile.php')) {
        include 'aktualny_wyscig_mobile.php';
        exit();
    }
}

// Sprawdzenie logowania
if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit();
}

// Sprawdź czy jest aktualnie jakiś wyścig
$currentRace = $conn->query("
    SELECT r.* 
    FROM f1_races_2026 r
    WHERE r.date = CURDATE()
    LIMIT 1
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktualny wyścig - Racepedia</title>
    
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
            border: 2px solid #ff0033;
            border-radius: 20px;
            padding: 50px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 0 50px rgba(255, 0, 51, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 30px rgba(255, 0, 51, 0.3); }
            50% { box-shadow: 0 0 60px rgba(255, 0, 51, 0.6); }
            100% { box-shadow: 0 0 30px rgba(255, 0, 51, 0.3); }
        }
        
        .construction-icon {
            font-size: 80px;
            color: #ff0033;
            margin-bottom: 30px;
            animation: spin 4s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .live-badge {
            display: inline-block;
            background: #ff0033;
            color: white;
            font-size: 14px;
            font-weight: 700;
            padding: 5px 15px;
            border-radius: 30px;
            margin-bottom: 20px;
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .construction-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 36px;
            font-weight: 800;
            color: #ff0033;
            margin-bottom: 20px;
        }
        
        .construction-text {
            font-size: 18px;
            color: #ccc;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .race-info {
            background: rgba(255, 0, 51, 0.1);
            border: 1px solid #ff0033;
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
            font-size: 16px;
        }
        
        .race-info-item i {
            color: #ff0033;
            font-size: 24px;
        }
        
        .progress-bar-container {
            width: 100%;
            height: 10px;
            background: #333;
            border-radius: 5px;
            margin: 30px 0;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            width: 75%;
            height: 100%;
            background: #ff0033;
            border-radius: 5px;
            animation: progress 2s ease-in-out infinite;
        }
        
        @keyframes progress {
            0% { width: 70%; }
            50% { width: 80%; }
            100% { width: 70%; }
        }
        
        .feature-list {
            text-align: left;
            background: #0a0a0a;
            border-radius: 15px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .feature-list h4 {
            color: #ff0033;
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 15px;
        }
        
        .feature-list ul {
            list-style: none;
            padding: 0;
        }
        
        .feature-list li {
            padding: 10px 0;
            border-bottom: 1px solid #333;
            color: #ccc;
        }
        
        .feature-list li:last-child {
            border-bottom: none;
        }
        
        .feature-list li i {
            color: #ff0033;
            width: 25px;
            margin-right: 10px;
        }
        
        .btn-construction {
            background: transparent;
            border: 2px solid #ff0033;
            color: #ff0033;
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
            background: #ff0033;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 0, 51, 0.4);
        }
        
        @media (max-width: 768px) {
            .construction-title {
                font-size: 24px;
            }
            
            .construction-card {
                padding: 30px;
            }
            
            .race-info-item {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>

<?php include "components/navbar.php"; ?>

<div class="construction-container">
    <div class="construction-card">
        <div class="live-badge">
            <i class="fas fa-circle me-2"></i>NA ŻYWO JUŻ WKRÓTCE
        </div>
        
        <div class="construction-icon">
            <i class="fas fa-satellite-dish"></i>
        </div>
        
        <h1 class="construction-title">
            AKTUALNY WYŚCIG
        </h1>
        
        <p class="construction-text">
            Sekcja śledzenia wyścigów na żywo jest obecnie w fazie intensywnych przygotowań.
        </p>
        
        <?php if($currentRace): ?>
        <div class="race-info">
            <div class="race-info-item">
                <i class="fas fa-flag-checkered"></i>
                <span><strong>Dzisiaj:</strong> <?= $currentRace['grand_prix'] ?></span>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="progress-bar-container">
            <div class="progress-bar-fill"></div>
        </div>
        
        <div class="feature-list">
            <h4><i class="fas fa-tools me-2"></i>Przygotowujemy:</h4>
            <ul>
                <li><i class="fas fa-check-circle text-success"></i> Kolejność na żywo</li>
                <li><i class="fas fa-spinner fa-spin text-warning"></i> Czasy okrążeń</li>
                <li><i class="fas fa-check-circle text-success"></i> Przewodnictwo w wyścigu</li>
                <li><i class="fas fa-spinner fa-spin text-warning"></i> Pit stopy</li>
                <li><i class="fas fa-check-circle text-success"></i> Karne sekundy</li>
                <li><i class="fas fa-spinner fa-spin text-warning"></i> Komentarz na żywo</li>
                <li><i class="fas fa-check-circle text-success"></i> Temperatury opon</li>
                <li><i class="fas fa-spinner fa-spin text-warning"></i> Streaming onboardów</li>
            </ul>
        </div>
        
        <p class="text-secondary mb-4">
            <i class="fas fa-clock me-2"></i>
            Planowana premiera: Start sezonu 2026
        </p>
        
        <a href="centrum_f1_2026.php" class="btn-construction">
            <i class="fas fa-arrow-left me-2"></i>Wróć do centrum F1
        </a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>