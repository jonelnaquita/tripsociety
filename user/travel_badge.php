<?php
include '../inc/session_user.php';
include 'header.php';
?>

<style>
    .icon-square {
        height: 200px;
        width: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        margin-top: 30px;
    }

    .icon-square:hover {
        background-color: #f0f0f0;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
    }

    .shadow {
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .progress {
        width: 100%;
        height: 8px;
        margin-top: 10px;
        background-color: #e9ecef;
        border-radius: 0.25rem;
    }

    .progress-bar {
        height: 100%;
        background-color: #007bff;
        transition: width 0.6s ease;
    }

    .details-text {
        font-size: 14px;
        color: #333;
    }

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .text-warning {
        color: #f39c12;
        /* A softer, more material-like yellow */
    }

    @media (max-width: 576px) {
        .fa-medal {
            font-size: 1.5em;
            /* Smaller icon on small screens */
        }
    }
</style>

<div class="content-wrapper">

    <div class="content-header">



        <section class="content">
            <div class="container-fluid">

                <div class="row mt-3">
                    <div class="col">
                        <div class="card p-3 bg-white border-0 shadow-sm rounded">
                            <div class="row m-auto text-left">
                                <div class="col-2 text-center">
                                    <i class="fas fa-medal text-warning fa-2x"></i>
                                </div>
                                <div class="col-10">
                                    <p class="pt-2" style="margin-bottom: 0; font-size: 14px; color: #333;">
                                        Your contributions will greatly help other people.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mt-2">
                    <div class="col">
                        <h5 class="font-weight-bold">Badges</h5>


                        <div class="container">
                            <div class="row">
                                <!-- Icon 1 -->
                                <div class="col-6 col-md-4 text-center">
                                    <div class="icon-square bg-light p-4 rounded shadow" data-toggle="collapse"
                                        data-target="#unlockNewContributor">
                                        <i class="fas fa-medal fa-2x text-secondary"></i>
                                        <p class="mt-2" style="font-size:12px;">Unlock New Contributor</p>
                                        <!-- Progress Bar -->
                                        <div class="progress">
                                            <div class="progress-unlockNewContributor progress-bar bg-primary"
                                                role="progressbar" style="width: 0%;" aria-valuenow="0"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 text-center">
                                    <div class="icon-square bg-light p-4 rounded shadow" data-toggle="collapse"
                                        data-target="#unlockCultureEnthusiast">
                                        <i class="fas fa-passport fa-2x text-secondary"></i>
                                        <p class="mt-2" style="font-size:12px;">Unlock Culture Enthusiast</p>
                                        <!-- Progress Bar -->
                                        <div class="progress">
                                            <div class="progress-unlockCultureEnthusiast progress-bar bg-warning"
                                                role="progressbar" style="width: 0%;" aria-valuenow="0"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 text-center">
                                    <div class="icon-square bg-light p-4 rounded shadow" data-toggle="collapse"
                                        data-target="#unlockRelaxationSpot">
                                        <i class="fas fa-hot-tub fa-2x text-secondary"></i>
                                        <p class="mt-2" style="font-size:12px;">Unlock Relaxation Spot</p>
                                        <!-- Progress Bar -->
                                        <div class="progress">
                                            <div class="progress-unlockRelaxationSpot progress-bar bg-info"
                                                role="progressbar" style="width: 30%;" aria-valuenow="30"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 text-center">
                                    <div class="icon-square bg-light p-4 rounded shadow" data-toggle="collapse"
                                        data-target="#unlockOutdoorAdventure">
                                        <i class="fas fa-hiking fa-2x text-secondary"></i>
                                        <p class="mt-2" style="font-size:12px;">Unlock Outdoor Adventure</p>
                                        <!-- Progress Bar -->
                                        <div class="progress">
                                            <div class="progress-unlockOutdoorAdventure progress-bar bg-success"
                                                role="progressbar" style="width: 50%;" aria-valuenow="50"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Icon 5 -->
                                <div class="col-6 col-md-4 text-center">
                                    <div class="icon-square bg-light p-4 rounded shadow" data-toggle="collapse"
                                        data-target="#unlockSacredSiteSeeker">
                                        <i class="fas fa-church fa-2x text-secondary"></i>
                                        <p class="mt-2" style="font-size:12px;">Unlock Sacred Site Seeker</p>
                                        <!-- Progress Bar -->
                                        <div class="progress">
                                            <div class="progress-unlockSacredSiteSeeker progress-bar bg-primary"
                                                role="progressbar" style="width: 70%;" aria-valuenow="70"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <hr>

                        <div class="row collapse unlock-new-contributor" id="unlockNewContributor"
                            style="margin-bottom: 100px;">
                            <div class="col">
                                <div class="card elevation-2"
                                    style="background-color: #582fff; border: none; border-radius: 8px;">
                                    <div class="card-body">
                                        <div id="NewContributorContainer">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="pt-2 font-weight-bold text-white">Earn your
                                                        New Contributor Badge</h4>
                                                </div>
                                                <div class="col-2 d-flex justify-content-center align-items-center">
                                                    <div class="icon-container position-relative">
                                                        <i class="fas fa-certificate text-white"
                                                            style="font-size: 30px;"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Post 2 photos" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white post-two-photos"
                                                                style="background-color: #3300cc;">0/2</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Write 2 reviews" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white write-two-reviews"
                                                                style="background-color: #3300cc;">0/2</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row collapse unlock-new-contributor" id="unlockCultureEnthusiast"
                            style="margin-bottom: 100px;">
                            <div class="col">
                                <div class="card elevation-2"
                                    style="background-color: #582fff; border: none; border-radius: 8px;">
                                    <div class="card-body">
                                        <div id="cultureEnthusiastContainer">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="pt-2 font-weight-bold text-white">Earn your Cultural
                                                        Enthusiast Badge</h4>
                                                </div>
                                                <div class="col-2 d-flex justify-content-center align-items-center">
                                                    <div class="icon-container position-relative">
                                                        <i class="fas fa-certificate text-white"
                                                            style="font-size: 30px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Discover Museo ni Miguel Malvar in Sto. Tomas"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <span
                                                                class="input-group-text text-white museo-miguel-malvar"
                                                                style="background-color: #3300cc;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Visit Apolinario Mabini Museum in Tanauan City"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <span
                                                                class="input-group-text text-white apolinario-mabini-museum"
                                                                style="background-color: #3300cc;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Placeholder for badges -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row collapse unlock-new-contributor" id="unlockRelaxationSpot"
                            style="margin-bottom: 100px;">
                            <div class="col">
                                <div class="card elevation-2"
                                    style="background-color: #582fff; border: none; border-radius: 8px;">
                                    <div class="card-body">
                                        <div id="relaxationSpotContainer">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="pt-2 font-weight-bold text-white">Earn your Relaxation
                                                        Spot Badge</h4>
                                                </div>
                                                <div class="col-2 d-flex justify-content-center align-items-center">
                                                    <div class="icon-container position-relative">
                                                        <i class="fas fa-certificate text-white"
                                                            style="font-size: 30px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Relax at Batangas Lakelands in Balete" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white batangas-lakelands"
                                                                style="background-color: #3300cc;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Unwind at Canyon Cove Hotel and Spa in Nasugbu"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <span
                                                                class="input-group-text text-white canyon-cove-hotel-and-spa"
                                                                style="background-color: #3300cc;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Rejuvenate at Camp Laiya Resort in San Juan"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white camp-laiya-resort"
                                                                style="background-color: #3300cc;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Placeholder for badges -->
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row collapse unlock-new-contributor" id="unlockOutdoorAdventure"
                            style="margin-bottom: 100px;">
                            <div class="col">
                                <div class="card elevation-2"
                                    style="background-color: #582fff; border: none; border-radius: 8px;">
                                    <div class="card-body">
                                        <div id="outdoorAdventureContainer">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="pt-2 font-weight-bold text-white">Earn your
                                                        Outdoor Adventure Badge</h4>
                                                </div>
                                                <div class="col-2 d-flex justify-content-center align-items-center">
                                                    <div class="icon-container position-relative">
                                                        <i class="fas fa-certificate text-white"
                                                            style="font-size: 30px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Go for a hike at Mt. Batulao in Nasugbu" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white mt-batulao"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Climb Mt. Maculot in Cuenca" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white mt-maculot"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Climb Mt. Talumpok in Tanauan City" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white mt-talumpok"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Soak in Malagaslas Falls in Laurel" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white malagaslas-falls"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Placeholder for badges -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row collapse unlock-new-contributor" id="unlockSacredSiteSeeker"
                            style="margin-bottom: 100px;">
                            <div class="col">
                                <div class="card elevation-2"
                                    style="background-color: #582fff; border: none; border-radius: 8px;">
                                    <div class="card-body">
                                        <div id="sacredSiteSeekerContainer">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="pt-2 font-weight-bold text-white">Earn your
                                                        Outdoor Adventure Badge</h4>
                                                </div>
                                                <div class="col-2 d-flex justify-content-center align-items-center">
                                                    <div class="icon-container position-relative">
                                                        <i class="fas fa-certificate text-white"
                                                            style="font-size: 30px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Visit Marian Orchard in Balete" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white marian-orchard"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Explore Monte Maria in Batangas City" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-white monte-maria"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Discover Caleruega Retreat House in Nasugbu"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <span
                                                                class="input-group-text text-white caleruega-retreat-house"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Journey to Kabanal-Banalang Puso ni Hesus in San Luis"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <span
                                                                class="input-group-text text-white kabanal-banalang-puso-ni-hesus"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-white"
                                                            style="background-color: #3300cc; border: none; font-size: 13px;"
                                                            value="Witness the historical Basilica of St. Martin De Tours in Taal"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <span
                                                                class="input-group-text text-white basilica-of-st-martin-de-tours"
                                                                style="background-color: #3300cc;">0/1</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Placeholder for badges -->
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>

            </div>
        </section>

    </div>
    <?php
    include 'footer.php';
    ?>


    <!--Close other Collapse-->
    <script>
        // Close other collapses when one is opened
        $('.collapse').on('show.bs.collapse', function () {
            // Close all other collapses
            $('.collapse').not(this).collapse('hide');
        });
    </script>

    <!--Contributor Badge-->
    <script>
        $(document).ready(function () {
            function updateProgress() {
                $.ajax({
                    url: 'api/badge/fetch-badge.php', // URL of the PHP script
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Update the counts for posts and reviews
                        $('.post-two-photos').text(`${data.post_count}/2`);
                        $('.write-two-reviews').text(`${data.review_count}/2`);

                        // Update the progress bar
                        $('.progress-unlockNewContributor').css('width', `${data.progress_percentage}%`);
                        $('.progress-unlockNewContributor').attr('aria-valuenow', data.progress_percentage);

                        // Check if progress is 100%, if so, make the icon text-primary
                        if (data.progress_percentage === 100) {
                            $('.icon-square .fa-medal').removeClass('text-secondary').addClass('text-primary');
                        } else {
                            $('.icon-square .fa-medal').removeClass('text-primary').addClass('text-secondary');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            // Call the function to update the progress on page load
            updateProgress();

            // Poll the server every 5 seconds to update the progress in real-time
            setInterval(updateProgress, 5000);  // 5000 milliseconds = 5 seconds
        });
    </script>

    <!--Cultural-->
    <script>
        $(document).ready(function () {
            function updateCultureProgress() {
                $.ajax({
                    url: 'api/badge/fetch-culture-enthusiast.php', // URL to the PHP script
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Update the count for Museo ni Miguel Malvar
                        $('.museo-miguel-malvar').text(`${data.museo_miguel_malvar}/1`);

                        // Update the count for Apolinario Mabini Museum
                        $('.apolinario-mabini-museum').text(`${data.apolinario_mabini_museum}/1`);

                        // Update the progress bar
                        $('.progress-unlockCultureEnthusiast').css('width', `${data.progress_percentage}%`);
                        $('.progress-unlockCultureEnthusiast').attr('aria-valuenow', data.progress_percentage);

                        // Check if progress is 100%, and change icon color
                        if (data.progress_percentage === 100) {
                            $('.fa-passport').removeClass('text-secondary').addClass('text-primary');
                        } else {
                            $('.fa-passport').removeClass('text-primary').addClass('text-secondary');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            // Call the function to update the progress on page load
            updateCultureProgress();

            // Optional: Poll the server every 5 seconds to check if the user visited new locations in real-time
            setInterval(updateCultureProgress, 5000);  // 5000 ms = 5 seconds
        });
    </script>

    <!--Relaxation Spot-->
    <script>
        $(document).ready(function () {
            function updateRelaxationSpotProgress() {
                $.ajax({
                    url: 'api/badge/fetch-relaxation-spot.php', // URL to the PHP script
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Update the count for Museo ni Miguel Malvar
                        $('.batangas-lakelands').text(data.batangas_lakelands + '/1');
                        $('.canyon-cove-hotel-and-spa').text(data.canyon_cove_hotel_and_spa + '/1');
                        $('.camp-laiya-resort').text(data.camp_laiya_resort + '/1');

                        $('.progress-unlockRelaxationSpot').css('width', data.progress_percentage + '%').attr('aria-valuenow', data.progress_percentage);

                        // Check if progress is 100%, and change icon color
                        if (data.progress_percentage === 100) {
                            $('.fa-hot-tub').removeClass('text-secondary').addClass('text-primary');
                        } else {
                            $('.fa-hot-tub').removeClass('text-primary').addClass('text-secondary');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            // Call the function to update the progress on page load
            updateRelaxationSpotProgress();

            // Optional: Poll the server every 5 seconds to check if the user visited new locations in real-time
            setInterval(updateRelaxationSpotProgress, 5000);  // 5000 ms = 5 seconds
        });
    </script>

    <script>
        $(document).ready(function () {
            // Function to update Outdoor Adventure progress
            function updateOutdoorAdventureProgress() {
                $.ajax({
                    url: 'api/badge/fetch-outdoor-adventure.php', // URL to the PHP script
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Update counts for each location
                        $('.mt-batulao').text(data.mt_batulao + '/1');
                        $('.mt-maculot').text(data.mt_maculot + '/1');
                        $('.mt-talumpok').text(data.mt_talumpok + '/1');
                        $('.malagaslas-falls').text(data.malagaslas_falls + '/1');

                        // Update progress bar width
                        $('.progress-unlockOutdoorAdventure').css('width', data.progress_percentage + '%').attr('aria-valuenow', data.progress_percentage);

                        // Change icon color if progress is 100%
                        if (data.progress_percentage === 100) {
                            $('.fa-hiking').removeClass('text-secondary').addClass('text-primary');
                        } else {
                            $('.fa-hiking').removeClass('text-primary').addClass('text-secondary');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            // Call the function to update the progress on page load
            updateOutdoorAdventureProgress();

            // Optional: Poll the server every 5 seconds to check for new updates
            setInterval(updateOutdoorAdventureProgress, 5000);  // 5000 ms = 5 seconds
        });
    </script>

    <!--Sacred Site Seeker-->
    <script>
        $(document).ready(function () {
            // Function to update Sacred Site Seeker progress
            function updateSacredSiteSeekerProgress() {
                $.ajax({
                    url: 'api/badge/fetch-sacred-site-seeker.php', // URL to the PHP script
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Update counts for each sacred site location
                        $('.marian-orchard').text(data.marian_orchard + '/1');
                        $('.monte-maria').text(data.monte_maria + '/1');
                        $('.caleruega-retreat-house').text(data.caleruega_retreat_house + '/1');
                        $('.kabanal-banalang-puso-ni-hesus').text(data.kabanal_banalang_puso + '/1');
                        $('.basilica-of-st-martin-de-tours').text(data.basilica_of_st_martin + '/1');

                        // Update progress bar width
                        $('.progress-unlockSacredSiteSeeker').css('width', data.progress_percentage + '%').attr('aria-valuenow', data.progress_percentage);

                        // Change icon color if progress is 100%
                        if (data.progress_percentage === 100) {
                            $('.fa-church').removeClass('text-secondary').addClass('text-primary');
                        } else {
                            $('.fa-church').removeClass('text-primary').addClass('text-secondary');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            // Call the function to update the progress on page load
            updateSacredSiteSeekerProgress();

            // Optional: Poll the server every 5 seconds to check for new updates
            setInterval(updateSacredSiteSeekerProgress, 5000);  // 5000 ms = 5 seconds
        });

    </script>


    <script>
        $(document).ready(function () {
            // Get the user's current location on page load
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const currentLat = position.coords.latitude;
                    const currentLng = position.coords.longitude;

                    // Make AJAX request to insert travel log if within 500 meters
                    $.ajax({
                        url: 'api/search/save-travel-log.php',
                        type: 'POST',
                        data: {
                            current_lat: currentLat,
                            current_lng: currentLng
                        },
                        dataType: 'json',
                        success: function (response) {
                            console.log('Success: ' + response.message);
                        },
                        error: function () {
                            console.log('Failed to check location');
                        }
                    });
                }, function (error) {
                    console.log('Error getting location: ' + error.message);
                });
            } else {
                console.log('Geolocation is not supported by this browser.');
            }
        });
    </script>