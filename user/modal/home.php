<!-- Image Preview-->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 bg-transparent">
            <div class="position-relative">
                <img src="" id="modalImage" class="img-fluid" alt="Large Image">
                <button type="button" class="close position-absolute" style="top: 10px; right: 10px;"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // When an image is clicked, set the src of the modal image
        $('[data-toggle="modal"]').click(function () {
            var imageSrc = $(this).data('src');
            $('#modalImage').attr('src', imageSrc);
        });
    });
</script>


<!--Select Location-->
<div class="modal fade" id="pinMapModal" tabindex="-1" role="dialog" aria-labelledby="pinMapModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pinMapModalLabel">Select Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Location:</label>
                    <?php
                    include '../inc/config.php';
                    $pdo_statement = $pdo->prepare("SELECT id, location_name FROM tbl_location");
                    $pdo_statement->execute();
                    $locations = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
                    $options = '<option value="" disabled selected>Select a location</option>'; // Add blank option
                    
                    foreach ($locations as $location) {
                        $options .= '<option value="' . htmlspecialchars($location['location_name']) . '">' . htmlspecialchars($location['location_name']) . '</option>';
                    }
                    ?>

                    <!-- HTML part -->
                    <select id="locationSelect" class="form-control" name="location">
                        <?php echo $options; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="addLocation">Add Location</button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Post -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this post?</p>
                <div class="action-buttons mt-4">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-delete" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #select-location {
        z-index: 1060 !important;
        /* Set a higher z-index for select-location modal */
    }
</style>

<!--Comment Section-->
<div class="modal fade" id="addCommentModal" tabindex="-1" role="dialog" aria-labelledby="pinMapModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div id="post_details">

                </div>
                <div id="comment_details">

                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>