<!-- Location Details Modal -->
<div class="modal fade" id="locationDetailsModal" tabindex="-1" aria-labelledby="locationDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img id="locationImage" src="" alt="" class="img-fluid mb-3" />
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
    // Event delegation to handle click events on dynamically created elements
    $(document).on('click', '.view-location', function () {
        const locationId = $(this).data('id');

        // Fetch the location details
        $.ajax({
            url: 'api/location/view-location.php', // Replace with your actual endpoint
            method: 'GET',
            data: { id: locationId },
            dataType: 'json',
            success: function (data) {
                // Populate the modal with location details
                $('#locationName').text(data.location_name);
                $('#locationImage').attr('src', 'images/' + data.image); // Update image path
                $('#locationDescription').text(data.description);
                $('#locationCategory').text(data.category);
                $('#locationCity').text(data.city);

                // Clear existing instructions
                $('#content .timeline').empty();

                // Check if instructions are available
                if (data.instructions && data.instructions.length > 0) {
                    // Populate instructions
                    data.instructions.forEach(function (instruction) {
                        $('#content .timeline').append(`
            <li class="event">
                <h3 class="route-text">${instruction.route_text}</h3>
                <p class="instruction-text">${instruction.instruction_text}</p>
            </li>
        `);
                    });
                } else {
                    // If there are no instructions, display a message
                    $('#content .timeline').append(`
        <li class="event">
            <p>No instructions available.</p>
        </li>
    `);
                }


                // Show the modal
                $('#locationDetailsModal').modal('show');
            },
            error: function (xhr, status, error) {
                console.error("Error fetching location details: ", status, error);
            }
        });
    });

</script>