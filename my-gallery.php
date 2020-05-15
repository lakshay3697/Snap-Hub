<?php

// Authorization check ............

session_start();
if(!isset($_SESSION['user_id'])) {
  header("Location: ./index.php");
  die;
}

// ................................

require "con_pdo.php";
// echo '<pre>';

$limit = 10;
if (isset($_GET['page'])) {
    $pn = $_GET['page'];
} else {
    $pn = 1;
};
$start_from = ($pn - 1) * $limit;

$user =  $_SESSION['user_id'];

$get_images = "SELECT user_images.image_title,user_images.image_name,user_images.image_description FROM user_images WHERE user_id = $user LIMIT $start_from, $limit";

// echo $get_images . "\n";

$stmt_get_images = $conn->prepare($get_images);
$stmt_get_images->execute();

$rows_returned = $stmt_get_images->rowCount();

if ($rows_returned == 0) {
} else {
    $images_array = $stmt_get_images->fetchAll(PDO::FETCH_ASSOC);
    $images_array_chunk = array_chunk($images_array, 4);
    // print_r($images_array_chunk);
    // die;
}

$page = "image_list";
include_once("header.php");

?>

<style>
    .card-img-top {
        width: 100%;
        height: 50vh;
        object-fit: cover;
    }
</style>

<div class="container-fluid">
    <?php
    foreach ($images_array_chunk as $row_images_array) {
        echo '<div class="row">';

        foreach ($row_images_array as $image_array) {
    ?>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="card">
                    <img class="card-img-top" src="./uploads/user_<?php echo $user."/".$image_array['image_name']; ?>" alt="Card image cap" class="img-fluid">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $image_array['image_title']; ?></h5>
                        <p class="card-text"><?php echo ($image_array['image_description']!="")?$image_array['image_description']:"NA"; ?></p>
                    </div>
                </div>
            </div>
    <?php
        }

        echo '</div><br><br>';
    }
    ?>
</div>

<?php

include_once("footer.php");

?>