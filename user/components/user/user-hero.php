<div class="row mt-2">
    <div class="col">
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
    </div>
</div>