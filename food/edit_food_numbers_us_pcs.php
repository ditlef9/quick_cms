<?php
/**
*
* File: food/edit_food_numbers_us_pcs.php
* Version 1.0.0
* Date 13:03 03.02.2018
* Copyright (c) 2008-2021 Sindre Andre Ditlefsen
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


/*- Translations ---------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/food/ts_edit_food_numbers_x.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['open_main_category_id'])){
	$open_main_category_id= $_GET['open_main_category_id'];
	$open_main_category_id = strip_tags(stripslashes($open_main_category_id));
}
else{
	$open_main_category_id = "";
}
if(isset($_GET['sub_category_id'])){
	$sub_category_id= $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}

if(isset($_GET['food_id'])){
	$food_id = $_GET['food_id'];
	$food_id = strip_tags(stripslashes($food_id));
}
else{
	$food_id = "";
}


$food_id_mysql = quote_smart($link, $food_id);

if(isset($_GET['autosubmit_form'])){
	$autosubmit_form = $_GET['autosubmit_form'];
	$autosubmit_form = strip_tags(stripslashes($autosubmit_form));
	if(!(is_numeric($autosubmit_form))){
		echo"autosubmit_form not numeric";
		die;
	}
}
else{
	$autosubmit_form = "";
}

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;


// Select food
$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$food_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_food_id, $get_current_food_user_id, $get_current_food_name, $get_current_food_clean_name, $get_current_food_manufacturer_name, $get_current_food_manufacturer_name_and_food_name, $get_current_food_description, $get_current_food_text, $get_current_food_country, $get_current_food_net_content_metric, $get_current_food_net_content_measurement_metric, $get_current_food_net_content_us, $get_current_food_net_content_measurement_us, $get_current_food_net_content_added_measurement, $get_current_food_serving_size_metric, $get_current_food_serving_size_measurement_metric, $get_current_food_serving_size_us, $get_current_food_serving_size_measurement_us, $get_current_food_serving_size_added_measurement, $get_current_food_serving_size_pcs, $get_current_food_serving_size_pcs_measurement, $get_current_food_numbers_entered_method, $get_current_food_energy_metric, $get_current_food_fat_metric, $get_current_food_saturated_fat_metric, $get_current_food_trans_fat_metric, $get_current_food_monounsaturated_fat_metric, $get_current_food_polyunsaturated_fat_metric, $get_current_food_cholesterol_metric, $get_current_food_carbohydrates_metric, $get_current_food_carbohydrates_of_which_sugars_metric, $get_current_food_added_sugars_metric, $get_current_food_dietary_fiber_metric, $get_current_food_proteins_metric, $get_current_food_salt_metric, $get_current_food_sodium_metric, $get_current_food_energy_us, $get_current_food_fat_us, $get_current_food_saturated_fat_us, $get_current_food_trans_fat_us, $get_current_food_monounsaturated_fat_us, $get_current_food_polyunsaturated_fat_us, $get_current_food_cholesterol_us, $get_current_food_carbohydrates_us, $get_current_food_carbohydrates_of_which_sugars_us, $get_current_food_added_sugars_us, $get_current_food_dietary_fiber_us, $get_current_food_proteins_us, $get_current_food_salt_us, $get_current_food_sodium_us, $get_current_food_score, $get_current_food_score_place_in_sub_category, $get_current_food_energy_calculated_metric, $get_current_food_fat_calculated_metric, $get_current_food_saturated_fat_calculated_metric, $get_current_food_trans_fat_calculated_metric, $get_current_food_monounsaturated_fat_calculated_metric, $get_current_food_polyunsaturated_fat_calculated_metric, $get_current_food_cholesterol_calculated_metric, $get_current_food_carbohydrates_calculated_metric, $get_current_food_carbohydrates_of_which_sugars_calculated_metric, $get_current_food_added_sugars_calculated_metric, $get_current_food_dietary_fiber_calculated_metric, $get_current_food_proteins_calculated_metric, $get_current_food_salt_calculated_metric, $get_current_food_sodium_calculated_metric, $get_current_food_energy_calculated_us, $get_current_food_fat_calculated_us, $get_current_food_saturated_fat_calculated_us, $get_current_food_trans_fat_calculated_us, $get_current_food_monounsaturated_fat_calculated_us, $get_current_food_polyunsaturated_fat_calculated_us, $get_current_food_cholesterol_calculated_us, $get_current_food_carbohydrates_calculated_us, $get_current_food_carbohydrates_of_which_sugars_calculated_us, $get_current_food_added_sugars_calculated_us, $get_current_food_dietary_fiber_calculated_us, $get_current_food_proteins_calculated_us, $get_current_food_salt_calculated_us, $get_current_food_sodium_calculated_us, $get_current_food_energy_net_content, $get_current_food_fat_net_content, $get_current_food_saturated_fat_net_content, $get_current_food_trans_fat_net_content, $get_current_food_monounsaturated_fat_net_content, $get_current_food_polyunsaturated_fat_net_content, $get_current_food_cholesterol_net_content, $get_current_food_carbohydrates_net_content, $get_current_food_carbohydrates_of_which_sugars_net_content, $get_current_food_added_sugars_net_content, $get_current_food_dietary_fiber_net_content, $get_current_food_proteins_net_content, $get_current_food_salt_net_content, $get_current_food_sodium_net_content, $get_current_food_barcode, $get_current_food_main_category_id, $get_current_food_sub_category_id, $get_current_food_image_path, $get_current_food_image_a, $get_current_food_thumb_a_small, $get_current_food_thumb_a_medium, $get_current_food_thumb_a_large, $get_current_food_image_b, $get_current_food_thumb_b_small, $get_current_food_thumb_b_medium, $get_current_food_thumb_b_large, $get_current_food_image_c, $get_current_food_thumb_c_small, $get_current_food_thumb_c_medium, $get_current_food_thumb_c_large, $get_current_food_image_d, $get_current_food_thumb_d_small, $get_current_food_thumb_d_medium, $get_current_food_thumb_d_large, $get_current_food_image_e, $get_current_food_thumb_e_small, $get_current_food_thumb_e_medium, $get_current_food_thumb_e_large, $get_current_food_last_used, $get_current_food_language, $get_current_food_no_of_comments, $get_current_food_stars, $get_current_food_comments_multiplied_stars, $get_current_food_synchronized, $get_current_food_accepted_as_master, $get_current_food_notes, $get_current_food_unique_hits, $get_current_food_unique_hits_ip_block, $get_current_food_user_ip, $get_current_food_created_date, $get_current_food_last_viewed, $get_current_food_age_restriction) = $row;

if($get_current_food_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $get_current_title_value";
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


	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_food_name $get_current_food_manufacturer_name - $get_current_title_value";
	if($autosubmit_form == "1"){
		echo"<!DOCTYPE html>\n";
		echo"<html lang=\"$l\">\n";
		echo"<head>\n";
		echo"	<title>Auto</title>\n";

		echo"	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
		echo"	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>\n";
		echo"	<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/jquery/jquery.min.js\"></script>\n";
		echo"	</head>\n";
		echo"<body>";
	}
	else{
		include("$root/_webdesign/header.php");
	}


	// Autosubmit form
	if($get_current_food_cholesterol_metric == ""){
		$autosubmit_form = "1";
	}



	// Get sub category
	$query = "SELECT sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_age_limit FROM $t_food_categories_sub WHERE sub_category_id=$get_current_food_sub_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_name, $get_current_sub_category_parent_id, $get_current_sub_category_symbolic_link_to_category_id, $get_current_sub_category_age_limit) = $row;
	// Get main category
	$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit FROM $t_food_categories_main WHERE main_category_id=$get_current_sub_category_parent_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit) = $row;


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


	// My user
	if(isset($_SESSION['user_id'])){
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		if(!(is_numeric($my_user_id))){
			echo"User id not numeric";
			die;
		}
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		
		// Fetch my user
		$query = "SELECT user_id, user_email, user_name, user_password, user_salt, user_security, user_language, user_rank, user_verified_by_moderator, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_password, $get_my_user_salt, $get_my_user_security, $get_my_user_language, $get_my_user_rank, $get_my_user_verified_by_moderator, $get_my_user_login_tries) = $row;

		$access = 0;
		if($get_current_food_user_id == "$my_user_id"){
			$access = 1;
		}
		else{
			if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				$access = 1;
			}
		}
		if($access == 0){
			echo"
			<p>Access denied.</p>
			";
		}
		elseif($access == 1){
			if($process == 1){
				$inp_food_energy_calculated = $_POST['inp_food_energy_calculated'];
				$inp_food_energy_calculated = output_html($inp_food_energy_calculated);
				$inp_food_energy_calculated = str_replace(",", ".", $inp_food_energy_calculated);
				$inp_food_energy_calculated_metric_mysql = quote_smart($link, $inp_food_energy_calculated);

				$inp_food_fat_calculated = $_POST['inp_food_fat_calculated'];
				$inp_food_fat_calculated = output_html($inp_food_fat_calculated);
				$inp_food_fat_calculated = str_replace(",", ".", $inp_food_fat_calculated);
				$inp_food_fat_calculated_metric_mysql = quote_smart($link, $inp_food_fat_calculated);

				$inp_food_saturated_fat_calculated = $_POST['inp_food_saturated_fat_calculated'];
				$inp_food_saturated_fat_calculated = output_html($inp_food_saturated_fat_calculated);
				$inp_food_saturated_fat_calculated = str_replace(",", ".", $inp_food_saturated_fat_calculated);
				$inp_food_saturated_fat_calculated_metric_mysql = quote_smart($link, $inp_food_saturated_fat_calculated);

				$inp_food_trans_fat_calculated = $_POST['inp_food_trans_fat_calculated'];
				$inp_food_trans_fat_calculated = output_html($inp_food_trans_fat_calculated);
				$inp_food_trans_fat_calculated = str_replace(",", ".", $inp_food_trans_fat_calculated);
				$inp_food_trans_fat_calculated_metric_mysql = quote_smart($link, $inp_food_trans_fat_calculated);

				$inp_food_monounsaturated_fat_calculated = $_POST['inp_food_monounsaturated_fat_calculated'];
				$inp_food_monounsaturated_fat_calculated = output_html($inp_food_monounsaturated_fat_calculated);
				$inp_food_monounsaturated_fat_calculated = str_replace(",", ".", $inp_food_monounsaturated_fat_calculated);
				$inp_food_monounsaturated_fat_calculated_metric_mysql = quote_smart($link, $inp_food_monounsaturated_fat_calculated);

				$inp_food_polyunsaturated_fat_calculated = $_POST['inp_food_polyunsaturated_fat_calculated'];
				$inp_food_polyunsaturated_fat_calculated = output_html($inp_food_polyunsaturated_fat_calculated);
				$inp_food_polyunsaturated_fat_calculated = str_replace(",", ".", $inp_food_polyunsaturated_fat_calculated);
				$inp_food_polyunsaturated_fat_calculated_metric_mysql = quote_smart($link, $inp_food_polyunsaturated_fat_calculated);

				$inp_food_carbohydrates_calculated = $_POST['inp_food_carbohydrates_calculated'];
				$inp_food_carbohydrates_calculated = output_html($inp_food_carbohydrates_calculated);
				$inp_food_carbohydrates_calculated = str_replace(",", ".", $inp_food_carbohydrates_calculated);
				$inp_food_carbohydrates_calculated_metric_mysql = quote_smart($link, $inp_food_carbohydrates_calculated);

				$inp_food_dietary_fiber_calculated = $_POST['inp_food_dietary_fiber_calculated'];
				$inp_food_dietary_fiber_calculated = output_html($inp_food_dietary_fiber_calculated);
				$inp_food_dietary_fiber_calculated = str_replace(",", ".", $inp_food_dietary_fiber_calculated);
				$inp_food_dietary_fiber_calculated_metric_mysql = quote_smart($link, $inp_food_dietary_fiber_calculated);

				$inp_food_carbohydrates_of_which_sugars_calculated = $_POST['inp_food_carbohydrates_of_which_sugars_calculated'];
				$inp_food_carbohydrates_of_which_sugars_calculated = output_html($inp_food_carbohydrates_of_which_sugars_calculated);
				$inp_food_carbohydrates_of_which_sugars_calculated = str_replace(",", ".", $inp_food_carbohydrates_of_which_sugars_calculated);
				$inp_food_carbohydrates_of_which_sugars_calculated_metric_mysql = quote_smart($link, $inp_food_carbohydrates_of_which_sugars_calculated);

				$inp_food_added_sugars_calculated = $_POST['inp_food_added_sugars_calculated'];
				$inp_food_added_sugars_calculated = output_html($inp_food_added_sugars_calculated);
				$inp_food_added_sugars_calculated = str_replace(",", ".", $inp_food_added_sugars_calculated);
				$inp_food_added_sugars_calculated_metric_mysql = quote_smart($link, $inp_food_added_sugars_calculated);

				$inp_food_proteins_calculated = $_POST['inp_food_proteins_calculated'];
				$inp_food_proteins_calculated = output_html($inp_food_proteins_calculated);
				$inp_food_proteins_calculated = str_replace(",", ".", $inp_food_proteins_calculated);
				$inp_food_proteins_calculated_metric_mysql = quote_smart($link, $inp_food_proteins_calculated);

				$inp_food_sodium_calculated = $_POST['inp_food_sodium_calculated'];
				$inp_food_sodium_calculated = output_html($inp_food_sodium_calculated);
				$inp_food_sodium_calculated = str_replace(",", ".", $inp_food_sodium_calculated);
				$inp_food_sodium_calculated_metric_mysql = quote_smart($link, $inp_food_sodium_calculated);
	
				// Salt is sodium + 60 %
				$inp_food_sodium_calculated_gram = $inp_food_sodium_calculated/1000;
				$inp_food_salt_calculated = $inp_food_sodium_calculated_gram*1.6;
				$inp_food_salt_calculated_metric_mysql = quote_smart($link, $inp_food_salt_calculated);

				$inp_food_cholesterol_calculated = $_POST['inp_food_cholesterol_calculated'];
				$inp_food_cholesterol_calculated = output_html($inp_food_cholesterol_calculated);
				$inp_food_cholesterol_calculated = str_replace(",", ".", $inp_food_cholesterol_calculated);
				$inp_food_cholesterol_calculated_metric_mysql = quote_smart($link, $inp_food_cholesterol_calculated);

				// Caulcate metric 100 
				$inp_food_energy = round($inp_food_energy_calculated/$get_current_food_serving_size_metric*100, 0);
				$inp_food_energy_metric_mysql = quote_smart($link, $inp_food_energy);
	
				$inp_food_fat = round($inp_food_fat_calculated/$get_current_food_serving_size_metric*100, 1);
				$inp_food_fat_metric_mysql = quote_smart($link, $inp_food_fat);

				$inp_food_saturated_fat = round($inp_food_saturated_fat_calculated/$get_current_food_serving_size_metric*100, 1);
				$inp_food_saturated_fat_metric_mysql = quote_smart($link, $inp_food_saturated_fat);

				$inp_food_trans_fat = round($inp_food_trans_fat_calculated/$get_current_food_serving_size_metric*100, 1);
				$inp_food_trans_fat_metric_mysql = quote_smart($link, $inp_food_trans_fat);
	
				$inp_food_monounsaturated_fat = round($inp_food_monounsaturated_fat_calculated/$get_current_food_serving_size_metric*100, 1);
				$inp_food_monounsaturated_fat_metric_mysql = quote_smart($link, $inp_food_monounsaturated_fat);
	
				$inp_food_polyunsaturated_fat = round($inp_food_polyunsaturated_fat_calculated/$get_current_food_serving_size_metric*100, 1);
				$inp_food_polyunsaturated_fat_metric_mysql = quote_smart($link, $inp_food_polyunsaturated_fat);

				$inp_food_carbohydrates = round($inp_food_carbohydrates_calculated/$get_current_food_serving_size_metric*100, 0);
				$inp_food_carbohydrates_metric_mysql = quote_smart($link, $inp_food_carbohydrates);

				$inp_food_dietary_fiber= round($inp_food_dietary_fiber_calculated/$get_current_food_serving_size_metric*100, 0);
				$inp_food_dietary_fiber_metric_mysql = quote_smart($link, $inp_food_dietary_fiber);

				$inp_food_carbohydrates_of_which_sugars = round($inp_food_carbohydrates_of_which_sugars_calculated/$get_current_food_serving_size_metric*100, 0);
				$inp_food_carbohydrates_of_which_sugars_metric_mysql = quote_smart($link, $inp_food_carbohydrates_of_which_sugars);

				$inp_food_added_sugars = round($inp_food_added_sugars_calculated/$get_current_food_serving_size_metric*100, 0);
				$inp_food_added_sugars_metric_mysql = quote_smart($link, $inp_food_added_sugars);

				$inp_food_proteins = round($inp_food_proteins_calculated/$get_current_food_serving_size_metric*100, 0);
				$inp_food_proteins_metric_mysql = quote_smart($link, $inp_food_proteins);

				$inp_food_salt = round($inp_food_salt_calculated/$get_current_food_serving_size_metric*100, 2);
				$inp_food_salt_metric_mysql = quote_smart($link, $inp_food_salt);

				$inp_food_sodium = round($inp_food_sodium_calculated/$get_current_food_serving_size_metric*100, 0);
				$inp_food_sodium_metric_mysql = quote_smart($link, $inp_food_sodium);

				$inp_food_cholesterol = round($inp_food_cholesterol_calculated/$get_current_food_serving_size_metric*100, 0);
				$inp_food_cholesterol_metric_mysql = quote_smart($link, $inp_food_cholesterol);
	

				// per 8 US System
				$inp_food_energy_us = round(($inp_food_energy_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_energy_us_mysql = quote_smart($link, $inp_food_energy_us);

				$inp_food_fat_us = round(($inp_food_fat_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_fat_us_mysql = quote_smart($link, $inp_food_fat_us);

				$inp_food_saturated_fat_us = round(($inp_food_saturated_fat_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_saturated_fat_us_mysql = quote_smart($link, $inp_food_saturated_fat_us);

				$inp_food_trans_fat_us = round(($inp_food_trans_fat_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_trans_fat_us_mysql = quote_smart($link, $inp_food_trans_fat_us);

				$inp_food_monounsaturated_fat_us = round(($inp_food_monounsaturated_fat_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_monounsaturated_fat_us_mysql = quote_smart($link, $inp_food_monounsaturated_fat_us);

				$inp_food_polyunsaturated_fat_us = round(($inp_food_polyunsaturated_fat_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_polyunsaturated_fat_us_mysql = quote_smart($link, $inp_food_polyunsaturated_fat_us);

				$inp_food_carbohydrates_us  = round(($inp_food_carbohydrates_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_carbohydrates_us_mysql = quote_smart($link, $inp_food_carbohydrates_us);

				$inp_food_dietary_fiber_us = round(($inp_food_dietary_fiber_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_dietary_fiber_us_mysql = quote_smart($link, $inp_food_dietary_fiber_us);

				$inp_food_carbohydrates_of_which_sugars_us = round(($inp_food_carbohydrates_of_which_sugars_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_carbohydrates_of_which_sugars_us_mysql = quote_smart($link, $inp_food_carbohydrates_of_which_sugars_us);

				$inp_food_added_sugars_us = round(($inp_food_added_sugars_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_added_sugars_us_mysql = quote_smart($link, $inp_food_added_sugars_us);

				$inp_food_proteins_us = round(($inp_food_proteins_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_proteins_us_mysql = quote_smart($link, $inp_food_proteins_us);

				$inp_food_salt_us = round(($inp_food_salt_calculated/$get_current_food_serving_size_us)*8, 2);
				$inp_food_salt_us_mysql = quote_smart($link, $inp_food_salt_us);

				$inp_food_sodium_us = round(($inp_food_sodium_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_sodium_us_mysql = quote_smart($link, $inp_food_sodium_us);

				$inp_food_cholesterol_us = round(($inp_food_cholesterol_calculated/$get_current_food_serving_size_us)*8, 0);
				$inp_food_cholesterol_us_mysql = quote_smart($link, $inp_food_cholesterol_us);


				// Calculate net content
				$inp_food_energy_net_content	   = round(($get_current_food_net_content_metric*$inp_food_energy)/100, 0);
				$inp_food_energy_net_content_mysql = quote_smart($link, $inp_food_energy_net_content);

				$inp_food_fat_net_content 	= round(($get_current_food_net_content_metric*$inp_food_fat)/100, 1);
				$inp_food_fat_net_content_mysql = quote_smart($link, $inp_food_fat_net_content);

  				$inp_food_saturated_fat_net_content 	  = round(($get_current_food_net_content_metric*$inp_food_saturated_fat)/100, 1);
				$inp_food_saturated_fat_net_content_mysql = quote_smart($link, $inp_food_saturated_fat_net_content);

  				$inp_food_trans_fat_net_content 	= round(($get_current_food_net_content_metric*$inp_food_trans_fat)/100, 1);
				$inp_food_trans_fat_net_content_mysql 	= quote_smart($link, $inp_food_trans_fat_net_content);

  				$inp_food_monounsaturated_fat_net_content	= round(($get_current_food_net_content_metric*$inp_food_monounsaturated_fat)/100, 1);
				$inp_food_monounsaturated_fat_net_content_mysql = quote_smart($link, $inp_food_monounsaturated_fat_net_content);

  				$inp_food_polyunsaturated_fat_net_content 	= round(($get_current_food_net_content_metric*$inp_food_polyunsaturated_fat)/100, 1);
				$inp_food_polyunsaturated_fat_net_content_mysql = quote_smart($link, $inp_food_polyunsaturated_fat_net_content);

  				$inp_food_cholesterol_net_content	= round(($get_current_food_net_content_metric*$inp_food_cholesterol)/100, 1);
				$inp_food_cholesterol_net_content_mysql = quote_smart($link, $inp_food_cholesterol_net_content);

 				$inp_food_carbohydrates_net_content 	  = round(($get_current_food_net_content_metric*$inp_food_carbohydrates)/100, 1);
				$inp_food_carbohydrates_net_content_mysql = quote_smart($link, $inp_food_carbohydrates_net_content);

  				$inp_food_carbohydrates_of_which_sugars_net_content 	  = round(($get_current_food_net_content_metric*$inp_food_carbohydrates_of_which_sugars)/100, 1);
				$inp_food_carbohydrates_of_which_sugars_net_content_mysql = quote_smart($link, $inp_food_carbohydrates_of_which_sugars_net_content);

  				$inp_food_added_sugars_net_content		= round(($get_current_food_net_content_metric*$inp_food_added_sugars)/100, 1);
				$inp_food_added_sugars_net_content_mysql	= quote_smart($link, $inp_food_added_sugars_net_content);

  				$inp_food_dietary_fiber_net_content 		= round(($get_current_food_net_content_metric*$inp_food_dietary_fiber)/100, 1);
				$inp_food_dietary_fiber_net_content_mysql 	= quote_smart($link, $inp_food_dietary_fiber_net_content);

 				$inp_food_proteins_net_content       = round(($get_current_food_net_content_metric*$inp_food_proteins)/100, 1);
				$inp_food_proteins_net_content_mysql = quote_smart($link, $inp_food_proteins_net_content);

  				$inp_food_salt_net_content       = round(($get_current_food_net_content_metric*$inp_food_salt)/100, 1);
				$inp_food_salt_net_content_mysql = quote_smart($link, $inp_food_salt_net_content);

  				$inp_food_sodium_net_content       = round(($get_current_food_net_content_metric*$inp_food_sodium)/100, 0);
				$inp_food_sodium_net_content_mysql = quote_smart($link, $inp_food_sodium_net_content);


				// Score
				$inp_total = $inp_food_energy + 
						$inp_food_fat + $inp_food_saturated_fat + $inp_food_trans_fat + $inp_food_monounsaturated_fat + $inp_food_polyunsaturated_fat + 
						$inp_food_carbohydrates + $inp_food_carbohydrates_of_which_sugars + $inp_food_dietary_fiber +
						$inp_food_proteins + 
						$inp_food_salt;
				$inp_calculation = ($inp_food_energy * 1) + 
					     	   ($inp_food_fat * 13) +  
					     	   ($inp_food_saturated_fat * 1) + 
				 	    	   ($inp_food_carbohydrates * 44) +
				  	   	   ($inp_food_dietary_fiber * 1) +
				  	   	   ($inp_food_carbohydrates_of_which_sugars * 1) +
				  	   	   ($inp_food_proteins * 43) +
				  	   	   ($inp_food_salt * 1);

				if($inp_total == "0"){
					$inp_score = "0";
				}
				else{
					$inp_score = round($inp_calculation / $inp_total, 0);
				}
				$inp_score_mysql = quote_smart($link, $inp_score);


				// Update food_id
				$result = mysqli_query($link, "UPDATE $t_food_index SET 

								food_numbers_entered_method='us_pcs', 
								food_energy_metric=$inp_food_energy_metric_mysql, 
								food_fat_metric=$inp_food_fat_metric_mysql, 
								food_saturated_fat_metric=$inp_food_saturated_fat_metric_mysql, 
								food_trans_fat_metric=$inp_food_trans_fat_metric_mysql, 
								food_monounsaturated_fat_metric=$inp_food_monounsaturated_fat_metric_mysql, 
								food_polyunsaturated_fat_metric=$inp_food_polyunsaturated_fat_metric_mysql, 
								food_cholesterol_metric=$inp_food_cholesterol_metric_mysql, 
								food_carbohydrates_metric=$inp_food_carbohydrates_metric_mysql, 
								food_dietary_fiber_metric=$inp_food_dietary_fiber_metric_mysql, 
								food_carbohydrates_of_which_sugars_metric=$inp_food_carbohydrates_of_which_sugars_metric_mysql, 
								food_added_sugars_metric=$inp_food_added_sugars_metric_mysql, 
								food_proteins_metric=$inp_food_proteins_metric_mysql, 
								food_salt_metric=$inp_food_salt_metric_mysql, 
								food_sodium_metric=$inp_food_sodium_metric_mysql, 

								food_energy_us=$inp_food_energy_us_mysql, 
								food_fat_us=$inp_food_fat_us_mysql, 
								food_saturated_fat_us=$inp_food_saturated_fat_us_mysql, 
								food_trans_fat_us=$inp_food_trans_fat_us_mysql, 
								food_monounsaturated_fat_us=$inp_food_monounsaturated_fat_us_mysql, 
								food_polyunsaturated_fat_us=$inp_food_polyunsaturated_fat_us_mysql, 
								food_cholesterol_us=$inp_food_cholesterol_us_mysql, 
								food_carbohydrates_us=$inp_food_carbohydrates_us_mysql, 
								food_dietary_fiber_us=$inp_food_dietary_fiber_us_mysql, 
								food_carbohydrates_us=$inp_food_carbohydrates_us_mysql, 
								food_dietary_fiber_us=$inp_food_dietary_fiber_us_mysql, 
								food_carbohydrates_of_which_sugars_us=$inp_food_carbohydrates_of_which_sugars_us_mysql, 
								food_added_sugars_us=$inp_food_added_sugars_us_mysql, 
								food_proteins_us=$inp_food_proteins_us_mysql, 
								food_salt_us=$inp_food_salt_us_mysql, 
								food_sodium_us=$inp_food_sodium_us_mysql, 

								food_score=$inp_score_mysql, 

								food_energy_calculated_metric=$inp_food_energy_calculated_metric_mysql, 
								food_fat_calculated_metric=$inp_food_fat_calculated_metric_mysql,
								food_saturated_fat_calculated_metric=$inp_food_saturated_fat_calculated_metric_mysql,
								food_trans_fat_calculated_metric=$inp_food_trans_fat_calculated_metric_mysql,
								food_monounsaturated_fat_calculated_metric=$inp_food_monounsaturated_fat_calculated_metric_mysql,
								food_polyunsaturated_fat_calculated_metric=$inp_food_polyunsaturated_fat_calculated_metric_mysql,
								food_cholesterol_calculated_metric=$inp_food_cholesterol_calculated_metric_mysql,
								food_carbohydrates_calculated_metric=$inp_food_carbohydrates_calculated_metric_mysql,
								food_dietary_fiber_calculated_metric=$inp_food_dietary_fiber_calculated_metric_mysql, 
								food_carbohydrates_of_which_sugars_calculated_metric=$inp_food_carbohydrates_of_which_sugars_calculated_metric_mysql,
								food_added_sugars_calculated_metric=$inp_food_added_sugars_calculated_metric_mysql,
								food_proteins_calculated_metric=$inp_food_proteins_calculated_metric_mysql,
								food_salt_calculated_metric=$inp_food_salt_calculated_metric_mysql,
								food_sodium_calculated_metric=$inp_food_sodium_calculated_metric_mysql,

								food_energy_calculated_us=$inp_food_energy_calculated_metric_mysql, 
								food_fat_calculated_us=$inp_food_fat_calculated_metric_mysql, 
								food_saturated_fat_calculated_us=$inp_food_saturated_fat_calculated_metric_mysql, 
								food_trans_fat_calculated_us=$inp_food_trans_fat_calculated_metric_mysql, 
								food_monounsaturated_fat_calculated_us=$inp_food_monounsaturated_fat_calculated_metric_mysql, 
								food_polyunsaturated_fat_calculated_us=$inp_food_polyunsaturated_fat_calculated_metric_mysql, 
								food_cholesterol_calculated_us=$inp_food_cholesterol_calculated_metric_mysql, 
								food_carbohydrates_calculated_us=$inp_food_carbohydrates_calculated_metric_mysql, 
								food_dietary_fiber_calculated_us=$inp_food_dietary_fiber_calculated_metric_mysql, 
								food_carbohydrates_of_which_sugars_calculated_us=$inp_food_carbohydrates_of_which_sugars_calculated_metric_mysql, 
								food_added_sugars_calculated_us=$inp_food_added_sugars_calculated_metric_mysql, 
								food_proteins_calculated_us=$inp_food_proteins_calculated_metric_mysql, 
								food_salt_calculated_us=$inp_food_salt_calculated_metric_mysql, 
								food_sodium_calculated_us=$inp_food_sodium_calculated_metric_mysql,

								food_energy_net_content=$inp_food_energy_net_content_mysql,
								food_fat_net_content=$inp_food_fat_net_content_mysql,
								food_saturated_fat_net_content=$inp_food_saturated_fat_net_content_mysql,
								food_trans_fat_net_content=$inp_food_trans_fat_net_content_mysql,
								food_monounsaturated_fat_net_content=$inp_food_monounsaturated_fat_net_content_mysql, 
								food_polyunsaturated_fat_net_content=$inp_food_polyunsaturated_fat_net_content_mysql, 
								food_cholesterol_net_content=$inp_food_cholesterol_net_content_mysql,
								food_carbohydrates_net_content=$inp_food_carbohydrates_net_content_mysql,
								food_carbohydrates_of_which_sugars_net_content=$inp_food_carbohydrates_of_which_sugars_net_content_mysql,
								food_added_sugars_net_content=$inp_food_added_sugars_net_content_mysql,
								food_dietary_fiber_net_content=$inp_food_dietary_fiber_net_content_mysql,
								food_proteins_net_content=$inp_food_proteins_net_content_mysql,
								food_salt_net_content=$inp_food_salt_net_content_mysql,
								food_sodium_net_content=$inp_food_sodium_net_content_mysql
								WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));



				$url = "edit_food_numbers_us_pcs.php?food_id=$get_current_food_id&l_$l&ft=success&fm=changes_saved";
				header("Location: $url");
				exit;
			
			
			}


			echo"
			<h1>$get_current_food_manufacturer_name $get_current_food_name</h1>

			<!-- Where am I? -->
				<p>
				<a href=\"my_food.php?l=$l#food$get_current_food_id\">$l_my_food</a>
				&gt;
				<a href=\"view_food.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;l=$l\">$get_current_food_name</a>
				&gt;
				<a href=\"edit_food.php?food_id=$food_id&amp;l=$l\">$l_edit</a>
				&gt;
				<a href=\"edit_food_numbers_us_pcs.php?food_id=$food_id&amp;l=$l\">$l_numbers</a>
				</p>
			<!-- //Where am I? -->

			<!-- Feedback -->";
				if(isset($ft) && isset($fm)){
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
			echo"
			<!-- //Feedback -->
			



			<!-- Headline -->
				<table>
				 <tr>
				  <td style=\"padding: 0px 4px 0px 0px;\">
					<h2>$l_numbers</h2>
				  </td>
				  <td>
				  </td>
				 </tr>
				</table>
			<!-- //Headline -->

			<!-- Img + Food Table -->
				<div class=\"two_rows\">
					<!-- Img -->
						<script src=\"_scripts/magnify.js\"></script>
						
						<div class=\"two_columns\">

							";
							if(file_exists("../$get_current_food_image_path/$get_current_food_image_b") && $get_current_food_image_b != ""){
								$imgsize = getimagesize("../$get_current_food_image_path/$get_current_food_image_b");
								echo"
								<div class=\"img-magnifier-container\">
								<img src=\"../$get_current_food_image_path/$get_current_food_image_b\" alt=\"$get_current_food_image_b\" id=\"food_image_b\" width=\"$imgsize[0]\" height=\"$imgsize[1]\" />
								</div>
								
								<p>
								<a href=\"../$get_current_food_image_path/$get_current_food_image_b\">Zoom</a>
								</p>

								<script>
								/* Initiate Magnify Function
								with the id of the image, and the strength of the magnifier glass:*/
								magnify(\"food_image_b\", 2);
								</script>
								";
							}
							else{
								if(file_exists("../$get_current_food_image_path/$get_current_food_image_a") && $get_current_food_image_a != ""){
									$imgsize = getimagesize("../$get_current_food_image_path/$get_current_food_image_a");
									echo"
									<div class=\"img-magnifier-container\">
									<img src=\"../$get_current_food_image_path/$get_current_food_image_a\" alt=\"$get_current_food_image_a\" id=\"food_image_a\" width=\"$imgsize[0]\" height=\"$imgsize[1]\" />
									</div>
								
									<p>
									<a href=\"../$get_current_food_image_path/$get_current_food_image_a\">Zoom</a>
									</p>

									<script>
									/* Initiate Magnify Function
									with the id of the image, and the strength of the magnifier glass:*/
									magnify(\"food_image_a\", 2);
									</script>
									";
								}

							}
							echo"
						</div> <!-- //two_columns -->
					<!-- //Img -->		
				

					<!-- Numbers -->
						<div class=\"two_columns\">

							<!-- System-country + Measurement -->
								<div style=\"text-align: center;\">
									<span>
									<a href=\"edit_food_numbers_eu_hundred_metric.php?food_id=$food_id&amp;l=$l\"><img src=\"_gfx/flags/european_union_50_percent_16x16.png\" alt=\"european_union_16x16.png\" /> EU</a>
									&nbsp;
									<a href=\"edit_food_numbers_us_pcs.php?food_id=$food_id&amp;l=$l\" style=\"font-weight:bold;\"><img src=\"_gfx/flags/united_states_16x16.png\" alt=\"united_states_16x16.png\" /> USA</a>
									<br />
						
									<a href=\"edit_food_numbers_us_pcs.php?food_id=$food_id&amp;l=$l\" style=\"font-weight:bold;\">$get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement</a>
									</span>
								</div>
							<!-- //System-country + Measurement -->

							<!-- Focus + Autosubmit -->
							";
								if($autosubmit_form == "1"){
									echo"
									<div class=\"info\"><p><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Autosubmitting form to save pending changes</p></div>
									<script language=\"javascript\" type=\"text/javascript\">
									\$(document).ready(function(){
  										$(\"#edit_food_numbers_form\").submit();
									});
									</script>
									";
								}
								else{
									echo"
									<script>
									\$(document).ready(function(){
										\$('[name=\"inp_food_energy_calculated\"]').focus();
										var input = $('[name=\"inp_food_energy_calculated\"]').val();
										input = input.replace(\",\", \".\");
										output = Math.round((input/$get_current_food_serving_size_us)*8);
										\$(\".food_energy\").text(output);
									});
									</script>
									";
								}
							echo"
							<!-- //Focus + Autosubmit -->

							<form method=\"post\" action=\"edit_food_numbers_us_pcs.php?food_id=$get_current_food_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" id=\"edit_food_numbers_form\">
					
							<table class=\"hor-zebra\" style=\"width: 350px\">
							 <thead>
							  <tr>
							   <th scope=\"col\">
							   </th>
							   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
								<span>$l_per_eight</span>
							   </th>
							   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
								<span>$l_serving<br />
								$get_current_food_serving_size_us $get_current_food_serving_size_measurement_us
								($get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement)</span>
							   </th>
							  </tr>
							 </thead>
							 <tbody>
							  <tr>
							   <td style=\"padding: 8px 4px 6px 8px;\">
								<span>$l_calories</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span class=\"food_energy\">$get_current_food_energy_us</span>

								<!-- On change energy calculate -->
								<script>
								\$(document).ready(function(){
									\$('[name=\"inp_food_energy_calculated\"]').on(\"change paste keyup\", function() {
										var input = $('[name=\"inp_food_energy_calculated\"]').val();
										input = input.replace(\",\", \".\");
										output = Math.round((input/$get_current_food_serving_size_us)*8);
										\$(\".food_energy\").text(output);
									});
								});
								</script>
								<!-- On change energy calculate -->
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span><input type=\"text\" name=\"inp_food_energy_calculated\" value=\"$get_current_food_energy_calculated_us\" size=\"3\" /></span>
							   </td>
							  </tr>
							  <tr>
							   <td style=\"padding: 8px 4px 6px 8px;\">
								<p style=\"margin:0;padding: 0px 0px 4px 0px;\">$l_fat:</p>
								<p style=\"margin:0;padding: 0;\">$l_dash_saturated_fat</p>
								<p style=\"margin:0;padding: 0;\">$l_dash_trans_fat</p>
								<p style=\"margin:0;padding: 0;\">$l_dash_monounsaturated_fat</p>
								<p style=\"margin:0;padding: 0;\">$l_dash_polyunsaturated_fat</p>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<p style=\"margin:0;padding: 0px 0px 4px 0px;\">
								<span class=\"food_fat\">$get_current_food_fat_us</span><br />
						<span class=\"food_saturated_fat\">$get_current_food_saturated_fat_us</span><br />
								<span class=\"food_trans_fat\">$get_current_food_trans_fat_us</span><br />
								<span class=\"food_monounsaturated_fat\">$get_current_food_monounsaturated_fat_us</span><br />
								<span class=\"food_polyunsaturated_fat\">$get_current_food_polyunsaturated_fat_us</span>
								</p>

						<!-- On change energy calculate -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_food_fat_calculated\"]').on(\"change paste keyup\", function() {
								var input = $('[name=\"inp_food_fat_calculated\"]').val();
								input = input.replace(\",\", \".\");
								output = Math.round((input/$get_current_food_serving_size_us)*8);
								\$(\".food_fat\").text(output);
							});
							\$('[name=\"inp_food_saturated_fat_calculated\"]').on(\"change paste keyup\", function() {
								var input_b = $('[name=\"inp_food_saturated_fat_calculated\"]').val();
								input_b = input_b.replace(\",\", \".\");
								output_b = Math.round((input_b/$get_current_food_serving_size_us)*8);
								\$(\".food_saturated_fat\").text(output_b);
							});
							\$('[name=\"inp_food_trans_fat_calculated\"]').on(\"change paste keyup\", function() {
								var input_c = $('[name=\"inp_food_trans_fat_calculated\"]').val();
								input_c = input_c.replace(\",\", \".\");
								output_c = Math.round((input_c/$get_current_food_serving_size_us)*8);
								\$(\".food_trans_fat\").text(output_c);
							});
							\$('[name=\"inp_food_monounsaturated_fat_calculated\"]').on(\"change paste keyup\", function() {
								var input_d = $('[name=\"inp_food_monounsaturated_fat_calculated\"]').val();
								input_d = input_d.replace(\",\", \".\");
								output_d = Math.round((input_d/$get_current_food_serving_size_us)*8);
								\$(\".food_monounsaturated_fat\").text(output_d);
							});
							\$('[name=\"inp_food_polyunsaturated_fat_calculated\"]').on(\"change paste keyup\", function() {
								var input_e = $('[name=\"inp_food_polyunsaturated_fat_calculated\"]').val();
								input_e = input_e.replace(\",\", \".\");
								output_e = Math.round((input_e/$get_current_food_serving_size_us)*8);
								\$(\".food_polyunsaturated_fat\").text(output_e);
							});
						});
						</script>
						<!-- On change energy calculate -->
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_food_fat_calculated\" value=\"$get_current_food_fat_calculated_us\" size=\"3\" /><br /></p>
								<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_saturated_fat_calculated\" value=\"$get_current_food_saturated_fat_calculated_us\" size=\"3\" /></p>
								<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_trans_fat_calculated\" value=\"$get_current_food_trans_fat_calculated_us\" size=\"3\" /></p>
								<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_monounsaturated_fat_calculated\" value=\"$get_current_food_monounsaturated_fat_calculated_us\" size=\"3\" /></p>
								<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_polyunsaturated_fat_calculated\" value=\"$get_current_food_polyunsaturated_fat_calculated_us\" size=\"3\" /></p>
				 	  </td>
							  </tr>
							  <tr>


							  <tr>
							   <td style=\"padding: 8px 4px 6px 8px;\">
								<span>$l_cholesterol_in_mg:</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span class=\"food_cholesterol\">$get_current_food_cholesterol_us</span>

								<!-- On change cholesterol calculate -->
								<script>
								\$(document).ready(function(){
									\$('[name=\"inp_food_cholesterol_calculated\"]').on(\"change paste keyup\", function() {
										var input = $('[name=\"inp_food_cholesterol_calculated\"]').val();
										input = input.replace(\",\", \".\");
										output = Math.round((input/$get_current_food_serving_size_us)*8);
										\$(\".food_cholesterol\").text(output);
									});
								});
								</script>
								<!-- On change cholesterol calculate -->
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span><input type=\"text\" name=\"inp_food_cholesterol_calculated\" value=\"$get_current_food_cholesterol_calculated_us\" size=\"3\" /></span>
							   </td>
							  </tr>

							  <tr>
							   <td style=\"padding: 8px 4px 6px 8px;\">
								<span>$l_sodium_in_mg</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span class=\"food_sodium_hundred\">$get_current_food_sodium_us</span>
								<!-- On change sodium calculate -->
								<script>
								\$(document).ready(function(){
									\$('[name=\"inp_food_sodium_calculated\"]').on(\"change paste keyup\", function() {

										// Calculate sodium pr pc
										var food_sodium_calculated = \$('[name=\"inp_food_sodium_calculated\"]').val();
										food_sodium_calculated = food_sodium_calculated.replace(\",\", \".\");
										food_sodium_hundred = ((food_sodium_calculated/$get_current_food_serving_size_us)*8);
										food_sodium_hundred = food_sodium_hundred.toFixed(2)
										\$(\".food_sodium_hundred\").text(food_sodium_hundred);
	

										// Calculate salt (salt is 60 % of sodium)
										food_salt_calculated = (food_sodium_hundred*60)/100; // mg
										food_salt_calculated = food_salt_calculated/1000; // g
										food_salt_calculated = food_salt_calculated.toFixed(2);
										\$('[name=\"inp_food_salt_calculated\"]').val(food_salt_calculated);

										food_salt_hundred = ((food_salt_calculated/$get_current_food_serving_size_us)*8);
										food_salt_hundred = food_salt_hundred.toFixed(2);
										\$(\".food_salt_hundred\").text(food_salt_hundred);

									});
								});
								</script>
								<!-- On change sodium calculate -->
					 		   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span><input type=\"text\" name=\"inp_food_sodium_calculated\" value=\"$get_current_food_sodium_calculated_us\" size=\"3\" /></span>
							   </td>
							  </tr>

							  <tr>
		 					   <td style=\"padding: 8px 4px 6px 8px;\">
								<p style=\"margin:0;padding: 0px 0px 4px 0px;\">$l_carbs:</p>
								<p style=\"margin:0;padding: 0;\">$l_dash_dietary_fiber</p>
								<p style=\"margin:0;padding: 0;\">$l_dash_of_which_sugars</p>
								<p style=\"margin:0;padding: 0;\">$l_dash_added_sugars</p>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<p style=\"margin:0;padding: 0px 0px 4px 0px;\">
								<span class=\"food_carbohydrates\">$get_current_food_carbohydrates_us</span><br />
								<span class=\"food_dietary_fiber\">$get_current_food_dietary_fiber_us</span><br />
								<span class=\"food_carbohydrates_of_which_sugars\">$get_current_food_carbohydrates_of_which_sugars_us</span><br />
								<span class=\"food_added_sugars\">$get_current_food_added_sugars_us</span>
								</p>

								<!-- On change carbs+diatary fibar+sugar calculate -->
								<script>
								\$(document).ready(function(){
									\$('[name=\"inp_food_carbohydrates_calculated\"]').on(\"change paste keyup\", function() {
										var input = $('[name=\"inp_food_carbohydrates_calculated\"]').val();
										input = input.replace(\",\", \".\");
										output = Math.round((input/$get_current_food_serving_size_us)*8);
										\$(\".food_carbohydrates\").text(output);
									});
									\$('[name=\"inp_food_dietary_fiber_calculated\"]').on(\"change paste keyup\", function() {
										var input_d = $('[name=\"inp_food_dietary_fiber_calculated\"]').val();
										input_d = input_d.replace(\",\", \".\");
										output_d = Math.round((input_d/$get_current_food_serving_size_us)*8);
										\$(\".food_dietary_fiber\").text(output_d);
									});
									\$('[name=\"inp_food_carbohydrates_of_which_sugars_calculated\"]').on(\"change paste keyup\", function() {
										var input_b = $('[name=\"inp_food_carbohydrates_of_which_sugars_calculated\"]').val();
										input_b = input_b.replace(\",\", \".\");
										output_b = Math.round((input_b/$get_current_food_serving_size_us)*8);
										\$(\".food_carbohydrates_of_which_sugars\").text(output_b);
									});
									\$('[name=\"inp_food_added_sugars_calculated\"]').on(\"change paste keyup\", function() {
										var input_a = $('[name=\"inp_food_added_sugars_calculated\"]').val();
										input_a = input_a.replace(\",\", \".\");
										output_a = Math.round((input_a/$get_current_food_serving_size_us)*8);
										\$(\".food_added_sugars\").text(output_a);
									});
								});
								</script>
								<!-- On change carbs+diatary fibar+sugar calculate -->
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_food_carbohydrates_calculated\" value=\"$get_current_food_carbohydrates_calculated_us\" size=\"3\" /></p>
								<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_dietary_fiber_calculated\" value=\"$get_current_food_dietary_fiber_calculated_us\" size=\"3\" /></p>
								<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_carbohydrates_of_which_sugars_calculated\" value=\"$get_current_food_carbohydrates_of_which_sugars_calculated_us\" size=\"3\" /></p>
								<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_food_added_sugars_calculated\" value=\"$get_current_food_added_sugars_calculated_us\" size=\"3\" /></p>
							   </td>
							  </tr>


							  <tr>
							   <td style=\"padding: 8px 4px 6px 8px;\">
								<span>$l_protein:</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span class=\"food_proteins\">$get_current_food_proteins_us</span>
		
								<!-- On change dietary fiber calculate -->
								<script>
								\$(document).ready(function(){
									\$('[name=\"inp_food_proteins_calculated\"]').on(\"change paste keyup\", function() {
										var input = $('[name=\"inp_food_proteins_calculated\"]').val();
										input = input.replace(\",\", \".\");
										output = Math.round((input/$get_current_food_serving_size_us)*8);
										\$(\".food_proteins\").text(output);
									});
								});
								</script>
								<!-- On change dietary fiber calculate -->
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span><input type=\"text\" name=\"inp_food_proteins_calculated\" value=\"$get_current_food_proteins_calculated_us\" size=\"3\" /></span>
							   </td>
							  </tr>

							  <tr>
							   <td style=\"padding: 8px 4px 6px 8px;\">
								<span>$l_salt_in_g</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span class=\"food_salt_hundred\">$get_current_food_salt_us</span>
								<!-- On change salt calculate -->
								<script>
								\$(document).ready(function(){
									\$('[name=\"inp_food_salt_calculated\"]').on(\"change paste keyup\", function() {

										// Calculate salt pr pc
										var food_salt_calculated = \$('[name=\"inp_food_salt_calculated\"]').val();
										food_salt_calculated = food_salt_calculated.replace(\",\", \".\");
										food_salt_hundred = ((food_salt_calculated/$get_current_food_serving_size_us)*8);
										food_salt_hundred = food_salt_hundred.toFixed(2)
										\$(\".food_salt_hundred\").text(food_salt_hundred);

										// var sodium_off = food_salt_hundred*0.40; // g
										// food_sodium_hundred = food_salt_hundred-sodium_off; // g
										// food_sodium_hundred = food_sodium_hundred*1000; // mg
										// food_sodium_calculated = Math.round((food_sodium_hundred*$get_current_food_serving_size_us)*8);
										// \$(\".food_sodium_hundred\").text(Math.round(food_sodium_hundred));
										// \$('[name=\"inp_food_sodium_calculated\"]').val(Math.round(food_sodium_calculated));
									});
								});
								</script>
								<!-- On change salt calculate -->
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span><input type=\"text\" name=\"inp_food_salt_calculated\" value=\"$get_current_food_salt_calculated_us\" size=\"3\" /></span>
							   </td>
							  </tr>


							 </tbody>
							</table>
					

							<div style=\"text-align: center;\">
								<p><input type=\"submit\" value=\"$l_save\" class=\"btn_default\" /></p>
							</div>
						</div> <!-- //two_columns -->
					<!-- //Numbers -->

				</div> <!-- //two_rows -->
			<!-- //Img + Food Table -->




			<!-- Back -->
				<div class=\"clear\"></div>
				<p>
				<a href=\"my_food.php?l=$l#food$get_current_food_id\" class=\"btn btn_default\">$l_my_food</a>
				</p>
			<!-- //Back -->

			";
		}
	}
	else{
		echo"<p>Please log in</p>";
	}
}
/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>