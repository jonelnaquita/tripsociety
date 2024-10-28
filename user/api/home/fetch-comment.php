<?php
session_start();
include '../../../inc/config.php'; // Include your PDO database connection file

if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // SQL Query to fetch comments and user info
    $query = "
        SELECT pc.message, pc.date_created, u.profile_img, u.username 
        FROM tbl_post_comment pc 
        JOIN tbl_user u ON pc.user_id = u.id 
        WHERE pc.post_id = :post_id 
        ORDER BY pc.date_created DESC
    ";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();

        // Prepare output
        $output = '';
        $manilaTimezone = new DateTimeZone('Asia/Manila'); // Define Manila timezone

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Create DateTime objects for the posted date and the current date
            $datePosted = new DateTime($row['date_created'], $manilaTimezone);
            $now = new DateTime('now', $manilaTimezone);

            // Calculate the interval
            $interval = $datePosted->diff($now);

            // Determine the time difference string
            $timeDifference = '';
            if ($interval->y > 0) {
                $timeDifference = $interval->y . 'y';
            } elseif ($interval->m > 0) {
                $timeDifference = $interval->m . 'm';
            } elseif ($interval->days > 0) {
                $timeDifference = $interval->days . 'd';
            } elseif ($interval->h > 0) {
                $timeDifference = $interval->h . 'h';
            } elseif ($interval->i > 0) {
                $timeDifference = $interval->i . 'm';
            } else {
                $timeDifference = $interval->s . 's';
            }

            $output .= '
                <div class="d-flex">
                    <img src="' . (!empty($row['profile_img']) ? '../admin/profile_image/' . $row['profile_img'] : 'https://via.placeholder.com/40') . '" 
                         alt="User Comment Picture" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                    <div class="user-info ml-2">
                        <h6 class="mb-0">' . htmlspecialchars($row['username']) . '</h6>
                        <small class="text-muted">@' . htmlspecialchars($row['username']) . ' · ' . $timeDifference . ' ago</small>
                        <p>' . htmlspecialchars($row['message']) . '</p>
                    </div>
                </div>';
        }

        // Output the comments or a message if none exist
        if ($output == '') {
            echo '<p>No comments available.</p>';
        } else {
            echo $output;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log the database error
        echo "Error: " . $e->getMessage();
    }
}

?>