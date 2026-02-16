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
        
        .progress-bar-container {
            width: 100%;
            height: 10px;
            background: #333;
            border-radius: 5px;
            margin: 30px 0;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            width: 30%;
            height: 100%;
            background: #ff0033;
            border-radius: 5px;
            animation: progress 2s ease-in-out infinite;
        }
        
        @keyframes progress {
            0% { width: 20%; }
            50% { width: 40%; }
            100% { width: 20%; }
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
        }
        
        .btn-construction:hover {
            background: #ff0033;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 0, 51, 0.4);
        }
        
        .f1-flags {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .f1-flag {
            width: 40px;
            height: 40px;
            background: #222;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff0033;
            font-size: 20px;
            border: 1px solid #ff0033;
            animation: flagFade 2s infinite;
        }
        
        @keyframes flagFade {
            0% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
            100% { opacity: 0.3; transform: scale(1); }
        }
        
        .f1-flag:nth-child(1) { animation-delay: 0s; }
        .f1-flag:nth-child(2) { animation-delay: 0.2s; }
        .f1-flag:nth-child(3) { animation-delay: 0.4s; }
        .f1-flag:nth-child(4) { animation-delay: 0.6s; }
        .f1-flag:nth-child(5) { animation-delay: 0.8s; }
        .f1-flag:nth-child(6) { animation-delay: 1s; }
        .f1-flag:nth-child(7) { animation-delay: 1.2s; }
    </style>
</head>
<body>

<?php include "components/navbar.php"; ?>

<div class="construction-container">
    <div class="construction-card">
        <div class="construction-icon">
            <i class="fas fa-cogs"></i>
        </div>
        
        <h1 class="construction-title">CENTRUM F1 2026</h1>
        
        <p class="construction-text">
            Witaj w centrum sezonu Formuły 1 2026!<br>
            Ta sekcja jest obecnie w trakcie intensywnych prac przygotowawczych.
        </p>
        
        <div class="progress-bar-container">
            <div class="progress-bar-fill"></div>
        </div>
        
        <p class="mb-4" style="color: #ff0033; font-weight: 600;">
            <i class="fas fa-tools me-2"></i>
            Przygotowujemy dla Ciebie:
        </p>
        
        <div class="row text-start mb-4" style="color: #ccc;">
            <div class="col-6 mb-2">
                <i class="fas fa-check-circle text-success me-2"></i> Pełny kalendarz
            </div>
            <div class="col-6 mb-2">
                <i class="fas fa-spinner fa-spin text-warning me-2"></i> Wyniki na żywo
            </div>
            <div class="col-6 mb-2">
                <i class="fas fa-check-circle text-success me-2"></i> Kierowcy i zespoły
            </div>
            <div class="col-6 mb-2">
                <i class="fas fa-spinner fa-spin text-warning me-2"></i> Klasyfikacje
            </div>
            <div class="col-6 mb-2">
                <i class="fas fa-check-circle text-success me-2"></i> Tory sezonu
            </div>
            <div class="col-6 mb-2">
                <i class="fas fa-spinner fa-spin text-warning me-2"></i> Statystyki
            </div>
        </div>
        
        <div class="f1-flags">
            <div class="f1-flag"><i class="fas fa-flag-checkered"></i></div>
            <div class="f1-flag"><i class="fas fa-flag"></i></div>
            <div class="f1-flag"><i class="fas fa-flag-checkered"></i></div>
            <div class="f1-flag"><i class="fas fa-flag"></i></div>
            <div class="f1-flag"><i class="fas fa-flag-checkered"></i></div>
            <div class="f1-flag"><i class="fas fa-flag"></i></div>
            <div class="f1-flag"><i class="fas fa-flag-checkered"></i></div>
        </div>
        
        <a href="index.php" class="btn-construction mt-4">
            <i class="fas fa-arrow-left me-2"></i>Wróć na stronę główną
        </a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>