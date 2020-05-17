<?php

$page = "index";
include_once("header.php");


?>

<!-- Body wrapped by a div with class container -->
<div class="container" style="padding-bottom:3em;">
    <div class="jumbotron text-center">
        <h1 class="display-3">Snaphub</h1>
        <img class="logo" src="./public/images/logo.png" alt="logo">
        <p class="lead">Personal as well as Private image gallery for you!</p>
        <a href="./public-gallery.php" class="btn btn-dark btn-lg mb-2">Public Image Gallery</a>
        <!-- <div class="dropdown show"> -->
            <a class="btn btn-dark btn-lg mb-2 dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Add Images
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="./single-upload.php">Single Image Uploader</a>
                <a class="dropdown-item" href="./multiple-upload.php">Multiple Image Uploader</a>
            </div>
        <!-- </div> -->

    </div>
</div>
<!--........................................... -->

<?php

include_once("footer.php");

?>