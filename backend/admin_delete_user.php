<?php
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Administrator') {
    header("Location: ../index.php");
    exit();
}

$userId = $_GET['id'] ?? 0;

if($userId > 0 && $userId != $_SESSION['user_id']) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    
    if($stmt->execute()) {
        header("Location: ../admin.php?tab=users&success=Użytkownik usunięty");
    } else {
        header("Location: ../admin.php?tab=users&error=Błąd usuwania");
    }
} else {
    header("Location: ../admin.php?tab=users&error=Nie można usunąć siebie");
}
exit();
?>