<?php

require "con_pdo.php";

session_start();

if($_POST['type']=="add_images")
{
    
    $error_flag=0;
    // Validation for image name ............................

    $uploaded_image_input_name = trim(strtolower(($_POST['image_name'])));

    if($uploaded_image_input_name=="")
    {
        // Error (Image name can't be empty)
        $error_flag=1;
        echo json_encode(array("STATUS"=>"error","message"=>"Input image name can't be empty!"));
        die;
    }
    else
    {
        if(strlen($uploaded_image_input_name)>15||strlen($uploaded_image_input_name)<3)
        {
            // Error (Input image name needs to between 3-15 characters)
            $error_flag=1;
            echo json_encode(array("STATUS"=>"error","message"=>"Input image name needs to be between 3-15 characters!"));
            die;
        }
        else
        {
            
            if(!preg_match('/^[a-zA-Z0-9-_ ]+$/', $uploaded_image_input_name))
            {
                // Error (Character not allowed in file name found)
                $error_flag=1;
                echo json_encode(array("STATUS"=>"error","message"=>"Not a valid character for input image's name!"));
                die;
            }
            
        }
    }

    // ...........................................................................

    // Validation for Image title .................................................

    $uploaded_image_title = trim(ucfirst(strtolower((strip_tags($_POST['image_title'])))));

    if($uploaded_image_title=="")
    {
        $error_flag=1;
        echo json_encode(array("STATUS"=>"error","message"=>"Input image title can't be empty!"));
        die;
    }
    else
    {
        if(strlen($uploaded_image_title)>50||strlen($uploaded_image_title)<4)
        {
            // Error (Input image title needs to between 4-50 characters)
            $error_flag=1;
            echo json_encode(array("STATUS"=>"error","message"=>"Input image title needs to be between 4-50 characters!"));
            die;
        }
        else
        {
            if(!preg_match("/^[a-zA-Z. ]*$/", $uploaded_image_title))
            {
                // Error (Character not allowed in image title)
                $error_flag=1;
                echo json_encode(array("STATUS"=>"error","message"=>"Input image title can have only letters, spaces and dots!"));
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

    if($uploaded_image_file_name!=""&&$error_flag!=1)
    {
        if(in_array($uploaded_file_ext, $allowed_extensions) === false)
        {
            // Error (Not a valid file extension)
            $error_flag=1;
            echo json_encode(array("STATUS"=>"error","message"=>"Only jpg, jpeg & png images are allowed!"));
            die;
        }
        elseif($uploaded_image_file_size > 2097152)
        {
            // Error (Uploaded file is bigger than 2 MB)
            $error_flag=1;
            echo json_encode(array("STATUS"=>"error","message"=>"Uploaded file's size is greater than 2 MB!"));
            die;
        }
        else
        {
            // Rename file as per the name provided by user (New file name needs to be unique to get away with the issue of image with similar name as that of already uploaded  overriding it)
            $newfilename = $uploaded_image_input_name. "_" . $_SESSION['user_id'] . "_" . uniqid("",TRUE) . "." . $uploaded_file_ext;

            if (!file_exists('./uploads/user_'.$_SESSION['user_id']))
                mkdir('./uploads/user_'.$_SESSION['user_id']);

            if (move_uploaded_file($uploaded_image_file_tmp, './uploads/user_'. $_SESSION['user_id'] . '/' . $newfilename))
            {
                // File uploaded .... So making an entry in user_images table 

                $image_upload_sql = "INSERT INTO user_images(user_id,image_title,image_name,image_description,image_size) VALUES (?,?,?,?,?)";
                $stmt_image_upload_sql = $conn->prepare($image_upload_sql);
                if ($stmt_image_upload_sql->execute(array($_SESSION['user_id'],$uploaded_image_title,$newfilename,$uploaded_image_description,$uploaded_image_file_size))) 
                {
                    // Success (File uploaded successfully alongwith insertion of data in db)
                    $error_flag=1;
                    echo json_encode(array("STATUS"=>"success","message"=>"File uploaded and data inserted successfully!"));
                    die;
                }
                else
                {
                    // Error (Couldn't insert)
                    $error_flag=1;
                    echo json_encode(array("STATUS"=>"error","message"=>"File uploaded but insertion failed!"));
                    die;
                }
            }
            else
            {
                // Error couldn't upload file 
                $error_flag=1;
                echo json_encode(array("STATUS"=>"error","message"=>"File can't be uploaded!"));
                die;
            }
        }
    }

    //.................................................................................
}

?>