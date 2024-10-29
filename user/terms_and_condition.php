<?php
include 'header.php';
?>

<div class="content-wrapper">



    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="card" style="margin-top:40px;">
                        <div class="card-body">
                            <p class="font-weight-bold">By participating in TripSociety: Uniting Wanderers in Exploring
                                the Wonders of Batangas ("TripSociety"), you agree to comply with the following terms
                                and conditions. TripSociety is a collaborative initiative aimed at bringing together
                                adventurers and explorers to discover the diverse wonders of Batangas.
                                Participants are expected to conduct themselves in a respectful and responsible manner
                                throughout all activities organized by TripSociety. All participants must adhere to
                                local laws, regulations, and guidelines, and are responsible for their own safety and
                                well-being during TripSociety events. TripSociety organizers reserve the right to modify
                                or cancel any event due to unforeseen circumstances or safety concerns.
                                By participating in TripSociety, you consent to the use of any photographs, videos, or
                                other media captured during events for promotional purposes. TripSociety organizers are
                                not liable for any loss, injury, or damage incurred during participation in TripSociety
                                activities. Participants are encouraged to act in an environmentally conscious manner
                                and to respect the natural beauty and cultural heritage of Batangas. By registering for
                                TripSociety events, you acknowledge that you have read, understood, and agree to abide
                                by these terms and conditions.</p>


                            <?php
                            if (!isset($_SESSION['user'])) {
                                ?>
                                <div class="text-right">
                                    <a href="home.php" type="button" class="btn btn-outline-dark pl-4 pr-4">Cancel</a>
                                    <a href="register.php?tnc" class="btn btn-secondary pl-4 pr-4 ml-2"
                                        type="button">Agree</a>
                                </div>
                                <?php
                            }
                            ?>

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