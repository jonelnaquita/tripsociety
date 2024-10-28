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
                        <div class="text-center mt-5">
                            <h5>Reset Password</h5>
                        </div>

                        <form method="POST">
                            <div class="row ml-5 mr-5">
                                <div class="col mt-3">
                                    <div class="input-group input-group-outline mb-4">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="new_password">
                                    </div>
                                </div>
                            </div>

                            <div class="row ml-5 mr-5">
                                <div class="col mt-2">
                                    <div class="input-group input-group-outline mb-4">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password">
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row m-auto text-center">
                                <div class="col-lg-12">
                                    <button type="submit" name="reset_password1" class="btn btn-secondary">RESET
                                        PASSWORD</button>
                                </div>
                            </div>
                            <br>
                        </form>

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
        $('form').on('submit', function (event) {
            event.preventDefault();

            // Get form values
            const newPassword = $('input[name="new_password"]').val();
            const confirmPassword = $('input[name="confirm_password"]').val();

            // Check if passwords match
            if (newPassword !== confirmPassword) {
                alert("Passwords do not match!");
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'api/settings/update-password.php', // PHP file to handle password update
                data: { new_password: newPassword },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        toastr.success('Password updated successfully!');
                        $('input[name="new_password"]').val('');
                        $('input[name="confirm_password"]').val('');
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while updating the password.');
                }
            });
        });
    });
</script>