<nav class="main-header navbar navbar-expand navbar-white navbar-light custom-fixed-top"
  style="background-color:#582fff;">

  <button type="button" class="btn btn-primary text-white shadow-sm" id="install-button" style="display:none;">Install
    App</button>

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