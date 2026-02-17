<?php
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Administrator') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

if($id > 0) {
    $stmt = $conn->prepare("DELETE FROM f1_driver_standings WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        header("Location: ../admin_f1_center.php?tab=drivers&success=Pozycja usunięta");
    } else {
        header("Location: ../admin_f1_center.php?tab=drivers&error=Błąd usuwania");
    }
} else {
    header("Location: ../admin_f1_center.php?tab=drivers&error=Nieprawidłowe ID");
}
exit();
?>