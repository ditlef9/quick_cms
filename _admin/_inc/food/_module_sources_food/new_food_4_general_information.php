<?php 
/**
*
* File: food/new_food_4_general_information.php
* Version 1.0.0
* Date 10:20 17.10.2020
* Copyright (c) 2011-2020 Localhost
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";



/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");
include("$root/_admin/_translations/site/$l/food/ts_new_food.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['barcode'])){
	$barcode = $_GET['barcode'];
	$barcode = output_html($barcode);
	if($barcode != "" && !(is_numeric($barcode))){
		echo"barcode_have_to_be_numeric";
		exit;
	}
}
else{
	$barcode = "";
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


$tabindex = 0;
$l_mysql = quote_smart($link, $l);

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

/*- Sub category -------------------------------------------------------------------------- */
// Select sub category
$sub_category_id_mysql = quote_smart($link, $sub_category_id);
$query = "SELECT sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_age_limit FROM $t_food_categories_sub WHERE sub_category_id=$sub_category_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_sub_category_id, $get_current_sub_category_name, $get_current_sub_category_parent_id, $get_current_sub_category_symbolic_link_to_category_id, $get_current_sub_category_age_limit) = $row;

if($get_current_sub_category_id== ""){
	$website_title = "Server error 404 - $get_current_title_value";
}
else{
	// Sub category Translation
	$query_t = "SELECT sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value, sub_category_translation_no_food, sub_category_unique_hits, sub_category_unique_hits_this_year, sub_category_unique_hits_this_year_year, sub_category_unique_hits_ip_block, sub_category_calories_min_100_g, sub_category_calories_med_100_g, sub_category_calories_max_100_g, sub_category_calories_p_ten_percentage_100_g, sub_category_calories_m_ten_percentage_100_g, sub_category_fat_min_100_g, sub_category_fat_med_100_g, sub_category_fat_max_100_g, sub_category_fat_p_ten_percentage_100_g, sub_category_fat_m_ten_percentage_100_g, sub_category_saturated_fat_min_100_g, sub_category_saturated_fat_med_100_g, sub_category_saturated_fat_max_100_g, sub_category_saturated_fat_p_ten_percentage_100_g, sub_category_saturated_fat_m_ten_percentage_100_g, sub_category_trans_fat_min_100_g, sub_category_trans_fat_med_100_g, sub_category_trans_fat_max_100_g, sub_category_trans_fat_p_ten_percentage_100_g, sub_category_trans_fat_m_ten_percentage_100_g, sub_category_monounsaturated_fat_min_100_g, sub_category_monounsaturated_fat_med_100_g, sub_category_monounsaturated_fat_max_100_g, sub_category_monounsaturated_fat_p_ten_percentage_100_g, sub_category_monounsaturated_fat_m_ten_percentage_100_g, sub_category_polyunsaturated_fat_min_100_g, sub_category_polyunsaturated_fat_med_100_g, sub_category_polyunsaturated_fat_max_100_g, sub_category_polyunsaturated_fat_p_ten_percentage_100_g, sub_category_polyunsaturated_fat_m_ten_percentage_100_g, sub_category_cholesterol_min_100_g, sub_category_cholesterol_med_100_g, sub_category_cholesterol_max_100_g, sub_category_cholesterol_p_ten_percentage_100_g, sub_category_cholesterol_m_ten_percentage_100_g, sub_category_carb_min_100_g, sub_category_carb_med_100_g, sub_category_carb_max_100_g, sub_category_carb_p_ten_percentage_100_g, sub_category_carb_m_ten_percentage_100_g, sub_category_carb_of_which_sugars_min_100_g, sub_category_carb_of_which_sugars_med_100_g, sub_category_carb_of_which_sugars_max_100_g, sub_category_carb_of_which_sugars_p_ten_percentage_100_g, sub_category_carb_of_which_sugars_m_ten_percentage_100_g, sub_category_added_sugars_min_100_g, sub_category_added_sugars_med_100_g, sub_category_added_sugars_max_100_g, sub_category_added_sugars_p_ten_percentage_100_g, sub_category_added_sugars_m_ten_percentage_100_g, sub_category_dietary_fiber_min_100_g, sub_category_dietary_fiber_med_100_g, sub_category_dietary_fiber_max_100_g, sub_category_dietary_fiber_p_ten_percentage_100_g, sub_category_dietary_fiber_m_ten_percentage_100_g, sub_category_proteins_min_100_g, sub_category_proteins_med_100_g, sub_category_proteins_max_100_g, sub_category_proteins_p_ten_percentage_100_g, sub_category_proteins_m_ten_percentage_100_g, sub_category_salt_min_100_g, sub_category_salt_med_100_g, sub_category_salt_max_100_g, sub_category_salt_p_ten_percentage_100_g, sub_category_salt_m_ten_percentage_100_g, sub_category_sodium_min_100_g, sub_category_sodium_med_100_g, sub_category_sodium_max_100_g, sub_category_sodium_p_ten_percentage_100_g, sub_category_sodium_m_ten_percentage_100_g FROM $t_food_categories_sub_translations WHERE sub_category_id=$get_current_sub_category_id AND sub_category_translation_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_sub_category_translation_id, $get_current_sub_category_id, $get_current_sub_category_translation_language, $get_current_sub_category_translation_value, $get_current_sub_category_translation_no_food, $get_current_sub_category_unique_hits, $get_current_sub_category_unique_hits_this_year, $get_current_sub_category_unique_hits_this_year_year, $get_current_sub_category_unique_hits_ip_block, $get_current_sub_category_calories_min_100_g, $get_current_sub_category_calories_med_100_g, $get_current_sub_category_calories_max_100_g, $get_current_sub_category_calories_p_ten_percentage_100_g, $get_current_sub_category_calories_m_ten_percentage_100_g, $get_current_sub_category_fat_min_100_g, $get_current_sub_category_fat_med_100_g, $get_current_sub_category_fat_max_100_g, $get_current_sub_category_fat_p_ten_percentage_100_g, $get_current_sub_category_fat_m_ten_percentage_100_g, $get_current_sub_category_saturated_fat_min_100_g, $get_current_sub_category_saturated_fat_med_100_g, $get_current_sub_category_saturated_fat_max_100_g, $get_current_sub_category_saturated_fat_p_ten_percentage_100_g, $get_current_sub_category_saturated_fat_m_ten_percentage_100_g, $get_current_sub_category_trans_fat_min_100_g, $get_current_sub_category_trans_fat_med_100_g, $get_current_sub_category_trans_fat_max_100_g, $get_current_sub_category_trans_fat_p_ten_percentage_100_g, $get_current_sub_category_trans_fat_m_ten_percentage_100_g, $get_current_sub_category_monounsaturated_fat_min_100_g, $get_current_sub_category_monounsaturated_fat_med_100_g, $get_current_sub_category_monounsaturated_fat_max_100_g, $get_current_sub_category_monounsaturated_fat_p_ten_percentage_100_g, $get_current_sub_category_monounsaturated_fat_m_ten_percentage_100_g, $get_current_sub_category_polyunsaturated_fat_min_100_g, $get_current_sub_category_polyunsaturated_fat_med_100_g, $get_current_sub_category_polyunsaturated_fat_max_100_g, $get_current_sub_category_polyunsaturated_fat_p_ten_percentage_100_g, $get_current_sub_category_polyunsaturated_fat_m_ten_percentage_100_g, $get_current_sub_category_cholesterol_min_100_g, $get_current_sub_category_cholesterol_med_100_g, $get_current_sub_category_cholesterol_max_100_g, $get_current_sub_category_cholesterol_p_ten_percentage_100_g, $get_current_sub_category_cholesterol_m_ten_percentage_100_g, $get_current_sub_category_carb_min_100_g, $get_current_sub_category_carb_med_100_g, $get_current_sub_category_carb_max_100_g, $get_current_sub_category_carb_p_ten_percentage_100_g, $get_current_sub_category_carb_m_ten_percentage_100_g, $get_current_sub_category_carb_of_which_sugars_min_100_g, $get_current_sub_category_carb_of_which_sugars_med_100_g, $get_current_sub_category_carb_of_which_sugars_max_100_g, $get_current_sub_category_carb_of_which_sugars_p_ten_percentage_100_g, $get_current_sub_category_carb_of_which_sugars_m_ten_percentage_100_g, $get_current_sub_category_added_sugars_min_100_g, $get_current_sub_category_added_sugars_med_100_g, $get_current_sub_category_added_sugars_max_100_g, $get_current_sub_category_added_sugars_p_ten_percentage_100_g, $get_current_sub_category_added_sugars_m_ten_percentage_100_g, $get_current_sub_category_dietary_fiber_min_100_g, $get_current_sub_category_dietary_fiber_med_100_g, $get_current_sub_category_dietary_fiber_max_100_g, $get_current_sub_category_dietary_fiber_p_ten_percentage_100_g, $get_current_sub_category_dietary_fiber_m_ten_percentage_100_g, $get_current_sub_category_proteins_min_100_g, $get_current_sub_category_proteins_med_100_g, $get_current_sub_category_proteins_max_100_g, $get_current_sub_category_proteins_p_ten_percentage_100_g, $get_current_sub_category_proteins_m_ten_percentage_100_g, $get_current_sub_category_salt_min_100_g, $get_current_sub_category_salt_med_100_g, $get_current_sub_category_salt_max_100_g, $get_current_sub_category_salt_p_ten_percentage_100_g, $get_current_sub_category_salt_m_ten_percentage_100_g, $get_current_sub_category_sodium_min_100_g, $get_current_sub_category_sodium_med_100_g, $get_current_sub_category_sodium_max_100_g, $get_current_sub_category_sodium_p_ten_percentage_100_g, $get_current_sub_category_sodium_m_ten_percentage_100_g) = $row_t;
	if($get_current_sub_category_translation_id == ""){
		echo"<p>Error could not find translation</p>";
		die;
	}

	// Find main category
	$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit FROM $t_food_categories_main WHERE main_category_id=$get_current_sub_category_parent_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit) = $row;

	
	// Main category translation
	$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_current_main_category_id AND main_category_translation_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_main_category_translation_id, $get_current_main_category_translation_value) = $row_t;
	
	// Title
	$website_title = "$get_current_sub_category_translation_value - $get_current_main_category_translation_value - $l_new_food - $get_current_title_value";

}

