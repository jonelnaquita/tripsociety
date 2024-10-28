<style>
  .install-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #007bff;
    /* Primary color */
    color: white;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
    z-index: 1000;
  }

  .install-header span {
    font-size: 16px;
  }

  #install-button {
    margin-left: 10px;
  }

  .close-button {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
  }
</style>

<div id="install-header" class="install-header" style="display: none;">
  <span>Install our app for a better experience!</span>
  <button type="button" class="btn btn-primary btn-sm text-white" id="install-button">Install App</button>
  <button type="button" class="close-button" id="close-install-header">✕</button>
</div>


<nav class="main-header navbar navbar-expand navbar-white navbar-light custom-fixed-top"
  style="background-color:#582fff;">
  <ul class="navbar-nav">
    <li class="nav-item m-auto text-center">
      <a class="nav-link" style="margin-top:-15px;">
        <img src="../img/logo-white.png" width="40px;">
        <span class="font-weight-bolder text-light">TripSociety</span>
      </a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown" style="margin-right: -13px;">
      <a class="nav-link" href="javascript:void(0);" onclick="openBottomSheet();">
        <i class="fas fa-search text-white" style="font-size:18px;"></i>
      </a>
    </li>


    <li class="nav-item dropdown" style="margin-right:-10px;">
      <a class="nav-link" href="notification.php">
        <i class="fas fa-bell text-white" style="font-size:18px;"></i>
        <span class="badge badge-warning notification-number navbar-badge"></span>
      </a>
    </li>

    <li class="nav-item" style="margin-top:-2px;">
      <?php
      if (!isset($_SESSION['profile_img']) or $_SESSION['profile_img'] == "") {
        echo '<a class="nav-link" href="user.php" role="button">
<img src="../dist/img/avatar2.png" class="img-fluid rounded-circle" style="width: 25px;"></a>';
      } else {
        echo '<a class="nav-link" href="user.php" role="button">
<img src="../admin/profile_image/' . $_SESSION['profile_img'] . '" class="img-fluid img-circle" style="width: 30px; height:30px;"></a>';
      }
      ?>
    </li>
  </ul>
</nav>

<script>
  $(document).ready(function () {
    function fetchNotifications() {
      $.ajax({
        url: 'api/notification/fetch-count-notification.php', // Make sure this points to your PHP file
        method: 'GET',
        dataType: 'json',
        success: function (response) {
          if (response.total_notifications) {
            $('.notification-number').text(response.total_notifications);
          } else {
            $('.notification-number').text('0'); // Fallback if there's an error
          }
        },
        error: function (xhr, status, error) {
          console.error('Error fetching notifications:', error);
        }
      });
    }

    // Call the function to fetch notifications on page load
    fetchNotifications();
  });
</script>

<?php include 'modal/search-companion.php' ?>