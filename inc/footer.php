

<aside class="control-sidebar control-sidebar-dark">

<div class="p-3">
<h5>Title</h5>
<p>Sidebar content</p>
</div>
</aside>

<footer class="main-footer">
<strong>Copyright &copy; 2024 <a href="" class="text-primary">Trip Society</a>.</strong> All rights reserved.
</footer>
</div>



<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>


<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/chart.js/Chart.min.js"></script>
<script src="../plugins/sparklines/sparkline.js"></script>
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="../plugins/sweetalert2/sweetalert2.min.js"></script>

<script src="../plugins/toastr/toastr.min.js"></script>
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../plugins/summernote/summernote-bs4.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>

<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../plugins/codemirror/codemirror.js"></script>
<script src="../plugins/codemirror/mode/css/css.js"></script>
<script src="../plugins/codemirror/mode/xml/xml.js"></script>
<script src="../plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>

<script src="../dist/js/adminlte.js?v=3.2.0"></script>
<script src="../plugins/flot/jquery.flot.js"></script>

<script src="../plugins/flot/plugins/jquery.flot.resize.js"></script>

<script src="../plugins/flot/plugins/jquery.flot.pie.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="../dist/js/pages/dashboard.js"></script>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>


</body>
</html>

<script>

  $(function () {
 
    $('#table').DataTable({
    });
  });


$(document).on('click', '.editLocation', function() {
    var id = $(this).data('id');
    var name = $(this).data('location_name');
    var categories = $(this).data('category'); // Retrieve the data attribute
    var location = $(this).data('location');
    var instruction = $(this).data('instruction');
    var image = $(this).data('image');

    // Split the categories string into an array, handling possible spaces
    var categoryArray = typeof categories === 'string' ? categories.split(',').map(function(item) {
        return item.trim(); // Remove extra spaces
    }) : [];

    var tourLink = $(this).data('tour_link');
    var description = $(this).data('description');
    
    // Set form values
    $('#editLocation1').val(location);
    $('#editLocationId').val(id);
    $('#editLocationName').val(name);
    $('#editLocationTourLink').val(tourLink);
    $('#editLocationImages').val(image);
    $('#editLocationDescription').val(description);
    $('#editLocationCategory').val(categoryArray).trigger('change'); // Update select2
    
    // Show the modal
    $('#editLocationModal').modal('show');
});

$(document).on('click', '.deleteLocation', function() {
  var id = $(this).data('id');
  $('#deleteLocationId').val(id);
  $('#deleteLocationModal').modal('show');
}); 


$(document).on('click', '.approveUser', function() {
  var id = $(this).data('id');
  $('#approveUserId').val(id);
  $('#approveUserModal').modal('show');
}); 


$(document).on('click', '.declineUser', function() {
  var id = $(this).data('id');
  $('#declineUserId').val(id);
  $('#declineUserModal').modal('show');
}); 



$(document).on('click', '.declineUser', function() {
  var id = $(this).data('id');
  $('#declineUserId').val(id);
  $('#declineUserModal').modal('show');
}); 

    $(document).on('click', '.editAnnouncement', function() {
        console.log('Edit button clicked');

        var $card = $(this).closest('.card');
        var id = $card.find('.editAnnouncement').data('id');
        var title = $card.find('.editAnnouncement').data('title');
        var description = $card.find('.editAnnouncement').data('description');
        var image = $card.find('.editAnnouncement').data('image');
        
        console.log('ID:', id);
        console.log('Title:', title);
        console.log('Description:', description);
        console.log('Image:', image);

        $('#editAnnouncementId').val(id);
        $('#editAnnouncementTitle').val(title);
        $('#editAnnouncementDescription').val(description);

        if (image) {
            $('#editAnnouncementImagePreview').attr('src', 'announcement/' + image).show();
            console.log('Image preview set to:', 'announcement/' + image);
        } else {
            $('#editAnnouncementImagePreview').hide();
            console.log('No image to preview');
        }
        
        $('#editAnnouncementModal').modal('show');
    });
    
    // Handle delete button click
    $(document).on('click', '.deleteAnnouncement', function() {
        var id = $(this).closest('.card').find('.deleteAnnouncement').data('id');
        $('#deleteAnnouncementId').val(id);
        $('#deleteAnnouncementModal').modal('show');
    });
</script>




<?php
if (isset($_SESSION['message']) && isset($_SESSION['response']) && isset($_SESSION['message_timestamp'])) {
    $message = $_SESSION['message'];
    $response = $_SESSION['response'];
    $timestamp = $_SESSION['message_timestamp'];

    if (time() - $timestamp <= 5) {
        echo '<script>
            $(document).ready(function() {';

        if ($response === 'Success') {
            echo 'toastr.success("' . $message . '");';
        } else {
            echo 'toastr.error("' . $message . '");';
        }

        echo '});
            </script>';
    } else {
        unset($_SESSION['message']);
        unset($_SESSION['response']);
        unset($_SESSION['message_timestamp']);
    }
}
?>
