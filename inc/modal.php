<div class="modal fade" id="deleteLocationModal" tabindex="-1" role="dialog" aria-labelledby="deleteDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../inc/function.php" method="POST" id="deleteDepartmentForm">
            <div class="modal-body">
                <input type="hidden" id="deleteLocationId" name="id">
                <h5 class="text-secondary text-center">Are you sure you want to delete this location?</h5>
            </div>
            <div class="modal-footer m-auto text-center">
                <div class="row m-auto text-center">
                    <div class="col">
                    <button class="btn btn-secondary" data-dismiss="modal"> NO</button>
                <button type="submit" name="delete_location" class="btn btn-success" id="saveChanges"> YES</button>

                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="editLocationModal" tabindex="-1" role="dialog" aria-labelledby="editLocationModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../inc/function.php" method="POST" id="deleteDepartmentForm">
            <div class="modal-body">
                <input type="hidden" id="editLocationId" name="id">

                <div class="row">
                    <div class="col">
                        <label for="">Location Name:</label>
                        <input type="text" id="editLocationName" name="location_name" class="form-control" placeholder="Location Name">
                    </div>
                    <div class="col">
                        <label for="">Category:</label><br>
                        <select name="category[]" id="editLocationCategory" class="form-control select2" style="width:400px;" multiple="multiple">
                        <option value="Nature">Nature</option>
                        <option value="Mountain">Mountain</option>
                        <option value="Historical">Historical</option>
                        <option value="Beach">Beach</option>
                        <option value="Church">Church</option>
                        <option value="Cultural">Cultural</option>
                        <option value="Relaxation">Relaxation</option>
                    </select>
                    </div>
                   
                       <div class="col">
                    <label for="">Location</label>
                   <div class="input-group">
                        <input type="text" id="editLocation1" name="location1" class="form-control bg-white" placeholder="Latitude, Longitude" readonly required>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mapModal2" >
                           <i class="fas fa-map-pin	"></i>
                        </button>
                    </div>

            </div>
                   
                </div>

                    
                    
                    
                    <div class="row mt-3">

                    <div class="col">
                        <label for="">Description:</label>
                        <textarea type="text" id="editLocationDescription" name="description" class="form-control" rows="3" cols="3"></textarea>
                    </div>
                   
                     <div class="col">
                        <label for="">How to get there?</label>
                          <div class="textarea-container">
                        <textarea class="form-control" id="instruction2" placeholder="How to get there?" cols="3" rows="3" required></textarea>
                        <button class="add-row-button" id="addRowButton2"><i class="fas fa-plus"></i></button>
                        <div class="inputs-overlay" id="dynamicContainer2">
                        </div>
                    </div>
                    </div>
                </div>
                     <div class="row mt-3">
            <div class="col-3">
             <label for="">Upload Location Images</label><br>
               <button type="button" class="upload-btn custom-file-upload bg-transparent btn-sm font-weight-bold text-dark" id="uploadBtn2">
                     Select Images &nbsp <i class="fas fa-upload"></i>
                </button>
                <input type="file" id="imageInput2" name="images[]" accept="image/*" multiple required>
                 <input style="font-size:10px; margin-top:-1px;" class="border-0 disabled fst-italic text-secondary" id="editLocationImages2"/>
            </div>
            
             <div class="col-3">
                    <label for="">Upload 360° Virtual Tour</label>
                    <br>
                 <label for="file-upload" class="custom-file-upload bg-light shadow-sm">
                        Select Image <i class="fas fa-upload ml-1"></i>
                    </label>
                <input id="file-upload" name="tour_link2" type="file" required/>
                 <input style="font-size:10px;" class="border-0 disabled fst-italic text-secondary" id="editLocationTourLink"/>

                </div>
            
        </div>
   
                    

            </div>
            <div class="modal-footer m-auto text-center">
                <div class="row m-auto text-center">
                    <div class="col">
                <button type="submit" name="update_location" class="btn btn-success"><i class="fas fa-check"></i> SAVE</button>

                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="mapModal2" tabindex="-1" aria-labelledby="mapModal2Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModal2Label">Edit Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info alert-sm p-2" style="font-size:14px;"><i class="fas fa-info-circle"></i> Drag marker to change location</div>
                <div id="map3" style="height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Change Location</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let map;
    let marker;

    // Function to initialize or reset the map
    function initializeMap(lat, lng) {
        if (!map) {
            map = L.map('map3').setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add a draggable marker
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            // Event listener for marker drag event
            marker.on('dragend', function(event) {
                const newLatLng = event.target.getLatLng();
                document.getElementById('editLocation1').value = `${newLatLng.lat.toFixed(6)}, ${newLatLng.lng.toFixed(6)}`;
            });
        } else {
            map.setView([lat, lng], 13);

            // Remove the old marker if it exists
            if (marker) {
                map.removeLayer(marker);
            }

            // Add a new draggable marker
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            // Event listener for marker drag event
            marker.on('dragend', function(event) {
                const newLatLng = event.target.getLatLng();
                document.getElementById('editLocation1').value = `${newLatLng.lat.toFixed(6)}, ${newLatLng.lng.toFixed(6)}`;
            });
        }
    }

    // Event listener for when the map modal is shown
    document.getElementById('mapModal2').addEventListener('shown.bs.modal', function() {
        const locationInput = document.getElementById('editLocation1').value;
        const [lat, lng] = locationInput.split(',').map(coord => parseFloat(coord.trim()));
        
        if (!isNaN(lat) && !isNaN(lng)) {
            initializeMap(lat, lng);
        }
    });

    // Event listener for when the map modal is hidden
    document.getElementById('mapModal2').addEventListener('hidden.bs.modal', function() {
        // Reopen the editLocationModal
        const editLocationModal = new bootstrap.Modal(document.getElementById('editLocationModal'));
        editLocationModal.show();
    });

    // Initialize Bootstrap modals



});
</script>



