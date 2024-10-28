
<?php
include '../inc/session.php';
include '../inc/header.php';
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


<style>
.textarea-container {
position: relative;
width: 100%;
}
.textarea-container textarea {
width: 100%;
box-sizing: border-box;
}
.add-row-button {
position: absolute;
top: 5px;
right: 10px;
z-index: 10;
background: transparent;
border: none;
font-size: 18px;
color: #007bff;
cursor: pointer;
}
.inputs-overlay {
position: absolute;
top: 50px; /* Adjust based on the height of the textarea */
left: 0;
width: 100%;
pointer-events: none; /* Makes the overlay non-interactive */
z-index: 5;
}
.inputs-overlay .row {
margin-bottom: 5px;
}
.inputs-overlay .col input {
pointer-events: auto; /* Allows interaction with inputs */
}

     
        .upload-btn {
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .upload-btn i {
            margin-right: 8px;
        }

  

        input[type="file"] {
            display: none; /* Hide the default file input */
        }

        p {
            margin-top: 10px;
        }
        
 
        .custom-file-upload {
          border: 2px dashed #ccc;
          display: inline-block;
          cursor: pointer;

        }
        
        #map {
            height: 400px;
            width: 100%;
        }
        .select2-container--default .select2-selection--single {
    height: 38px; /* Adjust as needed */
}

/* Adjust the height of the select2 dropdown */
.select2-container--default .select2-results__options {
    max-height: 200px; /* Adjust as needed */
    overflow-y: auto;  /* Add scroll if content exceeds max-height */
}
   </style>
   
</style>
<div class="content-wrapper">

<div class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-12">

</div>

</div>
</div>
</div>


<div class="content">
<div class="container-fluid">


<div class="row">

