<?php
/**
*
* File: food/view_tag.php
* Version 1.0.0
* Date 23:07 09.07.2017
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_food.php");

/*- Get extention ---------------------------------------------------------------------- */
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);



if(isset($_GET['tag'])){
	$tag = $_GET['tag'];
	$tag = output_html($tag);
}
else{
	$tag = "";
}
$tag_mysql = quote_smart($link, $tag);


if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}
// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

// Dates
$year = date("Y");
$month = date("m");
$week = date("W");


// Find tag
$l = output_html($l);
$l_mysql = quote_smart($link, $l);

$query = "SELECT tag_id, tag_language, tag_title, tag_title_clean, tag_number_of_food, tag_last_clicked_year, tag_last_clicked_month, tag_last_clicked_week, tag_unique_views_counter, tag_unique_views_ip_block FROM $t_food_tags_unique WHERE tag_language=$l_mysql AND tag_title_clean=$tag_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_tag_id, $get_current_tag_language, $get_current_tag_title, $get_current_tag_title_clean, $get_current_tag_number_of_food, $get_current_tag_last_clicked_year, $get_current_tag_last_clicked_month, $get_current_tag_last_clicked_week, $get_current_tag_unique_views_counter, $get_current_tag_unique_views_ip_block) = $row;
if($get_current_tag_id == ""){
	// Tag not found
	// look for food that used this tag, if exists then insert the tag as a new tag

	$query = "SELECT count(tag_id), tag_title FROM $t_food_index_tags WHERE tag_language=$l_mysql AND tag_title_clean=$tag_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_number_of_food, $get_tag_title) = $row;
	if($get_count_number_of_food == "0"){
		echo"
		<p>Tag not found</p>
		";
		die;
	}
	else{
		$inp_title_mysql = quote_smart($link, $get_tag_title);
		mysqli_query($link, "INSERT INTO $t_food_tags_unique
				(tag_id, tag_language, tag_title, tag_title_clean, tag_number_of_food, 
				tag_last_clicked_year, tag_last_clicked_month, tag_last_clicked_week, tag_unique_views_counter, tag_unique_views_ip_block) 
				VALUES 
				(NULL, $l_mysql, $inp_title_mysql, $tag_mysql, $get_count_number_of_food, $year, $month, $week, 0, '')")
				or die(mysqli_error($link));

		
		// Get ID
		$query = "SELECT tag_id, tag_language, tag_title, tag_title_clean, tag_number_of_food, tag_last_clicked_year, tag_last_clicked_month, tag_last_clicked_week, tag_unique_views_counter, tag_unique_views_ip_block FROM $t_food_tags_unique WHERE tag_language=$l_mysql AND tag_title_clean=$tag_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_tag_id, $get_current_tag_language, $get_current_tag_title, $get_current_tag_title_clean, $get_current_tag_number_of_food, $get_current_tag_last_clicked_year, $get_current_tag_last_clicked_month, $get_current_tag_last_clicked_week, $get_current_tag_unique_views_counter, $get_current_tag_unique_views_ip_block) = $row;

	}

}

// Update number of hits
$update_unique_hits = 1; // guess
$ip_block_array = explode("\n", $get_current_tag_unique_views_ip_block);
$ip_block_array_size = sizeof($ip_block_array);
$inp_ip_block = "";
for($x=0;$x<$ip_block_array_size;$x++){
	if($my_ip == "$ip_block_array[$x]"){
		$update_unique_hits = 0;
		break;
	}
	
	if($inp_ip_block == ""){
		$inp_ip_block = "$ip_block_array[$x]";
	}
	else{
		if($x < 10){
			$inp_ip_block = $inp_ip_block . "\n" . "$ip_block_array[$x]";
		}
	}

}
if($update_unique_hits == "1"){
	$inp_unique_views_counter = $get_current_tag_unique_views_counter+1;
	$inp_ip_block = $my_ip . "\n" . $inp_ip_block;
	$inp_ip_block_mysql = quote_smart($link, $inp_ip_block);
	mysqli_query($link, "UPDATE $t_food_tags_unique  SET tag_last_clicked_year=$year, tag_last_clicked_month=$month, tag_last_clicked_week=$week, tag_unique_views_counter=$inp_unique_views_counter, tag_unique_views_ip_block=$inp_ip_block_mysql WHERE tag_id=$get_current_tag_id") or die(mysqli_error($link));
}



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "#$tag - $get_current_title_value";
include("$root/_webdesign/header.php");



echo"
<!-- Search -->
	<div style=\"float: right;padding-top: 12px;\">
		<form method=\"post\" action=\"search.php\" enctype=\"multipart/form-data\">
		<p>
		<input type=\"text\" name=\"q\" value=\"\" size=\"10\" id=\"nettport_inp_search_query\" />
		<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" class=\"btn_default\" />
		</p>
	</div>
<!-- //Search -->

<!-- Headline and language -->
	<h1>#$tag</h1>
<!-- //Headline and language -->


<!-- Where am I? -->
	<p><b>$l_you_are_here:</b><br />
	<a href=\"index.php?l=$l\">$get_current_title_value</a>
	&gt;
	<a href=\"view_tag.php?tag=$tag&amp;l=$l\">#$tag</a>
	</p>
<!-- //Where am I ? -->


<!-- Food Quick menu -->
	<div class=\"clear\"></div>
	<p>
	<a href=\"$root/food/index.php?l=$l\" class=\"btn_default\">$l_home</a>
	<a href=\"$root/food/my_food.php?l=$l\" class=\"btn_default\">$l_my_food</a>
	<a href=\"$root/food/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
	<a href=\"$root/food/new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>
	</p>
<!-- //Food Quick menu -->


<!-- Sorting -->
	<div>
			<script>
			\$(function(){
				\$('#inp_order_by_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
				\$('#inp_order_method_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
			});
			</script>
		
        		<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
			<p>
			<select name=\"inp_order_by\" id=\"inp_order_by_select\">
				<option value=\"view_tag.php?tag=$tag&amp;l=$l\">- $l_order_by -</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_score&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_score"){ echo" selected=\"selected\""; } echo">$l_score</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_id&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_id"){ echo" selected=\"selected\""; } echo">$l_date</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_name&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_name"){ echo" selected=\"selected\""; } echo">$l_name</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_manufacturer_name_and_food_name&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_manufacturer_name_and_food_name" OR $order_by == ""){ echo" selected=\"selected\""; } echo">$l_manufacturer_and_name</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_unique_hits&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_unique_hits"){ echo" selected=\"selected\""; } echo">$l_unique_hits</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_energy_metric&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_energy_metric"){ echo" selected=\"selected\""; } echo">$l_calories</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_fat_metric&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_fat_metric"){ echo" selected=\"selected\""; } echo">$l_fat</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_carbohydrates_metric&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_carbohydrates_metric"){ echo" selected=\"selected\""; } echo">$l_carbs</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=food_proteins_metric&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_proteins_metric"){ echo" selected=\"selected\""; } echo">$l_proteins</option>
			</select>
			<select name=\"inp_order_method\" id=\"inp_order_method_select\">";
				if($order_by == ""){
					$order_by = "food_manufacturer_name_and_food_name";
				}
				echo"
				<option value=\"view_tag.php?tag=$tag&amp;order_by=$order_by&amp;order_method=asc&amp;l=$l\""; if($order_method == "asc" OR $order_method == ""){ echo" selected=\"selected\""; } echo">$l_asc</option>
				<option value=\"view_tag.php?tag=$tag&amp;order_by=$order_by&amp;order_method=desc&amp;l=$l\""; if($order_method == "desc"){ echo" selected=\"selected\""; } echo">$l_desc</option>
			</select>
			</p>
        		</form>
	</div>
