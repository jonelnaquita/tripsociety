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
            // Split the image filenames by comma and trim whitespace
            $images = array_map('trim', explode(',', $post['image']));

            // Prepare response
            $response = [
                'id' => $post['id'],
                'post' => $post['post'],
                'location' => $post['location'],
            ];

            // Only add images if they exist and are not empty
            if (!empty($images) && !empty($images[0])) {
                $response['images'] = $images; // Add images to response only if they are not empty
            }

            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Post not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>