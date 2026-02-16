<?php
include "config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Administrator') {
    header("Location: ../index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_id = $_POST['team_id'] ?? 0;
    $position = $_POST['position'] ?? 0;
    $points = $_POST['points'] ?? 0;
    $wins = $_POST['wins'] ?? 0;
    $season = $_POST['season'] ?? 2026;
    
    // Sprawdź czy już istnieje wpis dla tego zespołu w tym sezonie
    $check = $conn->prepare("SELECT id FROM f1_constructor_standings WHERE team_id = ? AND season = ?");
    $check->bind_param("ii", $team_id, $season);
    $check->execute();
    $result = $check->get_result();
    
    if($result->num_rows > 0) {
        // Aktualizuj istniejący
        $row = $result->fetch_assoc();
        $stmt = $conn->prepare("UPDATE f1_constructor_standings SET position = ?, points = ?, wins = ? WHERE id = ?");
        $stmt->bind_param("idii", $position, $points, $wins, $row['id']);
    } else {
        // Dodaj nowy
        $stmt = $conn->prepare("INSERT INTO f1_constructor_standings (team_id, season, position, points, wins) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidi", $team_id, $season, $position, $points, $wins);
    }
    
    if($stmt->execute()) {
        header("Location: ../admin_f1_center.php?tab=teams&success=Pozycja dodana/zaktualizowana");
    } else {
        header("Location: ../admin_f1_center.php?tab=teams&error=Błąd zapisu: " . $stmt->error);
    }
} else {
    header("Location: ../admin_f1_center.php?tab=teams");
}
exit();
?>