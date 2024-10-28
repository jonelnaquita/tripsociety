<?php
include '../inc/session.php';
include "includes/header.php"; ?>

<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card p-3">
                                <style>
                                    * {
                                        padding: 0;
                                        margin: 0;
                                    }

                                    #map {
                                        width: 100%;
                                        height: 700px;
                                        /* Adjust the height as needed */
                                    }
                                </style>

                                <?php
                                include '../inc/config.php';
                                if (isset($_GET['id'])) {
                                    $id = intval($_GET['id']);
                                    $pdo_statement = $pdo->prepare("SELECT * FROM tbl_location WHERE id = :id");
                                    $pdo_statement->bindParam(':id', $id, PDO::PARAM_INT);
                                    $pdo_statement->execute();
                                    $result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
                                    if ($result) {
                                        // Assuming 'location' field contains 'latitude,longitude'
                                        list($latitude, $longitude) = explode(',', $result['location']);
                                        ?>
                                        <div id="map"></div>
                                        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                                        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
                                        <script>
                                            // Initialize the map
                                            var map = L.map('map').setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], 13);

                                            // Add a tile layer to the map
                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                            }).addTo(map);

                                            // Add a marker to the map
                                            L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map)
                                                .bindPopup('Location: <?php echo $result['location_name']; ?>')
                                                .openPopup();
                                        </script>
                                        <?php
                                    } else {
                                        echo 'No data found.';
                                    }
                                } else {
                                    echo 'Invalid ID.';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <?php include "includes/footer.php"; ?>
        </div>
    </main>
</body>