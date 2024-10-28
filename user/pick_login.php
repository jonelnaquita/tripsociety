<?php 
session_start();
function generateCode() {
    return sprintf("%06d", mt_rand(0, 999999));
}
// echo $_SESSION['session_login'] = generateCode();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>User</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">

<link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">

<link rel="stylesheet" href="../dist/css/adminlte.min.css?v=3.2.0">
</head>
<body class="hold-transition login-page bg-white">
   
<div class="login-box" style="margin-top:-200px;">
<div class="login-logo text-left">
<img src="../img/logo.png" width="100px;">
<h4 class="font-weight-bold mt-3 mb-3">Sign in to wonder the <br> best of Trip Society</h4>



<div class="row mt-3">
    <div class="col p-3">
<?php 
require_once 'google-login/config.php';
if (isset($_SESSION['user_token'])) {
  header("Location: google-login/welcome.php");
} else {
  $authUrl = $client->createAuthUrl();
  echo "

    <style>
      /* Styling for the overlay */
      #overlay {
        display: none; /* Hide the overlay initially */
        position: fixed; /* Fixed positioning */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Black background with 50% opacity */
        z-index: 9998; /* Ensure overlay is above the background but below the loading image */
      }

      /* Styling for the loading image */
      #loading {
        display: none; /* Hide the loading image initially */
        position: fixed; /* Fixed positioning */
        left: 50%; /* Center horizontally */
        top: 50%; /* Center vertically */
        transform: translate(-50%, -50%); /* Center image */
        z-index: 9999; /* Make sure it's above the overlay */
      }
    </style>
    <script>
      function openAuthUrl() {
        // Show the overlay and loading image
        document.getElementById('overlay').style.display = 'block';
        document.getElementById('loading').style.display = 'block';
        // Open the Google Auth URL in a new window
        window.open('$authUrl', '_blank');
      }
    </script>

    <!-- Overlay for background dimming -->
    <div id='overlay'></div>
    <!-- Loading GIF -->
    <img id='loading' src='../dist/img/loading.gif' alt='Loading...' style='width:50px;'>
    <!-- Continue with Google button -->
    <button onclick='openAuthUrl()' id='authButton' class='authButton btn btn-secondary btn-block' style='border-radius:25px; font-size:23px;'>Continue with Google</button>
 ";
}
?>

    </div>
</div>
<p class="text-center font-weight-bold" style="font-size:20px; margin:-15px;">or</p>
<div class="row">
    <div class="col p-3">
        <a type="button" href="login.php" class="btn btn-secondary btn-block text-white" style="border-radius:25px; font-size:23px;"><i class="fas fa-envelope text-white"></i> Continue with Email</a>
        <p style="font-size:13px;" class="mt-1 text-center"><i>Continue with Email if doesnt have Google Account</i></p>
    </div>
</div>

</div>

</div>



<script src="../plugins/jquery/jquery.min.js"></script>

<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../dist/js/adminlte.min.js?v=3.2.0"></script>
</body>
</html>


<script>
    function checkCode() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_session.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                window.location.href = 'https://tripsociety.online/user/home.php';
            } else if (response.status === 'error') {
                console.error('Error checking code:', response.message);
            }
        }
    };
    xhr.send();
}
setInterval(checkCode, 3000);

</script>
