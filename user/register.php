<!DOCTYPE html>
<html lang="en">
<?php include 'includes.php'; ?>

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
  <div class="login-box" id="registrationBox">

    <form id="registerForm" class="p-4 rounded" style="background-color: #fff;">
      <div class="login-logo text-left">
        <h2 class="font-weight-bold" style="color: #582fff;">Become a <br> Trip Society <br> member.</h2>
      </div>
      <!-- Full Name Field -->
      <div class="form-group">
        <label class="font-weight-bold text-muted" for="name">Full Name</label>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Enter Full Name" name="name" id="name" required>
        </div>
        <small class="invalid-error" id="nameError" style="display: none;"></small>
      </div>

      <div class="form-group">
        <label class="font-weight-bold text-muted" for="username">Username</label>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Enter Username" name="username" id="username" required>
        </div>
        <small class="invalid-error" id="usernameError" style="display: none;"></small>
      </div>

      <div class="form-group">
        <label class="font-weight-bold text-muted" for="email">Email Address</label>
        <div class="input-group">
          <input type="email" class="form-control" placeholder="Enter Email" name="email" id="email" required>
        </div>
        <small class="invalid-error" id="emailError" style="display: none;"></small>
      </div>

      <!-- Password Field -->
      <div class="form-group">
        <label class="font-weight-bold text-muted" for="password">Password</label>
        <div class="input-group">
          <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password"
            required>
          <div class="input-group-append">
            <div class="input-group-text cursor-pointer" id="togglePassword"
              style="background-color: transparent; border-left: none;">
              <span class="fas fa-eye"></span>
            </div>
          </div>
        </div>
        <div class="password-check" style="display: none;">
          <div class="check-length">
            <i class="fa fa-times" style="color: red;"></i> At least 8 characters Long
          </div>
          <div class="check-uppercase">
            <i class="fa fa-times" style="color: red;"></i> At least 1 uppercase letter (A-Z)
          </div>
          <div class="check-lowercase">
            <i class="fa fa-times" style="color: red;"></i> At least 1 lowercase letter (a-z)
          </div>
          <div class="check-number">
            <i class="fa fa-times" style="color: red;"></i> At least 1 number (0-9)
          </div>
          <div class="check-special">
            <i class="fa fa-times" style="color: red;"></i> At least 1 special character (@-$)
          </div>
        </div>
      </div>

      <!-- Terms & Conditions -->
      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="agreeCheckbox" required>
        <label class="form-check-label text-muted" for="agreeCheckbox">
          I agree to the <a href="#" data-toggle="modal" data-target="#termsandconditionModal"
            class="text-primary font-weight-bold"><u>Terms & Conditions</u></a>
        </label>
      </div>

      <!-- Sign Up Button -->
      <div class="form-group text-center">
        <button id="signUpButton" type="submit" class="btn btn-block btn-lg"
          style="border-radius: 25px; background-color: #582fff; color: #fff;" disabled>
          <span id="loadingSpinner" class="spinner-border spinner-border-sm" style="display:none;" role="status"
            aria-hidden="true"></span>
          Sign Up
        </button>

        <a href="home-guest.php" class="btn btn-block btn-lg"
          style="border-radius: 25px; border: 1px solid #582fff; color: #582fff;">
          Continue as Guest
        </a>
      </div>
    </form>

    <div class="row mt-3">
      <div class="col text-center">
        <p>Already a member? <a href="login.php" class="text-dark font-weight-bold">Sign In</a></p>
      </div>
    </div>


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
                  By participating in Ala'emap: Uniting Wanderers in Exploring the Wonders of Batangas ("Ala'emap"), you
                  agree to comply with the following terms and conditions. Ala'emap is a collaborative initiative aimed
                  at bringing together adventurers and explorers to discover the diverse wonders of Batangas.
                  Participants are expected to conduct themselves in a respectful and responsible manner throughout all
                  activities organized by Ala'emap. All participants must adhere to local laws, regulations, and
                  guidelines, and are responsible for their own safety and well-being during Ala'emap events. Ala'emap
                  organizers reserve the right to modify or cancel any event due to unforeseen circumstances or safety
                  concerns.
                  By participating in Ala'emap, you consent to the use of any photographs, videos, or other media
                  captured during events for promotional purposes. Ala'emap organizers are not liable for any loss,
                  injury, or damage incurred during participation in Ala'emap activities. Participants are encouraged to
                  act in an environmentally conscious manner and to respect the natural beauty and cultural heritage of
                  Batangas. By registering for Ala'emap events, you acknowledge that you have read, understood, and
                  agree to abide by these terms and conditions.
                </p>
              </div>
            </div>


          </div>


        </div>
      </div>
    </div>
  </div>

  <div class="container mt-5 d-none" id="successMessage">
    <div class="row justify-content-center">
      <div class="col-md-6"> <!-- Adjusted column size for centering the card -->
        <div class="card text-center mx-auto">
          <div class="card-body">
            <i class="fas fa-check-circle fa-3x mb-3" style="color: #582fff;"></i>
            <h4 class="text-center">Thank You for Registration!</h4>
            <p class="card-text">Please check your email address to verify your account.</p>
            <a href="login.php">Back to Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/adminlte.min.js?v=3.2.0"></script>

  <script>
    $(document).ready(function () {
      const passwordInput = $('#password');
      const passwordCheckElements = {
        length: $('.check-length'),
        uppercase: $('.check-uppercase'),
        lowercase: $('.check-lowercase'),
        number: $('.check-number'),
        special: $('.check-special')
      };

      // Show password criteria when password input is focused
      passwordInput.on('focus', function () {
        $('.password-check').show();
      });

      // Hide password criteria when password input loses focus
      passwordInput.on('blur', function () {
        if (!passwordInput.val()) {
          $('.password-check').hide();
        }
      });

      // Password validation on input
      passwordInput.on('input', function () {
        const password = passwordInput.val();
        const isLengthValid = password.length >= 8;
        const isUppercaseValid = /[A-Z]/.test(password);
        const isLowercaseValid = /[a-z]/.test(password);
        const isNumberValid = /[0-9]/.test(password);
        const isSpecialValid = /[@$!%*?&]/.test(password);

        // Update icons and show/hide password criteria
        passwordCheckElements.length.find('i').toggleClass('fa-times', !isLengthValid).toggleClass('fa-check', isLengthValid).css('color', isLengthValid ? 'green' : 'red');
        passwordCheckElements.length.toggle(!isLengthValid);

        passwordCheckElements.uppercase.find('i').toggleClass('fa-times', !isUppercaseValid).toggleClass('fa-check', isUppercaseValid).css('color', isUppercaseValid ? 'green' : 'red');
        passwordCheckElements.uppercase.toggle(!isUppercaseValid);

        passwordCheckElements.lowercase.find('i').toggleClass('fa-times', !isLowercaseValid).toggleClass('fa-check', isLowercaseValid).css('color', isLowercaseValid ? 'green' : 'red');
        passwordCheckElements.lowercase.toggle(!isLowercaseValid);

        passwordCheckElements.number.find('i').toggleClass('fa-times', !isNumberValid).toggleClass('fa-check', isNumberValid).css('color', isNumberValid ? 'green' : 'red');
        passwordCheckElements.number.toggle(!isNumberValid);

        passwordCheckElements.special.find('i').toggleClass('fa-times', !isSpecialValid).toggleClass('fa-check', isSpecialValid).css('color', isSpecialValid ? 'green' : 'red');
        passwordCheckElements.special.toggle(!isSpecialValid);

        // Enable Sign Up button if all password criteria met
        $('#signUpButton').prop('disabled', !(isLengthValid && isUppercaseValid && isLowercaseValid && isNumberValid && isSpecialValid));
      });

      // Toggle password visibility
      $('#togglePassword').on('click', function () {
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).find('span').toggleClass('fa-eye fa-eye-slash');
      });

      // Validation for name, username, and email
      $('#name, #username, #email').on('blur', function () {
        const name = $('#name').val();
        const username = $('#username').val();
        const email = $('#email').val();

        let nameValid = true;
        let usernameValid = true;
        let emailValid = true;

        // Validate Full Name
        if (this.id === 'name') {
          if (name.length <= 7 || /\d/.test(name)) {
            $('#nameError').text('Full Name must be more than 7 characters long and cannot contain numbers.').show();
            nameValid = false;
          } else {
            $('#nameError').hide();
          }
        }

        // Validate Username
        if (this.id === 'username') {
          if (username.length <= 7) {
            $('#usernameError').text('Username must be more than 7 characters long.').show();
            usernameValid = false;
          } else {
            $('#usernameError').hide();
          }
        }

        // Validate Email
        if (this.id === 'email') {
          const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
          if (!emailPattern.test(email)) {
            $('#emailError').text('Please enter a valid email address.').show();
            emailValid = false;
          } else {
            $('#emailError').hide();
          }
        }

        // Enable or disable the Sign Up button based on overall form validation
        const isFormValid = nameValid && usernameValid && emailValid;
        $('#signUpButton').prop('disabled', !isFormValid);
      });

      $('#registerForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting normally

        if ($('#signUpButton').is(':disabled')) return; // Prevent submission if button is disabled

        // Disable the Sign Up button and show the spinner
        $('#signUpButton').prop('disabled', true);
        $('#loadingSpinner').show();

        $.ajax({
          type: 'POST',
          url: 'api/register/save-user.php',
          data: $(this).serialize(), // Serialize the form data
          success: function (response) {
            const data = JSON.parse(response);

            // Enable the button and hide the spinner after request is complete
            $('#signUpBtn').prop('disabled', false);
            $('#loadingSpinner').hide();

            if (data.response === 'Success') {
              // Hide the registration form
              $('#registrationBox').addClass('d-none');

              // Show the success message
              $('#successMessage').removeClass('d-none');

              // Clear the form fields
              $('#registerForm')[0].reset();
            } else {
              // Show error toastr notification
              alert(data.message);
            }
          },
          error: function () {
            // In case of an error, enable the button and hide the spinner
            $('#signUpBtn').prop('disabled', false);
            $('#loadingSpinner').hide();

            // Show error toastr notification
            alert('An error occurred. Please try again.');
          }
        });
      });
    });
  </script>

</body>

</html>