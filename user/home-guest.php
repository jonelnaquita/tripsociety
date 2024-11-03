<?php
include 'header.php';
include 'modal/search-modal.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="assets/css/search.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>-->

    <link rel="stylesheet" type="text/css" href="../plugins/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="../plugins/slick/slick-theme.css" />
    <script type="text/javascript" src="../plugins/slick/slick.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.27/dist/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.27/dist/fancybox.umd.js"></script>


    <style>
        body {
            padding-top: 56px;
            background-color: #f5f5f5;
        }

        .navbar {
            background-color: #582fff;
        }

        .navbar-brand {
            color: white !important;
        }

        .nav-tabs {
            justify-content: center;
            /* Center the tabs */
            border-bottom: 1px solid #582fff;
            /* Same border as navbar */
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            color: white;
            /* Change text color for active tab */
            background-color: #582fff;
            /* Match the active tab color with navbar */
            border: 1px solid #582fff;
            border-bottom-color: transparent;
        }

        .nav-link {
            color: #582fff;
        }

        .post {
            background-color: white;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .post img {
            max-width: 100%;
            border-radius: 10px;
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .material-icons {
            vertical-align: middle;
            margin-right: 5px;
        }

        .tab-content {
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            /* Additional styles if needed */
            padding: 0.5rem 1rem;
            /* Adjust padding if necessary */
        }

        #install-button-container {
            display: flex;
            /* Ensure flexbox behavior */
            align-items: center;
            /* Center vertically */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #582fff;">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="../img/logo-white.png" width="40px;" alt="Logo">
            <span class="font-weight-bolder text-light ml-2">TripSociety</span>
        </a>

        <div class="ml-auto">
            <li class="nav-item dropdown" id="install-button-container" style="margin-right: -13px; display:none;">
                <a class="nav-link" href="#">
                    <i class="fas fa-download text-white" id="install-button" style="font-size:18px;"></i>
                </a>
            </li>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="search-tab" data-toggle="tab" href="#search" role="tab"
                    aria-controls="search" aria-selected="true">Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="community-tab" data-toggle="tab" href="#community" role="tab"
                    aria-controls="community" aria-selected="false">Community</a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="myTabContent">
            <!-- Search Tab -->
            <div class="tab-pane fade show active" id="search" role="tabpanel" aria-labelledby="search-tab">
                <div class="mt-3">
                    <?php
                    include 'components/search/where-to.php';
                    include 'components/search/top-destination.php';
                    include 'components/search/recommendation.php';
                    ?>
                </div>
            </div>

            <!-- Community Tab -->
            <div class="tab-pane fade" id="community" role="tabpanel" aria-labelledby="community-tab">
                <div class="mt-3">
                    <div id="reviews-container" class="mb-3"></div>
                    <h6 class="font-weight-bold ml-2">Latest</h6>
                    <div id="announcements-container" class="mt-2"></div>
                </div>
            </div>

        </div>
    </div>

</body>
<?php
include 'footer.php';
?>

<style>
    .profile-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .review-images {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .fancybox-image img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        cursor: pointer;
        border-radius: 5px;
    }
</style>

<script>
    $(document).ready(function () {
        fetchReviewsAndAnnouncements();
    });

    function fetchReviewsAndAnnouncements() {
        $.ajax({
            url: 'api/home/fetch-guest-home.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                displayReviews(data.reviews);
                displayAnnouncements(data.announcements);
            },
            error: function (xhr, status, error) {
                console.log('Error fetching reviews and announcements:', error);
                console.log(xhr.responseText); // Log the response for debugging
            }
        });
    }

    function displayReviews(reviews) {
        let reviewsHTML = '';
        $.each(reviews, function (index, review) {
            // Check if review.images exists and split by commas to get an array
            let imagesHTML = '';
            if (review.images) {
                let images = review.images.split(', '); // Assuming images are separated by commas
                imagesHTML = `<div class="review-images">`;
                $.each(images, function (i, image) {
                    imagesHTML += `
                        <a data-fancybox="gallery-${index}" href="../admin/review_image/${image}" class="fancybox-image">
                            <img src="../admin/review_image/${image}" alt="Review Image" class="img-fluid">
                        </a>`;
                });
                imagesHTML += `</div>`;
            }

            reviewsHTML += `
                <div class="post">
                    <div class="d-flex align-items-center mb-2">
                        <img src="${review.profile_img ? '../admin/profile_image/' + review.profile_img : 'https://via.placeholder.com/50'}" alt="Profile Image" class="profile-image">
                        <h6 class="font-weight-bold mb-0 ms-2 ml-2">${review.user_name ? review.user_name : 'Anonymous'}</h6>
                    </div>
                    <div>
                        <p>${review.review}</p>
                        ${imagesHTML}
                    </div>
                </div>`;
        });
        $('#reviews-container').html(reviewsHTML);
    }

    function displayAnnouncements(announcements) {
        let announcementsHTML = '';
        $.each(announcements, function (index, announcement) {
            announcementsHTML += `
            <div class="post">
                <div class="d-flex align-items-center mb-2">
                    <img src="../img/logo.png" alt="Profile Image" class="profile-image">
                    <h6 class="font-weight-bold mb-0 ms-2 ml-2">TripSociety</h6>
                </div>
                <div>
                    <h6 class="font-weight-bold">${announcement.title}</h6>
                    <p>${announcement.description}</p>
                    ${announcement.image ? `
                        <a data-fancybox="announcement-${index}" href="../admin/announcement/${announcement.image}">
                            <img src="../admin/announcement/${announcement.image}" alt="Post Image" class="img-fluid">
                        </a>` : ''}
                </div>
            </div>`;
        });
        $('#announcements-container').html(announcementsHTML);
    }

</script>





<script>
    var searchInput = document.getElementById('search-input');
    var filterButton = document.querySelector('.input-group-append');

    searchInput.addEventListener('focus', function () {
        filterButton.classList.add('show');
    });

    searchInput.addEventListener('blur', function () {
        // Optional: Hide the filter button if the input loses focus and is empty

    });

    document.getElementById('filter-button').addEventListener('click', function () {
        $('#filter-modal').modal('show');
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('search-input');
        var dropdownMenu = document.getElementById('search-dropdown');

        searchInput.addEventListener('focus', function () {
            dropdownMenu.classList.add('show');
        });

        document.addEventListener('click', function (event) {
            if (!searchInput.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        var recentSearches = document.querySelectorAll('.recent-searches p');
        recentSearches.forEach(function (item) {
            item.addEventListener('click', function () {
                searchInput.value = this.textContent;
                dropdownMenu.classList.remove('show');
            });
        });
    });
</script>



<script>
    document.getElementById('clear-filters').addEventListener('click', function () {
        // Clear all selected radio buttons
        document.querySelectorAll('input[name="category"]').forEach(function (el) {
            el.checked = false;
        });

        // Clear all selected checkboxes
        document.querySelectorAll('input[name="location[]"]').forEach(function (el) {
            el.checked = false;
        });
    });
</script>




<script>
    function fetchResults() {
        const query = document.getElementById('search-input').value;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'user_searches.php?query=' + encodeURIComponent(query), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById('recent-searches-list').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>




<script>
    function togglePreferences() {
        var additionalPreferences = document.getElementById('additional-preferences');
        var showMoreButton = document.getElementById('show-more');

        if (additionalPreferences.style.display === 'none') {
            additionalPreferences.style.display = 'block';
            showMoreButton.textContent = 'Show Less';
        } else {
            additionalPreferences.style.display = 'none';
            showMoreButton.textContent = 'Show More';
        }
    }

    // Update selected categories and cities
    function updateSelections() {
        var selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked'))
            .map(input => input.value)
            .join(', ');
        document.getElementById('selected-categories').textContent = selectedCategories;

        var selectedCities = Array.from(document.querySelectorAll('input[name="location[]"]:checked'))
            .map(input => input.value)
            .join(', ');
        document.getElementById('selected-cities').textContent = selectedCities;
    }

    // Attach event listeners
    document.addEventListener('DOMContentLoaded', function () {
        // Update categories on change
        document.querySelectorAll('input[name="category"]').forEach(function (input) {
            input.addEventListener('change', updateSelections);
        });

        // Update cities on change
        document.querySelectorAll('input[name="location[]"]').forEach(function (input) {
            input.addEventListener('change', updateSelections);
        });
    });
</script>

<script>
    function toggleCities() {
        var moreCities = document.getElementById('more-cities');
        var showMoreButton = document.getElementById('show-more1');

        if (moreCities.style.display === 'none') {
            moreCities.style.display = 'block';
            showMoreButton.textContent = 'Show Less';
        } else {
            moreCities.style.display = 'none';
            showMoreButton.textContent = 'Show More';
        }
    }

    // Update selected categories and cities
    function updateSelections() {
        var selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked'))
            .map(input => input.value)
            .join(', ');
        document.getElementById('selected-categories').textContent = selectedCategories;

        var selectedCities = Array.from(document.querySelectorAll('input[name="location[]"]:checked'))
            .map(input => input.value)
            .join(', ');
        document.getElementById('selected-cities').textContent = selectedCities;
    }

    // Attach event listeners
    document.addEventListener('DOMContentLoaded', function () {
        // Update categories on change
        document.querySelectorAll('input[name="category"]').forEach(function (input) {
            input.addEventListener('change', updateSelections);
        });

        // Update cities on change
        document.querySelectorAll('input[name="location[]"]').forEach(function (input) {
            input.addEventListener('change', updateSelections);
        });
    });
</script>

</html>