/*- Headers ---------------------------------------------------------------------------------- */
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	


	if($get_current_sub_category_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>Sub category not found.</p>

		<p><a href=\"index.php?l=$l\">Categories</a></p>
		";
	}
	else{
		if($process == "1"){

			// Datetime (notes)
			$datetime = date("Y-m-d H:i:s");
			$date = date("Y-m-d");

			// Get the measurment I used for my last food
			$query = "SELECT food_id, food_net_content_measurement_metric, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_measurement_metric, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs_measurement, food_numbers_entered_method FROM $t_food_index WHERE food_user_id=$my_user_id_mysql ORDER BY food_id DESC LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_food_id, $get_last_food_net_content_measurement_metric, $get_last_food_net_content_measurement_us, $get_last_food_net_content_added_measurement, $get_last_food_serving_size_measurement_metric, $get_last_food_serving_size_measurement_us, $get_last_food_serving_size_added_measurement, $get_last_food_serving_size_pcs_measurement, $get_last_food_numbers_entered_method) = $row;

			$inp_net_content_added_measurement_mysql = quote_smart($link, $get_last_food_net_content_added_measurement);
			$inp_serving_size_added_measurement_mysql = quote_smart($link, $get_last_food_serving_size_added_measurement);


			$inp_name = $_POST['inp_food_name'];
			$inp_name = output_html($inp_name);
			$inp_name_mysql = quote_smart($link, $inp_name);
			if(empty($inp_name)){
				$ft = "error";
				$fm = "missing_name";
			}

			// Clean name
			$inp_clean_name = clean($inp_name);
			$inp_clean_name_mysql = quote_smart($link, $inp_clean_name);

			$inp_manufacturer_name = $_POST['inp_food_manufacturer_name'];
			$inp_manufacturer_name = output_html($inp_manufacturer_name);
			$inp_manufacturer_name_mysql = quote_smart($link, $inp_manufacturer_name);

			$inp_manufacturer_name_and_food_name = "$inp_manufacturer_name $inp_name";
			$inp_manufacturer_name_and_food_name_mysql = quote_smart($link, $inp_manufacturer_name_and_food_name);
		
			// $inp_food_description = $_POST['inp_food_description'];
			$inp_description = "";
			$inp_description = output_html($inp_description);
			$inp_description_mysql = quote_smart($link, $inp_description);

			$inp_text_mysql = quote_smart($link, "");

			$inp_country = $_POST['inp_food_country'];
			$inp_country = output_html($inp_country);
			$inp_country_mysql = quote_smart($link, $inp_country);

			// View method
			$inp_nutrition_facts_view_method = "eu";
			if($inp_country == "United States" && $l == "en"){
				$inp_nutrition_facts_view_method = "us";
			}
			$inp_nutrition_facts_view_method_mysql = quote_smart($link, $inp_nutrition_facts_view_method);

			$inp_net_content_metric_mysql= quote_smart($link, "0"); 
			$inp_net_content_measurement_metric_mysql= quote_smart($link, $get_last_food_net_content_measurement_metric); 
			$inp_net_content_us_mysql= quote_smart($link, "0"); 
			$inp_net_content_measurement_us_mysql= quote_smart($link, $get_last_food_net_content_measurement_us); 
			$inp_net_content_added_measurement_mysql= quote_smart($link, $get_last_food_net_content_added_measurement); 
			$inp_serving_size_metric_mysql= quote_smart($link, "0"); 

			$inp_serving_size_measurement_metric_mysql= quote_smart($link, $get_last_food_serving_size_measurement_metric); 
			$inp_serving_size_us_mysql= quote_smart($link, "0"); 
			$inp_serving_size_measurement_us_mysql= quote_smart($link, $get_last_food_serving_size_measurement_us); 
			$inp_serving_size_added_measurement_mysql= quote_smart($link, $get_last_food_serving_size_added_measurement); 
			$inp_serving_size_pcs_mysql= quote_smart($link, "0"); 
			$inp_serving_size_pcs_measurement_mysql= quote_smart($link, $get_last_food_serving_size_pcs_measurement); 
			$inp_numbers_entered_method_mysql= quote_smart($link, $get_last_food_numbers_entered_method); 
			$inp_energy_metric_mysql= quote_smart($link, "0"); 
			$inp_fat_metric_mysql= quote_smart($link, "0"); 
			$inp_saturated_fat_metric_mysql= quote_smart($link, "0"); 
			$inp_trans_fat_metric_mysql= quote_smart($link, "0"); 
			$inp_monounsaturated_fat_metric_mysql= quote_smart($link, "0"); 	
			$inp_polyunsaturated_fat_metric_mysql= quote_smart($link, "0"); 
			$inp_cholesterol_metric_mysql= quote_smart($link, "0"); 
			$inp_carbohydrates_metric_mysql= quote_smart($link, "0"); 

			$inp_carbohydrates_of_which_sugars_metric_mysql= quote_smart($link, "0"); $inp_added_sugars_metric_mysql= quote_smart($link, "0"); $inp_dietary_fiber_metric_mysql= quote_smart($link, "0"); $inp_proteins_metric_mysql= quote_smart($link, "0"); $inp_salt_metric_mysql= quote_smart($link, "0"); 
			$inp_sodium_metric_mysql= quote_smart($link, "0"); $inp_energy_us_mysql= quote_smart($link, "0"); $inp_fat_us_mysql= quote_smart($link, "0"); $inp_saturated_fat_us_mysql= quote_smart($link, "0"); $inp_trans_fat_us_mysql= quote_smart($link, "0"); 
			$inp_monounsaturated_fat_us_mysql= quote_smart($link, "0"); $inp_polyunsaturated_fat_us_mysql= quote_smart($link, "0"); $inp_cholesterol_us_mysql= quote_smart($link, "0"); $inp_carbohydrates_us_mysql= quote_smart($link, "0"); $inp_carbohydrates_of_which_sugars_us_mysql= quote_smart($link, "0"); 

			$inp_added_sugars_us_mysql= quote_smart($link, "0"); $inp_dietary_fiber_us_mysql= quote_smart($link, "0"); $inp_proteins_us_mysql= quote_smart($link, "0"); $inp_salt_us_mysql= quote_smart($link, "0"); $inp_sodium_us_mysql= quote_smart($link, "0"); 
			$inp_score_mysql= quote_smart($link, "0"); $inp_score_place_in_sub_category_mysql= quote_smart($link, "0"); $inp_energy_calculated_metric_mysql= quote_smart($link, "0"); $inp_fat_calculated_metric_mysql= quote_smart($link, "0"); $inp_saturated_fat_calculated_metric_mysql= quote_smart($link, "0"); 
			$inp_trans_fat_calculated_metric_mysql= quote_smart($link, "0"); $inp_monounsaturated_fat_calculated_metric_mysql= quote_smart($link, "0"); $inp_polyunsaturated_fat_calculated_metric_mysql= quote_smart($link, "0"); $inp_cholesterol_calculated_metric_mysql= quote_smart($link, "0"); $inp_carbohydrates_calculated_metric_mysql= quote_smart($link, "0"); 

			$inp_carbohydrates_of_which_sugars_calculated_metric_mysql= quote_smart($link, "0"); $inp_added_sugars_calculated_metric_mysql= quote_smart($link, "0"); $inp_dietary_fiber_calculated_metric_mysql= quote_smart($link, "0"); $inp_proteins_calculated_metric_mysql= quote_smart($link, "0"); $inp_salt_calculated_metric_mysql= quote_smart($link, "0"); 
			$inp_sodium_calculated_metric_mysql= quote_smart($link, "0"); $inp_energy_calculated_us_mysql= quote_smart($link, "0"); $inp_fat_calculated_us_mysql= quote_smart($link, "0"); $inp_saturated_fat_calculated_us_mysql= quote_smart($link, "0"); $inp_trans_fat_calculated_us_mysql= quote_smart($link, "0"); 
			$inp_monounsaturated_fat_calculated_us_mysql= quote_smart($link, "0"); $inp_polyunsaturated_fat_calculated_us_mysql= quote_smart($link, "0"); $inp_cholesterol_calculated_us_mysql= quote_smart($link, "0"); $inp_carbohydrates_calculated_us_mysql= quote_smart($link, "0"); $inp_carbohydrates_of_which_sugars_calculated_us_mysql= quote_smart($link, "0"); 

			$inp_added_sugars_calculated_us_mysql= quote_smart($link, "0"); $inp_dietary_fiber_calculated_us_mysql= quote_smart($link, "0"); $inp_proteins_calculated_us_mysql= quote_smart($link, "0"); $inp_salt_calculated_us_mysql= quote_smart($link, "0"); $inp_sodium_calculated_us_mysql= quote_smart($link, "0"); 
			$inp_energy_net_content_mysql= quote_smart($link, "0"); $inp_fat_net_content_mysql= quote_smart($link, "0"); $inp_saturated_fat_net_content_mysql= quote_smart($link, "0"); $inp_trans_fat_net_content_mysql= quote_smart($link, "0"); $inp_monounsaturated_fat_net_content_mysql= quote_smart($link, "0"); 
			$inp_polyunsaturated_fat_net_content_mysql= quote_smart($link, "0"); $inp_cholesterol_net_content_mysql= quote_smart($link, "0"); $inp_carbohydrates_net_content_mysql= quote_smart($link, "0"); $inp_carbohydrates_of_which_sugars_net_content_mysql= quote_smart($link, "0"); $inp_added_sugars_net_content_mysql= quote_smart($link, "0"); 

			$inp_dietary_fiber_net_content_mysql= quote_smart($link, "0"); $inp_proteins_net_content_mysql= quote_smart($link, "0"); 
			$inp_salt_net_content_mysql= quote_smart($link, "0"); $inp_sodium_net_content_mysql= quote_smart($link, "0"); 

			$inp_barcode_mysql= quote_smart($link, $barcode); 

			$inp_main_category_id_mysql= quote_smart($link, "0"); 
			$inp_sub_category_id_mysql= quote_smart($link, "0"); 

			$inp_image_path_mysql= quote_smart($link, "_uploads/food/_img/$l/$year"); 
			$inp_image_a_mysql= quote_smart($link, ""); 
			$inp_thumb_a_small_mysql= quote_smart($link, ""); 
			$inp_thumb_a_medium_mysql= quote_smart($link, ""); 
			$inp_thumb_a_large_mysql= quote_smart($link, ""); 

			$inp_image_b_mysql= quote_smart($link, ""); 
			$inp_thumb_b_small_mysql= quote_smart($link, ""); 
			$inp_thumb_b_medium_mysql= quote_smart($link, ""); 
			$inp_thumb_b_large_mysql= quote_smart($link, ""); 

			$inp_image_c_mysql= quote_smart($link, ""); 
			$inp_thumb_c_small_mysql= quote_smart($link, ""); 
			$inp_thumb_c_medium_mysql= quote_smart($link, ""); 
			$inp_thumb_c_large_mysql= quote_smart($link, ""); 

			$inp_image_d_mysql= quote_smart($link, ""); 
			$inp_thumb_d_small_mysql= quote_smart($link, ""); 
			$inp_thumb_d_medium_mysql= quote_smart($link, ""); 
			$inp_thumb_d_large_mysql= quote_smart($link, ""); 

			$inp_image_e_mysql= quote_smart($link, ""); 
			$inp_thumb_e_small_mysql= quote_smart($link, ""); 
			$inp_thumb_e_medium_mysql= quote_smart($link, ""); 
			$inp_thumb_e_large_mysql= quote_smart($link, ""); 

			$inp_last_used_mysql= quote_smart($link, $date); 
			$inp_language_mysql= quote_smart($link, $l); 

			$inp_no_of_comments_mysql= quote_smart($link, "0"); 
			$inp_stars_mysql= quote_smart($link, "0"); 
			$inp_stars_sum_mysql= quote_smart($link, "0"); 
			$inp_comments_multiplied_stars_mysql= quote_smart($link, "0"); 

			$inp_synchronized_mysql= quote_smart($link, $date); 
			$inp_accepted_as_master_mysql= quote_smart($link, "-1"); 

			$inp_notes = "Started on $datetime by user id $my_user_id";
			$inp_notes_mysql= quote_smart($link, $inp_notes); 

			$inp_unique_hits_mysql= quote_smart($link, "0"); 
			$inp_unique_hits_ip_block_mysql= quote_smart($link, ""); 
			$inp_user_ip_mysql= quote_smart($link, $my_ip); 
			$inp_created_date_mysql= quote_smart($link, $date); 
			$inp_last_viewed_mysql= quote_smart($link, $datetime); 

			$inp_age_restriction = $_POST['inp_age_restriction'];
			$inp_age_restriction = output_html($inp_age_restriction);
			$inp_age_restriction_mysql = quote_smart($link, $inp_age_restriction);


			if($ft == ""){

				mysqli_query($link, "INSERT INTO $t_food_index
				(food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, 
				food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, 
				food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, 

				food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, 
				food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, 
				food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, 

				food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, 
				food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, 
				food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, 

				food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, 
				food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, 
				food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, 

				food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, 
				food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, 
				food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, 

				food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, 
				food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, 
				food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, 

				food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, 
				food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, 
				food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, 

				food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, 
				food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, 
				food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, 

				food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, 
				food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, 
				food_created_date, food_last_viewed, food_age_restriction) 
				VALUES 
				(NULL, $my_user_id_mysql, $inp_name_mysql, $inp_clean_name_mysql, $inp_manufacturer_name_mysql, 
				$inp_manufacturer_name_and_food_name_mysql, $inp_description_mysql, $inp_text_mysql, $inp_country_mysql, $inp_net_content_metric_mysql, 
				$inp_net_content_measurement_metric_mysql, $inp_net_content_us_mysql, $inp_net_content_measurement_us_mysql, $inp_net_content_added_measurement_mysql, $inp_serving_size_metric_mysql, 

				$inp_serving_size_measurement_metric_mysql, $inp_serving_size_us_mysql, $inp_serving_size_measurement_us_mysql, $inp_serving_size_added_measurement_mysql, $inp_serving_size_pcs_mysql, 
				$inp_serving_size_pcs_measurement_mysql, $inp_numbers_entered_method_mysql, $inp_nutrition_facts_view_method_mysql, $inp_energy_metric_mysql, $inp_fat_metric_mysql, $inp_saturated_fat_metric_mysql, 
				$inp_trans_fat_metric_mysql, $inp_monounsaturated_fat_metric_mysql, $inp_polyunsaturated_fat_metric_mysql, $inp_cholesterol_metric_mysql, $inp_carbohydrates_metric_mysql, 

				$inp_carbohydrates_of_which_sugars_metric_mysql, $inp_added_sugars_metric_mysql, $inp_dietary_fiber_metric_mysql, $inp_proteins_metric_mysql, $inp_salt_metric_mysql, 
				$inp_sodium_metric_mysql, $inp_energy_us_mysql, $inp_fat_us_mysql, $inp_saturated_fat_us_mysql, $inp_trans_fat_us_mysql, 
				$inp_monounsaturated_fat_us_mysql, $inp_polyunsaturated_fat_us_mysql, $inp_cholesterol_us_mysql, $inp_carbohydrates_us_mysql, $inp_carbohydrates_of_which_sugars_us_mysql, 

				$inp_added_sugars_us_mysql, $inp_dietary_fiber_us_mysql, $inp_proteins_us_mysql, $inp_salt_us_mysql, $inp_sodium_us_mysql, 
				$inp_score_mysql, $inp_score_place_in_sub_category_mysql, $inp_energy_calculated_metric_mysql, $inp_fat_calculated_metric_mysql, $inp_saturated_fat_calculated_metric_mysql, 
				$inp_trans_fat_calculated_metric_mysql, $inp_monounsaturated_fat_calculated_metric_mysql, $inp_polyunsaturated_fat_calculated_metric_mysql, $inp_cholesterol_calculated_metric_mysql, $inp_carbohydrates_calculated_metric_mysql, 

				$inp_carbohydrates_of_which_sugars_calculated_metric_mysql, $inp_added_sugars_calculated_metric_mysql, $inp_dietary_fiber_calculated_metric_mysql, $inp_proteins_calculated_metric_mysql, $inp_salt_calculated_metric_mysql, 
				$inp_sodium_calculated_metric_mysql, $inp_energy_calculated_us_mysql, $inp_fat_calculated_us_mysql, $inp_saturated_fat_calculated_us_mysql, $inp_trans_fat_calculated_us_mysql, 
				$inp_monounsaturated_fat_calculated_us_mysql, $inp_polyunsaturated_fat_calculated_us_mysql, $inp_cholesterol_calculated_us_mysql, $inp_carbohydrates_calculated_us_mysql, $inp_carbohydrates_of_which_sugars_calculated_us_mysql, 

				$inp_added_sugars_calculated_us_mysql, $inp_dietary_fiber_calculated_us_mysql, $inp_proteins_calculated_us_mysql, $inp_salt_calculated_us_mysql, $inp_sodium_calculated_us_mysql, 
				$inp_energy_net_content_mysql, $inp_fat_net_content_mysql, $inp_saturated_fat_net_content_mysql, $inp_trans_fat_net_content_mysql, $inp_monounsaturated_fat_net_content_mysql, 
				$inp_polyunsaturated_fat_net_content_mysql, $inp_cholesterol_net_content_mysql, $inp_carbohydrates_net_content_mysql, $inp_carbohydrates_of_which_sugars_net_content_mysql, $inp_added_sugars_net_content_mysql, 

				$inp_dietary_fiber_net_content_mysql, $inp_proteins_net_content_mysql, $inp_salt_net_content_mysql, $inp_sodium_net_content_mysql, $inp_barcode_mysql, 
				$get_current_main_category_id, $get_current_sub_category_id, $inp_image_path_mysql, $inp_image_a_mysql, $inp_thumb_a_small_mysql, 
				$inp_thumb_a_medium_mysql, $inp_thumb_a_large_mysql, $inp_image_b_mysql, $inp_thumb_b_small_mysql, $inp_thumb_b_medium_mysql, 

				$inp_thumb_b_large_mysql, $inp_image_c_mysql, $inp_thumb_c_small_mysql, $inp_thumb_c_medium_mysql, $inp_thumb_c_large_mysql, 
				$inp_image_d_mysql, $inp_thumb_d_small_mysql, $inp_thumb_d_medium_mysql, $inp_thumb_d_large_mysql, $inp_image_e_mysql, 
				$inp_thumb_e_small_mysql, $inp_thumb_e_medium_mysql, $inp_thumb_e_large_mysql, $inp_last_used_mysql, $inp_language_mysql, 

				$inp_no_of_comments_mysql, $inp_stars_mysql, $inp_stars_sum_mysql, $inp_comments_multiplied_stars_mysql, $inp_synchronized_mysql, 
				$inp_accepted_as_master_mysql, $inp_notes_mysql, $inp_unique_hits_mysql, $inp_unique_hits_ip_block_mysql, $inp_user_ip_mysql, 
				$inp_created_date_mysql, $inp_last_viewed_mysql, $inp_age_restriction_mysql)")
				or die(mysqli_error($link));

				// Get _id
				$query = "SELECT food_id FROM $t_food_index WHERE food_notes=$inp_notes_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_food_id) = $row;
				if($get_food_id == ""){
					echo"Error could not get food id";
					die;
				}


				// Check if food country is used
				// echo "inp_country=$inp_country";
				$query_t = "SELECT food_country_id, food_country_count_food FROM $t_food_countries_used WHERE food_country_name=$inp_country_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_food_country_id, $get_food_country_count_food) = $row_t;
				if($get_food_country_id == ""){
					// New food country
					mysqli_query($link, "INSERT INTO $t_food_countries_used 
					(food_country_id, food_country_name, food_country_count_food) 
					VALUES 
					(NULL, $inp_country_mysql, '1')")
					or die(mysqli_error($link));
				}
				else{
					$inp_count = $get_food_country_count_food+1;
					$result = mysqli_query($link, "UPDATE $t_food_countries_used SET food_country_count_food=$inp_count WHERE food_country_id=$get_food_country_id");
				}

				// Barcode check 
				$barcode_first = substr($barcode, 0, 1);
				if($barcode_first == "0"){
					$sql = "UPDATE $t_food_index SET food_barcode=? WHERE food_id='$get_food_id'";
					$stmt = $link->prepare($sql);
					$stmt->bind_param("s", $barcode);
					$stmt->execute();
					if ($stmt->errno) {
						echo "FAILURE!!! " . $stmt->error; die;
					}
					
				}

				

				// Search engine
				$inp_index_title = "$inp_manufacturer_name $inp_name";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "food/view_food.php?food_id=$get_food_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				$inp_index_short_description = substr($inp_description , 0, 200);
				$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j. M Y H:i");

				$inp_index_language = output_html($l);
				$inp_index_language_mysql = quote_smart($link, $inp_index_language);

				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_reference_name, index_reference_id, index_is_ad, 
				index_created_datetime, index_created_datetime_print, index_language) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, '', 
				'food', 'food', 'food_id','$get_food_id', 0, 
				'$datetime', '$datetime_saying', $inp_index_language_mysql)")
				or die(mysqli_error($link));
		
				// Search engine
				include("new_food_00_add_update_search_engine.php");


				// Header
				$url = "new_food_5_images.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_food_id&image=a&l=$l";
				header("Location: $url");
				exit;

			}
			else{
				$url = "new_food_4_general_information.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				$url = $url . "&inp_food_name=$inp_food_name";
				$url = $url . "&inp_food_manufacturer_name=$inp_food_manufacturer_name";
				$url = $url . "&inp_food_description=$inp_food_description";
				$url = $url . "&inp_food_barcode=$inp_food_barcode";
				$url = $url . "&inp_food_serving_size_gram=$inp_food_serving_size_gram";
				$url = $url . "&inp_food_serving_size_gram_measurement=$inp_food_serving_size_gram_measurement";
				$url = $url . "&inp_food_serving_size_pcs=$inp_food_serving_size_pcs";
				$url = $url . "&inp_food_serving_size_pcs_measurement=$inp_food_serving_size_pcs_measurement";

				header("Location: $url");
				exit;
			}
		}	


		echo"
		<h1>$l_new_food</h1>
		<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "missing_energy"){
						$fm = "Please enter energy";
					}
					elseif($fm == "missing_proteins"){
						$fm = "Please enter proteins";
					}
					elseif($fm == "missing_carbohydrates"){
						$fm = "Please enter carbohydrates";
					}
					elseif($fm == "missing_fat"){
						$fm = "Please enter fat";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><p>$fm</p></div>";	
				}
				echo"

		<!-- //Feedback -->


		<!-- General information -->
			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_name\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"new_food_4_general_information.php?main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;barcode=$barcode&amp;process=1\" enctype=\"multipart/form-data\">

			<h2>$l_general_information</h2>
					
			<p><b>$l_name*:</b><br />
			<input type=\"text\" name=\"inp_food_name\" value=\"";
				if(isset($_GET['inp_food_name'])){
					$inp_food_name= $_GET['inp_food_name'];
					$inp_food_name = output_html($inp_food_name);
					echo"$inp_food_name";
				}
				echo"\" size=\"40\" /></p>
			
			<p><b>$l_manufacturer*:</b><br />
			<input type=\"text\" name=\"inp_food_manufacturer_name\" value=\"";
				if(isset($_GET['inp_food_manufacturer_name'])){
					$inp_food_manufacturer_name= $_GET['inp_food_manufacturer_name'];
					$inp_food_manufacturer_name = output_html($inp_food_manufacturer_name);
					echo"$inp_food_manufacturer_name";
				}
				echo"\" size=\"40\" /></p>
			
			<p><b>$l_country*:</b><br />\n";


				if(isset($_GET['inp_food_country'])){
					$inp_food_country = $_GET['inp_food_country'];
					$inp_food_country = strip_tags(stripslashes($inp_food_country));
				}
				else{
					$inp_food_country = "";
				}

				// Find the country the last person registrered used
				$inp_food_user_id = $_SESSION['user_id'];
				$inp_food_user_id = output_html($inp_food_user_id);
				$inp_food_user_id_mysql = quote_smart($link, $inp_food_user_id);

				$query = "SELECT food_country FROM $t_food_index WHERE food_user_id=$inp_food_user_id_mysql ORDER BY food_id DESC LIMIT 0,1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($inp_food_country) = $row;
			
				echo"
				<select name=\"inp_food_country\">\n";
				$query = "SELECT country_name FROM $t_languages_countries ORDER BY country_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_country_name) = $row;

					echo"			";
					echo"<option value=\"$get_country_name\""; if($inp_food_country == "$get_country_name"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
					
				}
				echo"
				</select>
				</p>
			
			<p><b>$l_age_restriction:</b><br />";

				if(isset($_GET['inp_age_restriction'])){
					$inp_age_restriction = $_GET['inp_age_restriction'];
					$inp_age_restriction = strip_tags(stripslashes($inp_age_restriction));
					echo"$inp_food_barcode";
				}
				else{
					$inp_age_restriction = 0;
				}
				echo"
				<select name=\"inp_age_restriction\">
					<option value=\"0\""; if($inp_age_restriction == "0"){ echo" selected=\"selected\""; } echo">$l_no</option>
					<option value=\"1\""; if($inp_age_restriction == "1"){ echo" selected=\"selected\""; } echo">$l_yes</option>
				</select>
				<br />
				<em>$l_example_alcohol</em></p>
			
			<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn-success btn-sm\" /></p>
			
			<!-- //General information -->

		";
	} // mode == ""
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/food/new_food.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>