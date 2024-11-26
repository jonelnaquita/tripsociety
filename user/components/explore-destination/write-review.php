<div class="row mt-3">
    <div class="col">
        <h5 class="font-weight-bold">Write a review</h5>
        <?php
        // Ensure the user session is set
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']; // Assuming the user ID is stored in the session
            $locationId = $row['id']; // Assuming $row contains the current location ID
        
            // Prepare the SQL query to check if the location exists in tbl_travel_log for the user
            $stmt = $pdo->prepare("
                SELECT DISTINCT l.location_name, l.id
                FROM tbl_location l
                INNER JOIN tbl_travel_log t ON l.id = t.location_id
                WHERE l.id = ? AND t.user_id = ?
            ");
            $stmt->execute([$locationId, $userId]);
            $locationExists = $stmt->fetch();

            // Show the write review button if the location exists for the user
            if ($locationExists) {
                ?>
                <a type="button" href="write_review3.php?id=<?php echo $locationId; ?>" class="btn btn-outline-dark"
                    style="border-radius:10px;">
                    <i class="far fa-edit"></i>
                </a>
                <?php
            } else {
                echo "<p class='text-muted'>You can write a review only for locations you've visited.</p>";
            }
        } else {
            // Redirect to login if the user is not logged in
            ?>
            <a type="button" href="login.php" class="btn btn-outline-dark" style="border-radius:10px;">
                <i class="far fa-edit"></i>
            </a>
            <?php
        }
        ?>
    </div>
</div>