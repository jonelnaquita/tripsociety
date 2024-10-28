<?php
include '../../../inc/config.php';

try {

    // Fetch data from tbl_location
    $pdo_statement = $pdo->prepare("SELECT * FROM tbl_location");
    $pdo_statement->execute();
    $result = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

    // Send the result as a JSON response
    echo json_encode($result);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>