<div class="modal fade" id="addAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="deleteDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add news & updates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../inc/function.php" method="POST" enctype="multipart/form-data" id="deleteDepartmentForm">
            <div class="modal-body">
                <style>
                    .custom-file-upload {
                        display: inline-block;
                        padding: 10px 15px;
                        cursor: pointer;
                        color: #fff;
                        font-size:10px;
                        background-color: #007bff;
                        border: 1px solid #007bff;
                        border-radius: 5px;
                    }
                    
                    .custom-file-upload:hover {
                        background-color: #0056b3;
                    }
                    
                    .custom-file-upload i {
                        margin-right: 5px;
                    }
                </style>
                <div class="row">
                    <div class="col-lg-9">
                        <label for="">Announcement Title:</label>
                        <input type="text" name="title" class="form-control" placeholder="Announcement Title">
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-8">
                                  <label for="fileInput" class="custom-file-upload" style=" margin-top:35px;">
                                    <input type="file" id="fileInput" name="image" class="form-control" style="display: none;" required>
                                    <i class="fa fa-image"></i><i class="fa fa-plus" style="margin-left:-10px; margin-top:-10px; font-size:10px;"></i><span style="margin-top:-7px; font-size:14px;">Image</span>
                                </label>         
                            </div>
                            <div class="col-4">
                            <div id="image-display" class="mt-4">
                            </div>
                        </div>
                           
                        
                    </div>
                </div>
                </div>
                    <div class="row mt-3">
                    <div class="col">
                        <label for="">Description:</label>
                        <textarea type="text" name="description" class="form-control" rows="3" cols="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer m-auto text-center">
                <div class="row m-auto text-center">
                    <div class="col">
                    <button class="btn btn-secondary" data-dismiss="modal"> CANCEL</button>
                <button type="submit" name="add_announcement" class="btn btn-success"><i class="fas fa-check"></i> ADD</button>

                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('fileInput').addEventListener('change', function(event) {
    const file = event.target.files[0]; // Get the selected file

    if (file) {
        const reader = new FileReader(); 
        reader.onload = function(e) {
            const imageDisplay = document.getElementById('image-display');
            imageDisplay.innerHTML = ''; // Clear previous image (if any)
            const img = document.createElement('img');
            img.src = e.target.result; // Set the source of the image to the file content
            img.style.maxWidth = '100%'; // Ensure the image scales with the container width
            img.style.height = 'auto'; // Maintain aspect ratio
            imageDisplay.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>

<div class="modal fade" id="viewIdModal" tabindex="-1" role="dialog" aria-labelledby="viewIdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center m-auto">ID Verification</h5>

            </div>
            <div class="modal-body text-center">
                <div class="row">
                 <div class="col">
                    <img id="idFrontImage" src="" alt="Front ID" class="img-fluid" style="max-width: 100%;">
                    <h5 class="font-weight-bold">Front</h5>
                </div>
                 <div class="col">
                 <img id="idBackImage" src="" alt="Back ID" class="img-fluid" style="max-width: 100%;">
                 <h5 class="font-weight-bold">Back</h5>
                </div>
                  </div>
                <div style="width:200px;" class="m-auto text-center mt-3">
                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">OKAY</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="approveUserModal" tabindex="-1" role="dialog" aria-labelledby="viewIdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center m-auto">Approve User</h5>

            </div>
            <div class="modal-body text-center">
                <form action="../inc/function.php" method="POST">
                     <input type="hidden" id="approveUserId" name="id">
                      <h6>Are you sure you want to approved this user?</h6>  
            
                <div style="width:160px;" class="m-auto text-center mt-3 pt-3">
                    <button type="submit" name="approve_user" class="btn btn-secondary btn-block"><i class="fas fa-check"></i> CONFIRM</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="declineUserModal" tabindex="-1" role="dialog" aria-labelledby="viewIdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center m-auto">Decline User</h5>

            </div>
            <div class="modal-body text-center">
                <form action="../inc/function.php" method="POST">
                    <input type="hidden" id="declineUserId" name="id">
                      <h6>Are you sure you want to decline this user?</h6>  
                <div style="width:160px;" class="m-auto text-center mt-3 pt-3">
                    <button type="submit" name="decline_user" class="btn btn-secondary btn-block"><i class="fas fa-check"></i> CONFIRM</button>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="editAnnouncementModal"  tabindex="-1" role="dialog" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Announcement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../inc/function.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="editAnnouncementId" name="id">
                    <div class="form-group">
                        <label for="editAnnouncementTitle">Title:</label>
                        <input type="text" class="form-control fo" id="editAnnouncementTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="editAnnouncementDescription">Description:</label>
                        <textarea class="form-control" id="editAnnouncementDescription" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col">
                             <div class="form-group">
                                <label for="editAnnouncementImage">Image:</label>
                                <input type="file" class="form-control-file" id="editAnnouncementImage" name="image">
                            </div>
                        </div>
                               <div class="col">
                             <div class="form-group">
                                
                                <img id="editAnnouncementImagePreview" class="img-fluid mt-2" style="max-width: 50%;" />
                            </div>
                        </div>
                    </div>
                    <div class=" m-auto text-center">
                        <button type="submit" name="update_announcement" class="btn btn-primary"><i class="fas fa-check"></i> SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="deleteAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="deleteAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form action="../inc/function.php" method="POST">
                    <input type="hidden" id="deleteAnnouncementId" name="id">
                    <h6>Are you sure you want to delete this announcement?</h6>
                    <div style="width:160px;" class="m-auto text-center mt-3 pt-3">
                        <button type="submit" name="delete_announcement" class="btn btn-secondary btn-block"><i class="fas fa-check"></i> CONFIRM</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



