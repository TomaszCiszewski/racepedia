<?php
// Zabezpieczenie przed wielokrotnym dołączeniem
if (!defined('CONFIG_LOADED')) {
    define('CONFIG_LOADED', true);

    // Konfiguracja połączenia z bazą danych dla MAMP
    $host = 'localhost';
    $port = 8889; // Port MySQL w MAMP to 8889
    $user = 'root';
    $pass = 'root'; // Domyślne hasło w MAMP to "root"
    $db = 'racepedia';

    // Połączenie z bazą z użyciem portu
    $conn = new mysqli($host, $user, $pass, $db, $port);

    // Sprawdzenie połączenia
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    // Ustawienie kodowania UTF-8
    $conn->set_charset("utf8mb4");

    // Rozpoczęcie sesji
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Funkcja do bezpiecznego wyświetlania danych
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    // Funkcja sprawdzająca czy użytkownik jest zalogowany
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Funkcja do przekierowania jeśli niezalogowany
    function requireLogin() {
        if (!isLoggedIn()) {
            header('Location: login.php');
            exit();
        }
    }

    // Funkcja pobierająca rolę użytkownika
    function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }

    // Funkcje pomocnicze dla statystyk
    function getUsersCount($conn) {
        $result = $conn->query("SELECT COUNT(*) as count FROM users");
        return $result->fetch_assoc()['count'];
    }

    function getDriversCount($conn) {
        $result = $conn->query("SELECT COUNT(*) as count FROM drivers");
        return $result->fetch_assoc()['count'];
    }

    function getTracksCount($conn) {
        $result = $conn->query("SELECT COUNT(*) as count FROM tracks");
        return $result->fetch_assoc()['count'];
    }

    function getRacesCount($conn) {
        $result = $conn->query("SELECT COUNT(*) as count FROM races");
        return $result->fetch_assoc()['count'];
    }

    // Funkcja wykrywająca urządzenie mobilne
    function isMobile() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $mobileAgents = [
            'Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 
            'Windows Phone', 'Opera Mini', 'IEMobile', 'Mobile'
        ];
        
        foreach ($mobileAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                return true;
            }
        }
        return false;
    }

    // Funkcja do wyboru odpowiedniego widoku
    function getView($baseView) {
        if (isMobile()) {
            // Sprawdź czy istnieje wersja mobilna
            $mobileView = str_replace('.php', '_mobile.php', $baseView);
            if (file_exists($mobileView)) {
                return $mobileView;
            }
        }
        return $baseView;
    }
}
?>