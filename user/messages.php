<?php
include '../inc/session_user.php';
include 'header.php';
?>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group mb-2">
                                        <input type="text" id="search-input" class="form-control form-control-sm"
                                            placeholder="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-default border-none btn-sm" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <select id="filter-select" class="form-control form-control-sm">
                                        <option value="">All</option>
                                        <option value="Travel Companion">Travel Companion</option>
                                        <option value="Requesting">Requesting</option>
                                        <option value="Not Travel Companion">Not Travel Companion</option>
                                    </select>
                                </div>
                            </div>

                            <ul class="list-group mt-2">
                                <!-- Users list will be dynamically injected here -->
                                <div id="users-list"></div>
                            </ul>
                        </div>

                    </div>
                </div>
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
        // Function to load users into the list
        function loadUsers(query = '', filter = '') {
            $.ajax({
                url: 'user_message.php', // URL to your PHP script
                type: 'GET',
                data: { search: query, filter: filter }, // Send the search query and filter
                success: function (response) {
                    $('#users-list').html(response); // Inject HTML into the users list
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching users:', error);
                }
            });
        }

        // Load users when the document is ready
        loadUsers();

        // Fetch users as user types in the search input
        $('#search-input').on('input', function () {
            let searchQuery = $(this).val();
            let filterValue = $('#filter-select').val();
            loadUsers(searchQuery, filterValue);
        });

        // Fetch users when the filter changes
        $('#filter-select').on('change', function () {
            let filterValue = $(this).val();
            let searchQuery = $('#search-input').val();
            loadUsers(searchQuery, filterValue);
        });
    });
</script>