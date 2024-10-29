<?php
include '../inc/session_user.php';
include 'header.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    include '../inc/config.php';
    $id = $_GET['id'];

    // Fetch location details
    $stmt = $pdo->prepare("SELECT * FROM tbl_location WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($locations) {
        foreach ($locations as $row) {
            $location_name = $row['location_name'];
            $instruction = $row['instruction'];
            $category = $row['category'];

            // Replace commas with bullet symbols
            $categories = str_replace(',', ' • ', $category);
        }
    }

    // Get the total average rating for the location
    $stmt_avg = $pdo->prepare("SELECT AVG(rating) as average_rating FROM tbl_review WHERE location_id = :id");
    $stmt_avg->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_avg->execute();
    $avg_rating = $stmt_avg->fetch(PDO::FETCH_ASSOC)['average_rating'];
    if ($avg_rating != "") {
        $rating = '3.0';
    } else {
        $rating = number_format($avg_rating, 2);
    }
    // Get the total count of reviews for the location
    $stmt_count = $pdo->prepare("SELECT COUNT(*) as total_reviews FROM tbl_review WHERE location_id = :id");
    $stmt_count->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_count->execute();
    $total_reviews = $stmt_count->fetch(PDO::FETCH_ASSOC)['total_reviews'];

}


?>
<link href="https://api.mapbox.com/mapbox-gl-js/v3.5.1/mapbox-gl.css" rel="stylesheet">
<style>
    .bottom-sheet {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        opacity: 0;
        pointer-events: none;
        /* Ensure map is clickable */
        align-items: center;
        flex-direction: column;
        justify-content: flex-end;
        transition: 0.1s linear;
        z-index: 1;
        /* Ensure it is behind the map when minimized */
    }

    .bottom-sheet.show {
        opacity: 1;
        pointer-events: auto;
        /* Enable interaction when visible */
    }

    .bottom-sheet .content {
        width: 100%;
        position: relative;
        background: #fff;
        max-height: 100vh;
        height: 15vh;
        /* Minimized height */
        max-width: 1150px;
        padding: 25px 30px;
        transform: translateY(100%);
        border-radius: 12px 12px 0 0;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.03);
        transition: 0.3s ease;
    }

    .bottom-sheet.show .content {
        transform: translateY(0%);
        /* Show full height when open */
    }

    /* Ensure the map is clickable */
    #map {
        pointer-events: auto;
    }

    .bottom-sheet .sheet-overlay {
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
        width: 100%;
        height: 100%;
        opacity: 0.2;
        background: #000;
    }

    .bottom-sheet.dragging .content {
        transition: none;
    }

    .bottom-sheet.fullscreen .content {
        border-radius: 0;
        overflow-y: hidden;
    }

    .bottom-sheet .header {
        display: flex;
        justify-content: center;
    }

    .header .drag-icon {
        cursor: grab;
        user-select: none;
        padding: 15px;
        margin-top: -15px;
    }

    .header .drag-icon span {
        height: 4px;
        width: 40px;
        display: block;
        background: #C7D0E1;
        border-radius: 50px;
    }

    .bottom-sheet .body {
        height: 100%;
        overflow-y: auto;
        padding: 15px 0 40px;
        scrollbar-width: none;
    }

    .bottom-sheet .body::-webkit-scrollbar {
        width: 0;
    }

    .bottom-sheet .body h2 {
        font-size: 1.8rem;
    }

    .bottom-sheet .body p {
        margin-top: 20px;
        font-size: 1.05rem;
    }

    .timeline-container {
        max-height: 300px;
        overflow-y: hidden;
        position: relative;
        padding-right: 15px;
    }

    .timeline-container:hover {
        overflow-y: auto;
    }



    .timeline::-webkit-scrollbar {
        width: 0px;
        /* Removes the scrollbar */
    }
</style>


