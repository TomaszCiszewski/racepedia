<?php include "backend/config.php"; ?>
<?php if(!isset($_SESSION['user'])) header("Location: login.php"); ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Baza wiedzy - Racepedia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#0a0a0a;color:white;">

<?php include "components/navbar.php"; ?>

<div class="container mt-5 pt-5">
<h2>Baza wiedzy</h2>
<p>Tu będzie encyklopedia kierowców i zespołów.</p>
</div>

</body>
</html>
