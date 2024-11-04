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
            <h1 class="font-weight-bold" style="color: #582fff;">Welcome to <br> Trip Society</h1>
        </div>

        <br>
        <!-- Login Form -->
        <form id="loginForm">
            <div class="input-container">
                <input type="email" id="email" name="email" class="material-input" placeholder=" " required>
                <label for="input" class="input-label">Email</label>
            </div>

            <div class="input-container">
                <input type="password" id="password" name="password" class="material-input" placeholder=" " required>
                <label for="input" class="input-label">Password</label>
                <i id="toggle-password" class="fa fa-eye form-control-feedback"></i>
            </div>


            <div style="margin-top:-10px;">
                <a class="forgot-password text-dark"><u>Forgot password?</u></a>
            </div>

            <div class="row mt-5">
                <div class="col">
                    <button type="submit" class="btn btn-block"
                        style="background-color: #582fff; color: #ffff; border-radius:25px; font-size:17px;">Sign
                        In</button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col text-center">
                    <p>Not a member? <a href="register.php" class="text-dark font-weight-bold">Sign Up</a></p>
                </div>
            </div>
        </form>
    </div>

    <div class="forgot-password-box" style="display: none;">
        <br>
        <!-- Login Form -->
        <form id="forgotPasswordForm">
            <div class="row container">
                <h5>Enter your email to reset your password.</h5>
            </div>
            <div class="input-container">
                <input type="email" id="email-reset" name="email-reset" class="material-input" placeholder=" " required>
                <label for="input" class="input-label">Email</label>
            </div>

            <div class="row mt-5">
                <div class="col">
                    <button type="submit" id="submit-reset" class="btn btn-block"
                        style="background-color: #582fff; color: #ffff; border-radius:25px; font-size:17px;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                            style="display: none;"></span>
                        <span class="button-text">Submit</span>
                    </button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col text-center">
                    <p>Back to <a class="back-login text-dark font-weight-bold">Sign In?</a></p>
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

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the icon
            if (type === 'password') {
                togglePassword.classList.remove('fa-eye-slash');
                togglePassword.classList.add('fa-eye');
            } else {
                togglePassword.classList.remove('fa-eye');
                togglePassword.classList.add('fa-eye-slash');
            }
        });

        // Show/hide toggle icon based on password input content
        passwordInput.addEventListener('input', function () {
            togglePassword.style.display = passwordInput.value ? 'block' : 'none';
        });

        // Initially hide the toggle icon
        togglePassword.style.display = 'none';
    });

</script>

<script>
    // Wait for the DOM to load
    document.addEventListener("DOMContentLoaded", function () {
        // Get the elements
        const loginBox = document.querySelector(".login-box");
        const forgotPasswordBox = document.querySelector(".forgot-password-box");
        const forgotPasswordLink = document.querySelector(".forgot-password");
        const backToLoginLink = document.querySelector(".back-login");

        // Show forgot password box and hide login box when .forgot-password is clicked
        forgotPasswordLink.addEventListener("click", function (e) {
            e.preventDefault();
            loginBox.style.display = "none";
            forgotPasswordBox.style.display = "block";
        });

        // Show login box and hide forgot password box when .back-login is clicked
        backToLoginLink.addEventListener("click", function (e) {
            e.preventDefault();
            forgotPasswordBox.style.display = "none";
            loginBox.style.display = "block";
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#loginForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var email = $('#email').val();
            var password = $('#password').val();

            $.ajax({
                url: 'api/login/login.php', // PHP file that processes login
                type: 'POST',
                data: {
                    email: email,
                    password: password
                },
                success: function (response) {
                    var data = JSON.parse(response);

                    if (data.status === 'success') {
                        toastr.success(data.message);  // Show success notification
                        setTimeout(function () {
                            window.location.href = 'home.php';  // Redirect to home
                        }, 2000);
                    } else {
                        toastr.error(data.message);  // Show error notification
                    }
                },
                error: function (xhr, status, error) {
                    // Log the full error details to the console
                    console.error('Error details:', {
                        xhr: xhr,
                        status: status,
                        error: error
                    });

                    // Show a general error message
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        });
    });
</script>


<!--Submit for Reset Password-->
<script>
    $(document).ready(function () {
        $('#forgotPasswordForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            var $submitButton = $('#submit-reset');
            var $spinner = $submitButton.find('.spinner-border');
            var $buttonText = $submitButton.find('.button-text');

            // Show the spinner and disable the button
            $spinner.show();
            $buttonText.hide(); // Hide button text
            $submitButton.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: 'api/login/forgot-password.php', // PHP file to process the request
                data: $(this).serialize(), // Serialize form data
                dataType: 'json',
                success: function (response) {
                    // Hide the spinner
                    $spinner.hide();
                    $buttonText.show(); // Show button text
                    $submitButton.prop('disabled', false); // Re-enable the button

                    // Show toastr message
                    toastr.success('A verification link has been sent to your email.');
                    $('#email-reset').val('');
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