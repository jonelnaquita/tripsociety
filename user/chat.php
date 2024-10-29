<?php
include '../inc/session_user.php';

function timeAgo($timestamp)
{
    $time = strtotime($timestamp);
    $time_diff = time() - $time;
    $seconds = $time_diff;

    $minutes = round($seconds / 60);           // value 60 is seconds
    $hours = round($seconds / 3600);           // value 3600 is 60 minutes * 60 seconds
    $days = round($seconds / 86400);          // value 86400 is 24 * 60 minutes * 60 seconds
    $weeks = round($seconds / 604800);         // value 604800 is 7 * 24 * 60 minutes * 60 seconds

    if ($seconds <= 60) {
        return "Just now";
    } else if ($minutes <= 60) {
        return "$minutes minute(s) ago";
    } else if ($hours <= 24) {
        return "$hours hour(s) ago";
    } else if ($days <= 7) {
        return "$days day(s) ago";
    } else {
        return date('F j, Y', $time);
    }
}

if (isset($_SESSION['user'])) {
    include_once "../inc/config.php";
    $outgoing_id = $_SESSION['user'];
    $incoming_id = $_POST['incoming_id'];

    $output = "";
    $sql = "SELECT *, messages.date_created as date_created FROM tbl_message as messages
            LEFT JOIN tbl_user as users ON users.id = messages.sender_id
            WHERE (receiver_id = :outgoing_id AND sender_id = :incoming_id)
            OR (receiver_id = :incoming_id AND sender_id = :outgoing_id) 
            ORDER BY messages.id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
        $stmt->bindParam(':incoming_id', $incoming_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $formatted_time = timeAgo($row['date_created']);

                if ($row['sender_id'] == $outgoing_id) {
                    // Current user's message
                    $output .= '<div class="chat outgoing p-1">
                                    <div class="details  bg-dark w-100 p-2 rounded" style="margin-top:14px;">
                                        <p class="text-right">' . htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8') . '</p>
                                    </div>
                                    <img src="../admin/profile_image/' . $row['profile_img'] . '" class="ml-2 mt-2 img-circle" alt="" style="width:50px; height:50px;">
                                </div>          
                                <div class="text-center" style="font-size:13px; margin-top:-15px;"><p>' . $formatted_time . '</p></div>
';
                } else {
                    // Other user's message
                    $output .= '<div class="chat incoming">
                                    <img src="../admin/profile_image/' . $row['profile_img'] . '" class="ml-2 mt-2 img-circle" alt="" style="width:50px; height:50px;">
                                    <div class="details  bg-light w-100 p-2 rounded" style="margin-top:14px;">
                                        <p> ' . htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8') . '</p>
                                    </div>
                                </div>
                                <div class="text-center" style="margin-top:-15px; font-size:13px;"><p>' . $formatted_time . '</p></div>';
                }
            }
        } else {
            $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
        }
    } catch (PDOException $e) {
        $output .= '<div class="text">An error occurred while fetching messages: ' . $e->getMessage() . '</div>';
    }

    echo $output;
}
?>