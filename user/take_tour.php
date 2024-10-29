<?php
include 'header.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    include '../inc/config.php';
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tbl_location WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($locations) {
        foreach ($locations as $row) {
            $tour_link = $row['tour_link'];
        }
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pannellum/2.5.6/pannellum.css"
    integrity="sha512-UoT/Ca6+2kRekuB1IDZgwtDt0ZUfsweWmyNhMqhG4hpnf7sFnhrLrO0zHJr2vFp7eZEvJ3FN58dhVx+YMJMt2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/pannellum/2.5.6/pannellum.js"
    integrity="sha512-EmZuy6vd0ns9wP+3l1hETKq/vNGELFRuLfazPnKKBbDpgZL0sZ7qyao5KgVbGJKOWlAFPNn6G9naB/8WnKN43Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="content-wrapper">
    <button class="back-button" onclick="goBack()">
        <span class="material-icons">arrow_back</span> Back
    </button>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0"></h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <style>
                    * {
                        padding: 0;
                        margin: 0;
                    }

                    #panorama-360-view {
                        width: 100vw;
                        height: 100vh;
                    }
                </style>

                <div id="panorama-360-view"></div>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>

<script>
    pannellum.viewer('panorama-360-view', {
        "type": "equirectangular",
        "panorama": "<?php echo htmlspecialchars('../admin/panorama/' . $tour_link, ENT_QUOTES, 'UTF-8'); ?>",
        "autoLoad": true
    });
</script>