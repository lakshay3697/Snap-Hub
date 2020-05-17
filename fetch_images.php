<?php

require "con_pdo.php";

session_start();

$limit = 8;

if($_REQUEST['referrer']=='publist')
{

    $pn = $_REQUEST['page'];

    $start_from = ($pn - 1) * $limit;

    $get_total_images = "SELECT count(*) as total_images FROM user_images";

    $stmt_get_image_total = $conn->prepare($get_total_images);
    $stmt_get_image_total->execute();

    $total_images = $stmt_get_image_total->fetchAll(PDO::FETCH_ASSOC)[0]['total_images'];


    $get_images = "SELECT users.name,user_images.user_id,user_images.image_title,user_images.image_name,user_images.image_description FROM user_images INNER JOIN users ON user_images.user_id = users.id";

    if($_REQUEST['sort_by']!="")
    {
        if($_REQUEST['sort_by']=="date")
        {
            $order_by_column_name = "user_images.uploaded_at";
        }
        elseif($_REQUEST['sort_by']=="size")
        {
            $order_by_column_name = "user_images.image_size";
        }
        elseif($_REQUEST['sort_by']=="name")
        {
            $order_by_column_name = "user_images.image_name";
        }
        
        $sort_order = ($_REQUEST['order']=="asc")?"ASC":"DESC";

        $get_images .= " ORDER BY ".$order_by_column_name." ".$sort_order;
    }

    $get_images .= " LIMIT $start_from, $limit";

    $stmt_get_images = $conn->prepare($get_images);
    $stmt_get_images->execute();

    $rows_returned = $stmt_get_images->rowCount();

    if ($rows_returned == 0) 
    {
        echo json_encode(array("STATUS"=>"error","data"=>[]));
        die;
    } 
    else {
        $images_array = $stmt_get_images->fetchAll(PDO::FETCH_ASSOC);
        $images_array_chunk = array_chunk($images_array, 4);

        echo json_encode(array("STATUS"=>"success","data"=>$images_array_chunk,"total_images"=>$total_images));
        die;
    }
}
elseif($_REQUEST['referrer']=='pvtlist')
{
    
    $pn = $_REQUEST['page'];

    $start_from = ($pn - 1) * $limit;

    $logged_in_user = $_REQUEST['logged_in_user'];

    $get_total_images = "SELECT count(*) as total_images FROM user_images WHERE user_id = $logged_in_user";

    $stmt_get_image_total = $conn->prepare($get_total_images);
    $stmt_get_image_total->execute();

    $total_images = $stmt_get_image_total->fetchAll(PDO::FETCH_ASSOC)[0]['total_images'];

    $get_images = "SELECT user_images.image_title,user_images.image_name,user_images.image_description FROM user_images WHERE user_images.user_id = $logged_in_user";

    if($_REQUEST['sort_by']!="")
    {
        if($_REQUEST['sort_by']=="date")
        {
            $order_by_column_name = "user_images.uploaded_at";
        }
        elseif($_REQUEST['sort_by']=="size")
        {
            $order_by_column_name = "user_images.image_size";
        }
        elseif($_REQUEST['sort_by']=="name")
        {
            $order_by_column_name = "user_images.image_name";
        }
        
        $sort_order = ($_REQUEST['order']=="asc")?"ASC":"DESC";

        $get_images .= " ORDER BY ".$order_by_column_name." ".$sort_order;
    }

    $get_images .= " LIMIT $start_from, $limit";

    $stmt_get_images = $conn->prepare($get_images);
    $stmt_get_images->execute();

    $rows_returned = $stmt_get_images->rowCount();

    if ($rows_returned == 0) 
    {
        echo json_encode(array("STATUS"=>"error","data"=>[]));
        die;
    } 
    else {
    
        $images_array = $stmt_get_images->fetchAll(PDO::FETCH_ASSOC);
        
        $images_array_chunk = array_chunk($images_array, 4);

        echo json_encode(array("STATUS"=>"success","data"=>$images_array_chunk,"total_images"=>$total_images));
        die;
    }
}

?>