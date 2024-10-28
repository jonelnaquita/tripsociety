<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
    <div class="card">
        <div class="card-body" id="dynamicContent2">



        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        function fetchContent() {
            $.ajax({
                url: '../inc/function.php?get_travel_companion_request', // URL to fetch the content
                method: 'GET',
                success: function (data) {
                    $('#dynamicContent2').html(data); // Update the .card-body with fetched data
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching content:', error); // Handle errors
                }
            });
        }

        // Fetch content every 1 second
        setInterval(fetchContent, 1000); // 1000 milliseconds = 1 second

        // Fetch content initially
        fetchContent();
    });
</script>