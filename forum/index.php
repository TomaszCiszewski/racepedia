<?php
include "../backend/config.php";
include "backend/forum_functions.php";

if (isMobile() && !isset($_GET['fullsite'])) {
    if (file_exists('forum_mobile.php')) {
        include 'forum_mobile.php';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - Racepedia</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Nasze style -->
    <?php include "../components/styles.php"; ?>
    
    <style>
        .forum-category {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .forum-category:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 0, 51, 0.3);
        }
        
        .category-icon {
            font-size: 40px;
            color: #ff0033;
            width: 60px;
            text-align: center;
        }
        
        .category-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 20px;
            color: #ff0033;
            margin-bottom: 5px;
        }
        
        .category-description {
            color: #ccc;
            font-size: 14px;
            margin: 0;
        }
        
        .category-stats {
            text-align: right;
            color: #ccc;
            font-size: 14px;
        }
        
        .category-stats i {
            color: #ff0033;
            margin-right: 5px;
        }
        
        @media (max-width: 768px) {
            .forum-category .row {
                flex-direction: column;
                text-align: center;
            }
            
            .category-icon {
                margin-bottom: 10px;
            }
            
            .category-stats {
                text-align: center;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<?php include "../components/navbar.php"; ?>

<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-12">
            <h2 style="font-family: 'Orbitron'; color: #ff0033; margin-bottom: 30px;">
                <i class="fas fa-comments me-2"></i>Forum dyskusyjne
            </h2>
        </div>
    </div>
    
    <div class="row">
        <?php
        // Pobierz wszystkie kategorie z bazy
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
        
        while ($cat = $categories->fetch_assoc()):
        ?>
        <div class="col-12">
            <a href="category.php?id=<?= $cat['id'] ?>" style="text-decoration: none;">
                <div class="forum-category">
                    <div class="row align-items-center">
                        <div class="col-md-1">
                            <div class="category-icon">
                                <i class="fas <?= $cat['icon'] ?? 'fa-comments' ?>"></i>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="category-title"><?= htmlspecialchars($cat['name']) ?></div>
                            <div class="category-description"><?= htmlspecialchars($cat['description']) ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="category-stats">
                                <i class="fas fa-file-alt"></i> Wątków: <?= $cat['thread_count'] ?> |
                                <i class="fas fa-reply"></i> Postów: <?= $cat['post_count'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>