<?php

require "../con-pdo.php";

session_start();

if ($_POST['type'] == "signup") {

    $error_flag = 0;

    // Validation for name field ....................................................
    $name = ucwords(strtolower(strip_tags($_POST['form_data']['name'])));

    if (trim($name) == "") {
        // Error (Name can't be empty)
        $error_flag=1;
        echo json_encode(array("STATUS"=>"error","message"=>"Name can't be empty!"));
        die;
    } else {

        if (strlen($name) > 25 || strlen($name) < 4) {
            // Error (Name should be between 4 and 25 characters)
            $error_flag=1;
            echo json_encode(array("STATUS"=>"error","message"=>"Name should be between 4 and 25 characters!"));
            die;
        } else {
            // check if name only contains letters, dot and whitespace
            if (!preg_match("/^[a-zA-Z. ]*$/", $name)) {
                // Error (Special Characters in name)
                $error_flag=1;
                echo json_encode(array("STATUS"=>"error","message"=>"Name can have only letters, dot and whitespace!"));
                die;
            }
        }
    }
    // ................................................................................

    // Email validation ...............................................................
    $email = strtolower(strip_tags($_POST['form_data']['email']));

    // Remove all illegal characters from email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Error (Email is invalid)
        $error_flag=1;
        echo json_encode(array("STATUS"=>"error","message"=>"Email is in an invalid format!"));
        die;
    }

    // .................................................................................

    // Password Validation .............................................................

    $pass1 = $_POST['form_data']['password'];
    $pass2 = $_POST['form_data']['password2'];

    if ($pass1 != $pass2) {
        // Error (Passwords don't match)
        $error_flag=1;
        echo json_encode(array("STATUS"=>"error","message"=>"Passwords don't match!"));
        die;
    } else {
        if (strlen($pass1) > 30 || strlen($pass1) < 5) {
            // Error (Password should be only 5 to 30 characters long)
            $error_flag=1;
            echo json_encode(array("STATUS"=>"error","message"=>"Passwords should be only 5 to 30 characters long!"));
            die;
        }
    }

    // ..........................................................................................................

    if (!$error_flag) {
        $user_exists_check = "SELECT id FROM users WHERE email = ?";
        $stmt_existence = $conn->prepare($user_exists_check);
        $stmt_existence->execute(array($email));

        $rows_returned = $stmt_existence->rowCount();
        
        if($rows_returned == 0)
        {
            $pass = $pass1;

            $password = password_hash($pass, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users(name,email,password) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            
            if($stmt->execute(array($name, $email, $password)))
            {
                // Success (User registered)
                echo json_encode(array("STATUS"=>"success","message"=>"Thanks for registering!"));
                die;
            }
            else
            {
                // Error (Error while inserting)
                $error_flag=1;
                echo json_encode(array("STATUS"=>"error","message"=>"Error while inserting!"));
                die;
            }

        }
        else
        {
            // Error (User already exists)
            $error_flag=1;
            echo json_encode(array("STATUS"=>"error","message"=>"User already exists!"));
            die;
        }
    }
} 
elseif ($_POST['type'] == "login") 
{
    
    $email = filter_var(strtolower(strip_tags($_POST['form_data']['email'])), FILTER_SANITIZE_EMAIL);
    $pass = $_POST['form_data']['password'];

    $user_exists = "SELECT * FROM users WHERE email = ?";

    $stmt_exists = $conn->prepare($user_exists);
    $stmt_exists->execute(array($email));

    $rows_returned = $stmt_exists->rowCount();

    if($rows_returned==0)
    {
        // Error (User does not exists)
        echo json_encode(array("STATUS"=>"error","message"=>"User does not exists!"));
        die;
    }
    else
    {
        $res = $stmt_exists->fetchAll(PDO::FETCH_ASSOC);

        $password_query = $res[0]['password'];

        if(password_verify($pass, $password_query))
        {
            $_SESSION['user_name'] = $res[0]['name'];	
            $_SESSION['user_email'] = $res[0]['email'];
            $_SESSION['user_id'] = $res[0]['id'];

            echo json_encode(array("STATUS"=>"success","message"=>"You are now logged in!"));
            die;
        }
        else
        {
            // Error (Password incorrect )
            echo json_encode(array("STATUS"=>"error","message"=>"Entered password is incorrect!"));
            die;
        }
        
    }

}
