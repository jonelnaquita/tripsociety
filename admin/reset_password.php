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

                        <form method="POST" id="resetPasswordForm">
                            <div class="row ml-5 mr-5">
                                <div class="col mt-3">
                                    <div class="input-group input-group-outline mb-4">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="new_password"
                                            id="new-password">
                                    </div>
                                    <div class="password-check" style="display: none;">
                                        <div class="check-length">
                                            <i class="fa fa-times" style="color: red;"></i> At least 8 characters Long
                                        </div>
                                        <div class="check-uppercase">
                                            <i class="fa fa-times" style="color: red;"></i> At least 1 uppercase letter
                                            (A-Z)
                                        </div>
                                        <div class="check-lowercase">
                                            <i class="fa fa-times" style="color: red;"></i> At least 1 lowercase letter
                                            (a-z)
                                        </div>
                                        <div class="check-number">
                                            <i class="fa fa-times" style="color: red;"></i> At least 1 number (0-9)
                                        </div>
                                        <div class="check-special">
                                            <i class="fa fa-times" style="color: red;"></i> At least 1 special character
                                            (@-$)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row ml-5 mr-5">
                                <div class="col mt-2">
                                    <div class="input-group input-group-outline mb-4">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password"
                                            id="confirm-password">
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
        const $newPasswordInput = $('input[name="new_password"]'); // Use the correct input name
        const $confirmPasswordInput = $('input[name="confirm_password"]'); // Use the correct input name
        const $passwordCheck = $('.password-check');
        const $updateButton = $('button[name="reset_password1"]');

        // Show password criteria when new password input is focused
        $newPasswordInput.on('focus', function () {
            $passwordCheck.show();
        });

        // Hide password criteria when new password input loses focus
        $newPasswordInput.on('blur', function () {
            if (!$newPasswordInput.val()) {
                $passwordCheck.hide();
            }
        });

        // Password validation on input
        $newPasswordInput.on('input', function () {
            const password = $newPasswordInput.val();
            const isLengthValid = password.length >= 8;
            const isUppercaseValid = /[A-Z]/.test(password);
            const isLowercaseValid = /[a-z]/.test(password);
            const isNumberValid = /[0-9]/.test(password);
            const isSpecialValid = /[@$!%*?&]/.test(password);

            // Update icons and show/hide password criteria
            $('.check-length i').toggleClass('fa-times', !isLengthValid).toggleClass('fa-check', isLengthValid).css('color', isLengthValid ? 'green' : 'red');
            $('.check-uppercase i').toggleClass('fa-times', !isUppercaseValid).toggleClass('fa-check', isUppercaseValid).css('color', isUppercaseValid ? 'green' : 'red');
            $('.check-lowercase i').toggleClass('fa-times', !isLowercaseValid).toggleClass('fa-check', isLowercaseValid).css('color', isLowercaseValid ? 'green' : 'red');
            $('.check-number i').toggleClass('fa-times', !isNumberValid).toggleClass('fa-check', isNumberValid).css('color', isNumberValid ? 'green' : 'red');
            $('.check-special i').toggleClass('fa-times', !isSpecialValid).toggleClass('fa-check', isSpecialValid).css('color', isSpecialValid ? 'green' : 'red');

            // Hide criteria if already satisfied
            $('.check-length').toggle(!isLengthValid);
            $('.check-uppercase').toggle(!isUppercaseValid);
            $('.check-lowercase').toggle(!isLowercaseValid);
            $('.check-number').toggle(!isNumberValid);
            $('.check-special').toggle(!isSpecialValid);

            // Check if all password criteria are met
            if (isLengthValid && isUppercaseValid && isLowercaseValid && isNumberValid && isSpecialValid) {
                $updateButton.prop('disabled', false);
            } else {
                $updateButton.prop('disabled', true);
            }
        });

        // Handle form submission
        $('form').on('submit', function (event) {
            event.preventDefault();

            const newPassword = $newPasswordInput.val();
            const confirmPassword = $confirmPasswordInput.val();

            // Check if passwords match
            if (newPassword !== confirmPassword) {
                toastr.error("Passwords do not match!");
                return;
            }

            // Proceed with AJAX submission if all validations pass
            $.ajax({
                type: 'POST',
                url: 'api/settings/update-password.php', // PHP file to handle password update
                data: { new_password: newPassword },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        toastr.success('Password updated successfully!');
                        $newPasswordInput.val('');
                        $confirmPasswordInput.val('');
                        $updateButton.prop('disabled', true); // Disable button after successful update
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    toastr.error('An error occurred while updating the password.');
                }
            });
        });
    });
</script>