<?php

// Authorization check ............

session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ./login.php");
  die;
}

// ................................

$page = "add_multiple_images";
include_once("header.php");

?>

<div class="container" style="padding-bottom:5em;">
  <h3 class="text-center"><strong>Multiple Image Uploader</strong></h3>
  <hr>
  <div class="form-container mb-2">
    <form id="validate_image_form">
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
      <button type="submit" class="btn btn-primary">Validate File</button>
    </form>
  </div>
  <hr>
  <h4 class="text-center">Uploaded Files Preview</h4>
  <hr>
  <div id="file_preview_div" class="mt-2 mb-3">

  </div>
  <button id="multi_file_upload" type="button" class="btn btn-primary">Upload All</button>
</div>

<?php

include_once("footer.php");

?>

<script>
  var uploaded_valid_files = [];

  $('#validate_image_form').submit((e) => {
    var formElement = "#validate_image_form";

    e.preventDefault();
    var image_name = e.currentTarget.img_name.value;
    var image_title = e.currentTarget.img_title.value;
    var image_description = e.currentTarget.img_description.value;
    var uploaded_image = e.currentTarget.img_upload.files[0];

    var formData = new FormData();
    formData.append('uploaded_image', uploaded_image);
    formData.append('type', 'validate_image');
    formData.append('image_name', image_name);
    formData.append('image_title', image_title);
    formData.append('image_description', image_description);

    $(formElement).find('button[type="submit"]').attr('disabled', 'disabled');

    $.ajax({
      type: 'POST',
      dataType: 'JSON',
      url: './handlers/multiple-upload-handler.php',
      data: formData,
      contentType: false,
      processData: false,
      cache: false,
      success: function(data) {

        if (data.STATUS == 'error') {
          $(formElement).find('button[type="submit"]').removeAttr('disabled');
          toastr.error(data.message, "Validate Image Module Error!");
        } else {
          toastr.success(data.message, "Validate Image Module Success!");
          $('#validate_image_form').trigger("reset");
          $(formElement).find('button[type="submit"]').removeAttr('disabled');

          setTimeout(function() {
            var file_array = [];
            file_array['file'] = uploaded_image;
            var file_meta = {};
            file_meta['image_name'] = image_name;
            file_meta['image_title'] = image_title;
            file_meta['image_description'] = image_description;
            file_array['file_meta'] = file_meta;

            uploaded_valid_files.push(file_array);

            create_filePreview_list(uploaded_valid_files);
          }, 3000);
        }

      },
      error: function(res) {}
    });

    return false;
  });

  function create_filePreview_list(uploaded_files_array) {

    if (document.getElementById("file_preview_list")) {
      document.getElementById("file_preview_list").remove();
    }

    var file_list = uploaded_files_array;

    var list = '';

    file_list.map(function(file, index) {
      list += '<li class="list-group-item mb-2">' + file["file"]["name"] + '<span style="float:right;"><button class="remove btn btn-danger" id="' + index + '">Remove</button></span></li>';
    });

    var child = document.createElement('ul');
    child.className = "list-group";
    child.id = "file_preview_list";
    child.innerHTML = list;

    document.getElementById('file_preview_div').appendChild(child);

  }

  $(document).on('click', '.remove', function() {

    var indexRemove = $(this).attr('id'); // $(this) refers to button that was clicked

    uploaded_valid_files.splice(indexRemove, 1);

    create_filePreview_list(uploaded_valid_files);
  });

  $(document).on('click', '#multi_file_upload', function() {

    if (uploaded_valid_files.length > 0) {
      var formData = new FormData();

      var file_meta_json = {};

      uploaded_valid_files.forEach(function(file_info, index) {
        var key = 'file_' + index;
        formData.append(key, file_info['file']);
        file_meta_json[key] = file_info['file_meta'];
      });

      formData.append("files_meta", JSON.stringify(file_meta_json));
      formData.append("type", "multi_file_upload");

      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: './handlers/multiple-upload-handler.php',
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        success: function(data) {

          if (data.STATUS == 'error') {
            toastr.error(data.message, "Multiple Images Upload Module Error!");
          } else {
            toastr.success(data.message, "Multiple Images Upload Module Success!");
            if (document.getElementById("file_preview_list")) {
              document.getElementById("file_preview_list").remove();
            }
          }

        },
        error: function(res) {}
      });

      return false;
    }

  });
</script>