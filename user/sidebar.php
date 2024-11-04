<body class="hold-transition sidebar-mini layout-fixed bg-white">
    <style>
        body {
            background-color: white !important;
        }

        .custom-fixed-top {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .custom-fixed-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .footer-icons {
            display: none;
        }


        @media only screen and (min-width: 767px) {
            .main-footer {
                display: none;
            }

            .footer-icons {
                display: block;
            }
        }

        @media only screen and (min-width: 767px) {
            .home {
                display: block;
            }

            .footer-icons {
                display: block;
            }

        }

        .main-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            /* Ensure it stays above other content */
        }

        .sidebar {
            padding: 1rem;
            /* Optional padding */
        }

        .footer-icons {
            font-size: 1.1rem;
            /* Adjust icon size */
        }

        /* Optional: Style for Sign Out button */
        .sign-buttons .btn {
            width: 80%;
            /* Button width */
            border-radius: 20px;
            /* Rounded button */
            transition: background-color 0.3s;
            /* Smooth hover transition */
        }

        .sign-buttons .btn:hover {
            background-color: #007bff;
            /* Change background on hover */
            color: #fff;
            /* Change text color */
        }

        #signout {
            border: 1px solid #007bff;
        }

        .user-panel {
            background: #fff;
            /* White background for the panel */
            padding: 15px;
            /* Padding for spacing */
            border-radius: 8px;
            /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            margin-bottom: 15px;
            /* Space below the user panel */
        }

        .user-panel .image {
            margin-bottom: 1rem;
            /* Space below the image */
        }

        .user-panel .info a {
            font-size: 1.2rem;
            /* Font size for username */
            color: #333;
            /* Dark text color */
            text-decoration: none;
            /* No underline */
            transition: color 0.3s;
            /* Smooth transition on hover */
        }

        .user-panel .info a:hover {
            color: #007bff;
            /* Color change on hover */
        }
    </style>

    <body class="wrapper">
        <?php include '../inc/navbar.php'; ?>
        <aside class="main-sidebar bg-light d-flex flex-column">
            <div class="sidebar" style="margin-top: 60px">
                <br>
                <div class="user-panel text-center m-auto">
                    <div class="image">
                        <?php
                        if (!isset($_SESSION['profile_img']) || $_SESSION['profile_img'] == "") {
                            echo '<img src="../dist/img/avatar2.png" class="img-fluid rounded-circle elevation-2" style="width: 60px; height: 60px; object-fit: cover;">';
                        } else {
                            echo '<img src="../admin/profile_image/' . $_SESSION['profile_img'] . '" class="img-fluid rounded-circle elevation-2" style="width: 60px; height: 60px; object-fit: cover;">';
                        }
                        ?>
                    </div>
                    <br>
                    <div class="info">
                        <a href="#" class="d-block font-weight-bold" style="margin-top: 5px;">
                            <?php
                            if (isset($_SESSION['username'])) {
                                echo '@' . $_SESSION['username'];
                            } else {
                                echo '@guest';
                            }
                            ?>
                        </a>
                    </div>
                </div>

                <nav class="mt-2 flex-grow-1">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item footer-icons">
                            <a href="home.php" class="nav-link">
                                <i class="fas fa-home"></i>
                                <p class="ml-2">Home</p>
                            </a>
                        </li>
                        <li class="nav-item footer-icons">
                            <a href="search.php" class="nav-link">
                                <i class="fas fa-search"></i>
                                <p class="ml-2">Search Destination</p>
                            </a>
                        </li>
                        <li class="nav-item footer-icons">
                            <a href="reviews.php" class="nav-link">
                                <i class="fas fa-edit"></i>
                                <p class="ml-2">Reviews</p>
                            </a>
                        </li>
                        <li class="nav-item footer-icons">
                            <a href="messages.php" class="nav-link">
                                <i class="fas fa-envelope"></i>
                                <p class="ml-2">Messages</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="travel_badge.php" class="nav-link">
                                <i class="fas fa-medal"></i>
                                <p class="ml-2">Travel Badge</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="travel_log_history.php" class="nav-link">
                                <i class="fas fa-history"></i>
                                <p class="ml-2">Travel log history</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="feedback.php" class="nav-link">
                                <i class="far fa-edit"></i>
                                <p class="ml-2">Feedback</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="user_manual.php" class="nav-link">
                                <i class="far fa-folder-open"></i>
                                <p class="ml-2">User Manual</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="terms_and_condition.php" class="nav-link">
                                <i class="far fa-file-alt"></i>
                                <p class="ml-2">Terms and Condition</p>
                            </a>
                        </li>

                        <?php if (isset($_SESSION['user'])): ?>
                            <li class="nav-item">
                                <a href="setting.php" class="nav-link">
                                    <i class="fas fa-cog"></i>
                                    <p class="ml-2">Settings</p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item footer-icons">
                            <a type="button" data-widget="pushmenu" href="#" role="button" class="nav-link">
                                <i class="fas fa-bars"></i>
                                <p class="ml-2">More</p>
                            </a>
                        </li>
                        <div class="sign-buttons text-center" style="margin-top: 90px;">
                            <?php if (isset($_SESSION['user'])): ?>
                                <a href="signout.php" class="nav-link btn btn-light" id="signout" style="width: 210px;">
                                    <i class="fas fa-sign-out-alt"></i> Sign Out
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="nav-link btn btn-light" id="signout" style="width: 210px;">
                                    <i class="fas fa-sign-in-alt"></i> Sign In
                                </a>
                            <?php endif; ?>
                        </div>
                    </ul>
                </nav>

            </div>
        </aside>
        <br><br>
        <script>
            document.getElementById('go-back').addEventListener('click', function () {
                window.history.back();
            });
        </script>

        <script>
            $(document).ready(function () {
                var sidebar = $('.main-sidebar');
                var moreButton = $('[data-widget="pushmenu"]');

                // Initially hide the sidebar by adding the hidden class
                sidebar.addClass('sidebar-hidden');

                moreButton.on('click', function (event) {
                    event.preventDefault(); // Prevent the default behavior of the button

                    // Toggle the classes to show or hide the sidebar
                    if (sidebar.hasClass('sidebar-hidden')) {
                        sidebar.removeClass('sidebar-hidden').addClass('sidebar-float-left');
                    } else {
                        sidebar.removeClass('sidebar-float-left').addClass('sidebar-hidden');
                    }
                });
            });
        </script>


    </body>