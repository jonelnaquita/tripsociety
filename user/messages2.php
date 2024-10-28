<?php 
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

            
            if($row['profile_img']==""){
                $profile_img = '../dist/img/avatar2.png';
            }else{
                $profile_img = '../admin/profile_image/'.$row['profile_img'];
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
                <a type="button" class="btn btn-light bg-white border-0 btn-sm" onclick="history.back()" class="text-dark">
                    <i class="fas fa-arrow-left"></i>
                </a>
             <img src="<?php echo $profile_img; ?>" alt="User Icon" class="img-fluid rounded-circle ml-2" style="width: 40px; height: 40px;">
                &nbsp <?php echo $row['name']; ?></p>
              </div>
            <div class="card-body" style="height:700px;">
                
                
                <style>

.chat-box {
    height: 400px;
    overflow-y: scroll;
    border: 1px solid #ddd;
   
}

.chat {
    display: flex;
    align-items: flex-start;
    margin-bottom: 10px;
}

.chat.incoming {
    justify-content: flex-start;
}

.chat.outgoing {
    justify-content: flex-end;
}

.chat img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.details {
    max-width: 70%;
}

.details p {
    margin: 0;
}



                </style>
                    <div id="chat-box" class="chat-box"></div>
                 
     


            <div class="card-footer">
            <form id="message-form" method="post" class="mt-3">
                <div class="input-group">
                    <input type="hidden" id="outgoing-id" value="<?php echo $_SESSION['user']; ?>">
                    <input type="hidden" id="incoming-id" value="<?php echo $_GET['id']; ?>">
                    <input type="text" name="message" id="message-input" class="form-control" placeholder="Message...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </form>
                        
            </div>
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
$(document).ready(function() {
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
            success: function(response) {
                $('#chat-box').html(response); // Update the chat box with new messages
                if (isAtBottom) {
                    scrollToBottom(); // Scroll to the bottom only if the user is at the bottom
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching messages:', error);
            }
        });
    }

    // Handle form submission
    $('#message-form').on('submit', function(event) {
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
            success: function(response) {
                $('#message-input').val(''); // Clear the input field
                fetchMessages(); // Refresh the message list
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
            }
        });
    });

    // Detect scroll event
    $('#chat-box').on('scroll', function() {
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
