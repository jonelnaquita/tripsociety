<?php
include '../inc/session.php';
include '../inc/header.php';
?>


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

                        <?php
                        if (!isset($_GET['id'])) {
                            $pdo_statement = $pdo->prepare("SELECT * FROM tbl_user");
                            $pdo_statement->execute();
                            $result = $pdo_statement->fetchAll();
                        } else {
                            $userId = $_GET['id'];
                            $update_query = "UPDATE tbl_user SET unread = 1 WHERE id = :id";
                            $update_stmt = $pdo->prepare($update_query);
                            $update_stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                            $update_stmt->execute();

                            $pdo_statement = $pdo->prepare("SELECT * FROM tbl_user where id = " . $_GET['id'] . "");
                            $pdo_statement->execute();
                            $result = $pdo_statement->fetchAll();
                        }

                        ?>

                        <table id="table" class="table table-bordered table-hover font-weight-normal"
                            style="font-size:15px;">
                            <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Email Address</th>
                                    <th class="text-center">Travel Preferences</th>
                                    <th class="text-center">Location</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">ID Verification</th>


                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php
                                if (!empty($result)) {
                                    foreach ($result as $row) {
                                        ?>
                                        <tr class="table-row text-center">
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['travel_preferences']; ?></td>
                                            <td><?php echo $row['location']; ?></td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 0) {
                                                    echo '<span class="badge badge-secondary">Declined</span>';
                                                } elseif ($row['status'] == 1) {
                                                    echo '<span class="badge badge-success">Approved</span>';
                                                } else {
                                                    echo '<span class="badge badge-info">Pending</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-light viewId" data-id="<?php echo $row['id']; ?>"><i
                                                        class="fas fa-id-card"></i> View Document</button>

                                                <button class="btn btn-success approveUser"
                                                    data-id="<?php echo $row['id']; ?>"><i class="fas fa-check"></i></button>
                                                <button class="btn btn-secondary declineUser"
                                                    data-id="<?php echo $row['id']; ?>"><i class="fas fa-times"></i></button>

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

<?php
include '../inc/footer.php';
?>


</body>

</html>
<script>
    var titleElement = document.getElementById("title");
    titleElement.innerHTML = "Users";

    window.onload = function () {
        var element = document.getElementById('users');
        element.classList.add('active');
    };


</script>


<script>
    $(document).ready(function () {
        $('.viewId').on('click', function () {
            var userId = $(this).data('id');

            $.ajax({
                url: '../inc/function.php',
                type: 'GET',
                data: { view_document: true, id: userId },
                dataType: 'json',
                success: function (response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        // Log response to console to verify the data
                        console.log('Response:', response);

                        // Set the image sources and show the modal
                        $('#idFrontImage').attr('src', response.frontImage);
                        $('#idBackImage').attr('src', response.backImage);
                        $('#viewIdModal').modal('show');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    alert('An error occurred while fetching the document.');
                }
            });
        });
    });

</script>