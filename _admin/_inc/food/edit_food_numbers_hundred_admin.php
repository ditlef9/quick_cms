<?php
/**
*
* File: _admin/_inc/food/edit_food_numbers.php
* Version 14:53 20.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
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
$t_food_countries_used 		  = $mysqlPrefixSav . "food_countries_used";



/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['language'])){
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
}
else{
	$language = "en";
}
if(isset($_GET['main_category_id'])){
	$main_category_id= $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])){
	$sub_category_id= $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}


/*- Settings ---------------------------------------------------------------------------- */
$settings_image_width = "847";
$settings_image_height = "847";

/*- Languages -------------------------------------------------------------------------- */
include("_translations/site/$l/food/ts_edit_food.php");
include("_translations/site/$l/food/ts_edit_food_numbers_hundred.php");
include("_translations/site/$l/food/ts_view_food.php");

// Get variables
$food_id = $_GET['food_id'];
$food_id = strip_tags(stripslashes($food_id));
$food_id_mysql = quote_smart($link, $food_id);
$editor_language_mysql = quote_smart($link, $editor_language);

// Select food
$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us_system, food_net_content_measurement_us_system, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us_system, food_serving_size_measurement_us_system, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_fat_of_which_saturated_fatty_acids_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us_system, food_fat_us_system, food_fat_of_which_saturated_fatty_acids_us_system, food_monounsaturated_fat_us_system, food_polyunsaturated_fat_us_system, food_cholesterol_us_system, food_carbohydrates_us_system, food_carbohydrates_of_which_sugars_us_system, food_dietary_fiber_us_system, food_proteins_us_system, food_salt_us_system, food_sodium_us_system, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_fat_of_which_saturated_fatty_acids_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us_system, food_fat_calculated_us_system, food_fat_of_which_saturated_fatty_acids_calculated_us_system, food_monounsaturated_fat_calculated_us_system, food_polyunsaturated_fat_calculated_us_system, food_carbohydrates_calculated_us_system, food_carbohydrates_of_which_sugars_calculated_us_system, food_dietary_fiber_calculated_us_system, food_proteins_calculated_us_system, food_salt_calculated_us_system, food_sodium_calculated_us_system, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$food_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_food_id, $get_current_food_user_id, $get_current_food_name, $get_current_food_clean_name, $get_current_food_manufacturer_name, $get_current_food_manufacturer_name_and_food_name, $get_current_food_description, $get_current_food_country, $get_current_food_net_content_metric, $get_current_food_net_content_measurement_metric, $get_current_food_net_content_us_system, $get_current_food_net_content_measurement_us_system, $get_current_food_net_content_added_measurement, $get_current_food_serving_size_metric, $get_current_food_serving_size_measurement_metric, $get_current_food_serving_size_us_system, $get_current_food_serving_size_measurement_us_system, $get_current_food_serving_size_added_measurement, $get_current_food_serving_size_pcs, $get_current_food_serving_size_pcs_measurement, $get_current_food_energy_metric, $get_current_food_fat_metric, $get_current_food_fat_of_which_saturated_fatty_acids_metric, $get_current_food_monounsaturated_fat_metric, $get_current_food_polyunsaturated_fat_metric, $get_current_food_cholesterol_metric, $get_current_food_carbohydrates_metric, $get_current_food_carbohydrates_of_which_sugars_metric, $get_current_food_dietary_fiber_metric, $get_current_food_proteins_metric, $get_current_food_salt_metric, $get_current_food_sodium_metric, $get_current_food_energy_us_system, $get_current_food_fat_us_system, $get_current_food_fat_of_which_saturated_fatty_acids_us_system, $get_current_food_monounsaturated_fat_us_system, $get_current_food_polyunsaturated_fat_us_system, $get_current_food_cholesterol_us_system, $get_current_food_carbohydrates_us_system, $get_current_food_carbohydrates_of_which_sugars_us_system, $get_current_food_dietary_fiber_us_system, $get_current_food_proteins_us_system, $get_current_food_salt_us_system, $get_current_food_sodium_us_system, $get_current_food_score, $get_current_food_energy_calculated_metric, $get_current_food_fat_calculated_metric, $get_current_food_fat_of_which_saturated_fatty_acids_calculated_metric, $get_current_food_monounsaturated_fat_calculated_metric, $get_current_food_polyunsaturated_fat_calculated_metric, $get_current_food_carbohydrates_calculated_metric, $get_current_food_carbohydrates_of_which_sugars_calculated_metric, $get_current_food_dietary_fiber_calculated_metric, $get_current_food_proteins_calculated_metric, $get_current_food_salt_calculated_metric, $get_current_food_sodium_calculated_metric, $get_current_food_energy_calculated_us_system, $get_current_food_fat_calculated_us_system, $get_current_food_fat_of_which_saturated_fatty_acids_calculated_us_system, $get_current_food_monounsaturated_fat_calculated_us_system, $get_current_food_polyunsaturated_fat_calculated_us_system, $get_current_food_carbohydrates_calculated_us_system, $get_current_food_carbohydrates_of_which_sugars_calculated_us_system, $get_current_food_dietary_fiber_calculated_us_system, $get_current_food_proteins_calculated_us_system, $get_current_food_salt_calculated_us_system, $get_current_food_sodium_calculated_us_system, $get_current_food_barcode, $get_current_food_main_category_id, $get_current_food_sub_category_id, $get_current_food_image_path, $get_current_food_image_a, $get_current_food_thumb_a_small, $get_current_food_thumb_a_medium, $get_current_food_thumb_a_large, $get_current_food_image_b, $get_current_food_thumb_b_small, $get_current_food_thumb_b_medium, $get_current_food_thumb_b_large, $get_current_food_image_c, $get_current_food_thumb_c_small, $get_current_food_thumb_c_medium, $get_current_food_thumb_c_large, $get_current_food_image_d, $get_current_food_thumb_d_small, $get_current_food_thumb_d_medium, $get_current_food_thumb_d_large, $get_current_food_image_e, $get_current_food_thumb_e_small, $get_current_food_thumb_e_medium, $get_current_food_thumb_e_large, $get_current_food_last_used, $get_current_food_language, $get_current_food_synchronized, $get_current_food_accepted_as_master, $get_current_food_notes, $get_current_food_unique_hits, $get_current_food_unique_hits_ip_block, $get_current_food_comments, $get_current_food_likes, $get_current_food_dislikes, $get_current_food_likes_ip_block, $get_current_food_user_ip, $get_current_food_created_date, $get_current_food_last_viewed, $get_current_food_age_restriction) = $row;

