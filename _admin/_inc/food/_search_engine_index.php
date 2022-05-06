<?php
/**
*
* File: _admin/_inc/food/_search_engine_index.php
* Version 21:08 16.01.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_food_categories		  	= $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  	= $mysqlPrefixSav . "food_categories_translations";
$t_food_index			 	= $mysqlPrefixSav . "food_index";
$t_food_index_stores		 	= $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		 	= $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  	= $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  	= $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		 	= $mysqlPrefixSav . "food_index_contents";
$t_food_index_favorites  	  	= $mysqlPrefixSav . "food_index_favorites";
$t_food_stores		  	  	= $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  	= $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  	= $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  	= $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations 	= $mysqlPrefixSav . "food_measurements_translations";
$t_food_countries_used	 	 	= $mysqlPrefixSav . "food_countries_used";
$t_food_integration	 	  	= $mysqlPrefixSav . "food_integration";
$t_food_age_restrictions 	 	= $mysqlPrefixSav . "food_age_restrictions";
$t_food_age_restrictions_accepted	= $mysqlPrefixSav . "food_age_restrictions_accepted";

/*- Functions ---------------------------------------------------------------------------- */
include("_functions/get_extension.php");

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_food_index";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){



	/* Food index */
	$query_w = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us_system, food_net_content_measurement_us_system, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us_system, food_serving_size_measurement_us_system, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_fat_of_which_saturated_fatty_acids_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us_system, food_fat_us_system, food_fat_of_which_saturated_fatty_acids_us_system, food_monounsaturated_fat_us_system, food_polyunsaturated_fat_us_system, food_cholesterol_us_system, food_carbohydrates_us_system, food_carbohydrates_of_which_sugars_us_system, food_dietary_fiber_us_system, food_proteins_us_system, food_salt_us_system, food_sodium_us_system, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_fat_of_which_saturated_fatty_acids_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us_system, food_fat_calculated_us_system, food_fat_of_which_saturated_fatty_acids_calculated_us_system, food_monounsaturated_fat_calculated_us_system, food_polyunsaturated_fat_calculated_us_system, food_carbohydrates_calculated_us_system, food_carbohydrates_of_which_sugars_calculated_us_system, food_dietary_fiber_calculated_us_system, food_proteins_calculated_us_system, food_salt_calculated_us_system, food_sodium_calculated_us_system, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list(food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us_system, $get_food_net_content_measurement_us_system, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us_system, $get_food_serving_size_measurement_us_system, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_fat_of_which_saturated_fatty_acids_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us_system, $get_food_fat_us_system, $get_food_fat_of_which_saturated_fatty_acids_us_system, $get_food_monounsaturated_fat_us_system, $get_food_polyunsaturated_fat_us_system, $get_food_cholesterol_us_system, $get_food_carbohydrates_us_system, $get_food_carbohydrates_of_which_sugars_us_system, $get_food_dietary_fiber_us_system, $get_food_proteins_us_system, $get_food_salt_us_system, $get_food_sodium_us_system, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_fat_of_which_saturated_fatty_acids_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us_system, $get_food_fat_calculated_us_system, $get_food_fat_of_which_saturated_fatty_acids_calculated_us_system, $get_food_monounsaturated_fat_calculated_us_system, $get_food_polyunsaturated_fat_calculated_us_system, $get_food_carbohydrates_calculated_us_system, $get_food_carbohydrates_of_which_sugars_calculated_us_system, $get_food_dietary_fiber_calculated_us_system, $get_food_proteins_calculated_us_system, $get_food_salt_calculated_us_system, $get_food_sodium_calculated_us_system, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row_w;


		// Find category by searcing food_category_id
		$l_mysql = quote_smart($link, $get_food_language);


		// Get sub category
		$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_food_categories WHERE category_id=$get_food_sub_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;

		if($get_current_sub_category_id == ""){
			echo"<p><b>Unknown sub category.</b></p>";
		}

		// Get main category
		$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_food_categories WHERE category_id=$get_current_sub_category_parent_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;
		if($get_current_main_category_id == ""){
			echo"<p><b>Unknown category.</b></p>";
		}

		// Translation
		$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_main_category_id AND category_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_main_category_translation_value) = $row_t;

		// Sub category translation
		$query_t = "SELECT category_translation_id, category_id, category_translation_language, category_translation_value, category_translation_no_food, category_translation_last_updated, category_calories_min, category_calories_med, category_calories_max, category_fat_min, category_fat_med, category_fat_max, category_fat_of_which_saturated_fatty_acids_min, category_fat_of_which_saturated_fatty_acids_med, category_fat_of_which_saturated_fatty_acids_max, category_carb_min, category_carb_med, category_carb_max, category_carb_of_which_dietary_fiber_min, category_carb_of_which_dietary_fiber_med, category_carb_of_which_dietary_fiber_max, category_carb_of_which_sugars_min, category_carb_of_which_sugars_med, category_carb_of_which_sugars_max, category_proteins_min, category_proteins_med, category_proteins_max, category_salt_min, category_salt_med, category_salt_max FROM $t_food_categories_translations WHERE category_id=$get_current_sub_category_id AND category_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_sub_category_translation_id, $get_current_sub_category_id, $get_current_sub_category_translation_language, $get_current_sub_category_translation_value, $get_current_sub_category_translation_no_food, $get_current_sub_category_translation_last_updated, $get_current_sub_category_calories_min, $get_current_sub_category_calories_med, $get_current_sub_category_calories_max, $get_current_sub_category_fat_min, $get_current_sub_category_fat_med, $get_current_sub_category_fat_max, $get_current_sub_category_fat_of_which_saturated_fatty_acids_min, $get_current_sub_category_fat_of_which_saturated_fatty_acids_med, $get_current_sub_category_fat_of_which_saturated_fatty_acids_max, $get_current_sub_category_carb_min, $get_current_sub_category_carb_med, $get_current_sub_category_carb_max, $get_current_sub_category_carb_of_which_dietary_fiber_min, $get_current_sub_category_carb_of_which_dietary_fiber_med, $get_current_sub_category_carb_of_which_dietary_fiber_max, $get_current_sub_category_carb_of_which_sugars_min, $get_current_sub_category_carb_of_which_sugars_med, $get_current_sub_category_carb_of_which_sugars_max, $get_current_sub_category_proteins_min, $get_current_sub_category_proteins_med, $get_current_sub_category_proteins_max, $get_current_sub_category_salt_min, $get_current_sub_category_salt_med, $get_current_sub_category_salt_max) = $row_t;
		


		$inp_index_title = "$get_food_manufacturer_name $get_food_name";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "food/view_food.php?main_category_id=$get_current_main_category_id&sub_category_id=$get_current_sub_category_id&food_id=$get_food_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_food_description, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$get_food_barcode");

		// Image
		$inp_index_image_path_mysql = quote_smart($link, $get_food_image_path);
		$inp_index_image_file_mysql = quote_smart($link, $get_food_image_a);
		
		// Thumb
		$thumb = "";
		if($get_food_image_a != ""){
			$ext = get_extension($get_food_image_a);
			$thumb = str_replace(".$ext", "", $get_food_image_a);
			$thumb = $thumb . "_thumb_235x132." . $ext;
		}
		$inp_index_image_thumb_mysql = quote_smart($link, $thumb);

		$inp_index_module_name_mysql = quote_smart($link, "food");

		$inp_index_module_part_name_mysql = quote_smart($link, "food");

		$inp_index_reference_name_mysql = quote_smart($link, "food_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_food_id");

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_food_language);


		// Check if exists
		$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_index_id) = $row_exists;
		if($get_index_id == ""){
			// Insert
			echo"<span>Insert $inp_index_title<br /></span>\n";
			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_image_path, index_image_file, index_image_thumb_235x132, 
			index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
			index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
			index_unique_hits) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
			$inp_index_image_path_mysql, $inp_index_image_file_mysql, $inp_index_image_thumb_mysql, 
			$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
			'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
			0)")
			or die(mysqli_error($link));
		}
	} // all food




	/* Food categories */
	$query_food = "SELECT category_translation_id, category_id, category_translation_language, category_translation_value, category_translation_no_food, category_translation_last_updated, category_calories_min, category_calories_med, category_calories_max, category_fat_min, category_fat_med, category_fat_max, category_fat_of_which_saturated_fatty_acids_min, category_fat_of_which_saturated_fatty_acids_med, category_fat_of_which_saturated_fatty_acids_max, category_carb_min, category_carb_med, category_carb_max, category_carb_of_which_dietary_fiber_min, category_carb_of_which_dietary_fiber_med, category_carb_of_which_dietary_fiber_max, category_carb_of_which_sugars_min, category_carb_of_which_sugars_med, category_carb_of_which_sugars_max, category_proteins_min, category_proteins_med, category_proteins_max, category_salt_min, category_salt_med, category_salt_max, category_sodium_min, category_sodium_med, category_sodium_max FROM $t_food_categories_translations";
	$result_food = mysqli_query($link, $query_food);
	while($row_food = mysqli_fetch_row($result_food)) {
		list($get_category_translation_id, $get_category_id, $get_category_translation_language, $get_category_translation_value, $get_category_translation_no_food, $get_category_translation_last_updated, $get_category_calories_min, $get_category_calories_med, $get_category_calories_max, $get_category_fat_min, $get_category_fat_med, $get_category_fat_max, $get_category_fat_of_which_saturated_fatty_acids_min, $get_category_fat_of_which_saturated_fatty_acids_med, $get_category_fat_of_which_saturated_fatty_acids_max, $get_category_carb_min, $get_category_carb_med, $get_category_carb_max, $get_category_carb_of_which_dietary_fiber_min, $get_category_carb_of_which_dietary_fiber_med, $get_category_carb_of_which_dietary_fiber_max, $get_category_carb_of_which_sugars_min, $get_category_carb_of_which_sugars_med, $get_category_carb_of_which_sugars_max, $get_category_proteins_min, $get_category_proteins_med, $get_category_proteins_max, $get_category_salt_min, $get_category_salt_med, $get_category_salt_max, $get_category_sodium_min, $get_category_sodium_med, $get_category_sodium_max) = $row_food;


		// is this main or sub category?
		$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_food_categories WHERE category_id=$get_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;
		


		$inp_index_title_mysql = quote_smart($link, $get_category_translation_value);

		if($get_current_sub_category_id == ""){
			$inp_index_url = "food/open_main_category.php?main_category_id=$get_category_id";
		}
		else{
			// Main category
			$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_food_categories WHERE category_id=$get_current_sub_category_parent_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;
			$inp_index_url = "food/open_sub_category.php?main_category_id=$get_current_main_category_id&sub_category_id=$get_current_sub_category_id";
		}
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = "";
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "food");

		$inp_index_module_part_name_mysql = quote_smart($link, "categories");

		$inp_index_reference_name_mysql = quote_smart($link, "category_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_category_id");

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_category_translation_language);


		// Check if exists
		$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_index_id) = $row_exists;
		if($get_index_id == ""){
			// Insert
			echo"<span>Insert $inp_index_title<br /></span>\n";
			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
			index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
			index_unique_hits) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
			$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
			'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
			0)")
			or die(mysqli_error($link));
		}
	} // Food categories


} // table exists
?>