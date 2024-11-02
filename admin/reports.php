<?php
include '../inc/session.php';
include "includes/header.php";

if (isset($_GET['id'])) {
    require_once '../inc/config.php';
    $reportId = $_GET['id'];
    $update_query = "UPDATE tbl_post_report SET unread = 1 WHERE id = :id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindParam(':id', $reportId, PDO::PARAM_INT);
    $update_stmt->execute();
}
?>

<style>
    .post-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin: 20px;
        padding: 15px;
    }

    .profile-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .post-image {
        width: 100%;
        border-radius: 8px;
        margin-top: 10px;
    }

    .reported-by {
        font-size: 12px;
        color: gray;
        margin-top: 10px;
    }

    .post-container {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 5px;
    }

    .image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .image-thumbnail {
        width: 170px;
        height: 170px;
        overflow: hidden;
        border-radius: 5px;
    }

    .album-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5px;
    }

    .profile-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    /**Sweet Alert */
    .swal-popup {
        border-radius: 8px;
        /* Rounded corners */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        /* Subtle shadow */
        font-family: 'Roboto', sans-serif;
        /* Use a clean font */
    }

    .swal-title {
        font-size: 1.2em;
        /* Slightly smaller title */
        color: #333;
        /* Darker text color for better readability */
    }

    .swal-content {
        font-size: 0.9em;
        /* Smaller content font */
        color: #555;
        /* Light gray for the content */
    }

    .swal-confirm,
    .swal-cancel {
        border-radius: 4px;
        /* Rounded buttons */
        padding: 8px 16px;
        /* Compact padding */
        font-size: 0.9em;
        /* Smaller button font */
        transition: background-color 0.3s;
        /* Smooth transition */
    }

    .swal-confirm:hover {
        background-color: #3700B3;
        /* Darker shade on hover */
        color: #fff;
        /* White text */
    }

    .swal-cancel:hover {
        background-color: #C62828;
        /* Darker red on hover */
        color: #fff;
        /* White text */
    }
</style>


<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">

            <div class="report-container row">
            </div>

            <?php include "includes/footer.php"; ?>
        </div>
    </main>

    <script>
        function fetchReports() {
            const urlParams = new URLSearchParams(window.location.search);
            const reportId = urlParams.get('id');
            console.log('Report ID:', reportId);

            // Prepare the data to be sent with the AJAX request
            const requestData = reportId ? { id: reportId } : {};

            $.ajax({
                url: 'api/report/fetch-reports.php',
                type: 'GET',
                data: requestData, // Use the prepared data
                dataType: 'json',
                success: function (data) {
                    $('.report-container').empty();
                    console.log('Fetched data:', data);

                    if (!data || data.length === 0) {
                        $('.report-container').append('<p>No reports found.</p>');
                        return;
                    }

                    data.forEach(function (review) {
                        let imageContainerHtml = '';
                        if (Array.isArray(review.images) && review.images.length > 0) {
                            review.images.forEach(function (image) {
                                const imagePath = review.category === 'Post' ? 'post_image/' + image : 'review_image/' + image;
                                imageContainerHtml += `
                            <a href="${imagePath}" data-fancybox="gallery-${review.post_id}" data-caption="${review.category}">
                                <div class="image-thumbnail">
                                    <img src="${imagePath}" alt="Post Image" class="album-image">
                                </div>
                            </a>`;
                            });
                        }

                        let postHtml = `
                    <div class="col-6">
                        <div class="post-container" data-id="${review.post_id}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center user-info">
                                    <img src="profile_image/${review.profile_img || 'default.png'}" alt="Profile Image" class="profile-image" style="object-fit: cover; margin-right: 10px;">
                                    <div class="ml-3">
                                        <h6 class="mb-0">${review.user_name || 'Unknown User'}</h6>
                                        <small class="text-muted d-block">Location: ${review.post_location || review.review_location_id || 'Unknown Location'}</small>
                                    </div>
                                </div>

                                <div class="report-dropdown">
                                    <button class="btn bg-gradient-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item action-delete" href="#" data-action="delete" data-post-id="${review.report_id}">
                                                <i class="fas fa-trash-alt mr-2"></i> Delete
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item action-ignore" href="#" data-action="ignore" data-post-id="${review.report_id}">
                                                <i class="fas fa-ban mr-2"></i> Ignore
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <span class="badge badge-pill badge-md bg-gradient-warning mt-3">${review.violation || 'No Violation'}</span>
                            <p class="mt-3">${review.category === 'Post' ? review.post_text || '' : review.review_text || ''}</p>
                            <div class="image-container d-flex flex-wrap">
                                ${imageContainerHtml}
                            </div>
                            <div class="reported-by mt-3">
                                <strong>Reported by:</strong> ${review.reporter_name || 'Anonymous'} <br>
                                <small class="text-muted">Reported on ${review.reported_date || 'Unknown Date'}</small>
                            </div>
                        </div>
                    </div>`;

                        $('.report-container').append(postHtml);
                    });

                    // Initialize Fancybox
                    Fancybox.bind("[data-fancybox]", {
                        groupAll: false
                    });
                },
                error: function (err) {
                    console.error('Error fetching reports:', err);
                    $('.report-container').append('<p>Error fetching reports. Please try again later.</p>');
                }
            });
        }


        // Load reports on page load
        $(document).ready(function () {
            fetchReports();

            // Event delegation for Ignore and Delete actions
            $('.report-container').on('click', '.dropdown-item', function (e) {
                e.preventDefault();
                const postId = $(this).data('post-id');
                const action = $(this).data('action');

                const actionText = action === 'ignore' ? 'ignore this report' : 'delete this report';

                // SweetAlert2 confirmation dialog
                Swal.fire({
                    title: `Are you sure you want to ${actionText}?`,
                    text: action === 'ignore' ? "This report will be marked as ignored." : "This report will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6200EE',
                    cancelButtonColor: '#B00020',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'swal-popup',
                        title: 'swal-title',
                        content: 'swal-content',
                        confirmButton: 'swal-confirm',
                        cancelButton: 'swal-cancel'
                    },
                    width: '600px',
                    padding: '20px',
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateReportStatus(postId, action);
                    }
                });
            });
        });

        function updateReportStatus(postId, action) {
            const apiUrl = action === 'ignore' ? 'api/report/ignore-report.php' : 'api/report/delete-report.php';

            $.ajax({
                url: apiUrl,
                type: 'POST',
                dataType: 'json',
                data: { post_id: postId },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        fetchReports(); // Refresh the reports list
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (err) {
                    Swal.fire('Error', 'An error occurred while updating the report status.', 'error');
                    console.error('Error updating report status:', err);
                }
            });
        }
    </script>



</body>