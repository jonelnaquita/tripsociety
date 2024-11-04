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
    /* Bottom Sheet Container */
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

    /* Show state for Bottom Sheet */
    .bottom-sheet.show {
        opacity: 1;
        pointer-events: auto;
    }

    /* Bottom Sheet Content */
    .bottom-sheet .content {
        width: 90%;
        max-width: 500px;
        background: #fff;
        height: 40vh;
        transform: translateY(100%);
        border-radius: 12px 12px 0 0;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    /* Show full height when open */
    .bottom-sheet.show .content {
        transform: translateY(0);
    }

    /* Overlay */
    .sheet-overlay {
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.2);
    }

    /* Drag Icon */
    .drag-icon {
        display: flex;
        justify-content: center;
        cursor: grab;
        padding: 15px;
    }

    .drag-icon span {
        display: block;
        width: 40px;
        height: 4px;
        background: #C7D0E1;
        border-radius: 50px;
    }

    .body {
        padding: 15px 20px;
        text-align: center;
    }

    @media (max-width: 600px) {
        .bottom-sheet .content {
            width: 100%;
        }
    }

    #get-directions,
    #get-navigation,
    #share {
        border-radius: 30px;
        padding: 8px 12px;
        /* Adjusted padding for better spacing */
    }

    /* Optional: Add hover effects for better UX */
    #get-directions:hover,
    #get-navigation:hover,
    #share:hover {
        background-color: rgba(0, 0, 0, 0.1);
        /* Light background on hover */
    }

    .instructions-container {
        text-align: left;
        /* Ensures text is aligned to the left */
    }

    .instructions-container strong {
        display: block;
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
            <div class="row bottom-sheet-header">
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

                    <!-- Direction Button -->
                    <button class="btn btn-secondary btn-sm rounded-pill" id="get-directions">
                        <i class="fas fa-directions"></i> Direction
                    </button>

                    <!-- Navigation Button -->
                    <button class="btn btn-outline-secondary btn-sm ml-1 rounded-pill" id="get-navigation">
                        <i class="fas fa-play"></i> Start
                    </button>

                </div>
            </div>

            <div class="row mt-3">
                <div class="col instructions-container">
                    <h5 class="font-weight-bold">How to get there?</h5>
                    <!-- Instructions will be dynamically injected here by AJAX -->
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
        const dragIcon = document.querySelector(".drag-icon");
        const content = bottomSheet.querySelector(".content");
        let isDragging = false;
        let startY, startHeight;

        // Toggle Bottom Sheet Visibility
        const toggleBottomSheet = (show) => {
            bottomSheet.classList.toggle("show", show);
            document.body.style.overflowY = show ? "hidden" : "auto";
            content.style.height = show ? "50vh" : "0"; // Set initial height on open
        };

        // Show Bottom Sheet on Button Click
        viewRouteBtn.addEventListener("click", (e) => {
            e.preventDefault();
            toggleBottomSheet(true);
        });

        // Hide Bottom Sheet on Overlay Click
        overlay.addEventListener("click", () => toggleBottomSheet(false));

        // Dragging Functionality
        const dragStart = (e) => {
            e.preventDefault();
            isDragging = true;
            startY = e.pageY || e.touches[0].pageY;
            startHeight = content.offsetHeight;
        };

        const dragging = (e) => {
            if (!isDragging) return;

            const currentY = e.pageY || e.touches[0].pageY;
            const delta = startY - currentY;
            const newHeight = Math.min(Math.max(15, startHeight + delta), window.innerHeight * 0.9);

            // Apply new height smoothly
            content.style.height = `${newHeight}px`;
        };

        const dragStop = () => {
            if (isDragging) {
                isDragging = false;
                // Optionally, snap to closest position
                const currentHeight = content.offsetHeight;
                if (currentHeight < window.innerHeight * 0.3) {
                    toggleBottomSheet(false); // Close if dragged down significantly
                }
            }
        };

        // Event Listeners for Dragging with Touch and Mouse Events
        dragIcon.addEventListener("mousedown", dragStart);
        document.addEventListener("mousemove", dragging);
        document.addEventListener("mouseup", dragStop);

        dragIcon.addEventListener("touchstart", (e) => {
            dragStart(e);
            e.preventDefault();
        }, { passive: false });

        document.addEventListener("touchmove", (e) => {
            dragging(e);
            e.preventDefault();
        }, { passive: false });

        document.addEventListener("touchend", dragStop);
    });
</script>


