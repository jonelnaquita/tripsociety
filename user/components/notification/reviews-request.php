<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <div class="card">
        <?php

        include '../inc/config.php';
        $stmt = $pdo->prepare("SELECT * FROM tbl_review tr LEFT JOIN tbl_location tl ON tl.id = tr.location_id LEFT JOIN tbl_user tu ON tu.id = tr.user_id ORDER BY tr.id DESC");
        $stmt->execute();
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="row">
            <div class="col">

                <h6 class="font-weight-bold ml-2 mt-2 mb-2">Latest</h6>
                <?php
                // include 'fetch_locations.php';
                if (!empty($locations)) {
                    foreach ($locations as $location) {
                        $images = $location['images'];
                        $imageArray = explode(',', $images);
                        $firstImage = isset($imageArray[0]) ? trim($imageArray[0]) : null;
                        if ($location['profile_img'] == "") {
                            $profile_img = '../dist/img/avatar2.png';
                        } else {
                            $profile_img = '../admin/profile_image/' . $location['profile_img'];
                        }
                        echo '
                        <div class="col-md-12 mb-3">
                            <div class="card elevation-2" style="background-color:#6CB4EE;">
                                <div class="card-body">
                                <div class="row">
                                <div class="col-auto">
                                <img src="' . $profile_img . '" class="img-circle" style="margin-top:-12px; width:20px;">
                                </div>
                                <div class="col-auto" style="margin-left:-10px;">
                                <h6 class="font-weight-bold" style="font-size:14px;">' . $location['name'] . '</h6>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-8 m-auto">
                                   <p class="card-text" style="font-size:13px; line-height: 1.2;">' . htmlspecialchars($location['review']) . '</p>
                                </div>
                                    <div class="col-4 mr-auto text-right">
                                   <p class="card-text"><img src="../admin/review_image/' . $firstImage . '" style="width:70px; height:60px;"></p>
                                </div>
                                </div>
                                    
                                    
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p>No locations found.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>