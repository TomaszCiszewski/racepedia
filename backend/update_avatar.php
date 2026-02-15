<?php
include "config.php";

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Nie zalogowany']);
    exit();
}

// Obsługa przesłanego pliku
if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = $_FILES['avatar']['type'];
    
    if(in_array($fileType, $allowed)) {
        $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        $destination = '../assets/avatars/' . $filename;
        
        if(move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
            // Aktualizuj w bazie
            $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $stmt->bind_param("si", $filename, $_SESSION['user_id']);
            
            if($stmt->execute()) {
                $_SESSION['user_avatar'] = $filename;
                echo json_encode(['success' => true, 'avatar' => $filename]);
                exit();
            }
        }
    }
}

// Obsługa wyboru avatara z listy
if(isset($_POST['avatar'])) {
    $avatar = $_POST['avatar'];
    // Walidacja - tylko dozwolone pliki
    if(preg_match('/^avatar[1-5]\.png$/', $avatar)) {
        $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $stmt->bind_param("si", $avatar, $_SESSION['user_id']);
        
        if($stmt->execute()) {
            $_SESSION['user_avatar'] = $avatar;
            echo json_encode(['success' => true, 'avatar' => $avatar]);
            exit();
        }
    }
}

echo json_encode(['success' => false, 'error' => 'Nie udało się zaktualizować avatara']);
?>