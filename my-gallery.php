<?php

// Authorization check ............

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    die;
}

// ................................

error_reporting(0);
include_once './helpers/pagination.php';
$limit = 8;

$logged_in_user = $_SESSION['user_id'];

function httpGet($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $output = curl_exec($ch);

    curl_close($ch);

    return $output;
}

$exploded_query_string = explode('&', $_SERVER['QUERY_STRING']);

$query_params = array();

foreach ($exploded_query_string as $param_val_pair) {
    $query_params[explode('=', $param_val_pair)[0]] = explode('=', $param_val_pair)[1];
}

if (!array_key_exists('page', $query_params)) {
    $query_params['page'] = 1;
} else {
    if ($query_params['page'] == "")
        $query_params['page'] = 1;
}
if (!array_key_exists('sort_by', $query_params)) {
    $query_params['sort_by'] = "date";
} else {
    if ($query_params['sort_by'] == "")
        $query_params['sort_by'] = "date";
}
if (!array_key_exists('order', $query_params)) {
    $query_params['order'] = "asc";
} else {
    if ($query_params['order'] == "")
        $query_params['order'] = "asc";
}

$curl_request_query_string = "";

foreach ($query_params as $param => $param_value) {
    if ($param == "sort_by" || $param == "order" || $param == "page")
        $curl_request_query_string .= $param . "=" . $param_value . "&";
}

$curl_request_query_string .= "referrer=pvtlist&logged_in_user=" . $logged_in_user;

// echo $_SERVER['SERVER_NAME']."/Snaphub/fetch_images.php?" . $curl_request_query_string."\n";

$images_fetch_resp = json_decode(httpGet($_SERVER['SERVER_NAME'] . "/Snaphub/handlers/gallery-handler.php?" . $curl_request_query_string), true);
 
$images_array_chunk = $images_fetch_resp['data'];

if (array_key_exists('total_images', $images_fetch_resp))
    $total_records = $images_fetch_resp['total_images'];
else
    $total_records = 0;

if ($total_records != 0) {
    $pagConfig = array(
        'baseURL' => 'http://localhost/Snaphub/my-gallery.php',
        'totalRows' => $total_records,
        'perPage' => $limit
    );
    $pagination =  new Pagination($pagConfig);
}

$page = "my-gallery";
include_once("header.php");

?>

<style>
    .card-img-top {
        width: 100%;
        height: 50vh;
        object-fit: cover;
    }
</style>

<div class="container-fluid" style="padding-bottom:3em;">
    <h4 class="title">Filter options:</h4>
    <span>
        <hr></span>
    <div class="container">
        <form id="sort_form">
            <div class="row">
                <div class="form-group col-xs-12 col-sm-6">
                    <label for="sort">Sort by</label>
                    <select class="form-control" name="sort">
                        <option value="date">Date</option>
                        <option value="size">Size</option>
                        <option value="name">Name</option>
                    </select>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                    <label for="sort_order">Sort order</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="asc" checked>
                        <label class="form-check-label" for="inlineRadio1">Asc</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="desc">
                        <label class="form-check-label" for="inlineRadio2">Desc</label>
                    </div>
                </div>
                <div class="form-group col-xs-12">
                    <button class="btn btn-primary btn-block" type="submit">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="pvt_gal">
        <h1 class="text-center mb-3"><strong>Personal Image Gallery</strong></h1>
        <hr>
        <?php

        if (count($images_array_chunk) == 0) {

        ?>
            <h4 class="text-center">No images in private gallery! Create an account and add one ....</h4>
            <?php

        } else {
            foreach ($images_array_chunk as $row_images_array) {
                echo '<div class="row" style="margin-bottom:1.5em;">';

                foreach ($row_images_array as $image_array) {
            ?>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="card" style="margin-bottom: 0.5rem;">
                            <a data-fancybox="private-gallery" title="<?php echo $image_array['image_name']; ?>" href="./uploads/user_<?php echo $logged_in_user . "/" . $image_array['image_name']; ?>"><img class="card-img-top" src="./uploads/user_<?php echo $logged_in_user . "/" . $image_array['image_name']; ?>" alt="Card image cap" class="img-fluid"></a>
                            <div class="card-body">
                                <h5 class="card-title" style="margin-bottom:0.40em;font-size:1.35rem;"><?php echo $image_array['image_title']; ?></h5>
                                <p class="card-text" style="font-size: 1rem;font-family: cursive;"><?php echo ($image_array['image_description'] != "") ? $image_array['image_description'] : "NA"; ?></p>
                            </div>
                        </div>
                    </div>
        <?php
                }

                echo '</div>';
            }
        }
        ?>
    </div>

    <div class="pagination_cont" style="padding-bottom: 2em;text-align:center;">
        <div class="inner_div" style="display: inline-block;margin: auto;">
            <?php
            if ($total_records != 0) {
                echo $pagination->createLinks();
            }
            ?>
        </div>
    </div>

</div>

<?php

include_once("footer.php");

?>
<script>
    $(document).ready(function() {
        $('[data-fancybox="private-gallery"]').fancybox({
            animationDuration: 100
        });
    })

    var parameters = "",
        par_dat = {};
    parameters = window.location.search;
    if (parameters != "") {
        var temp = parameters.split("?")[1];
        var y = temp.split("&");
        for (var i = 0; i < y.length; i++) {
            par_dat[y[i].split("=")[0]] = y[i].split("=")[1];
        }
    }

    if (par_dat.hasOwnProperty('sort_by') && par_dat['sort_by'] != "") {
        $('[name=sort]').val(par_dat['sort_by']);
    } else {
        $('[name=sort]').val("date");
    }

    if (par_dat.hasOwnProperty('order') && par_dat['order'] != "") {
        if (par_dat['order'] == "asc") {
            $('#inlineRadio1').prop('checked', true);
        } else {
            $('#inlineRadio2').prop('checked', true);
        }
    } else {
        $('#inlineRadio1').prop('checked', true);
    }

    $('#sort_form').submit((e) => {
        var formElement = "#sort_form";
        e.preventDefault();
        
        var sort_by = e.currentTarget.sort.value;
        var sort_order = e.currentTarget.inlineRadioOptions.value;
        var redirect_url = "";

        if (sort_by == "date" && sort_order == "asc") {
            redirect_url = "./my-gallery.php";

            if (par_dat.hasOwnProperty('page') && par_dat['page'] != "") {
                redirect_url = redirect_url + '?page=' + par_dat['page'];
            }

        } else {
            redirect_url = "./my-gallery.php?sort_by=" + sort_by + "&order=" + sort_order;

            if (par_dat.hasOwnProperty('page') && par_dat['page'] != "") {
                redirect_url = redirect_url + '&page=' + par_dat['page'];
            }
        }

        window.location = redirect_url;

    });
</script>