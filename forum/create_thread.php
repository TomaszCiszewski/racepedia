<?php
include "../backend/config.php";
include "backend/forum_functions.php";

// Sprawdź czy użytkownik jest zalogowany
if (!isForumUserLoggedIn()) {
    header("Location: ../login.php?redirect=forum/create_thread.php");
    exit();
}

$category_id = $_GET['category_id'] ?? 0;
$error = '';
$success = '';

// Pobierz nazwę kategorii
$stmt = $conn->prepare("SELECT name FROM forum_categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    header("Location: index.php");
    exit();
}

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (empty($title)) {
        $error = 'Tytuł wątku nie może być pusty.';
    } elseif (empty($content)) {
        $error = 'Treść wątku nie może być pusta.';
    } elseif (strlen($title) > 255) {
        $error = 'Tytuł jest za długi (max 255 znaków).';
    } else {
        // Rozpocznij transakcję
        $conn->begin_transaction();
        
        try {
            // Utwórz nowy wątek
            $stmt = $conn->prepare("
                INSERT INTO forum_threads (category_id, title, content, author_id, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param("issi", $category_id, $title, $content, $_SESSION['user_id']);
            $stmt->execute();
            
            $thread_id = $conn->insert_id;
            
            // Utwórz pierwszy post w wątku
            $stmt = $conn->prepare("
                INSERT INTO forum_posts (thread_id, content, author_id, created_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->bind_param("isi", $thread_id, $content, $_SESSION['user_id']);
            $stmt->execute();
            
            $conn->commit();
            
            // Przekieruj do nowego wątku
            header("Location: thread.php?id=" . $thread_id);
            exit();
            
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Wystąpił błąd podczas tworzenia wątku. Spróbuj ponownie.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowy wątek - <?= htmlspecialchars($category['name']) ?> - Racepedia</title>
    
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
        
        .character-counter {
            color: #ccc;
            font-size: 12px;
            margin-top: 5px;
            text-align: right;
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
            <a href="category.php?id=<?= $category_id ?>" style="color: #ff0033; text-decoration: none;">
                <?= htmlspecialchars($category['name']) ?>
            </a>
            <i class="fas fa-chevron-right mx-2" style="color: #ff0033; font-size: 12px;"></i>
            <span style="color: white;">Nowy wątek</span>
        </div>
    </div>
    
    <!-- Formularz -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h3 style="font-family: 'Orbitron'; color: #ff0033; margin-bottom: 30px;">
                    <i class="fas fa-plus-circle me-2"></i>Nowy wątek
                </h3>
                
                <?php if ($error): ?>
                <div class="alert-custom alert-error">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="form-label">Tytuł wątku</label>
                        <input type="text" 
                               name="title" 
                               class="form-control" 
                               placeholder="Wpisz tytuł wątku"
                               maxlength="255"
                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                               required>
                        <div class="character-counter">
                            <span id="title-counter">0</span>/255 znaków
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Treść</label>
                        <textarea name="content" 
                                  class="form-control" 
                                  rows="10" 
                                  placeholder="Wpisz treść wątku..."
                                  required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                        <div class="character-counter">
                            Minimalna długość: 3 znaki
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-submit flex-grow-1">
                            <i class="fas fa-paper-plane me-2"></i>Utwórz wątek
                        </button>
                        <a href="category.php?id=<?= $category_id ?>" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>Anuluj
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Licznik znaków dla tytułu
document.querySelector('input[name="title"]').addEventListener('input', function() {
    document.getElementById('title-counter').textContent = this.value.length;
});

// Zapobiegnij przypadkowemu wysłaniu pustego formularza
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.querySelector('input[name="title"]').value.trim();
    const content = document.querySelector('textarea[name="content"]').value.trim();
    
    if (!title || !content) {
        e.preventDefault();
        alert('Wypełnij wszystkie pola!');
    }
});
</script>

</body>
</html>