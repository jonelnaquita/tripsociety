<!-- Carousel HTML -->
<div class="row mt-3 filtered-container" style="display: none;">
    <div class="col">
        <h5 class="font-weight-bold">Filtered Destinations</h5>

        <div class="filtered-class">
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col">
        <h5 class="font-weight-bold">Top Destinations</h5>
        <h6 style="color:#002D62; margin-top:-7px;">Explore the wonders of Batangas</h6>
    </div>
</div>
<div class="carousel-container">
    <div class="your-class">
        <!-- Dynamic content for initial fetch will be inserted here -->
    </div>
</div>

<script>
    $(document).ready(function () {
        let isFiltered = false; // Flag to indicate if filtering is applied
        let isFetching = false; // Flag to prevent duplicate fetch requests

        // Function to fetch initial locations via AJAX
        function initialFetch() {
            if (!isFiltered && !isFetching) {
                isFetching = true;
                $.ajax({
                    url: 'api/search/fetch-top-destination.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (locations) {
                        renderLocations(locations, '.your-class');
                        isFetching = false;
                    },
                    error: function (error) {
                        console.log('Error fetching initial locations:', error);
                        isFetching = false;
                    }
                });
            }
        }

        // Render locations to the specified container
        function renderLocations(locations, container) {
            // Remove any existing slick instance
            if ($(container).hasClass('slick-initialized')) {
                $(container).slick('unslick'); // Unslick previous initialization
            }

            // Clear the container before rendering new content
            $(container).empty(); // Clear previous content

            let locationHTML = '';

            $.each(locations, function (index, location) {
                var imageArray = location.image.split(',');
                var firstImage = imageArray.length > 0 ? imageArray[0] : 'default.jpg';

                locationHTML += `
                <a href="explore_destination.php?search&id=${location.id}" class="text-dark destination-card">
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

            // Insert new content into the container
            $(container).html(locationHTML);

            // Initialize Slick slider
            $(container).slick({
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
            if (!isFetching) {
                isFiltered = true;
                isFetching = true;

                // Show the filtered container and fade in new results
                $('.filtered-container').fadeIn(200);

                $.ajax({
                    url: 'api/search/filter-top-destination.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        category: category,
                        cities: cities.join(',')
                    },
                    success: function (locations) {
                        // Clear previous filtered data
                        $('.filtered-class').empty();

                        // Check if locations are returned
                        if (locations.length === 0) {
                            $('.filtered-class').html('<p>No results found.</p>');
                        } else {
                            renderLocations(locations, '.filtered-class'); // Render filtered locations
                        }
                        isFetching = false; // Reset fetching flag
                    },
                    error: function (error) {
                        console.log('Error fetching filtered locations:', error);
                        isFetching = false; // Reset fetching flag
                    }
                });
            }
        }

        // Handle filter application
        $(document).ready(function () {
            // Function to check if both fields are filled
            function checkFilters() {
                var selectedCategory = $('input[name="category"]:checked').val();
                var selectedCities = $('input[name="location[]"]:checked').length;

                // Enable or disable button based on selection
                if (selectedCategory && selectedCities > 0) {
                    $('#apply-filters').prop('disabled', false);
                } else {
                    $('#apply-filters').prop('disabled', true);
                }
            }

            // Event listeners to check fields whenever selections change
            $('input[name="category"]').change(checkFilters);
            $('input[name="location[]"]').change(checkFilters);

            // Handle filter application with error message
            $('#apply-filters').off('click').on('click', function () {
                if (!isFetching) {
                    var selectedCategory = $('input[name="category"]:checked').val();
                    var selectedCities = [];

                    $('input[name="location[]"]:checked').each(function () {
                        selectedCities.push($(this).val());
                    });

                    // Check if selectedCategory is empty or selectedCities is empty
                    if (!selectedCategory || selectedCities.length === 0) {
                        // Show toastr message if both fields are required
                        toastr.error('Both category and city selections are required.');
                    } else {
                        // Fetch locations if both fields are selected
                        fetchLocations(selectedCategory, selectedCities);
                    }
                }
            });

            // Initial call to set button state
            checkFilters();
        });

        // Initial fetch on page load
        initialFetch(); // Load initial data once on page load
    });

</script>