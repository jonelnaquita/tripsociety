<div class="card card-background rounded-0" style="background-image: url('<?php echo $cover_img; ?>');">
    <div class="card-body text-center text-white">
        <div class="mt-3" sty>
            <?php
            if ($row['profile_img'] == "") {
                echo '<img src="../dist/img/avatar2.png" class="img-fluid rounded-circle" style="width: 50px; object-fit: cover;">';
            } else {
                echo '<img src="' . $profile_img . '" class="img-fluid rounded-circle" style="width: 50px; height:50px; object-fit: cover;">';
            }
            ?>
        </div>
        <h6 class="font-weight-bold mt-2" style=" text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">
            <?php echo $_SESSION['name']; ?>
        </h6>
        <p class="mb-0" style="font-size:12px; margin-top:-10px;  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">
            @<?php echo $_SESSION['username']; ?></p>

        <div id="badge-accomplishment"></div>

        <div class="row w-50 m-auto">
            <div class="col text-center">
                <p class="text-white" style=" text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">Posts</p>
                <h6 class="font-weight-bold text-white"
                    style="margin-top:-15px; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">
                    <?php echo $post_count; ?>
                </h6>
            </div>
            <div class="col text-center">
                <p class="text-white" style=" text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">Reviews</p>
                <h6 class="font-weight-bold text-white"
                    style="margin-top:-15px; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">
                    <?php echo $review_count; ?>
                </h6>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Get the user_id from the URL if present, otherwise it will default to session in PHP
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get('id');

        $.ajax({
            url: 'api/badge/fetch-badge-accomplishment.php', // PHP script to fetch badges
            method: 'GET',
            data: { user_id: userId }, // Send user_id if available
            dataType: 'json',
            success: function (data) {
                // Clear the badge container
                $('#badge-accomplishment').empty();

                // Check for any error in response
                if (data.error) {
                    $('#badge-accomplishment').html('<p class="error">' + data.error + '</p>');
                    return;
                }

                // Iterate over badges and append them to #badge-accomplishment
                data.forEach(function (badge) {
                    const badgeElement = `
                    <div class="badge badge-accomplishment" style="background-color: ${badge.color};">
                        <i class="fas ${badge.icon}"></i>
                        <span class="ml-1 badge-text">${badge.badge}</span>
                    </div>
                `;
                    $('#badge-accomplishment').append(badgeElement);
                });
            },
            error: function (xhr, status, error) {
                $('#badge-accomplishment').html('<p class="error">An error occurred. Please try again later.</p>');
            }
        });
    });
</script>

<style>
    .badge-accomplishment {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        color: #fff;
        font-size: 10px;
        font-weight: 500;
        border-radius: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
        cursor: default;
    }

    .badge-accomplishment:hover {
        transform: translateY(-2px);
    }

    .badge-text {
        margin: 0;
    }
</style>