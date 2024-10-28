<?php
include '../inc/session.php';
include "includes/header.php";
include '../inc/config.php'; ?>

<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">
            <div class="container-fluid">
                <hr>
                <div class="text-right mb-2">
                    <button class="btn btn-primary shadow" data-bs-toggle="modal"
                        data-bs-target="#addAnnouncementModal">Add
                        news
                        & update</button>
                </div>
                <div class="row">
                    <div class="col">
                        <?php
                        $pdo_statement = $pdo->prepare("SELECT * FROM tbl_announcement");
                        $pdo_statement->execute();
                        $result = $pdo_statement->fetchAll();
                        ?>

                        <?php if (!empty($result)) { ?>
                            <?php foreach ($result as $row) { ?>
                                <div class="card mb-3 p-3 card-outline card-primary">

                                    <!-- Dropdown for actions -->
                                    <div class="dropdown" style="float: right !important; text-align:right;">
                                        <button class="btn btn-primary editAnnouncement" data-id="<?php echo $row['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                            data-description="<?php echo htmlspecialchars($row['description']); ?>"
                                            data-image="<?php echo htmlspecialchars($row['image']); ?>" type="button">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button class="btn btn-light deleteAnnouncement" data-id="<?php echo $row['id']; ?>"
                                            type="button"><i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>

                                    <!-- Announcement content -->
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                                            <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                                        </div>
                                        <div class="col-lg-4 text-center">
                                            <img src="announcement/<?php echo htmlspecialchars($row['image']); ?>"
                                                style="width:250px;" class="img-fluid" alt="Announcement Image">
                                            <h6 class="mt-2">- Administrator</h6>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <p>No announcements available.</p>
                        <?php } ?>

                    </div>
                </div>
            </div>

            <?php include "includes/footer.php"; ?>
        </div>
    </main>
</body>


<div class="modal fade" id="editAnnouncementModal" tabindex="-1" role="dialog"
    aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit Announcement</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../inc/function.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="editAnnouncementId" name="id">
                    <div class="mb-3">
                        <label for="editAnnouncementTitle" class="form-label">Title:</label>
                        <input type="text" class="form-control" id="editAnnouncementTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAnnouncementDescription" class="form-label">Description:</label>
                        <textarea class="form-control" id="editAnnouncementDescription" name="description" rows="3"
                            required></textarea>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="editAnnouncementImage" class="form-label">Image:</label>
                                <input type="file" class="form-control" id="editAnnouncementImage" name="image">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <img id="editAnnouncementImagePreview" class="img-fluid mt-2" style="max-width: 100%;"
                                    alt="Image Preview" />
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="update_announcement" class="btn btn-primary"><i
                                class="fas fa-check"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteAnnouncementModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form action="../inc/function.php" method="POST">
                    <input type="hidden" id="deleteAnnouncementId" name="id">
                    <h6>Are you sure you want to delete this announcement?</h6>
                    <div class="mt-4">
                        <button type="submit" name="delete_announcement" class="btn btn-danger btn-block"><i
                                class="fas fa-trash-alt"></i> Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Add New Announcement or News</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="announcementForm" enctype="multipart/form-data">
                    <div class="col-md-6">
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="announcementTitle" name="title" required>
                        </div>
                    </div>
                    <label class="form-label">Description</label>
                    <div class="input-group input-group-dynamic">
                        <textarea class="form-control" rows="5" id="announcementDescription" name="description" required
                            placeholder="Say a few words about your announcement." spellcheck="false"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="announcementImage" class="form-label">Image</label>
                        <input type="file" class="form-control" id="announcementImage" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <img id="imagePreview" class="img-fluid mt-2" style="max-width: 100%; display: none;">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-gradient-primary">Save Announcement</button>
                        <button type="button" class="btn bg-gradient-light" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).on('click', '.editAnnouncement', function () {
        console.log('Edit button clicked');

        var $card = $(this).closest('.card');
        var id = $card.find('.editAnnouncement').data('id');
        var title = $card.find('.editAnnouncement').data('title');
        var description = $card.find('.editAnnouncement').data('description');
        var image = $card.find('.editAnnouncement').data('image');

        console.log('ID:', id);
        console.log('Title:', title);
        console.log('Description:', description);
        console.log('Image:', image);

        $('#editAnnouncementId').val(id);
        $('#editAnnouncementTitle').val(title);
        $('#editAnnouncementDescription').val(description);

        if (image) {
            $('#editAnnouncementImagePreview').attr('src', 'announcement/' + image).show();
            console.log('Image preview set to:', 'announcement/' + image);
        } else {
            $('#editAnnouncementImagePreview').hide();
            console.log('No image to preview');
        }

        $('#editAnnouncementModal').modal('show');
    });

    // Handle delete button click
    $(document).on('click', '.deleteAnnouncement', function () {
        var id = $(this).closest('.card').find('.deleteAnnouncement').data('id');
        $('#deleteAnnouncementId').val(id);
        $('#deleteAnnouncementModal').modal('show');
    });
</script>

<!-- Add New Announcement -->
<script>
    $(document).ready(function () {
        // Image Preview
        $('#announcementImage').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#imagePreview').hide();
            }
        });

        // AJAX form submission
        $('#announcementForm').on('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                url: 'api/announcement/save-announcement.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response); // Displays response from PHP
                    $('#addAnnouncementModal').modal('hide'); // Close modal
                    $('#announcementForm')[0].reset(); // Clear form
                    $('#imagePreview').hide(); // Hide image preview
                },
                error: function () {
                    alert('An error occurred while saving the announcement.');
                }
            });
        });
    });
</script>