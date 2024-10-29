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
                dataType: 'json', // Expecting a JSON response
                success: function (data) {
                    let htmlOutput = ''; // Initialize an empty string for HTML output

                    if (data.length > 0) {
                        data.forEach(function (row) {
                            const profileImg = row.profile_img ? '../admin/profile_image/' + row.profile_img : '../dist/img/avatar2.png';
                            const userId = row.user_id;

                            // Construct the HTML for each request
                            htmlOutput += '<form action="../inc/function.php" method="POST" class="travel-companion-request" data-user-id="' + userId + '">';
                            htmlOutput += '    <div class="card p-2 border-0 shadow-none">';
                            htmlOutput += '        <div class="row">';
                            htmlOutput += '            <div class="col-auto">';
                            htmlOutput += '                <a href="profile.php?id=' + userId + '">';
                            htmlOutput += '                    <img src="' + profileImg + '" class="img-circle" style="width:30px;">';
                            htmlOutput += '                </a>';
                            htmlOutput += '            </div>';
                            htmlOutput += '            <div class="col-7">';
                            htmlOutput += '                <a class="text-dark notif-viewed" data-user-id="' + userId + '">';
                            htmlOutput += '                    <h6 class="font-weight-bold" style="font-size:13px;">' + row.name + ' wants you to be his/her travel companion</h6>';
                            htmlOutput += '                    <p style="font-size:13px; margin-top:-10px;" class="text-muted">' + row.date + '</p>';
                            htmlOutput += '                </a>';
                            htmlOutput += '            </div>';
                            htmlOutput += '            <div class="col ml-auto text-right">';
                            htmlOutput += '                <input value="' + userId + '" type="hidden" name="user_id">';
                            htmlOutput += '                <button class="btn btn-dark btn-xs" type="submit" name="accept_travel_companion">Accept</button>';
                            htmlOutput += '                <button class="btn btn-outline-dark btn-xs" type="submit" name="decline_travel_companion">Decline</button>';
                            htmlOutput += '            </div>';
                            htmlOutput += '        </div>';
                            htmlOutput += '    </div>';
                            htmlOutput += '</form>';
                        });
                    } else {
                        htmlOutput = '<p>No travel companion requests found.</p>';
                    }

                    $('#dynamicContent2').html(htmlOutput); // Update the .card-body with constructed HTML
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching content:', error); // Handle errors
                }
            });
        }

        // Event delegation for the notif-viewed link
        $(document).on('click', '.notif-viewed', function (e) {
            e.preventDefault(); // Prevent the default anchor behavior

            var userId = $(this).data('user-id'); // Get user_id from the data attribute

            // AJAX request to update the viewed status
            $.ajax({
                url: 'api/notification/update-travel-companion.php',
                type: 'POST',
                data: { user_id: userId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.href = 'profile.php?id=' + userId;
                    } else {
                        console.error('Error:', response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            });
        });

        // Fetch content every 1 second
        setInterval(fetchContent, 1000); // 1000 milliseconds = 1 second

        // Fetch content initially
        fetchContent();
    });
</script>