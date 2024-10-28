<nav class="navbar navbar-main bg-gradient-info navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky"
    id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-3">
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            </div>
            <ul class="navbar-nav d-flex align-items-center  justify-content-end">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="account.php" class="nav-link text-body p-0">
                        <i class="material-symbols-rounded fixed-plugin-button-nav text-white">settings</i>
                    </a>
                </li>

                <li class="nav-item d-flex dropdown mr-3">
                    <a class="nav-link" data-bs-toggle="dropdown" href="#">
                        <i class="material-symbols-rounded cursor-pointer text-white">notifications</i>
                        <span class="badge badge-warning navbar-badge" id="unread-count"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-3" style="width: 400px;">
                        <h5 class="text-center mb-3">Notifications</h5>

                        <!-- Flex container for All and Unread buttons -->
                        <div class="d-flex justify-content-center mb-3">
                            <button class="btn btn-dark btn-sm mx-2 px-3 rounded-3" id="showAll">All</button>
                            <button class="btn btn-light btn-sm mx-2 px-3 rounded-3" id="showUnread">Unread</button>
                        </div>

                        <!-- Notification contents -->
                        <div id="all-activities"></div>
                        <div id="unread-activities" style="display: none;"></div>
                    </div>
                </li>



                <script>
                    $(document).ready(function () {
                        // Badge update function
                        function updateBadge() {
                            $.ajax({
                                url: '../inc/function.php?unread-count',
                                type: 'GET',
                                dataType: 'json',
                                success: function (response) {
                                    $('#unread-count').text(response.count);
                                },
                                error: function () {
                                    console.error('Failed to fetch counts.');
                                }
                            });
                        }

                        // Activity loading functions
                        function loadActivities() {
                            $.ajax({
                                url: '../inc/function.php?view_all_activity',
                                type: 'GET',
                                success: function (response) {
                                    $('#all-activities').html(response);
                                },
                                error: function () {
                                    console.error('Failed to fetch activities.');
                                }
                            });
                        }

                        function loadUnreadActivities() {
                            $.ajax({
                                url: '../inc/function.php?view_unread_activity',
                                type: 'GET',
                                success: function (response) {
                                    $('#unread-activities').html(response);
                                },
                                error: function () {
                                    console.error('Failed to fetch unread activities.');
                                }
                            });
                        }

                        // Initial load and interval setup
                        updateBadge();
                        loadActivities();
                        loadUnreadActivities();
                        setInterval(updateBadge, 10000); // Refresh badge count every 10 seconds
                        setInterval(loadActivities, 10000); // Refresh all activities every 10 seconds
                        setInterval(loadUnreadActivities, 10000); // Refresh unread activities every 10 seconds

                        // Show all activities by default
                        $('#all-activities').show();
                        $('#unread-activities').hide();

                        // Toggle between "All" and "Unread" views
                        $('#showAll').on('click', function (e) {
                            e.preventDefault();
                            $('#all-activities').show();
                            $('#unread-activities').hide();
                            $(this).removeClass('btn-light').addClass('btn-dark');
                            $('#showUnread').removeClass('btn-dark').addClass('btn-light');
                        });

                        $('#showUnread').on('click', function (e) {
                            e.preventDefault();
                            $('#all-activities').hide();
                            $('#unread-activities').show();
                            $(this).removeClass('btn-light').addClass('btn-dark');
                            $('#showAll').removeClass('btn-dark').addClass('btn-light');
                        });

                        // Prevent dropdown from closing on button clicks inside the dropdown
                        $('.dropdown-menu').on('click', function (e) {
                            e.stopPropagation();
                        });
                    });
                </script>

                <li class="nav-item d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                        <i class="material-symbols-rounded text-white">account_circle</i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>