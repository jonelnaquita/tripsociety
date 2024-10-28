<ul class="nav nav-tabs bg-white p-2" style="border-radius:5px;" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="active"
            aria-selected="true">Posts</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-dark" id="link2-tab" data-toggle="tab" href="#link2" role="tab" aria-controls="link2"
            aria-selected="false">Reviews</a>
    </li>
    <li class="nav-item ml-auto">
        <?php
        if (isset($_SESSION['user'])) {
            ?>


        <li class="nav-item ml-auto">
            <div class="dropdown mt-1">
                <button class="btn btn-dark btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" style="font-size:13px;">
                    Edit
                </button>
                <div class="dropdown-menu" style="margin-right:115px;" aria-labelledby="dropdownMenuButton">
                    <form id="profilePhotoForm" action="../inc/function.php?add_profile_image" method="POST"
                        enctype="multipart/form-data">
                        <a class="dropdown-item" style="font-size:13px;" href="#" id="uploadProfilePhotoLink">Upload Profile
                            Photo</a>
                        <input type="file" name="profile_img" id="profilePhotoInput" accept="image/*"
                            style="display: none;" />
                        <button name="add_profile_image" type="submit" hidden></button>
                    </form>

                    <script>
                        $(document).ready(function () {
                            // When the "Upload Profile Photo" link is clicked
                            $('#uploadProfilePhotoLink').on('click', function (event) {
                                event.preventDefault(); // Prevent default link behavior
                                $('#profilePhotoInput').click(); // Trigger the file input click event
                            });

                            // When a file is selected
                            $('#profilePhotoInput').on('change', function () {
                                var file = $(this).prop('files')[0];
                                if (file) {
                                    var reader = new FileReader();
                                    reader.onload = function (e) {
                                        $('#profilePhotoPreview').attr('src', e.target.result).show(); // Show preview
                                    };
                                    reader.readAsDataURL(file);

                                    // Automatically submit the form
                                    $('#profilePhotoForm').submit();
                                }
                            });
                            // When the "Upload Profile Photo" link is clicked
                            $('#uploadProfilePhotoLink1').on('click', function (event) {
                                event.preventDefault(); // Prevent default link behavior
                                $('#profilePhotoInput1').click(); // Trigger the file input click event
                            });
                            $('#profilePhotoInput1').on('change', function () {
                                var file = $(this).prop('files')[0];
                                if (file) {
                                    var reader = new FileReader();
                                    reader.onload = function (e) {
                                        $('#profilePhotoPreview1').attr('src', e.target.result).show(); // Show preview
                                    };
                                    reader.readAsDataURL(file);
                                    $('#profilePhotoForm1').submit();
                                }
                            });
                        });
                    </script>

                    <form id="profilePhotoForm1" action="../inc/function.php?add_cover_image" method="POST"
                        enctype="multipart/form-data">
                        <a class="dropdown-item" style="font-size:13px;" href="#" id="uploadProfilePhotoLink1">Upload Cover
                            Photo</a>
                        <input type="file" name="cover_img" id="profilePhotoInput1" accept="image/*"
                            style="display: none;" />
                        <button name="add_cover_image" type="submit" hidden></button>
                    </form>

                </div>
            </div>
        </li>

        <?php
        }
        ?>
    </li>
</ul>