<?php
include '../inc/session.php';
include "includes/header.php"; ?>

<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">

            <div class="row mt-5">
                <div class="col-lg-6 m-auto">
                    <div class="card p-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs"
                                        role="tab" aria-controls="profile-tabs" aria-selected="true">
                                        <span class="material-symbols-rounded align-middle mb-1">
                                            badge
                                        </span>
                                        My Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#password-tabs"
                                        role="tab" aria-controls="password-tabs" aria-selected="false">
                                        <span class="material-symbols-rounded align-middle mb-1">
                                            laptop
                                        </span>
                                        Password
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <!-- My Profile Tab -->
                            <div class="tab-pane fade show active" id="profile-tabs" role="tabpanel"
                                aria-labelledby="profile-tabs-tab">
                                <div class="text-center mt-5">
                                    <h5>Update Profile</h5>
                                </div>

                                <form id="updateForm">
                                    <div class="row ml-5 mr-5">
                                        <div class="col mt-3">
                                            <label for="">Username</label>
                                            <div class="input-group input-group-outline my-3">
                                                <input type="text" placeholder="@user" class="form-control"
                                                    name="username" id="username">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row ml-5 mr-5">
                                        <div class="col mt-2">
                                            <label for="">Email Address</label>
                                            <div class="input-group input-group-outline my-3">
                                                <input type="email" class="form-control" name="email" id="email">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row m-auto text-center">
                                        <div class="col-lg-12">
                                            <button type="submit" name="update_account" class="btn btn-secondary">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <!-- Change Password Tab -->
                            <div class="tab-pane fade" id="password-tabs" role="tabpanel"
                                aria-labelledby="password-tabs-tab">
                                <div class="text-center mt-5">
                                    <h5>Reset Password</h5>
                                </div>

                                <form action="../inc/function.php" method="POST" id="resetPasswordForm">
                                    <div class="row ml-5 mr-5">
                                        <div class="col mt-2">
                                            <label for="">Email Address</label>
                                            <div class="input-group input-group-outline my-3">
                                                <input type="email" class="form-control" name="email-reset"
                                                    id="email-reset" required readonly>
                                            </div>
                                            <p style="font-size:16px; line-height: 1.3;">We recommend using a password
                                                manager or creating a unique password that contains 10 characters and a
                                                special character.</p>
                                            <div class="float-right mt-2">
                                                <button type="submit" name="send_reset_password"
                                                    class="btn btn-outline-dark mr-auto btn-sm">
                                                    <i class="fas fa-envelope"></i> Verify Email
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <?php include "includes/footer.php"; ?>
        </div>
    </main>
</body>

<script>
    $(document).ready(function () {
        // Fetch user data based on session admin
        $.ajax({
            url: 'api/settings/fetch-admin-data.php', // Path to your PHP file
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                // Populate the form fields with the fetched data
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#email-reset').val(data.email);
            },
            error: function (xhr, status, error) {
                console.error('Error fetching user data:', error);
            }
        });
    });

</script>

<script>
    $(document).ready(function () {
        $('#updateForm').on('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting normally

            $.ajax({
                type: 'POST',
                url: 'api/settings/update-admin.php', // Your PHP file to handle the update
                data: $(this).serialize(), // Serialize the form data
                dataType: 'json', // Expect a JSON response
                success: function (response) {
                    if (response.success) {
                        toastr.success('Profile updated successfully!');
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText); // Log error response
                    alert('An error occurred while updating the profile.');
                }
            });
        });
    });
</script>