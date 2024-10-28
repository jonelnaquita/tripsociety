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
                                    <label for="">New Password</label>
                                    <input type="password" placeholder="New Password" class="form-control"
                                        name="new_password">
                                </div>
                            </div>

                            <div class="row ml-5 mr-5">
                                <div class="col mt-2">
                                    <label for="">Confirm Password</label>
                                    <input type="password" placeholder="Confirm Password" class="form-control"
                                        name="confirm_password">
                                </div>
                            </div>

                            <br>
                            <div class="row m-auto text-center">
                                <div class="col-lg-12 ">
                                    <button type="submit" name="reset_password1" class="btn btn-secondary">RESET
                                        PASSWORD</button>
                                </div>
                            </div>
                            <br>
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