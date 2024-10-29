<div class="container-fluid">
    <br>

    <?php if (isset($_SESSION['user'])) { ?>

        <form action="../inc/function.php" id="postForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="hiddenImages" name="images">
            <input type="hidden" id="hiddenPost" name="post">
            <input type="hidden" id="hiddenLocation" name="location">

            <div class="card mb-1 elevation-3">
                <div class="row align-items-center p-1">
                    <div class="col-auto" style="margin-left:13px;">
                        <?php
                        if ($_SESSION['profile_img'] == "") {
                            echo '<img src="../dist/img/avatar2.png" class="img-circle elevation-2" style="width: 40px;">';
                        } else {
                            echo '<img src="../admin/profile_image/' . $_SESSION['profile_img'] . '" class="img-circle elevation-2" style="width: 40px; height:40px;">';
                        }
                        ?>
                    </div>
                    <div class="col" style="margin-left:-15px;">
                        <div class="textarea-container">
                            <textarea class="form-control postDetail form-control-border"
                                placeholder="Tell me about your adventure"></textarea>
                        </div>
                    </div>
                </div>

                <div id="bottomSheet" class="bottom-sheet shadow">
                    <div class="content">
                        <div class="row align-items-center mt-3">
                            <!-- Back Button -->
                            <div class="col-4 text-left">
                                <button type="button" class="btn btn-default bg-transparent border-0" id="closeSheet">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <!-- Title -->
                            <div class="col-4 text-center">
                                <p class="mb-0">Create Post</p>
                            </div>
                            <!-- Publish Button -->
                            <div class="col-4 text-right">
                                <button type="submit" name="add_post"
                                    class="btn btn-default btn-custom font-weight-bold shadow-sm" id="publishButton">
                                    <i class="fas fa-calendar-check"></i> Publish
                                </button>
                            </div>
                        </div>
                        <hr>

                        <div class="row mt-2">
                            <div class="col-auto m-auto text-center">
                                <?php
                                if ($_SESSION['profile_img'] == "") {
                                    echo '<img src="../dist/img/avatar2.png" class="img-circle elevation-2" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">';
                                } else {
                                    echo '<img src="../admin/profile_image/' . $_SESSION['profile_img'] . '" class="img-circle elevation-2" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">';
                                }
                                ?>
                            </div>

                            <div class="col mt-3">
                                <p><?php echo $_SESSION['name']; ?></p>
                                <div style="float:right; margin-top:-34px;">
                                    <h6 style="font-size:15px;"><span id="selectedLocation"></span></h6>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-left:37px; margin-top:-10px;">
                            <div class="col">
                                <input type="file" id="imageInput" name="images[]" multiple accept="image/*"
                                    style="display: none;">
                                <button class="btn btn-default btn-custom" type="button" id="addPhotoButton">
                                    <i class="far fa-images" style="font-size:12px;"></i>&nbsp; Add photo
                                </button>
                                <input type="hidden" id="imagePaths" name="imagePaths">

                                <button class="btn btn-default btn-custom ml-2" type="button" data-toggle="modal"
                                    data-target="#pinMapModal" style="font-size:12px;">
                                    <i class="fas fa-map-pin" style="font-size:12px;"></i>&nbsp; Add location
                                </button>
                            </div>
                        </div>

                        <div class="row ml-2 mr-2">
                            <div class="col">
                                <textarea class="form-control border-0" id="messageTextarea" name="post" rows="6"
                                    placeholder="Tell me about your adventure"></textarea>
                            </div>
                        </div>

                        <!-- Image Preview Container -->
                        <div class="row mt-3">
                            <div class="col">
                                <div class="image-preview" id="imagePostPreviewContainer">
                                    <!-- Image preview elements with delete button will be appended here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php } ?>
</div>