if($get_current_food_id == ""){
	echo"
	<h1>Food not found</h1>
	<p>
	Sorry, the food was not found.
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Back</a>
	</p>
	";
}
else{

	// Translation
	$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_food_main_category_id AND category_translation_language=$editor_language_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_main_category_translation_value) = $row_t;


	$query_t = "SELECT category_translation_id, category_id, category_translation_language, category_translation_value, category_translation_no_food, category_translation_last_updated, category_calories_min, category_calories_med, category_calories_max, category_fat_min, category_fat_med, category_fat_max, category_fat_of_which_saturated_fatty_acids_min, category_fat_of_which_saturated_fatty_acids_med, category_fat_of_which_saturated_fatty_acids_max, category_carb_min, category_carb_med, category_carb_max, category_carb_of_which_dietary_fiber_min, category_carb_of_which_dietary_fiber_med, category_carb_of_which_dietary_fiber_max, category_carb_of_which_sugars_min, category_carb_of_which_sugars_med, category_carb_of_which_sugars_max, category_proteins_min, category_proteins_med, category_proteins_max, category_salt_min, category_salt_med, category_salt_max FROM $t_food_categories_translations WHERE category_id=$get_current_food_sub_category_id AND category_translation_language=$editor_language_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_sub_category_translation_id, $get_current_sub_category_id, $get_current_sub_category_translation_language, $get_current_sub_category_translation_value, $get_current_sub_category_translation_no_food, $get_current_sub_category_translation_last_updated, $get_current_sub_category_calories_min, $get_current_sub_category_calories_med, $get_current_sub_category_calories_max, $get_current_sub_category_fat_min, $get_current_sub_category_fat_med, $get_current_sub_category_fat_max, $get_current_sub_category_fat_of_which_saturated_fatty_acids_min, $get_current_sub_category_fat_of_which_saturated_fatty_acids_med, $get_current_sub_category_fat_of_which_saturated_fatty_acids_max, $get_current_sub_category_carb_min, $get_current_sub_category_carb_med, $get_current_sub_category_carb_max, $get_current_sub_category_carb_of_which_dietary_fiber_min, $get_current_sub_category_carb_of_which_dietary_fiber_med, $get_current_sub_category_carb_of_which_dietary_fiber_max, $get_current_sub_category_carb_of_which_sugars_min, $get_current_sub_category_carb_of_which_sugars_med, $get_current_sub_category_carb_of_which_sugars_max, $get_current_sub_category_proteins_min, $get_current_sub_category_proteins_med, $get_current_sub_category_proteins_max, $get_current_sub_category_salt_min, $get_current_sub_category_salt_med, $get_current_sub_category_salt_max) = $row_t;

	// Author
	$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$get_current_food_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_food_author_user_id, $get_current_food_author_user_email, $get_current_food_author_user_name, $get_current_food_author_user_alias) = $row;


	// Process == 1
	if($process == "1"){
		// Dates
		$datetime = date("Y-m-d H:i:s");

				// per 100 
				$inp_food_energy = $_POST['inp_food_energy'];
				$inp_food_energy = output_html($inp_food_energy);
				$inp_food_energy = str_replace(",", ".", $inp_food_energy);
				$inp_food_energy_mysql = quote_smart($link, $inp_food_energy);
				if($inp_food_energy == ""){
					$ft = "error";
					$fm = "missing_energy";
				}

				$inp_food_fat = $_POST['inp_food_fat'];
				$inp_food_fat = output_html($inp_food_fat);
				$inp_food_fat = str_replace(",", ".", $inp_food_fat);
				$inp_food_fat_mysql = quote_smart($link, $inp_food_fat);
				if($inp_food_fat == ""){
					$ft = "error";
					$fm = "missing_fat";
				}

				$inp_food_fat_of_which_saturated_fatty_acids = $_POST['inp_food_fat_of_which_saturated_fatty_acids'];
				$inp_food_fat_of_which_saturated_fatty_acids = output_html($inp_food_fat_of_which_saturated_fatty_acids);
				$inp_food_fat_of_which_saturated_fatty_acids = str_replace(",", ".", $inp_food_fat_of_which_saturated_fatty_acids);
				$inp_food_fat_of_which_saturated_fatty_acids_mysql = quote_smart($link, $inp_food_fat_of_which_saturated_fatty_acids);
				if($inp_food_fat_of_which_saturated_fatty_acids == ""){
					$ft = "error";
					$fm = "missing_of_which_saturated_fatty_acid";
				}


				$inp_food_carbohydrates = $_POST['inp_food_carbohydrates'];
				$inp_food_carbohydrates = output_html($inp_food_carbohydrates);
				$inp_food_carbohydrates = str_replace(",", ".", $inp_food_carbohydrates);
				$inp_food_carbohydrates_mysql = quote_smart($link, $inp_food_carbohydrates);
				if($inp_food_carbohydrates == ""){
					$ft = "error";
					$fm = "missing_carbohydrates";
				}


				$inp_food_carbohydrates_of_which_sugars = $_POST['inp_food_carbohydrates_of_which_sugars'];
				$inp_food_carbohydrates_of_which_sugars = output_html($inp_food_carbohydrates_of_which_sugars);
				$inp_food_carbohydrates_of_which_sugars = str_replace(",", ".", $inp_food_carbohydrates_of_which_sugars);
				$inp_food_carbohydrates_of_which_sugars_mysql = quote_smart($link, $inp_food_carbohydrates_of_which_sugars);
				if($inp_food_carbohydrates_of_which_sugars == ""){
					$ft = "error";
					$fm = "missing_of_which_sugars";
				}




				$inp_food_proteins = $_POST['inp_food_proteins'];
				$inp_food_proteins = output_html($inp_food_proteins);
				$inp_food_proteins = str_replace(",", ".", $inp_food_proteins);
				$inp_food_proteins_mysql = quote_smart($link, $inp_food_proteins);
				if($inp_food_proteins == ""){
					$ft = "error";
					$fm = "missing_proteins";
				}

				$inp_food_salt = $_POST['inp_food_salt'];
				$inp_food_salt = output_html($inp_food_salt);
				$inp_food_salt = str_replace(",", ".", $inp_food_salt);
				$inp_food_salt_mysql = quote_smart($link, $inp_food_salt);
				if($inp_food_salt == ""){
					$ft = "error";
					$fm = "missing_salt";
				}

		// Calculated
		$inp_food_energy_calculated = round($inp_food_energy*$get_current_food_serving_size_gram/100, 0);
		$inp_food_fat_calculated = round($inp_food_fat*$get_current_food_serving_size_gram/100, 0);
		$inp_food_fat_of_which_saturated_fatty_acids_calculated = round($inp_food_fat_of_which_saturated_fatty_acids*$get_current_food_serving_size_gram/100, 0);
		$inp_food_carbohydrates_calculated = round($inp_food_carbohydrates*$get_current_food_serving_size_gram/100, 0);
		$inp_food_carbohydrates_of_which_sugars_calculated = round($inp_food_carbohydrates_of_which_sugars*$get_current_food_serving_size_gram/100, 0);
		$inp_food_proteins_calculated = round($inp_food_proteins*$get_current_food_serving_size_gram/100, 0);
		$inp_food_salt_calculated = round($inp_food_salt*$get_current_food_serving_size_gram/100, 0);

		// Score
		$inp_total = $inp_food_energy + $inp_food_fat + $inp_food_fat_of_which_saturated_fatty_acids + $inp_food_carbohydrates + $inp_food_carbohydrates_of_which_sugars + $inp_food_proteins + $inp_food_salt;
		$inp_calculation = ($inp_food_energy * 1) + 
				     ($inp_food_fat * 13) +  
				     ($inp_food_fat_of_which_saturated_fatty_acids * 1) + 
				     ($inp_food_carbohydrates * 44) +
				     ($inp_food_carbohydrates_of_which_sugars * 1) +
				     ($inp_food_proteins * 43) +
				     ($inp_food_salt * 1);
		$inp_score = round($inp_calculation / $inp_total, 0);
		$inp_score_mysql = quote_smart($link, $inp_score);


		$result = mysqli_query($link, "UPDATE $t_food_index SET food_energy=$inp_food_energy_mysql, 
food_fat=$inp_food_fat_mysql, 
food_fat_of_which_saturated_fatty_acids=$inp_food_fat_of_which_saturated_fatty_acids_mysql, 
food_carbohydrates=$inp_food_carbohydrates_mysql, 
food_carbohydrates_of_which_sugars=$inp_food_carbohydrates_of_which_sugars_mysql, 
food_proteins=$inp_food_proteins_mysql, 
food_salt=$inp_food_salt_mysql, 
food_score=$inp_score_mysql, 
food_energy_calculated='$inp_food_energy_calculated', 
food_fat_calculated='$inp_food_fat_calculated',
food_fat_of_which_saturated_fatty_acids_calculated='$inp_food_fat_of_which_saturated_fatty_acids_calculated',
food_carbohydrates_calculated='$inp_food_carbohydrates_calculated',
food_carbohydrates_of_which_sugars_calculated='$inp_food_carbohydrates_of_which_sugars_calculated',
food_proteins_calculated='$inp_food_proteins_calculated',
food_salt_calculated='$inp_food_salt_calculated'
 WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
	

		$url = "index.php?open=$open&page=edit_food_numbers_admin&main_category_id=$get_current_food_main_category_id&sub_category_id=$get_current_food_sub_category_id&food_id=$get_current_food_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	} // process == 1

	echo"
	<h1>$get_current_food_manufacturer_name $get_current_food_name</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Food</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=default&amp;action=open_main_category&amp;main_category_id=$get_current_food_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_translation_value</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=default&amp;action=open_sub_category&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_sub_category_translation_value</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_food_manufacturer_name $get_current_food_name</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Food Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">View</a>
				<li><a href=\"index.php?open=$open&amp;page=edit_food_general_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
				<li><a href=\"index.php?open=$open&amp;page=edit_food_numbers_hundred_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Numbers</a>
				<li><a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Images</a>
				<li><a href=\"index.php?open=$open&amp;page=delete_food_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</ul>
		</div>
		<div class=\"clear\"></div>
		<div style=\"height: 10px;\"></div>
	<!-- //Food Menu -->

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_food_energy\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- Edit food general -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=edit_food_numbers_hundred_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" id=\"my_form\">
		
		<p>
		<a href=\"index.php?open=$open&amp;page=edit_food_numbers_hundred_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;\">Per 100</a>
		&middot;
		<a href=\"index.php?open=$open&amp;page=edit_food_numbers_pcs_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Per pcs</a>
		</p>
		";
		if($get_current_food_serving_size_metric == "" OR $get_current_food_serving_size_metric == "0"){
			echo"<div class=\"warning\"><p>Missing serving size!</p></div>\n";
		}
		echo"

			<table class=\"hor-zebra\" style=\"width: 350px\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
			   </th>
			   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
				<span>$l_per_100</span>
			   </th>
			   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
				<span>$l_serving<br />$get_current_food_serving_size_metric $get_current_food_serving_size_measurement_metric ($get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement)</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			  <tr>
			   <td style=\"padding: 8px 4px 6px 8px;\">
				<span>$l_calories</span>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span><input type=\"text\" name=\"inp_food_energy\" value=\"$get_current_food_energy_metric\" size=\"3\" /></span>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span class=\"food_energy_calculated\">$get_current_food_energy_calculated_metric</span>

				<!-- On change energy calculate -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_energy\"]').on(\"change paste keyup\", function() {
						var energy_hundred = $('[name=\"inp_food_energy\"]').val();
						energy_hundred = energy_hundred.replace(\",\", \".\");
						energy_calculated = Math.round((energy_hundred*$get_current_food_serving_size_metric)/100);
						\$(\".food_energy_calculated\").text(energy_calculated);
					});
				});
				</script>

				<!-- On change energy calculate -->
			   </td>
			  </tr>
			  <tr>
			   <td style=\"padding: 8px 4px 6px 8px;\">
				<p style=\"margin:0;padding: 0px 0px 4px 0px;\">$l_fat:</p>
				<p style=\"margin:0;padding: 0;\">$l_dash_of_which_saturated_fatty_acids</p>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_food_fat\" value=\"$get_current_food_fat_metric\" size=\"3\" /><br /></p>
				<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_fat_of_which_saturated_fatty_acids\" value=\"$get_current_food_fat_of_which_saturated_fatty_acids_metric\" size=\"3\" /></p>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><span class=\"food_fat_calculated\">$get_current_food_fat_calculated_metric</span><br />
				<span class=\"food_fat_of_which_saturated_fatty_acids_calculated\">$get_current_food_fat_of_which_saturated_fatty_acids_calculated_metric</span></p>

				<!-- On change fat calculate -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_fat\"]').on(\"change paste keyup\", function() {
						var fat_hundred = $('[name=\"inp_food_fat\"]').val();
						fat_hundred = fat_hundred.replace(\",\", \".\");
						fat_calculated = Math.round((fat_hundred*$get_current_food_serving_size_metric)/100);
						\$(\".food_fat_calculated\").text(fat_calculated);
					});
					\$('[name=\"inp_food_fat_of_which_saturated_fatty_acids\"]').on(\"change paste keyup\", function() {
						var food_fat_of_which_saturated_fatty_acids_hundred = $('[name=\"inp_food_fat_of_which_saturated_fatty_acids\"]').val();
						food_fat_of_which_saturated_fatty_acids_hundred = food_fat_of_which_saturated_fatty_acids_hundred.replace(\",\", \".\");
						food_fat_of_which_saturated_fatty_acids_calculated = Math.round((food_fat_of_which_saturated_fatty_acids_hundred*$get_current_food_serving_size_metric)/100);
						\$(\".food_fat_of_which_saturated_fatty_acids_calculated\").text(food_fat_of_which_saturated_fatty_acids_calculated);
					});
				});
				</script>
				<!-- On change fat calculate -->
			   </td>
			 </tr>
			 <tr>
		 	  <td style=\"padding: 8px 4px 6px 8px;\">
				<p style=\"margin:0;padding: 0px 0px 4px 0px;\">$l_carbs:</p>
				<p style=\"margin:0;padding: 0;\">$l_dash_of_which_sugars</p>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_food_carbohydrates\" value=\"$get_current_food_carbohydrates_metric\" size=\"3\" /></p>
				<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_carbohydrates_of_which_sugars\" value=\"$get_current_food_carbohydrates_of_which_sugars_metric\" size=\"3\" /></p>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><span class=\"food_carbohydrates_calculated\">$get_current_food_carbohydrates_calculated_metric</span><br />
				<span class=\"food_carbohydrates_of_which_sugars_calculated\">$get_current_food_carbohydrates_of_which_sugars_calculated_metric</span></p>

				<!-- On change fat calculate -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_carbohydrates\"]').on(\"change paste keyup\", function() {
						var food_carbohydrates_hundred = $('[name=\"inp_food_carbohydrates\"]').val();
						food_carbohydrates_hundred = food_carbohydrates_hundred.replace(\",\", \".\");
						food_carbohydrates_calculated = Math.round((food_carbohydrates_hundred*$get_current_food_serving_size_metric)/100);
						\$(\".food_carbohydrates_calculated\").text(food_carbohydrates_calculated);
					});
					\$('[name=\"inp_food_carbohydrates_of_which_sugars\"]').on(\"change paste keyup\", function() {
						var food_carbohydrates_of_which_sugars_hundred = $('[name=\"inp_food_carbohydrates_of_which_sugars\"]').val();
						food_carbohydrates_of_which_sugars_hundred = food_carbohydrates_of_which_sugars_hundred.replace(\",\", \".\");
						food_carbohydrates_of_which_sugars_calculated = Math.round((food_carbohydrates_of_which_sugars_hundred*$get_current_food_serving_size_metric)/100);
						\$(\".food_carbohydrates_of_which_sugars_calculated\").text(food_carbohydrates_of_which_sugars_calculated);
					});
				});
				</script>
				<!-- On change fat calculate -->
			   </td>
			  </tr>
			 <tr>
		 	  <td style=\"padding: 8px 4px 6px 8px;\">
				<p style=\"margin:0;padding: 0;\">$l_dietary_fiber:</p>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<p style=\"margin:0px 0px 4px 0px;padding: 0;\"><input type=\"text\" name=\"inp_food_dietary_fiber\" value=\"$get_current_food_dietary_fiber_metric\" size=\"3\" /></p>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span class=\"food_dietary_fiber_calculated\">$get_current_food_dietary_fiber_calculated_metric</span>

				<!-- On change dietary fiber calculate -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_dietary_fiber\"]').on(\"change paste keyup\", function() {
						var food_dietary_fiber_hundred = $('[name=\"inp_food_dietary_fiber\"]').val();
						food_dietary_fiber_hundred = food_dietary_fiber_hundred.replace(\",\", \".\");
						food_dietary_fiber_calculated = Math.round((food_dietary_fiber_hundred*$get_current_food_serving_size_metric)/100);
						\$(\".food_dietary_fiber_calculated\").text(food_dietary_fiber_calculated);
					});
				});
				</script>
				<!-- On change dietary fiber calculate -->
			   </td>
			  </tr>
			  <tr>
			   <td style=\"padding: 8px 4px 6px 8px;\">
				<span>$l_protein:</span>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span><input type=\"text\" name=\"inp_food_proteins\" value=\"$get_current_food_proteins_metric\" size=\"3\" /></span>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span class=\"food_proteins_calculated\">$get_current_food_proteins_calculated_metric</span>
				<!-- On change protein calculate -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_proteins\"]').on(\"change paste keyup\", function() {
						var food_proteins_hundred = $('[name=\"inp_food_proteins\"]').val();
						food_proteins_hundred = food_proteins_hundred.replace(\",\", \".\");
						food_proteins_calculated = Math.round((food_proteins_hundred*$get_current_food_serving_size_metric)/100);
						\$(\".food_proteins_calculated\").text(food_proteins_calculated);
					});
				});
				</script>
				<!-- On change protein calculate -->
			   </td>
			  </tr>
			 </tr>
			  <tr>
			   <td style=\"padding: 8px 4px 6px 8px;\">
				<span>$l_salt_in_gram</span>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span><input type=\"text\" name=\"inp_food_salt\" value=\"$get_current_food_salt_metric\" size=\"3\" /></span>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span class=\"food_salt_calculated\">$get_current_food_salt_calculated_metric</span>
				<!-- On change salt calculate -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_salt\"]').on(\"change paste keyup\", function() {

						// Calculate salt pr pc
						var food_salt_hundred = \$('[name=\"inp_food_salt\"]').val();
						food_salt_hundred = food_salt_hundred.replace(\",\", \".\");
						food_salt_calculated = (food_salt_hundred*$get_current_food_serving_size_metric)/100;
						food_salt_calculated = food_salt_calculated.toFixed(2)
						\$(\".food_salt_calculated\").text(food_salt_calculated);

						// Calculate sodium (Sodium is 40 % of salt)
						food_sodium_hundred = (food_salt_hundred*40)/100; // g
						food_sodium_hundred = food_sodium_hundred*1000; // mg
						food_sodium_calculated = Math.round((food_sodium_hundred*$get_current_food_serving_size_metric)/100);
						\$(\".food_sodium_calculated\").text(food_sodium_calculated);
						\$('[name=\"inp_food_sodium\"]').val(Math.round(food_sodium_hundred));
						

					});
				});
				</script>
				<!-- On change salt calculate -->
			   </td>
			  </tr>
			  <tr>
			   <td style=\"padding: 8px 4px 6px 8px;\">
				<span>$l_sodium_in_mg</span>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span><input type=\"text\" name=\"inp_food_sodium\" value=\"$get_current_food_sodium_metric\" size=\"3\" /></span>
			   </td>
			   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
				<span class=\"food_sodium_calculated\">$get_current_food_sodium_calculated_metric</span>
				<!-- On change sodium calculate -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_sodium\"]').on(\"change paste keyup\", function() {
						var food_sodium_hundred = \$('[name=\"inp_food_sodium\"]').val();
						food_sodium_hundred = food_sodium_hundred.replace(\",\", \".\");
						food_sodium_calculated = (food_sodium_hundred*$get_current_food_serving_size_metric)/100;
						food_sodium_calculated = food_sodium_calculated.toFixed(2)
						\$(\".food_sodium_calculated\").text(food_sodium_calculated);
						
					});
				});
				</script>
				<!-- On change sodium calculate -->
			   </td>
			  </tr>
			 </tbody>
			</table>


		<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" /></p>
		</form>
	<!-- //Edit food general -->
	";
} // food found
?>