<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
/* ===== VARIABLES ===== */
:root {
    --bg-primary: #0a0a0a;
    --bg-secondary: #111111;
    --bg-card: #111111;
    --accent-red: #ff0033;
    --accent-red-hover: #ff1a47;
    --accent-red-transparent: rgba(255, 0, 51, 0.2);
    --text-primary: #ffffff;
    --text-secondary: #cccccc;
    --border-color: rgba(255, 0, 51, 0.3);
    --glass-bg: rgba(17, 17, 17, 0.8);
    --glass-border: rgba(255, 0, 51, 0.2);
}

/* ===== GLOBAL STYLES ===== */
body {
    background: var(--bg-primary);
    color: var(--text-primary);
    font-family: 'Montserrat', sans-serif;
    min-height: 100vh;
    padding-top: 76px; /* dla fixed navbar */
}

h1, h2, h3, h4, h5, h6, .logo, .brand-text {
    font-family: 'Orbitron', sans-serif;
    letter-spacing: 1px;
}

.text-accent {
    color: var(--accent-red) !important;
}

/* ===== NAVBAR STYLES ===== */
.navbar {
    background: rgba(10, 10, 10, 0.95) !important;
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 0;
}

.navbar-brand {
    font-family: 'Orbitron', sans-serif;
    font-weight: 800;
    font-size: 1.8rem;
    color: var(--accent-red) !important;
    transition: all 0.3s ease;
}

.navbar-brand:hover {
    transform: scale(1.05);
    text-shadow: 0 0 15px var(--accent-red);
}

.brand-text {
    background: linear-gradient(45deg, #fff 30%, var(--accent-red) 70%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.btn-outline-light {
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.btn-outline-light:hover {
    background: var(--accent-red);
    border-color: var(--accent-red);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 0, 51, 0.3);
}

.btn-danger {
    background: var(--accent-red);
    border: none;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: var(--accent-red-hover);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 0, 51, 0.4);
}

/* ===== CARD STYLES ===== */
.card, .driver-card, .track-card, .race-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.card:hover, .driver-card:hover, .track-card:hover, .race-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(255, 0, 51, 0.2);
    border-color: var(--accent-red);
}

/* ===== KNOWLEDGE BASE STYLES ===== */
.knowledge-hero {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../assets/hero-bg.jpg');
    background-size: cover;
    background-position: center;
    padding: 80px 0;
    margin-bottom: 40px;
    text-align: center;
    border-bottom: 2px solid var(--accent-red);
}

.knowledge-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 4rem;
    font-weight: 800;
    color: var(--accent-red);
    text-shadow: 0 0 20px rgba(255, 0, 51, 0.5);
    margin-bottom: 20px;
}

.knowledge-subtitle {
    font-size: 1.2rem;
    color: var(--text-secondary);
}

/* ===== KAFELKI STYLE (INSPIROWANE FIFA CARDS) ===== */
.driver-card {
    position: relative;
    background: linear-gradient(145deg, var(--bg-secondary) 0%, #1a1a1a 100%);
}

.driver-image-wrapper {
    position: relative;
    height: 280px;
    overflow: hidden;
}

.driver-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.driver-card:hover .driver-image {
    transform: scale(1.1);
}

.driver-number {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--accent-red);
    color: white;
    font-family: 'Orbitron', sans-serif;
    font-weight: 800;
    font-size: 2rem;
    padding: 5px 15px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(255, 0, 51, 0.5);
    border: 2px solid white;
}

.driver-info {
    padding: 20px;
    position: relative;
    background: linear-gradient(to top, var(--bg-secondary), transparent);
}

.driver-name {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--accent-red);
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 10px;
}

.driver-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.driver-team, .driver-country {
    background: rgba(255, 0, 51, 0.1);
    padding: 5px 10px;
    border-radius: 5px;
    border-left: 3px solid var(--accent-red);
}

.driver-description {
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 20px;
    color: var(--text-secondary);
}

/* ===== TORÓW KAFELKI ===== */
.track-card {
    position: relative;
}

.track-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.track-card:hover .track-image {
    transform: scale(1.1);
}

.track-info {
    padding: 20px;
}

.track-name {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--accent-red);
}

