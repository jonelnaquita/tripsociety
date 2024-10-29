<?php
include 'header.php';


if (isset($_GET['id']) && isset($_SESSION['user']) && isset($_GET['search'])) {
    include '../inc/config.php';
    $location_id = $_GET['id'];
    $user_id = $_SESSION['user'];
    $current_date = date('Y-m-d');
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_user_searches 
                                WHERE location_id = :location_id 
                                AND user_id = :user_id 
                                AND DATE(date_created) = :current_date");
    $checkStmt->bindParam(':location_id', $location_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':current_date', $current_date);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO tbl_user_searches (location_id, user_id, date_created) 
                               VALUES (:location_id, :user_id, NOW())");
        $stmt->bindParam(':location_id', $location_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    include '../inc/config.php';
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tbl_location WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($locations) {
        foreach ($locations as $row) {
            $name = $row['location_name'];
            $imageList = $row['image'];
            $imageArray = explode(',', $imageList);
            $firstImage = isset($imageArray[0]) ? $imageArray[0] : 'default.jpg';
        }
    }
}
?>

<div class="content-wrapper">
    <button class="back-button" onclick="goBack()">
        <span class="material-icons">arrow_back</span> Back
    </button>




    <section class="content" style="height:1000px;">
        <div class="container-fluid">
            <style>
                .card-img {
                    height: 250px;
                }

                @media (min-width: 992px) {
                    .card-img {
                        height: 500px;
                    }
                }
            </style>
            <div class="row">
                <div class="col">
                    <h4 class="font-weight-bold mt-4">Explore <?php echo $row['location_name']; ?> <i
                            class="far fa-check-circle" style="font-size:10px;"></i></h4>

                    <div class="card">
                        <img src="../admin/images/<?php echo $firstImage; ?>" class="card-img" alt="Image">

                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col">
                    <h5 class="font-weight-bold">About</h5>
                    <p><?php echo $row['description']; ?></p>
                    <a href="#" id="viewRouteBtn" class="btn btn-outline-dark" style="border-radius:20px;">
                        <i class="far fa-map"></i> View route
                    </a>
                    <a type="button" href="take_tour.php?id=<?php echo $row['id']; ?>" class="btn btn-dark ml-2"
                        style="border-radius:20px;"><i class="fas fa-street-view"></i> Take a tour</a>

                </div>
            </div>



            <?php
            include 'components/explore-destination/write-review.php';
            include 'components/explore-destination/review.php';
            include 'components/explore-destination/hazard.php';
            include 'components/explore-destination/direction2.php';
            ?>
        </div>
    </section>

</div>
<?php
include 'footer.php';
?>