<?php
include '../inc/session.php';
include '../inc/header.php';
?>


<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">

                </div>

            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-6 m-auto">
                    <div class="card p-3">
                        <div class="text-center">
                            <img src="../dist/img/avatar5.png" style="width:70px;" class="img-circle" alt="">
                            <h5>Admin</h5>
                        </div>

                        <form action="../inc/function.php" method="POST">
                            <div class="row ml-5 mr-5">
                                <div class="col mt-3">
                                    <label for="">Username</label>
                                    <input type="text" placeholder="@user" value="<?php echo $_SESSION['username']; ?>"
                                        class="form-control" name="username">
                                </div>
                            </div>

                            <div class="row ml-5 mr-5">
                                <div class="col mt-2">
                                    <label for="">Email Address</label>
                                    <input type="email" placeholder="user@gmail.com"
                                        value="<?php echo $_SESSION['email']; ?>" class="form-control" name="email">
                                </div>
                            </div>

                            <hr>


                            <div class="row ml-5 mr-5">
                                <div class="col mt-2">
                                    <label for="">Reset Password</label><br>
                                    <p style="font-size:16px;  line-height: 1.3;">We recommend using a password manager
                                        or creating a unique password that containes 10 characters and special character
                                    </p>
                                    <label for="">Email Address</label>
                                    <input type="email" placeholder="user@gmail.com" readonly
                                        value="<?php echo $_SESSION['email']; ?>" class="form-control bg-white"
                                        name="email_reset">
                                    <div class="float-right mt-2">
                                        <button type="submit" name="send_reset_password"
                                            class="btn btn-outline-dark mr-auto btn-sm"><i class="fas fa-envelope"></i>
                                            Verify Email</button>

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
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../inc/footer.php';
?>


</body>

</html>
<script>
    window.onload = function () {
        var element = document.getElementById('accounts');
        element.classList.add('active');
    };
</script>




<script>
    var titleElement = document.getElementById("title");
    titleElement.innerHTML = "Account Info";



</script>