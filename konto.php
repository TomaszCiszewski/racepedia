<?php
include "backend/config.php";
if(!isset($_SESSION['user'])) header("Location: login.php");

$stmt = $conn->prepare("SELECT username,email,role FROM users WHERE id=?");
$stmt->bind_param("i",$_SESSION['user']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Konto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php include "components/styles.php"; ?>
</head>
<body>

<?php include "components/navbar.php"; ?>

<div class="container mt-5 pt-5">
<h2>Panel Konta</h2>

<p><strong>Login:</strong> <?= e($user['username']) ?></p>
<p><strong>Email:</strong> <?= e($user['email']) ?></p>
<p><strong>Rola:</strong> <?= e($user['role']) ?></p>

<h4 class="mt-4">Zmiana hasła</h4>
<form method="POST" action="backend/update_account.php">
  <input type="password" name="newpass" class="form-control mb-2" placeholder="Nowe hasło">
  <button class="btn btn-danger">Zmień hasło</button>
</form>

</div>
</body>
</html>
