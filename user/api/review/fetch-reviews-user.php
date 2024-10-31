<?php
// fetch_reviews.php

include '../../../inc/config.php';
session_start();

if (isset($_SESSION['user'])) {
    $id = $_SESSION['user'];

    // Get `user_id` from the URL if present
    $urlUserId = isset($_GET['id']) ? $_GET['id'] : null;

    // If a user ID is passed in the URL, filter reviews by this user ID
    if ($urlUserId) {
        $query = "SELECT tr.*, tu.id AS user_id, tl.location_name, tl.city, tu.name, tu.profile_img, tr.user_id AS review_user_id,
              COUNT(trr.id) AS like_count,
              (SELECT COUNT(*) FROM tbl_review_reaction WHERE user_id = :user_id AND review_id = tr.id) AS user_liked,
              (SELECT COUNT(*) FROM tbl_travel_companion WHERE user_id = :user_id AND companion_id = tr.user_id) AS request_sent
              FROM tbl_review tr
              LEFT JOIN tbl_location tl ON tr.location_id = tl.id 
              LEFT JOIN tbl_user tu ON tu.id = tr.user_id 
              LEFT JOIN tbl_review_reaction trr ON tr.id = trr.review_id 
              WHERE tr.user_id = :url_user_id
              GROUP BY tr.id
              ORDER BY tr.id DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':user_id' => $id, ':url_user_id' => $urlUserId]); // Pass both current user ID and URL user ID

    } else {
        // Use current user’s travel preferences if no user_id parameter is provided
        $travel_preferences = $_SESSION['travel_preferences'];
        $travel_pref_array = explode(',', $travel_preferences);
        $conditions = [];

        foreach ($travel_pref_array as $pref) {
            $conditions[] = "FIND_IN_SET('$pref', tu.travel_preferences) > 0";
        }

        $conditions_str = implode(' OR ', $conditions);

        $query = "SELECT tr.*, tu.id AS user_id, tl.location_name, tl.city, tu.name, tu.profile_img, tr.user_id AS review_user_id,
              COUNT(trr.id) AS like_count,
              (SELECT COUNT(*) FROM tbl_review_reaction WHERE user_id = :user_id AND review_id = tr.id) AS user_liked,
              (SELECT COUNT(*) FROM tbl_travel_companion WHERE user_id = :user_id AND companion_id = tr.user_id) AS request_sent
              FROM tbl_review tr
              LEFT JOIN tbl_location tl ON tr.location_id = tl.id 
              LEFT JOIN tbl_user tu ON tu.id = tr.user_id 
              LEFT JOIN tbl_review_reaction trr ON tr.id = trr.review_id 
              WHERE ($conditions_str) AND tr.user_id = :user_id
              GROUP BY tr.id
              ORDER BY tr.id DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':user_id' => $id]); // Only pass current user ID for like and request_sent checks
    }

    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode($locations);
}
?>