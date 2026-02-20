<?php
include "../backend/config.php";
include "backend/forum_functions.php";

// Sprawdź czy użytkownik jest zalogowany
if (!isForumUserLoggedIn()) {
    header("Location: ../login.php?redirect=forum/reply.php?thread_id=" . ($_GET['thread_id'] ?? 0));
    exit();
}

$thread_id = $_GET['thread_id'] ?? 0;
$quote_id = $_GET['quote'] ?? 0;
$error = '';
$quote_content = '';

// Pobierz informacje o wątku
$stmt = $conn->prepare("
    SELECT t.*, c.name as category_name 
    FROM forum_threads t
    JOIN forum_categories c ON t.category_id = c.id
    WHERE t.id = ?
");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$thread = $stmt->get_result()->fetch_assoc();

if (!$thread) {
    header("Location: index.php");
    exit();
}

// Jeśli to cytat, pobierz cytowany post
if ($quote_id) {
    $stmt = $conn->prepare("
        SELECT p.content, u.username 
        FROM forum_posts p
        JOIN users u ON p.author_id = u.id
        WHERE p.id = ?
    ");
    $stmt->bind_param("i", $quote_id);
    $stmt->execute();
    $quote = $stmt->get_result()->fetch_assoc();
    
    if ($quote) {
        $quote_content = '[quote="' . $quote['username'] . '"]' . $quote['content'] . "[/quote]\n\n";
    }
}

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    
    if (empty($content)) {
        $error = 'Treść odpowiedzi nie może być pusta.';
    } elseif (strlen($content) < 3) {
        $error = 'Odpowiedź jest za krótka (minimum 3 znaki).';
    } else {
        // Zapisz odpowiedź
        $stmt = $conn->prepare("
            INSERT INTO forum_posts (thread_id, content, author_id, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("isi", $thread_id, $content, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            header("Location: thread.php?id=" . $thread_id . "#post-" . $conn->insert_id);
            exit();
        } else {
            $error = 'Wystąpił błąd podczas zapisywania odpowiedzi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odpowiedz w wątku - Racepedia</title>
    
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
        
        .thread-info {
            background: #1a1a1a;
            border-left: 4px solid #ff0033;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        
        .thread-info-title {
            font-weight: 600;
            color: white;
            margin-bottom: 5px;
        }
        
        .thread-info-meta {
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
        
        .quote-preview {
            background: #0a0a0a;
            border: 1px dashed #ff0033;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #ccc;
            font-style: italic;
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
    </style>
</head>
<body>

<?php include "../components/navbar.php"; ?>

<div class="container mt-5 pt-5">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <div class="d-flex align-items-center flex-wrap">
            <a href="index.php" style="color: #ff0033; text-decoration: none;">Forum</a>
            <i class="fas fa-chevron-right mx-2" style="color: #ff0033; font-size: 12px;"></i>
            <a href="category.php?id=<?= $thread['category_id'] ?>" style="color: #ff0033; text-decoration: none;">
                <?= htmlspecialchars($thread['category_name']) ?>
            </a>
            <i class="fas fa-chevron-right mx-2" style="color: #ff0033; font-size: 12px;"></i>
            <a href="thread.php?id=<?= $thread_id ?>" style="color: #ff0033; text-decoration: none;">
                <?= htmlspecialchars($thread['title']) ?>
            </a>
            <i class="fas fa-chevron-right mx-2" style="color: #ff0033; font-size: 12px;"></i>
            <span style="color: white;">Odpowiedz</span>
        </div>
    </div>
    
    <!-- Formularz -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h3 style="font-family: 'Orbitron'; color: #ff0033; margin-bottom: 30px;">
                    <i class="fas fa-reply me-2"></i>Odpowiedz w wątku
                </h3>
                
                <!-- Informacje o wątku -->
                <div class="thread-info">
                    <div class="thread-info-title"><?= htmlspecialchars($thread['title']) ?></div>
                    <div class="thread-info-meta">
                        <i class="fas fa-user me-2"></i><?= htmlspecialchars($thread['author_name'] ?? 'Nieznany') ?> |
                        <i class="fas fa-clock me-2"></i><?= date('d.m.Y H:i', strtotime($thread['created_at'])) ?>
                    </div>
                </div>
                
                <?php if ($error): ?>
                <div class="alert-custom alert-error">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($quote_content): ?>
                <div class="quote-preview">
                    <i class="fas fa-quote-left me-2" style="color: #ff0033;"></i>
                    Cytujesz poprzedni post...
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="form-label">Twoja odpowiedź</label>
                        <textarea name="content" 
                                  class="form-control" 
                                  rows="10" 
                                  placeholder="Wpisz swoją odpowiedź..."
                                  required><?= htmlspecialchars($_POST['content'] ?? $quote_content) ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-submit flex-grow-1">
                            <i class="fas fa-paper-plane me-2"></i>Opublikuj odpowiedź
                        </button>
                        <a href="thread.php?id=<?= $thread_id ?>" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>Anuluj
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>