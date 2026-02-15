<?php
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Administrator') {
    header("Location: ../index.php");
    exit();
}

$userId = $_POST['user_id'] ?? 0;
$newRole = $_POST['role'] ?? '';

$allowedRoles = ['Noob', 'Niedzielny Kierowca', 'Dobry Kierowca', 'Administrator'];

if($userId && in_array($newRole, $allowedRoles)) {
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $userId);
    
    if($stmt->execute()) {
        header("Location: ../admin.php?tab=users&success=Rola zaktualizowana");
    } else {
        header("Location: ../admin.php?tab=users&error=Błąd aktualizacji");
    }
} else {
    header("Location: ../admin.php?tab=users&error=Nieprawidłowe dane");
}
exit();
?>