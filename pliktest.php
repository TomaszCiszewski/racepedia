<?php
include "backend/config.php";

echo "<h2>Test kodowania</h2>";

// Sprawdź kodowanie połączenia
$charset = $conn->get_charset();
echo "Kodowanie połączenia: " . $charset->charset . "<br>";

// Dodaj testowy rekord z polskimi znakami
$test = "Zażółć gęślą jaźń - test polskich znaków";
$stmt = $conn->prepare("INSERT INTO forum_posts (thread_id, content, author_id, created_at) VALUES (1, ?, 1, NOW())");
$stmt->bind_param("s", $test);
if ($stmt->execute()) {
    echo "✅ Dodano testowy post<br>";
    $last_id = $conn->insert_id;
    
    // Odczytaj go
    $result = $conn->query("SELECT content FROM forum_posts WHERE id = $last_id");
    $row = $result->fetch_assoc();
    echo "Odczytano: " . $row['content'] . "<br>";
} else {
    echo "❌ Błąd: " . $stmt->error . "<br>";
}
?>