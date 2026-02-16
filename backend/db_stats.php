<?php
if (!defined('DB_STATS_LOADED')) {
    define('DB_STATS_LOADED', true);

    /**
     * Pobiera rozmiar całej bazy danych w bajtach
     */
    function getDatabaseSize($conn) {
        $sql = "SELECT SUM(data_length + index_length) as size 
                FROM information_schema.tables 
                WHERE table_schema = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $GLOBALS['db']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['size'] ?? 0;
    }

    /**
     * Formatuje rozmiar w bajtach na czytelną formę
     */
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Pobiera szczegółowe informacje o wszystkich tabelach
     */
    function getTableStats($conn) {
        $sql = "SELECT 
                    table_name,
                    table_rows as rows_count,
                    data_length + index_length as total_size,
                    data_length as data_size,
                    index_length as index_size,
                    create_time,
                    update_time
                FROM information_schema.tables 
                WHERE table_schema = ?
                ORDER BY total_size DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $GLOBALS['db']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tables = [];
        while ($row = $result->fetch_assoc()) {
            $tables[] = $row;
        }
        return $tables;
    }

    /**
     * Pobiera liczbę plików i rozmiar folderu assets
     */
    function getAssetsStats() {
        $stats = [
            'avatars' => ['count' => 0, 'size' => 0],
            'drivers' => ['count' => 0, 'size' => 0],
            'tracks' => ['count' => 0, 'size' => 0],
            'total' => ['count' => 0, 'size' => 0]
        ];
        
        $folders = [
            'avatars' => '../assets/avatars/',
            'drivers' => '../assets/drivers/',
            'tracks' => '../assets/tracks/'
        ];
        
        foreach ($folders as $key => $folder) {
            if (is_dir($folder)) {
                $files = glob($folder . '*');
                $stats[$key]['count'] = count($files);
                $stats[$key]['size'] = array_sum(array_map('filesize', $files));
                $stats['total']['count'] += $stats[$key]['count'];
                $stats['total']['size'] += $stats[$key]['size'];
            }
        }
        
        return $stats;
    }

    /**
     * Pobiera statystyki forum (jeśli tabela istnieje)
     */
    function getForumStats($conn) {
        $stats = [
            'topics' => 0,
            'posts' => 0,
            'users' => 0,
            'last_post' => null
        ];
        
        // Sprawdź czy tabela forum istnieje
        $result = $conn->query("SHOW TABLES LIKE 'forum_topics'");
        if ($result->num_rows > 0) {
            $result = $conn->query("SELECT COUNT(*) as count FROM forum_topics");
            $stats['topics'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(*) as count FROM forum_posts");
            $stats['posts'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(DISTINCT user_id) as count FROM forum_posts");
            $stats['users'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT created_at FROM forum_posts ORDER BY id DESC LIMIT 1");
            if ($result->num_rows > 0) {
                $stats['last_post'] = $result->fetch_assoc()['created_at'];
            }
        }
        
        return $stats;
    }

    /**
     * Pobiera statystyki dotyczące torów
     */
    function getTracksStats($conn) {
        $stats = [
            'total' => 0,
            'countries' => 0,
            'avg_length' => 0,
            'longest' => null,
            'shortest' => null
        ];
        
        // Sprawdź czy tabela tracks istnieje
        $result = $conn->query("SHOW TABLES LIKE 'tracks'");
        if ($result->num_rows > 0) {
            $result = $conn->query("SELECT COUNT(*) as count FROM tracks");
            $stats['total'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(DISTINCT country) as count FROM tracks");
            $stats['countries'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT AVG(length_km) as avg FROM tracks");
            $stats['avg_length'] = round($result->fetch_assoc()['avg'], 2);
            
            $result = $conn->query("SELECT name, length_km FROM tracks ORDER BY length_km DESC LIMIT 1");
            if ($row = $result->fetch_assoc()) {
                $stats['longest'] = $row;
            }
            
            $result = $conn->query("SELECT name, length_km FROM tracks ORDER BY length_km ASC LIMIT 1");
            if ($row = $result->fetch_assoc()) {
                $stats['shortest'] = $row;
            }
        }
        
        return $stats;
    }

    /**
     * Pobiera statystyki wyścigów
     */
    function getRacesStats($conn) {
        $stats = [
            'total' => 0,
            'unique_winners' => 0,
            'most_wins' => null,
            'next_race' => null,
            'last_race' => null
        ];
        
        // Sprawdź czy tabela races istnieje
        $result = $conn->query("SHOW TABLES LIKE 'races'");
        if ($result->num_rows > 0) {
            $result = $conn->query("SELECT COUNT(*) as count FROM races");
            $stats['total'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT COUNT(DISTINCT winner) as count FROM races WHERE winner IS NOT NULL");
            $stats['unique_winners'] = $result->fetch_assoc()['count'];
            
            $result = $conn->query("SELECT winner, COUNT(*) as wins FROM races WHERE winner IS NOT NULL GROUP BY winner ORDER BY wins DESC LIMIT 1");
            if ($row = $result->fetch_assoc()) {
                $stats['most_wins'] = $row;
            }
            
            $result = $conn->query("SELECT name, date FROM races WHERE date >= CURDATE() ORDER BY date ASC LIMIT 1");
            if ($row = $result->fetch_assoc()) {
                $stats['next_race'] = $row;
            }
            
            $result = $conn->query("SELECT name, date FROM races WHERE date < CURDATE() ORDER BY date DESC LIMIT 1");
            if ($row = $result->fetch_assoc()) {
                $stats['last_race'] = $row;
            }
        }
        
        return $stats;
    }

    /**
     * Pobiera statystyki użytkowników
     */
    function getUsersStats($conn) {
        $stats = [
            'total' => 0,
            'by_role' => [],
            'new_today' => 0,
            'new_week' => 0,
            'new_month' => 0,
            'last_user' => null
        ];
        
        $result = $conn->query("SELECT COUNT(*) as count FROM users");
        $stats['total'] = $result->fetch_assoc()['count'];
        
        $result = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
        while ($row = $result->fetch_assoc()) {
            $stats['by_role'][$row['role']] = $row['count'];
        }
        
        $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE DATE(join_date) = CURDATE()");
        $stats['new_today'] = $result->fetch_assoc()['count'];
        
        $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE join_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stats['new_week'] = $result->fetch_assoc()['count'];
        
        $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE join_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stats['new_month'] = $result->fetch_assoc()['count'];
        
        $result = $conn->query("SELECT username, join_date FROM users ORDER BY id DESC LIMIT 1");
        if ($row = $result->fetch_assoc()) {
            $stats['last_user'] = $row;
        }
        
        return $stats;
    }
}
?>