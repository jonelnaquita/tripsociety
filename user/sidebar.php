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
    </style>

    <body class="wrapper">

        <?php include '../inc/navbar.php'; ?>


        <aside class="main-sidebar sidebar-dark-primary  elevation-4" style="background-color:#173045;">


            <div class="sidebar">
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
                        <a href="#" class="d-block text-white font-weight-bold" style="margin-top:-5px;">
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



                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item footer-icons">
                            <a href="home.php" class="nav-link">
                                <i class="fas fa-home text-white"></i>
                                <p class="text-white ml-2">Home</p>
                            </a>
                        </li>



                        <li class="nav-item footer-icons">
                            <a href="search.php" class="nav-link">
                                <i class="fas fa-search text-white"></i>
                                <p class="text-white ml-2">Search Destination</p>
                            </a>
                        </li>

                        <li class="nav-item footer-icons">
                            <a href="reviews.php" class="nav-link">
                                <i class="fas fa-edit text-white"></i>
                                <p class="text-white ml-2">Reviews</p>
                            </a>
                        </li>

                        <li class="nav-item footer-icons">
                            <a href="messages.php" class="nav-link">
                                <i class="fas fa-envelope text-white"></i>
                                <p class="text-white ml-2">Messages</p>
                            </a>
                        </li>





                        <li class="nav-item">
                            <a href="travel_badge.php" class="nav-link">
                                <i class="fas fa-medal text-white"></i>
                                <p class="text-white ml-2">Travel Badge</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="travel_log_history.php" class="nav-link">
                                <i class="fas fa-history text-white"></i>
                                <p class="text-white ml-2">Travel log history</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="feedback.php" class="nav-link">
                                <i class="far fa-edit text-white"></i>
                                <p class="text-white ml-2">Feedback</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="user_manual.php" class="nav-link">
                                <i class="far fa-folder-open text-white"></i>
                                <p class="text-white ml-2">User Manual</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="terms_and_condition.php" class="nav-link">
                                <i class="far fa-file-alt text-white"></i>
                                <p class="text-white ml-2">Terms and Condition</p>
                            </a>
                        </li>

                        <?php
                        if (isset($_SESSION['user'])) {
                            ?>
                            <li class="nav-item">
                                <a href="setting.php" class="nav-link">
                                    <i class="fas fa-cog text-white"></i>
                                    <p class="text-white ml-2">Settings</p>
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <li class="nav-item footer-icons">
                            <a type="button" data-widget="pushmenu" href="#" role="button" class="nav-link">
                                <i class="fas fa-bars text-white"></i>
                                <p class="text-white ml-2">More</p>
                            </a>
                        </li>





                    </ul>



                    <div class="d-flex flex-column" id="signOut" style="height:40vh;">
                        <div class="mt-auto text-center">
                            <?php
                            if (isset($_SESSION['user'])) {
                                ?>
                                <a href="signout.php" class="btn btn-light text-dark">
                                    <i class="fas fa-sign-out-alt"></i> Sign Out
                                </a>
                                <?php
                            } else {
                                ?>
                                <a href="login.php" class="btn btn-light text-dark">
                                    <i class="fas fa-sign-out-alt"></i> Sign In
                                </a>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                </nav>

            </div>

        </aside>
        <br><br>
        <script>
            document.getElementById('go-back').addEventListener('click', function () {
                window.history.back();
            });
        </script>
    </body>