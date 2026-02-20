<?php
include "backend/config.php";

// Sprawdź czy to mobile i przekieruj do odpowiedniego widoku
if (isMobile()) {
    // Jeśli plik mobilny istnieje, załaduj go
    if (file_exists('index_mobile.php')) {
        include 'index_mobile.php';
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Racepedia</title>
<link rel="icon" href="assets/racepedia_favicon.png" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
<?php include "components/styles.php"; ?>

<style>
:root{
  --hero-image: url('assets/hero.jpg');
}

body{
  margin:0;
  background:#0a0a0a;
  color:white;
  font-family:'Montserrat',sans-serif;
  scroll-behavior:smooth;
}

.hero{
  height:100vh;
  position:relative;
  display:flex;
  align-items:center;
  justify-content:center;
  text-align:center;
  overflow:hidden;
}

.hero::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    linear-gradient(rgba(0,0,0,0.75),rgba(0,0,0,0.75)),
    var(--hero-image) center/cover no-repeat;
  z-index:-1;
}

.scroll-btn{
  position:absolute;
  bottom:30px;
  left:50%;
  transform:translateX(-50%);
  font-size:2rem;
  cursor:pointer;
  animation:bounce 2s infinite;
}

@keyframes bounce{
  0%,100%{transform:translate(-50%,0);}
  50%{transform:translate(-50%,10px);}
}

.section{
  padding:100px 0;
}

.card-dark{
  background:#111;
  border:1px solid #ff003350;
  border-radius:15px;
  transition:0.4s;
}

.card-dark:hover{
  transform:translateY(-10px);
  box-shadow:0 0 20px #ff0033;
}

footer{
  background:#111;
  padding:40px 0;
  border-top:1px solid #ff003350;
}
</style>
</head>

<body>

<?php include "components/navbar.php"; ?>

<!-- HERO -->
<section class="hero">
  <div>
    <h1 style="font-family:Orbitron;font-size:4rem;color:#ff0033;">
      Racepedia
    </h1>
    <p class="lead">Motorsport Knowledge Base & Community</p>
  </div>

  <div class="scroll-btn" onclick="document.getElementById('info').scrollIntoView()">
    ↓
  </div>
</section>

<!-- INFO SECTION -->
<section id="info" class="section container text-center">
  <div class="row">

    <div class="col-md-4 mb-4" data-aos="fade-up">
      <div class="card-dark p-4">
        <h3 style="color:#ff0033;">F1</h3>
        <p>Najwyższa klasa wyścigowa jednomiejscowych bolidów.</p>
      </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
      <div class="card-dark p-4">
        <h3 style="color:#ff0033;">WRC</h3>
        <p>Rajdowe mistrzostwa świata – prędkość i precyzja.</p>
      </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
      <div class="card-dark p-4">
        <h3 style="color:#ff0033;">WEC</h3>
        <p>Długodystansowe wyścigi, w tym 24h Le Mans. Aktualnym mistrzem jest Robert Kubica</p>
      </div>
    </div>

  </div>
</section>

<!-- FOOTER -->
<footer id="footer">
  <div class="container text-center">

    <p>
      📸 <a href="#" class="text-danger">Instagram</a> |
      👍 <a href="#" class="text-danger">Facebook</a>
    </p>

    <p>📩 Kontakt: kontakt@racepedia.pl</p>

    <form class="row justify-content-center">
      <div class="col-md-4">
        <input type="email" class="form-control" placeholder="Twój email">
      </div>
      <div class="col-md-2">
        <button class="btn btn-danger w-100">Wyślij</button>
      </div>
    </form>

    <p class="mt-4 small text-muted">© 2026 Racepedia</p>

  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({duration:1000});
</script>

</body>
</html>