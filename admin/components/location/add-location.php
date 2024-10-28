<style>
    /* Ensure the select element takes full width */
    .select2-container {
        width: 100% !important;
        /* Force Select2 container to be 100% wide */
    }

    .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        min-height: 38px;
        /* Set minimum height for better appearance */
    }

    .how-to-get-there {
        margin-bottom: 20px;
    }

    .remove-route-btn {
        float: right;
        font-size: 12px;
        margin-top: -20px;
        color: red;
        cursor: pointer;
    }

    #map {
        height: 400px;
        width: 100%;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Add New Destination</h6>
                </div>
                <button type="button" class="btn btn-light mt-4 back-table-location"><i
                        class="fa-solid fa-caret-left"></i> Back</button>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="container">
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Location Name</label>
                                <input type="text" id="location-name" class="form-control">
                            </div>

                            <div class="input-group input-group-outline my-3">
                                <input type="text" id="location" name="location1" class="form-control"
                                    placeholder="Latitude, Longitude">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#mapNewModal">
                                    <i class="fas fa-map-pin"></i>
                                </button>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group-dynamic mb-4">
                                    <textarea id="description" class="form-control" rows="5"
                                        placeholder="Tell something about the destination or tourist spot."
                                        spellcheck="false"></textarea>
                                </div>
                            </div>

                            <label for="exampleFormControlSelect1" class="ms-0">Category</label>
                            <div class="input-group input-group-static mb-4">
                                <select class="form-control" id="category" multiple="multiple">
                                    <option value="Nature">Nature</option>
                                    <option value="Mountain">Mountain</option>
                                    <option value="Historical">Historical</option>
                                    <option value="Beach">Beach</option>
                                    <option value="Church">Church</option>
                                    <option value="Cultural">Cultural</option>
                                    <option value="Relaxation">Relaxation</option>
                                </select>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label for="city" class="ms-0">City / Municipality</label>
                                <select class="form-control" id="city-municipality">
                                    <!-- City options will be dynamically populated here -->
                                </select>
                            </div>
                        </div>

                        <!-- Second Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="how-to-get-there">
                                        <h5>How to get there?</h5>
                                        <div id="instructions-container">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group input-group-outline mb-4">
                                                        <label class="form-label">Route</label>
                                                        <input type="text" class="form-control route">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group input-group-outline mb-4">
                                                        <label class="form-label">Details</label>
                                                        <input type="text" class="form-control details">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="float-right">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                id="add-route-btn" style="width: 150px; margin-top: 10px;">
                                                <i class="fa-solid fa-plus"></i> Add More
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-4 mt-4">
                                <label for="imageInput">Upload Location Images</label><br>
                                <input type="file" id="imageInput" name="images" accept="image/*">
                            </div>

                            <div class="col-lg-12 mb-4">
                                <label for="file-upload">Upload 360° Virtual Tour</label>
                                <br>
                                <input id="file-upload" name="tour_link" type="file" accept="image/*" />
                            </div>

                        </div>
                    </div>
                    <button type="button" class="btn btn-info btn-lg" id="save-button">Save</button>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="mapNewModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="map" style="width: 100%; height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="selectLocation" class="btn btn-primary">Select Location</button>
            </div>
        </div>
    </div>
</div>




<style>
    /* Optional: Add some styling */
    .select2-container .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .select2-container--default .select2-selection--single {
        height: 38px;
        /* Aligns the Select2 dropdown height with the other Bootstrap inputs */
    }
</style>

<script>
    // Array of cities
    const cities = [
        'Batangas City',
        'Lipa City',
        'Tanauan City',
        'Balayan',
        'Batangas',
        'Calaca',
        'Calatagan',
        'Cuenca',
        'Lemery',
        'Lian',
        'Mabini',
        'Malvar',
        'Matabungkay',
        'Nasugbu',
        'San Jose',
        'San Juan',
        'San Luis',
        'San Nicolás',
        'San Pascual',
        'Santa Teresa',
        'Taal',
        'Talisay',
        'Taysan',
        'Vaughn'
    ];

    // Get the select element for cities
    const citySelect = document.getElementById('city-municipality');

    // Populate the select with options
    cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city; // Set the value of the option
        option.textContent = city; // Set the text of the option
        citySelect.appendChild(option); // Append the option to the select
    });

    // Initialize Select2 on the city select element
    $(document).ready(function () {
        $('#city-municipality').select2({
            placeholder: 'Select a city or municipality', // Placeholder for Select2
            allowClear: true, // Allows clearing the selected option
            width: '100%' // Make sure the Select2 dropdown takes up the full width
        });

        // Initialize Select2 on the category select element
        $('#category').select2({
            placeholder: 'Select categories', // Placeholder text
            allowClear: true // Allows clearing of selections
        }).css("width", "100%"); // Ensure the select2 is 100% wide
    });
</script>

