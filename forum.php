<?php include "backend/config.php"; ?>
<?php if(!isset($_SESSION['user'])) header("Location: login.php"); ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forum - Racepedia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
</head>
<body style="background:#0a0a0a;color:white;">

<?php include "components/navbar.php"; ?>

<div class="container mt-5 pt-5">
<h2>Forum</h2>
<p>Tu będzie system postów.</p>
</div>

</body>
</html>
