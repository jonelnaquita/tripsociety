<?php
// Database connection using PDO
include '../../../inc/config.php';

if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    try {
        // Fetch the main post details including images
        $sql = "SELECT * FROM tbl_post WHERE id = :post_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['post_id' => $post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            // Split the image filenames by comma
            $images = explode(',', $post['image']);
            $image_paths = [];

            // Create the full path for each image
            foreach ($images as $img) {
                $image_paths[] = '../admin/post_image/' . trim($img);
            }

            echo json_encode([
                'id' => $post['id'],
                'post' => $post['post'],
                'location' => $post['location'],
                'image' => $post['image'],
                'images' => !empty($image_paths) ? $image_paths : [] // Send back all image paths if available
            ]);
        } else {
            echo json_encode(['error' => 'Post not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>