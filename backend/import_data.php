<?php
include "config.php";

// Funkcja pomocnicza do sprawdzania czy tabela jest pusta
function isTableEmpty($conn, $table) {
    $result = $conn->query("SELECT COUNT(*) as count FROM $table");
    return $result->fetch_assoc()['count'] == 0;
}

// ========== DODAWANIE ZESPOŁÓW ==========
$teams = [
    'Red Bull Racing', 'Ferrari', 'Mercedes', 'McLaren', 'Aston Martin',
    'Alpine', 'Williams', 'Audi', 'Cadillac', 'Haas F1 Team', 'Racing Bulls'
];

foreach ($teams as $teamName) {
    // Sprawdź czy zespół już istnieje
    $stmt = $conn->prepare("SELECT id FROM teams WHERE name = ?");
    $stmt->bind_param("s", $teamName);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO teams (name, is_active) VALUES (?, 1)");
        $stmt->bind_param("s", $teamName);
        $stmt->execute();
        echo "Dodano zespół: $teamName<br>";
    }
}

// ========== DODAWANIE KIEROWCÓW ==========
$drivers = [
    [
        "full_name" => "Max Verstappen",
        "number" => 1,
        "country" => "Holandia",
        "team" => "Red Bull Racing",
        "image_path" => "verstappen.png",
        "bio" => "4-krotny mistrz świata F1. Obrońca tytułu.",
        "world_titles" => 4
    ],
    [
        "full_name" => "Lewis Hamilton",
        "number" => 44,
        "country" => "Wielka Brytania",
        "team" => "Ferrari",
        "image_path" => "hamilton.png",
        "bio" => "7-krotny mistrz świata. Jeden z najbardziej utytułowanych kierowców w historii F1.",
        "world_titles" => 7
    ],
    [
        "full_name" => "Fernando Alonso",
        "number" => 14,
        "country" => "Hiszpania",
        "team" => "Aston Martin",
        "image_path" => "alonso.png",
        "bio" => "2-krotny mistrz świata. Legenda F1, znany z niesamowitej determinacji i doświadczenia.",
        "world_titles" => 2
    ],
    [
        "full_name" => "Charles Leclerc",
        "number" => 16,
        "country" => "Monako",
        "team" => "Ferrari",
        "image_path" => "leclerc.png",
        "bio" => "Utalentowany kierowca Ferrari, wielokrotny zwycięzca Grand Prix.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Lando Norris",
        "number" => 4,
        "country" => "Wielka Brytania",
        "team" => "McLaren",
        "image_path" => "norris.png",
        "bio" => "Młody talent McLarena, znany z szybkości i aktywnej obecności w mediach.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Oscar Piastri",
        "number" => 81,
        "country" => "Australia",
        "team" => "McLaren",
        "image_path" => "piastri.png",
        "bio" => "Utalentowany Australijczyk, mistrz F2 i F3, przyszłość McLarena.",
        "world_titles" => 0
    ],
    [
        "full_name" => "George Russell",
        "number" => 63,
        "country" => "Wielka Brytania",
        "team" => "Mercedes",
        "image_path" => "russell.png",
        "bio" => "Lider Mercedesa, dyrektor GPDA. Jeden z najjaśniejszych talentów w stawce.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Kimi Antonelli",
        "number" => 12,
        "country" => "Włochy",
        "team" => "Mercedes",
        "image_path" => "antonelli.png",
        "bio" => "Młody włoski talent, mistrz FRECA i F4, przyszłość Mercedesa.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Carlos Sainz",
        "number" => 55,
        "country" => "Hiszpania",
        "team" => "Williams",
        "image_path" => "sainz.png",
        "bio" => "Doświadczony Hiszpan, zwycięzca Grand Prix, teraz w Williamsie.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Alexander Albon",
        "number" => 23,
        "country" => "Tajlandia",
        "team" => "Williams",
        "image_path" => "albon.png",
        "bio" => "Utalentowany kierowca, kilkukrotnie na podium, lider Williamsa.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Pierre Gasly",
        "number" => 10,
        "country" => "Francja",
        "team" => "Alpine",
        "image_path" => "gasly.png",
        "bio" => "Zwycięzca Grand Prix, mistrz determinacji, teraz w Alpine.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Franco Colapinto",
        "number" => null,
        "country" => "Argentyna",
        "team" => "Alpine",
        "image_path" => "colapinto.png",
        "bio" => "Młody argentyński talent, przyszłość Alpine.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Lance Stroll",
        "number" => 18,
        "country" => "Kanada",
        "team" => "Aston Martin",
        "image_path" => "stroll.png",
        "bio" => "Doświadczony Kanadyjczyk, kilkukrotnie na podium w F1.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Nico Hülkenberg",
        "number" => 27,
        "country" => "Niemcy",
        "team" => "Audi",
        "image_path" => "hulkenberg.png",
        "bio" => "Doświadczony Niemiec, znany z niesamowitych kwalifikacji, teraz w Audi.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Gabriel Bortoleto",
        "number" => null,
        "country" => "Brazylia",
        "team" => "Audi",
        "image_path" => "bortoleto.png",
        "bio" => "Młody brazylijski talent, mistrz F3, przyszłość Audi.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Sergio Perez",
        "number" => 11,
        "country" => "Meksyk",
        "team" => "Cadillac",
        "image_path" => "perez.png",
        "bio" => "Specjalista od opon, wielokrotny zwycięzca Grand Prix, teraz w Cadillac.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Valtteri Bottas",
        "number" => 77,
        "country" => "Finlandia",
        "team" => "Cadillac",
        "image_path" => "bottas.png",
        "bio" => "10-krotny zwycięzca Grand Prix, doświadczenie z Mercedesa, teraz w Cadillac.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Esteban Ocon",
        "number" => 31,
        "country" => "Francja",
        "team" => "Haas F1 Team",
        "image_path" => "ocon.png",
        "bio" => "Zwycięzca Grand Prix, utalentowany Francuz, teraz w Haas.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Oliver Bearman",
        "number" => null,
        "country" => "Wielka Brytania",
        "team" => "Haas F1 Team",
        "image_path" => "bearman.png",
        "bio" => "Młody brytyjski talent, przyszłość Haasa.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Liam Lawson",
        "number" => 30,
        "country" => "Nowa Zelandia",
        "team" => "Racing Bulls",
        "image_path" => "lawson.png",
        "bio" => "Młody Nowozelandczyk, utalentowany kierowca Red Bulla.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Arvid Lindblad",
        "number" => null,
        "country" => "Szwecja",
        "team" => "Racing Bulls",
        "image_path" => "lindblad.png",
        "bio" => "Młody szwedzki talent, przyszłość Red Bulla.",
        "world_titles" => 0
    ],
    [
        "full_name" => "Isack Hadjar",
        "number" => null,
        "country" => "Francja",
        "team" => "Red Bull Racing",
        "image_path" => "hadjar.png",
        "bio" => "Młody francuski talent, członek akademii Red Bulla.",
        "world_titles" => 0
    ]
];

