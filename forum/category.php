<?php
header('Content-Type: text/html; charset=utf-8');
include "../backend/config.php";
include "backend/forum_functions.php";

$category_id = $_GET['id'] ?? 0;

// Pobierz informacje o kategorii
$stmt = $conn->prepare("SELECT * FROM forum_categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    header("Location: index.php");
    exit();
}

// Pobierz wątki dla tej kategorii
$threads = $conn->prepare("
    SELECT t.*, u.username, u.avatar,
           (SELECT COUNT(*) FROM forum_posts WHERE thread_id = t.id) as post_count,
           (SELECT created_at FROM forum_posts WHERE thread_id = t.id ORDER BY created_at DESC LIMIT 1) as last_post_time
    FROM forum_threads t
    JOIN users u ON t.author_id = u.id
    WHERE t.category_id = ?
    ORDER BY t.is_pinned DESC, t.created_at DESC
");
$threads->bind_param("i", $category_id);
$threads->execute();
$threads_result = $threads->get_result();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category['name']) ?> - Forum Racepedia</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Nasze style -->
    <?php include "../components/styles.php"; ?>
    
    <style>
        .breadcrumb-custom {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 30px;
        }
        
        .btn-new-thread {
            background: #ff0033;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
        }
        
        .btn-new-thread:hover {
            background: #ff1a47;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 0, 51, 0.3);
            color: white;
        }
        
        /* Style dla wątków */
        .thread-header {
            margin-bottom: 10px;
            padding: 0 15px;
        }
        
        .thread-row {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .thread-row:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(255, 0, 51, 0.2);
            background: #1a1a1a;
        }
        
        .pinned-thread {
            border-left: 5px solid gold;
            background: #1a1a1a;
        }
        
        .thread-title {
            font-size: 16px;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .thread-link {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .thread-link:hover {
            color: #ff0033;
        }
        
        .thread-meta {
            font-size: 12px;
            color: #ccc;
        }
        
        .thread-meta i {
            color: #ff0033;
            width: 16px;
            margin-right: 3px;
        }
        
        .thread-stats {
            color: #ccc;
            font-size: 14px;
        }
        
        .thread-stats i {
            color: #ff0033;
        }
        
        /* Responsywność */
        @media (max-width: 768px) {
            .breadcrumb-custom .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }
            
            .btn-new-thread {
                width: 100%;
                justify-content: center;
            }
            
            .thread-row {
                padding: 12px;
            }
            
            .thread-title {
                font-size: 15px;
            }
            
            .thread-meta {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

<?php include "../components/navbar.php"; ?>

<div class="container mt-5 pt-5">
    <!-- Breadcrumb z przyciskiem -->
    <div class="breadcrumb-custom">
        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 5px;">
                <a href="index.php" style="color:#ff0033; text-decoration:none;">Forum</a>
                <i class="fas fa-chevron-right mx-2" style="color:#ff0033; font-size: 12px;"></i>
                <span style="color:white;"><?= htmlspecialchars($category['name']) ?></span>
            </div>
            
            <?php if (isForumUserLoggedIn()): ?>
                <a href="create_thread.php?category_id=<?= $category_id ?>" class="btn-new-thread">
                    <i class="fas fa-plus me-2"></i>Nowy wątek
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Lista wątków -->
    <div class="row">
        <div class="col-12">
            <?php if ($threads_result->num_rows == 0): ?>
                <div class="text-center py-5" style="color: #ccc;">
                    <i class="fas fa-comments fa-3x mb-3" style="color: #ff0033;"></i>
                    <p>Brak wątków w tej kategorii.</p>
                    <?php if (isForumUserLoggedIn()): ?>
                        <a href="create_thread.php?category_id=<?= $category_id ?>" class="btn-new-thread mt-3">
                            <i class="fas fa-plus me-2"></i>Załóż pierwszy wątek
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Nagłówek tabeli (widoczny tylko na desktopie) -->
                <div class="thread-header d-none d-md-flex">
                    <div class="row w-100 mx-0 py-2" style="color: #ff0033; font-family: 'Orbitron'; border-bottom: 2px solid #ff0033;">
                        <div class="col-md-7">Temat</div>
                        <div class="col-md-2 text-center">Odpowiedzi</div>
                        <div class="col-md-3 text-end">Ostatnia aktywność</div>
                    </div>
                </div>
                
                <?php while ($thread = $threads_result->fetch_assoc()): ?>
                <div class="thread-row <?= $thread['is_pinned'] ? 'pinned-thread' : '' ?>">
                    <!-- Wersja desktopowa (widoczna na md i większych) -->
                    <div class="row align-items-center d-none d-md-flex">
                        <div class="col-md-7">
                            <?php if ($thread['is_pinned']): ?>
                                <i class="fas fa-thumbtack me-2" style="color: gold;"></i>
                            <?php endif; ?>
                            <div class="thread-title">
                                <a href="thread.php?id=<?= $thread['id'] ?>" class="thread-link">
                                    <?= htmlspecialchars($thread['title']) ?>
                                </a>
                            </div>
                            <div class="thread-meta">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($thread['username']) ?> |
                                <i class="fas fa-clock"></i> <?= date('d.m.Y H:i', strtotime($thread['created_at'])) ?>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="thread-stats">
                                <i class="fas fa-reply me-1"></i> <?= $thread['post_count'] - 1 ?>
                            </span>
                        </div>
                        <div class="col-md-3 text-end">
                            <?php if ($thread['last_post_time']): ?>
                                <div class="thread-meta">
                                    <i class="fas fa-history"></i> 
                                    <?= date('d.m.Y H:i', strtotime($thread['last_post_time'])) ?>
                                </div>
                            <?php else: ?>
                                <span class="text-secondary">Brak odpowiedzi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Wersja mobilna (widoczna tylko na małych ekranach) -->
                    <div class="d-md-none">
                        <div class="d-flex align-items-start gap-2">
                            <?php if ($thread['is_pinned']): ?>
                                <i class="fas fa-thumbtack mt-1" style="color: gold; font-size: 14px;"></i>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <div class="thread-title mb-2">
                                    <a href="thread.php?id=<?= $thread['id'] ?>" class="thread-link">
                                        <?= htmlspecialchars($thread['title']) ?>
                                    </a>
                                </div>
                                <div class="d-flex flex-wrap gap-3 mb-2">
                                    <div class="thread-meta">
                                        <i class="fas fa-user"></i> <?= htmlspecialchars($thread['username']) ?>
                                    </div>
                                    <div class="thread-meta">
                                        <i class="fas fa-clock"></i> <?= date('d.m H:i', strtotime($thread['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="thread-stats">
                                        <i class="fas fa-reply"></i> 
                                        <span class="ms-1"><?= $thread['post_count'] - 1 ?> odpowiedzi</span>
                                    </div>
                                    <?php if ($thread['last_post_time']): ?>
                                        <div class="thread-meta">
                                            <i class="fas fa-history"></i> 
                                            <?= date('d.m H:i', strtotime($thread['last_post_time'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>