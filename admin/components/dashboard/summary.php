<div class="row">
    <div class="ms-3">
        <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
        <p class="mb-4 text-muted">
            This dashboard serves as a comprehensive tool to understand user preferences, highlight top destinations,
            and evaluate travel safety.
        </p>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Total Users</p>
                        <h4 class="mb-0 total-users">$53k</h4>
                    </div>
                    <div
                        class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">person</i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Total Destination</p>
                        <h4 class="mb-0 total-destination">2300</h4>
                    </div>
                    <div
                        class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">location_on</i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Total Verified Users</p>
                        <h4 class="mb-0 verified-users">3,462</h4>
                    </div>
                    <div
                        class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">verified</i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Total Reports</p>
                        <h4 class="mb-0 total-reports">$103,430</h4>
                    </div>
                    <div
                        class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">feedback</i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        function fetchUserCounts() {
            $.ajax({
                url: 'api/dashboard/fetch-users.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('.total-users').text(data.totalUsers);
                    $('.verified-users').text(data.verifiedUsers);
                    $('.total-destination').text(data.totalDestination);
                    $('.total-reports').text(data.totalReports);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        // Fetch counts on page load
        fetchUserCounts();

        // Optionally, refresh the counts every 5 minutes
        setInterval(fetchUserCounts, 300000); // 300,000 ms = 5 minutes
    });
</script>