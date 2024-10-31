<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    #destinationHazardChart {
        height: 400px;
        /* Adjust this value as needed */
    }
</style>


<?php
include '../inc/session.php';
include "includes/header.php"; ?>

<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">

            <?php include 'components/dashboard/summary.php'; ?>

            <div class="row">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <div class="card mt-4">
                        <div class="card-header bg-gradient-dark text-white">
                            <h5 class="card-title mb-0">Top Destinations by Average Rating</h5>
                            <p class="card-text small mb-0">Based on user reviews</p>
                        </div>
                        <div class="card-body">
                            <canvas id="destinationRatingsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-8 mx-auto">
                    <div class="card mt-4">
                        <div class="card-header bg-gradient-dark text-white">
                            <h5 class="card-title mb-0">Most Frequently Visited Destinations</h5>
                            <p class="card-text small mb-0">Based on user visits</p>
                        </div>
                        <div class="card-body">
                            <canvas id="destinationVisitsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <div class="card mt-4">
                        <div class="card-header bg-gradient-dark text-white">
                            <h5 class="card-title mb-0">User Travel Preferences</h5>
                            <p class="card-text small mb-0">Most common travel preferences among users.</p>
                        </div>
                        <div class="card-body">
                            <canvas id="userPreferencesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-8 mx-auto">
                    <div class="card mt-4">
                        <div class="card-header bg-gradient-dark text-white">
                            <h5 class="card-title mb-0">User Distribution by Location</h5>
                            <p class="card-text small mb-0">Visual representation of user locations.</p>
                        </div>
                        <div class="card-body">
                            <canvas id="userLocationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card mt-4">
                        <div class="card-header bg-gradient-dark text-white">
                            <h5 class="card-title mb-0">Destination Hazard Levels</h5>
                            <p class="card-text small mb-0">Average hazard level based on user reviews.</p>
                        </div>
                        <div class="card-body">
                            <canvas id="destinationHazardChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <?php include "includes/footer.php"; ?>
        </div>
    </main>
</body>

<script>
    $(document).ready(function () {
        function fetchDestinationRatings() {
            $.ajax({
                url: 'api/dashboard/fetch-top-destinations.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    renderChart(data);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        function renderChart(data) {
            const ctx = document.getElementById('destinationRatingsChart').getContext('2d');

            // Extract location names and ratings
            const labels = data.map(item => item.location_name);
            const ratings = data.map(item => item.avg_rating);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average Rating',
                        data: ratings,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5 // Assuming the rating scale is 1-5
                        }
                    }
                }
            });
        }

        // Fetch data on page load
        fetchDestinationRatings();
    });
</script>


<script>
    $(document).ready(function () {
        function fetchVisitCounts() {
            $.ajax({
                url: 'api/dashboard/fetch-visit-counts.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    renderVisitChart(data);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        function renderVisitChart(data) {
            const ctx = document.getElementById('destinationVisitsChart').getContext('2d');

            // Extract location names and visit counts
            const labels = data.map(item => item.location_name);
            const visitCounts = data.map(item => item.visit_count);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Visit Count',
                        data: visitCounts,
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Fetch data on page load
        fetchVisitCounts();
    });
</script>

<script>
    $(document).ready(function () {
        function fetchPreferences() {
            $.ajax({
                url: 'api/dashboard/fetch-preferences.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    renderPreferencesChart(data);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        function renderPreferencesChart(data) {
            const ctx = document.getElementById('userPreferencesChart').getContext('2d');

            // Extract preferences and counts
            const labels = data.map(item => item.preference);
            const preferenceCounts = data.map(item => item.preference_count);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Preference Count',
                        data: preferenceCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Fetch data on page load
        fetchPreferences();
    });
</script>


<script>
    $(document).ready(function () {
        function fetchUserLocations() {
            $.ajax({
                url: 'api/dashboard/fetch-user-locations.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    renderLocationChart(data);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        function renderLocationChart(data) {
            const ctx = document.getElementById('userLocationChart').getContext('2d');

            // Extract locations and user counts, replacing empty locations with "No City Selected"
            const labels = data.map(item => item.location || 'No City Selected');
            const userCounts = data.map(item => item.user_count);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'User Count',
                        data: userCounts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // Fetch data on page load
        fetchUserLocations();
    });
</script>



<script>
    $(document).ready(function () {
        function fetchHazardLevels() {
            $.ajax({
                url: 'api/dashboard/fetch-hazard-levels.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    renderHazardChart(data);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        function renderHazardChart(data) {
            const ctx = document.getElementById('destinationHazardChart').getContext('2d');

            // Extract destination names and average hazard levels
            const labels = data.map(item => item.location_name);
            const hazardLevels = data.map(item => item.avg_hazard);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average Hazard Level',
                        data: hazardLevels,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function (value) {
                                    // Add label to hazard scale
                                    if (value === 0) return 'No Hazard';
                                    if (value === 20) return 'Very Low';
                                    if (value === 40) return 'Low';
                                    if (value === 60) return 'Moderate';
                                    if (value === 80) return 'High';
                                    if (value === 100) return 'Extreme';
                                    return value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Fetch data on page load
        fetchHazardLevels();
    });
</script>