<div class="col">
    <div class="card p-3 card-outline card-primary">
        <form action="../inc/function.php" method="POST"  enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-4">
                    <label for="">Location Name</label>
                    <input type="text" class="form-control" name="location_name" placeholder="Location Name">
                </div>
                <div class="col-lg-4">
                    <label for="category">Category</label>
                    <select name="category[]" class="form-control select2" multiple="multiple">
                        <option value="Nature">Nature</option>
                        <option value="Mountain">Mountain</option>
                        <option value="Historical">Historical</option>
                        <option value="Beach">Beach</option>
                        <option value="Church">Church</option>
                        <option value="Cultural">Cultural</option>
                        <option value="Relaxation">Relaxation</option>
                    </select>
                    
       
                </div>
               
            
              <div class="col-lg-4">
                    <label for="">Location</label>
                   <div class="input-group">
                        <input type="text" id="location" name="location1" class="form-control bg-white" placeholder="Latitude, Longitude">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mapModal">
                           <i class="fas fa-map-pin	"></i>
                        </button>
                    </div>
            </div>
            
            </div>

            <div class="row mt-3">
                <div class="col-lg-6  col-md-12 col-sm-12">
                    <label for="">Description</label>
                    <textarea name="description" class="form-control" placeholder="Description" cols="3" rows="3"></textarea>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
      
    
                <label for="">How to get there?</label>
                     <div class="textarea-container">
                <textarea class="form-control" id="instruction" placeholder="How to get there?" cols="3" rows="3"></textarea>
                <button class="add-row-button" type="button" id="addRowButton"><i class="fas fa-plus"></i></button>
                <div class="inputs-overlay" id="dynamicContainer">
                </div>
            </div>
            </div>
           </div>




        <div class="row mt-3">
            <?php
            $cities = [
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
            ?>
            <div class="col-lg-2 col-md-12 col-sm-12">
                <label for="city">Municipality</label><br>
                <select type="text" class="form-control select3" name="city" id="city">
                    <?php foreach ($cities as $city): ?>
                                                    <option value="<?php echo htmlspecialchars($city); ?>">
                                                        <?php echo htmlspecialchars($city); ?>
                                                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
                
            <div class="col-lg-2 col-md-12 col-sm-12 ml-5">
             <label for="">Upload Location Images</label><br>
               <button type="button" class="upload-btn custom-file-upload bg-transparent btn-sm font-weight-bold text-dark" id="uploadBtn">
                     Select Images &nbsp <i class="fas fa-upload"></i>
                </button>
                <input type="file" id="imageInput" name="images[]" accept="image/*" multiple>
                <p style="font-size:10px; margin-top:-1px;" class="fst-italic text-secondary" id="imageList"><i></i></p>
            </div>
            
             <div class="col-lg-2 col-md-12 col-sm-12">
                    <label for="">Upload 360° Virtual Tour</label>
                    <br>
                 <label for="file-upload" class="custom-file-upload bg-light shadow-sm">
                        Select Image <i class="fas fa-upload ml-1"></i>
                    </label>
                <input id="file-upload" name="tour_link" type="file"/>
            </div>
            
        </div>
   
   
   
   
   
   
    <hr>
            <div class="row mt-3">
                <div class="col">
                    <button class="btn btn-primary" type="submit" name="add_location"><i class="fas fa-plus"></i> Add</button>
                </div>
            </div>
    </form>
</div>
</div>
</div>


<div class="row">

<div class="col">
    <div class="card card-outline card-primary p-3 table-responsive">
        
        <?php
        $pdo_statement = $pdo->prepare("SELECT * FROM tbl_location");
        $pdo_statement->execute();
        $result = $pdo_statement->fetchAll();
        ?>

            <table id="table" class="table table-bordered table-hover font-weight-normal" style="font-size:15px;">
            <thead>
            <tr>
            <th class="text-center">Destination Name</th>
            <th class="text-center">Municipality</th>
            <th class="text-center">Images</th>
            <th class="text-center">Description</th>
            <th class="text-center">Tags</th>
            <th class="text-center">360° View</th>
            <th class="text-center">How to get there?</th>
            <th class="text-center">Location</th>
            <th class="text-center">Action</th>


            </tr>
        </thead>
        <tbody id="table-body">
            <?php
            if (!empty($result)) {
                foreach ($result as $row) {
                    ?>
                                                                <tr class="table-row text-center">        
                                                                        <td><?php echo $row['location_name']; ?></td>
                                                                        <td><?php echo $row['city']; ?></td>


                                                                        <td>
                                                                            <?php
                                                                            $images = explode(',', $row['image']);
                                                                            foreach ($images as $image) {
                                                                                $image = trim($image);
                                                                                if (!empty($image)) {
                                                                                    echo '<img src="images/' . htmlspecialchars($image) . '" alt="Location Images" style="width: 100px; height: auto; margin-right: 5px;">'; // Display the image
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </td>

                                                                        <td><?php echo $row['description']; ?></td>
                                                                        <td><?php echo $row['category']; ?></td>
                                                                        <td>
                                                                            <a type="button" target="_blank" href="panorama.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary view-panorama-btn btn-sm">
                                                                                <i class="fas fa-street-view"></i> 
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                           <button type="button" class="btn btn-outline-primary btn-sm view-instructions-btn" 
                                                                            data-bs-toggle="modal" data-bs-target="#instructionModal"
                                                                            data-instructions="<?php echo htmlspecialchars($row['instruction']); ?>">
                                                                            How to get there
                                                                        </button>
                                                                        </td>
                                                                        <td>
                                                                        <a href="map_location.php?id=<?php echo $row['id']; ?>" type="button" class="btn btn-outline-primary btn-sm map-btn" 
                    
                                                                            data-location1="<?php echo htmlspecialchars($row['location']); ?>">
                                                                            <i class="fas fa-map"></i>
                                                                        </a>

                                                                        <td>
                                                                            <button class="btn btn-primary editLocation" 
                                                                             data-id="<?php echo $row['id']; ?>"
                                                                             data-location_name="<?php echo $row['location_name']; ?>"
                                                                             data-description="<?php echo $row['description']; ?>"
                                                                             data-category="<?php echo $row['category']; ?>"
                                                                             data-tour_link="<?php echo $row['tour_link']; ?>"
                                                                             data-instruction="<?php echo $row['instruction']; ?>"
                                                                             data-image="<?php echo $row['image']; ?>"
                                                                             data-location="<?php echo $row['location']; ?>"
                                                                             ><i class="far fa-edit"></i></button>
                                                                             <button class="btn btn-secondary deleteLocation" 
                                                                             data-id="<?php echo $row['id']; ?>"
                                                                             ><i class="far fa-trash-alt"></i></button>

                                                                        </td>

                                                                <?php
                }
            }
            ?>
        </table>

        
    </div>
</div>
</div>
</div>
</div>
</div>


   <!-- Modal for Map -->
            <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mapModalLabel">Select Location</h5>
                        </div>
                        <div class="modal-body">
                            <div id="map"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="selectLocation">Select Location</button>
                        </div>
                    </div>
                </div>
            </div>
  
  
<!-- Modal Structure -->
<div class="modal fade" id="instructionModal" tabindex="-1" aria-labelledby="instructionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-center">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="instructionModalLabel">How to Get There?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="timeline">
                <div class="time-label">
                <span class="bg-primary"><i class="fas fa-map-signs"></i> Direction </span>
                </div>
                <div class="timeline" id="timelineContainer">
                    <!-- Timeline items will be dynamically inserted here -->
                </div>
                </div>
            </div>
       
        </div>
    </div>
</div>

<div class="modal fade" id="mapModal3" tabindex="-1" aria-labelledby="instructionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="instructionModalLabel">Location on Map</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map3" style="height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
include '../inc/footer.php';
?>


</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>




<script>
  var titleElement = document.getElementById("title");
  titleElement.innerHTML = "Location";

  window.onload = function() {
            var element = document.getElementById('locations');
            element.classList.add('active');
        };


</script>
<script>
    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a category",
            allowClear: true
        });
        
                $('.select3').select2({
            placeholder: "Select a City",
            allowClear: true
        });
        

        // Handle file input for multiple images
        document.getElementById('uploadBtn').addEventListener('click', function() {
            document.getElementById('imageInput').click();
        });

        // Display selected files
        document.getElementById('imageInput').addEventListener('change', function() {
            const fileList = this.files;
            const fileNames = [];

            for (let i = 0; i < fileList.length; i++) {
                fileNames.push(fileList[i].name);
            }

            document.getElementById('imageList').textContent = fileNames.join(', ');
        });

        // Adjust textarea height
        function updateTextareaHeight() {
            const overlayHeight = $('#dynamicContainer').outerHeight();
            $('#instruction').height(overlayHeight + 60); // 60px is an estimate for padding and border
        }

        $('#addRowButton').on('click', function(e) {
            e.preventDefault();

            const newRow = `
                <div class="row m-2">
                    <div class="col">
                        <input type="text" name="location[]" class="form-control btn-rounded" placeholder="Location">
                    </div>
                    <div class="col">
                        <input type="text" name="instruction[]" class="form-control btn-rounded" placeholder="How to get there?">
                    </div>
                </div>
            `;

            $('#dynamicContainer').append(newRow);
            updateTextareaHeight();
        });

        updateTextareaHeight();
    });
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    
    
    // Function to format and display the timeline
    function formatTimeline(instructions) {
        var timelineHTML = '';
        var pairs = instructions.split(',');
        pairs.forEach(function(pair, index) {
            var parts = pair.split('-');
            if (parts.length === 2) {
                var location = parts[0].trim();
                var instruction = parts[1].trim();
                timelineHTML += `
                
                    <div>
                        <i class="fas bg-light" style="border:1px solid #e3e6e4;"> ${index + 1}</i>
                        <div class="timeline-item border-none">
                            <h3 class="timeline-header">${location}</h3>
                            <div class="timeline-body" style="font-size:13px;">
                                ${instruction}
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        return timelineHTML;
    }

    // Event listener for the instruction buttons
    document.querySelectorAll('.view-instructions-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var instructions = this.getAttribute('data-instructions');
            var formattedTimeline = formatTimeline(instructions);
            document.getElementById('timelineContainer').innerHTML = formattedTimeline;
        });
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    let map;
    let marker;

    function initializeMap() {
        if (map) {
            map.remove(); // Clean up existing map
        }

        map = L.map('map').setView([13.756, 121.067], 12); // Centered on Batangas, Philippines

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map);
            window.selectedLocation = { lat, lng };
        });
    }

    document.getElementById('mapModal').addEventListener('shown.bs.modal', function() {
        initializeMap();
    });

    document.getElementById('selectLocation').addEventListener('click', function() {
        if (window.selectedLocation) {
            const { lat, lng } = window.selectedLocation;
            document.getElementById('location').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            const modal = bootstrap.Modal.getInstance(document.getElementById('mapModal'));
            modal.hide();
        }
    });
});
</script>



<script>
// document.addEventListener('DOMContentLoaded', function () {
//     var map1, marker1;

//     function initializeMap2(location) {
//         var [latitude, longitude] = location.split(',').map(Number);
        
//         if (map1) {
//             map1.remove();
//         }

//         map1 = L.map1('map3').setView([latitude, longitude], 13);

//         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//         }).addTo(map);

//         marker1 = L.marker1([latitude, longitude]).addTo(map1);
//     }

//     document.querySelectorAll('.map-btn').forEach(button => {
//         button.addEventListener('click', function () {
//             var location = this.getAttribute('data-location1');
//             // Initialize map with the location
//             initializeMap2(location);
//         });
//     });
// });
</script>
