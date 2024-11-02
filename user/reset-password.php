<?php
session_start();
if (isset($_SESSION['user'])) {
    header('location: home.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css?v=3.2.0">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body class="hold-transition login-page bg-white">
    <div class="login-box">
        <div class="login-logo">
            <h5 class="font-weight-bold" style="color: #582fff;">Reset your Password</h5>
        </div>
        <form id="resetPasswordForm">
            <div class="input-container">
                <input type="password" id="password" name="password" class="material-input" placeholder=" " required>
                <label for="input" class="input-label">New Password</label>
                <i id="toggle-password" class="fa fa-eye form-control-feedback"></i>
            </div>

            <div class="input-container">
                <input type="password" id="confirm-password" name="confirm-password" class="material-input"
                    placeholder=" " required>
                <label for="input" class="input-label">Confirm Password</label>
                <i id="confirm-toggle-password" class="fa fa-eye form-control-feedback"></i>
            </div>

            <div class="password-check">
                <div class="check-length">
                    <i class="fa fa-times" style="color: red;"></i> At least 8 characters Long
                </div>
                <div class="check-uppercase">
                    <i class="fa fa-times" style="color: red;"></i> At least 1 uppercase letter (A-Z)
                </div>
                <div class="check-lowercase">
                    <i class="fa fa-times" style="color: red;"></i> At least 1 lowercase letter (a-z)
                </div>
                <div class="check-number">
                    <i class="fa fa-times" style="color: red;"></i> At least 1 number (0-9)
                </div>
                <div class="check-special">
                    <i class="fa fa-times" style="color: red;"></i> At least 1 special character (@-$)
                </div>
            </div>

            <div class="row mt-5">
                <div class="col">
                    <button type="submit" id="reset-password" class="btn btn-block"
                        style="background-color: #582fff; color: #ffff; border-radius:25px; font-size:17px;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                            style="display: none;"></span>
                        <span class="button-text">Reset Password</span>
                    </button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col text-center">
                    <p>Back to <a href="login.php" class="text-dark font-weight-bold">Sign In?</a></p>
                </div>
            </div>
        </form>


    </div>


    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/toastr/toastr.min.js"></script>
    <script src="../dist/js/adminlte.min.js?v=3.2.0"></script>
</body>

</html>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('confirm-toggle-password');
        const confirmPasswordInput = document.getElementById('confirm-password');

        // Function to toggle password visibility
        function toggleVisibility(input, toggleIcon) {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            toggleIcon.classList.toggle('fa-eye', type === 'text');
            toggleIcon.classList.toggle('fa-eye-slash', type === 'password');
        }

        // Add event listeners for toggling password fields
        togglePassword.addEventListener('click', function () {
            toggleVisibility(passwordInput, togglePassword);
        });

        toggleConfirmPassword.addEventListener('click', function () {
            toggleVisibility(confirmPasswordInput, toggleConfirmPassword);
        });

        // Show/hide toggle icons based on input content
        passwordInput.addEventListener('input', function () {
            togglePassword.style.display = passwordInput.value ? 'block' : 'none';
        });

        confirmPasswordInput.addEventListener('input', function () {
            toggleConfirmPassword.style.display = confirmPasswordInput.value ? 'block' : 'none';
        });

        // Initially hide the toggle icons
        togglePassword.style.display = 'none';
        toggleConfirmPassword.style.display = 'none';
    });

</script>

<script>
    $(document).ready(function () {
        const passwordCheckElements = {
            length: $('.check-length i'),
            uppercase: $('.check-uppercase i'),
            lowercase: $('.check-lowercase i'),
            number: $('.check-number i'),
            special: $('.check-special i'),
        };

        $('#password, #confirm-password').on('input', function () {
            const password = $('#password').val();
            const confirmPassword = $('#confirm-password').val();

            // Validate password criteria
            const isLengthValid = password.length >= 8;
            const isUppercaseValid = /[A-Z]/.test(password);
            const isLowercaseValid = /[a-z]/.test(password);
            const isNumberValid = /[0-9]/.test(password);
            const isSpecialValid = /[@$!%*?&]/.test(password);

            // Update icons and colors based on criteria
            passwordCheckElements.length.removeClass('fa-times fa-check').addClass(isLengthValid ? 'fa-check' : 'fa-times').css('color', isLengthValid ? 'green' : 'red');
            passwordCheckElements.uppercase.removeClass('fa-times fa-check').addClass(isUppercaseValid ? 'fa-check' : 'fa-times').css('color', isUppercaseValid ? 'green' : 'red');
            passwordCheckElements.lowercase.removeClass('fa-times fa-check').addClass(isLowercaseValid ? 'fa-check' : 'fa-times').css('color', isLowercaseValid ? 'green' : 'red');
            passwordCheckElements.number.removeClass('fa-times fa-check').addClass(isNumberValid ? 'fa-check' : 'fa-times').css('color', isNumberValid ? 'green' : 'red');
            passwordCheckElements.special.removeClass('fa-times fa-check').addClass(isSpecialValid ? 'fa-check' : 'fa-times').css('color', isSpecialValid ? 'green' : 'red');

            // Enable or disable the submit button based on password match
            $('#reset-password').prop('disabled', password !== confirmPassword || password === '');
        });

        $('#resetPasswordForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            const $submitButton = $('#reset-password');
            const $spinner = $submitButton.find('.spinner-border');
            const $buttonText = $submitButton.find('.button-text');

            // Show the spinner and disable the button
            $spinner.show();
            $buttonText.hide(); // Hide button text
            $submitButton.prop('disabled', true);

            // Get the token from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');

            // Serialize form data and append the token
            const formData = $(this).serialize() + '&token=' + encodeURIComponent(token);

            $.ajax({
                type: 'POST',
                url: 'api/login/reset-password.php', // Your PHP file for password update
                data: formData, // Send the serialized data with token
                dataType: 'json',
                success: function (response) {
                    $spinner.hide();
                    $buttonText.show(); // Show button text
                    $submitButton.prop('disabled', false); // Re-enable the button

                    if (response.response === 'Success') {
                        toastr.success('Password has been reset successfully.');

                        // Set a timeout of 1 second (1000 milliseconds) before redirecting
                        setTimeout(function () {
                            window.location.href = 'login.php';
                        }, 1000); // Redirects after 1000 milliseconds (1 second)
                    } else {
                        toastr.error(response.message);
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error(jqXHR.responseText);
                    $spinner.hide(); // Hide the spinner on error
                    $buttonText.show(); // Show button text
                    $submitButton.prop('disabled', false); // Re-enable the button
                    toastr.error('An error occurred. Please try again.');
                }
            });
        });
    });

</script>