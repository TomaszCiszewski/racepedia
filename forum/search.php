<?php
include "../backend/config.php";
include "backend/forum_functions.php";

$query = trim($_GET['q'] ?? '');
$type = $_GET['type'] ?? 'all'; // all, threads, posts
$results = [];
$searchTime = 0;

if (!empty($query) && strlen($query) >= 3) {
    $startTime = microtime(true);
    
    // Zabezpieczenie przed SQL injection przez przygotowane zapytania
    $searchTerm = "%$query%";
    
    if ($type == 'all' || $type == 'threads') {
        // Szukaj w wątkach
        $threadSql = "
            SELECT 
                'thread' as result_type,
                t.id, 
                t.title as title,
                t.content as preview,
                t.created_at,
                c.name as category_name,
                c.id as category_id,
                u.username as author_name,
                u.id as author_id,
                (SELECT COUNT(*) FROM forum_posts WHERE thread_id = t.id) as reply_count
            FROM forum_threads t
            JOIN forum_categories c ON t.category_id = c.id
            JOIN users u ON t.author_id = u.id
            WHERE t.title LIKE ? OR t.content LIKE ?
            ORDER BY t.created_at DESC
            LIMIT 20
        ";
        $stmt = $conn->prepare($threadSql);
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $threadResults = $stmt->get_result();
        while ($row = $threadResults->fetch_assoc()) {
            $results[] = $row;
        }
    }
    
    if ($type == 'all' || $type == 'posts') {
        // Szukaj w postach (ale nie w pierwszym poście wątku)
        $postSql = "
            SELECT 
                'post' as result_type,
                p.id,
                t.title as thread_title,
                t.id as thread_id,
                p.content as preview,
                p.created_at,
                c.name as category_name,
                c.id as category_id,
                u.username as author_name,
                u.id as author_id
            FROM forum_posts p
            JOIN forum_threads t ON p.thread_id = t.id
            JOIN forum_categories c ON t.category_id = c.id
            JOIN users u ON p.author_id = u.id
            WHERE p.content LIKE ? 
              AND p.id != (SELECT MIN(id) FROM forum_posts WHERE thread_id = t.id)
            ORDER BY p.created_at DESC
            LIMIT 20
        ";
        $stmt = $conn->prepare($postSql);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $postResults = $stmt->get_result();
        while ($row = $postResults->fetch_assoc()) {
            $results[] = $row;
        }
    }
    
    // Sortuj wyniki po dacie (najnowsze pierwsze)
    usort($results, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    $searchTime = round((microtime(true) - $startTime) * 1000, 2);
}

// Funkcja do podświetlania wyników
function highlight($text, $query) {
    if (empty($query)) return htmlspecialchars($text);
    
    $words = explode(' ', $query);
    $text = htmlspecialchars($text);
    
    foreach ($words as $word) {
        if (strlen($word) < 2) continue;
        $text = preg_replace('/(' . preg_quote($word, '/') . ')/iu', 
                             '<span class="highlight">$1</span>', 
                             $text);
    }
    return $text;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szukaj w forum - Racepedia</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Nasze style -->
    <?php include "../components/styles.php"; ?>
    
    <style>
        .search-container {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .search-title {
            font-family: 'Orbitron', sans-serif;
            color: #ff0033;
            margin-bottom: 25px;
        }
        
        .search-box {
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 50px;
            padding: 5px;
            display: flex;
            transition: all 0.3s ease;
        }
        
        .search-box:focus-within {
            border-color: #ff0033;
            box-shadow: 0 0 20px rgba(255, 0, 51, 0.3);
        }
        
        .search-input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 15px 25px;
            color: white;
            font-size: 16px;
            outline: none;
        }
        
        .search-input::placeholder {
            color: #666;
        }
        
        .search-button {
            background: #ff0033;
            border: none;
            border-radius: 50px;
            padding: 10px 30px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-button:hover {
            background: #ff1a47;
            transform: scale(1.05);
        }
        
        .search-filters {
            margin-top: 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .filter-option {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ccc;
            cursor: pointer;
        }
        
        .filter-option input[type="radio"] {
            accent-color: #ff0033;
            width: 16px;
            height: 16px;
        }
        
        .results-info {
            background: #1a1a1a;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            color: #ccc;
        }
        
        .result-card {
            background: #111;
            border: 1px solid #ff0033;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .result-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(255, 0, 51, 0.2);
        }
        
        .result-type {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .type-thread {
            background: #ff0033;
            color: white;
        }
        
        .type-post {
            background: #333;
            color: #ccc;
        }
        
        .result-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .result-title a {
            color: white;
            text-decoration: none;
        }
        
        .result-title a:hover {
            color: #ff0033;
        }
        
        .result-preview {
            color: #ccc;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 10px;
            padding: 10px;
            background: #1a1a1a;
            border-radius: 5px;
        }
        
        .highlight {
            background: rgba(255, 0, 51, 0.3);
            color: #ff0033;
            font-weight: 600;
            padding: 0 2px;
            border-radius: 2px;
        }
        
        .result-meta {
            font-size: 12px;
            color: #999;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .result-meta i {
            color: #ff0033;
            width: 16px;
            margin-right: 5px;
        }
        
        .no-results {
            text-align: center;
            padding: 50px;
            color: #ccc;
        }
        
        .no-results i {
            font-size: 60px;
            color: #ff0033;
            margin-bottom: 20px;
        }
        
        .search-tip {
            background: #1a1a1a;
            border-left: 4px solid #ff0033;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        
        .search-tip i {
            color: #ff0033;
            margin-right: 10px;
        }
        
        @media (max-width: 768px) {
            .search-box {
                flex-direction: column;
                border-radius: 15px;
            }
            
            .search-button {
                border-radius: 10px;
                margin: 5px;
            }
            
            .search-filters {
                justify-content: center;
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
            <span style="color: white;">Szukaj</span>
        </div>
    </div>
    
    <!-- Wyszukiwarka -->
    <div class="search-container">
        <h2 class="search-title">
            <i class="fas fa-search me-2"></i>Szukaj w forum
        </h2>
        
        <form method="GET" action="search.php">
            <div class="search-box">
                <input type="text" 
                       name="q" 
                       class="search-input" 
                       placeholder="Szukaj wątków i postów..."
                       value="<?= htmlspecialchars($query) ?>"
                       minlength="3"
                       autofocus>
                <button type="submit" class="search-button">
                    <i class="fas fa-search me-2"></i>Szukaj
                </button>
            </div>
            
            <div class="search-filters">
                <label class="filter-option">
                    <input type="radio" name="type" value="all" <?= $type == 'all' ? 'checked' : '' ?>>
                    <span>Wszystko</span>
                </label>
                <label class="filter-option">
                    <input type="radio" name="type" value="threads" <?= $type == 'threads' ? 'checked' : '' ?>>
                    <span>Tylko wątki</span>
                </label>
                <label class="filter-option">
                    <input type="radio" name="type" value="posts" <?= $type == 'posts' ? 'checked' : '' ?>>
                    <span>Tylko odpowiedzi</span>
                </label>
            </div>
        </form>
        
        <?php if (!empty($query) && strlen($query) < 3): ?>
        <div class="search-tip">
            <i class="fas fa-info-circle"></i>
            Wpisz przynajmniej 3 znaki aby rozpocząć wyszukiwanie.
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Wyniki -->
    <?php if (!empty($query) && strlen($query) >= 3): ?>
        <div class="results-info">
            <i class="fas fa-info-circle me-2" style="color: #ff0033;"></i>
            Znaleziono <strong><?= count($results) ?></strong> wyników dla frazy "<?= htmlspecialchars($query) ?>" 
            (czas wyszukiwania: <?= $searchTime ?> ms)
        </div>
        
        <?php if (empty($results)): ?>
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h4 style="color: white; margin-bottom: 10px;">Brak wyników</h4>
            <p>Nie znaleziono żadnych wątków ani postów zawierających "<?= htmlspecialchars($query) ?>"</p>
            
            <div class="search-tip" style="text-align: left; margin-top: 30px;">
                <i class="fas fa-lightbulb"></i>
                <strong>Porady:</strong>
                <ul style="margin-top: 10px; color: #ccc;">
                    <li>Sprawdź pisownię słów kluczowych</li>
                    <li>Użyj bardziej ogólnych terminów</li>
                    <li>Poszukaj w starszych wątkach</li>
                </ul>
            </div>
        </div>
        <?php else: ?>
            <?php foreach ($results as $result): ?>
                <?php if ($result['result_type'] == 'thread'): ?>
                <!-- Wynik: wątek -->
                <div class="result-card">
                    <span class="result-type type-thread">
                        <i class="fas fa-file-alt me-1"></i>Wątek
                    </span>
                    <div class="result-title">
                        <a href="thread.php?id=<?= $result['id'] ?>">
                            <?= highlight($result['title'], $query) ?>
                        </a>
                    </div>
                    <div class="result-preview">
                        <?= highlight(substr($result['preview'], 0, 200) . '...', $query) ?>
                    </div>
                    <div class="result-meta">
                        <span><i class="fas fa-user"></i> <?= htmlspecialchars($result['author_name']) ?></span>
                        <span><i class="fas fa-folder"></i> <?= htmlspecialchars($result['category_name']) ?></span>
                        <span><i class="fas fa-reply"></i> <?= $result['reply_count'] ?> odpowiedzi</span>
                        <span><i class="far fa-clock"></i> <?= date('d.m.Y H:i', strtotime($result['created_at'])) ?></span>
                    </div>
                </div>
                
                <?php else: ?>
                <!-- Wynik: post -->
                <div class="result-card">
                    <span class="result-type type-post">
                        <i class="fas fa-reply me-1"></i>Odpowiedź
                    </span>
                    <div class="result-title">
                        <a href="thread.php?id=<?= $result['thread_id'] ?>#post-<?= $result['id'] ?>">
                            <?= highlight($result['thread_title'], $query) ?>
                        </a>
                    </div>
                    <div class="result-preview">
                        <?= highlight(substr($result['preview'], 0, 200) . '...', $query) ?>
                    </div>
                    <div class="result-meta">
                        <span><i class="fas fa-user"></i> <?= htmlspecialchars($result['author_name']) ?></span>
                        <span><i class="fas fa-folder"></i> <?= htmlspecialchars($result['category_name']) ?></span>
                        <span><i class="far fa-clock"></i> <?= date('d.m.Y H:i', strtotime($result['created_at'])) ?></span>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>