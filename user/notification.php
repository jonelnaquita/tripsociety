<?php
include 'header.php';
?>

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css"
    integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />


<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0"></h1>
                </div>

            </div>
        </div>
    </div>



    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Tab navigation -->
            <ul class="nav nav-tabs m-auto border-0" id="myTab" role="tablist">
                <li class="nav-item ml-1">
                    <a type="button" class="btn btn-outline-dark border-dark nav-link active pt-1 pb-1 pl-2 pr-2"
                        id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                        style="font-size:12px;" aria-selected="true">Notifications</a>
                </li>
                <li class="nav-item ml-1">
                    <a type="button" class="nav-link btn btn-outline-dark border-dark  pt-1 pb-1 pl-2 pr-2"
                        id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
                        style="font-size:12px;" aria-selected="false">Companion Request</a>
                </li>
                <li class="nav-item ml-1">
                    <a type="button" class="nav-link btn btn-outline-dark border-dark  pt-1 pb-1 pl-2 pr-2"
                        id="news-tab" data-toggle="tab" href="#news" role="tab" aria-controls="contact"
                        style="font-size:12px;" aria-selected="false">News & Updates</a>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content mt-2" id="myTabContent">
                <?php include 'components/notification/user-engagement.php'; ?>
                <?php include 'components/notification/news-notification.php'; ?>
                <?php include 'components/notification/companion-request.php'; ?>

            </div>
        </div>
    </div>
    <br><br><br>
</div>
</section>

</div>
<?php
include 'footer.php';
?>