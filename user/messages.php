<?php
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
                                    <div class="input-group">
                                        <input type="text" id="search-input" class="form-control form-control-sm"
                                            placeholder="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-default border-none btn-sm" type="button"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
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
        function loadUsers(query = '') {
            $.ajax({
                url: 'user_message.php', // URL to your PHP script
                type: 'GET',
                data: { search: query }, // Send the search query
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
            loadUsers(searchQuery);
        });
    });
</script>