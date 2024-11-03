<style>
    .remove-route-btn-update {
        float: right;
        font-size: 12px;
        margin-top: -20px;
        color: red;
        cursor: pointer;
    }
</style>

<!-- Edit Location Modal -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="locationDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <!-- First Column -->
                        <input type="hidden" id="location-id">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <input type="text" id="location-name-update" class="form-control"
                                    placeholder="Destination">
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <input type="text" id="location-update" class="form-control"
                                    placeholder="Latitude, Longitude">
                                <button type="button" class="btn btn-primary openMap">
                                    <i class="fas fa-map-pin"></i>
                                </button>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-dynamic mb-4">
                                    <textarea id="description-update" class="form-control" rows="5"
                                        placeholder="Tell something about the destination or tourist spot."
                                        spellcheck="false"></textarea>
                                </div>
                            </div>
                            <label for="category" class="ms-0">Category</label>
                            <div class="input-group input-group-static mb-4">
                                <select class="form-control" id="travel-category" multiple="multiple"></select>
                            </div>
                            <div class="input-group input-group-static mb-4">
                                <label for="city" class="ms-0">City / Municipality</label>
                                <select class="form-control" id="city">
                                    <!-- City options will be dynamically populated here -->
                                </select>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="how-to-get-there">
                                        <h5>How to get there?</h5>
                                        <div id="instructions-container-update">
                                            <!-- Dynamic instructions will be appended here -->
                                        </div>
                                        <div class="float-right">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                id="add-route-btn-update" style="width: 150px; margin-top: 10px;">
                                                <i class="fa-solid fa-plus"></i> Add More
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Second Column -->
                        <div class="col-md-6">

                            <div class="col-lg-12 mb-4 mt-4">
                                <label for="imageInput-update">Upload Location Images</label><br>
                                <input type="file" id="imageInput-update" name="images[]" accept="image/*" multiple>
                                <!-- Hidden input to store current image paths -->
                                <input type="hidden" id="current-images" name="current-images" value="">

                                <!-- Image preview for location images -->
                                <div id="imagePreview" style="display:none; margin-top: 10px;">
                                    <label>New Images</label>
                                    <div class="image-thumbnails" style="display: flex; flex-wrap: wrap; gap: 10px;">

                                    </div>
                                </div>
                                <label>Current Images</label>
                                <div id="imagePreviewsContainer"
                                    style="display: flex; flex-wrap: wrap; margin-top: 10px;">

                                </div>
                            </div>




                            <div class="col-lg-12 mb-4">
                                <label for="file-upload-update">Upload 360° Virtual Tour</label><br>
                                <input id="file-upload-update" name="tour_link" type="file" accept="image/*">
                                <!-- Image preview for virtual tour -->
                                <img id="tourPreview" src="" alt="Virtual Tour Preview"
                                    style="display:none; width: 100%; max-height: 200px; margin-top: 10px;">
                            </div>

                        </div>
                    </div>
                    <button type="button" class="btn btn-info btn-lg" id="save-changes-button">Save</button>
                    <button type="button" class="btn btn-light btn-lg" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="mapModalNew" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <!-- OpenStreetMap will be displayed here -->
                <div id="map-new" style="width: 100%; height: 400px;"></div>
            </div>
            <div class="modal-footer"
                style="border-top: none; padding: 1rem 0; justify-content: center; border-radius: 0 0 10px 10px;">
                <button type="button" id="selectLocationUpdate" class="btn btn-primary"
                    style="border-radius: 20px; margin: 0 10px;">Select Location</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let updateMap; // renamed variable
        let updateMarker; // renamed variable

        function initializeUpdateMap() { // renamed function
            if (updateMap) {
                updateMap.remove(); // Clean up existing map
            }

            // Initialize map and set the default location to Batangas, Philippines
            updateMap = L.map('map-new').setView([13.756, 121.067], 12);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(updateMap);

            // Add Nominatim Geocoder (search control)
            const updateGeocoder = L.Control.geocoder({ // renamed variable
                geocoder: L.Control.Geocoder.nominatim(), // Use Nominatim geocoder
                defaultMarkGeocode: false
            }).on('markgeocode', function (e) {
                const latlng = e.geocode.center;

                // If there's already a marker, remove it
                if (updateMarker) {
                    updateMap.removeLayer(updateMarker);
                }

                // Set the new marker to the geocoded location
                updateMarker = L.marker(latlng).addTo(updateMap);
                updateMap.setView(latlng, 15); // Zoom in to the selected location

                // Store the selected latitude and longitude
                window.selectedLocation = { lat: latlng.lat, lng: latlng.lng };
            }).addTo(updateMap);

            // Add marker on map click
            updateMap.on('click', function (e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (updateMarker) {
                    updateMap.removeLayer(updateMarker); // Remove the previous marker if one exists
                }

                // Place a new marker
                updateMarker = L.marker([lat, lng]).addTo(updateMap);

                // Store the selected latitude and longitude
                window.selectedLocation = { lat, lng };
            });
        }

        // Initialize the map when the modal is shown
        document.getElementById('mapModalNew').addEventListener('shown.bs.modal', function () {
            initializeUpdateMap(); // renamed function call
        });

        // Handle the "Select Location" button click
        document.getElementById('selectLocationUpdate').addEventListener('click', function () {
            if (window.selectedLocation) {
                const { lat, lng } = window.selectedLocation;
                document.getElementById('location-update').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`; // Display the latitude and longitude in the input field
                const modal = bootstrap.Modal.getInstance(document.getElementById('mapModalNew')); // Close the modal
                modal.hide();
            }
        });
    });
</script>

<!--Edit Location-->
<script>
    $(document).ready(function () {
        $('.openMap').on('click', function () {
            $('#mapModalNew').modal('show'); // Show the map modal
            initializeMap(); // Initialize or refresh the map
        });

        // Define travel categories
        const travelCategories = [
            "Nature", "Mountain", "Historical", "Beach",
            "Church", "Cultural", "Relaxation"
        ];

        const selectedCity = [
            'Agoncillo',
            'Alitagtag',
            'Balayan',
            'Balete',
            'Bauan',
            'Calaca',
            'Calatagan',
            'Cuenca',
            'Ibaan',
            'Laurel',
            'Lemery',
            'Lian',
            'Lipa',
            'Lobo',
            'Mabini',
            'Malvar',
            'Mataas na Kahoy',
            'Nasugbu',
            'Padre Garcia',
            'Rosario',
            'San Jose',
            'San Juan',
            'San Luis',
            'San Nicolas',
            'San Pascual',
            'Santa Teresita',
            'Santo Tomas',
            'Taal',
            'Talisay',
            'Tanauan',
            'Taysan',
            'Tingloy',
            'Tuy'
        ];

        const citySelect = document.getElementById('city');

        // Populate the select with options
        cities.forEach(selectedCity => {
            const option = document.createElement('option');
            option.value = selectedCity; // Set the value of the option
            option.textContent = selectedCity; // Set the text of the option
            citySelect.appendChild(option); // Append the option to the select
        });

        // Initialize Select2 on the city select element
        $(document).ready(function () {
            $('#city').select2({
                placeholder: 'Select a city or municipality', // Placeholder for Select2
                allowClear: true, // Allows clearing the selected option
                width: '100%' // Make sure the Select2 dropdown takes up the full width
            });
        });

        // Populate categories in the #travel-category select
        const $travelCategorySelect = $('#travel-category');
        travelCategories.forEach(category => {
            $travelCategorySelect.append(`<option value="${category}">${category}</option>`);
        });

        // Initialize Select2 on the select elements
        $travelCategorySelect.select2({
            placeholder: 'Select categories',
            width: '100%'
        });

        $('#city').select2({
            placeholder: 'Select a city or municipality',
            allowClear: true,
            width: '100%'
        });

        // Initialize Fancybox (this step is optional since it will auto-initialize based on data attributes)
        Fancybox.bind('[data-fancybox="gallery"]', {
            // Optional configuration options
            loop: true,
            arrows: true,
            toolbar: true,
        });


        // File input change event to preview images
        const imageInput = document.getElementById('imageInput-update');
        const imagePreviewContainer = document.getElementById('imagePreview');
        const thumbnailsContainer = document.querySelector('.image-thumbnails');

        imageInput.addEventListener('change', function () {
            const files = this.files;
            if (files.length > 0) {
                imagePreviewContainer.style.display = 'block';
                thumbnailsContainer.innerHTML = ''; // Clear previous thumbnails

                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Create a thumbnail image element
                        const thumbnail = document.createElement('a');
                        thumbnail.href = e.target.result; // Full image source
                        thumbnail.setAttribute('data-fancybox', 'gallery'); // Fancybox data attribute
                        thumbnail.innerHTML = `<img src="${e.target.result}" style="width: 100px; height: auto; cursor: pointer;">`; // Thumbnail style
                        thumbnailsContainer.appendChild(thumbnail);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                imagePreviewContainer.style.display = 'none'; // Hide if no files selected
            }
        });



        // Function to change slides
        function changeSlide(n) {
            const imgs = slidesContainer.getElementsByTagName('img');
            if (imgs.length > 0) {
                imgs[currentSlide].style.display = 'none'; // Hide current slide
                currentSlide = (currentSlide + n + imgs.length) % imgs.length; // Update slide index
                imgs[currentSlide].style.display = 'block'; // Show new slide
            }
        }


        $('#file-upload-update').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#tourPreview').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#tourPreview').hide();
            }
        });


        $('#add-route-btn-update').click(function () {
            // Create new input fields for Route and Details
            var newRoute = `
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="input-group input-group-outline mb-4">
                        <input type="text" class="form-control route-update" placeholder="Route" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-outline mb-4">
                        <input type="text" class="form-control details-update   " placeholder="Location" required>
                    </div>
                </div>
                <a class="remove-route-btn-update">
                    <i class="fa-solid fa-trash"></i> Remove
                </a>
            </div>
        `;
            $('#instructions-container-update').append(newRoute);
        });

        // Delegate event handler for dynamically added remove buttons
        $(document).on('click', '.remove-route-btn-update', function () {
            $(this).closest('.row').remove(); // Remove the corresponding route row
        });

        // Fetch and populate data when an edit-location button is clicked
        $(document).on('click', '.edit-location-btn', function () {
            const locationId = $(this).data('id');

            $.ajax({
                url: 'api/location/get-location.php',
                method: 'GET',
                data: { id: locationId },
                dataType: 'json',
                success: function (data) {
                    console.log("Data received:", data);

                    // Populate location information fields
                    $('#location-id').val(locationId);
                    $('#location-name-update').val(data.location_name);
                    $('#location-update').val(data.location);
                    $('#description-update').val(data.description);

                    // Populate categories as an array
                    $('#travel-category').val(data.category).trigger('change');

                    // Populate city
                    $('#city').val(data.city).trigger('change');

                    // Clear previous images and prepare for new ones
                    const slidesContainer = $('#imagePreviewsContainer');
                    slidesContainer.empty(); // Clear previous images

                    // Initialize current images variable
                    let currentImages = [];

                    // Display image previews if available
                    if (data.image && data.image.length > 0) {
                        // If images are stored as a comma-separated string, split it into an array
                        const imagesArray = data.image.split(','); // Adjust this based on how images are returned

                        imagesArray.forEach(image => {
                            const imgLink = $('<a>', {
                                href: `images/${image.trim()}`, // Full image source
                                'data-fancybox': 'gallery', // Fancybox data attribute
                                html: `<img src="images/${image.trim()}" style="width: 100px; height: auto; cursor: pointer; margin-right: 10px;">` // Thumbnail style
                            });
                            slidesContainer.append(imgLink); // Append to the container

                            // Add image to current images array
                            currentImages.push(image.trim());
                        });

                        // Set the current images in the hidden input
                        $('#current-images').val(currentImages.join(',')); // Save as a comma-separated string
                    }

                    if (data.virtual_tour) {
                        $('#tourPreview').attr('src', `panorama/${data.virtual_tour}`).show();
                    } else {
                        $('#tourPreview').hide();
                    }

                    // Populate instructions
                    $('#instructions-container-update').empty();
                    data.instructions.forEach(instruction => {
                        $('#instructions-container-update').append(`
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-4">
                                <input type="text" class="form-control route-update" value="${instruction.location}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-4">
                                <input type="text" class="form-control details-update" value="${instruction.instruction}">
                            </div>
                        </div>
                        <a class="remove-route-btn">
                            <i class="fa-solid fa-trash"></i> Remove
                        </a>
                    </div>
                `);
                    });

                    // Optionally bind Fancybox here to ensure it recognizes newly added elements
                    Fancybox.bind('[data-fancybox="gallery"]', {
                        loop: true,
                        arrows: true,
                        toolbar: true,
                    });
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Response:", xhr.responseText);
                }
            });
        });



    });
</script>


<script>
    // Event listener for saving changes
    document.getElementById('save-changes-button').addEventListener('click', function () {
        // Collect input values
        const locationId = document.getElementById('location-id').value; // Assuming you have a hidden input for location ID
        const locationName = document.getElementById('location-name-update').value;
        const location = document.getElementById('location-update').value;
        const description = document.getElementById('description-update').value;
        const category = $('#travel-category').val(); // Get selected categories
        const city = $('#city').val(); // Get selected city

        // Gather routes and details
        var instructions = [];
        // Change here: iterate over each row within the instructions container
        $('#instructions-container-update .row').each(function () {
            var route = $(this).find('.route-update').val().trim();
            var details = $(this).find('.details-update').val().trim();
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

        // Create a FormData object to handle file uploads
        const formData = new FormData();
        formData.append('id', locationId);
        formData.append('location_name', locationName);
        formData.append('location', location);
        formData.append('description', description);
        formData.append('category', category.join(', ')); // Save category as a comma-separated string
        formData.append('city', city);

        // Append files only if available
        const imageInput = document.getElementById('imageInput-update');
        const tourInput = document.getElementById('file-upload-update');

        // Check if the image has changed
        const files = imageInput.files;
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]); // Append each file
        }


        // Check if the tour link has changed
        if (tourInput.files.length > 0) {
            formData.append('tour_link', tourInput.files[0]);
        }

        // Append instructions
        instructions.forEach(function (instruction) {
            formData.append('instructions[]', instruction);
        });

        // Show loading indicator (optional)
        $('#save-changes-button').prop('disabled', true).text('Saving...');

        // AJAX request to update the location
        $.ajax({
            url: 'api/location/update-location.php', // Update with the correct path
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    // Handle success (e.g., close the modal and refresh the data)
                    $('#editLocationModal').modal('hide');
                    toastr.success('Location updated successfully!', 'Success')
                } else {
                    alert('Error: ' + result.error);
                }
            },
            error: function () {
                alert('An error occurred while updating the location.');
            },
            complete: function () {
                // Hide loading indicator
                $('#save-changes-button').prop('disabled', false).text('Save');
            }
        });
    });
</script>