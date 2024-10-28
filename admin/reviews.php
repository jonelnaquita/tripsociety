<style>

</style>

<?php
include 'header.php';
include '../inc/config.php';

if (isset($_SESSION['user'])) {
    $id = $_SESSION['user'];

    function getLocation($pdo)
    {
        $travel_preferences = $_SESSION['travel_preferences'];
        $travel_pref_array = explode(',', $travel_preferences);
        $conditions = [];

        foreach ($travel_pref_array as $pref) {
            $conditions[] = "FIND_IN_SET('$pref', tu.travel_preferences) > 0";
        }

        $conditions_str = implode(' OR ', $conditions);

        $query = "SELECT *, tu.id as user_id, tr.id as id, tr.date_created 
                  FROM tbl_review tr 
                  LEFT JOIN tbl_location tl ON tr.location_id = tl.id 
                  LEFT JOIN tbl_user tu ON tu.id = tr.user_id 
                  WHERE ($conditions_str) 
                  ORDER BY tr.id DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    $locations = getLocation($pdo);


}

?>

<div class="content-wrapper">


    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">


                    <style>
                        .img-container {
                            width: 40px;
                            /* Set fixed width */
                            max-width: 100px;
                            overflow: hidden;
                            /* Hide overflow */
                            display: flex;
                            align-items: center;
                            /* Center image vertically */
                            justify-content: center;
                            /* Center image horizontally */
                        }

                        .img-container img {
                            width: 100%;
                            /* Scale the image to fit the container */
                            height: 40px;
                            /* Maintain aspect ratio */
                            object-fit: cover;
                            /* Ensure the image covers the container */
                        }
                    </style>
                    <br>
                    <?php
                    if (isset($_SESSION['user'])) {
                        foreach ($locations as $location) {
                            $imageList = $location['image'];
                            $imageArray = explode(',', $imageList);
                            $firstImage = isset($imageArray[0]) ? $imageArray[0] : 'default.jpg';
                            $dateCreated = $location['date_created'];
                            $date = new DateTime($dateCreated);
                            $formattedDate = $date->format('m/d/Y');



                            $stmt = $pdo->prepare("
                SELECT COUNT(*) AS count
                FROM tbl_travel_companion
                WHERE status = 'Accepted' AND user_id = :user_id
                
            ");
                            $stmt->bindParam(':user_id', $_SESSION['user'], PDO::PARAM_INT);
                            $stmt->execute();
                            $count1 = $stmt->fetchColumn();

                            $stmt = $pdo->prepare("
                SELECT COUNT(*) AS count
                FROM tbl_travel_companion
                WHERE status = 'Accepted' AND companion_id = :companionId 
                
            ");
                            $stmt->bindParam(':companionId', $_SESSION['user'], PDO::PARAM_INT);
                            $stmt->execute();
                            $count2 = $stmt->fetchColumn();

                            $count = $count1 + $count2;
                            ?>
                            <style>
                                .img-container1 {
                                    width: 100%;
                                    height: 0;
                                    padding-top: 100%;
                                    /* Aspect ratio of 1:1 */
                                    position: relative;
                                    overflow: hidden;
                                }

                                .img-container1 img {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                }
                            </style>
                            <div class="card mt-3 elevation-2">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-auto" style="margin-top:-2px;">
                                            <div class=" ml-2">

                                                <?php
                                                if ($location['profile_img'] == "") {
                                                    echo '<a href="profile.php?id=' . $location['user_id'] . '"><img src="../dist/img/avatar2.png" style="width:40px;" class="img-circle" ></a>';
                                                } else {
                                                    echo '<a href="profile.php?id=' . $location['user_id'] . '">
        <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%;">
            <img src="../admin/profile_image/' . $location['profile_img'] . '" class="img-fluid" style="min-width: 100%; min-height: 100%; object-fit: cover;" alt="Profile Image">
        </div>
      </a>';

                                                }
                                                ?>
                                            </div>

                                        </div>
                                        <div class="col" style="margin-top:-4px; margin-left:-10px;">
                                            <h6 class="font-weight-bold">
                                                <?php echo $location['name'] ?>         <?php if ($location['status'] == 1): ?>
                                                    <i class="fas fa-check-circle" style="color: #582fff; margin-left: 3px;"
                                                        title="Verified"></i>
                                                <?php endif; ?>
                                            </h6>

                                            <h6 style="margin-top:-7px; font-size:12px;" class="text-dark"><i
                                                    class="fas fa-map-marker-alt"></i>
                                                <?php echo $location['location_name'] . ', ' . $location['city']; ?></h6>
                                            <div style="margin-top:-7px;">
                                                <?php
                                                $rating = (float) $location['rating'];
                                                $fullStars = floor($rating);
                                                $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                                $emptyStars = 5 - ($fullStars + $halfStar);
                                                ?>
                                                <div class="star-rating">
                                                    <?php
                                                    for ($i = 0; $i < $fullStars; $i++) {
                                                        echo '<i class="fas fa-star text-warning"></i>';
                                                    }
                                                    if ($halfStar) {
                                                        echo '<i class="fas fa-star-half-alt"></i>';
                                                    }
                                                    for ($i = 0; $i < $emptyStars; $i++) {
                                                        echo '<i class="far fa-star text-warning"></i>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="ml-2 mr-2"><?php echo $location['review']; ?></p>

                                    <div class="container">
                                        <div class="row">
                                            <?php
                                            $imagesString = $location['images'];
                                            $imagesArray = explode(',', $imagesString);

                                            // Check if there are images and render accordingly
                                            if (!empty($imagesArray) && $imagesArray[0] !== ""): // Check for non-empty image array
                                                foreach ($imagesArray as $file): ?>
                                                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                                                        <!-- Adjusted column sizes for better responsiveness -->
                                                        <div class="d-flex justify-content-center"
                                                            style="height: 0; padding-bottom: 100%; position: relative;">
                                                            <img src="../admin/review_image/<?php echo htmlspecialchars($file); ?>"
                                                                alt="Image" class="img-fluid rounded"
                                                                style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; object-fit: cover;"
                                                                data-toggle="modal" data-target="#imageModal"
                                                                data-src="../admin/review_image/<?php echo htmlspecialchars($file); ?>"
                                                                onclick="previewImage(this.dataset.src)">
                                                        </div>
                                                    </div>
                                                <?php endforeach;
                                            endif; ?>
                                        </div>
                                    </div>




                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col ml-auto text-left">
                                            <p class="text-secondary" style="font-size:15px;">written
                                                <?php $dateTime = new DateTime($dateCreated);
                                                $formattedDate = $dateTime->format('F j, Y g:i A');

                                                echo $formattedDate;
                                                ?>
                                            </p>
                                        </div>
                                        <div class="col ml-auto text-right">
                                            <button class="btn btn-light btn-sm mr-2"
                                                style="border-radius:20px; font-size:13px; margin-top:-2px;">
                                                <img src="../img/companion.png" style="width:15px;">
                                                <span class="companion-count">0</span>
                                                <!-- Updated class -->
                                            </button>
                                            <span class="text-dark" style="font-size:13px;">
                                                <i class="far fa-thumbs-up text-dark"></i>
                                                <span class="reaction-count">0</span> <!-- Updated class -->
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>



                </div>
            </div>


        </div><br><br><br>
    </section>

</div>

<?php
include 'footer.php';
?>
<?php
if (isset($_SESSION['user'])) {
    ?>
    <div style="position:fixed; bottom:0; right:0; margin-bottom:80px; z-index:50; margin-right:15px;">
        <a type="button" href="write_review.php" class="btn btn-light p-3 text-white shadow"
            style="border-radius:50px; background-color:#002D62;"><i class="far fa-edit fa-2x"></i></a>
    </div>
<?php } else {
    ?>
    <div style="position:fixed; bottom:0; right:0; margin-bottom:80px; z-index:50; margin-right:15px;">
        <a type="button" href="login.php" class="btn btn-light p-3 text-white shadow"
            style="border-radius:50px; background-color:#002D62;"><i class="far fa-edit fa-2x"></i></a>
    </div>

    <?php
} ?>
<!-- uPDATE sTATUS Modal-->
<div class="modal fade" id="disclaimerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">


            <div class="modal-body m-2">
                <h3 class="text-center font-weight-bold">Disclaimer</h3>
                <h5 class="text-center font-weight-bold">Safety of Accepting a Travel Companion</h5>
                <p>By choosing to accept a travel companion, you acknowledge and agree that you are solely responsible
                    for your personal safety. We recommend conducting thorough background checks, meeting in public
                    places before traveling, and informing family or friends of your travel plans and companion details.
                    Our platform does not vet travel companions and cannot guarantee their trustworthiness. Use caution
                    and good judgment when making travel arrangements. We are not liable for any incidents, accidents,
                    or disputes that may arise from traveling with a companion met through our service.</p>
                <div class="text-center">
                    <a type="button" href="travel_companion.php" class="btn btn-primary">Proceed</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // Fetch counts when the page loads
        fetchCounts();

        function fetchCounts() {
            $.ajax({
                url: 'api/review/fetch-companion-reaction-number.php',
                type: 'GET',
                data: {
                    fetch_companion_count: true,
                    fetch_reaction_count: true
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    // Update the counts in your HTML
                    $('.companion-count').text(data.companion_count);
                    $('.reaction-count').text(data.reaction_count);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching counts:', error);
                }
            });
        }
    });
</script>

<!-- Bootstrap Modal for image preview -->
<div class="modal fade" id="customImageModal" tabindex="-1" role="dialog" aria-labelledby="customImageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content transparent-modal">
            <div class="modal-body">
                <img id="modalImage" src="" alt="Preview" class="img-fluid" style="border-radius: 10px;">
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                style="position: absolute; top: 10px; right: 10px; color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<script>
    function previewImage(src) {
        const modalImage = document.getElementById("modalImage");
        modalImage.src = src;
        $('#customImageModal').modal('show'); // Show the Bootstrap modal
    }
</script>

<!-- Add custom styles for the transparent modal -->
<style>
    .transparent-modal {
        background: rgba(0, 0, 0, 0.8);
        /* Semi-transparent background */
        border: none;
        /* Remove border for a cleaner look */
        border-radius: 10px;
        /* Rounded corners */
    }

    .transparent-modal .modal-body {
        padding: 0;
        /* Remove padding for a minimalist design */
    }

    .transparent-modal .close {
        font-size: 30px;
        /* Larger close button */
    }
</style>