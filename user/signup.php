<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css?v=3.2.0">
</head>

<body class="hold-transition login-page bg-white">
  <div class="login-box">
    <div class="login-logo text-left">
      <h2 class="font-weight-bold">Become a <br> Trip Society <br> member.</h2>
    </div>


    <form action="../inc/function.php" method="post">
      <label class="font-weight-normal">Full Name</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Full Name" name="name">
      </div>

      <div class="row">
        <div class="col">
          <label class="font-weight-normal">Email Address</label>
          <input type="email" class="form-control" placeholder="email Address" name="email">

        </div>
        <div class="col">
          <label class="font-weight-normal">Username</label>
          <input type="text" class="form-control" placeholder="Username" name="username">
        </div>
      </div>


      <label class="font-weight-normal mt-3">Password</label>
      <div class="input-group mb-3">
        <input type="password" class="form-control" placeholder="Password">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-eye"></span>
          </div>
        </div>
      </div>


      <div class="row mb-4">
        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="agreeCheckbox" checked required>
            <label class="form-check-label" for="agreeCheckbox">
              I agree to the <a href="#" data-toggle="modal" data-target="#termsandconditionModal"
                class="font-weight-bold text-dark"><u>Terms & Conditions</u></a>
            </label>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <button type="submit" class="btn btn-secondary btn-block" style="border-radius:25px; font-size:20px;">Sign
            Up</button>
          <p class="text-center mt-2">Already a member? <a href="pick_login.php" class="font-weight-bold text-dark">Sign
              in</a></p>
        </div>

      </div>





    </form>


    <div class="modal fade" id="termsandconditionModal" tabindex="-1" role="dialog"
      aria-labelledby="deleteUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteUserModalLabel">Terms & Condition</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pl-4 pr-4">
            <div class="row">
              <div class="col">
                <p>
                  By participating in TripSociety: Uniting Wanderers in Exploring the Wonders of Batangas
                  ("TripSociety"), you
                  agree to comply with the following terms and conditions. TripSociety is a collaborative initiative
                  aimed
                  at bringing together adventurers and explorers to discover the diverse wonders of Batangas.
                  Participants are expected to conduct themselves in a respectful and responsible manner throughout all
                  activities organized by TripSociety. All participants must adhere to local laws, regulations, and
                  guidelines, and are responsible for their own safety and well-being during TripSociety events.
                  TripSociety
                  organizers reserve the right to modify or cancel any event due to unforeseen circumstances or safety
                  concerns.
                  By participating in TripSociety, you consent to the use of any photographs, videos, or other media
                  captured during events for promotional purposes. TripSociety organizers are not liable for any loss,
                  injury, or damage incurred during participation in TripSociety activities. Participants are encouraged
                  to
                  act in an environmentally conscious manner and to respect the natural beauty and cultural heritage of
                  Batangas. By registering for TripSociety events, you acknowledge that you have read, understood, and
                  agree to abide by these terms and conditions.
                </p>
              </div>
            </div>


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
  // Automatically check the checkbox when the page loads
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('agreeCheckbox').checked = true;
  });
</script>