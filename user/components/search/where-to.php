<div class="row">
    <div class="col">
        <h3 class="text-center font-weight-bold mt-4">Where to?</h3>

        <form action="explore_destination.php?search" class="mt-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="submit" class="btn btn-sm btn-default bg-white" style="border-right:none;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <input type="search" name="query" class="form-control form-control-sm" placeholder="Search"
                    id="search-input" oninput="fetchResults()">
                <div class="input-group-append">
                    <button type="button" class="btn btn-sm btn-default" id="filter-button">
                        <i class="fas fa-sliders-h"></i> <!-- Sliders icon -->
                    </button>
                </div>
            </div>
            <div class="dropdown mt-1">
                <div class="dropdown-menu p-3" id="search-dropdown">
                    <div class="recent-searches">
                        <div id="recent-searches-list"></div>
                        <hr>
                        <h6 class="text-left">Recent Searches</h6>
                        <?php
                        include '../inc/config.php';
                        $query = "
                SELECT *, u.location_id, l.location_name 
                FROM tbl_user_searches u
                LEFT JOIN tbl_location l ON u.location_id = l.id
                ORDER BY u.date_created DESC
                LIMIT 3
                 ";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute();
                        $results = $stmt->fetchAll();
                        foreach ($results as $row) {
                            $name = $row['location_name'];
                            $imageList = $row['image'];
                            $imageArray = explode(',', $imageList);
                            $firstImage = isset($imageArray[0]) ? $imageArray[0] : 'default.jpg';

                            $locationName = htmlspecialchars($row['location_name']);
                            $locationId = htmlspecialchars($row['location_id']);
                            echo "<a class='text-dark' href='explore_destination.php?id=$locationId'>
                    <img style='width:30px;' src='../admin/images/" . $firstImage . "'>
                    $locationName</a><br>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Prevent Enter key from submitting the form
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission
        }
    });
</script>