<?php
session_start();
include 'header.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<style>

</style>
<div class="content-wrapper">


    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="card p-2 mt-4">
                        <!-- Carousel HTML -->
                        <div class="carousel-container">
                            <div class="your-class">



                                <div class="text-center m-auto">
                                    <img src="../img/manual.png" style="width:400px;">
                                </div>
                                <div class="text-center m-auto">
                                    <img src="../img/manual.png" style="width:400px;">
                                </div>
                                <div class="text-center m-auto">
                                    <img src="../img/manual.png" style="width:400px;">
                                </div>
                                <div class="text-center m-auto">
                                    <img src="../img/manual.png" style="width:400px;">
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