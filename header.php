<?php

$page = "header";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pics Gallore</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    <link rel="stylesheet" href="./public/styles/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-light bg-light mb-3">
        <div class="container">
            <a class="navbar-brand" href="./">Snaphub</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./about.php">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php if (!isset($_SESSION['user_id'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./sign-up.php">Register</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-users"></i>&nbsp;<?php echo ucwords($_SESSION['user_name']); ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toogle" data-toggle="dropdown" id="navbarDropdownMenu">Add Ideas&nbsp;<i class="fa fa-caret-down"></i></a>
                            <div class="dropdown-menu">
                                <a href="./single-upload.php" class="dropdown-item">Single Image Uploader</a>
                                <a href="./multiple-upload.php" class="dropdown-item">Multiple Image Uploader</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./public-gallery.php">Public Gallery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./my-gallery.php">My Gallery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./logout.php">Logout</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </nav>