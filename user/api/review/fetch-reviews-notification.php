<?php
// fetch_reviews.php

include '../../../inc/config.php';
session_start();

if (isset($_SESSION['user'])) {
    // Get `review_id` from the URL if present
    $urlReviewId = isset($_GET['id']) ? $_GET['id'] : null;

    try {
        // If a review ID is passed in the URL, fetch the specific review
        if ($urlReviewId) {
            $query = "SELECT tr.*, tu.id AS user_id, tl.location_name, tl.city, tu.name, tu.profile_img, tr.user_id AS review_user_id,
                      COUNT(trr.id) AS like_count,
                      (SELECT COUNT(*) FROM tbl_review_reaction WHERE review_id = tr.id) AS user_liked
                      FROM tbl_review tr
                      LEFT JOIN tbl_location tl ON tr.location_id = tl.id 
                      LEFT JOIN tbl_user tu ON tu.id = tr.user_id 
                      LEFT JOIN tbl_review_reaction trr ON tr.id = trr.review_id 
                      WHERE trr.id = :url_review_id
                      GROUP BY tr.id
                      ORDER BY tr.id DESC";

            $stmt = $pdo->prepare($query);
            $stmt->execute([':url_review_id' => $urlReviewId]);

            $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Optional: Logic for fetching all reviews can go here
            // $locations = fetchAllReviews(); // Example placeholder
        }

        // Return the data as JSON
        echo json_encode($locations ?? []);
    } catch (PDOException $e) {
        // Handle the error and return an appropriate response
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>