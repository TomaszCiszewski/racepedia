<?php include "backend/config.php"; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login - Racepedia</title>
    <link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
  background:#0a0a0a;
  color:white;
  font-family:'Montserrat';
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
}

.card{
  background:#111;
  border:1px solid #ff003350;
  width:400px;
  padding:30px;
  animation:fadeIn 1s ease;
}

@keyframes fadeIn{
  from{opacity:0; transform:translateY(30px);}
  to{opacity:1; transform:translateY(0);}
}

h2{
  font-family:'Orbitron';
  color:#ff0033;
}
</style>
</head>
<body>

<div class="card">
<h2 class="text-center mb-4">Logowanie</h2>

<form method="POST" action="backend/login_process.php">
  <input type="text" name="username" class="form-control mb-3" placeholder="Login" required>
  <input type="password" name="password" class="form-control mb-3" placeholder="Hasło" required>
  <button class="btn btn-danger w-100">Zaloguj</button>
</form>

<div class="text-center mt-3">
  <a href="register.php" class="text-danger">Nie masz konta? Zarejestruj się</a>
</div>

</div>

<div class="modal fade" id="errorModal">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white">
      <div class="modal-body">
        <?= isset($_GET['error']) ? e($_GET['error']) : '' ?>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php if(isset($_GET['error'])): ?>
<script>
var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
myModal.show();
</script>
<?php endif; ?>

</body>
</html>
