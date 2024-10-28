<?php
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $imagePath = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imageName = time() . '_' . $image['name'];
        $imagePath = '../../announcement/' . $imageName;

        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            echo "Error uploading image.";
            exit;
        }
    }

    // Insert data into database
    $stmt = $pdo->prepare("INSERT INTO tbl_announcement (title, description, image) VALUES (:title, :description, :image)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $imageName);

    if ($stmt->execute()) {
        echo "Announcement saved successfully!";
    } else {
        echo "Error saving announcement.";
    }
}
?>