<?php
session_start();
include '../../../inc/config.php'; // Include your PDO database connection

if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // Prepare the SQL query
    $query = "
        SELECT u.name, u.username, u.profile_img, p.image, p.post 
        FROM tbl_user u 
        JOIN tbl_post p ON u.id = p.user_id 
        WHERE p.id = :post_id
    ";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the data
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if post exists
        if ($post) {
            // Explode image string into an array
            $imageArray = !empty($post['image']) ? explode(',', $post['image']) : [];

            $response = [
                'status' => 'success',
                'data' => [
                    'name' => $post['name'],
                    'username' => $post['username'],
                    'profile_img' => $post['profile_img'],
                    'post' => $post['post'],
                    'images' => $imageArray
                ],
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Post not found.',
            ];
        }

        echo json_encode($response);

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log error
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while fetching data.']);
    }

    // Log the fetched post_id
    error_log("Fetched post_id: " . $post_id);
}
?>