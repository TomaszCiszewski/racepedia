<?php
include "config.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    header("Location: ../login.php?error=Uzupełnij wszystkie pola");
    exit();
}

// Poprawione zapytanie - używamy password_hash zamiast password
$stmt = $conn->prepare("SELECT id, username, email, password_hash, role FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password_hash'])) {
    // Zapisujemy dane w sesji
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_email'] = $user['email'];
    
    // Przekierowanie do strony głównej
    header("Location: ../index.php");
    exit();
} else {
    // Błędne dane logowania
    header("Location: ../login.php?error=Nieprawidłowy login lub hasło");
    exit();
}

$stmt->close();
$conn->close();
?>