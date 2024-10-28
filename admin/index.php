<?php
include "includes/header.php"; ?>
<!DOCTYPE html>
<html lang="en">

<body class="bg-gray-200">
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-100"
            style="background-image: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/d7/ee/12/img-20170212-223321-557.jpg?w=1200&h=-1&s=1');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
                                    <div class="row mt-3">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form role="form" class="text-start" id="loginForm">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" id="signInButton"
                                            class="btn bg-gradient-dark w-100 my-4 mb-2">Sign in</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script>
    $(document).ready(function () {
        $('#signInButton').on('click', function () {
            const email = $('#email').val();
            const password = $('#password').val();

            $.ajax({
                url: 'api/auth/signin.php',
                type: 'POST',
                data: { email: email, password: password },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message, 'Success');
                        // Redirect or perform another action on success
                        setTimeout(() => {
                            window.location.href = 'dashboard.php'; // Redirect to dashboard or desired page
                        }, 1500);
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                },
                error: function () {
                    toastr.error('An error occurred. Please try again later.', 'Error');
                }
            });
        });
    });

</script>

</html>