.track-details {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 15px;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.track-details span {
    background: rgba(255, 255, 255, 0.05);
    padding: 5px;
    border-radius: 5px;
    text-align: center;
    border-bottom: 2px solid var(--accent-red);
}

.track-description {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.6;
}

/* ===== WYŚCIGÓW KAFELKI ===== */
.race-card {
    position: relative;
}

.race-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.race-overlay {
    position: absolute;
    top: 10px;
    left: 10px;
    right: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.race-date {
    background: var(--accent-red);
    color: white;
    padding: 5px 15px;
    border-radius: 5px;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 0 15px rgba(255, 0, 51, 0.3);
}

.race-info {
    padding: 20px;
}

.race-name {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--accent-red);
}

.race-circuit, .race-winner {
    font-size: 0.95rem;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.race-circuit i, .race-winner i {
    color: var(--accent-red);
    width: 20px;
}

.race-description {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid var(--border-color);
}

/* ===== ZAKŁADKI ===== */
.knowledge-tabs {
    border-bottom: 2px solid var(--border-color);
    margin-bottom: 30px;
}

.knowledge-tabs .nav-link {
    color: var(--text-secondary);
    font-family: 'Orbitron', sans-serif;
    font-weight: 600;
    border: none;
    padding: 15px 30px;
    transition: all 0.3s ease;
}

.knowledge-tabs .nav-link:hover {
    color: var(--accent-red);
    background: rgba(255, 0, 51, 0.1);
}

.knowledge-tabs .nav-link.active {
    color: var(--accent-red);
    background: transparent;
    border-bottom: 3px solid var(--accent-red);
}

/* ===== MODALE ===== */
.modal-content {
    background: var(--bg-secondary);
    border: 1px solid var(--accent-red);
    border-radius: 15px;
}

.modal-header {
    border-bottom: 2px solid var(--border-color);
}

.modal-title {
    font-family: 'Orbitron', sans-serif;
    color: var(--accent-red);
}

.btn-close-white {
    filter: invert(1);
}

/* ===== ANIMACJE ===== */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.driver-card, .track-card, .race-card {
    animation: fadeIn 0.6s ease-out forwards;
}

/* ===== GLASSMORPHISM ===== */
.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .knowledge-title {
        font-size: 2.5rem;
    }
    
    .driver-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .track-details {
        grid-template-columns: 1fr;
    }
}

/* ===== SCROLLBAR ===== */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: var(--bg-primary);
}

::-webkit-scrollbar-thumb {
    background: var(--accent-red);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--accent-red-hover);
}

/* ===== TORY - NOWY HORYZONTALNY UKŁAD ===== */
.track-card-horizontal {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    width: 100%;
}

.track-card-horizontal:hover {
    transform: translateX(10px);
    box-shadow: 0 10px 30px rgba(255, 0, 51, 0.2);
    border-color: var(--accent-red);
}

.track-image-horizontal {
    transition: transform 0.5s ease;
}

.track-card-horizontal:hover .track-image-horizontal {
    transform: scale(1.05);
}

.track-name-horizontal {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--accent-red);
    margin-bottom: 15px;
}

.track-description-horizontal {
    font-size: 1rem;
    line-height: 1.6;
    color: var(--text-secondary);
}

@media (max-width: 768px) {
    .track-name-horizontal {
        font-size: 1.4rem;
    }
}

/* ===== INFO O ZWYCIĘZCY ===== */
.winner-info {
    border-radius: 8px;
    font-size: 1rem;
}

.winner-info i {
    font-size: 1.1rem;
}

.badge.bg-dark {
    background: #222 !important;
    color: #fff;
    padding: 8px 12px;
    font-weight: 500;
}

.badge.bg-danger {
    padding: 8px 12px;
    font-weight: 600;
}

/* Fallback gdy Font Awesome nie zadziała */
.fa-fallback {
    display: none;
}

/* Pokaż fallback tylko jeśli Font Awesome nie działa */
@font-face {
    font-family: 'Font Awesome 6 Free';
    src: local('Arial');
    unicode-range: U+00-FF;
}

.fas:not(:defined) {
    display: none;
}

.fas:not(:defined) + .forum-fallback {
    display: inline !important;
}

</style>