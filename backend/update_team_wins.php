<?php
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Administrator') {
    header("Location: ../index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $wins = $_POST['wins'] ?? 0;
    
    $stmt = $conn->prepare("UPDATE f1_constructor_standings SET wins = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ii", $wins, $id);
    
    if($stmt->execute()) {
        header("Location: ../admin_f1_center.php?tab=teams&success=Zwycięstwa zaktualizowane");
    } else {
        header("Location: ../admin_f1_center.php?tab=teams&error=Błąd aktualizacji");
    }
} else {
    header("Location: ../admin_f1_center.php?tab=teams");
}
exit();
?>