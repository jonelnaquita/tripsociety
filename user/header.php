<?php
session_start();
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

</head>

<?php
include 'sidebar.php';
?>