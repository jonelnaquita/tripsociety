<?php
session_start();
include 'header.php';
?>

<style>
    /* Set consistent image dimensions and styling */
    .your-class img {
        width: 100%;
        height: 70vh;
        /* Adjust the height as necessary */
        object-fit: contain;
        /* Change from cover to contain to prevent cropping */
        border-radius: 8px;
    }

    /* Adjust dots styling */
    .slick-dots {
        bottom: -25px;
    }
</style>


<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h5 class="font-weight-bold mt-4">User Manual</h5>
                    <div class="card p-2 mt-4">
                        <!-- Carousel HTML -->
                        <div class="carousel-container">
                            <div class="your-class">
                                <!-- Each image wrapped in a Fancybox anchor link -->
                                <div class="text-center">
                                    <a href="../img/manual/1.png" data-fancybox="gallery">
                                        <img src="../img/manual/1.png" alt="Slide 1">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a href="../img/manual/2.png" data-fancybox="gallery">
                                        <img src="../img/manual/2.png" alt="Slide 2">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a href="../img/manual/3.png" data-fancybox="gallery">
                                        <img src="../img/manual/3.png" alt="Slide 3">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a href="../img/manual/4.png" data-fancybox="gallery">
                                        <img src="../img/manual/4.png" alt="Slide 4">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a href="../img/manual/5.png" data-fancybox="gallery">
                                        <img src="../img/manual/5.png" alt="Slide 5">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a href="../img/manual/6.png" data-fancybox="gallery">
                                        <img src="../img/manual/6.png" alt="Slide 6">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a href="../img/manual/7.png" data-fancybox="gallery">
                                        <img src="../img/manual/7.png" alt="Slide 7">
                                    </a>
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
<!-- Slider -->
<script>
    $(document).ready(function () {
        // Initialize Slick carousel
        $('.your-class').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 2000,
            dots: true,
            arrows: true
        });

        // Initialize Fancybox for images
        Fancybox.bind('[data-fancybox="gallery"]', {
            // Optional: Customize Fancybox settings here
        });
    });
</script>