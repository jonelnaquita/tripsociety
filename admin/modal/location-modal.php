<style>
    #locationImageCarousel .carousel-item img {
        height: 400px;
        /* Set a fixed height as needed */
        width: 100%;
        object-fit: cover;
        /* Ensures the image covers the fixed height while maintaining aspect ratio */
    }
</style>

<div class="modal fade" id="locationDetailsModal" tabindex="-1" aria-labelledby="locationDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Carousel for multiple images -->
                <div id="locationImageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselImages"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#locationImageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#locationImageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <h5 id="locationName"></h5>
                <p style="margin-top: -10px;">
                    <i class="fa-solid fa-location-dot" style="margin-right: 10px;"></i>
                    <span id="locationCity"></span>
                </p>
                <span class="badge bg-gradient-success"><span id="locationCategory"></span></span>
                <p id="locationDescription" class="mt-4"></p>

                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">How to Get There</h6>
                                    <div id="content">
                                        <ul class="timeline">
                                            <!-- Instructions will be populated here -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.view-location', function () {
        const locationId = $(this).data('id');

        // Fetch the location details
        $.ajax({
            url: 'api/location/view-location.php',
            method: 'GET',
            data: { id: locationId },
            dataType: 'json',
            success: function (data) {
                $('#locationName').text(data.location_name);
                $('#locationDescription').text(data.description);
                $('#locationCategory').text(data.category);
                $('#locationCity').text(data.city);

                // Parse and display multiple images as carousel items
                const images = data.image.split(','); // Split comma-separated images
                let carouselItems = '';

                images.forEach((image, index) => {
                    carouselItems += `
                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                            <img src="images/${image.trim()}" class="d-block w-100" alt="Location Image">
                        </div>`;
                });
                $('#carouselImages').html(carouselItems);

                // Clear existing instructions
                $('#content .timeline').empty();

                if (data.instructions && data.instructions.length > 0) {
                    data.instructions.forEach(function (instruction) {
                        $('#content .timeline').append(`
                            <li class="event">
                                <h3 class="route-text">${instruction.route_text}</h3>
                                <p class="instruction-text">${instruction.instruction_text}</p>
                            </li>
                        `);
                    });
                } else {
                    $('#content .timeline').append(`
                        <li class="event">
                            <p>No instructions available.</p>
                        </li>
                    `);
                }

                $('#locationDetailsModal').modal('show');
            },
            error: function (xhr, status, error) {
                console.error("Error fetching location details: ", status, error);
            }
        });
    });
</script>