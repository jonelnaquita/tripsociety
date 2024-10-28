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
            <h5>All news & updates</h5>
            <hr>
            <div class="text-right mb-2">
                <button class="btn btn-primary shadow" data-toggle="modal" data-target="#addAnnouncementModal">Add news
                    & update</button>
            </div>
            <div class="row">
                <div class="col">
                    <?php
                    $pdo_statement = $pdo->prepare("SELECT * FROM tbl_announcement");
                    $pdo_statement->execute();
                    $result = $pdo_statement->fetchAll();
                    ?>

                    <?php if (!empty($result)) { ?>
                        <?php foreach ($result as $row) { ?>
                            <div class="card mb-3 p-3 card-outline card-primary">

                                <!-- Dropdown for actions -->
                                <div class="dropdown" style="float: right !important; text-align:right;">
                                    <button class="btn btn-sm btn-primary editAnnouncement" data-id="<?php echo $row['id']; ?>"
                                        data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                        data-description="<?php echo htmlspecialchars($row['description']); ?>"
                                        data-image="<?php echo htmlspecialchars($row['image']); ?>" type="button">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-secondary deleteAnnouncement"
                                        data-id="<?php echo $row['id']; ?>" type="button">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>

                                <!-- Announcement content -->
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                                        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                                    </div>
                                    <div class="col-lg-4 text-center">
                                        <img src="announcement/<?php echo htmlspecialchars($row['image']); ?>"
                                            style="width:250px;" class="img-fluid" alt="Announcement Image">
                                        <h6 class="mt-2">- Administrator</h6>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>No announcements available.</p>
                    <?php } ?>


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
    window.onload = function () {
        var element = document.getElementById('accounts');
        element.classList.add('active');
    };
</script>




<script>
    var titleElement = document.getElementById("title");
    titleElement.innerHTML = "News & Update";

    window.onload = function () {
        var element = document.getElementById('announcement');
        element.classList.add('active');
    };


</script>