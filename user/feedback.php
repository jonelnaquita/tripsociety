<?php 
include 'header.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


<style>
.star-rating {
            display: flex;
            direction: row;
            cursor: pointer;
        }
.star-rating .fa-star {
    color: #ccc; /* Default color */
}
.star-rating .fa-star.hover,
.star-rating .fa-star.highlighted {
    color: #f39c12; /* Color for highlighted stars */
}

</style>
<div class="content-wrapper">

<div class="content-header">



<section class="content">
<div class="container-fluid">
<form action="../inc/function.php" method="POST">
<div class="row mt-5">
<div class="col m-auto">
    <div class="card m-auto" style="background-color:#6CB4EE;">
        <div class="card-header font-weight-bold" style="font-size:20px">
            <i class="fas fa-comments"></i> Feedback
        </div>
        <div class="card-body">
            <h5 class="font-weight-bold">Rate your experience</h5>
             <div class="star-rating">
                <i class="far fa-star fa-2x"></i>
                <i class="far fa-star fa-2x"></i>
                <i class="far fa-star fa-2x"></i>
                <i class="far fa-star fa-2x"></i>
                <i class="far fa-star fa-2x"></i>
            </div>
            <input type="hidden" id="rating-input" name="rate" value="0">

            <h5 class="font-weight-bold mt-3">Which part of the app needs improvement?</h5>
            <select class="form-control" name="app_improvement[]" multiple="multiple" id="app_improvement">
                <option value="None">None</option>
                <option value="User Interface">User Interface</option>
                <option value="Security">Security</option>
                <option value="Privacy">Privacy</option>
                <option value="Loading Speed">Loading Speed</option>
                <option value="Community Content">Community Content</option>
                <option value="Functionality">Functionality</option>
                <option value="Accessibility">Accessibility</option>
            </select>

            <textarea class="form-control mt-2" placeholder="Add comment" rows="4" name="feedback"></textarea>
            <div class="text-center mt-3">
                
                    <?php
                    if(isset($_SESSION['user'])){
                    ?>
                <button class="btn btn-dark btn-block" style="border-radius:25px; background-color:#002D62;" type="submit" name="add_feedback">SUBMIT NOW</button>
                    <?php 
                    }else{
                    ?>
                <a type="button" href="login.php" class="btn btn-dark btn-block" style="border-radius:25px; background-color:#002D62;">SUBMIT NOW</a>
                    <?php 
                    }
                    ?>
                    
        
            </div>
        </div>
    </div>
</div>

</div>
</form>
</div>
</section>

</div>
<?php 
include 'footer.php';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#app_improvement').select2({
        placeholder: "Select improvements",
        allowClear: true
    });
});
</script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating .fa-star');
            const ratingInput = document.getElementById('rating-input');

            stars.forEach((star, index) => {
                star.addEventListener('mouseover', () => {
                    // Highlight stars on hover
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.add('hover');
                        } else {
                            s.classList.remove('hover');
                        }
                    });
                });

                star.addEventListener('mouseout', () => {
                    // Remove highlight when not hovering
                    stars.forEach(s => s.classList.remove('hover'));
                });

                star.addEventListener('click', () => {
                    // Set rating value on click
                    ratingInput.value = index + 1;
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.add('highlighted');
                        } else {
                            s.classList.remove('highlighted');
                        }
                    });
                });
            });
        });
    </script>
