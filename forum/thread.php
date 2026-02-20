<?php
include "../backend/config.php";
include "backend/forum_functions.php";

$thread_id = $_GET['id'] ?? 0;

// Funkcja do parsowania cytatów
function parseQuotes($text) {
    // Zabezpiecz przed XSS, ale zachowaj znaczniki quote
    $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
    
    // Obsługa zagnieżdżonych cytatów
    $pattern = '/\[quote="([^"]+)"\](.*?)\[\/quote\]/is';
    
    // Rekurencyjnie przetwarzaj cytaty
    while (preg_match($pattern, $text)) {
        $text = preg_replace_callback($pattern, function($matches) {
            $author = $matches[1];
            $content = $matches[2];
            
            // Przetwórz wnętrze cytatu (może zawierać kolejne cytaty)
            $content = parseQuotes($content);
            
            return '<div class="forum-quote">
                        <div class="quote-author">
                            <i class="fas fa-quote-left me-2"></i>' . $author . ' napisał:
                        </div>
                        <div class="quote-content">' . $content . '</div>
                    </div>';
        }, $text);
    }
    
    return $text;
}

// Pobierz informacje o wątku
$stmt = $conn->prepare("
    SELECT t.*, c.name as category_name, c.id as category_id,
           u.username as author_name, u.avatar as author_avatar
    FROM forum_threads t
    JOIN forum_categories c ON t.category_id = c.id
    JOIN users u ON t.author_id = u.id
    WHERE t.id = ?
");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$thread = $stmt->get_result()->fetch_assoc();

if (!$thread) {
    header("Location: index.php");
    exit();
}

// Zwiększ licznik wyświetleń
$conn->query("UPDATE forum_threads SET views = views + 1 WHERE id = $thread_id");

// Pobierz posty w wątku
$posts = $conn->prepare("
    SELECT p.*, u.username, u.avatar, u.role, u.id as user_id
    FROM forum_posts p
    JOIN users u ON p.author_id = u.id
    WHERE p.thread_id = ?
    ORDER BY p.created_at ASC
");
$posts->bind_param("i", $thread_id);
$posts->execute();
$posts_result = $posts->get_result();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($thread['title']) ?> - Forum Racepedia</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Nasze style -->
    <?php include "../components/styles.php"; ?>
    
    <style>
        /* Style dla cytatów */
        .forum-quote {
            background: #1a1a1a;
            border-left: 4px solid #ff0033;
            border-radius: 5px;
            margin: 15px 0;
            padding: 10px;
        }

        .forum-quote .forum-quote {
            margin-left: 20px;
            background: #222;
        }

        .quote-author {
            color: #ff0033;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }

        .quote-author i {
            color: #ff0033;
            font-size: 12px;
        }

        .quote-content {
            color: #ccc;
            font-style: italic;
            padding: 5px 10px;
            line-height: 1.5;
            font-size: 14px;
        }

        .breadcrumb-custom {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 30px;
        }
        
        .post-card {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 15px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .post-header {
            background: #0a0a0a;
            padding: 15px 20px;
            border-bottom: 1px solid #ff0033;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .post-author {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #ff0033;
            object-fit: cover;
        }
        
        .author-name {
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }
        
        .author-role {
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 3px;
            background: #ff0033;
            color: white;
            display: inline-block;
        }
        
        .post-date {
            color: #ccc;
            font-size: 12px;
        }
        
        .post-date i {
            color: #ff0033;
            margin-right: 5px;
        }
        
        .post-content {
            padding: 25px;
            color: #ccc;
            line-height: 1.8;
            font-size: 15px;
            word-wrap: break-word;
        }
        
        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        
        .post-footer {
            background: #0a0a0a;
            padding: 10px 20px;
            border-top: 1px solid #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .post-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            background: transparent;
            border: 1px solid #ff0033;
            color: #ff0033;
            padding: 5px 15px;
            font-size: 13px;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-action:hover {
            background: #ff0033;
            color: white;
        }
        
        .btn-reply {
            background: #ff0033;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-reply:hover {
            background: #ff1a47;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 0, 51, 0.3);
        }
        
        .post-number {
            background: #ff0033;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .quote-block {
            background: #1a1a1a;
            border-left: 4px solid #ff0033;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        
        @media (max-width: 768px) {
            .post-header {
                flex-direction: column;
                text-align: center;
            }
            
            .post-author {
                flex-direction: column;
                text-align: center;
            }
            
            .post-footer {
                flex-direction: column;
            }
            
            .post-actions {
                justify-content: center;
            }
            
            .forum-quote .forum-quote {
                margin-left: 10px;
            }
        }
    </style>
</head>
<body>

<?php include "../components/navbar.php"; ?>

<div class="container mt-5 pt-5">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <div class="d-flex align-items-center flex-wrap" style="gap: 5px;">
            <a href="index.php" style="color: #ff0033; text-decoration: none;">Forum</a>
            <i class="fas fa-chevron-right mx-2" style="color: #ff0033; font-size: 12px;"></i>
            <a href="category.php?id=<?= $thread['category_id'] ?>" style="color: #ff0033; text-decoration: none;">
                <?= htmlspecialchars($thread['category_name']) ?>
            </a>
            <i class="fas fa-chevron-right mx-2" style="color: #ff0033; font-size: 12px;"></i>
            <span style="color: white;"><?= htmlspecialchars($thread['title']) ?></span>
        </div>
    </div>
    
    <!-- Tytuł wątku -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap: 15px;">
        <h2 style="font-family: 'Orbitron'; color: #ff0033; margin: 0;">
            <?= htmlspecialchars($thread['title']) ?>
        </h2>
        <div>
            <i class="fas fa-eye me-2" style="color: #ff0033;"></i>
            <span style="color: #ccc;"><?= $thread['views'] ?> wyświetleń</span>
        </div>
    </div>
    
    <!-- Posty -->
    <?php 
    $post_count = 1;
    while ($post = $posts_result->fetch_assoc()): 
    ?>
    <div class="post-card" id="post-<?= $post['id'] ?>">
        <div class="post-header">
            <div class="post-author">
                <img src="../assets/avatars/<?= htmlspecialchars($post['avatar'] ?? 'default.jpg') ?>" 
                     alt="Avatar" 
                     class="author-avatar">
                <div>
                    <div class="author-name"><?= htmlspecialchars($post['username']) ?></div>
                    <?php if ($post['user_id'] == $thread['author_id']): ?>
                        <span class="author-role">Autor wątku</span>
                    <?php elseif ($post['role'] == 'Administrator'): ?>
                        <span class="author-role">Administrator</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <span class="post-number">#<?= $post_count ?></span>
            </div>
        </div>
        
        <div class="post-content">
            <?= parseQuotes($post['content']) ?>
            
            <?php if ($post['is_edited']): ?>
                <div class="quote-block" style="margin-top: 20px; font-size: 12px;">
                    <i class="fas fa-edit me-2"></i>
                    Ostatnia edycja: <?= date('d.m.Y H:i', strtotime($post['updated_at'])) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="post-footer">
            <div class="post-date">
                <i class="far fa-clock"></i>
                <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
            </div>
            
            <div class="post-actions">
                <?php if (isForumUserLoggedIn()): ?>
                    <?php if ($post['author_id'] == $_SESSION['user_id'] || isForumAdmin()): ?>
                        <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn-action">
                            <i class="fas fa-edit"></i> Edytuj
                        </a>
                    <?php endif; ?>
                    
                    <a href="reply.php?thread_id=<?= $thread_id ?>&quote=<?= $post['id'] ?>" class="btn-action">
                        <i class="fas fa-quote-right"></i> Cytuj
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php 
    $post_count++;
    endwhile; 
    ?>
    
    <!-- Przycisk odpowiedzi -->
    <?php if (isForumUserLoggedIn()): ?>
    <div class="text-center mt-4">
        <a href="reply.php?thread_id=<?= $thread_id ?>" class="btn-reply">
            <i class="fas fa-reply"></i>
            Odpowiedz w tym wątku
        </a>
    </div>
    <?php else: ?>
    <div class="text-center mt-4">
        <p style="color: #ccc;">
            <a href="../login.php?redirect=forum/thread.php?id=<?= $thread_id ?>" style="color: #ff0033;">
                Zaloguj się
            </a> aby odpowiedzieć w tym wątku.
        </p>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>