<!--Save Entry-->
<script>
    $(document).ready(function () {
        // Click event for adding new route inputs
        $('#add-route-btn').click(function () {
            // Create new input fields for Route and Details
            var newRoute = `
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="input-group input-group-outline mb-4">
                        <input type="text" class="form-control route" placeholder="Route" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-outline mb-4">
                        <input type="text" class="form-control details" placeholder="Location" required>
                    </div>
                </div>
                <a class="remove-route-btn">
                    <i class="fa-solid fa-trash"></i> Remove
                </a>
            </div>
        `;
            $('#instructions-container').append(newRoute); // Append new route fields
        });

        // Delegate event handler for dynamically added remove buttons
        $(document).on('click', '.remove-route-btn', function () {
            $(this).closest('.row').remove(); // Remove the corresponding route row
        });

        // Save button click event
        $('#save-button').click(function () {
            // Gather data from input fields
            var locationName = $('#location-name').val().trim();
            var locationCoords = $('#location').val().trim();
            var description = $('#description').val().trim();
            var categories = $('#category').val(); // Get selected categories
            var city = $('#city-municipality').val();

            // Validate required fields and log empty ones
            let hasError = false;
            if (!locationName) {
                hasError = true;
            }
            if (!locationCoords) {
                hasError = true;
            }
            if (!description) {
                hasError = true;
            }
            if (!categories.length) {
                hasError = true;
            }
            if (!city) {
                hasError = true;
            }

            if (hasError) {
                toastr.error('Please fill in all required fields.'); // Show error if validation fails
                return; // Stop the function if validation fails
            }

            // Gather routes and details
            var instructions = [];
            $('#instructions-container .row').each(function () {
                var route = $(this).find('.route').val().trim();
                var details = $(this).find('.details').val().trim();
                if (route && details) {
                    instructions.push(route + '|' + details);
                } else {
                    if (!route) {
                        console.log('Route is empty');
                    }
                    if (!details) {
                        console.log('Details are empty');
                    }
                    toastr.error('Please fill in all route and details fields.'); // Show error for incomplete fields
                    return; // Stop the function if validation fails
                }
            });

            // Prepare image and tour link uploads
            var formData = new FormData();
            formData.append('location_name', locationName);
            formData.append('location', locationCoords);
            formData.append('description', description);
            formData.append('category', categories.join(', ')); // Join categories for submission
            formData.append('city-municipality', city);
            if ($('#imageInput')[0].files.length > 0) {
                formData.append('image', $('#imageInput')[0].files[0]); // Append image if available
            }
            if ($('#file-upload')[0].files.length > 0) {
                formData.append('tour_link', $('#file-upload')[0].files[0]); // Append tour link if available
            }

            // Append instructions
            instructions.forEach(function (instruction) {
                formData.append('instructions[]', instruction);
            });

            // AJAX call
            $.ajax({
                url: 'api/location/save-location.php', // Your PHP file to process the data
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        toastr.success(result.message); // Display success message
                        // Clear all input fields
                        $('#location-name').val('');
                        $('#location').val('');
                        $('#description').val('');
                        $('#category').val('').trigger('change'); // Reset select2
                        $('#city-municipality').val('').trigger('change'); // Reset city select2
                        $('#instructions-container').empty(); // Clear the instructions container
                        $('#imageInput').val(''); // Clear image input
                        $('#file-upload').val(''); // Clear tour link input
                    } else {
                        toastr.error(result.message); // Display error message
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    toastr.error('An error occurred while saving the location. Please try again.'); // Show general error message
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let updateMapNew; // renamed variable
        let updateMarkerNew; // renamed variable

        function initializeNewMap() { // renamed function
            if (updateMapNew) {
                updateMapNew.remove(); // Clean up existing map
            }

            // Initialize map and set the default location to Batangas, Philippines
            updateMapNew = L.map('map').setView([13.756, 121.067], 12);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(updateMapNew);

            // Add Nominatim Geocoder (search control)
            const updateGeocoderNew = L.Control.geocoder({ // renamed variable
                geocoder: L.Control.Geocoder.nominatim(), // Use Nominatim geocoder
                defaultMarkGeocode: false
            }).on('markgeocode', function (e) {
                const latlng = e.geocode.center;

                // If there's already a marker, remove it
                if (updateMarkerNew) {
                    updateMapNew.removeLayer(updateMarkerNew);
                }

                // Set the new marker to the geocoded location
                updateMarkerNew = L.marker(latlng).addTo(updateMapNew);
                updateMapNew.setView(latlng, 15); // Zoom in to the selected location

                // Store the selected latitude and longitude
                window.selectedLocationNew = { lat: latlng.lat, lng: latlng.lng }; // renamed variable
            }).addTo(updateMapNew);

            // Add marker on map click
            updateMapNew.on('click', function (e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (updateMarkerNew) {
                    updateMapNew.removeLayer(updateMarkerNew); // Remove the previous marker if one exists
                }

                // Place a new marker
                updateMarkerNew = L.marker([lat, lng]).addTo(updateMapNew);

                // Store the selected latitude and longitude
                window.selectedLocationNew = { lat, lng }; // renamed variable
            });
        }

        // Initialize the map when the modal is shown
        document.getElementById('mapNewModal').addEventListener('shown.bs.modal', function () {
            initializeNewMap(); // renamed function call
        });

        // Handle the "Select Location" button click
        document.getElementById('selectLocation').addEventListener('click', function () {
            if (window.selectedLocationNew) {
                const { lat, lng } = window.selectedLocationNew;
                document.getElementById('location').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`; // Display the latitude and longitude in the input field
                const modal = bootstrap.Modal.getInstance(document.getElementById('mapNewModal')); // Close the modal
                modal.hide();
            }
        });
    });
</script>