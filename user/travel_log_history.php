<?php
include '../inc/session_user.php';
include 'header.php';
?>

<div class="content-wrapper">


    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col">
                    <div class="timeline mt-3">
                        <!-- Timeline items will be inserted here by the AJAX script -->
                    </div>
                </div>
            </div>
        </div>

    </section>

</div>
<?php
include 'footer.php';
?>

<script>
    $(document).ready(function () {
        function fetchTravelLogs() {
            $.ajax({
                url: 'api/travel-log/fetch-travel-log-history.php', // URL to the PHP script
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    let timelineHTML = '';
                    let previousDate = null;;

                    data.forEach(function (log, index) {
                        const logDate = new Date(log.date_created);

                        timelineHTML += `
                            <div>
                                <i class="fas fa-map-marked-alt text-white" style="font-size:16px !important;"></i>
                                <div class="timeline-item">
                                    <div class="timeline-body">
                                        <strong>${log.location_name}</strong>
                                        • ${log.formatted_date} <br>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    $('.timeline').html(timelineHTML);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        // Call the function to fetch and display the logs
        fetchTravelLogs();
    });
</script>