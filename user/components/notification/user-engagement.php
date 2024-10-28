<style>
    body {
        margin-top: 20px;
        background-color: #f0f2f5;
    }

    .dropdown-list-image {
        position: relative;
        height: 2.5rem;
        width: 2.5rem;
    }

    .dropdown-list-image img {
        height: 2.5rem;
        width: 2.5rem;
    }

    .btn-light {
        color: #2cdd9b;
        background-color: #e5f7f0;
        border-color: #d8f7eb;
    }
</style>


<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <div class="box shadow-sm rounded bg-white mb-3">
        <div class="box-body p-0">
            <!-- Dynamic content will be loaded here -->
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        var user_id = <?php echo $_SESSION['user']; ?>; // Assuming user ID is stored in session

        // Make an AJAX request to fetch the post interactions
        $.ajax({
            url: 'api/notification/fetch-notification.php',
            type: 'POST',
            data: { user_id: user_id },
            dataType: 'json',
            success: function (response) {
                // Clear any existing content
                $('#home .box-body').empty();

                // Loop through notifications
                if (response.notifications.length > 0) {
                    $.each(response.notifications, function (index, notification) {
                        var notificationHtml;
                        if (notification.type === 'reaction') {
                            notificationHtml = `<div class="p-3 d-flex align-items-center bg-light border-bottom osahan-post-header">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="../admin/profile_image/${notification.profile_img}" alt />
                    </div>
                    <div class="mr-3">
                        <div><span class="font-weight-bold">${notification.name}</span> reacted on your post.</div>
                    </div>
                    <span class="ml-auto mb-auto">
                        <br />
                        <div class="text-right text-muted pt-1">${notification.elapsed_time}</div>
                    </span>
                </div>`;
                        } else if (notification.type === 'comment') {
                            notificationHtml = `<div class="p-3 d-flex align-items-center bg-light border-bottom osahan-post-header">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="../admin/profile_image/${notification.profile_img}" alt />
                    </div>
                    <div class="mr-3">
                        <div><span class="font-weight-bold">${notification.name}</span> commented "<span class="font-weight-bold">${notification.comment_text}</span>" on your post.</div>
                    </div>
                    <span class="ml-auto mb-auto">
                        <br />
                        <div class="text-right text-muted pt-1">${notification.elapsed_time}</div>
                    </span>
                </div>`;
                        }
                        $('#home .box-body').append(notificationHtml);
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error fetching notifications:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    });
</script>