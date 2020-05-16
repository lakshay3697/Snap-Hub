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
        <a href="./image_list.php" class="btn btn-dark btn-lg mb-2">Public Image Gallery</a>
        <a href="./add-images.php" class="btn btn-dark btn-lg mb-2">Add Images</a>

    </div>
</div>
<!--........................................... -->

<?php

include_once("footer.php");

?>

    