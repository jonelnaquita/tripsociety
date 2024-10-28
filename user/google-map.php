<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps Directions</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <h1>Get Directions</h1>
    <button id="get-directions">Start Driving Navigation</button>

    <script>
        $(document).ready(function () {
            // Latitude and longitude of the destination
            const destinationLat = 14.5995; // Replace with your destination latitude
            const destinationLng = 120.9842; // Replace with your destination longitude

            // When the button is clicked, get the current location and open Google Maps
            $('#get-directions').click(function () {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        // Get current latitude and longitude
                        const currentLat = position.coords.latitude;
                        const currentLng = position.coords.longitude;

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
                        alert("Error getting location: " + error.message);
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            });
        });
    </script>

    <script>
        const earthRadiusKm = 6371;

        // Function to convert degrees to radians
        function degreesToRadians(degrees) {
            return degrees * (Math.PI / 180);
        }

        // Function to calculate the bounding box
        function getBoundingBox(lat, lon, distanceInMeters) {
            const latInRad = degreesToRadians(lat);
            const lonInRad = degreesToRadians(lon);

            // Distance in radians
            const distanceInKm = distanceInMeters / 1000;

            // Latitude boundaries
            const deltaLat = distanceInKm / earthRadiusKm;

            // Longitude boundaries (adjusted by the latitude)
            const deltaLon = distanceInKm / (earthRadiusKm * Math.cos(latInRad));

            // Bounding box
            const minLat = lat - deltaLat * (180 / Math.PI);
            const maxLat = lat + deltaLat * (180 / Math.PI);
            const minLon = lon - deltaLon * (180 / Math.PI);
            const maxLon = lon + deltaLon * (180 / Math.PI);

            return {
                minLat,
                maxLat,
                minLon,
                maxLon,
            };
        }

        const lat = 14.01580292352516;
        const lon = 120.87307691727602;
        const distance = 500; // in meters

        const boundingBox = getBoundingBox(lat, lon, distance);
        console.log(boundingBox);
    </script>
</body>

</html>