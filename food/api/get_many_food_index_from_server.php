<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables -------------------------------------------------------------------- */
$t_food_categories		  = $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  = $mysqlPrefixSav . "food_categories_translations";
$t_food_index			  = $mysqlPrefixSav . "food_index";
$t_food_index_stores		  = $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		  = $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  = $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  = $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		  = $mysqlPrefixSav . "food_index_contents";
$t_food_stores		  	  = $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  = $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  = $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  = $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations = $mysqlPrefixSav . "food_measurements_translations";
$t_users			  =  $mysqlPrefixSav . "users";
/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
}
else{
	$l = "";
}
if(isset($_GET['start'])) {
	$start = $_GET['start'];
	$start = strip_tags(stripslashes($start));
}
else{
	$start = "";
}
if(isset($_GET['stop'])) {
	$stop = $_GET['stop'];
	$stop = strip_tags(stripslashes($stop));
}
else{
	$stop = "";
}


/*- Get recipe ------------------------------------------------------------------------- */

// Build array
$rows_array = array();


// Select
$x =0;

$l_mysql = quote_smart($link, $l);
$start_mysql = quote_smart($link, $start);
$stop_mysql   = quote_smart($link, $stop);


if($l == ""){
	$q = "SELECT food_id FROM $t_food_index WHERE food_id BETWEEN $start_mysql AND $stop_mysql";
}
else{
	$q = "SELECT food_id FROM $t_food_index WHERE food_id BETWEEN $start_mysql AND $stop_mysql AND food_language=$l_mysql";
}
$r = mysqli_query($link, $q);
while($rows = mysqli_fetch_row($r)) {
	list($get_food_id) = $rows;


	$food_array = array();


	// Food index
	$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content, food_net_content_measurement, food_serving_size_gram, food_serving_size_gram_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy, food_fat, food_fat_of_which_saturated_fatty_acids, food_carbohydrates, food_carbohydrates_of_which_dietary_fiber, food_carbohydrates_of_which_sugars, food_proteins, food_salt, food_score, food_energy_calculated, food_fat_calculated, food_fat_of_which_saturated_fatty_acids_calculated, food_carbohydrates_calculated, food_carbohydrates_of_which_dietary_fiber_calculated, food_carbohydrates_of_which_sugars_calculated, food_proteins_calculated, food_salt_calculated, food_barcode, food_category_id, food_image_path, food_thumb_small, food_thumb_medium, food_thumb_large, food_image_a, food_image_b, food_image_c, food_image_d, food_image_e, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_date, food_time, food_last_viewed FROM $t_food_index WHERE food_id=$get_food_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$food_array['index'] = $row;



	// food_ads
	$query = "SELECT ad_id, ad_food_language, ad_food_id, ad_text, ad_url, ad_food_created_datetime, ad_food_created_by_user_id, ad_food_updated_datetime, ad_food_updated_by_user_id, ad_food_unique_clicks FROM $t_food_index_ads WHERE ad_food_id=$get_food_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$food_array['index_ads'] = $row;


	// food index stores
	$food_array['index_stores'] = array();
	$query = "SELECT food_store_id, food_store_food_id, food_store_store_id, food_store_store_name, food_store_store_logo, food_store_store_price, food_store_store_currency, food_store_user_id, food_store_user_ip, food_store_updated FROM $t_food_index_stores WHERE food_store_food_id=$get_food_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($food_array['index_stores'],$row);
	}

	
	// food prices 
	$food_array['index_prices'] = array();
	$query = "SELECT food_price_id, food_price_food_id, food_price_store_id, food_price_store_name, food_price_price, food_price_currency, food_price_offer, food_price_offer_valid_from, food_price_offer_valid_to, food_price_user_id, food_price_user_ip, food_price_added_datetime, food_price_added_datetime_print, food_price_updated, food_price_updated_print, food_price_reported, food_price_reported_checked FROM $t_food_index_prices WHERE food_price_food_id=$get_food_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($food_array['index_prices'],$row);
	}

	// food tags
	$food_array['index_tags'] = array();
	$query = "SELECT tag_id, tag_language, tag_food_id, tag_title, tag_title_clean, tag_user_id FROM $t_food_index_tags WHERE tag_food_id=$get_food_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($food_array['index_tags'],$row);
	}


	array_push($rows_array,$food_array);

	$x++;
}

if($x > 0){
	// Json everything
	$rows_json = json_encode(utf8ize($rows_array));

	echo"$rows_json";
}
else{
	// the recipe was not found
	// Are there more records?

	$query = "SELECT food_id FROM $t_food_index WHERE food_id > $to_mysql ORDER BY food_id ASC LIMIT 1"; 
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_food_id) = $row;

	if($get_food_id != ""){
		echo"Food not found.Please look for next food";
	}
	else{
		echo"Food not found.No more food";
	}
}





?>