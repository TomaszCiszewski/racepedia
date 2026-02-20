<?php
include "../backend/config.php";
include "backend/forum_functions.php";

// Sprawdź czy użytkownik jest zalogowany
if (!isForumUserLoggedIn()) {
    header("Location: ../login.php?redirect=forum/edit_post.php?id=" . ($_GET['id'] ?? 0));
    exit();
}

$post_id = $_GET['id'] ?? 0;
$error = '';
$success = '';

// Pobierz dane posta
$stmt = $conn->prepare("
    SELECT p.*, t.id as thread_id, t.title as thread_title, 
           c.id as category_id, c.name as category_name
    FROM forum_posts p
    JOIN forum_threads t ON p.thread_id = t.id
    JOIN forum_categories c ON t.category_id = c.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    header("Location: index.php");
    exit();
}

// Sprawdź uprawnienia (właściciel lub admin)
if ($post['author_id'] != $_SESSION['user_id'] && !isForumAdmin()) {
    header("Location: thread.php?id=" . $post['thread_id']);
    exit();
}

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    
    if (empty($content)) {
        $error = 'Treść posta nie może być pusta.';
    } elseif (strlen($content) < 3) {
        $error = 'Post jest za krótki (minimum 3 znaki).';
    } else {
        $stmt = $conn->prepare("
            UPDATE forum_posts 
            SET content = ?, is_edited = 1, updated_at = NOW() 
            WHERE id = ?
        ");
        $stmt->bind_param("si", $content, $post_id);
        
        if ($stmt->execute()) {
            header("Location: thread.php?id=" . $post['thread_id'] . "&edited=1#post-" . $post_id);
            exit();
        } else {
            $error = 'Wystąpił błąd podczas zapisywania zmian.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj post - Racepedia</title>
    
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
        
        .form-container {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 15px;
            padding: 30px;
        }
        
        .post-info {
            background: #1a1a1a;
            border-left: 4px solid #ff0033;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        
        .post-info-title {
            font-weight: 600;
            color: white;
            margin-bottom: 5px;
        }
        
        .post-info-meta {
            font-size: 12px;
            color: #ccc;
        }
        
        .form-label {
            color: #ff0033;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .form-control {
            background: #1a1a1a;
            border: 1px solid #333;
            color: white;
            padding: 12px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .form-control:focus {
            background: #222;
            border-color: #ff0033;
            box-shadow: 0 0 0 0.25rem rgba(255, 0, 51, 0.25);
            color: white;
        }
        
        .btn-submit {
            background: #ff0033;
            color: white;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            background: #ff1a47;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 0, 51, 0.3);
        }
        
        .btn-cancel {
            background: transparent;
            border: 1px solid #ff0033;
            color: #ff0033;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cancel:hover {
            background: #ff0033;
            color: white;
        }
        
        .alert-custom {
            background: #1a1a1a;
            border: 1px solid;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            border-color: #ff0033;
            color: #ff0033;
        }
        
        .character-counter {
            color: #ccc;
            font-size: 12px;
            margin-top: 5px;
            text-align: right;
        }
        
        .preview-btn {
            background: transparent;
            border: 1px solid #ff0033;
            color: #ff0033;
            padding: 12px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .preview-btn:hover {
            background: #ff0033;
            color: white;
        }
        
        .preview-content {
            background: #1a1a1a;
            border: 1px solid #ff0033;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            display: none;
        }
        
        .preview-content.show {
            display: block;
        }
        
        .preview-title {
            color: #ff0033;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
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
            <a href="category.php?id=<?= $post['category_id'] ?>" style="color: #ff0033; text-decoration: none;">
                <?= htmlspecialchars($post['category_name']) ?>
            </a>
            <i class="fas fa-chevron-right mx-2" style="color: #ff0033; font-size: 12px;"></i>
            <a href="thread.php?id=<?= $post['thread_id'] ?>" style="color: #ff0033; text-decoration: none;">
                <?= htmlspecialchars($post['thread_title']) ?>
            </a>
            <i class="fas fa-chevron-right mx-2" style="color: #ff0033; font-size: 12px;"></i>
            <span style="color: white;">Edytuj post</span>
        </div>
    </div>
    
    <!-- Formularz -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h3 style="font-family: 'Orbitron'; color: #ff0033; margin-bottom: 30px;">
                    <i class="fas fa-edit me-2"></i>Edytuj post
                </h3>
                
                <!-- Informacje o poście -->
                <div class="post-info">
                    <div class="post-info-title">Post w wątku: <?= htmlspecialchars($post['thread_title']) ?></div>
                    <div class="post-info-meta">
                        <i class="fas fa-clock me-2"></i>Utworzono: <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                    </div>
                </div>
                
                <?php if ($error): ?>
                <div class="alert-custom alert-error">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="editForm">
                    <div class="mb-4">
                        <label class="form-label">Treść posta</label>
                        <textarea name="content" 
                                  id="postContent"
                                  class="form-control" 
                                  rows="12" 
                                  required><?= htmlspecialchars($post['content']) ?></textarea>
                        <div class="character-counter">
                            <span id="contentCounter"><?= strlen($post['content']) ?></span> znaków
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button type="button" class="preview-btn" onclick="togglePreview()">
                            <i class="fas fa-eye me-2"></i>Podgląd
                        </button>
                        <button type="submit" class="btn-submit flex-grow-1">
                            <i class="fas fa-save me-2"></i>Zapisz zmiany
                        </button>
                        <a href="thread.php?id=<?= $post['thread_id'] ?>" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>Anuluj
                        </a>
                    </div>
                </form>
                
                <!-- Podgląd -->
                <div id="preview" class="preview-content">
                    <div class="preview-title">
                        <i class="fas fa-eye me-2"></i>Podgląd
                    </div>
                    <div id="previewContent" class="post-content-preview"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Licznik znaków
document.getElementById('postContent').addEventListener('input', function() {
    document.getElementById('contentCounter').textContent = this.value.length;
});

// Funkcja podglądu
function togglePreview() {
    const preview = document.getElementById('preview');
    const content = document.getElementById('postContent').value;
    const previewContent = document.getElementById('previewContent');
    
    // Proste parsowanie cytatów dla podglądu
    let html = content.replace(/\[quote="([^"]+)"\](.*?)\[\/quote\]/gis, 
        '<div class="forum-quote"><div class="quote-author">$1 napisał:</div><div class="quote-content">$2</div></div>');
    html = html.replace(/\n/g, '<br>');
    
    previewContent.innerHTML = html;
    preview.classList.add('show');
}

// Zapobiegnij przypadkowemu wyjściu
window.addEventListener('beforeunload', function(e) {
    const content = document.getElementById('postContent').value;
    if (content !== '<?= htmlspecialchars($post['content'], ENT_QUOTES) ?>') {
        e.preventDefault();
        e.returnValue = 'Masz niezapisane zmiany. Na pewno chcesz opuścić stronę?';
    }
});
</script>

</body>
</html>