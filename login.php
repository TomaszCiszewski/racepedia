<?php
include "backend/config.php";
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Logowanie - Racepedia</title>
<link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
body{
  background:#0a0a0a;
  color:white;
  font-family:'Montserrat', sans-serif;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.login-card{
  background:#111;
  border:1px solid #ff003350;
  border-radius: 15px;
  width:100%;
  max-width:400px;
  padding:30px;
  animation:fadeIn 0.6s ease;
}

@keyframes fadeIn{
  from{opacity:0; transform:translateY(30px);}
  to{opacity:1; transform:translateY(0);}
}

h2{
  font-family:'Orbitron';
  color:#ff0033;
}

.form-control {
  background: #1a1a1a;
  border: 1px solid #333;
  color: white;
  padding: 12px;
}

.form-control:focus {
  background: #222;
  border-color: #ff0033;
  box-shadow: 0 0 0 0.25rem rgba(255, 0, 51, 0.25);
  color: white;
}

.btn-danger {
  background: #ff0033;
  border: none;
  padding: 12px;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-danger:hover {
  background: #ff1a47;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(255, 0, 51, 0.3);
}

.footer {
  background: #111;
  border-top: 1px solid #ff003350;
  padding: 30px 0;
  margin-top: auto;
}
</style>
</head>
<body>

<?php include "components/navbar.php"; ?>

<main>
  <div class="login-card">
    <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt me-2"></i>Logowanie</h2>
    
    <form method="POST" action="backend/login_process.php">
      <div class="mb-3">
        <label class="form-label">Login</label>
        <input type="text" name="username" class="form-control" placeholder="Wprowadź login" required>
      </div>
      
      <div class="mb-4">
        <label class="form-label">Hasło</label>
        <input type="password" name="password" class="form-control" placeholder="Wprowadź hasło" required>
      </div>
      
      <button type="submit" class="btn btn-danger w-100 mb-3">
        <i class="fas fa-sign-in-alt me-2"></i>Zaloguj się
      </button>
    </form>
    
    <div class="text-center">
      <p class="text-secondary">Nie masz konta?</p>
      <a href="register.php" class="btn btn-outline-danger">
        <i class="fas fa-user-plus me-2"></i>Zarejestruj się
      </a>
    </div>
  </div>
</main>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h5 style="font-family:'Orbitron'; color:#ff0033;">Racepedia</h5>
        <p class="text-secondary">Twoja baza wiedzy o motorsporcie</p>
      </div>
      <div class="col-md-6 text-end">
        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-2x"></i></a>
        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
        <a href="#" class="text-white"><i class="fas fa-envelope fa-2x"></i></a>
      </div>
    </div>
  </div>
</footer>

<!-- Modal błędu -->
<div class="modal fade" id="errorModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-danger">
        <h5 class="modal-title text-danger">
          <i class="fas fa-exclamation-triangle me-2"></i>Błąd logowania
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?= isset($_GET['error']) ? e($_GET['error']) : '' ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php if(isset($_GET['error'])): ?>
<script>
  var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
  errorModal.show();
</script>
<?php endif; ?>

</body>
</html>