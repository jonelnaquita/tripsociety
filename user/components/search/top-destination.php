<!-- Carousel HTML -->
<div class="row mt-3">
    <div class="col">
        <h5 class="font-weight-bold">Top Destinations</h5>
        <h6 style="color:#002D62; margin-top:-7px;">Explore the wonders of Batangas</h6>
    </div>
</div>
<div class="carousel-container">
    <div class="your-class">
        <!-- Dynamic content will be inserted here -->
    </div>
</div>

<script>
    $(document).ready(function () {
        // Fetch locations via AJAX on initial load
        function initialFetch() {
            $.ajax({
                url: 'api/search/fetch-top-destination.php',
                type: 'GET',
                dataType: 'json',
                success: function (locations) {
                    renderLocations(locations);
                },
                error: function (error) {
                    console.log('Error fetching initial locations:', error);
                }
            });
        }

        // Render locations to the carousel
        function renderLocations(locations) {
            var locationHTML = '';

            $.each(locations, function (index, location) {
                var imageArray = location.image.split(',');
                var firstImage = imageArray.length > 0 ? imageArray[0] : 'default.jpg';

                locationHTML += `
                <a href="explore_destination.php?search&id=${location.id}" class="text-dark">
                    <div class="mr-3">
                        <span class="badge badge-light p-2 m-2">
                            <i class="fas fa-star text-warning"></i>
                            ${parseFloat(location.average_rating).toFixed(1)}
                        </span>
                        <i class="d-block w-100 p-3 text-center" id="img-gallery"
                            style="font-size:60px; margin-top:-40px; background-image: url('../admin/images/${firstImage}'); background-size: cover;">
                        </i>
                        <h6 class="text-center font-weight-bold"
                            style="margin-top:10px; height:50px; font-size:13px; margin-bottom:-15px;">
                            ${location.location_name}
                        </h6>
                    </div>
                </a>`;
            });

            // Update the carousel container with new content
            $('.your-class').html(locationHTML);

            // Re-initialize the Slick Carousel after content update
            if ($('.your-class').hasClass('slick-initialized')) {
                $('.your-class').slick('unslick'); // Unslick if already initialized
            }

            // Initialize the Slick Carousel
            $('.your-class').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        }

        // Fetch locations when filter is applied
        function fetchLocations(category, cities) {
            // Clear the existing carousel content
            $('.your-class').html(''); // Clear existing content

            $.ajax({
                url: 'api/search/filter-top-destination.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    category: category,
                    cities: cities.join(',') // Join cities as a comma-separated string
                },
                success: function (locations) {
                    console.log('Filtered Locations:', locations); // Log the response to verify it
                    renderLocations(locations); // Render the filtered locations
                },
                error: function (error) {
                    console.log('Error fetching filtered locations:', error);
                }
            });
        }

        // Handle filter application
        $('#apply-filters').on('click', function () {
            var selectedCategory = $('input[name="category"]:checked').val();
            var selectedCities = [];

            $('input[name="location[]"]:checked').each(function () {
                selectedCities.push($(this).val());
            });

            console.log('Applying filters:', selectedCategory, selectedCities); // Log selected filters

            // Fetch locations with the selected category and cities
            fetchLocations(selectedCategory, selectedCities);
        });

        // Initial fetch on page load
        initialFetch(); // Load initial data
    });
</script>