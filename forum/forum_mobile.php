<?php
include "../backend/config.php";
include "backend/forum_functions.php";

// Sprawdź czy to mobile - jeśli nie, przekieruj do normalnego forum
if (!isMobile()) {
    header("Location: index.php");
    exit();
}

// Pobierz kategorie z licznikami
$categories = $conn->query("
    SELECT c.*, 
           COUNT(DISTINCT t.id) as thread_count,
           COUNT(DISTINCT p.id) as post_count
    FROM forum_categories c
    LEFT JOIN forum_threads t ON t.category_id = c.id
    LEFT JOIN forum_posts p ON p.thread_id = t.id
    GROUP BY c.id
    ORDER BY c.display_order ASC
");

// Pobierz ostatnie posty
$latestPosts = $conn->query("
    SELECT p.id, p.content, p.created_at,
           t.id as thread_id, t.title as thread_title,
           u.username, u.avatar
    FROM forum_posts p
    JOIN forum_threads t ON p.thread_id = t.id
    JOIN users u ON p.author_id = u.id
    ORDER BY p.created_at DESC
    LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Forum - Racepedia Mobile</title>
    
    <!-- Bootstrap 5 mobile-first -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="icon" href="../assets/racepedia_favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Nasze style -->
    <?php include "../components/styles.php"; ?>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #0a0a0a;
            color: white;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.4;
            padding-bottom: 70px;
        }
        
        /* Mobilny navbar dolny */
        .mobile-nav-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #111;
            border-top: 2px solid #ff0033;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            z-index: 1000;
        }
        
        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #ccc;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s ease;
            flex: 1;
        }
        
        .mobile-nav-item i {
            font-size: 22px;
            color: #ff0033;
            margin-bottom: 3px;
        }
        
        .mobile-nav-item span {
            color: #ccc;
        }
        
        .mobile-nav-item.active i,
        .mobile-nav-item.active span {
            color: white;
        }
        
        .mobile-nav-item:active {
            transform: scale(0.95);
        }
        
        /* Nagłówek mobilny */
        .mobile-header {
            background: #111;
            border-bottom: 2px solid #ff0033;
            padding: 15px;
            position: sticky;
            top: 80px;
            z-index: 900;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .mobile-header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 20px;
            color: #ff0033;
            margin: 0;
        }
        
        .mobile-header i {
            font-size: 22px;
            color: #ff0033;
        }
        
        /* Kategorie */
        .mobile-container {
            padding: 15px;
        }
        
        .category-card {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            display: block;
            text-decoration: none;
        }
        
        .category-card:active {
            transform: scale(0.98);
            background: #1a1a1a;
        }
        
        .category-icon {
            font-size: 28px;
            color: #ff0033;
            margin-right: 15px;
        }
        
        .category-name {
            font-size: 18px;
            font-weight: 600;
            color: white;
            margin-bottom: 5px;
        }
        
        .category-description {
            font-size: 13px;
            color: #ccc;
            margin-bottom: 10px;
        }
        
        .category-stats {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #999;
        }
        
        .category-stats i {
            color: #ff0033;
            margin-right: 5px;
        }
        
        /* Wyszukiwarka */
        .mobile-search {
            background: #1a1a1a;
            border: 1px solid #ff0033;
            border-radius: 30px;
            padding: 10px 20px;
            margin: 20px 0;
            display: flex;
            align-items: center;
        }
        
        .mobile-search i {
            color: #ff0033;
            margin-right: 10px;
            font-size: 18px;
        }
        
        .mobile-search input {
            background: transparent;
            border: none;
            color: white;
            font-size: 14px;
            width: 100%;
            outline: none;
        }
        
        .mobile-search input::placeholder {
            color: #666;
        }
        
        /* Ostatnie posty */
        .section-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            color: #ff0033;
            margin: 25px 0 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .post-item {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
        }
        
        .post-item:active {
            background: #1a1a1a;
        }
        
        .post-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        
        .post-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #ff0033;
            object-fit: cover;
        }
        
        .post-author {
            color: #ff0033;
            font-weight: 600;
            font-size: 13px;
        }
        
        .post-time {
            color: #999;
            font-size: 11px;
            margin-left: auto;
        }
        
        .post-title {
            color: white;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .post-excerpt {
            color: #ccc;
            font-size: 13px;
            line-height: 1.4;
        }
        
        /* Przycisk nowego wątku */
        .new-thread-btn {
            background: #ff0033;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            margin: 20px 0;
            width: 100%;
        }
        
        .new-thread-btn:active {
            transform: scale(0.98);
            background: #ff1a47;
        }
        
        /* Link do pełnej wersji */
        .full-site-link {
            text-align: center;
            margin: 30px 0;
        }
        
        .full-site-link a {
            color: #ff0033;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        /* Breadcrumb mobilny */
        .mobile-breadcrumb {
            background: #1a1a1a;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-left: 4px solid #ff0033;
            font-size: 13px;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        
        .mobile-breadcrumb a {
            color: #ff0033;
            text-decoration: none;
        }
        
        .mobile-breadcrumb span {
            color: #ccc;
        }
    </style>
</head>
<body>

<?php include "../components/navbar.php"; ?>

<!-- Mobilny nagłówek -->
<div class="mobile-header">
    <h1><i class="fas fa-comments me-2"></i>Forum</h1>
    <?php if (isForumUserLoggedIn()): ?>
        <a href="create_thread.php" style="color: #ff0033;">
            <i class="fas fa-pen"></i>
        </a>
    <?php endif; ?>
</div>

<div class="mobile-container">
    
    <!-- Wyszukiwarka -->
    <div class="mobile-search">
        <i class="fas fa-search"></i>
        <input type="text" 
               placeholder="Szukaj w forum..." 
               id="mobileSearch"
               onkeypress="if(event.key==='Enter') window.location='search.php?q='+encodeURIComponent(this.value)">
    </div>
    
    <!-- Kategorie -->
    <div class="section-title">
        <i class="fas fa-folder"></i> Kategorie
    </div>
    
    <?php while ($cat = $categories->fetch_assoc()): ?>
    <a href="category.php?id=<?= $cat['id'] ?>" class="category-card">
        <div class="d-flex">
            <div class="category-icon">
                <i class="fas <?= $cat['icon'] ?? 'fa-comments' ?>"></i>
            </div>
            <div style="flex: 1;">
                <div class="category-name"><?= htmlspecialchars($cat['name']) ?></div>
                <div class="category-description"><?= htmlspecialchars($cat['description']) ?></div>
                <div class="category-stats">
                    <span><i class="fas fa-file-alt"></i> <?= $cat['thread_count'] ?> wątków</span>
                    <span><i class="fas fa-reply"></i> <?= $cat['post_count'] ?> postów</span>
                </div>
            </div>
        </div>
    </a>
    <?php endwhile; ?>
    
    <!-- Ostatnie posty -->
    <div class="section-title">
        <i class="fas fa-history"></i> Ostatnie posty
    </div>
    
    <?php while ($post = $latestPosts->fetch_assoc()): ?>
    <a href="thread.php?id=<?= $post['thread_id'] ?>#post-<?= $post['id'] ?>" class="post-item">
        <div class="post-header">
            <img src="../assets/avatars/<?= htmlspecialchars($post['avatar'] ?? 'default.jpg') ?>" 
                 alt="" 
                 class="post-avatar">
            <span class="post-author"><?= htmlspecialchars($post['username']) ?></span>
            <span class="post-time"><?= date('d.m H:i', strtotime($post['created_at'])) ?></span>
        </div>
        <div class="post-title"><?= htmlspecialchars($post['thread_title']) ?></div>
        <div class="post-excerpt">
            <?= htmlspecialchars(substr(strip_tags($post['content']), 0, 80)) ?>...
        </div>
    </a>
    <?php endwhile; ?>
    
    <!-- Przycisk nowego wątku (jeśli zalogowany) -->
    <?php if (isForumUserLoggedIn()): ?>
    <a href="create_thread.php" class="new-thread-btn">
        <i class="fas fa-plus"></i> Załóż nowy wątek
    </a>
    <?php endif; ?>
    
    <!-- Link do pełnej wersji -->
    <div class="full-site-link">
        <a href="index.php?fullsite=1">
            <i class="fas fa-desktop"></i> Pełna wersja
        </a>
    </div>
</div>

<!-- Dolny pasek nawigacyjny -->
<div class="mobile-nav-bottom">
    <a href="../index.php" class="mobile-nav-item">
        <i class="fas fa-home"></i>
        <span>Strona gł.</span>
    </a>
    <a href="../centrum_f1_2026.php" class="mobile-nav-item">
        <i class="fas fa-flag-checkered"></i>
        <span>F1 2026</span>
    </a>
    <a href="../baza.php" class="mobile-nav-item">
        <i class="fas fa-database"></i>
        <span>Baza</span>
    </a>
    <a href="index.php" class="mobile-nav-item active">
        <i class="fas fa-comments"></i>
        <span>Forum</span>
    </a>
    <?php if (isForumUserLoggedIn()): ?>
    <a href="../konto.php" class="mobile-nav-item">
        <i class="fas fa-user"></i>
        <span>Konto</span>
    </a>
    <?php else: ?>
    <a href="../login.php" class="mobile-nav-item">
        <i class="fas fa-sign-in-alt"></i>
        <span>Login</span>
    </a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>