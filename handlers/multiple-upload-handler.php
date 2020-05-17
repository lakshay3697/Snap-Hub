<?php

require "../con-pdo.php";

session_start();

if ($_POST['type'] == "validate_image") {

    $error_flag = 0;
    // Validation for image name ............................

    $uploaded_image_input_name = trim(strtolower(($_POST['image_name'])));

    if ($uploaded_image_input_name == "") {
        // Error (Image name can't be empty)
        $error_flag = 1;
        echo json_encode(array("STATUS" => "error", "message" => "Input image name can't be empty!"));
        die;
    } else {
        if (strlen($uploaded_image_input_name) > 15 || strlen($uploaded_image_input_name) < 3) {
            // Error (Input image name needs to between 3-15 characters)
            $error_flag = 1;
            echo json_encode(array("STATUS" => "error", "message" => "Input image name needs to be between 3-15 characters!"));
            die;
        } else {

            if (!preg_match('/^[a-zA-Z0-9-_ ]+$/', $uploaded_image_input_name)) {
                // Error (Character not allowed in file name found)
                $error_flag = 1;
                echo json_encode(array("STATUS" => "error", "message" => "Not a valid character for input image's name..., Only a-z,A-Z,0-9,-_ are allowed!"));
                die;
            }
        }
    }

    // ...........................................................................

    // Validation for Image title .................................................

    $uploaded_image_title = trim(ucfirst(strtolower((strip_tags($_POST['image_title'])))));

    if ($uploaded_image_title == "") {
        $error_flag = 1;
        echo json_encode(array("STATUS" => "error", "message" => "Input image title can't be empty!"));
        die;
    } else {
        if (strlen($uploaded_image_title) > 50 || strlen($uploaded_image_title) < 4) {
            // Error (Input image title needs to between 4-50 characters)
            $error_flag = 1;
            echo json_encode(array("STATUS" => "error", "message" => "Input image title needs to be between 4-50 characters!"));
            die;
        } else {
            if (!preg_match("/^[a-zA-Z0-9. ]*$/", $uploaded_image_title)) {
                // Error (Character not allowed in image title)
                $error_flag = 1;
                echo json_encode(array("STATUS" => "error", "message" => "Input image title can have only letters, spaces, digits and dots!"));
                die;
            }
        }
    }

    // ...............................................................................

    $uploaded_image_description = trim(strip_tags($_POST['image_description']));

    // Uploaded file validation .......................................................


    $uploaded_image_file_name =  $_FILES['uploaded_image']['name'];
    $uploaded_image_file_size =  $_FILES['uploaded_image']['size']; // Size of uploaded image in bytes
    $uploaded_image_file_tmp  =  $_FILES['uploaded_image']['tmp_name'];
    $uploaded_image_file_type =  $_FILES['uploaded_image']['type'];

    $exploded_uploaded_file_name   =  explode('.', $uploaded_image_file_name);

    $uploaded_file_ext = strtolower(end($exploded_uploaded_file_name));

    $allowed_extensions = array("png", "jpg", "jpeg");

    if ($uploaded_image_file_name != "" && $error_flag != 1) {
        if (in_array($uploaded_file_ext, $allowed_extensions) === false) {
            // Error (Not a valid file extension)
            $error_flag = 1;
            echo json_encode(array("STATUS" => "error", "message" => "Only jpg, jpeg & png images are allowed!"));
            die;
        } elseif ($uploaded_image_file_size > 2097152) {
            // Error (Uploaded file is bigger than 2 MB)
            $error_flag = 1;
            echo json_encode(array("STATUS" => "error", "message" => "Uploaded file's size is greater than 2 MB!"));
            die;
        } else {
            echo json_encode(array("STATUS" => "success", "message" => "Uploaded file is valid!"));
            die;
        }
    }

    //.................................................................................
}

if ($_POST['type'] == "multi_file_upload") {

    // Since the files and data coming from multiple file uploader is valid (Validated in step 1 so now i will simply upload files and then make insertions)

    $files_array = $_FILES;

    $files_meta_array = json_decode($_POST['files_meta'],True);

    if (!file_exists('../uploads/user_'.$_SESSION['user_id'])) // If directory doesn't exist's for the logged in user then make one 
        mkdir('../uploads/user_'.$_SESSION['user_id']);

    $files_failed = 0;

    foreach ($files_array as $file_key => $file) 
    {
        $uploaded_image_file_name =  $file['name'];
        $uploaded_image_file_size =  $file['size']; // Size of uploaded image in bytes
        $uploaded_image_file_tmp  =  $file['tmp_name'];
        $uploaded_image_file_type =  $file['type'];

        $exploded_uploaded_file_name   =  explode('.', $uploaded_image_file_name);

        $uploaded_file_ext = strtolower(end($exploded_uploaded_file_name));

        if($uploaded_image_file_name!="")
        {
            $newfilename = $files_meta_array[$file_key]['image_name']. "_" . $_SESSION['user_id'] . "_" . uniqid("",TRUE) . "." . $uploaded_file_ext;

            if (move_uploaded_file($uploaded_image_file_tmp, '../uploads/user_'. $_SESSION['user_id'] . '/' . $newfilename))
            {
                // File uploaded .... So making an entry in user_images table 

                $image_upload_sql = "INSERT INTO user_images(user_id,image_title,image_name,image_description,image_size) VALUES (?,?,?,?,?)";
                $stmt_image_upload_sql = $conn->prepare($image_upload_sql);
                if ($stmt_image_upload_sql->execute(array($_SESSION['user_id'],$files_meta_array[$file_key]['image_title'],$newfilename,$files_meta_array[$file_key]['image_description'],$uploaded_image_file_size))) 
                {
                    
                }
                else
                {
                    unlink('../uploads/user_'. $_SESSION['user_id'] . '/' . $newfilename);
                    $files_failed++;
                }
            }
            else
            {
                $files_failed++;
            }
        }
    }

    if($files_failed==0)
    {
        echo json_encode(array("STATUS" => "success", "message" => "All the files uploaded successfully!"));
        die;
    }
    else
    {
        echo json_encode(array("STATUS" => "error", "message" => "Partial files uploaded! Files failed - ".$files_failed));
        die;
    }

}
