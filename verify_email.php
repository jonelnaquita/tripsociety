<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Verify Email</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/logo.png">

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

<body class="hold-transition login-page">
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
    <p class="login-box-msg font-weight-bold" style="font-size:30px; line-height: 1.1;">Confirm your Email address</p>

      <p style="margin-top:-10px;">We sent confirmation email to:</p>
      <div style="margin-top:-20px;">
      <a  href="#" class="text-secondary font-weight-bold"><?php echo $_SESSION['verified_email'];?></a>
      </div>
      <i><p style="margin-top:8px; font-size:12px; line-height: 1.1;">
    To complete your verification, check your email, (don't forget to check your Spam folder) and click on the confirmation link to continue
    </p></i>
      <?php if(isset($msg)): ?>
        <div class="alert alert-danger"><?php echo $msg; ?></div>
      <?php endif; ?>
        
      <form method="post" action="inc/function.php">

        <br>
        <div class="row" style="margin-top: -20px;">
            <div class="col-12 login-btn">
                <input type="text" name="email" value="<?php echo $_SESSION['verified_email']; ?>" hidden>
                <button id="verifyButton" type="submit" class="btn btn-light text-white disabled btn-block" style="background-color:#002D62;" name="verify_email">
                    Verify Email
                </button>
            </div>
        </div>
        
        <div class="row">
            <div class="col">
                <h6 id="resend_text">Resend Email in <span id="countdown" class="time">30</span>s</h6>
            </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var verificationCode = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
    $.ajax({
        type: "GET",
        url: "verify_code.php",
        data: { id: verificationCode },
        success: function(response) {
            if (response.trim() === "Verification code does not match.") {
                console.log("Not Verified Yet!");
            } else {
                window.location.href = "reset_password.php";
            }
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error);
        }
    });
});
</script>

<script>
    function startCountdown(duration, display) {
        var timer = duration, minutes, seconds;
        var resendText = document.getElementById("resend_text");

        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = seconds;

            if (--timer < 0) {
                timer = duration;
                document.getElementById("verifyButton").classList.remove('disabled');
                document.getElementById("verifyButton").removeAttribute('disabled');
                display.textContent = duration;

                // Remove the resend_text element
                if (resendText) {
                    resendText.parentNode.removeChild(resendText);
                }
            }
        }, 1000);
    }

    document.addEventListener("DOMContentLoaded", function () {
        var countdown = 30; 
        var display = document.getElementById("countdown");
        startCountdown(countdown, display);
    });
</script>

</body>
</html>
