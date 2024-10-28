<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Trip Society</title>

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css?v=3.2.0">

  <style>
    /* Style for splash screen */
    .splash-screen {
      position: fixed;
      top: 0;

      width: 100%;
      height: 100%;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .splash-screen img {
      max-width: 100%;
      max-height: 100%;
      height: auto;
    }

    /* Hide main content initially */
    .main-content {
      display: none;
    }
  </style>

</head>

<body class="hold-transition login-page bg-white">

  <!-- Splash Screen -->
  <div id="splash-screen" class="splash-screen" style="width:200px;">
    <img src="user/splash/splash.gif" alt="Splash Screen">
  </div>


  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js?v=3.2.0"></script>

  <script>
    // Function to handle splash screen and redirection
    function showSplashScreen() {
      const splashScreen = document.getElementById('splash-screen');

      // Show splash screen for 9 seconds
      setTimeout(() => {
        // Redirect to get_started.php after 9 seconds
        window.location.href = 'user/get_started.php';
      }, 9000); // 9 seconds
    }

    // Run the splash screen function on page load
    window.onload = showSplashScreen;
  </script>

</body>

</html>