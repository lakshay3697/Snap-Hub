<?php

$page = "sign_up";
include_once("header.php");


?>
<!-- <div id="page-container"> -->
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
                <label for="password2">Password</label>
                <input type="password" class="form-control" name="password2" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
<!-- </div> -->


<?php

include_once("footer.php");

?>

<script>
    $('#sign_up_form').submit((e) => {
        var formElement = "#sign_up_form";
        // var formStep = 1;
        e.preventDefault();
        var name = e.currentTarget.name.value;
        var email = e.currentTarget.email.value;
        var password = e.currentTarget.password.value;
        var confirm_password = e.currentTarget.password2.value;
        // console.log(name);
        // console.log(email);
        // console.log(password);
        // console.log(confirm_password);
        // debugger;


        $(formElement).find('button[type="submit"]').attr('disabled', 'disabled');
        // $(formElement).find('.modal-body').addClass('loading');
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: './form_handler.php',
            // url: '../popUp_Handler_SignUp_updated.php',
            data: {
                type: 'signup',
                // file_check : 0,
                form_data: {
                    // source: 'reg_form',
                    name: name,
                    email: email,
                    password: password,
                    password2: confirm_password,
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
                    $(formElement).find('button[type="submit"]').removeAttr('disabled');
                    // $(formElement).find('.modal-body').removeClass('loading');
                    toastr.error(data.message, "Registration Error!");
                } else {
                    toastr.success(data.message, "Registration Successful!");
                    window.location = './login.php';
                }
                // console.log(data);
                // debugger;

            },
            error: function(res) {
                // signModalSlide(1);
                // $(formElement).find('button[type="submit"]').removeAttr('disabled');
                // $(formElement).find('.modal-body').removeClass('loading');
            }
        });

        // return false;
    });
</script>