<div class="content-wrapper">

    <div class="content-header">


        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">
                        <div class="card p-1 mt-2">
                            <div class="row" style="position:absolute; z-index:2;">
                                <div class="col m-2 text-center">
                                    <form action="view_route.php" method="GET">
                                        <input class="form-control" name="search" type="search"
                                            placeholder="Destination" style="width:300px; border-radius:20px;">
                                    </form>
                                </div>
                            </div>
                            <div id="map" style="height:540px;"></div>
                            <button class="show-modal btn btn-default shadow"
                                style="position:absolute; bottom:0; right:0; margin-bottom:40px; margin-right:40px;"><i
                                    class="fas fa-info-circle"></i> <strong>View Details</strong></button>

                        </div>
                    </div>
                </div>

                <style>
                    @media (min-width: 992px) {
                        .bottom-sheet {
                            max-width: 50%;
                            /* Adjust width as needed */
                            margin-left: 500px;
                        }
                    }
                </style>
                <div class="bottom-sheet">
                    <div class="sheet-overlay"></div>
                    <div class="content">
                        <div class="header">
                            <div class="drag-icon"><span></span></div>
                        </div>
                        <div class="body">
                            <h5><?php echo $location_name; ?></h5>
                            <div class="row">
                                <div class="col">
                                    <h6 class="font-weight-bold"><?php echo $rating; ?> &nbsp


                                        <?php
                                        $rating = round($rating, 1);
                                        $full_stars = floor($rating);
                                        $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
                                        $empty_stars = 5 - ($full_stars + $half_star);
                                        for ($i = 1; $i <= $full_stars; $i++) {
                                            echo '<i class="fas fa-star text-warning"></i>';
                                        }

                                        if ($half_star) {
                                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                        }

                                        for ($i = 1; $i <= $empty_stars; $i++) {
                                            echo '<i class="far fa-star text-warning"></i>';
                                        }
                                        ?>


                                        <span class="text-muted ml-2"> <?php echo $total_reviews; ?> Reviews</span>
                                    </h6>
                                    <h6 class="text-muted"><?php echo $categories; ?></h6>
                                    <button class="btn btn-secondary btn-sm" style="border-radius:25px;"><i
                                            class="fas fa-directions pr-1"></i> Direction</button>
                                    <button class="btn btn-outline-secondary btn-sm ml-1" style="border-radius:25px;">
                                        <i class="fas fa-location-arrow pr-1"></i>Start</button>
                                    <button class="btn btn-outline-secondary btn-sm ml-1" style="border-radius:25px;"><i
                                            class="fas fa-external-link-alt pr-1"></i> Share</button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <h5 class="font-weight-bold">How to get there? </h5>


                                    <?php
                                    $location_instructions = explode(',', $instruction);
                                    echo '<div class="timeline-container">';
                                    echo '<div class="timeline">';

                                    $total_items = count($location_instructions); // Get the total number of items
                                    $current_item = 1; // Initialize the item counter
                                    
                                    foreach ($location_instructions as $location_instruction) {
                                        list($location, $detail_instruction) = explode('-', $location_instruction);

                                        echo '<div>';
                                        echo '<i class="fas fa-dot-circle" style="font-size:5px !important;"></i>';
                                        echo '<div class="timeline-item">';
                                        echo '<div class="timeline-body">';
                                        echo "<strong>$location</strong> <br>";
                                        echo $detail_instruction;
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';

                                        $current_item++; // Increment the item counter
                                    }

                                    echo '</div>';
                                    echo '</div>';
                                    ?>




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
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.0/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.0/mapbox-gl.js"></script>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoicGZkZWxmaW45OCIsImEiOiJjbHV0YjNuNjQwdnZmMmlwazBydnV4N2lwIn0.nqsd0AUgvz-c3j38EhpB1w';

        const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v11', // style URL
            center: [-74.5, 40], // default position [lng, lat]
            zoom: 9 // default zoom
        });

        // Coordinates for Batangas
        const batangas = [121.0667, 13.75];

        // Add a marker for Batangas
        new mapboxgl.Marker({ color: 'red' })
            .setLngLat(batangas)
            .setPopup(new mapboxgl.Popup().setText('Batangas'))
            .addTo(map);

        // Function to handle geolocation success
        function success(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Center the map on the user's location
            map.setCenter([longitude, latitude]);
            map.setZoom(10);

            // Add a marker at the user's location
            new mapboxgl.Marker({ color: 'blue' })
                .setLngLat([longitude, latitude])
                .setPopup(new mapboxgl.Popup().setText('You are here!'))
                .addTo(map);

            // Draw a route from the user's location to Batangas
            drawRoute([longitude, latitude], batangas);
        }

        // Function to handle geolocation errors
        function error(err) {
            console.error('Error occurred while retrieving location:', err);
            alert('Unable to retrieve your location. Please make sure location services are enabled and permissions are granted.');
        }

        // Function to draw a route using Mapbox Directions API
        function drawRoute(start, end) {
            map.addControl(new mapboxgl.NavigationControl());

            // Create a directions request
            const directionsRequest = `https://api.mapbox.com/directions/v5/mapbox/driving/${start.join(',')};${end.join(',')}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`;

            fetch(directionsRequest)
                .then(response => response.json())
                .then(data => {
                    const route = data.routes[0].geometry.coordinates;

                    // Add the route to the map
                    map.addSource('route', {
                        'type': 'geojson',
                        'data': {
                            'type': 'Feature',
                            'geometry': {
                                'type': 'LineString',
                                'coordinates': route
                            }
                        }
                    });

                    map.addLayer({
                        'id': 'route',
                        'type': 'line',
                        'source': 'route',
                        'layout': {
                            'line-join': 'round',
                            'line-cap': 'round'
                        },
                        'paint': {
                            'line-color': '#ff7e5f',
                            'line-width': 8
                        }
                    });
                })
                .catch(err => console.error('Error fetching directions:', err));
        }

        // Check if geolocation is available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success, error, {
                enableHighAccuracy: true, // Request high accuracy
                timeout: 5000, // Timeout for requesting location
                maximumAge: 0 // Prevents using cached location
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    </script>







    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showBottomSheet(); // Automatically show the bottom sheet on page load
        });

        const showModalBtn = document.querySelector(".show-modal");
        const bottomSheet = document.querySelector(".bottom-sheet");
        const sheetOverlay = bottomSheet.querySelector(".sheet-overlay");
        const sheetContent = bottomSheet.querySelector(".content");
        const dragIcon = bottomSheet.querySelector(".drag-icon");

        // Global variables for tracking drag events
        let isDragging = false, startY, startHeight;

        // Show the bottom sheet, hide body vertical scrollbar, and call updateSheetHeight
        const showBottomSheet = () => {
            bottomSheet.classList.add("show");
            document.body.style.overflowY = "hidden";
            updateSheetHeight(50);
        };
        const updateSheetHeight = (height) => {
            sheetContent.style.height = `${height}vh`; // Update height of the sheet content

            // Toggle fullscreen class based on height
            bottomSheet.classList.toggle("fullscreen", height === 100);

            // Set pointer-events based on height
            if (height <= 15) {
                document.querySelector('#map').style.pointerEvents = 'auto'; // Allow map interactions
            } else {
                document.querySelector('#map').style.pointerEvents = 'none'; // Disable map interactions
            }
        };

        // Other existing JavaScript functions remain unchanged

        // Hide the bottom sheet and show body vertical scrollbar
        const hideBottomSheet = () => {
            updateSheetHeight(15); // Set to minimized height
            document.querySelector('#map').style.zIndex = '2'; // Set z-index to ensure the map is clickable
        };


        const dragging = (e) => {
            if (!isDragging) return;
            const delta = startY - (e.pageY || e.touches?.[0].pageY);
            const newHeight = startHeight + (delta / window.innerHeight) * 100;

            updateSheetHeight(newHeight);
            document.querySelector('#map').style.zIndex = '';
            bottomSheet.style.zIndex = '200';
        };

        const dragStop = () => {
            isDragging = false;
            bottomSheet.classList.remove("dragging");
            const sheetHeight = parseInt(sheetContent.style.height, 10);

            document.querySelector('#map').style.zIndex = '';

            if (sheetHeight > 25) {
                bottomSheet.style.zIndex = '200';
                updateSheetHeight(80); // Optional: Adjust to a specific height if needed
                document.querySelector('#map').style.zIndex = '';
            } else {
                document.querySelector('#map').style.zIndex = '200';
                bottomSheet.style.zIndex = '';
                sheetHeight < 25 ? hideBottomSheet() : updateSheetHeight(50);
            }
        };


        // Set initial drag position, sheetContent height and add dragging class to the bottom sheet
        const dragStart = (e) => {
            isDragging = true;
            startY = e.pageY || e.touches?.[0].pageY;
            startHeight = parseInt(sheetContent.style.height, 10);
            bottomSheet.classList.add("dragging");
            bottomSheet.style.zIndex = '10';
            document.querySelector('#map').style.zIndex = '';

        };


        dragIcon.addEventListener("mousedown", dragStart);
        document.addEventListener("mousemove", dragging);
        document.addEventListener("mouseup", dragStop);
        dragIcon.addEventListener("touchstart", dragStart);
        document.addEventListener("touchmove", dragging);
        document.addEventListener("touchend", dragStop);
        sheetOverlay.addEventListener("click", hideBottomSheet);
        showModalBtn.addEventListener("click", showBottomSheet);
    </script>