foreach ($drivers as $driver) {
    // Pobierz ID zespołu
    $teamId = null;
    if ($driver['team']) {
        $stmt = $conn->prepare("SELECT id FROM teams WHERE name = ?");
        $stmt->bind_param("s", $driver['team']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $teamId = $row['id'];
        }
    }
    
    // Sprawdź czy kierowca już istnieje
    $stmt = $conn->prepare("SELECT id FROM drivers WHERE full_name = ?");
    $stmt->bind_param("s", $driver['full_name']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO drivers (full_name, number, country, team_id, image_path, bio, world_titles, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("sissisi", 
            $driver['full_name'], 
            $driver['number'], 
            $driver['country'], 
            $teamId, 
            $driver['image_path'], 
            $driver['bio'], 
            $driver['world_titles']
        );
        $stmt->execute();
        echo "Dodano kierowcę: {$driver['full_name']}<br>";
    }
}

// ========== DODAWANIE TORÓW ==========
$tracks = [
    [
        "name" => "Albert Park Circuit",
        "country" => "Australia",
        "city" => "Melbourne",
        "length_km" => 5.278,
        "laps" => 58,
        "image_path" => "australia.png",
        "bio" => "Uliczny tor w Melbourne. Szybki, częściowo stały, częściowo uliczny, z słynnym zakrętem 11 i 12. Rozpoczyna sezon 2026."
    ],
    [
        "name" => "Shanghai International Circuit",
        "country" => "Chiny",
        "city" => "Szanghaj",
        "length_km" => 5.451,
        "laps" => 56,
        "image_path" => "chiny.png",
        "bio" => "Nowoczesny tor zaprojektowany przez Hermanna Tilke. Charakteryzuje się unikalnym układem i długą prostą startową."
    ],
    [
        "name" => "Suzuka International Racing Course",
        "country" => "Japonia",
        "city" => "Suzuka",
        "length_km" => 5.807,
        "laps" => 53,
        "image_path" => "japonia.png",
        "bio" => "Kultowa ósemka. Jeden z najbardziej wymagających technicznie torów w kalendarzu, uwielbiany przez kierowców."
    ],
    [
        "name" => "Bahrain International Circuit",
        "country" => "Bahrajn",
        "city" => "Sakhir",
        "length_km" => 5.412,
        "laps" => 57,
        "image_path" => "bahrajn.png",
        "bio" => "Tor w Sakhir, tradycyjne miejsce testów przedsezonowych. Nowoczesny obiekt z długimi prostymi i wymagającymi zakrętami."
    ],
    [
        "name" => "Jeddah Corniche Circuit",
        "country" => "Arabia Saudyjska",
        "city" => "Dżudda",
        "length_km" => 6.174,
        "laps" => 50,
        "image_path" => "arabiasaudyjska.png",
        "bio" => "Najszybszy uliczny tor w kalendarzu. Wysokie prędkości i ciasne bariery tworzą niesamowite widowisko."
    ],
    [
        "name" => "Miami International Autodrome",
        "country" => "USA",
        "city" => "Miami",
        "length_km" => 5.412,
        "laps" => 57,
        "image_path" => "usa1.png",
        "bio" => "Tor wokół stadionu Hard Rock. Mieszanka szybkich sekcji i technicznych zakrętów w klimacie Miami."
    ],
    [
        "name" => "Circuit Gilles Villeneuve",
        "country" => "Kanada",
        "city" => "Montreal",
        "length_km" => 4.361,
        "laps" => 70,
        "image_path" => "kanada.png",
        "bio" => "Półstały tor w Montrealu. Słynie z długich prostych, 'ściany mistrzów' i nieprzewidywalnej pogody."
    ],
    [
        "name" => "Circuit de Monaco",
        "country" => "Monako",
        "city" => "Monte Carlo",
        "length_km" => 3.337,
        "laps" => 78,
        "image_path" => "monako.png",
        "bio" => "Perła koronna F1. Wąskie ulice, prestiż i glamour. Najwolniejszy, ale najbardziej prestiżowy tor w kalendarzu."
    ],
    [
        "name" => "Circuit de Barcelona-Catalunya",
        "country" => "Hiszpania",
        "city" => "Barcelona",
        "length_km" => 4.657,
        "laps" => 66,
        "image_path" => "barcelona.png",
        "bio" => "Klasyczny tor używany do testów zimowych. Różnorodne zakręty sprawdzają wszystkie aspekty bolidu."
    ],
    [
        "name" => "Red Bull Ring",
        "country" => "Austria",
        "city" => "Spielberg",
        "length_km" => 4.318,
        "laps" => 71,
        "image_path" => "austria.png",
        "bio" => "Krótki, ale szybki tor w Alpach. Krótkie okrążenia oznaczają dużo akcji i walkę na dystansie."
    ],
    [
        "name" => "Silverstone Circuit",
        "country" => "Wielka Brytania",
        "city" => "Silverstone",
        "length_km" => 5.891,
        "laps" => 52,
        "image_path" => "uk.png",
        "bio" => "Home of British Motor Racing. Szybkie, płynne zakręty jak Maggots, Becketts i Chapel to marzenie każdego kierowcy."
    ],
    [
        "name" => "Circuit de Spa-Francorchamps",
        "country" => "Belgia",
        "city" => "Spa",
        "length_km" => 7.004,
        "laps" => 44,
        "image_path" => "belgia.png",
        "bio" => "Kultowe Eau Rouge i Raidillon. Najdłuższy i jeden z najbardziej wymagających torów w kalendarzu."
    ],
    [
        "name" => "Hungaroring",
        "country" => "Węgry",
        "city" => "Budapeszt",
        "length_km" => 4.381,
        "laps" => 70,
        "image_path" => "wegry.png",
        "bio" => "Ciasny, kręty tor pod Budapesztem. Trudny do wyprzedzania, ale technicznie wymagający dla kierowców."
    ],
    [
        "name" => "Circuit Zandvoort",
        "country" => "Holandia",
        "city" => "Zandvoort",
        "length_km" => 4.259,
        "laps" => 72,
        "image_path" => "holandia.png",
        "bio" => "Odnowiony tor z bandami i nachylonymi zakrętami. Kibice w pomarańczowym szale tworzą niesamowitą atmosferę."
    ],
    [
        "name" => "Autodromo Nazionale Monza",
        "country" => "Włochy",
        "city" => "Monza",
        "length_km" => 5.793,
        "laps" => 53,
        "image_path" => "wlochy.png",
        "bio" => "Świątynia szybkości. Długie proste i szykany, gdzie liczy się moc silnika i mały opór powietrza."
    ],
    [
        "name" => "Madrid Street Circuit (Madring)",
        "country" => "Hiszpania",
        "city" => "Madryt",
        "length_km" => 5.470,
        "laps" => 55,
        "image_path" => "madryt.png",
        "bio" => "Nowy hybrydowy tor uliczny w Madrycie. Debiutuje w kalendarzu F1 w 2026 roku, zastępując Imolę."
    ],
    [
        "name" => "Baku City Circuit",
        "country" => "Azerbejdżan",
        "city" => "Baku",
        "length_km" => 6.003,
        "laps" => 51,
        "image_path" => "azerbejdzan.png",
        "bio" => "Uliczny tor w Baku. Mieszanka wąskich sekcji i długiej prostej, gdzie prędkości sięgają 350 km/h."
    ],
    [
        "name" => "Marina Bay Street Circuit",
        "country" => "Singapur",
        "city" => "Singapur",
        "length_km" => 5.063,
        "laps" => 61,
        "image_path" => "singapur.png",
        "bio" => "Pierwszy nocny wyścig w F1. Wilgotność i temperatura sprawiają, że to jeden z najbardziej wymagających fizycznie wyścigów."
    ],
    [
        "name" => "Circuit of the Americas",
        "country" => "USA",
        "city" => "Austin",
        "length_km" => 5.513,
        "laps" => 56,
        "image_path" => "usa2.png",
        "bio" => "Nowoczesny tor w Teksasie. Słynie z podjazdu na pierwszy zakręt i sekencji szybkich esów."
    ],
    [
        "name" => "Autódromo Hermanos Rodríguez",
        "country" => "Meksyk",
        "city" => "Meksyk",
        "length_km" => 4.304,
        "laps" => 71,
        "image_path" => "meksyk.png",
        "bio" => "Tor na dużej wysokości, co wpływa na aerodynamikę. Słynie z głośnych i oddanych kibiców."
    ],
    [
        "name" => "Autódromo José Carlos Pace (Interlagos)",
        "country" => "Brazylia",
        "city" => "São Paulo",
        "length_km" => 4.309,
        "laps" => 71,
        "image_path" => "brazylia.png",
        "bio" => "Tor w Interlagos. Kultowe zakręty, zmienna pogoda i niesamowita atmosfera tworzą legendę tego miejsca."
    ],
    [
        "name" => "Las Vegas Strip Circuit",
        "country" => "USA",
        "city" => "Las Vegas",
        "length_km" => 6.201,
        "laps" => 50,
        "image_path" => "usa3.png",
        "bio" => "Tor na Stripie w Las Vegas. Najdłuższa prosta w kalendarzu i nocny wyścig wśród kasyn."
    ],
    [
        "name" => "Lusail International Circuit",
        "country" => "Katar",
        "city" => "Lusail",
        "length_km" => 5.419,
        "laps" => 57,
        "image_path" => "katar.png",
        "bio" => "Nowoczesny tor w Katarze. Szybkie zakręty i wymagające warunki fizyczne dla kierowców."
    ],
    [
        "name" => "Yas Marina Circuit",
        "country" => "Zjednoczone Emiraty Arabskie",
        "city" => "Abu Zabi",
        "length_km" => 5.281,
        "laps" => 58,
        "image_path" => "zea.png",
        "bio" => "Finał sezonu. Nowoczesny tor z tunelem i wyścigiem o zachodzie słońca, kończący się pod pałacem."
    ]
];

foreach ($tracks as $track) {
    // Sprawdź czy tor już istnieje
    $checkStmt = $conn->prepare("SELECT id FROM tracks WHERE name = ?");
    $checkStmt->bind_param("s", $track['name']);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows == 0) {
        // Przygotuj zmienne dla bind_param
        $name = $track['name'];
        $country = $track['country'];
        $city = $track['city'];
        $length_km = (string)$track['length_km']; // Konwersja na string dla DECIMAL
        $laps = $track['laps'];
        $image_path = $track['image_path'];
        $bio = $track['bio'];
        
        $stmt = $conn->prepare("INSERT INTO tracks (name, country, city, length_km, laps, image_path, bio, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("ssssiss", 
            $name,      // string
            $country,   // string
            $city,      // string
            $length_km, // string (decimal)
            $laps,      // integer
            $image_path, // string
            $bio        // string
        );
        
        if($stmt->execute()) {
            echo "Dodano tor: {$track['name']}<br>";
        } else {
            echo "Błąd przy dodawaniu toru {$track['name']}: " . $stmt->error . "<br>";
        }
        $stmt->close();
    }
    $checkStmt->close();
}
echo "<br><strong>Import zakończony!</strong>";
?>