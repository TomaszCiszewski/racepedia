<?php
include "config.php";

echo "<h2>Sprawdzenie danych kierowców w bazie</h2>";

$result = $conn->query("
    SELECT d.*, t.name as team_name 
    FROM drivers d 
    LEFT JOIN teams t ON d.team_id = t.id 
    ORDER BY d.id
");

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr>
        <th>ID</th>
        <th>Imię i nazwisko</th>
        <th>Numer</th>
        <th>Kraj</th>
        <th>Zespół</th>
        <th>Image Path</th>
        <th>Tytuły</th>
        <th>Bio</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['full_name'] . "</td>";
    echo "<td>" . ($row['number'] ?? 'NULL') . "</td>";
    echo "<td>" . $row['country'] . "</td>";
    echo "<td>" . ($row['team_name'] ?? 'Brak') . "</td>";
    echo "<td>" . ($row['image_path'] ?? 'BRAK!') . "</td>";
    echo "<td>" . $row['world_titles'] . "</td>";
    echo "<td>" . substr($row['bio'] ?? '', 0, 50) . "...</td>";
    echo "</tr>";
}
echo "</table>";

// Sprawdź czy pliki istnieją w folderze
echo "<h2>Sprawdzenie plików w folderze assets/drivers/</h2>";
$files = scandir('../assets/drivers/');
$imageFiles = array_diff($files, ['.', '..', '.DS_Store']);
echo "<ul>";
foreach ($imageFiles as $file) {
    echo "<li>$file</li>";
}
echo "</ul>";
?>