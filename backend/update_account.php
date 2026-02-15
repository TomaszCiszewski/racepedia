<?php
include "config.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$newpass = $_POST['newpass'] ?? '';
$confirmpass = $_POST['confirmpass'] ?? '';

if(empty($newpass) || empty($confirmpass)) {
    header("Location: ../konto.php?error=Wypełnij wszystkie pola");
    exit();
}

if($newpass !== $confirmpass) {
    header("Location: ../konto.php?error=Hasła nie są identyczne");
    exit();
}

// Hashowanie nowego hasła
$hash = password_hash($newpass, PASSWORD_DEFAULT);

// Aktualizacja w bazie
$stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
$stmt->bind_param("si", $hash, $_SESSION['user_id']);

if($stmt->execute()) {
    header("Location: ../konto.php?success=Hasło zostało zmienione");
} else {
    header("Location: ../konto.php?error=Błąd podczas zmiany hasła");
}

$stmt->close();
$conn->close();
?>