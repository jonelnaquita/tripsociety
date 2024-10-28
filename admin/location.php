<!DOCTYPE html>
<html lang="en">

<?php
include '../inc/session.php';
include "includes/header.php"; ?>

<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">



            <section id="table-location">
                <?php
                include 'components/location/table-location.php';
                ?>
            </section>

            <section id="add-location" style="display: none;">
                <?php
                include 'components/location/add-location.php';
                ?>
            </section>


            <?php include "includes/footer.php"; ?>
        </div>
    </main>
</body>

<script>
    $(document).ready(function () {
        $('.add-destination-btn').on('click', function () {
            // Show the add-location section and hide the table-location section
            $('#add-location').show();        // Show the add location section
            $('#table-location').hide();      // Hide the table location section
        });
    });

    $(document).ready(function () {
        $('.back-table-location').on('click', function () {
            // Show the add-location section and hide the table-location section
            $('#add-location').hide();        // Show the add location section
            $('#table-location').show();      // Hide the table location section
        });
    });

</script>

</html>