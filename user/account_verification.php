<?php
include 'header.php';
?>
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f5f5f5;
    }

    .content-wrapper {
        padding: 20px;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .file-input-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        /* Space between input containers */
    }

    .file-input {
        display: none;
        /* Hide the default file input */
    }

    .file-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #582fff;
        background: #ffffff;
        padding: 20px;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: box-shadow 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        flex: 1;
        /* Allow the button to take up available space */
        margin-right: 10px;
        /* Space between buttons */
    }

    .file-label:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .preview {
        width: 100%;
        height: auto;
        border: 2px solid #582fff;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
        /* Change cursor to pointer for previews */
        margin-left: 10px;
        /* Space between button and preview */
    }

    .preview img {
        width: 100%;
        /* Ensure the image fits within the preview */
        height: auto;
        /* Maintain aspect ratio */
        object-fit: contain;
        /* Scale the image to fit within the preview */
    }

    .error-message {
        color: red;
        font-size: 0.9em;
    }

    .btn-verify {
        background-color: #582fff;
        border: none;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 20px;
    }

    .modal-content {
        border-radius: 16px;
        /* Rounded corners for a modern look */
        background-color: #ffffff;
        /* Clean white background */
    }

    .modal-body {
        padding: 24px;
        /* Adequate padding for breathing space */
    }

    .btn {
        transition: background-color 0.3s, color 0.3s;
        /* Smooth transition for hover effects */
    }

    .btn-light {
        background-color: #f8f9fa;
        /* Light background for the button */
    }

    .btn-light:hover {
        background-color: #e2e6ea;
        /* Slightly darker on hover */
        color: #28a745;
        /* Change text color on hover */
    }

    /* Icon styling */
    .text-success {
        color: #28a745;
        /* Success color */
    }

    /* Centering the modal icon and text */
    .modal-body i {
        display: block;
        margin: 0 auto;
    }
</style>


<div class="content-wrapper" style="background-color: white; margin-bottom: 100px;">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="mt-4">
                        <div class="card-body">
                            <h6 class="font-weight-bold">Upload a photo of your ID card. The photo should be:</h6>
                            <ul>
                                <li>Bright and clear</li>
                                <li>All corners of the document should be visible</li>
                            </ul>

                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="file-input-container">
                                            <input type="file" class="file-input" id="fileFront" name="fileFront"
                                                onchange="previewImage(this, 'frontPreview')">
                                            <label for="fileFront" class="file-label" id="frontPreview">
                                                <i class="fas fa-plus-circle fa-3x"></i>
                                                <h6 class="mt-1 font-weight-bold">Upload the <u>front</u> of your
                                                    document</h6>
                                                <p class="p-2">No front image uploaded</p>
                                                <div class="preview"></div>
                                            </label>
                                        </div>
                                        <div class="error-message" id="frontError"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="file-input-container">
                                            <input type="file" class="file-input" id="fileBack" name="fileBack"
                                                onchange="previewImage(this, 'backPreview')">
                                            <label for="fileBack" class="file-label" id="backPreview">
                                                <i class="fas fa-plus-circle fa-3x"></i>
                                                <h6 class="mt-1 font-weight-bold">Upload the <u>back</u> of your
                                                    document</h6>
                                                <p class="p-2">No back image uploaded</p>
                                                <div class="preview"></div>
                                            </label>
                                        </div>
                                        <div class="error-message" id="backError"></div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <button type="button" class="btn btn-block btn-primary btn-verify"
                                            onclick="submitForm()">Submit</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-sm border-0">
            <div class="modal-body text-center p-4">
                <i class="fas fa-id-card-alt fa-4x text-success mb-3"></i>
                <h6 class="font-weight-bold text-success mb-3">
                    <i>Your request for verification has been sent</i>
                </h6>
                <button class="btn btn-light border border-success text-success rounded-pill px-4" data-dismiss="modal">
                    Okay
                </button>
            </div>
        </div>
    </div>
</div>


<?php
include 'footer.php';
?>

<script>
    function submitForm() {
        var fileFront = $('#fileFront')[0].files[0];
        var fileBack = $('#fileBack')[0].files[0];
        var frontError = $('#frontError');
        var backError = $('#backError');

        // Validate that both files are uploaded
        if (!fileFront || !fileBack) {
            if (!fileFront) {
                frontError.text('Please upload the front of your document');
            } else {
                frontError.text('');
            }
            if (!fileBack) {
                backError.text('Please upload the back of your document');
            } else {
                backError.text('');
            }
            return;
        }

        // Clear error messages
        frontError.text('');
        backError.text('');

        // Create FormData object for file upload
        var formData = new FormData();
        formData.append('fileFront', fileFront);
        formData.append('fileBack', fileBack);

        console.log('FormData:', formData.get('fileFront'), formData.get('fileBack')); // Debugging step

        // Send data using jQuery Ajax
        $.ajax({
            url: 'api/setting/update-id.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log("Response from server:", response); // Log the raw response for debugging

                // Check if the response has a success property
                if (response.success) {
                    $('#successModal').modal('show'); // Show success modal

                    // Clear the form fields
                    $('#fileFront').val(''); // Clear the front file input
                    $('#fileBack').val(''); // Clear the back file input
                    $('#frontError').text(''); // Clear any front error messages
                    $('#backError').text(''); // Clear any back error messages
                    $('#frontPreview').html('<i class="fas fa-plus-circle fa-3x"></i><h6 class="mt-1 font-weight-bold">Upload the <u>front</u> of your document</h6><p class="p-2">No front image uploaded</p>'); // Reset front preview
                    $('#backPreview').html('<i class="fas fa-plus-circle fa-3x"></i><h6 class="mt-1 font-weight-bold">Upload the <u>back</u> of your document</h6><p class="p-2">No back image uploaded</p>'); // Reset back preview
                } else {
                    alert(response.message); // Alert with the message if not successful
                }
            },
            error: function (xhr, status, error) {
                console.log("Error: " + error); // Log the error for debugging
                alert("An error occurred during the upload process. Please try again.");
            },
        });

    }

    function previewImage(input, previewId) {
        const file = input.files[0];
        const previewLabel = document.getElementById(previewId);
        const previewContainer = previewLabel.querySelector('.preview'); // Get the preview container
        const errorId = previewId === 'frontPreview' ? 'frontError' : 'backError';
        const defaultText = previewLabel.querySelector('p');

        // Limit file size to 2MB (2 * 1024 * 1024 bytes)
        const maxSize = 2 * 1024 * 1024;

        if (file) {
            if (file.size > maxSize) {
                document.getElementById(errorId).textContent = "File size must be less than 2MB.";
                previewContainer.innerHTML = ''; // Clear previous content
                return; // Stop processing if file size exceeds limit
            } else {
                document.getElementById(errorId).textContent = ""; // Clear error message if within limit
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewContainer.innerHTML = ''; // Clear previous content
                const img = document.createElement('img');
                img.src = e.target.result;
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = ''; // Clear the preview if no file is selected
            previewLabel.innerHTML = '<i class="fas fa-plus-circle fa-3x"></i><h6 class="mt-1 font-weight-bold">Upload the <u>' + (previewId === 'frontPreview' ? 'front' : 'back') + '</u> of your document</h6><p class="p-2">No image uploaded</p>'; // Default message
            document.getElementById(errorId).textContent = ""; // Clear error message when no file is selected
        }
    }
</script>