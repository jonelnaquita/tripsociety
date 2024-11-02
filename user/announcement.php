<?php
include '../inc/session_user.php';
include 'header.php';
?>
<style>
    .announcement-card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
    }

    .announcement-title {
        font-size: 1.5em;
        color: #333;
    }

    .date-posted {
        font-size: 0.9em;
        color: #757575;
    }

    .announcement-description {
        color: #333;
        line-height: 1.5;
    }
</style>

<div class="content-wrapper">
    <section class="row">
        <div class="container">
            <div class="card announcement-card mt-4">
                <div class="card-body mt-3">
                    <div class="container mt-5">
                        <h5 class="font-weight-bold"><span class="badge badge-primary">Announcement</span></h5>
                    </div>
                    <h2 class="announcement-title font-weight-bold">Loading...</h2>
                    <p class="date-posted">Posted on: <span>Loading...</span></p>
                    <img src="https://via.placeholder.com/400x200" alt="Announcement"
                        class="image-container img-fluid mb-3">
                    <p class="announcement-description">Loading...</p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const announcementId = urlParams.get('id');

        if (announcementId) {
            $.ajax({
                url: 'api/announcement/fetch-announcement.php', // Adjust the path accordingly
                type: 'GET',
                data: { id: announcementId },
                dataType: 'json',
                success: function (data) {
                    if (data.title) {
                        $('.announcement-title').text(data.title);
                        $('.announcement-description').text(data.description);
                        $('.date-posted span').text(data.formatted_date);

                        // Set the image source to the correct path
                        const imagePath = data.image ? `../admin/announcement/${data.image}` : 'https://via.placeholder.com/400x200';
                        $('.image-container').attr('src', imagePath);
                    } else {
                        $('.announcement-description').text('No announcement found.');
                    }
                },
                error: function (err) {
                    console.error('Error fetching announcement:', err);
                    $('.announcement-description').text('Error fetching announcement. Please try again later.');
                }
            });
        } else {
            $('.announcement-description').text('No announcement ID provided.');
        }
    });
</script>


<?php
include 'footer.php';
?>