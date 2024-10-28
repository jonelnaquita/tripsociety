<div class="row mt-3">
    <div class="col">
        <h5 class="font-weight-bold">Write a review</h5>
        <?php
        if (isset($_SESSION['user'])) {
            ?>
            <a type="button" href="write_review3.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-dark"
                style="border-radius:10px;"><i class="far fa-edit"></i></a>
            <?php
        } else {
            ?>
            <a type="button" href="login.php" class="btn btn-outline-dark" style="border-radius:10px;"><i
                    class="far fa-edit"></i></a>
            <?php
        }
        ?>
    </div>
</div>