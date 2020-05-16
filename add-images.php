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

    var formData = new FormData();
    formData.append('uploaded_image', uploaded_image);
    formData.append('type', 'add_images');
    formData.append('image_name', image_name);
    formData.append('image_title', image_title);
    formData.append('image_description', image_description);

    $(formElement).find('button[type="submit"]').attr('disabled', 'disabled');
    
    $.ajax({
    type: 'POST',
    dataType: 'JSON',
    url: './add_images_handler.php',
    data: formData,
    contentType: false,
    processData: false,
    cache: false,
    success: function(data) {

        if (data.STATUS == 'error') {
        $(formElement).find('button[type="submit"]').removeAttr('disabled');
        toastr.error(data.message,"Add Images Module Error!");
        } else {
        toastr.success(data.message,"Add Images Module Success!");
        $('#add_image_form').trigger("reset");
        $(formElement).find('button[type="submit"]').removeAttr('disabled');
        }

    },
    error: function(res) {
    }
    });

    return false;
  });
</script>