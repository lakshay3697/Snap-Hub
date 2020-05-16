<?php

// echo '<pre>';
error_reporting(0);
// echo "Server query string is :- \n";
// echo $_SERVER['QUERY_STRING']."\n";

function httpGet($url)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	//  curl_setopt($ch,CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	$output = curl_exec($ch);

	curl_close($ch);

	return $output;
}

$exploded_query_string = explode('&',$_SERVER['QUERY_STRING']);

// print_r($exploded_query_string);

$query_params = array();

foreach($exploded_query_string as $param_val_pair)
{
  $query_params[explode('=',$param_val_pair)[0]] = explode('=',$param_val_pair)[1];
}

// print_r($query_params); 

if(!array_key_exists('page',$query_params))
{
    $query_params['page'] = 1;
}

$curl_request_query_string = "";

foreach($query_params as $param => $param_value)
{
    if($param=="sort_by"||$param=="order"||$param=="page")
        $curl_request_query_string .= $param."=".$param_value."&";
}

$curl_request_query_string .= "referrer=publist";

// echo $_SERVER['SERVER_NAME']."/Pics_Gallore/fetch_images.php?" . $curl_request_query_string."\n"; die;

$images_fetch_resp = json_decode(httpGet($_SERVER['SERVER_NAME']."/Pics_Gallore/fetch_images.php?" . $curl_request_query_string), true);

$images_array_chunk = $images_fetch_resp['data'];


$page = "image_list";
include_once("header.php");

?>

<style>
    .card-img-top {
        width: 100%;
        height: 50vh;
        object-fit: cover;
    }
</style>

<div class="container-fluid">
    <div class="container">
        <form id="sort_form">
            <div class="row">
                <div class="form-group col-xs-12 col-sm-6">
                    <label for="sort">Sort by</label>
                    <select class="form-control" id="sort">
                        <option value="date">Date</option>
                        <option value="size">Size</option>
                        <option value="name">Name</option>
                    </select>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                    <label for="sort_order">Sort order</label><br>
                    <!-- <input type="checkbox" id="sort_order" required> -->
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Ascending" checked>
                        <label class="form-check-label" for="inlineRadio1">Asc</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="Descending">
                        <label class="form-check-label" for="inlineRadio2">Desc</label>
                    </div>
                </div>
                <div class="form-group col-xs-12">
                    <button class="btn btn-primary btn-block" type="submit">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <?php
    foreach ($images_array_chunk as $row_images_array) {
        echo '<div class="row">';

        foreach ($row_images_array as $image_array) {
    ?>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="card">
                    <img class="card-img-top" src="./uploads/user_<?php echo $image_array['user_id'] . "/" . $image_array['image_name']; ?>" alt="Card image cap" class="img-fluid">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $image_array['image_title']; ?></h5>
                        <p class="card-text"><?php echo ($image_array['image_description'] != "") ? $image_array['image_description'] : "NA"; ?></p>
                    </div>
                </div>
            </div>
    <?php
        }

        echo '</div><br><br>';
    }
    ?>
</div>

<?php

include_once("footer.php");

?>
<script>
    $(function() {
        $('#sort_order').bootstrapToggle({
            on: 'Descending',
            off: 'Ascending'
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

    // console.log(par_dat);

    $('#sort_form').submit((e) => {
        var formElement = "#sort_form";
        e.preventDefault();
        // console.log("I am here now!");
        // console.log(par_dat);
        // debugger;
        var sort_by = e.currentTarget.sort.value;
        var sort_order = e.currentTarget.inlineRadioOptions.value;
        var redirect_url = "";

        if (sort_by == "date" && sort_order == "Ascending") {
            redirect_url = "./image_list.php";

            if(par_dat.hasOwnProperty('page')&&par_dat['page']!="")
            {
                redirect_url = redirect_url + '?page=' + par_dat['page'];
            }

        } else {
            redirect_url = "./image_list.php?sort_by=" + sort_by + "&order=" + sort_order;

            if(par_dat.hasOwnProperty('page')&&par_dat['page']!="")
            {
                redirect_url = redirect_url + '&page=' + par_dat['page'];
            }
        }

        // console.log(redirect_url);
        // debugger;

        window.location = redirect_url;

    });
</script>