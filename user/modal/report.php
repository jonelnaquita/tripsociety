<!-- Report Post Modal -->
<div class="modal fade" id="reportPostModal" tabindex="-1" role="dialog" aria-labelledby="reportPostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content report-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportPostModalLabel">Report This Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body report-body">
                <form id="reportPostForm">
                    <p>Please select the reason for reporting this post:</p>
                    <div class="form-group">
                        <select id="violationSelect" class="form-control" required>
                            <option value="">Select a violation</option>
                            <option value="Hate speech or discriminatory remarks">Hate speech or discriminatory remarks
                            </option>
                            <option value="Harassment or bullying">Harassment or bullying</option>
                            <option value="Spam or irrelevant content">Spam or irrelevant content</option>
                            <option value="Posting false or misleading information">Posting false or misleading
                                information</option>
                            <option value="Illegal activities or content">Illegal activities or content</option>
                            <option value="Impersonation or misrepresentation">Impersonation or misrepresentation
                            </option>
                            <option value="Inappropriate or explicit content">Inappropriate or explicit content</option>
                            <option value="Violations of privacy">Violations of privacy</option>
                        </select>
                    </div>
                    <input type="hidden" name="post_id" id="postIdInput" class="form-control" readonly>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user']; ?>" class="form-control">
                    <!-- Hidden input for user ID -->
            </div>
            <div class="modal-footer">
                <button type="button" id="submitReport" class="btn btn-primary btn-sm">Report Post</button>
            </div>
            </form>
        </div>
    </div>
</div>