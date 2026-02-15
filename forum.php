<?php include "backend/config.php"; ?>
<?php 
// Poprawione sprawdzanie logowania
if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forum - Racepedia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
<?php include "components/styles.php"; ?>
</head>
<body style="background:#0a0a0a;color:white;">

<?php include "components/navbar.php"; ?>

<div class="container mt-5 pt-5">
    <h2 class="mb-4" style="font-family:'Orbitron'; color:#ff0033;">Forum dyskusyjne</h2>
    <div class="alert alert-info" style="background:#111; border:1px solid #ff0033; color:white;">
        <i class="fas fa-info-circle me-2"></i>
        System forum w budowie. Wkrótce pojawią się nowe funkcje!
    </div>
</div>

</body>
</html>