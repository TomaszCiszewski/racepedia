<?php
include "backend/config.php";

// Sprawdzenie logowania - komentuję bo chcesz chyba żeby było publiczne?
// if(!isset($_SESSION['user'])) header("Location: login.php");

// Funkcja pomocnicza do escape'owaniu

// DANE KIEROWCÓW (rozszerzone)
$drivers = [
    [
        "name" => "Max Verstappen",
        "number" => "#1",
        "team" => "Red Bull Racing",
        "country" => "Holandia",
        "img" => "assets/drivers/verstappen.png",
        "info" => "4-krotny mistrz świata F1. Obrońca tytułu."
    ],
    [
        "name" => "Lewis Hamilton",
        "number" => "#44",
        "team" => "Ferrari",
        "country" => "Wielka Brytania",
        "img" => "assets/drivers/hamilton.png",
        "info" => "7-krotny mistrz świata. Jeden z najbardziej utytułowanych kierowców w historii F1."
    ],
    [
        "name" => "Fernando Alonso",
        "number" => "#14",
        "team" => "Aston Martin",
        "country" => "Hiszpania",
        "img" => "assets/drivers/alonso.png",
        "info" => "2-krotny mistrz świata. Legenda F1, znany z niesamowitej determinacji i doświadczenia."
    ],
    [
        "name" => "Charles Leclerc",
        "number" => "#16",
        "team" => "Ferrari",
        "country" => "Monako",
        "img" => "assets/drivers/leclerc.png",
        "info" => "Utalentowany kierowca Ferrari, wielokrotny zwycięzca Grand Prix."
    ],
    [
        "name" => "Lando Norris",
        "number" => "#4",
        "team" => "McLaren",
        "country" => "Wielka Brytania",
        "img" => "assets/drivers/norris.png",
        "info" => "Młody talent McLarena, znany z szybkości i aktywnej obecności w mediach."
    ],
    [
        "name" => "Oscar Piastri",
        "number" => "#81",
        "team" => "McLaren",
        "country" => "Australia",
        "img" => "assets/drivers/piastri.png",
        "info" => "Utalentowany Australijczyk, mistrz F2 i F3, przyszłość McLarena."
    ],
    [
        "name" => "George Russell",
        "number" => "#63",
        "team" => "Mercedes",
        "country" => "Wielka Brytania",
        "img" => "assets/drivers/russell.png",
        "info" => "Lider Mercedesa, dyrektor GPDA. Jeden z najjaśniejszych talentów w stawce."
    ],
    [
        "name" => "Kimi Antonelli",
        "number" => "#12",
        "team" => "Mercedes",
        "country" => "Włochy",
        "img" => "assets/drivers/antonelli.png",
        "info" => "Młody włoski talent, mistrz FRECA i F4, przyszłość Mercedesa."
    ],
    [
        "name" => "Carlos Sainz",
        "number" => "#55",
        "team" => "Williams",
        "country" => "Hiszpania",
        "img" => "assets/drivers/sainz.png",
        "info" => "Doświadczony Hiszpan, zwycięzca Grand Prix, teraz w Williamsie."
    ],
    [
        "name" => "Alexander Albon",
        "number" => "#23",
        "team" => "Williams",
        "country" => "Tajlandia",
        "img" => "assets/drivers/albon.png",
        "info" => "Utalentowany kierowca, kilkukrotnie na podium, lider Williamsa."
    ],
    [
        "name" => "Pierre Gasly",
        "number" => "#10",
        "team" => "Alpine",
        "country" => "Francja",
        "img" => "assets/drivers/gasly.png",
        "info" => "Zwycięzca Grand Prix, mistrz determinacji, teraz w Alpine."
    ],
    [
        "name" => "Franco Colapinto",
        "number" => "TBA",
        "team" => "Alpine",
        "country" => "Argentyna",
        "img" => "assets/drivers/colapinto.png",
        "info" => "Młody argentyński talent, przyszłość Alpine."
    ],
    [
        "name" => "Lance Stroll",
        "number" => "#18",
        "team" => "Aston Martin",
        "country" => "Kanada",
        "img" => "assets/drivers/stroll.png",
        "info" => "Doświadczony Kanadyjczyk, kilkukrotnie na podium w F1."
    ],
    [
        "name" => "Nico Hülkenberg",
        "number" => "#27",
        "team" => "Audi",
        "country" => "Niemcy",
        "img" => "assets/drivers/hulkenberg.png",
        "info" => "Doświadczony Niemiec, znany z niesamowitych kwalifikacji, teraz w Audi."
    ],
    [
        "name" => "Gabriel Bortoleto",
        "number" => "TBA",
        "team" => "Audi",
        "country" => "Brazylia",
        "img" => "assets/drivers/bortoleto.png",
        "info" => "Młody brazylijski talent, mistrz F3, przyszłość Audi."
    ],
    [
        "name" => "Sergio Perez",
        "number" => "#11",
        "team" => "Cadillac",
        "country" => "Meksyk",
        "img" => "assets/drivers/perez.png",
        "info" => "Specjalista od opon, wielokrotny zwycięzca Grand Prix, teraz w Cadillac."
    ],
    [
        "name" => "Valtteri Bottas",
        "number" => "#77",
        "team" => "Cadillac",
        "country" => "Finlandia",
        "img" => "assets/drivers/bottas.png",
        "info" => "10-krotny zwycięzca Grand Prix, doświadczenie z Mercedesa, teraz w Cadillac."
    ],
    [
        "name" => "Esteban Ocon",
        "number" => "#31",
        "team" => "Haas F1 Team",
        "country" => "Francja",
        "img" => "assets/drivers/ocon.png",
        "info" => "Zwycięzca Grand Prix, utalentowany Francuz, teraz w Haas."
    ],
    [
        "name" => "Oliver Bearman",
        "number" => "TBA",
        "team" => "Haas F1 Team",
        "country" => "Wielka Brytania",
        "img" => "assets/drivers/bearman.png",
        "info" => "Młody brytyjski talent, przyszłość Haasa."
    ],
    [
        "name" => "Liam Lawson",
        "number" => "#30",
        "team" => "Racing Bulls",
        "country" => "Nowa Zelandia",
        "img" => "assets/drivers/lawson.png",
        "info" => "Młody Nowozelandczyk, utalentowany kierowca Red Bulla."
    ],
    [
        "name" => "Arvid Lindblad",
        "number" => "TBA",
        "team" => "Racing Bulls",
        "country" => "Szwecja",
        "img" => "assets/drivers/lindblad.png",
        "info" => "Młody szwedzki talent, przyszłość Red Bulla."
    ],
    [
        "name" => "Isack Hadjar",
        "number" => "TBA",
        "team" => "Red Bull Racing",
        "country" => "Francja",
        "img" => "assets/drivers/hadjar.png",
        "info" => "Młody francuski talent, członek akademii Red Bulla."
    ]
];

