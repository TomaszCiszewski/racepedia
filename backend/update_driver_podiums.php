<?php
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Administrator') {
    header("Location: ../index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $podiums = $_POST['podiums'] ?? 0;
    
    $stmt = $conn->prepare("UPDATE f1_driver_standings SET podiums = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ii", $podiums, $id);
    
    if($stmt->execute()) {
        header("Location: ../admin_f1_center.php?tab=drivers&success=Podia zaktualizowane");
    } else {
        header("Location: ../admin_f1_center.php?tab=drivers&error=Błąd aktualizacji");
    }
} else {
    header("Location: ../admin_f1_center.php?tab=drivers");
}
exit();
?>