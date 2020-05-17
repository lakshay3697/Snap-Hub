<?php

$page = "login";
include_once("header.php");


?>

<div class="container">
    <form id="login_form">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<?php

include_once("footer.php");

?>

<script>
    $('#login_form').submit((e) => {
        var formElement = "#login_form";
        e.preventDefault();
        var email = e.currentTarget.email.value;
        var pass = e.currentTarget.password.value;

        if (email && pass) {
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: './handlers/account-handler.php',
                data: {
                    type: 'login',
                    form_data: {
                        email: email,
                        password: pass
                    }
                },
                success: function(data) {

                    if (data.STATUS == 'error') {
                        toastr.error(data.message,"Login Error!");
                    } else {
                        toastr.success(data.message,"Login Successful!");
                        
                        setTimeout(function(){
                            window.location = './my-gallery.php';
                        },3000);

                    }

                },
                error: function(res) {}
            });
        }
        return false;
    });
</script>