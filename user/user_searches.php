<?php
header('Content-Type: text/html; charset=utf-8');
include '../inc/config.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    try {
        // Prepare the SQL statement to fetch both id and location_name
        $stmt = $pdo->prepare('SELECT id, location_name FROM tbl_location WHERE location_name LIKE :query LIMIT 5');
        $stmt->execute(['query' => '%' . $query . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch both id and location_name

        if ($results) {
            foreach ($results as $result) {
                // Use the id in the href attribute
                echo '<a href="explore_destination.php?search&id=' . urlencode($result['id']) . '" class="text-dark">' . htmlspecialchars($result['location_name']) . '</a><br>';
            }
        } else {                   
            echo '<p>No results found</p>';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
