<?php
include '../inc/session.php';
include "includes/header.php";
include '../inc/config.php';
include 'modal/show-user-id.php';
include 'modal/confirm-user-status.php';

if (isset($_GET['id'])) {
    $reportId = $_GET['id'];
    $update_query = "UPDATE tbl_user SET unread = 1 WHERE id = :id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindParam(':id', $reportId, PDO::PARAM_INT);
    $update_stmt->execute();
}
?>

<style>
    .dataTables_wrapper .dataTables_paginate {
        background-color: white;
        /* Change pagination background to white */
        padding: 10px;
        /* Optional padding */
        border-radius: 0.5rem;
        margin-right: 20px;
    }
</style>


<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Users Table</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table id="userTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Profile</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Email Address</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Travel Preferences</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Location</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID Verification</th>
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
            <?php include "includes/footer.php"; ?>
        </div>
    </main>
</body>


<script>
    $(document).ready(function () {
        const userId = getUrlParameter('id'); // Get 'id' from URL if present
        fetchUsers(userId);

        function getUrlParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        function fetchUsers(id = null) {
            $.ajax({
                url: 'api/user/fetch-user.php', // Path to the PHP script
                type: 'GET',
                data: { id: id }, // Send id if available
                dataType: 'json',
                success: function (data) {
                    const tableBody = $('#userTable tbody');
                    tableBody.empty(); // Clear the table body

                    data.forEach(function (row) {
                        const statusBadge = getStatusBadge(row.status, row.id_front, row.id_back);
                        const locationText = row.location ? row.location : 'No Selected City';
                        const tableRow = `
                        <tr>
                            <td class="text-center">
                                <img src="profile_image/${row.profile_img}" class="avatar avatar-sm me-3 border-radius-lg" alt="${row.profile_img}">
                            </td>
                            <td class="ml-4 mb-0 text-sm">${row.name}</td>
                            <td class="mb-0 text-sm">${row.email}</td>
                            <td class="mb-0 text-sm">${row.travel_preferences}</td>
                            <td class="mb-0 text-sm">${locationText}</td>
                            <td class="mb-0 text-sm">${statusBadge}</td>
                            <td class="mb-0 text-sm">
                                <button class="btn btn-light viewId" data-id="${row.id}">
                                    <i class="fas fa-id-card"></i> View Document
                                </button>
                                <button class="btn btn-success approveUser" data-id="${row.id}">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-secondary declineUser" data-id="${row.id}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                        tableBody.append(tableRow);
                    });

                    // Initialize DataTable after appending rows
                    $('#userTable').DataTable({
                        "destroy": true,
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
                },
                error: function () {
                    alert('An error occurred while fetching user data.');
                }
            });
        }

        function getStatusBadge(status, idFront, idBack) {
            if (!idFront && !idBack) {
                return '<span class="badge bg-gradient-danger">No ID Uploaded</span>';
            } else if (status == '1') {
                return '<span class="badge bg-gradient-info">Approved</span>';
            } else if (status == '0') {
                return '<span class="badge bg-gradient-secondary">Declined</span>';
            } else if (status === '' && idFront && idBack) {
                return '<span class="badge bg-gradient-warning">Pending</span>';
            }
        }
    });
</script>



<script>
    $(document).on('click', '.viewId', function () {
        const userId = $(this).data('id');

        $.ajax({
            url: 'api/user/fetch-id.php', // Path to the PHP script
            type: 'GET',
            data: { id: userId },
            dataType: 'json',
            success: function (data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Check and set the images in the modal
                    const frontImage = `id/${data.id_front}`;
                    const backImage = `id/${data.id_back}`;

                    // Front ID Image
                    if (data.id_front) {
                        $('#idFront').attr('src', frontImage).show();
                        $('#noFront').hide();
                    } else {
                        $('#idFront').hide();
                        $('#noFront').show();
                    }

                    // Back ID Image
                    if (data.id_back) {
                        $('#idBack').attr('src', backImage).show();
                        $('#noBack').hide();
                    } else {
                        $('#idBack').hide();
                        $('#noBack').show();
                    }

                    // Show the modal
                    $('#idModal').modal('show');
                }
            },
            error: function () {
                alert('An error occurred while fetching ID images.');
            }
        });
    });
</script>

<!-- Update User Status -->
<script>
    let currentUserId = null; // Store the current user ID to be updated
    let currentAction = null; // Store the current action (approve or decline)

    $(document).on('click', '.approveUser', function () {
        currentUserId = $(this).data('id');
        currentAction = 'approve';
        $('#confirmationMessage').text('Are you sure you want to approve this user?');
        $('#confirmationModal').modal('show');
    });

    $(document).on('click', '.declineUser', function () {
        currentUserId = $(this).data('id');
        currentAction = 'decline';
        $('#confirmationMessage').text('Are you sure you want to decline this user?');
        $('#confirmationModal').modal('show');
    });

    $('#confirmAction').on('click', function () {
        const status = currentAction === 'approve' ? 1 : 0; // Set status based on action
        $.ajax({
            url: 'api/user/update-status.php', // Path to the PHP script
            type: 'POST',
            data: {
                id: currentUserId,
                status: status
            },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    toastr.success(data.message); // Show success toastr message
                    $('#confirmationModal').modal('hide');
                    fetchUsers(); // Fetch users again to reflect changes
                } else {
                    toastr.error(data.error); // Show error toastr message
                }
                $('#confirmationModal').modal('hide'); // Hide the confirmation modal
            },
            error: function () {
                toastr.error('An error occurred while updating the user status.'); // Show error toastr message
                $('#confirmationModal').modal('hide'); // Hide the modal in case of error
            }
        });
    });
</script>