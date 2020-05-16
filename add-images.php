<?php

// Authorization check ............

session_start();
if(!isset($_SESSION['user_id'])) {
  header("Location: ./login.php");
  die;
}

// ................................

$page = "index";
include_once("header.php");

?>

<div class="container">
    <form id="add_image_form">
        <div class="form-group">
            <label for="name">Image Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="img_name" placeholder="Enter image name" required>
        </div>
        <div class="form-group">
            <label for="title">Image Title<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="img_title" placeholder="Enter image title" required>
        </div>
        <div class="form-group">
            <label for="description">Image Description</label>
            <textarea class="form-control" name="img_description" rows="3" placeholder="Provide a small description/caption for your image ..."></textarea>
        </div>
        <div class="form-group">
            <label for="file">Upload Image<span class="text-danger">*</span></label>
            <input type="file" class="form-control-file" name="img_upload" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php

include_once("footer.php");

?>

<script>
  $('#add_image_form').submit((e) => {
    var formElement = "#add_image_form";
    
    e.preventDefault();
    var image_name = e.currentTarget.img_name.value;
    var image_title = e.currentTarget.img_title.value;
    var image_description = e.currentTarget.img_description.value;
    var uploaded_image = e.currentTarget.img_upload.files[0];
    // console.log(image_name);
    // console.log(image_title);
    // console.log(image_description);
    // console.log(uploaded_image);
    // debugger;

    // var file_data = e.currentTarget.resume.files[0];
    var formData = new FormData();
    formData.append('uploaded_image', uploaded_image);
    formData.append('type', 'add_images');
    // formData.append('source', 'reg_form');
    // formData.append('file_check', 1);
    formData.append('image_name', image_name);
    formData.append('image_title', image_title);
    formData.append('image_description', image_description);
    // formData.append('job_preferences', job_preference);
    // formData.append('location', location);
    // formData.append('experience', experience);
    // formData.append('salary', salary);
    // formData.append('education', education);
    // formData.append('curr_employer', curr_employer);
    // formData.append('add_details', add_details);
    // // formData.append('opt_pop_screen_no', formStep);

    $(formElement).find('button[type="submit"]').attr('disabled', 'disabled');
    // $(formElement).find('.modal-body').addClass('loading');
    $.ajax({
    type: 'POST',
    dataType: 'JSON',
    url: './add_images_handler.php',
    // url: '../popUp_Handler_SignUp_updated.php',
    data: formData,
    contentType: false,
    processData: false,
    cache: false,
    success: function(data) {

        if (data.STATUS == 'error') {
        // signModalSlide(1);
        $(formElement).find('button[type="submit"]').removeAttr('disabled');
        // $(formElement).find('.modal-body').removeClass('loading');
        toastr.error(data.message,"Add Images Module Error!");
        } else {
        // sessionStorage.setItem('status', 'loggedIn')
        toastr.success(data.message,"Add Images Module Success!");
        $('#add_image_form').trigger("reset");
        }

    },
    error: function(res) {
        // signModalSlide(3);
        // $(formElement).find('button[type="submit"]').removeAttr('disabled');
        // $(formElement).find('.modal-body').removeClass('loading');
    }
    });

    // return false;
  });
</script>