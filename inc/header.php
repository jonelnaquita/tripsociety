<?php
include '../inc/config.php';
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trip Society</title>
    <link rel="icon" type="image/png" href="../img/logo.png">

    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css?v=3.2.0">
    <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <script src="../plugins/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="../plugins/uplot/uPlot.min.css">

<body class="hold-transition sidebar-mini">


    <div class="preloader flex-column justify-content-center align-items-center"
        style="background-color: rgba(255, 255, 255, 0.3);">
        <img src="../dist/img/loading.gif" alt="AdminLTELogo" height="60" width="60">
    </div>

    <style>
        * {
            font-size: 18px;

        }

        .user-img {
            position: absolute;
            height: 27px;
            width: 27px;
            object-fit: cover;
            left: -7%;
            top: -10%;
        }

        .btn-rounded {
            border-radius: 50px;
        }

        .title {
            font-family: 'Young Serif', serif !important;

        }

        .active {
            background-color: #131f54 !important;
            color: white !important;
        }
    </style>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color:#B9D9EB;">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars text-white"></i></a>
                </li>
                <li class="nav-item text-center text-white font-weight-normal" style="margin-top:10px;">
                    <h5 id="title" class="text-dark">Trip Society</h5>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown mr-3">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell text-white"></i>
                        <span class="badge badge-warning navbar-badge" id="unread-count"></span>
                    </a>
                    <div class="dropdown-menu" style="width:400px;">
                        <h5 class="m-3">Notifications</h5>

                        <!-- All and unread -->
                        <button class="btn btn-dark ml-3 btn-sm rounded-3 p-0 border" style="margin-top:-15px;"
                            id="showAll"> &nbsp &nbsp All &nbsp &nbsp</button>
                        <button class="btn btn-light btn-sm rounded-3 p-0 border-0" style="margin-top:-15px;"
                            id="showUnread"> &nbsp &nbsp Unread &nbsp &nbsp</button>

                        <div id="all-activities">

                        </div>

                        <div id="unread-activities" style="display: none;">

                        </div>





                    </div>
                </li>


                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script>
                    $(document).ready(function () {
                        function updateBadge() {
                            $.ajax({
                                url: '../inc/function.php?unread-count',
                                type: 'GET',
                                dataType: 'json',
                                success: function (response) {
                                    if (response.error) {
                                        console.error(response.error);
                                    } else {
                                        $('#unread-count').text(response.count);
                                    }
                                },
                                error: function () {
                                    console.error('Failed to fetch counts.');
                                }
                            });
                        }
                        updateBadge();
                        setInterval(updateBadge, 10000);
                    });
                </script>

                <script>



                    $(document).ready(function () {

                        let activitiesTimeout;
                        let unreadActivitiesTimeout;

                        function loadActivities() {
                            const container = $('#all-activities');
                            const scrollTop = container.scrollTop();
                            const scrollHeight = container.prop('scrollHeight');

                            $.ajax({
                                url: '../inc/function.php?view_all_activity',
                                type: 'GET',
                                success: function (response) {
                                    container.html(response);
                                    container.scrollTop(scrollTop + (container.prop('scrollHeight') - scrollHeight));
                                },
                                error: function () {
                                    console.error('Failed to fetch activities.');
                                }
                            });
                        }

                        function loadUnreadActivities() {
                            const container = $('#unread-activities');
                            const scrollTop = container.scrollTop();
                            const scrollHeight = container.prop('scrollHeight');

                            $.ajax({
                                url: '../inc/function.php?view_unread_activity',
                                type: 'GET',
                                success: function (response) {
                                    container.html(response);
                                    container.scrollTop(scrollTop + (container.prop('scrollHeight') - scrollHeight));
                                },
                                error: function () {
                                    console.error('Failed to fetch unread activities.');
                                }
                            });
                        }

                        function startPolling() {
                            // Clear previous timeouts if they exist
                            clearTimeout(activitiesTimeout);
                            clearTimeout(unreadActivitiesTimeout);

                            // Set new timeouts
                            activitiesTimeout = setTimeout(function () {
                                loadActivities();
                                startPolling(); // Recur after fetching
                            }, 10000); // Adjust interval as needed (e.g., 10 seconds)

                            unreadActivitiesTimeout = setTimeout(function () {
                                loadUnreadActivities();
                                startPolling(); // Recur after fetching
                            }, 10000); // Adjust interval as needed (e.g., 10 seconds)
                        }

                        // Initial load
                        loadActivities();
                        loadUnreadActivities();

                        // Start polling with a reasonable interval
                        startPolling();

                        $('#all-activities').show();
                        $('#unread-activities').hide();
                        function toggleDropdown(e) {
                            e.stopPropagation(); // Prevent the dropdown from closing
                            $(this).parent().parent().dropdown('toggle');
                        }

                        $('#showAll').on('click', function (e) {
                            e.preventDefault();
                            $('#all-activities').show();
                            $('#unread-activities').hide();
                            $(this).removeClass('btn-light').addClass('btn-dark');
                            $('#showUnread').removeClass('btn-dark').addClass('btn-light');
                            toggleDropdown(e);
                        });

                        $('#showUnread').on('click', function (e) {
                            e.preventDefault();
                            $('#all-activities').hide();
                            $('#unread-activities').show();
                            $(this).removeClass('btn-light').addClass('btn-dark');
                            $('#showAll').removeClass('btn-dark').addClass('btn-light');
                            toggleDropdown(e); // Keep dropdown open
                        });

                        $(document).on('click', function (e) {
                            if (!$(e.target).closest('.dropdown-menu').length) {
                                $('.dropdown-menu').dropdown('hide');
                            }
                        });
                    });
                </script>




                <li class="nav-item" style="margin-left: -20px;">
                    <div class="btn-group nav-link">
                        <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon"
                            data-toggle="dropdown">
                            <span><img src="../dist/img/avatar4.png" class="img-circle elevation-2 user-img"
                                    alt="User Image"></span>
                            <span class="ml-3 text-dark" style="font-size: 15px;">Administrator</span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Sign out</a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="account.php"><i class="fas fa-cog"></i> Account info</a>

                        </div>
                    </div>
                </li>

            </ul>
        </nav>


        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#B9D9EB;">

            <a href="index.php" class="brand-link">
                <div class="text-center m-auto">
                    <img src="../img/logo.png" style="margin-top:1px; width:100px;"><BR>
                    <span class="brand-text text-dark font-weight-bold">Trip Society</span>

                </div>
            </a>

            <div class="sidebar">



                <nav class="mt-4">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">



                        <li class="nav-item">
                            <a href="index.php" id="dashboard" class="nav-link font-weight-bold text-dark">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>





                        <li class="nav-item">
                            <a href="location.php" class="nav-link font-weight-bold text-dark" id="locations">
                                <i class="fas fa-user"></i> Locations

                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="user.php" class="nav-link font-weight-bold text-dark" id="users">

                                <i class="fas fa-id-card"></i> Users

                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="feedback.php" class="nav-link font-weight-bold text-dark" id="feedbacks">
                                <i class="fas fa-lock"></i> Feedbacks
                            </a>
                        </li>




                        <li class="nav-item">
                            <a href="announcement.php" class="nav-link font-weight-bold text-dark" id="announcement">
                                <i class="fas fa-history"></i> Announcement

                            </a>
                        </li>


                        </li>









                    </ul>
                </nav>

            </div>

        </aside>
        <?php
        include '../inc/modal.php';

        ?>