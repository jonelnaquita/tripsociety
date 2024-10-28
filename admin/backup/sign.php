<?php
session_start();
if (isset($_SESSION['admin'])) {
  header('location: admin/');
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login Account</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="icon" type="image/png" href="img/logo.png">

</head>


<style type="text/css">
  .login-box {
    background-color: white;
    /* Set the background color to #c3fffe (light blue-green) */
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    margin: 5% auto;
    max-width: 400px !important;
    width: 500px;
    padding: 20px;
    text-align: center;
  }

  .login-logo img {
    max-width: 100px;
  }

  .login-card-body {
    padding: 20px;
  }

  .login-box-msg {
    font-size: 20px;
  }

  .alert {
    background-color: #f2dede;
    border-color: #ebccd1;
    color: #a94442;
  }

  .form-group.has-feedback {
    margin-bottom: 15px;
  }

  .form-control {
    border-radius: 20px;
  }

  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    border-radius: 20px;
    font-weight: bold;
  }

  .login-btn {
    margin-bottom: 10px;
    /* You can adjust this value to control the spacing */
  }

  /* Custom style for the "Login as Guest" button */
  .btn-guest {
    background-color: #28a745;
    /* Green color */
    border-color: #28a745;
    /* Green color for border */
    border-radius: 20px;
    /* Rounded corners */
    color: #fff;
    /* Text color */
    transition: background-color 0.3s, color 0.3s;
  }

  .btn-guest:hover {
    background-color: #1e7e34;
    /* Darker green color on hover */
    color: #fff;
    /* Text color on hover */
  }
</style>

<body class="hold-transition login-page" style="background-color:#B9D9EB;">
  <style>
    body {
      background-image: url("image/bgpic.jpg");
      background-size: cover;
    }
  </style>
  <div class="login-box" style="border-radius:5px;  border:1px solid #7C7676;">
    <br><br><br>
    <div class="login-logo" style="font-size:24px;">
      <img src="img/logo.png">

    </div>
    <h6 style="font-size:25px; margin-top:-15px;"> TripSociety</h6>
    <div class="card shadow-none ml-4 mr-4">
      <div class="card-body login-card-body">

        <?php if (isset($msg)): ?>
          <div class="alert alert-danger"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="POST" action="inc/function.php">
          <div class="form-group has-feedback" style="position: relative;">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required
              style="padding-right: 40px;">
            <i class="fa fa-user form-control-feedback"
              style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
          </div>

          <div class="form-group has-feedback" style="position: relative;">
            <input type="password" name="password" class="form-control" placeholder="Password" required
              style="padding-right: 50px;" id="password-input">
            <i id="toggle-password" class="fa fa-eye form-control-feedback"
              style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 20px; cursor: pointer;"></i>
          </div>


          <div class="text-left" style="margin-top:-10px;">
            <h6 style="font-size:14px;">Forgotten Password? <a href="auth.php" class="text-dark">Click here</a></h6>
          </div>
          <br>
          <div class="row">
            <div class="col-12 login-btn">
              <button type="submit" class="btn btn-primary btn-block border-0" style="background-color:#002D62;"
                name="admin_login">
                <i class="fa fa-lock"></i> Sign in
              </button>
            </div>
          </div>

          <div class="row">

          </div>
        </form>
        <br><br><br>

      </div>
    </div>
  </div>
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <script src="plugins/toastr/toastr.min.js"></script>

</body>

</html>


<?php
if (isset($_SESSION['message']) && isset($_SESSION['response']) && isset($_SESSION['message_timestamp'])) {
  $message = $_SESSION['message'];
  $response = $_SESSION['response'];
  $timestamp = $_SESSION['message_timestamp'];

  if (time() - $timestamp <= 5) {
    echo '<script>
            $(document).ready(function() {';

    if ($response === 'Success') {
      echo 'toastr.success("' . $message . '");';
    } else {
      echo 'toastr.error("' . $message . '");';
    }

    echo '});
            </script>';
  } else {
    unset($_SESSION['message']);
    unset($_SESSION['response']);
    unset($_SESSION['message_timestamp']);
  }
}
?>
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