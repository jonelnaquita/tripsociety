<?php
include '../inc/session_user.php';
include 'header.php';


if (isset($_GET['id'])) {
    include '../inc/config.php';
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($locations) {
        foreach ($locations as $row) {


            if ($row['profile_img'] == "") {
                $profile_img = '../dist/img/avatar2.png';
            } else {
                $profile_img = '../admin/profile_image/' . $row['profile_img'];
            }

        }
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0"></h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header pb-0">
                            <p class="font-weight-bold">
                                <a type="button" class="btn btn-light bg-white border-0 btn-sm" onclick="history.back()"
                                    class="text-dark">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <img src="<?php echo $profile_img; ?>" alt="User Icon"
                                    class="img-fluid rounded-circle ml-2" style="width: 40px; height: 40px;">
                                &nbsp <?php echo $row['name']; ?>
                            </p>
                        </div>


                        <style>
                            .chat-box {
                                height: 60vh;
                                overflow-y: auto;
                                padding: 10px;
                                background-color: #fff;
                            }

                            .chat {
                                display: flex;
                                align-items: center;
                                margin-bottom: 10px;
                                position: relative;
                            }

                            .chat.incoming {
                                justify-content: flex-start;
                            }

                            .chat.outgoing {
                                justify-content: flex-end;
                            }

                            .profile-img {
                                width: 40px;
                                height: 40px;
                                border-radius: 50%;
                                margin: 0 10px;
                                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
                                object-fit: cover;
                            }

                            .details {
                                max-width: 70%;
                                padding: 10px 10px 10px 10px;
                                border-radius: 15px;
                                position: relative;
                            }

                            .details.bg-primary {
                                background-color: #3f51b5;
                                /* Material Blue */
                            }

                            .details.bg-light {
                                background-color: #e0e0e0;
                                /* Light Gray */
                            }

                            .details p {
                                margin: 0;
                                line-height: 1.5;
                            }

                            .timestamp {
                                font-size: 12px;
                                color: #999;
                                margin-top: 5px;
                            }

                            .no-messages {
                                text-align: center;
                                color: #999;
                                font-style: italic;
                                margin-top: 20px;
                            }

                            .card-footer {
                                border-top: 1px solid #ddd;
                                /* Subtle border to separate from chat area */
                                padding: 10px;
                                background-color: #f8f9fa;
                                /* Light background color for contrast */
                                border-radius: 0 0 8px 8px;
                                /* Rounded bottom corners */
                            }

                            .message-form {
                                display: flex;
                                align-items: center;
                            }

                            .input-group {
                                flex-grow: 1;
                                /* Allow input to take available space */
                            }

                            .message-input {
                                border: 1px solid #ccc;
                                /* Light border for the input field */
                                border-radius: 20px;
                                /* Rounded corners for a modern look */
                                padding: 10px 15px;
                                /* More padding for comfort */
                                transition: border-color 0.3s;
                                /* Smooth transition for focus effect */
                            }

                            .message-input:focus {
                                border-color: #3f51b5;
                                /* Material Blue color on focus */
                                box-shadow: 0 0 5px rgba(63, 81, 181, 0.5);
                                /* Subtle shadow on focus */
                                outline: none;
                                /* Remove default outline */
                            }

                            .btn-send {
                                background-color: #3f51b5;
                                /* Material Blue */
                                border: none;
                                /* Remove default border */
                                border-radius: 50%;
                                /* Circular button */
                                color: white;
                                /* White icon color */
                                width: 40px;
                                /* Fixed width */
                                height: 40px;
                                /* Fixed height */
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: background-color 0.3s;
                                /* Smooth transition for hover effect */
                            }

                            .btn-send:hover {
                                background-color: #303f9f;
                                /* Darker blue on hover */
                            }

                            .btn-send:focus {
                                outline: none;
                                /* Remove default outline */
                                box-shadow: 0 0 5px rgba(63, 81, 181, 0.5);
                                /* Subtle shadow on focus */
                            }
                        </style>
                        <div id="chat-box" class="chat-box"></div>

                        <div class="card-footer">
                            <form id="message-form" method="post" class="message-form mt-3">
                                <div class="input-group">
                                    <input type="hidden" id="outgoing-id" value="<?php echo $_SESSION['user']; ?>">
                                    <input type="hidden" id="incoming-id" value="<?php echo $_GET['id']; ?>">
                                    <input type="text" name="message" id="message-input"
                                        class="form-control message-input" placeholder="Type your message..."
                                        aria-label="Message">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-send">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <br><br><br><br>
            </div>
    </section>
</div>

<?php
include 'footer.php';
?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        var isAtBottom = true;

        // Function to scroll the chat box to the bottom
        function scrollToBottom() {
            var chatBox = $('#chat-box');
            chatBox.scrollTop(chatBox[0].scrollHeight);
        }

        // Function to fetch messages and update the chat box
        function fetchMessages() {
            var outgoingId = $('#outgoing-id').val();
            var incomingId = $('#incoming-id').val();

            $.ajax({
                url: 'chat.php', // Your server-side script to fetch messages
                type: 'POST',
                data: {
                    outgoing_id: outgoingId,
                    incoming_id: incomingId
                },
                success: function (response) {
                    $('#chat-box').html(response); // Update the chat box with new messages
                    if (isAtBottom) {
                        scrollToBottom(); // Scroll to the bottom only if the user is at the bottom
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching messages:', error);
                }
            });
        }

        // Handle form submission
        $('#message-form').on('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting the default way

            // Get the form data
            var outgoingId = $('#outgoing-id').val();
            var incomingId = $('#incoming-id').val();
            var message = $('#message-input').val();

            // Send the data via AJAX
            $.ajax({
                url: 'send_message.php', // Your server-side script to handle message sending
                type: 'POST',
                data: {
                    outgoing_id: outgoingId,
                    incoming_id: incomingId,
                    message: message
                },
                success: function (response) {
                    $('#message-input').val(''); // Clear the input field
                    fetchMessages(); // Refresh the message list
                },
                error: function (xhr, status, error) {
                    console.error('Error sending message:', error);
                }
            });
        });

        // Detect scroll event
        $('#chat-box').on('scroll', function () {
            var chatBox = $(this);
            var scrollHeight = chatBox[0].scrollHeight;
            var scrollTop = chatBox.scrollTop();
            var clientHeight = chatBox.height();

            // Check if the user is at the bottom
            if (scrollTop + clientHeight >= scrollHeight - 10) {
                isAtBottom = true;
            } else {
                isAtBottom = false;
            }
        });

        // Fetch messages when the page loads
        fetchMessages();

        // Fetch messages periodically
        setInterval(fetchMessages, 1000); // Fetch messages every second
    });
</script>