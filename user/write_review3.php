<?php
include 'header.php';
include '../inc/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    function getLocation($pdo, $id)
    {
        $query = "SELECT * FROM tbl_location WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $locations = getLocation($pdo, $id);

    if (!empty($locations)) {
        foreach ($locations as $location) {
            $name = htmlspecialchars($location['location_name']);
            $imageList = htmlspecialchars($location['image']);
            $imageArray = explode(',', $imageList);
            $firstImage = isset($imageArray[0]) ? $imageArray[0] : 'default.jpg';
        }
    }
}
?>

<style>
    .file-input {
        display: none;
    }

    .file-input-label {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        border-radius: 5px;
        color: #333;
    }

    .file-input-label i {
        margin-right: 5px;
    }

    .star-rating {
        display: flex;
        direction: row;
        cursor: pointer;
        margin-bottom: 15px;
    }

    .star-rating .fa-star {
        color: #ccc;
    }

    .star-rating .fa-star.hover,
    .star-rating .fa-star.highlighted {
        color: #f39c12;
    }

    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .image-preview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .card-header {
        background: #582fff;
    }

    .submit-btn {
        background-color: #582fff;
        color: white;
    }

    .submit-btn:hover {
        background-color: #001F4D;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

<div class="content-wrapper">
    <button class="back-button" onclick="goBack()">
        <span class="material-icons">arrow_back</span> Back
    </button>

    <section class="content overflow-hidden" style="height: auto; margin-bottom: 50px;">
        <div class="container-fluid">
            <form action="../inc/function.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="id">
                <div class="row mt-3">
                    <div class="col">
                        <div class="card mt-4 rounded-0 shadow">
                            <div class="card-header rounded-0">
                                <div class="row">
                                    <div class="col-4 m-auto">
                                        <img src="../admin/images/<?php echo $firstImage; ?>" class="img-fluid rounded"
                                            alt="<?php echo htmlspecialchars($name); ?>">
                                    </div>
                                    <div class="col-8 m-auto">
                                        <h4 class="font-weight-bold text-white mt-2">
                                            <?php echo htmlspecialchars($name); ?>
                                        </h4>
                                        <p class="text-white" style="font-size:13px; margin-top:-6px;">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($location['city']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="font-weight-bold">Rate your experiences:</h5>
                                <div class="star-rating">
                                    <i class="far fa-star fa-2x"></i>
                                    <i class="far fa-star fa-2x"></i>
                                    <i class="far fa-star fa-2x"></i>
                                    <i class="far fa-star fa-2x"></i>
                                    <i class="far fa-star fa-2x"></i>
                                </div>
                                <input type="hidden" id="rating-input" name="rating" value="0">

                                <h5 class="mt-3 font-weight-bold">Have you encountered any hazard?</h5>
                                <select class="form-control w-50" name="hazard" required>
                                    <option value="">Select a hazard level</option> <!-- Placeholder option -->
                                    <option value="No Hazard">No Hazard</option>
                                    <option value="Very low hazard">Very low hazard</option>
                                    <option value="Low hazard">Low hazard</option>
                                    <option value="Moderate hazard">Moderate hazard</option>
                                    <option value="High hazard">High hazard</option>
                                    <option value="Extreme hazard">Extreme hazard</option>
                                </select>

                                <div class="mt-3">
                                    <h5 class="font-weight-bold">Write your review</h5>
                                    <textarea class="form-control" name="review" rows="4"
                                        placeholder="Share your thoughts..." required></textarea>
                                </div>

                                <div class="mt-3">
                                    <h5 class="font-weight-bold">Share some photos of your visit</h5>
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="image-preview" id="image-preview"></div>
                                        </div>
                                        <div class="col-auto">
                                            <label for="file-input" class="file-input-label ml-2"
                                                style="margin-top:25px;">
                                                <i class="far fa-images fa-2x text-secondary"></i>
                                                <i class="fas fa-plus-circle shadow"
                                                    style="margin-left:-10px; margin-top:14px;"></i>
                                                <input type="file" name="images[]" id="file-input" class="file-input"
                                                    multiple required>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center"> <!-- Centering container -->
                                <button type="submit" name="add_review" class="btn btn-primary submit-btn"
                                    style="border-radius: 30px; padding: 12px 20px; margin-bottom: 50px;">
                                    <i class="fas fa-check-circle"></i> Submit Review
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>


<?php include 'footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('image-preview');

        fileInput.addEventListener('change', function (event) {
            previewContainer.innerHTML = ''; // Clear previous previews

            const files = event.target.files;

            for (const file of files) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        previewContainer.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                }
            }
        });

        const stars = document.querySelectorAll('.star-rating .fa-star');
        const ratingInput = document.getElementById('rating-input');

        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                stars.forEach((s, i) => {
                    s.classList.toggle('hover', i <= index);
                });
            });

            star.addEventListener('mouseout', () => {
                stars.forEach(s => s.classList.remove('hover'));
            });

            star.addEventListener('click', () => {
                ratingInput.value = index + 1;
                stars.forEach((s, i) => {
                    s.classList.toggle('highlighted', i <= index);
                });
            });
        });
    });
</script>