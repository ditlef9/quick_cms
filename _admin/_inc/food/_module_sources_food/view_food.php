<?php
/**
*
* File: food/view_food.php
* Version 2
* Date 04:12 02.04.2022
* Copyright (c) 2008-2022 Sindre Andre Ditlefsen
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
$t_food_liquidbase			= $mysqlPrefixSav . "food_liquidbase";

$t_food_categories		  	= $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  	= $mysqlPrefixSav . "food_categories_translations";
$t_food_index			 	= $mysqlPrefixSav . "food_index";
$t_food_index_stores		 	= $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		 	= $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  	= $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  	= $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		 	= $mysqlPrefixSav . "food_index_contents";
$t_food_index_ratings		 	= $mysqlPrefixSav . "food_index_ratings";
$t_food_stores		  	  	= $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  	= $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  	= $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  	= $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations 	= $mysqlPrefixSav . "food_measurements_translations";
$t_food_countries_used	 	 	= $mysqlPrefixSav . "food_countries_used";
$t_food_integration	 	  	= $mysqlPrefixSav . "food_integration";
$t_food_age_restrictions 	 	= $mysqlPrefixSav . "food_age_restrictions";
$t_food_age_restrictions_accepted	= $mysqlPrefixSav . "food_age_restrictions_accepted";
/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['food_id'])){
	$food_id = $_GET['food_id'];
	$food_id = strip_tags(stripslashes($food_id));
	if(!(is_numeric($food_id))){
		echo"food_id has to be numeric";
		die;
	}
}
else{
	$food_id = "";
	echo"food_id missing";
	die;
}
$food_id_mysql = quote_smart($link, $food_id);

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;


// System
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
}
else{
	// IP
	$my_user_ip = $_SERVER['REMOTE_ADDR'];
	$my_user_ip = output_html($my_user_ip);
	$my_user_ip_mysql = quote_smart($link, $my_user_ip);

	$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
}
if($get_current_view_system == ""){
	$get_current_view_system = "metric";
}




// Select food
$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$food_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_food_id, $get_current_food_user_id, $get_current_food_name, $get_current_food_clean_name, $get_current_food_manufacturer_name, $get_current_food_manufacturer_name_and_food_name, $get_current_food_description, $get_current_food_text, $get_current_food_country, $get_current_food_net_content_metric, $get_current_food_net_content_measurement_metric, $get_current_food_net_content_us, $get_current_food_net_content_measurement_us, $get_current_food_net_content_added_measurement, $get_current_food_serving_size_metric, $get_current_food_serving_size_measurement_metric, $get_current_food_serving_size_us, $get_current_food_serving_size_measurement_us, $get_current_food_serving_size_added_measurement, $get_current_food_serving_size_pcs, $get_current_food_serving_size_pcs_measurement, $get_current_food_numbers_entered_method, $get_current_food_nutrition_facts_view_method, $get_current_food_energy_metric, $get_current_food_fat_metric, $get_current_food_saturated_fat_metric, $get_current_food_trans_fat_metric, $get_current_food_monounsaturated_fat_metric, $get_current_food_polyunsaturated_fat_metric, $get_current_food_cholesterol_metric, $get_current_food_carbohydrates_metric, $get_current_food_carbohydrates_of_which_sugars_metric, $get_current_food_added_sugars_metric, $get_current_food_dietary_fiber_metric, $get_current_food_proteins_metric, $get_current_food_salt_metric, $get_current_food_sodium_metric, $get_current_food_energy_us, $get_current_food_fat_us, $get_current_food_saturated_fat_us, $get_current_food_trans_fat_us, $get_current_food_monounsaturated_fat_us, $get_current_food_polyunsaturated_fat_us, $get_current_food_cholesterol_us, $get_current_food_carbohydrates_us, $get_current_food_carbohydrates_of_which_sugars_us, $get_current_food_added_sugars_us, $get_current_food_dietary_fiber_us, $get_current_food_proteins_us, $get_current_food_salt_us, $get_current_food_sodium_us, $get_current_food_score, $get_current_food_score_place_in_sub_category, $get_current_food_energy_calculated_metric, $get_current_food_fat_calculated_metric, $get_current_food_saturated_fat_calculated_metric, $get_current_food_trans_fat_calculated_metric, $get_current_food_monounsaturated_fat_calculated_metric, $get_current_food_polyunsaturated_fat_calculated_metric, $get_current_food_cholesterol_calculated_metric, $get_current_food_carbohydrates_calculated_metric, $get_current_food_carbohydrates_of_which_sugars_calculated_metric, $get_current_food_added_sugars_calculated_metric, $get_current_food_dietary_fiber_calculated_metric, $get_current_food_proteins_calculated_metric, $get_current_food_salt_calculated_metric, $get_current_food_sodium_calculated_metric, $get_current_food_energy_calculated_us, $get_current_food_fat_calculated_us, $get_current_food_saturated_fat_calculated_us, $get_current_food_trans_fat_calculated_us, $get_current_food_monounsaturated_fat_calculated_us, $get_current_food_polyunsaturated_fat_calculated_us, $get_current_food_cholesterol_calculated_us, $get_current_food_carbohydrates_calculated_us, $get_current_food_carbohydrates_of_which_sugars_calculated_us, $get_current_food_added_sugars_calculated_us, $get_current_food_dietary_fiber_calculated_us, $get_current_food_proteins_calculated_us, $get_current_food_salt_calculated_us, $get_current_food_sodium_calculated_us, $get_current_food_energy_net_content, $get_current_food_fat_net_content, $get_current_food_saturated_fat_net_content, $get_current_food_trans_fat_net_content, $get_current_food_monounsaturated_fat_net_content, $get_current_food_polyunsaturated_fat_net_content, $get_current_food_cholesterol_net_content, $get_current_food_carbohydrates_net_content, $get_current_food_carbohydrates_of_which_sugars_net_content, $get_current_food_added_sugars_net_content, $get_current_food_dietary_fiber_net_content, $get_current_food_proteins_net_content, $get_current_food_salt_net_content, $get_current_food_sodium_net_content, $get_current_food_barcode, $get_current_food_main_category_id, $get_current_food_sub_category_id, $get_current_food_image_path, $get_current_food_image_a, $get_current_food_thumb_a_small, $get_current_food_thumb_a_medium, $get_current_food_thumb_a_large, $get_current_food_image_b, $get_current_food_thumb_b_small, $get_current_food_thumb_b_medium, $get_current_food_thumb_b_large, $get_current_food_image_c, $get_current_food_thumb_c_small, $get_current_food_thumb_c_medium, $get_current_food_thumb_c_large, $get_current_food_image_d, $get_current_food_thumb_d_small, $get_current_food_thumb_d_medium, $get_current_food_thumb_d_large, $get_current_food_image_e, $get_current_food_thumb_e_small, $get_current_food_thumb_e_medium, $get_current_food_thumb_e_large, $get_current_food_last_used, $get_current_food_language, $get_current_food_no_of_comments, $get_current_food_stars, $get_current_food_stars_sum, $get_current_food_comments_multiplied_stars, $get_current_food_synchronized, $get_current_food_accepted_as_master, $get_current_food_notes, $get_current_food_unique_hits, $get_current_food_unique_hits_ip_block, $get_current_food_user_ip, $get_current_food_created_date, $get_current_food_last_viewed, $get_current_food_age_restriction) = $row;

if($get_current_food_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $get_current_title_value";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");


	echo"
	<h1>Food not found</h1>

	<p>
	Sorry, the food was not found.
	</p>

	<p>
	<a href=\"index.php\">Back</a>
	</p>
	";
}
else{
	// View method

if(isset($_GET['nutrition_facts_view_method'])){
	$nutrition_facts_view_method = $_GET['nutrition_facts_view_method'];
	$nutrition_facts_view_method = strip_tags(stripslashes($nutrition_facts_view_method));

	if($nutrition_facts_view_method == "eu" OR $nutrition_facts_view_method == "us" OR $nutrition_facts_view_method == "all"){
	}
	else{
		$nutrition_facts_view_method = "$get_current_food_nutrition_facts_view_method";
	}
}
else{
	$nutrition_facts_view_method = "$get_current_food_nutrition_facts_view_method";
}



	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_food_manufacturer_name $get_current_food_name - $get_current_title_value";
	if($get_current_food_nutrition_facts_view_method == "$nutrition_facts_view_method"){
			$website_title = "$get_current_food_manufacturer_name $get_current_food_name - $get_current_title_value";
	}
	else{
		if($nutrition_facts_view_method == "eu"){
			$website_title = "$get_current_food_manufacturer_name $get_current_food_name ($l_eu) - $get_current_title_value";
		}
		elseif($nutrition_facts_view_method == "us"){
			$website_title = "$get_current_food_manufacturer_name $get_current_food_name ($l_us) - $get_current_title_value";
		}
	}
	include("$root/_webdesign/header.php");


	// Last viewed
	$datetime = date("Y-m-d H:i:s");
	$result = mysqli_query($link, "UPDATE $t_food_index SET food_last_viewed='$datetime' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));

	// Author
	$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$get_current_food_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_food_author_user_id, $get_current_food_author_user_email, $get_current_food_author_user_name, $get_current_food_author_user_alias) = $row;
	if($get_current_food_author_user_id == ""){
		$result = mysqli_query($link, "UPDATE $t_food_index SET food_user_id='1' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
	}


	// Get sub category
	if($get_current_food_sub_category_id != ""){
		$query = "SELECT sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_age_limit FROM $t_food_categories_sub WHERE sub_category_id=$get_current_food_sub_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_sub_category_id, $get_current_sub_category_name, $get_current_sub_category_parent_id, $get_current_sub_category_symbolic_link_to_category_id, $get_current_sub_category_age_limit) = $row;


	}

	if($get_current_sub_category_id == ""){
		echo"<p><b>Unknown sub category.</b></p>";

		// Find a random sub category
		$query = "SELECT sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_age_limit FROM $t_food_categories_sub";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_sub_category_id, $get_current_sub_category_name, $get_current_sub_category_parent_id, $get_current_sub_category_symbolic_link_to_category_id, $get_current_sub_category_age_limit) = $row;

		$result = mysqli_query($link, "UPDATE $t_food_index SET food_sub_category_id='$get_current_sub_category_id' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
	}



	// Get main category
	$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit FROM $t_food_categories_main WHERE main_category_id=$get_current_sub_category_parent_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit) = $row;

	if($get_current_main_category_id == ""){
		echo"<p><b>Unknown category.</b></p>";
	}
	else{
		// Check that we have it correct
		if($get_current_main_category_id != "$get_current_food_main_category_id"){
			echo"<div class=\"info\"><p>Updated food main category id</p></div>\n";
			$result = mysqli_query($link, "UPDATE $t_food_index SET food_main_category_id='$get_current_main_category_id' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
		}
	}

	// Main category translation
	$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_current_main_category_id AND main_category_translation_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_main_category_translation_id, $get_current_main_category_translation_value) = $row_t;


	// Sub category translation
	$query_t = "SELECT sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value, sub_category_translation_no_food, sub_category_unique_hits, sub_category_unique_hits_this_year, sub_category_unique_hits_this_year_year, sub_category_unique_hits_ip_block, sub_category_calories_min_100_g, sub_category_calories_med_100_g, sub_category_calories_max_100_g, sub_category_calories_p_ten_percentage_100_g, sub_category_calories_m_ten_percentage_100_g, sub_category_fat_min_100_g, sub_category_fat_med_100_g, sub_category_fat_max_100_g, sub_category_fat_p_ten_percentage_100_g, sub_category_fat_m_ten_percentage_100_g, sub_category_saturated_fat_min_100_g, sub_category_saturated_fat_med_100_g, sub_category_saturated_fat_max_100_g, sub_category_saturated_fat_p_ten_percentage_100_g, sub_category_saturated_fat_m_ten_percentage_100_g, sub_category_trans_fat_min_100_g, sub_category_trans_fat_med_100_g, sub_category_trans_fat_max_100_g, sub_category_trans_fat_p_ten_percentage_100_g, sub_category_trans_fat_m_ten_percentage_100_g, sub_category_monounsaturated_fat_min_100_g, sub_category_monounsaturated_fat_med_100_g, sub_category_monounsaturated_fat_max_100_g, sub_category_monounsaturated_fat_p_ten_percentage_100_g, sub_category_monounsaturated_fat_m_ten_percentage_100_g, sub_category_polyunsaturated_fat_min_100_g, sub_category_polyunsaturated_fat_med_100_g, sub_category_polyunsaturated_fat_max_100_g, sub_category_polyunsaturated_fat_p_ten_percentage_100_g, sub_category_polyunsaturated_fat_m_ten_percentage_100_g, sub_category_cholesterol_min_100_g, sub_category_cholesterol_med_100_g, sub_category_cholesterol_max_100_g, sub_category_cholesterol_p_ten_percentage_100_g, sub_category_cholesterol_m_ten_percentage_100_g, sub_category_carb_min_100_g, sub_category_carb_med_100_g, sub_category_carb_max_100_g, sub_category_carb_p_ten_percentage_100_g, sub_category_carb_m_ten_percentage_100_g, sub_category_carb_of_which_sugars_min_100_g, sub_category_carb_of_which_sugars_med_100_g, sub_category_carb_of_which_sugars_max_100_g, sub_category_carb_of_which_sugars_p_ten_percentage_100_g, sub_category_carb_of_which_sugars_m_ten_percentage_100_g, sub_category_added_sugars_min_100_g, sub_category_added_sugars_med_100_g, sub_category_added_sugars_max_100_g, sub_category_added_sugars_p_ten_percentage_100_g, sub_category_added_sugars_m_ten_percentage_100_g, sub_category_dietary_fiber_min_100_g, sub_category_dietary_fiber_med_100_g, sub_category_dietary_fiber_max_100_g, sub_category_dietary_fiber_p_ten_percentage_100_g, sub_category_dietary_fiber_m_ten_percentage_100_g, sub_category_proteins_min_100_g, sub_category_proteins_med_100_g, sub_category_proteins_max_100_g, sub_category_proteins_p_ten_percentage_100_g, sub_category_proteins_m_ten_percentage_100_g, sub_category_salt_min_100_g, sub_category_salt_med_100_g, sub_category_salt_max_100_g, sub_category_salt_p_ten_percentage_100_g, sub_category_salt_m_ten_percentage_100_g, sub_category_sodium_min_100_g, sub_category_sodium_med_100_g, sub_category_sodium_max_100_g, sub_category_sodium_p_ten_percentage_100_g, sub_category_sodium_m_ten_percentage_100_g FROM $t_food_categories_sub_translations WHERE sub_category_id=$get_current_sub_category_id AND sub_category_translation_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_sub_category_translation_id, $get_current_sub_category_id, $get_current_sub_category_translation_language, $get_current_sub_category_translation_value, $get_current_sub_category_translation_no_food, $get_current_sub_category_unique_hits, $get_current_sub_category_unique_hits_this_year, $get_current_sub_category_unique_hits_this_year_year, $get_current_sub_category_unique_hits_ip_block, $get_current_sub_category_calories_min_100_g, $get_current_sub_category_calories_med_100_g, $get_current_sub_category_calories_max_100_g, $get_current_sub_category_calories_p_ten_percentage_100_g, $get_current_sub_category_calories_m_ten_percentage_100_g, $get_current_sub_category_fat_min_100_g, $get_current_sub_category_fat_med_100_g, $get_current_sub_category_fat_max_100_g, $get_current_sub_category_fat_p_ten_percentage_100_g, $get_current_sub_category_fat_m_ten_percentage_100_g, $get_current_sub_category_saturated_fat_min_100_g, $get_current_sub_category_saturated_fat_med_100_g, $get_current_sub_category_saturated_fat_max_100_g, $get_current_sub_category_saturated_fat_p_ten_percentage_100_g, $get_current_sub_category_saturated_fat_m_ten_percentage_100_g, $get_current_sub_category_trans_fat_min_100_g, $get_current_sub_category_trans_fat_med_100_g, $get_current_sub_category_trans_fat_max_100_g, $get_current_sub_category_trans_fat_p_ten_percentage_100_g, $get_current_sub_category_trans_fat_m_ten_percentage_100_g, $get_current_sub_category_monounsaturated_fat_min_100_g, $get_current_sub_category_monounsaturated_fat_med_100_g, $get_current_sub_category_monounsaturated_fat_max_100_g, $get_current_sub_category_monounsaturated_fat_p_ten_percentage_100_g, $get_current_sub_category_monounsaturated_fat_m_ten_percentage_100_g, $get_current_sub_category_polyunsaturated_fat_min_100_g, $get_current_sub_category_polyunsaturated_fat_med_100_g, $get_current_sub_category_polyunsaturated_fat_max_100_g, $get_current_sub_category_polyunsaturated_fat_p_ten_percentage_100_g, $get_current_sub_category_polyunsaturated_fat_m_ten_percentage_100_g, $get_current_sub_category_cholesterol_min_100_g, $get_current_sub_category_cholesterol_med_100_g, $get_current_sub_category_cholesterol_max_100_g, $get_current_sub_category_cholesterol_p_ten_percentage_100_g, $get_current_sub_category_cholesterol_m_ten_percentage_100_g, $get_current_sub_category_carb_min_100_g, $get_current_sub_category_carb_med_100_g, $get_current_sub_category_carb_max_100_g, $get_current_sub_category_carb_p_ten_percentage_100_g, $get_current_sub_category_carb_m_ten_percentage_100_g, $get_current_sub_category_carb_of_which_sugars_min_100_g, $get_current_sub_category_carb_of_which_sugars_med_100_g, $get_current_sub_category_carb_of_which_sugars_max_100_g, $get_current_sub_category_carb_of_which_sugars_p_ten_percentage_100_g, $get_current_sub_category_carb_of_which_sugars_m_ten_percentage_100_g, $get_current_sub_category_added_sugars_min_100_g, $get_current_sub_category_added_sugars_med_100_g, $get_current_sub_category_added_sugars_max_100_g, $get_current_sub_category_added_sugars_p_ten_percentage_100_g, $get_current_sub_category_added_sugars_m_ten_percentage_100_g, $get_current_sub_category_dietary_fiber_min_100_g, $get_current_sub_category_dietary_fiber_med_100_g, $get_current_sub_category_dietary_fiber_max_100_g, $get_current_sub_category_dietary_fiber_p_ten_percentage_100_g, $get_current_sub_category_dietary_fiber_m_ten_percentage_100_g, $get_current_sub_category_proteins_min_100_g, $get_current_sub_category_proteins_med_100_g, $get_current_sub_category_proteins_max_100_g, $get_current_sub_category_proteins_p_ten_percentage_100_g, $get_current_sub_category_proteins_m_ten_percentage_100_g, $get_current_sub_category_salt_min_100_g, $get_current_sub_category_salt_med_100_g, $get_current_sub_category_salt_max_100_g, $get_current_sub_category_salt_p_ten_percentage_100_g, $get_current_sub_category_salt_m_ten_percentage_100_g, $get_current_sub_category_sodium_min_100_g, $get_current_sub_category_sodium_med_100_g, $get_current_sub_category_sodium_max_100_g, $get_current_sub_category_sodium_p_ten_percentage_100_g, $get_current_sub_category_sodium_m_ten_percentage_100_g) = $row_t;
	if($get_current_sub_category_translation_id == ""){
		echo"<p>Error could not find translation</p>";
		die;
	}


	
	// Unique hits
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);

	$ip_array = explode("\n", $get_current_food_unique_hits_ip_block);
	$ip_array_size = sizeof($ip_array);

	$has_seen_this_food_before = 0;

	for($x=0;$x<$ip_array_size;$x++){
		if($ip_array[$x] == "$inp_ip"){
			$has_seen_this_food_before = 1;
			break;
		}
		if($x > 5){
			break;
		}
	}
	
	if($has_seen_this_food_before == 0){
		$inp_food_unique_hits_ip_block = $inp_ip . "\n" . $get_current_food_unique_hits_ip_block;
		$inp_food_unique_hits_ip_block_mysql = quote_smart($link, $inp_food_unique_hits_ip_block);
		$inp_food_unique_hits = $get_current_food_unique_hits + 1;
		$result = mysqli_query($link, "UPDATE $t_food_index SET food_unique_hits=$inp_food_unique_hits, food_unique_hits_ip_block=$inp_food_unique_hits_ip_block_mysql WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
	}



	// Manufactor and food name
	if($get_current_food_manufacturer_name_and_food_name != "$get_current_food_manufacturer_name $get_current_food_name"){
		$inp_food_manufacturer_name_and_food_name = "$get_current_food_manufacturer_name $get_current_food_name";
		$inp_food_manufacturer_name_and_food_name_mysql = quote_smart($link, $inp_food_manufacturer_name_and_food_name);
		$result = mysqli_query($link, "UPDATE $t_food_index SET food_manufacturer_name_and_food_name=$inp_food_manufacturer_name_and_food_name_mysql WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
		
		echo"
		<div class=\"info\"><p>Updated food_manufacturer_name_and_food_name to $inp_food_manufacturer_name_and_food_name</p></div>
		";
	}

	// Restriction?
	$get_current_restriction_show_food = 1;
	$get_current_restriction_show_image_a = 1;
	$get_current_restriction_show_image_b = 1;
	$get_current_restriction_show_image_c = 1;
	$get_current_restriction_show_image_d = 1;
	$get_current_restriction_show_image_e = 1;
	$get_current_restriction_show_smileys = 1;
	if($get_current_food_age_restriction == "1"){
		// Check if I have accepted 
		$inp_ip_mysql = quote_smart($link, $my_ip);
		$query_t = "SELECT accepted_id, accepted_country FROM $t_food_age_restrictions_accepted WHERE accepted_ip=$inp_ip_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_accepted_id, $get_accepted_country) = $row_t;
		
		if($get_accepted_id == ""){
			// Accept age restriction
			$get_current_restriction_show_food = 0;
			include("view_food_show_age_restriction_warning.php");
		}
		else{
			// Can I see food and images?
			$country_mysql = quote_smart($link, $get_accepted_country);
			$query = "SELECT restriction_id, restriction_country_name, restriction_country_iso_two, restriction_country_flag_path_16x16, restriction_country_flag_16x16, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_show_food, restriction_show_image_a, restriction_show_image_b, restriction_show_image_c, restriction_show_image_d, restriction_show_image_e, restriction_show_smileys FROM $t_food_age_restrictions WHERE restriction_country_iso_two=$country_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_restriction_id, $get_current_restriction_country_name, $get_current_restriction_country_iso_two, $get_current_restriction_country_flag_path_16x16, $get_current_restriction_country_flag_16x16, $get_current_restriction_language, $get_current_restriction_age_limit, $get_current_restriction_title, $get_current_restriction_text, $get_current_restriction_show_food, $get_current_restriction_show_image_a, $get_current_restriction_show_image_b, $get_current_restriction_show_image_c, $get_current_restriction_show_image_d, $get_current_restriction_show_image_e, $get_current_restriction_show_smileys) = $row;

			if($get_current_restriction_id == ""){
				// Could not find country
				echo"<div class=\"error\"><p>Could not find country.</p></div>\n";
			}

			if($get_current_restriction_show_food == 0){
				echo"
				<h1 style=\"padding-bottom:0;margin-bottom:0;\">$get_current_food_manufacturer_name $get_current_food_name</h1>
				<p>$get_current_restriction_text</p>
				";
				
			}
		}
	}



	if($get_current_restriction_show_food == 1){
		if($process != "1"){
			echo"

			<!-- Headline, buttons -->
				<div class=\"food_float_left\">
					<h1>$get_current_food_manufacturer_name $get_current_food_name</h1>
				</div>
				<div class=\"food_float_right\">
		
					<!-- Food menu -->
						<p>
						<a href=\"$root/food/my_food.php?l=$l\" class=\"btn_default\">$l_my_food</a>
						<a href=\"$root/food/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
						<a href=\"$root/food/new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>
						</p>
					<!-- //Food menu -->
				</div>
				<div class=\"clear\"></div>
			<!-- //Headline, buttons -->


			<!-- Food Search -->
				<div class=\"food_search\">
					<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
					<p>
					<input type=\"text\" name=\"search_query\" id=\"nettport_inp_search_query\" value=\"\" size=\"10\" style=\"width: 50%;\"  />
					<input type=\"hidden\" name=\"l\" value=\"$l\" />
					<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" class=\"btn_default\" />
					</p>
					</form>
				</div>

				<!-- Search script -->
				<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
				\$(document).ready(function () {
					\$('#nettport_inp_search_query').keyup(function () {
       						// getting the value that user typed
       						var searchString    = $(\"#nettport_inp_search_query\").val();
 						// forming the queryString
      						var data            = 'l=$l&search_query='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {


           						// ajax call
            						\$.ajax({
                						type: \"GET\",
               							url: \"search_jquery.php\",
                						data: data,
								beforeSend: function(html) { // this happens before actual call
									\$(\"#nettport_search_results\").html(''); 
								},
               							success: function(html){
									\$(\"#nettport_search_results\").html(''); 
                    							\$(\"#nettport_search_results\").html(html);
              							}
            						});
       						}
        					return false;
           			 	});
         			   });
				</script>
				<!-- //Search script -->
			<!-- //Food Search -->


			<!-- Where am I? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$get_current_title_value</a>
				&gt;
				<a href=\"open_main_category.php?main_category_id=$get_current_main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>
				&gt;
				<a href=\"open_sub_category.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\">$get_current_sub_category_translation_value</a>
				&gt;
				<a href=\"view_food.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;l=$l\">$get_current_food_name</a>
				</p>
			<!-- //Where am I? -->
	
			<!-- Ad -->
				";
				include("$root/ad/_includes/ad_main_below_headline.php");
				echo"
			<!-- //Ad -->

			<!-- Images, width = 845 -->
				<p style=\"padding-bottom: 0;margin-bottom: 0;\">";


				// 845/4 = 211
				echo"
				<a id=\"image\"></a>
				";
				if(file_exists("../$get_current_food_image_path/$get_current_food_image_a") && $get_current_food_image_a != ""){
					echo"<a href=\"#image\" class=\"change_image_to_next_image\" data-currentimage=\"a\"><img src=\"../$get_current_food_image_path/$get_current_food_image_a\" alt=\"$get_current_food_image_a\" id=\"food_image\" /></a><br />\n";
				}
				
				if($get_current_food_image_a != "" && $get_current_restriction_show_image_a == 1){
					// Thumb medium
					if(!(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_medium")) OR $get_current_food_thumb_a_medium == ""){
						$ext = get_extension("$get_current_food_image_a");
						$inp_thumb_name = str_replace(".$ext", "", $get_current_food_image_a);
						$get_current_food_thumb_a_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
						$inp_food_thumb_a_medium_mysql = quote_smart($link, $get_current_food_thumb_a_medium);
						$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_medium=$inp_food_thumb_a_medium_mysql WHERE food_id=$get_current_food_id") or die(mysqli_error($link));
						resize_crop_image(200, 200, "$root/$get_current_food_image_path/$get_current_food_image_a", "$root/$get_current_food_image_path/$get_current_food_thumb_a_medium");
					}
					echo"<a href=\"#image\" class=\"change_image_to_image\" data-image=\"$root/$get_current_food_image_path/$get_current_food_image_a\" style=\"margin-right: 11px;\"><img src=\"$root/$get_current_food_image_path/$get_current_food_thumb_a_medium\" alt=\"$get_current_food_thumb_a_medium\" /></a>";
				}

				if($get_current_food_image_b != "" && $get_current_restriction_show_image_b == 1){
					// Thumb medium
					if(!(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_medium")) OR $get_current_food_thumb_b_medium == ""){
						$ext = get_extension("$get_current_food_image_b");
						$inp_thumb_name = str_replace(".$ext", "", $get_current_food_image_b);
						$get_current_food_thumb_b_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
						$inp_food_thumb_b_medium_mysql = quote_smart($link, $get_current_food_thumb_b_medium);
						$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_b_medium=$inp_food_thumb_b_medium_mysql WHERE food_id=$get_current_food_id") or die(mysqli_error($link));
				
						resize_crop_image(200, 200, "$root/$get_current_food_image_path/$get_current_food_image_b", "$root/$get_current_food_image_path/$get_current_food_thumb_b_medium");
					}
					echo"<a href=\"#image\" class=\"change_image_to_image\" data-image=\"$root/$get_current_food_image_path/$get_current_food_image_b\" style=\"margin-right: 11px;\"><img src=\"$root/$get_current_food_image_path/$get_current_food_thumb_b_medium\" alt=\"$get_current_food_thumb_b_medium\" /></a>";
				}


				if($get_current_food_image_c != "" && $get_current_restriction_show_image_c == 1){
					// Thumb medium
					if(!(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_medium")) OR $get_current_food_thumb_c_medium == ""){
						$ext = get_extension("$get_current_food_image_c");
						$inp_thumb_name = str_replace(".$ext", "", $get_current_food_image_c);
						$get_current_food_thumb_c_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
						$inp_food_thumb_c_medium_mysql = quote_smart($link, $get_current_food_thumb_c_medium);
						$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_c_medium=$inp_food_thumb_c_medium_mysql WHERE food_id=$get_current_food_id") or die(mysqli_error($link));
				
						resize_crop_image(200, 200, "$root/$get_current_food_image_path/$get_current_food_image_c", "$root/$get_current_food_image_path/$get_current_food_thumb_c_medium");
					}

					echo"<a href=\"#image\" class=\"change_image_to_image\" data-image=\"$root/$get_current_food_image_path/$get_current_food_image_c\" style=\"margin-right: 11px;\"><img src=\"$root/$get_current_food_image_path/$get_current_food_thumb_c_medium\" alt=\"$get_current_food_thumb_c_medium\" /></a>";
	
				}
				if($get_current_food_image_d != "" && $get_current_restriction_show_image_d == 1){
					if(!(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_medium")) OR $get_current_food_thumb_d_medium == ""){
						$ext = get_extension("$get_current_food_image_d");
						$inp_thumb_name = str_replace(".$ext", "", $get_current_food_image_d);
						$get_current_food_thumb_d_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
						$inp_food_thumb_d_medium_mysql = quote_smart($link, $get_current_food_thumb_d_medium);
						$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_d_medium=$inp_food_thumb_d_medium_mysql WHERE food_id=$get_current_food_id") or die(mysqli_error($link));
				
						resize_crop_image(200, 200, "$root/$get_current_food_image_path/$get_current_food_image_d", "$root/$get_current_food_image_path/$get_current_food_thumb_d_medium");
					}

					echo"<a href=\"#image\" class=\"change_image_to_image\" data-image=\"$root/$get_current_food_image_path/$get_current_food_image_d\" style=\"margin-right: 11px;\"><img src=\"$root/$get_current_food_image_path/$get_current_food_thumb_d_medium\" alt=\"$get_current_food_thumb_d_medium\" /></a>";
			
				}
				echo"
				</p>

				<!-- Change image script -->
				<script>
				\$(document).ready(function(){

					\$(\".change_image_to_image\").click(function(){
						// Get image we want to switch to
						var imageSrc = \$(this).attr(\"data-image\");
						\$(\"#food_image\").attr(\"src\", imageSrc); 
					});
					\$(\".change_image_to_next_image\").click(function(){
						// Get image we want to switch to
						var currentImageSrc = \$(\"#food_image\").attr(\"src\");
						console.log(currentImageSrc);
						";
						if($get_current_food_image_a != "" && $get_current_restriction_show_image_a == 1 && $get_current_food_image_b != "" && $get_current_restriction_show_image_b == 1){
							echo"
							if(currentImageSrc === '$root/$get_current_food_image_path/$get_current_food_image_a'){
								\$(\"#food_image\").attr(\"src\", '$root/$get_current_food_image_path/$get_current_food_image_b'); 
							}\n";
							if($get_current_food_image_c != "" && $get_current_restriction_show_image_c == 1){
								echo"
								if(currentImageSrc === '$root/$get_current_food_image_path/$get_current_food_image_b'){
									\$(\"#food_image\").attr(\"src\", '$root/$get_current_food_image_path/$get_current_food_image_c'); 
								}\n";
								if($get_current_food_image_d != "" && $get_current_restriction_show_image_d == 1){
									echo"
									if(currentImageSrc === '$root/$get_current_food_image_path/$get_current_food_image_c'){
										\$(\"#food_image\").attr(\"src\", '$root/$get_current_food_image_path/$get_current_food_image_d'); 
									}\n";
								}
								else{
									echo"
									if(currentImageSrc === '$root/$get_current_food_image_path/$get_current_food_image_c'){
										\$(\"#food_image\").attr(\"src\", '$root/$get_current_food_image_path/$get_current_food_image_a'); 
									}\n";
								}
							}
							else{
								echo"
								if(currentImageSrc === '$root/$get_current_food_image_path/$get_current_food_image_b'){
									\$(\"#food_image\").attr(\"src\", '$root/$get_current_food_image_path/$get_current_food_image_a'); 
								}\n";
							}
						}
						echo"
					});
				});
				</script>
				<!-- //Change image script -->
			<!-- //Images -->
	
			<!-- Favorite, edit, delete -->
				<div class=\"clear\"></div>
				<div style=\"float: left;padding: 1px 0px 0px 0px;\">
					<p style=\"margin:0;padding:0;\">
					$l_published_by <a href=\"$root/users/view_profile.php?user_id=$get_current_food_user_id&amp;l=$l\">$get_current_food_author_user_alias</a><br />
					</p>
				</div>
				<div style=\"float: left;padding: 3px 0px 0px 16px;\">
			
					<p style=\"margin: 0px;padding:0\">";
						if(isset($_SESSION['user_id'])){

							// My user
							$my_user_id = $_SESSION['user_id'];
							$my_user_id_mysql = output_html($my_user_id);
							$my_user_id_mysql = quote_smart($link, $my_user_id);
							$q = "SELECT user_id, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
							$r = mysqli_query($link, $q);
							$rowb = mysqli_fetch_row($r);
							list($get_my_user_id, $get_my_user_rank) = $rowb;

							// Favorite
							$q = "SELECT food_favorite_id FROM $t_food_favorites WHERE food_favorite_food_id=$get_current_food_id AND food_favorite_user_id=$my_user_id_mysql";
							$r = mysqli_query($link, $q);
							$rowb = mysqli_fetch_row($r);
							list($get_current_food_favorite_id) = $rowb;
							if($get_current_food_favorite_id == ""){
								echo"
								<a href=\"favorite_food_add.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/heart_grey.png\" alt=\"heart_grey.png\" /></a>
								";
							}
							else{
								echo"
								<a href=\"favorite_food_remove.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/heart_fill.png\" alt=\"heart_fill.png\" /></a>
								";
							}

							// edit, delte
							if($get_my_user_id == "$get_current_food_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
								echo"
								<a href=\"edit_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;l=$l\"><img src=\"_gfx/icons/edit.png\" alt=\"ic_mode_edit_black_18dp_1x.png\" /></a>
								<a href=\"delete_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;l=$l\"><img src=\"_gfx/icons/delete.png\" alt=\"ic_delete_black_18dp_1x.png\" /></a>
								";
							}
						}
						else{
							echo"
							<a href=\"$root/users/index.php?page=login&amp;l=$l&amp;refer=food/favorite_food_add.php?recipe_id=$get_current_food_id&amp;l=$l\"><img src=\"_gfx/icons/heart_grey.png\" alt=\"heart_grey.png\" /></a>
							";
						}
					echo"
					</p>
				</div>
				<div class=\"clear\"></div>
				<p style=\"margin-top: 0px;padding-top:0\">
				<img src=\"_gfx/icons/eye_dark_grey.png\" alt=\"eye.png\" /> $get_current_food_unique_hits $l_unique_views_lovercase
				</p>
			<!-- //Favorite, edit, delete -->
		
			<!-- About -->
				<p>
				$get_current_food_description

				<!-- Tags -->";

					$query = "SELECT tag_id, tag_title FROM $t_food_index_tags WHERE tag_food_id=$get_current_food_id ORDER BY tag_id ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_tag_id, $get_tag_title) = $row;
						echo"
						<a href=\"view_tag.php?tag=$get_tag_title&amp;l=$l\">#$get_tag_title</a>
						";
					}
					echo"
				<!-- //Tags -->

				</p>
			<!-- //About -->


			<!-- Money link -->";

				$query = "SELECT ad_id, ad_text FROM $t_food_index_ads WHERE ad_food_id='$get_current_food_id'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_ad_id, $get_ad_text) = $row;
				if($get_ad_id != ""){
					echo"
					$get_ad_text
					<div class=\"clear\"></div>
					";
				}
				echo"
			<!-- //Money link -->
	
			<!-- Numbers -->
				<a id=\"nutrition_facts\"></a>
				
				<div style=\"float: left;\">
					<h2>$l_nutrition_facts_headline</h2>
				</div>
				<div style=\"float: left;padding-left: 10px;\">
					<p>
					<a href=\"view_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;nutrition_facts_view_method=eu&amp;l=$l#nutrition_facts\"><img src=\"_gfx/flags/european_union"; if($nutrition_facts_view_method != "metric"){ echo"_50_percent"; } echo"_16x16.png\" alt=\"european_union_16x16.png\" title=\"$l_metric\" /></a>
					<a href=\"view_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;nutrition_facts_view_method=eu&amp;l=$l#nutrition_facts\""; if($nutrition_facts_view_method == "eu"){ echo" style=\"font-weight: bold;\""; } echo">$l_eu</a>
					&nbsp;
					<a href=\"view_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;nutrition_facts_view_method=us&amp;l=$l#nutrition_facts\"><img src=\"_gfx/flags/united_states"; if($nutrition_facts_view_method != "us"){ echo"_50_percent"; } echo"_16x16.png\" alt=\"united_states_16x16.png\" title=\"$l_us\" /></a>
					<a href=\"view_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;nutrition_facts_view_method=us&amp;l=$l#nutrition_facts\""; if($nutrition_facts_view_method == "us"){ echo" style=\"font-weight: bold;\""; } echo">$l_us</a>
					</p>
				</div>
				<div class=\"clear\"></div>
				";
				if($nutrition_facts_view_method == "eu"){
					include("view_food_include_numbers_eu.php");
				}
				else{
					include("view_food_include_numbers_us.php");
				}
				
				$get_current_sub_category_translation_value_lowercase = strtolower($get_current_sub_category_translation_value);
				$get_current_food_name_lowercase = strtolower($get_current_food_name);
				echo"
				<p>
				$l_we_take_the_average_of_all_the_foods_in_the_category $get_current_sub_category_translation_value_lowercase $l_to_calculate_the_median_lowercase.
				$l_by_doing_this_we_can_find_out_if $get_current_food_name_lowercase $l_has_more_the_same_or_less_calories_than_the_rest_of_the_food_in_lowercase $get_current_sub_category_translation_value_lowercase.<br />
				</p>
				<ul>
					<li><span style=\"color:red;\">$l_red_color_means_it_has_more.</span></li>
					<li><span>$l_black_color_means_it_has_the_same_as_the_median.</span></li>
					<li><span style=\"color:green;\">$l_finally_green_color_means_it_has_less.</span></li>
				</ul>
				";
				include("view_food_include_score.php");
				
				echo"
			<!-- //Numbers -->


			<!-- Info -->
				<h2>$l_info</h2>

		<table class=\"hor-zebra\" style=\"width: auto;min-width: 0;display: table;\">
		 <tbody>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span><b>$l_manufacturer:</b></span>
		   </td>
		   <td style=\"padding: 0px 4px 0px 4px;\">
			<span><a href=\"search.php?manufacturer_name=$get_current_food_manufacturer_name&amp;l=$l\">$get_current_food_manufacturer_name</a></span>
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span><b>$l_barcode:</b></span>
		   </td>
		   <td style=\"padding: 0px 4px 0px 4px;\">
			<span><a href=\"search.php?barcode=$get_current_food_barcode&amp;l=$l\">$get_current_food_barcode</a></span>
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span><b>$l_net_content:</b></span>
		   </td>
		   <td style=\"padding: 0px 4px 0px 4px;\">
			<span>$get_current_food_net_content_metric $get_current_food_net_content_measurement_metric</span>
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 20px;\">
			<span><b>$l_us:</b></span>
		   </td>
		   <td style=\"padding: 0px 4px 0px 4px;\">
			<span>$get_current_food_net_content_us $get_current_food_net_content_measurement_us</span>
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span><b>$l_stores:</b></span>
		   </td>
		   <td style=\"padding: 0px 4px 0px 4px;\">
			<span>";
			
			// Count stores
			$query = "SELECT count(food_store_id) FROM $t_food_index_stores  WHERE food_store_food_id=$get_current_food_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_food_stores) = $row;
	
			$x = 0;
			$count_minus_two = $get_count_food_stores-2;

			$query = "SELECT food_store_id, food_store_store_id, food_store_store_name FROM $t_food_index_stores WHERE food_store_food_id=$get_current_food_id ORDER BY food_store_store_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_current_food_store_id, $get_current_food_store_store_id, $get_current_food_store_store_name) = $row;
				echo"
				<a href=\"search.php?q=&amp;barcode=&amp;manufacturer_name=&amp;store_id=$get_current_food_store_store_id&amp;order_by=food_score&amp;order_method=asc&amp;l=$l\">$get_current_food_store_store_name</a>";
				
				// Check if I have prices
				$query_p = "SELECT food_price_id, food_price_food_id, food_price_store_id, food_price_store_name, food_price_price, food_price_currency, food_price_offer, food_price_offer_valid_from, food_price_offer_valid_to, food_price_user_id, food_price_user_ip, food_price_added_datetime, food_price_added_datetime_print, food_price_updated, food_price_updated_print, food_price_reported, food_price_reported_checked FROM $t_food_index_prices WHERE food_price_food_id=$get_current_food_id AND food_price_store_id=$get_current_food_store_store_id";
				$result_p = mysqli_query($link, $query_p);
				$row_p = mysqli_fetch_row($result_p);
				list($get_current_food_price_id, $get_current_food_price_food_id, $get_current_food_price_store_id, $get_current_food_price_store_name, $get_current_food_price_price, $get_current_food_price_currency, $get_current_food_price_offer, $get_current_food_price_offer_valid_from, $get_current_food_price_offer_valid_to, $get_current_food_price_user_id, $get_current_food_price_user_ip, $get_current_food_price_added_datetime, $get_current_food_price_added_datetime_print, $get_current_food_price_updated, $get_current_food_price_updated_print, $get_current_food_price_reported, $get_current_food_price_reported_checked) = $row_p;
				if($get_current_food_price_id == ""){
					echo"
					
					";
				}
				else{
					echo"
					<span>($get_current_food_price_price)</span>
					";
				}


				if($x < $count_minus_two){
					echo", ";
				}
				elseif($x == $count_minus_two){
					echo" $l_and_lowercase ";
				}

				$x++;
			}
			echo"</span>
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span><b>$l_last_viewed:</b></span>
		   </td>
		   <td style=\"padding: 0px 4px 0px 4px;\">
			<span>$get_current_food_last_viewed</span>
		   </td>
		  </tr>
		 </tbody>
		</table>

			<!-- //Info -->

			<!-- Text -->
				$get_current_food_text
			<!-- //Text -->

			<div class=\"clear\" style=\"height: 20px;\"></div>

			";
		} // process != 1


		// New comment and read comments
		if($process != "1"){
			echo"
			<!-- Ratings -->
				<a id=\"ratings\"></a>

				<!-- Feedback -->
					";
					if(isset($_GET['ft_rating']) && isset($_GET['fm_rating'])){
						$ft_rating = $_GET['ft_rating'];
						$ft_rating = output_html($ft_rating);
						$fm_rating = $_GET['fm_rating'];
						$fm_rating = output_html($fm_rating);
						$fm_rating = str_replace("_", " ", $fm_rating);
						$fm_rating = ucfirst($fm_rating);
						echo"<div class=\"$ft_rating\"><span>$fm_rating</span></div>";
					}
					echo"	
				<!-- //Feedback -->
			";
		}
		include("view_food_include_new_rating.php");
		include("view_food_include_fetch_ratings.php");
		echo"
			<!-- //Ratings -->
		";

	} // can view food
}
/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>