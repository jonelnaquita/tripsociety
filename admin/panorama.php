<?php
include '../inc/session.php';
include "includes/header.php";
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pannellum/2.5.6/pannellum.css"
    integrity="sha512-UoT/Ca6+2kRekuB1IDZgwtDt0ZUfsweWmyNhMqhG4hpnf7sFnhrLrO0zHJr2vFp7eZEvJ3FN58dhVx+YMJMt2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/pannellum/2.5.6/pannellum.js"
    integrity="sha512-EmZuy6vd0ns9wP+3l1hETKq/vNGELFRuLfazPnKKBbDpgZL0sZ7qyao5KgVbGJKOWlAFPNn6G9naB/8WnKN43Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card p-3">
                                <style>
                                    * {
                                        padding: 0;
                                        margin: 0;
                                    }

                                    #panorama-360-view {
                                        width: 82.4vw;
                                        height: 90vh;
                                    }
                                </style>

                                <?php
                                include '../inc/config.php';
                                if (isset($_GET['id'])) {
                                    $id = intval($_GET['id']); // Ensure it's an integer
                                    $pdo_statement = $pdo->prepare("SELECT * FROM tbl_location WHERE id = :id");
                                    $pdo_statement->bindParam(':id', $id, PDO::PARAM_INT);
                                    $pdo_statement->execute();
                                    $result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
                                    if ($result) {
                                        $tourLink = 'panorama/' . $result['tour_link']; // Get the URL from the result
                                        ?>
                                        <div id="panorama-360-view"></div>

                                        <script>
                                            // Initialize Panorama Viewer with the URL from PHP
                                            document.addEventListener('DOMContentLoaded', function () {
                                                pannellum.viewer('panorama-360-view', {
                                                    "type": "equirectangular",
                                                    "panorama": "<?php echo $tourLink; ?>",
                                                    "autoLoad": true
                                                });
                                            });
                                        </script>
                                        <?php
                                    } else {
                                        echo 'No data found.';
                                    }
                                } else {
                                    echo 'Invalid ID.';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <?php include "includes/footer.php"; ?>
        </div>
    </main>
</body>