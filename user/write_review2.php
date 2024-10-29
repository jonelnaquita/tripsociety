<?php
include 'header.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<style>
    .gallery-container {
        position: relative;
        overflow: hidden;
    }

    .gallery-wrapper {
        display: flex;
        transition: transform 0.5s ease;
    }

    .gallery-slide {
        flex: 0 0 auto;
        width: 100%;
    }

    .prev,
    .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        z-index: 2;
    }

    .prev {
        left: 10px;
    }

    .next {
        right: 10px;
    }

    .bg-secondary {
        background-color: #a8a8a8 !important;
    }

    .input-group-append {
        display: none;
        /* Hide the filter button initially */
    }

    .input-group-append.show {
        display: block;
        /* Show the filter button when needed */
    }

    .dropdown-menu {
        width: 100%;
        max-width: none;
    }

    .recent-searches {
        padding: 10px;
    }

    .recent-searches p {
        margin: 0;
        padding: 5px;
        cursor: pointer;
    }

    .recent-searches p:hover {
        background-color: #f0f0f0;
    }
</style>
<div class="content-wrapper">
    <section class="content overflow-hidden" style="height: 1000px;">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <h5 class="text-center font-weight-bold mt-4">What would you <Br> like to review?</h5>

                    <form action="explore_destination.php" class="mt-3">
                        <div class="input-group">
                            <input type="search" class="form-control form-control-sm" placeholder="Search a destination"
                                id="search-input">
                            <div class="input-group-append">

                            </div>
                        </div>
                        <div class="dropdown mt-1">
                            <div class="dropdown-menu p-3" id="search-dropdown">


                                <div class=" p-3" id="search-dropdown" style="width: 100%;"></div>


                            </div>
                        </div>
                    </form>

                    <!-- Modal -->
                    <div class="modal fade" id="filter-modal" tabindex="-1" role="dialog"
                        aria-labelledby="filter-modal-label" aria-hidden="true">
                        <div class="modal-dialog" style="margin-top:170px;" role="document">
                            <div class="modal-content">

                                <div class="modal-body">
                                    <h5 class="modal-title font-weight-bold text-center" id="filter-modal-label">Filters
                                    </h5>

                                    <div class="row mt-4">
                                        <div class="col">
                                            <h6>APPLIED FILTERS</h6>

                                            <div class="radio-group mt-3">
                                                <h6>CATEGORIES</h6>
                                                <label>
                                                    <input type="radio" name="category[]" value="option1">
                                                    Beach
                                                </label>
                                                <br>
                                                <label>
                                                    <input type="radio" name="category[]" value="option2">
                                                    Church
                                                </label>
                                                <br>
                                                <label>
                                                    <input type="radio" name="category[]" value="option3">
                                                    Mountain
                                                </label>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Clear
                                        Filter</button>
                                    <button type="button" class="btn btn-primary btn-sm">Apply Filters</button>
                                </div>
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
<!-- Slick Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>




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

<!-- Slider -->
<script>
    $(document).ready(function () {
        $('.your-class').slick({
            dots: true, // Show navigation dots
            infinite: true, // Infinite loop
            speed: 300, // Transition speed
            slidesToShow: 3, // Number of slides to show at a time
            slidesToScroll: 3, // Number of slides to scroll at a time
            nextArrow: '<button type="button" class="slick-next">></button>',
            prevArrow: '<button type="button" class="slick-prev"><</button>',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
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
    $(document).ready(function () {
        $("#search-input").on("input", function () {
            var query = $(this).val();

            if (query.length >= 2) { // Trigger search if at least 2 characters are typed
                $.ajax({
                    url: "../inc/function.php?view_destination", // URL to your PHP script
                    type: "GET",
                    data: { query: query },
                    success: function (response) {
                        $("#search-dropdown").html(response).show();
                    },
                    error: function (xhr, status, error) {
                        console.error("An error occurred:", status, error);
                    }
                });
            } else {
                $("#search-dropdown").empty().hide();
            }
        });

        // Hide dropdown when clicking outside
        $(document).click(function (e) {
            if (!$(e.target).closest('#search-input').length && !$(e.target).closest('#search-dropdown').length) {
                $("#search-dropdown").empty().hide();
            }
        });
    });
</script>