<!-- //Sorting -->

<!-- Tags -->
	<p>
	";
	$query_t = "SELECT tag_id, tag_language, tag_title, tag_title_clean FROM $t_food_tags_unique WHERE tag_language=$l_mysql ORDER BY tag_unique_views_counter ASC";
	$result_t = mysqli_query($link, $query_t);
	while($row_t = mysqli_fetch_row($result_t)) {
		list($get_tag_id, $get_tag_language, $get_tag_title, $get_tag_title_clean) = $row_t;
		echo"
		<a href=\"view_tag.php?tag=$get_tag_title_clean&amp;l=$l\" class=\"btn_default\""; if($tag == "$get_tag_title_clean"){ echo" style=\"font-weight: bold;\""; } echo">$get_tag_title</a>
		";
	}
	echo"
	</p>
<!-- //Tags -->
";




// Get food with that tag
$x = 0;
$query = "SELECT $t_food_index_tags.tag_id, $t_food_index.food_id, $t_food_index.food_user_id, $t_food_index.food_name, $t_food_index.food_clean_name, $t_food_index.food_manufacturer_name, $t_food_index.food_manufacturer_name_and_food_name, $t_food_index.food_description, $t_food_index.food_country, $t_food_index.food_net_content_metric, $t_food_index.food_net_content_measurement_metric, $t_food_index.food_net_content_us, $t_food_index.food_net_content_measurement_us, $t_food_index.food_net_content_added_measurement, $t_food_index.food_serving_size_metric, $t_food_index.food_serving_size_measurement_metric, $t_food_index.food_serving_size_us, $t_food_index.food_serving_size_measurement_us, $t_food_index.food_serving_size_added_measurement, $t_food_index.food_serving_size_pcs, $t_food_index.food_serving_size_pcs_measurement, $t_food_index.food_energy_metric, $t_food_index.food_fat_metric, $t_food_index.food_saturated_fat_metric, $t_food_index.food_monounsaturated_fat_metric, $t_food_index.food_polyunsaturated_fat_metric, $t_food_index.food_cholesterol_metric, $t_food_index.food_carbohydrates_metric, $t_food_index.food_carbohydrates_of_which_sugars_metric, $t_food_index.food_dietary_fiber_metric, $t_food_index.food_proteins_metric, $t_food_index.food_salt_metric, $t_food_index.food_sodium_metric, $t_food_index.food_energy_us, $t_food_index.food_fat_us, $t_food_index.food_saturated_fat_us, $t_food_index.food_monounsaturated_fat_us, $t_food_index.food_polyunsaturated_fat_us, $t_food_index.food_cholesterol_us, $t_food_index.food_carbohydrates_us, $t_food_index.food_carbohydrates_of_which_sugars_us, $t_food_index.food_dietary_fiber_us, $t_food_index.food_proteins_us, $t_food_index.food_salt_us, $t_food_index.food_sodium_us, $t_food_index.food_score, $t_food_index.food_energy_calculated_metric, $t_food_index.food_fat_calculated_metric, $t_food_index.food_saturated_fat_calculated_metric, $t_food_index.food_monounsaturated_fat_calculated_metric, $t_food_index.food_polyunsaturated_fat_calculated_metric, $t_food_index.food_cholesterol_calculated_metric, $t_food_index.food_carbohydrates_calculated_metric, $t_food_index.food_carbohydrates_of_which_sugars_calculated_metric, $t_food_index.food_dietary_fiber_calculated_metric, $t_food_index.food_proteins_calculated_metric, $t_food_index.food_salt_calculated_metric, $t_food_index.food_sodium_calculated_metric, $t_food_index.food_energy_calculated_us, $t_food_index.food_fat_calculated_us, $t_food_index.food_saturated_fat_calculated_us, $t_food_index.food_monounsaturated_fat_calculated_us, $t_food_index.food_polyunsaturated_fat_calculated_us, $t_food_index.food_cholesterol_calculated_us, $t_food_index.food_carbohydrates_calculated_us, $t_food_index.food_carbohydrates_of_which_sugars_calculated_us, $t_food_index.food_dietary_fiber_calculated_us, $t_food_index.food_proteins_calculated_us, $t_food_index.food_salt_calculated_us, $t_food_index.food_sodium_calculated_us, $t_food_index.food_barcode, $t_food_index.food_main_category_id, $t_food_index.food_sub_category_id, $t_food_index.food_image_path, $t_food_index.food_image_a, $t_food_index.food_thumb_a_small, $t_food_index.food_thumb_a_medium, $t_food_index.food_thumb_a_large FROM $t_food_index_tags INNER JOIN $t_food_index ON $t_food_index_tags.tag_food_id=$t_food_index.food_id WHERE $t_food_index_tags.tag_title_clean=$tag_mysql AND $t_food_index_tags.tag_language=$l_mysql";
// Order
if($order_by != ""){
	if($order_method == "desc"){
		$order_method_mysql = "DESC";
	}
	else{
		$order_method_mysql = "ASC";
	}

	if($order_by == "food_score" OR $order_by == "food_id" OR $order_by == "food_manufacturer_name_and_food_name" OR $order_by == "food_name" OR $order_by == "food_unique_hits" 
	OR $order_by == "food_energy_metric" OR $order_by == "food_proteins_metric" OR $order_by == "food_carbohydrates_metric" OR $order_by == "food_fat_metric"){
		$order_by_mysql = "$t_food_index.$order_by";
	}
	else{
		$order_by_mysql = "$t_food_index.food_id";
	}
	$query = $query . " ORDER BY $order_by_mysql $order_method_mysql";
}
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_tag_id, $get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large) = $row;
		
	if(file_exists("$root/$get_food_image_path/$get_food_image_a")){
	
		// Name saying
		$title = "$get_food_manufacturer_name $get_food_name";
		$check = strlen($title);
		if($check > 35){
			$title = substr($title, 0, 35);
			$title = $title . "...";
		}


		if($x == 0){
			echo"
			<div class=\"clear\"></div>
			<div class=\"left_center_center_right_left\" style=\"text-align: center;padding-bottom: 20px;\">
			";
		}
		elseif($x == 1){
			echo"
			<div class=\"left_center_center_left_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
			";
		}
		elseif($x == 2){
			echo"
			<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
			";
		}
		elseif($x == 3){
			echo"
			<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
			";
		}


		if($get_food_score > 0){
			echo"
			<img src=\"_gfx/smiley_sad.png\" alt=\"smiley_sad.gif\" title=\"$get_food_score\" class=\"food_score_img\" />";
		}
		elseif($get_food_score < 0){
			echo"
			<img src=\"_gfx/smiley_smile.png\" alt=\"smiley_smile.png\" title=\"$get_food_score\" class=\"food_score_img\" />";
		}
		else{
			echo"
			<img src=\"_gfx/smiley_confused.png\" alt=\"smiley_confused.png\" title=\"$get_food_score\" class=\"food_score_img\" />";
		}

		echo"
		<p style=\"padding-bottom:5px;\">
		";

	
		
		echo"<a href=\"view_food.php?main_category_id=&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"../$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_thumb_a_small\" style=\"margin-bottom: 5px;\" /></a><br />
		<a href=\"view_food.php?main_category_id=&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
		";
		echo"
		</p>

		<table style=\"margin: 0px auto;\">
		 <tr>
		  <td style=\"padding-right: 10px;text-align: center;\">
			<span class=\"grey_smal\">$get_food_energy_metric</span>
		  </td>
		  <td style=\"padding-right: 10px;text-align: center;\">
			<span class=\"grey_smal\">$get_food_fat_metric</span>
		  </td>
		  <td style=\"padding-right: 10px;text-align: center;\">
			<span class=\"grey_smal\">$get_food_carbohydrates_metric</span>
		  </td>
		  <td style=\"text-align: center;\">
			<span class=\"grey_smal\">$get_food_proteins_metric</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding-right: 10px;text-align: center;\">
			<span class=\"grey_smal\">$l_cal_lowercase</span>
		  </td>
		  <td style=\"padding-right: 10px;text-align: center;\">
			<span class=\"grey_smal\">$l_fat_lowercase</span>
		  </td>
		  <td style=\"padding-right: 10px;text-align: center;\">
			<span class=\"grey_smal\">$l_carb_lowercase</span>
		  </td>
		  <td style=\"text-align: center;\">
			<span class=\"grey_smal\">$l_proteins_lowercase</span>
		  </td>
		 </tr>
		</table>
		</div>
		";
	
		// Increment
		$x++;

		// Reset
		if($x == 4){
			$x = 0;
		}
	} // img
}
if($x == "1"){
	echo"
			<div class=\"left_center_center_left_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
		</div>
		
			<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
		</div>
		<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
		</div>
		<div class=\"clear\"></div>
	";
}
elseif($x == "2"){
	echo"
		<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
		</div>
		<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
		</div>
		<div class=\"clear\"></div>
	";
}
elseif($x == "3"){
	echo"
		<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
		</div>
		<div class=\"clear\"></div>
	";
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>