// DANE TORÓW
$tracks = [
    [
        "name" => "Albert Park Circuit",
        "country" => "Australia",
        "flag" => "Australia",
        "date" => "6-8 marca 2026",
        "winner" => "Lando Norris",
        "length" => "5.278 km",
        "laps" => 58,
        "img" => "assets/tracks/australia.png",
        "info" => "Uliczny tor w Melbourne. Szybki, częściowo stały, częściowo uliczny, z słynnym zakrętem 11 i 12. Rozpoczyna sezon 2026."
    ],
    [
        "name" => "Shanghai International Circuit",
        "country" => "Chiny",
        "flag" => "Chiny",
        "date" => "13-15 marca 2026",
        "winner" => "Oscar Piastri",
        "length" => "5.451 km",
        "laps" => 56,
        "img" => "assets/tracks/chiny.png",
        "info" => "Nowoczesny tor zaprojektowany przez Hermanna Tilke. Charakteryzuje się unikalnym układem i długą prostą startową. W 2026 odbędzie się tutaj sprint weekend."
    ],
    [
        "name" => "Suzuka International Racing Course",
        "country" => "Japonia",
        "flag" => "Japonia",
        "date" => "27-29 marca 2026",
        "winner" => "Max Verstappen",
        "length" => "5.807 km",
        "laps" => 53,
        "img" => "assets/tracks/japonia.png",
        "info" => "Kultowa ósemka. Jeden z najbardziej wymagających technicznie torów w kalendarzu, uwielbiany przez kierowców."
    ],
    [
        "name" => "Bahrain International Circuit",
        "country" => "Bahrajn",
        "flag" => "Bahrajn",
        "date" => "10-12 kwietnia 2026",
        "winner" => "Oscar Piastri",
        "length" => "5.412 km",
        "laps" => 57,
        "img" => "assets/tracks/bahrajn.png",
        "info" => "Tor w Sakhir, tradycyjne miejsce testów przedsezonowych. Nowoczesny obiekt z długimi prostymi i wymagającymi zakrętami."
    ],
    [
        "name" => "Jeddah Corniche Circuit",
        "country" => "Arabia Saudyjska",
        "flag" => "Arabia Saudyjska",
        "date" => "17-19 kwietnia 2026",
        "winner" => "Oscar Piastri",
        "length" => "6.174 km",
        "laps" => 50,
        "img" => "assets/tracks/arabiasaudyjska.png",
        "info" => "Najszybszy uliczny tor w kalendarzu. Wysokie prędkości i ciasne bariery tworzą niesamowite widowisko."
    ],
    [
        "name" => "Miami International Autodrome",
        "country" => "USA (Miami)",
        "flag" => "USA",
        "date" => "1-3 maja 2026",
        "winner" => "Oscar Piastri",
        "length" => "5.412 km",
        "laps" => 57,
        "img" => "assets/tracks/usa.png",
        "info" => "Tor wokół stadionu Hard Rock. Mieszanka szybkich sekcji i technicznych zakrętów w klimacie Miami. W 2026 odbędzie się tutaj sprint weekend."
    ],
    [
        "name" => "Circuit Gilles Villeneuve",
        "country" => "Kanada",
        "flag" => "Kanada",
        "date" => "22-24 maja 2026",
        "winner" => "George Russell",
        "length" => "4.361 km",
        "laps" => 70,
        "img" => "assets/tracks/kanada.png",
        "info" => "Półstały tor w Montrealu. Słynie z długich prostych, 'ściany mistrzów' i nieprzewidywalnej pogody. Po raz pierwszy w historii odbędzie się tutaj sprint weekend."
    ],
    [
        "name" => "Circuit de Monaco",
        "country" => "Monako",
        "flag" => "Monako",
        "date" => "5-7 czerwca 2026",
        "winner" => "Lando Norris",
        "length" => "3.337 km",
        "laps" => 78,
        "img" => "assets/tracks/monako.png",
        "info" => "Perła koronna F1. Wąskie ulice, prestiż i glamour. Najwolniejszy, ale najbardziej prestiżowy tor w kalendarzu."
    ],
    [
        "name" => "Circuit de Barcelona-Catalunya",
        "country" => "Hiszpania",
        "flag" => "Hiszpania",
        "date" => "12-14 czerwca 2026",
        "winner" => "Oscar Piastri",
        "length" => "4.657 km",
        "laps" => 66,
        "img" => "assets/tracks/hiszpania.png",
        "info" => "Klasyczny tor używany do testów zimowych. Różnorodne zakręty sprawdzają wszystkie aspekty bolidu."
    ],
    [
        "name" => "Red Bull Ring",
        "country" => "Austria",
        "flag" => "Austria",
        "date" => "26-28 czerwca 2026",
        "winner" => "Lando Norris",
        "length" => "4.318 km",
        "laps" => 71,
        "img" => "assets/tracks/austria.png",
        "info" => "Krótki, ale szybki tor w Alpach. Krótkie okrążenia oznaczają dużo akcji i walkę na dystansie."
    ],
    [
        "name" => "Silverstone Circuit",
        "country" => "Wielka Brytania",
        "flag" => "Wielka Brytania",
        "date" => "3-5 lipca 2026",
        "winner" => "Lando Norris",
        "length" => "5.891 km",
        "laps" => 52,
        "img" => "assets/tracks/wielkabrytania.png",
        "info" => "Home of British Motor Racing. Szybkie, płynne zakręty jak Maggots, Becketts i Chapel to marzenie każdego kierowcy. W 2026 odbędzie się tutaj sprint weekend."
    ],
    [
        "name" => "Circuit de Spa-Francorchamps",
        "country" => "Belgia",
        "flag" => "Belgia",
        "date" => "17-19 lipca 2026",
        "winner" => "Oscar Piastri",
        "length" => "7.004 km",
        "laps" => 44,
        "img" => "assets/tracks/belgia.png",
        "info" => "Kultowe Eau Rouge i Raidillon. Najdłuższy i jeden z najbardziej wymagających torów w kalendarzu."
    ],
    [
        "name" => "Hungaroring",
        "country" => "Węgry",
        "flag" => "Węgry",
        "date" => "24-26 lipca 2026",
        "winner" => "Lando Norris",
        "length" => "4.381 km",
        "laps" => 70,
        "img" => "assets/tracks/wegry.png",
        "info" => "Ciasny, kręty tor pod Budapesztem. Trudny do wyprzedzania, ale technicznie wymagający dla kierowców."
    ],
    [
        "name" => "Circuit Zandvoort",
        "country" => "Holandia",
        "flag" => "Holandia",
        "date" => "21-23 sierpnia 2026",
        "winner" => "Oscar Piastri",
        "length" => "4.259 km",
        "laps" => 72,
        "img" => "assets/tracks/holandia.png",
        "info" => "Odnowiony tor z bandami i nachylonymi zakrętami. Kibice w pomarańczowym szale tworzą niesamowitą atmosferę. Po raz pierwszy w historii odbędzie się tutaj sprint weekend."
    ],
    [
        "name" => "Autodromo Nazionale Monza",
        "country" => "Włochy",
        "flag" => "Włochy",
        "date" => "4-6 września 2026",
        "winner" => "Max Verstappen",
        "length" => "5.793 km",
        "laps" => 53,
        "img" => "assets/tracks/wlochy.png",
        "info" => "Świątynia szybkości. Długie proste i szykany, gdzie liczy się moc silnika i mały opór powietrza."
    ],
    [
        "name" => "Madrid Street Circuit (Madring)",
        "country" => "Hiszpania (Madryt)",
        "flag" => "Hiszpania",
        "date" => "11-13 września 2026",
        "winner" => "NOWY TOR - debiut w 2026",
        "length" => "5.470 km",
        "laps" => 55,
        "img" => "assets/tracks/hiszpania.png",
        "info" => "Nowy hybrydowy tor uliczny w Madrycie. Debiutuje w kalendarzu F1 w 2026 roku, zastępując Imolę."
    ],
    [
        "name" => "Baku City Circuit",
        "country" => "Azerbejdżan",
        "flag" => "Azerbejdżan",
        "date" => "24-26 września 2026",
        "winner" => "Max Verstappen",
        "length" => "6.003 km",
        "laps" => 51,
        "img" => "assets/tracks/azerbejdzan.png",
        "info" => "Uliczny tor w Baku. Mieszanka wąskich sekcji i długiej prostej, gdzie prędkości sięgają 350 km/h. Wyścig odbędzie się w sobotę."
    ],
    [
        "name" => "Marina Bay Street Circuit",
        "country" => "Singapur",
        "flag" => "Singapur",
        "date" => "9-11 października 2026",
        "winner" => "George Russell",
        "length" => "5.063 km",
        "laps" => 61,
        "img" => "assets/tracks/singapur.png",
        "info" => "Pierwszy nocny wyścig w F1. Wilgotność i temperatura sprawiają, że to jeden z najbardziej wymagających fizycznie wyścigów. Po raz pierwszy odbędzie się tutaj sprint weekend."
    ],
    [
        "name" => "Circuit of the Americas",
        "country" => "USA (Austin)",
        "flag" => "USA",
        "date" => "23-25 października 2026",
        "winner" => "Max Verstappen",
        "length" => "5.513 km",
        "laps" => 56,
        "img" => "assets/tracks/usa.png",
        "info" => "Nowoczesny tor w Teksasie. Słynie z podjazdu na pierwszy zakręt i sekencji szybkich esów."
    ],
    [
        "name" => "Autódromo Hermanos Rodríguez",
        "country" => "Meksyk",
        "flag" => "Meksyk",
        "date" => "30 października - 1 listopada 2026",
        "winner" => "Lando Norris",
        "length" => "4.304 km",
        "laps" => 71,
        "img" => "assets/tracks/meksyk.png",
        "info" => "Tor na dużej wysokości, co wpływa na aerodynamikę. Słynie z głośnych i oddanych kibiców."
    ],
    [
        "name" => "Autódromo José Carlos Pace (Interlagos)",
        "country" => "Brazylia",
        "flag" => "Brazylia",
        "date" => "6-8 listopada 2026",
        "winner" => "Lando Norris",
        "length" => "4.309 km",
        "laps" => 71,
        "img" => "assets/tracks/brazylia.png",
        "info" => "Tor w Interlagos. Kultowe zakręty, zmienna pogoda i niesamowita atmosfera tworzą legendę tego miejsca."
    ],
    [
        "name" => "Las Vegas Strip Circuit",
        "country" => "USA (Las Vegas)",
        "flag" => "USA",
        "date" => "19-21 listopada 2026",
        "winner" => "Max Verstappen",
        "length" => "6.201 km",
        "laps" => 50,
        "img" => "assets/tracks/usa.png",
        "info" => "Tor na Stripie w Las Vegas. Najdłuższa prosta w kalendarzu i nocny wyścig wśród kasyn. Wyścig odbędzie się w sobotę."
    ],
    [
        "name" => "Lusail International Circuit",
        "country" => "Katar",
        "flag" => "Katar",
        "date" => "27-29 listopada 2026",
        "winner" => "Max Verstappen",
        "length" => "5.419 km",
        "laps" => 57,
        "img" => "assets/tracks/katar.png",
        "info" => "Nowoczesny tor w Katarze. Szybkie zakręty i wymagające warunki fizyczne dla kierowców."
    ],
    [
        "name" => "Yas Marina Circuit",
        "country" => "Abu Zabi",
        "flag" => "ZEA",
        "date" => "4-6 grudnia 2026",
        "winner" => "Max Verstappen",
        "length" => "5.281 km",
        "laps" => 58,
        "img" => "assets/tracks/zea.png",
        "info" => "Finał sezonu. Nowoczesny tor z tunelem i wyścigiem o zachodzie słońca, kończący się pod pałacem."
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
                        <div class="driver-image-wrapper" style="height: 280px; overflow: hidden; position: relative;">
                            <img src="<?= e($driver['img']) ?>" 
                                alt="<?= e($driver['name']) ?>" 
                                class="driver-image"
                                style="width: 100%; height: auto; object-fit: cover; object-position: center 0%; margin-top: -10px;">
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

                <!-- Modal dla kierowcy - CAŁE ZDJĘCIE -->
                <div class="modal fade" id="driverModal<?= md5($driver['name']) ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content glass-card">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= e($driver['name']) ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- CAŁE ZDJĘCIE w modalnym oknie -->
                                        <img src="<?= e($driver['img']) ?>" 
                                            alt="<?= e($driver['name']) ?>" 
                                            class="img-fluid rounded"
                                            style="width: 100%; height: auto; object-fit: contain;">
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="text-accent"><?= e($driver['name']) ?></h4>
                                        <p><strong><i class="fas fa-car me-2"></i>Zespół:</strong> <?= e($driver['team']) ?></p>
                                        <p><strong><i class="fas fa-globe me-2"></i>Kraj:</strong> <?= e($driver['country']) ?></p>
                                        <p><strong><i class="fas fa-hashtag me-2"></i>Numer:</strong> <?= e($driver['number']) ?></p>
                                        <p class="mt-3"><?= e($driver['info']) ?></p>
                                    </div>
                                </div>
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
              <div class="col-12">
                  <div class="track-card-horizontal">
                      <div class="row g-0">
                          <div class="col-md-5">
                              <div class="track-image-wrapper" style="height: 300px; overflow: hidden;">
                                  <img src="<?= e($track['img']) ?>" 
                                      alt="<?= e($track['name']) ?>" 
                                      class="track-image-horizontal"
                                      style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                              </div>
                          </div>
                          <div class="col-md-7">
                              <div class="track-info-horizontal p-4">
                                  <h3 class="track-name-horizontal"><?= e($track['name']) ?></h3>
                                  
                                  <div class="track-meta-horizontal mb-3">
                                      <span class="badge bg-danger me-2"><i class="fas fa-flag me-1"></i><?= e($track['flag']) ?></span>
                                      <span class="badge bg-dark me-2"><i class="fas fa-calendar-alt me-1"></i><?= e($track['date']) ?></span>
                                      <span class="badge bg-dark me-2"><i class="fas fa-road me-1"></i><?= e($track['length']) ?></span>
                                      <span class="badge bg-dark"><i class="fas fa-flag-checkered me-1"></i><?= e($track['laps']) ?> okrążeń</span>
                                  </div>
                                  
                                  <div class="winner-info mb-3 p-2" style="background: rgba(255, 0, 51, 0.1); border-left: 4px solid var(--accent-red);">
                                      <i class="fas fa-trophy text-accent me-2"></i>
                                      <strong>Zwycięzca 2025:</strong> <?= e($track['winner']) ?>
                                  </div>
                                  
                                  <p class="track-description-horizontal"><?= e($track['info']) ?></p>
                                  
                                  <button class="btn btn-outline-danger mt-3" data-bs-toggle="modal" data-bs-target="#trackModal<?= md5($track['name']) ?>">
                                      <i class="fas fa-info-circle me-2"></i>Więcej informacji
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Modal dla toru -->
              <div class="modal fade" id="trackModal<?= md5($track['name']) ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content glass-card">
                          <div class="modal-header">
                              <h5 class="modal-title"><?= e($track['name']) ?></h5>
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                              <img src="<?= e($track['img']) ?>" class="img-fluid rounded mb-3" alt="">
                              <p><strong><i class="fas fa-flag me-2"></i>Kraj:</strong> <?= e($track['flag']) ?></p>
                              <p><strong><i class="fas fa-calendar-alt me-2"></i>Data wyścigu 2026:</strong> <?= e($track['date']) ?></p>
                              <p><strong><i class="fas fa-road me-2"></i>Długość:</strong> <?= e($track['length']) ?></p>
                              <p><strong><i class="fas fa-flag-checkered me-2"></i>Liczba okrążeń:</strong> <?= e($track['laps']) ?></p>
                              <p><strong><i class="fas fa-trophy me-2"></i>Zwycięzca 2025:</strong> <?= e($track['winner']) ?></p>
                              <p class="mt-3"><?= e($track['info']) ?></p>
                          </div>
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