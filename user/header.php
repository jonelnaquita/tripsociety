<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#4a90e2">
    <link rel="manifest" href="../manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="../img/logo.png">
    <link rel="apple-touch-icon" href="/img/icons/icon-512x512.png">
    <link rel="icon" type="image/png" href="../img/logo.png">

    <title>Trip Society</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css?v=3.2.0">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <!-- Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/chart.js/Chart.min.js"></script>
    <script src="../plugins/sparklines/sparkline.js"></script>
    <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="../dist/js/adminlte.js?v=3.2.0"></script>
    <script src="../dist/js/pages/dashboard.js"></script>

    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!--Slider-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.27/dist/fancybox.css">
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.27/dist/fancybox.umd.js"></script>

    <link rel="stylesheet" type="text/css" href="../plugins/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="../plugins/slick/slick-theme.css" />
    <script type="text/javascript" src="../plugins/slick/slick.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,         // Show close button
            "debug": false,
            "newestOnTop": false,        // Show newest notifications at the bottom
            "progressBar": true,         // Show progress bar
            "positionClass": "toast-top-right", // Position of the toast
            "preventDuplicates": true,   // Prevent duplicate toasts
            "onclick": null,
            "showDuration": "300",       // Animation duration
            "hideDuration": "1000",      // Hide animation duration
            "timeOut": "3000",           // Duration before auto hide
            "extendedTimeOut": "1000",   // Time to keep toast open after hover
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",      // Animation for showing
            "hideMethod": "fadeOut"      // Animation for hiding
        };
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('../service-worker.js')
                    .then(registration => {
                        console.log('Service Worker registered with scope:', registration.scope);
                    })
                    .catch(error => {
                        console.error('Service Worker registration failed:', error);
                    });
            });
        }
    </script>

    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Show the install button
            const installButton = document.getElementById('install-button');
            const installButtonContainer = document.getElementById('install-button-container');
            installButtonContainer.style.display = 'block';

            installButton.addEventListener('click', () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(choiceResult => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });

    </script>

    <script>
        // Function to go back to the last page
        function goBack() {
            window.history.back();
        }
    </script>

    <style>
        /* Back Button Styles */
        .back-button {
            display: inline-flex;
            align-items: center;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            background-color: #f5f5f5;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            margin-left: 20px;
            margin-top: 30px;
        }

        .back-button:hover {
            background-color: #e0e0e0;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.15);
        }

        .back-button .material-icons {
            font-size: 18px;
            margin-right: 5px;
        }
    </style>


</head>

<?php if (isset($_SESSION['user'])):
    include 'sidebar.php';
endif;
?>