<?php
session_start();
include_once "../inc/config.php"; // Adjust the path according to your directory structure

$outgoing_id = $_SESSION['user']; // Fetch the outgoing user's ID

// Get the search query from GET request
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "
    SELECT u.id AS user_id, u.name, u.profile_img,
           COALESCE(tc.status, 'Not Travel Companion') AS status,
           m.message, m.date_created
    FROM tbl_user AS u
    LEFT JOIN tbl_travel_companion AS tc
        ON (
            (tc.user_id = u.id AND tc.companion_id = :outgoing_id) 
            OR (tc.companion_id = u.id AND tc.user_id = :outgoing_id)
        )
    LEFT JOIN tbl_message AS m
        ON (
            (m.sender_id = u.id AND m.receiver_id = :outgoing_id)
            OR (m.receiver_id = u.id AND m.sender_id = :outgoing_id)
        )
    WHERE u.name LIKE :search_query
    AND m.date_created = (
        SELECT MAX(m2.date_created)
        FROM tbl_message AS m2
        WHERE (m2.sender_id = u.id AND m2.receiver_id = :outgoing_id)
        OR (m2.receiver_id = u.id AND m2.sender_id = :outgoing_id)
    )
    GROUP BY u.id
    ORDER BY m.date_created DESC;
";


try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $stmt->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() == 0) {
        $output .= "<li class='list-group-item border-0 p-0 mt-2'>No users are available to chat</li>";
    } else {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // For each user, generate the HTML list item
            $user_id = $row['user_id'];
            $user_name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
            $user_img = $row['profile_img'] ? "../admin/profile_image/" . $row['profile_img'] : "../dist/img/avatar5.png";

            // Format the time elapsed
            $time_ago = time_elapsed_string($row['date_created']);
            $latest_message = htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8');

            // Determine the badge text based on the status
            if ($row['status'] == 'Accepted') {
                $badge_text = "Travel Companion";
            } elseif ($row['status'] == 'Requesting') {
                $badge_text = "Requesting";
            } else {
                $badge_text = "Not Travel Companion"; // Default badge text
            }

            // Generate the badge HTML
            $badge_html = "<span class='badge badge-info' style='font-size:10px;'>{$badge_text}</span>";

            $output .= "<li class='list-group-item border-0 p-0 mt-2'>
                            <div class='d-flex w-100 justify-content-between'>
                                <a href='messages2.php?id={$user_id}' class='text-dark'>
                                    <img src='{$user_img}' alt='User Avatar' class='img-circle mr-2' style='width:30px; height:30px; margin-top:-2px;'>
                                </a>
                                <div class='flex-grow-1'>
                                    <h6 class='mb-1' style='font-size:15px;'>
                                        {$user_name} {$badge_html}
                                        <div class='dropdown float-right'>
                                            <button class='btn btn-white btn-sm border-0' type='button' id='dropdownMenuButton{$user_id}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                <i class='fas fa-ellipsis-h'></i>
                                            </button>
                                            <div class='dropdown-menu' style='margin-right:115px;' aria-labelledby='dropdownMenuButton{$user_id}'>
                                                <a class='dropdown-item text-center' style='font-size:13px;' href='#' 
                                                    data-user-id='{$user_id}' data-toggle='modal' data-target='#deleteModal'>Delete</a>
                                            </div>
                                        </div>
                                    </h6>
                                    <h6 style='font-size:15px; margin-top:-5px;'>{$latest_message} - {$time_ago}</h6>
                                </div>
                            </div>
                        </li>";
        }
    }

    echo $output;
} catch (PDOException $e) {
    echo "<li class='list-group-item border-0 p-0 mt-2'>Error fetching users: " . $e->getMessage() . "</li>";
}


// Function to calculate time elapsed
function time_elapsed_string($datetime, $full = false)
{
    // Set the timezone to Philippine Time
    $timezone = new DateTimeZone('Asia/Manila');

    // Get the current time in Philippine Time
    $now = new DateTime('now', $timezone);

    // Create a DateTime object for the provided datetime in Philippine Time
    $ago = new DateTime($datetime, $timezone);

    // Calculate the difference
    $diff = $now->diff($ago);

    // Calculate weeks and adjust days
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [];
    $units = [
        'year' => $diff->y,
        'month' => $diff->m,
        'week' => $diff->w,
        'day' => $diff->d,
        'hour' => $diff->h,
        'minute' => $diff->i,
        'second' => $diff->s,
    ];

    foreach ($units as $key => $value) {
        if ($value) {
            $string[] = $value . ' ' . $key . ($value > 1 ? 's' : '');
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>



<!-- Modal Structure -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="border-radius: 8px; overflow: hidden;">
        <div class="modal-content" style="border-radius: 8px; box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="background-color: #6200ea; color: white; padding: 16px;">
                <h5 class="modal-title" id="deleteModalLabel">Delete Message</h5>
                <button type=" button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 24px; font-size: 16px; color: #555;">
                <p style="margin-bottom: 24px;">Are you sure you want to delete messages to this user?</p>
                <input type="hidden" id="userIdToDelete"> <!-- Hidden input -->
            </div>
            <div class="modal-footer" style="padding: 16px; justify-content: flex-end; border-top: none;">
                <button type="button" class="btn btn-light" data-dismiss="modal"
                    style="margin-right: 10px; border-radius: 4px;">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete"
                    style="border-radius: 4px; background-color: #d32f2f;">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Styling -->
<style>
    .modal-header {
        border-bottom: none;
    }

    .modal-body {
        font-family: 'Roboto', sans-serif;
        letter-spacing: 0.5px;
    }

    .btn-danger {
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.15);
    }

    .btn-light {
        background-color: #f5f5f5;
        color: #757575;
    }

    .close {
        outline: none;
    }
</style>


<!-- JavaScript to handle the modal -->
<script>
    // Show the delete modal and set the user ID
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var userId = button.data('user-id'); // Extract user ID from data attribute

        // Update the hidden input field in the modal
        var modal = $(this);
        modal.find('#userIdToDelete').val(userId); // Set user ID in hidden input
    });

    // Handle the confirm delete action
    $('#confirmDelete').on('click', function () {
        var userId = $('#userIdToDelete').val();
        var senderId = '<?php echo $_SESSION['user']; ?>'; // Get the sender ID from PHP session

        $.ajax({
            url: 'api/chat/delete-message.php', // URL to your PHP script for deletion
            type: 'POST',
            data: {
                sender_id: senderId,
                receiver_id: userId
            },
            success: function (response) {
                response = JSON.parse(response); // Parse the JSON response
                if (response.success) {
                    alert('Message deleted successfully.');
                    location.reload(); // Refresh the page
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('An error occurred while deleting messages.');
            }
        });
    });
</script>