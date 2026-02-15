<?php
include "config.php";

$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if(!$email || !$username || !$password){
 header("Location: ../register.php?error=Uzupełnij wszystkie pola");
 exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

// Poprawiona nazwa kolumny z 'password' na 'password_hash'
$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hash);

if($stmt->execute()){
 header("Location: ../login.php?success=Rejestracja zakończona pomyślnie");
} else {
 // Sprawdź konkretny błąd
 if($conn->errno == 1062) { // Duplicate entry
   header("Location: ../register.php?error=Użytkownik lub email już istnieje");
 } else {
   header("Location: ../register.php?error=Błąd rejestracji: " . $conn->error);
 }
}

$stmt->close();
$conn->close();
?>