<script>
    $(document).ready(function () {
        $('#get-directions').click(function () {
            const button = $(this);
            const icon = button.find('i');
            const locationId = '<?php echo $_GET['id']; ?>';

            // Show spinner, disable button
            icon.removeClass('fa-directions').addClass('fa-spinner fa-spin');
            button.prop('disabled', true);

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

                                // Construct Google Maps URL and redirect
                                const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${currentLat},${currentLng}&destination=${destinationLat},${destinationLng}&travelmode=driving`;

                                // Redirect to Google Maps
                                window.location.href = googleMapsUrl;

                                resetButtonState(button, icon);

                            }, function (error) {
                                alert("Error getting current location: " + error.message);
                                resetButtonState(button, icon);
                            });
                        } else {
                            alert("Geolocation is not supported by this browser.");
                            resetButtonState(button, icon);
                        }
                    } else {
                        alert("Location not found");
                        resetButtonState(button, icon);
                    }
                },
                error: function () {
                    alert("Failed to fetch location data");
                    resetButtonState(button, icon);
                }
            });
        });

        // Function to reset the button state
        function resetButtonState(button, icon) {
            button.text("Direction").prop('disabled', false);
            icon.removeClass('fa-spinner fa-spin').addClass('fa-directions');
        }
    });
</script>


<script>
    $(document).ready(function () {
        $('#get-navigation').click(function () {
            const button = $(this);
            const icon = button.find('i');
            const locationId = '<?php echo $_GET['id']; ?>';

            // Show spinner, disable button
            icon.removeClass('fa-play').addClass('fa-spinner fa-spin');
            button.prop('disabled', true);

            $.ajax({
                url: 'api/search/fetch-location.php',
                type: 'POST',
                data: { id: locationId },
                dataType: 'json',
                success: function (response) {
                    // Check if latitude and longitude are present
                    if (response.latitude && response.longitude) {
                        const destinationLat = response.latitude;
                        const destinationLng = response.longitude;

                        // Get user's current location
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function (position) {
                                const currentLat = position.coords.latitude;
                                const currentLng = position.coords.longitude;

                                // Detect platform
                                const isAndroid = /android/i.test(navigator.userAgent);
                                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

                                // Prepare navigation URL
                                let navigationUrl;
                                if (isAndroid) {
                                    navigationUrl = `google.navigation:q=${destinationLat},${destinationLng}&mode=d`;
                                    resetButton();
                                } else if (isIOS) {
                                    navigationUrl = `maps://?q=${destinationLat},${destinationLng}&dirflg=d`;
                                    resetButton();
                                } else {
                                    alert("Navigation is only supported on Android and iOS devices.");
                                    resetButton();
                                    return; // Exit the function
                                }

                                // Redirect after a slight delay to allow spinner to stop
                                setTimeout(() => {
                                    window.location.href = navigationUrl;
                                }, 100); // Adjust delay as needed (100ms)

                            }, function (error) {
                                alert("Error getting current location: " + error.message);
                                resetButton();
                            });
                        } else {
                            alert("Geolocation is not supported by this browser.");
                            resetButton();
                        }
                    } else {
                        alert("Location not found");
                        resetButton();
                    }
                },
                error: function () {
                    alert("Failed to fetch location data");
                    resetButton();
                }
            });

            // Reset button function
            function resetButton() {
                button.text("Start").prop('disabled', false);
                icon.removeClass('fa-spinner fa-spin').addClass('fa-play');
            }
        });
    });
</script>



<!-- CSS to make the container scrollable -->
<style>
    .instructions-container {
        max-height: 500px;
        border-radius: 10px;
        /* Set a maximum height */
        overflow-y: auto;
        /* Enable vertical scrolling */
        border: 1px solid #ccc;
        /* Optional: Add a border for visibility */
        padding: 10px;
        /* Optional: Add padding for aesthetics */
        margin-top: 10px;
    }
</style>

<!-- JavaScript to fetch instructions -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const locationId = new URLSearchParams(window.location.search).get('id'); // Get the location_id from URL

        // Function to fetch instructions
        const fetchInstructions = () => {
            fetch(`api/search/fetch-instruction.php?location_id=${locationId}`)
                .then(response => response.json())
                .then(data => {
                    const instructionsContainer = document.querySelector('.instructions-container');
                    instructionsContainer.innerHTML = ''; // Clear existing content

                    // Add "How To Get There" heading
                    const heading = document.createElement('h6');
                    heading.innerHTML = '<strong>How To Get There</strong>'; // Using strong tag for bold
                    instructionsContainer.appendChild(heading)

                    if (data.length > 0) {
                        const timelineContainer = document.createElement('div');
                        timelineContainer.className = 'timeline-container';
                        const timeline = document.createElement('div');
                        timeline.className = 'timeline';

                        data.forEach(item => {
                            const timelineItem = document.createElement('div');
                            timelineItem.innerHTML = `
                            <i class='fas fa-dot-circle' style='font-size:5px;'></i>
                            <div class='timeline-item'>
                                <div class='timeline-body'>
                                    <strong>${item.location}</strong><br>${item.instruction}
                                </div>
                            </div>
                        `;
                            timeline.appendChild(timelineItem);
                        });

                        timelineContainer.appendChild(timeline);
                        instructionsContainer.appendChild(timelineContainer);
                    } else {
                        instructionsContainer.innerHTML += '<p>No instructions available.</p>';
                    }
                })
                .catch(error => console.error('Error fetching instructions:', error));
        };

        // Call the function to fetch instructions on page load
        fetchInstructions();
    });
</script>