<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog comment-section-modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content comment-section-modal-content">
            <div class="modal-header d-flex align-items-center" style="padding-left: 0; padding-right: 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="margin-left: 0; padding-left: 15px;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title mx-auto" id="commentModalLabel">Add Comment</h5>
                <i class="material-icons" style="font-size: 24px; cursor: pointer;">more_vert</i>
            </div>

            <!-- Modal Body -->
            <div class="modal-body comment-section-modal-body">
                <!-- Social Media Post Template -->
                <div class="post-section mb-3">

                </div>

                <!-- Comment Section -->
                <div id="post-comment-section" class="post-comment-section">
                    <!-- Comments will be loaded here -->
                </div>
            </div>

            <!-- Modal Footer (Comment Area) -->
            <div class="modal-footer">
                <div class="d-flex w-100">
                    <textarea class="form-control" id="comment-input" placeholder="Write a comment..." rows="1"
                        style="resize: none;"
                        oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px';"></textarea>
                    <button class="btn" type="button" id="submit-comment"
                        style="border: none; background: none; color: #007bff; margin-left: 8px;">
                        <i class="material-icons" style="font-size: 24px;">send</i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    // Open modal when comment section button is clicked
    $(document).on('click', '.comment-section', function () {
        $('#commentModal').modal('show');
    });
</script>

<style>
    .square-image {
        width: 100px;
        /* Set the desired width */
        height: 100px;
        /* Set the desired height */
        object-fit: cover;
        /* Ensure the image fits well within the square */
        display: inline-block;
        /* Align images horizontally */
        margin-right: 5px;
        /* Add space between images */
    }

    .image-album {
        display: flex;
        /* Use flex to arrange images in a row */
        overflow-x: auto;
        /* Allow horizontal scrolling */
        padding: 10px 0;
        /* Add some padding */
    }
</style>