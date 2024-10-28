<div class="row mt-3">
    <div class="col">
        <h5 class="font-weight-bold" style="color: #582fff;">Your Recent Searches</h5>
        <div id="recent-searches" class="btn-group d-flex flex-wrap">
            <!-- Recent searches will be dynamically inserted here -->
        </div>
    </div>
</div>

<script>
    // Function to fetch recent searches
    function fetchRecentSearches() {
        $.ajax({
            url: 'api/search/fetch-recent-searches.php', // PHP file to fetch recent searches
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var searchHTML = '';

                // Loop through the search results and create buttons
                $.each(data, function (index, search) {
                    searchHTML += `<a type='button' href='explore_destination.php?id=${search.location_id}' 
                                    class='btn btn-recent-search m-1'>
                                    ${search.location_name}</a>`;
                });

                // Update the HTML content with new recent searches
                $('#recent-searches').html(searchHTML);
            },
            error: function (xhr, status, error) {
                console.log('Error fetching recent searches:', error);
            }
        });
    }

    // Fetch recent searches initially
    fetchRecentSearches();

    // Optionally, set an interval to fetch recent searches every X seconds (for real-time updates)
    setInterval(fetchRecentSearches, 10000); // Fetch every 10 seconds
</script>

<!-- Add custom styles for the buttons -->
<style>
    .btn-recent-search {
        background-color: transparent;
        /* Transparent background */
        color: #582fff;
        /* Text color */
        border: 2px solid #582fff;
        font-size: 12px;
        /* Smaller font size */
        padding: 5px 15px;
        /* Reduced padding for smaller buttons */
        transition: background-color 0.3s, color 0.3s;
        /* Smooth transitions */
        text-decoration: none;
        /* Remove underline */
        white-space: nowrap;
        /* Prevent button from stretching */
        display: inline-block;
        /* Maintain individual size */
        margin: 5px;
        /* Space between buttons */
    }

    .btn-recent-search:hover {
        background-color: #582fff;
        /* Background color on hover */
        color: white;
        /* Change text color on hover */
    }
</style>