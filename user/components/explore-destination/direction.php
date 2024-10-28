<?php
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
        height: 60vh;
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

                    <button class="btn btn-secondary btn-sm" id="get-directions" style="border-radius:25px;">
                        <i class="fas fa-directions pr-1"></i> Direction
                    </button>

                    <button class="btn btn-outline-secondary btn-sm ml-1" id="get-navigation"
                        style="border-radius:25px;">
                        <i class="fas fa-location-arrow pr-1"></i> Start
                    </button>

                    <button class="btn btn-outline-secondary btn-sm ml-1" style="border-radius:25px;">
                        <i class="fas fa-external-link-alt pr-1"></i> Share
                    </button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <h5 class="font-weight-bold">How to get there? </h5>
                    <?php
                    $location_instructions = explode(',', $instruction);
                    echo '<div class="timeline-container">';
                    echo '<div class="timeline">';

                    foreach ($location_instructions as $location_instruction) {
                        // Split each instruction into location and detail instruction
                        $parts = explode('-', $location_instruction);
                        if (count($parts) === 2) {
                            list($location, $detail_instruction) = $parts;

                            echo '<div>';
                            echo '<i class="fas fa-dot-circle" style="font-size:5px !important;"></i>';
                            echo '<div class="timeline-item">';
                            echo '<div class="timeline-body">';
                            echo "<strong>$location</strong> <br>";
                            echo $detail_instruction;
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<div>';
                            echo '<i class="fas fa-dot-circle" style="font-size:5px !important;"></i>';
                            echo '<div class="timeline-item">';
                            echo '<div class="timeline-body">';
                            echo "<strong>Invalid Instruction:</strong> $location_instruction";
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                    echo '</div>';
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const viewRouteBtn = document.getElementById("viewRouteBtn");
        const bottomSheet = document.querySelector(".bottom-sheet");
        const overlay = document.querySelector(".sheet-overlay");

        viewRouteBtn.addEventListener("click", (e) => {
            e.preventDefault();
            bottomSheet.classList.toggle("show");
        });

        overlay.addEventListener("click", () => {
            bottomSheet.classList.remove("show");
        });
    });
</script>

<script>
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


<!-- Direction Script -->
<script>
    $(document).ready(function () {
        $('#get-directions').click(function () {
            const locationId = '<?php echo $_GET['id']; ?>'; // Dynamic ID

            // Make AJAX request to fetch latitude and longitude
            $.ajax({
                url: 'api/search/fetch-location.php',
                type: 'POST',
                data: { id: locationId },
                dataType: 'json',
                success: function (response) {
                    if (response.latitude && response.longitude) {
                        const destinationLat = response.latitude;
                        const destinationLng = response.longitude;

                        // Get user's current location
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function (position) {
                                const currentLat = position.coords.latitude;
                                const currentLng = position.coords.longitude;

                                // Open Google Maps Directions URL
                                const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${currentLat},${currentLng}&destination=${destinationLat},${destinationLng}&travelmode=driving`;
                                window.location.href = googleMapsUrl;
                            }, function (error) {
                                alert("Error getting current location: " + error.message);
                            });
                        } else {
                            alert("Geolocation is not supported by this browser.");
                        }
                    } else {
                        alert("Location not found");
                    }
                },
                error: function () {
                    alert("Failed to fetch location data");
                }
            });
        });
    });
</script>

<!-- Navigation Script -->
<script>
    $(document).ready(function () {
        $('#get-navigation').click(function () {
            const locationId = '<?php echo $_GET['id']; ?>'; // Dynamic ID

            // Make AJAX request to fetch latitude and longitude
            $.ajax({
                url: 'api/search/fetch-location.php',
                type: 'POST',
                data: { id: locationId },
                dataType: 'json',
                success: function (response) {
                    if (response.latitude && response.longitude) {
                        const destinationLat = response.latitude;
                        const destinationLng = response.longitude;

                        // Get user's current location
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function (position) {
                                const currentLat = position.coords.latitude;
                                const currentLng = position.coords.longitude;

                                // Detect the user's device platform (Android or iOS)
                                const isAndroid = /android/i.test(navigator.userAgent);
                                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

                                // Open the appropriate maps app based on the device
                                if (isAndroid) {
                                    // Open Google Maps on Android
                                    const googleMapsUrl = `google.navigation:q=${destinationLat},${destinationLng}&mode=d`;
                                    window.location.href = googleMapsUrl;
                                } else if (isIOS) {
                                    // Open Apple Maps on iOS
                                    const appleMapsUrl = `maps://?q=${destinationLat},${destinationLng}&dirflg=d`;
                                    window.location.href = appleMapsUrl;
                                } else {
                                    // Fallback for other devices or platforms (desktop)
                                    alert("Navigation is only supported on Android and iOS devices.");
                                }
                            }, function (error) {
                                alert("Error getting current location: " + error.message);
                            });
                        } else {
                            alert("Geolocation is not supported by this browser.");
                        }
                    } else {

                        alert("Location not found");
                    }
                },
                error: function () {
                    alert("Failed to fetch location data");
                }
            });
        });
    });
</script>