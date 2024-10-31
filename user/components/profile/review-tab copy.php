<div class="tab-pane fade" id="link2" role="tabpanel" aria-labelledby="link2-tab">
    <label class="font-weight-bold mt-2">Details</label>

    <div class="input-group" style="margin-top:-10px;">
        <div class="input-group-prepend">
            <span class="input-group-text bg-transparent border-0"><i class="fas fa-map-marker-alt"></i></span>
        </div>
        <input class="form-control form-control-border bg-transparent"
            value="<?php echo !empty($row['location']) ? $row['location'] : 'No City Selected'; ?>" readonly>
    </div>
    <br>

    <?php
    if (isset($_GET['id'])) {
        include '../inc/config.php';
        $userId = $_GET['id']; // Assuming user ID is stored in session
    
        // Fetch posts with user information
        $pdo_statement = $pdo->prepare("SELECT *, p.date_created as date, p.id as id, tl.location as location 
                                FROM tbl_review p 
                                LEFT JOIN tbl_user u ON u.id = p.user_id 
                                LEFT JOIN tbl_location tl ON tl.id = p.location_id
                                where p.user_id = " . $userId . "
                                ORDER BY p.id DESC");
        $pdo_statement->execute();
        $posts = $pdo_statement->fetchAll();

        if (!empty($posts)) {


            foreach ($posts as $post) {
                $date = $post['date'];
                $datePosted = new DateTime($date);
                $now = new DateTime();

                $interval = $datePosted->diff($now);
                $timeDifference = '';
                if ($interval->y > 0) {
                    $timeDifference = $interval->y . 'y';
                } elseif ($interval->m > 0) {
                    $timeDifference = $interval->m . 'm';
                } elseif ($interval->days > 0) {
                    $timeDifference = $interval->days . 'd';
                } elseif ($interval->h > 0) {
                    $timeDifference = $interval->h . 'h';
                } elseif ($interval->i > 0) {
                    $timeDifference = $interval->i . 'm';
                } else {
                    $timeDifference = $interval->s . 's';
                }


                $dateTimeString = $date; // Assume $date contains the date and time
                $dateTime = new DateTime($dateTimeString);
                $formattedDate = $dateTime->format('d/m/Y');

                $image_statement = $pdo->prepare("SELECT images FROM tbl_review WHERE id = ?");
                $image_statement->execute([$post['id']]);
                $post_images = $image_statement->fetchColumn();
                $imageFiles = explode(',', $post_images);
                $city = $post['city'];
                $maxLength = 13;
                if (strlen($city) > $maxLength) {
                    $city = substr($city, 0, $maxLength) . '...';
                }

                ?>
                <div class="row" style="margin-bottom:-10px; ">
                    <div class="col">
                        <div class="card elevation-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto" style="margin-top:5px;">
                                        <?php
                                        if ($post['profile_img'] == "") {
                                            echo '<a href="profile.php?id=' . $post['user_id'] . '"><img src="../dist/img/avatar2.png"  class="img-circle elevation-2"  style="width:40px; margin-top:-4px;"></a>';
                                        } else {
                                            echo '<a href="profile.php?id=' . $post['user_id'] . '"><div><img src="../admin/profile_image/' . $post['profile_img'] . '" class="img-circle elevation-2"  style="width:40px; height:40px; margin-top:-4px;"></div></a>';
                                        }
                                        ?>
                                    </div>

                                    <div class="col-8" style="margin-left:-10px;">
                                        <p class="font-weight-bold">
                                            <?php echo htmlspecialchars($post['name']); ?>
                                        </p>
                                        <h6 style="margin-top:-17px; font-size:13px" class="text-dark">
                                            <i class="fas fa-map-marker-alt mr-1"></i><?php echo $post['location_name']; ?>
                                            • <?php echo $city; ?>
                                        </h6>
                                    </div>


                                </div>

                                <div class="row mt-2">
                                    <div class="col">
                                        <div>
                                            <p style="font-size:14px;line-height:15px;">
                                                <?php echo $post['review']; ?>
                                            </p>

                                        </div>
                                    </div>
                                </div>



                                <?php if (!empty($post_images)): // Only render the row if there are image files ?>
                                    <div class="row">
                                        <?php foreach ($imageFiles as $file): ?>
                                            <div class="col-6 col-md-4 col-lg-3 mb-3">
                                                <div class="d-flex justify-content-center"
                                                    style="height: 0; padding-bottom: 100%; position: relative;">
                                                    <img src="../admin/review_image/<?php echo htmlspecialchars($file); ?>" alt="Image"
                                                        class="img-fluid rounded"
                                                        style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; object-fit: cover;"
                                                        data-toggle="modal" data-target="#imageModal"
                                                        data-src="../admin/review_image/<?php echo htmlspecialchars($file); ?>">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <!-- Nothing will be displayed if there are no images -->
                                <?php endif; ?>

                            </div>
                            <div class="card-footer card-outline card-light" style=" margin-top:-30px;">
                                <div class="row">
                                    <div class="col">
                                        <p class="text-muted" style="font-size:13px;">written on
                                            <?php echo $formattedDate; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>

</div>