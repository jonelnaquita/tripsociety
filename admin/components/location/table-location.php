<style>
    .description-limited {
        max-width: 300px;
        /* Adjust this value as needed */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .timeline {
        border-left: 3px solid #727cf5;
        border-bottom-right-radius: 4px;
        border-top-right-radius: 4px;
        background: rgba(114, 124, 245, 0.09);
        margin: 0 auto;
        letter-spacing: 0.2px;
        position: relative;
        line-height: 1.4em;
        font-size: 1.03em;
        padding: 50px;
        list-style: none;
        text-align: left;
        max-width: 70%;
    }

    @media (max-width: 767px) {
        .timeline {
            max-width: 98%;
            padding: 25px;
        }
    }

    .timeline h1 {
        font-weight: 300;
        font-size: 1.4em;
    }

    .timeline h2,
    .timeline h3 {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .timeline .event {
        border-bottom: 1px dashed #e8ebf1;
        padding-bottom: 25px;
        margin-bottom: 25px;
        position: relative;
    }

    @media (max-width: 767px) {
        .timeline .event {
            padding-top: 30px;
        }
    }

    .timeline .event:last-of-type {
        padding-bottom: 0;
        margin-bottom: 0;
        border: none;
    }

    .timeline .event:before,
    .timeline .event:after {
        position: absolute;
        display: block;
        top: 0;
    }

    .timeline .event:before {
        left: -207px;
        text-align: right;
        font-weight: 100;
        font-size: 0.9em;
        min-width: 120px;
    }

    @media (max-width: 767px) {
        .timeline .event:before {
            left: 0px;
            text-align: left;
        }
    }

    .timeline .event:after {
        -webkit-box-shadow: 0 0 0 3px #727cf5;
        box-shadow: 0 0 0 3px #727cf5;
        left: -55.8px;
        background: #fff;
        border-radius: 50%;
        height: 9px;
        width: 9px;
        content: "";
        top: 5px;
    }

    @media (max-width: 767px) {
        .timeline .event:after {
            left: -31.8px;
        }
    }

    .rtl .timeline {
        border-left: 0;
        text-align: right;
        border-bottom-right-radius: 0;
        border-top-right-radius: 0;
        border-bottom-left-radius: 4px;
        border-top-left-radius: 4px;
        border-right: 3px solid #727cf5;
    }
</style>


<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Destination Table</h6>
                </div>
                <button type="button" class="btn btn-info mt-4 add-destination-btn"><i class="fa-solid fa-plus"></i> Add
                    New</button>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table id="locationTable" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Image</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Destination Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Municipality</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Description</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Category</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    360 View</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Location</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be dynamically inserted here by jQuery -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'modal/location-modal.php';
include 'modal/edit-location-modal.php';
include 'modal/delete-location-modal.php'; ?>

<script>
    $(document).ready(function () {
        // Initialize previous data variable
        let previousData = [];

        // Initial fetch of locations
        fetchLocations();

        // Function to fetch locations
        function fetchLocations() {
            $.ajax({
                url: 'api/location/fetch-locations.php',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Check if the data has changed
                    if (JSON.stringify(data) !== JSON.stringify(previousData)) {
                        // Store new data as previous data for comparison in the next interval
                        previousData = data;

                        // Clear and destroy the table if it exists
                        if ($.fn.DataTable.isDataTable('#locationTable')) {
                            $('#locationTable').DataTable().clear().destroy();
                        }

                        // Clear the table body
                        $("table tbody").empty();

                        // Loop through the data and append to the table
                        $.each(data, function (index, location) {
                            var row = `
                                <tr>
                                    <td class="text-center">
                                        <img src="images/${location.image.split(',')[0]}" class="avatar avatar-sm me-3 border-radius-lg" alt="${location.location_name}">
                                    </td>
                                    <td class="mb-0 text-sm font-weight-bold">${location.location_name}</td>
                                    <td class="mb-0 text-sm">${location.city}</td>
                                    <td class="description-limited mb-0 text-sm">${location.description}</td>
                                    <td class="text-xs font-weight-bold mb-0">
                                        <span class="badge badge-sm bg-gradient-success">${location.category}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="panorama.php?id=${location.id}" class="btn btn-primary">
                                            <i class="fas fa-street-view"></i> 
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="map_location.php?id=${location.id}" class="btn btn-info">
                                            <i class="fas fa-map"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn bg-gradient-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item view-location" data-bs-toggle="modal" data-bs-target="#locationDetailsModal" data-toggle="tooltip" data-id="${location.id}" title="View">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a class="dropdown-item edit-location-btn" data-bs-toggle="modal" data-bs-target="#editLocationModal" data-toggle="tooltip" data-id="${location.id}" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a class="dropdown-item delete-location-btn" data-toggle="tooltip" data-id="${location.id}" title="Delete">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>`;
                            $("table tbody").append(row);
                        });

                        // Reinitialize DataTable with options
                        $('#locationTable').DataTable({
                            "paging": true,
                            "searching": true,
                            "pagingType": "simple",
                            "language": {
                                "paginate": {
                                    "next": '<i class="fas fa-chevron-right"></i>',
                                    "previous": '<i class="fas fa-chevron-left"></i>'
                                },
                                "emptyTable": "No data available"
                            }
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching locations: ", status, error);
                }
            });
        }

        // Set interval to fetch locations every 5 seconds (5000 ms)
        setInterval(fetchLocations, 5000);
    });
</script>