<?php 
session_start();
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
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

</head>


<style type="text/css">
.login-box {
    background-color: #f0fffe; /* Set the background color to #c3fffe (light blue-green) */
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    margin: 5% auto;
    max-width: 400px;
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



  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    border-radius: 20px;
    font-weight: bold;
  }

  .login-btn {
    margin-bottom: 10px; /* You can adjust this value to control the spacing */
  }

  /* Custom style for the "Login as Guest" button */
  .btn-guest {
    background-color: #28a745; /* Green color */
    border-color: #28a745; /* Green color for border */
    border-radius: 20px; /* Rounded corners */
    color: #fff; /* Text color */
    transition: background-color 0.3s, color 0.3s;
  }
  .btn-guest:hover {
    background-color: #1e7e34; /* Darker green color on hover */
    color: #fff; /* Text color on hover */
  }
  body{
      background-color:#B9D9EB !important;
  }
  </style>

<body class="hold-transition login-page" >
<style>
    body {
      background-image: url("image/bgpic.jpg");
      background-size: cover;
    }
  </style>
<div class="login-box bg-white" style="border-radius:5px;">
    
  <div class="login-logo">
    <img src="img/logo.png">
  </div>
  <div class="card shadow-none">
    <div class="card-body login-card-body">
      <p class="text-left font-weight-bold text-dark">Don't worry it happens. Kindly <br> enter the Email Address associated <br> with your account</p>

      <p class="text-left text-dark">Email Address</p>


      <form method="post" action="inc/function.php" style="margin-top:-5px;">
      <div class="form-group has-feedback" style="position: relative;">
        <input type="email" name="email" class="form-control" placeholder="Email Address" required style="padding-right: 40px;">
        <i class="fa fa-envelope form-control-feedback" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
      </div>

        <br>
        <div class="row">
        <div class="col-6 login-btn">
            <a type="button" href="index.php" class="btn btn-outline-dark btn-block" style="border-radius:10px;">
              CANCEL
            </a>
          </div>
          <div class="col-6 login-btn">
            <button type="submit" class="btn btn-light btn-block text-white"  style="background-color:#002D62; border-radius:10px;" name="verify_email">
            <i class="fa fa-check"></i> SUBMIT
            </button>
          </div>
        </div>

        <div class="row">

</div>
      </form>


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

