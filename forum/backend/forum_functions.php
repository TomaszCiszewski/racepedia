<?php
// Sprawdź czy sesja jest już uruchomiona
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Funkcja sprawdzająca czy użytkownik jest zalogowany
function isForumUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Funkcja pobierająca ID zalogowanego użytkownika
function getForumUserId() {
    return $_SESSION['user_id'] ?? 0;
}

// Funkcja pobierająca nazwę użytkownika
function getForumUsername() {
    return $_SESSION['username'] ?? 'Gość';
}

// Funkcja sprawdzająca czy użytkownik jest administratorem
function isForumAdmin() {
    return ($_SESSION['user_role'] ?? '') === 'Administrator';
}
?>