<?php
session_start();
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
</head>

<body class="hold-transition login-page bg-white">
    <div class="login-box">
        <div class="login-logo">
            <h1 class="font-weight-bold" style="color: #582fff;">Welcome to <br> Trip Society</h1>
        </div>

        <br>
        <!-- Login Form -->
        <form id="loginForm">
            <label class="font-weight-normal">Email Address</label>
            <div class="input-group mb-3">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <label class="font-weight-normal">Password</label>
            <div class="form-group has-feedback" style="position: relative;">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                    required style="padding-right: 50px;" id="password-input">
                <i id="toggle-password" class="fa fa-eye form-control-feedback"
                    style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 20px; cursor: pointer;"></i>
            </div>

            <div style="margin-top:-10px;">
                <a href="auth.php" class="text-dark"><u>Forgot password?</u></a>
            </div>

            <div class="row mt-5">
                <div class="col">
                    <button type="submit" class="btn btn-block"
                        style="background-color: #582fff; color: #ffff; border-radius:25px; font-size:20px;">Sign
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



    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="../plugins/toastr/toastr.min.js"></script>


    <script src="../dist/js/adminlte.min.js?v=3.2.0"></script>
</body>

</html>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password-input');

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
                    toastr.error('Something went wrong. Please try again.');  // General error message
                }
            });
        });
    });
</script>