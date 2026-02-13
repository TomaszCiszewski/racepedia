<?php include "backend/config.php"; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login - Racepedia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    background:#0a0a0a;
    color:white;
}
.card{
    background:#111;
    border:1px solid #ff003350;
}
</style>
</head>
<body>

<?php include "components/navbar.php"; ?>

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
  <div class="card p-4" style="width:400px;">
    <h3 class="text-center mb-4" style="color:#ff0033;">Logowanie</h3>

    <form method="POST" action="backend/login_process.php">
      <input type="text" name="username" class="form-control mb-3" placeholder="Login" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Hasło" required>
      <button class="btn btn-danger w-100">Zaloguj</button>
    </form>

  </div>
</div>

</body>
</html>
