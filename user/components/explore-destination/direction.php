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
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        opacity: 0;
        pointer-events: none;
        align-items: flex-end;
        justify-content: center;
        transition: opacity 0.3s ease, transform 0.3s ease;
        z-index: 1;
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
                    <h6 class="font-weight-bold">
                        <?php echo $rating; ?> &nbsp
                        <?php
                        // Rating stars logic
                        $rating = round($rating, 1);
                        $full_stars = floor($rating);
                        $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
                        $empty_stars = 5 - ($full_stars + $half_star);

                        for ($i = 1; $i <= $full_stars; $i++)
                            echo '<i class="fas fa-star text-warning"></i>';
                        if ($half_star)
                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                        for ($i = 1; $i <= $empty_stars; $i++)
                            echo '<i class="far fa-star text-warning"></i>';
                        ?>
                        <span class="text-muted ml-2"> <?php echo $total_reviews; ?> Reviews</span>
                    </h6>
                    <h6 class="text-muted"><?php echo $categories; ?></h6>

                    <button class="btn btn-secondary btn-sm" id="get-directions">Direction</button>
                    <button class="btn btn-outline-secondary btn-sm ml-1" id="get-navigation">Start</button>
                    <button class="btn btn-outline-secondary btn-sm ml-1">Share</button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <h5 class="font-weight-bold">How to get there?</h5>
                    <?php
                    $location_instructions = explode(',', $instruction);
                    echo '<div class="timeline-container"><div class="timeline">';
                    foreach ($location_instructions as $location_instruction) {
                        $parts = explode('-', $location_instruction);
                        if (count($parts) === 2) {
                            list($location, $detail_instruction) = $parts;
                            echo "<div><i class='fas fa-dot-circle' style='font-size:5px;'></i><div class='timeline-item'><div class='timeline-body'><strong>$location</strong><br>$detail_instruction</div></div></div>";
                        }
                    }
                    echo '</div></div>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const bottomSheet = document.querySelector(".bottom-sheet");
        const overlay = document.querySelector(".sheet-overlay");
        const content = document.querySelector(".content");
        const dragIcon = document.querySelector(".drag-icon");
        const showModalButton = document.querySelector(".show-modal");

        let isDragging = false;
        let isBottomSheetVisible = false; // Track visibility state
        let startY, startHeight;

        // Show/Hide Bottom Sheet
        const toggleBottomSheet = (show = true) => {
            isBottomSheetVisible = show;
            bottomSheet.classList.toggle("show", show);
            document.body.style.overflowY = show ? "hidden" : "auto";

            if (show) {
                content.style.height = "60vh"; // Reset to initial height on show
            } else {
                content.style.height = "0";    // Explicitly set to zero on hide
            }
        };

        // Handle Dragging
        const dragStart = (e) => {
            isDragging = true;
            startY = e.pageY || e.touches[0].pageY;
            startHeight = parseInt(window.getComputedStyle(content).height);
            bottomSheet.classList.add("dragging");
        };

        const dragging = (e) => {
            if (!isDragging) return;
            const currentY = e.pageY || e.touches[0].pageY;
            const delta = startY - currentY;
            const newHeight = Math.max(15, Math.min(startHeight + delta, window.innerHeight * 0.9));
            content.style.height = `${newHeight}px`;
        };

        const dragStop = () => {
            isDragging = false;
            bottomSheet.classList.remove("dragging");
            const sheetHeight = parseInt(content.style.height);
            toggleBottomSheet(sheetHeight > window.innerHeight * 0.5);
        };

        dragIcon.addEventListener("mousedown", dragStart);
        document.addEventListener("mousemove", dragging);
        document.addEventListener("mouseup", dragStop);
        dragIcon.addEventListener("touchstart", dragStart);
        document.addEventListener("touchmove", dragging);
        document.addEventListener("touchend", dragStop);

        // Toggle Bottom Sheet on Overlay Click
        overlay.addEventListener("click", () => toggleBottomSheet(false));

        // Button to Show Modal
        showModalButton.addEventListener("click", () => {
            if (isBottomSheetVisible) {
                toggleBottomSheet(false); // Close first if already visible
                setTimeout(() => toggleBottomSheet(true), 300); // Re-open after a short delay
            } else {
                toggleBottomSheet(true); // Open directly if not visible
            }
        });
    });

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