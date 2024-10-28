<?php 
include '../inc/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrollable Plant List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .scrollable-container {
            max-height: 80vh;
            overflow-y: auto;
        }
        .plant-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .plant-item img {
            width: 150px;
            height: auto;
            margin-right: 20px;
        }
        .plant-description {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Plant List</h2>
        <div class="scrollable-container">
            <?php
            $stmt = $pdo->query("SELECT * FROM tbl_plant");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="plant-item">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<div>';
                echo '<h5>' . htmlspecialchars($row['name']) . '</h5>';
                echo '<p class="plant-description">' . htmlspecialchars($row['description']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
