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
        // console.log(email);
        // console.log(pass);
        // debugger;

        if (email && pass) {
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: './form_handler.php',
                data: {
                    type: 'login',
                    // file_check : 0,
                    form_data: {
                        // source: 'reg_form',
                        // name: name,
                        email: email,
                        password: pass,
                        // password2: confirm_password,
                        // location: location,
                        // experience: experience,
                        // salary: salary,
                        // education: education,
                        // curr_employer: curr_employer,
                        // add_details: add_details
                        // paramet: par_dat
                    }
                },
                success: function(data) {

                    if (data.STATUS == 'error') {
                        toastr.error(data.message,"Login Error!");
                    } else {
                        toastr.success(data.message,"Login Successful!");
                        // sessionStorage.setItem('status', 'loggedIn')
                        // window.location = '../receptix_new/index.php';
                        window.location = './my-gallery.php';

                    }

                },
                error: function(res) {}
            });
        }
        return false;
    });
</script>