<?php
include 'header.php';
include 'modal/search-modal.php';
?>

<link rel="stylesheet" href="assets/css/search.css">
<!-- Include Slick Carousel CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

<div class="content-wrapper">
    <section class="content overflow-hidden" style="height: 1000px;">
        <div class="container-fluid">

            <?php
            include 'components/search/where-to.php';
            include 'components/search/top-destination.php';
            include 'components/search/recent-searches.php';
            include 'components/search/recommendation.php';
            ?>

    </section>

</div>

<?php
include 'footer.php';
?>
<!-- Slick Carousel JS -->




<!-- Filter-->


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