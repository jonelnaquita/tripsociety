<style>
    .recommendation-item {
        display: flex;
        /* Use flexbox for alignment */
        align-items: center;
        /* Center items vertically */
        background-color: white;
        /* Card background color */
        border-radius: 10px;
        /* Rounded corners */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Subtle shadow for depth */
        padding: 10px;
        /* Padding inside the card */
        transition: box-shadow 0.3s ease;
        /* Smooth shadow transition */
    }

    .recommendation-item:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        /* Deeper shadow on hover */
    }

    .recommendation-img {
        width: 70px;
        /* Set a consistent width */
        height: 70px;
        /* Set a consistent height */
        object-fit: cover;
        /* Ensure the image covers the container without distortion */
        border-radius: 5px;
        /* Round image corners */
        margin-right: 10px;
        /* Space between image and text */
    }

    h5 {
        font-weight: bold;
        /* Bold header */
        color: #582fff;
        /* Custom header color */
    }

    .recommendation-text {
        text-align: left;
        /* Align text to the left */
        flex-grow: 1;
        /* Allow text to take up available space */
    }

    .recommendation-text h6 {
        font-size: 12px;
        /* Font size for the location name */
        margin: 0;
        /* Remove default margin */
    }
</style>

<div class="row mt-3">
    <div class="col">
        <h5>Recommendations</h5>
    </div>
</div>
<div class="row" id="recommendations-container">
    <!-- Recommended locations will be dynamically inserted here -->
</div>

<script>
    function fetchRecommendations() {
        var user_id = <?php echo isset($_SESSION['user']) ? json_encode($_SESSION['user']) : 'null'; ?>; // Replace with actual user ID

        $.ajax({
            url: 'api/search/fetch-recommendation.php', // Path to the PHP script
            type: 'POST',
            data: { user_id: user_id },
            dataType: 'json',
            success: function (locations) {
                var recommendationsHTML = '';

                if (locations.error) {
                    recommendationsHTML = '<p>' + locations.error + '</p>';
                } else {
                    $.each(locations, function (index, location) {
                        recommendationsHTML += `
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="explore_destination.php?id=${location.id}" class="text-dark">
                                    <div class="recommendation-item">
                                        <img src="../admin/images/${location.image.split(',')[0]}" alt="${location.location_name}" class="img-fluid recommendation-img">
                                        <div class="recommendation-text">
                                            <h6 class="font-weight-bold">${location.location_name}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>`;
                    });
                }

                // Update the recommendations container with the new data
                $('#recommendations-container').html(recommendationsHTML);
            },
            error: function (xhr, status, error) {
                console.log('Error fetching recommendations:', error);
            }
        });
    }

    // Fetch recommendations on page load
    $(document).ready(function () {
        fetchRecommendations();
    });
</script>