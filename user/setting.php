<?php
include 'header.php';
?>

<style>
    .nav-tabs .nav-link {
        border: none;
        /* Remove border for a cleaner look */
        border-bottom: 2px solid transparent;
        /* Initial border for active tab */
        color: #495057;
        /* Default text color */
    }

    .nav-tabs .nav-link.active {
        border-bottom: 2px solid #582fff;
        /* Active tab underline color */
        font-weight: bold;
        /* Bold active tab */
        color: #582fff;
        /* Active tab text color */
    }

    .form-control {
        border: 1px solid #ced4da;
        /* Default input border */
        border-radius: 4px;
        /* Rounded corners for input fields */
        transition: border-color 0.3s;
        /* Smooth transition for border color */
    }

    .form-control:focus {
        border-color: #582fff;
        /* Focused input border color */
        box-shadow: 0 0 5px rgba(88, 47, 255, 0.5);
        /* Focused input shadow */
    }

    .btn-custom {
        border-radius: 50px;
        /* Rounded button */
        background-color: #582fff;
        /* Button color */
        color: #fff;
        /* Button text color */
        transition: background-color 0.3s;
        /* Smooth button color transition */
    }

    .btn-custom:hover {
        background-color: #4a23e1;
        /* Darker shade on hover */
    }

    h6 {
        font-weight: 500;
        /* Slightly lighter weight for headings */
        color: #6c757d;
        /* Muted color for headings */
    }
</style>

<link rel="stylesheet"
    href="https://adminlte.io/themes/v3/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">

<div class="content-wrapper">


    <section class="content">
        <div class="container-fluid">

            <div class="row mt-2">
                <div class="col text-right">
                    <a href="account_verification.php" class="mt-3 text-dark btn btn-default"><i
                            class="fas fa-id-card-alt"></i> Verify Account</a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <div class="card mt-2">
                        <div class="card-body">
                            <h6 class="text-muted font-weight-bold">Notifications</h6>
                            <div class="row">
                                <div class="col-lg-8 col-8">
                                    <h5 class="font-weight-bold mt-2">Push Notification</h5>
                                </div>
                                <div class="col-lg-4 col-4">
                                    <div class="form-group mt-2">
                                        <input type="checkbox" checked data-bootstrap-switch>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="container mt-5">
                                <h2 class="text-center mb-4">Update Account</h2>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="update-account-tab" data-toggle="tab"
                                            href="#update-account" role="tab" aria-controls="update-account"
                                            aria-selected="true">Information</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="update-password-tab" data-toggle="tab"
                                            href="#update-password" role="tab" aria-controls="update-password"
                                            aria-selected="false">Password</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="update-account" role="tabpanel"
                                        aria-labelledby="update-account-tab">
                                        <h6 class="mt-3">Account Information</h6>
                                        <form id="updateAccountForm">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" id="name"
                                                    placeholder="Name of user" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" class="form-control" id="username"
                                                    placeholder="@user" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Email Address</label>
                                                <input type="email" class="form-control" id="email"
                                                    placeholder="Email Address" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" id="location"
                                                    placeholder="Address" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="password"
                                                        placeholder="Password" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-custom btn-block">Save Changes</button>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="update-password" role="tabpanel"
                                        aria-labelledby="update-password-tab">
                                        <h6 class="mt-3">Change Password</h6>
                                        <form id="updatePasswordForm">
                                            <div class="form-group">
                                                <label>Current Password</label>
                                                <input type="password" class="form-control" id="current-password"
                                                    placeholder="Current Password" required>
                                            </div>
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="password" class="form-control" id="new-password"
                                                    placeholder="New Password" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm New Password</label>
                                                <input type="password" class="form-control" id="confirm-new-password"
                                                    placeholder="Confirm New Password" required>
                                            </div>
                                            <button type="submit" class="btn btn-custom btn-update-password btn-block"
                                                disabled>Update
                                                Password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br>

        </div>
    </section>

</div>
<?php
include 'footer.php';
?>
<script src="https://adminlte.io/themes/v3/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>
    $(document).ready(function () {
        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Fetch existing user data using AJAX on page load
        $.ajax({
            url: 'api/setting/fetch-user-information.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.error) {
                    toastr.error(data.error);
                } else {
                    $('#name').val(data.name);
                    $('#username').val(data.username);
                    $('#email').val(data.email);
                    $('#location').val(data.location);
                }
            },
            error: function () {
                toastr.error('An error occurred while fetching user information.');
            }
        });

        $('#updateAccountForm').on('submit', function (e) {
            e.preventDefault(); // Prevent form from submitting normally

            // Create an object to hold the form data
            var formData = {
                name: $('#name').val(),
                username: $('#username').val(),
                email: $('#email').val(),
                location: $('#location').val(),
                password: $('#password').val()
            };

            $.ajax({
                url: 'api/setting/update-user.php',
                type: 'POST',
                data: formData, // Send the form data as an object
                dataType: 'json', // Expect a JSON response
                success: function (response) {
                    if (response && response.success) {
                        toastr.success(response.message, 'Success');
                        $('#password').val('');
                    } else {
                        toastr.error(response.message || 'An unknown error occurred.');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                    console.error('Response:', jqXHR.responseText); // Log the entire response text
                    toastr.error('An error occurred while processing your request.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Disable the button by default
        const $updateButton = $('.btn-update-password');

        // Function to validate password confirmation
        function validatePassword() {
            const newPassword = $('#new-password').val();
            const confirmNewPassword = $('#confirm-new-password').val();
            if (newPassword !== confirmNewPassword) {
                $updateButton.prop('disabled', true);
            } else {
                $updateButton.prop('disabled', false);
            }
        }

        // Check password confirmation on input
        $('#new-password, #confirm-new-password').on('input', validatePassword);

        $('#updatePasswordForm').on('submit', function (e) {
            e.preventDefault(); // Prevent form from submitting normally

            const currentPassword = $('#current-password').val();
            const newPassword = $('#new-password').val();

            $.ajax({
                url: 'api/setting/update-password.php',
                type: 'POST',
                data: {
                    current_password: currentPassword,
                    new_password: newPassword
                },
                dataType: 'json', // Expect a JSON response
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Clear the input fields
                        $('#current-password').val('');
                        $('#new-password').val('');
                        $('#confirm-new-password').val('');
                        // Disable the button again after clearing fields
                        $updateButton.prop('disabled', true);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                    toastr.error('An error occurred while processing your request.');
                }
            });
        });
    });

</script>