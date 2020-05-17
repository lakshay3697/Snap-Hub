<?php

$page = "sign-up";
include_once("header.php");


?>
<div class="container" style="padding-bottom:3em;">
    <form id="sign_up_form">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
        </div>
        <div class="form-group">
            <label for="password2">Confirm Password</label>
            <input type="password" class="form-control" name="password2" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<?php

include_once("footer.php");

?>

<script>
    $('#sign_up_form').submit((e) => {
        var formElement = "#sign_up_form";
        e.preventDefault();
        var name = e.currentTarget.name.value;
        var email = e.currentTarget.email.value;
        var password = e.currentTarget.password.value;
        var confirm_password = e.currentTarget.password2.value;

        $(formElement).find('button[type="submit"]').attr('disabled', 'disabled');
        
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: './handlers/account-handler.php',
            data: {
                type: 'signup',
                form_data: {
                    name: name,
                    email: email,
                    password: password,
                    password2: confirm_password
                }
            },
            success: function(data) {

                if (data.STATUS == 'error') {
                    $(formElement).find('button[type="submit"]').removeAttr('disabled');
                    toastr.error(data.message, "Registration Error!");
                } else {
                    toastr.success(data.message, "Registration Successful!");
                    
                    setTimeout(function(){
                        window.location = './login.php';
                    },3000);
                }

            },
            error: function(res) {
            }
        });

        return false;
    });
</script>