<form action="../inc/function.php" method="POST" hidden>
    <input type="hidden" id="locationInput" readonly name="current_location" placeholder="Fetching location...">
    <button type="submit" name="update_user_location" hidden></button>
</form>

<style>
    .active-state {
        color: #582fff;
        border-color: #fff;
        border-radius: 10px;

    }

    .main-footer {
        background-color: #fff;
    }

    .btn-footer {
        padding: 8px 0px;
    }
</style>

<?php if (isset($_SESSION['user'])): ?>
    <footer class="main-footer fixed custom-fixed-bottom p-0">
        <div class="row h-100 align-items-center">
            <div class="col d-flex justify-content-between mt-1 ml-3 mr-3 p-0">
                <a type="button" id="homeTab" style="font-size:14px;" href="home.php"
                    class="btn-footer btn btn-outline-dark flex-fill border-0"><i class="fas fa-home"></i><br>Home</a>
                <a type="button" id="searchTab" style="font-size:14px;" href="search.php"
                    class="btn-footer btn btn-outline-dark flex-fill border-0"><i class="fas fa-search"></i><br>Search</a>
                <a type="button" id="reviewsTab" style="font-size:14px;" href="reviews.php"
                    class="btn-footer btn btn-outline-dark flex-fill border-0"><i class="far fa-edit"></i><br>Reviews</a>
                <a type="button" id="messagesTab" style="font-size:14px;" href="messages.php"
                    class="btn-footer btn btn-outline-dark flex-fill border-0"><i
                        class="fas fa-envelope"></i><br>Messages</a>
                <button style="font-size:14px;" class="btn-footer btn btn-outline-dark flex-fill border-0"
                    data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i><br>More</button>
            </div>
        </div>
    </footer>
<?php endif; ?>


<script>
    // Get the current URL
    const currentPage = window.location.pathname;

    // Get all the buttons/links
    const tabs = document.querySelectorAll('.main-footer .btn');

    // Remove active class from all tabs
    tabs.forEach(tab => tab.classList.remove('active-state'));

    // Check the current URL and activate the corresponding tab
    if (currentPage.includes('home.php')) {
        document.getElementById('homeTab').classList.add('active-state');
    } else if (currentPage.includes('search.php')) {
        document.getElementById('searchTab').classList.add('active-state');
    } else if (currentPage.includes('reviews.php')) {
        document.getElementById('reviewsTab').classList.add('active-state');
    } else if (currentPage.includes('messages.php')) {
        document.getElementById('messagesTab').classList.add('active-state');
    }


</script>


</div>

</body>

</html>
<script>
    let currentLocation = '';

    function updateLocation(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        currentLocation = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
        $('#locationInput').val(currentLocation);
    }

    function handleError(error) {
        console.warn(`ERROR(${error.code}): ${error.message}`);
    }

    function startTracking() {
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(updateLocation, handleError, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 1000
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }

    function sendLocation() {
        if (currentLocation) {
            $.ajax({
                url: '../inc/function.php',
                type: 'POST',
                data: {
                    current_location: currentLocation,
                    update_user_location: true
                },
                success: function (response) {
                    console.log('Location updated successfully:', response);
                },
                error: function (xhr, status, error) {
                    console.error('Error updating location:', error);
                }
            });
        }
    }

    // Start tracking and sending the location every second
    startTracking();
    setInterval(sendLocation, 5000); // Send location every 1000 milliseconds (1 second)
</script>

<?php
if (isset($_SESSION['message']) && isset($_SESSION['response']) && isset($_SESSION['message_timestamp'])) {
    $message = $_SESSION['message'];
    $response = $_SESSION['response'];
    $timestamp = $_SESSION['message_timestamp'];

    if (time() - $timestamp <= 5) {
        echo '<script>
            $(document).ready(function() {';

        if ($response === 'Success') {
            echo 'toastr.success("' . $message . '");';
        } else {
            echo 'toastr.error("' . $message . '");';
        }

        echo '});
            </script>';
    } else {
        unset($_SESSION['message']);
        unset($_SESSION['response']);
        unset($_SESSION['message_timestamp']);
    }
}
?>