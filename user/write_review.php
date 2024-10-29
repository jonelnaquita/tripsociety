<link rel="stylesheet" href="assets/css/write-review.css">

<?php
include 'header.php';
include '../inc/config.php';
if (isset($_SESSION['user'])) {
    $id = $_SESSION['user'];

    function getLocation($pdo, $id)
    {
        $query = "SELECT *, tr.id as review_id, tr.date_created FROM tbl_review tr 
                  LEFT JOIN tbl_location tl ON tr.location_id = tl.id 
                  WHERE tr.user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $locations = getLocation($pdo, $id);


}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold">Reviews</h5>
                    <a href="write_review2.php" id="openReviewSheetBtn"
                        class="btn btn-primary btn-sm d-flex align-items-center"
                        style="padding: 5px 10px; border-radius: 24px;">
                        <i class="far fa-edit mr-1"></i>
                        Write a review
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="mt-2">
                        <?php
                        foreach ($locations as $location) {
                            $imageList = $location['image'];
                            $imageArray = explode(',', $imageList);
                            $firstImage = isset($imageArray[0]) ? $imageArray[0] : 'default.jpg';
                            $dateCreated = $location['date_created'];
                            $date = new DateTime($dateCreated);
                            $formattedDate = $date->format('m/d/Y');
                            ?>

                            <div class="card mt-3 shadow-sm rounded">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="img-container rounded-circle overflow-hidden mr-3">
                                                <img src="../admin/images/<?php echo htmlspecialchars($firstImage); ?>"
                                                    alt="" class="img-fluid">
                                            </div>
                                            <div>
                                                <h5 class="font-weight-bold mb-0"><?php echo $location['location_name']; ?>
                                                </h5>
                                                <h6 class="text-muted" style="font-size: 13px;">Written on
                                                    <?php echo $formattedDate; ?>
                                                </h6>
                                            </div>
                                        </div>
                                        <button class="btn btn-link text-danger delete-review p-0" title="Delete Review"
                                            data-id="<?php echo $location['review_id']; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                    </div>

                                    <div class="star-rating mb-2">
                                        <?php
                                        $rating = (float) $location['rating'];
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - ($fullStars + $halfStar);

                                        for ($i = 0; $i < $fullStars; $i++) {
                                            echo '<i class="fas fa-star text-warning"></i>';
                                        }
                                        if ($halfStar) {
                                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                        }
                                        for ($i = 0; $i < $emptyStars; $i++) {
                                            echo '<i class="far fa-star text-warning"></i>';
                                        }
                                        ?>
                                    </div>

                                    <p class="mt-3"><?php echo $location['review']; ?></p>

                                    <?php
                                    // Check if there are images
                                    $imagesString = $location['images'];
                                    $imagesArray = explode(',', $imagesString);
                                    if (count($imagesArray) > 0 && !empty($imagesArray[0])) {
                                        echo '<div class="row mt-3">';
                                        foreach ($imagesArray as $index => $image) {
                                            // Only display images that exist
                                            if (!empty(trim($image))) {
                                                echo '<div class="col-4 mb-3">';
                                                echo '<div class="img-container1 rounded overflow-hidden">';
                                                echo '<img src="../admin/review_image/' . htmlspecialchars($image) . '" alt="Image" class="img-fluid">';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        }
                                        echo '</div>'; // Close the row
                                    }
                                    ?>
                                </div>
                            </div>

                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <br><br><br>
</div>
</section>

</div>
<?php
include 'footer.php';
include 'modal/review.php';
?>

<script>
    $(document).ready(function () {
        let reviewIdToDelete;

        // Show the confirmation modal when delete button is clicked
        $('.delete-review').on('click', function () {
            reviewIdToDelete = $(this).data('id'); // Get the review ID from data attribute
            $('#deleteConfirmationModal').modal('show'); // Show the modal
        });

        // Confirm delete action
        $('#confirmDeleteBtn').on('click', function () {
            $.ajax({
                url: 'api/review/delete-review.php', // PHP file to handle deletion
                type: 'POST',
                data: { id: reviewIdToDelete },
                success: function (response) {
                    if (response == 'success') {
                        $('#deleteConfirmationModal').modal('hide'); // Hide modal
                        // Optionally, you can remove the review from the DOM or refresh the page
                        location.reload(); // Reload the page to see the changes
                    } else {
                        alert('Error deleting review. Please try again.'); // Show error message
                    }
                }
            });
        });
    });
</script>