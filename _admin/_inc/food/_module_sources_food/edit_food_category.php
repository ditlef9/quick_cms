<?php
/**
*
* File: food/edit_food_category.php
* Version 1.0.0
* Date 13:03 03.02.2018
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


/*- Translations ---------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/food/ts_view_food.php");
include("$root/_admin/_translations/site/$l/food/ts_edit_food.php");
include("$root/_admin/_translations/site/$l/food/ts_new_food.php");

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
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");





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
			if($action == "edit_language"){

				if(isset($_GET['inp_food_language'])){
					$inp_food_language = $_GET['inp_food_language'];
					$inp_food_language = strip_tags(stripslashes($inp_food_language));
					$inp_food_language_mysql = quote_smart($link, $inp_food_language);

					
					$result = mysqli_query($link, "UPDATE $t_food_index SET food_language=$inp_food_language_mysql WHERE food_id=$food_id_mysql");

					// results
					$ft = "success";
					$fm = "changes_saved";

					// Pass new values
					$get_current_food_language = $inp_food_language;
				}
				else{
					$inp_food_language = "";
				}
			}
			elseif($action == "change_main_category"){

				if(isset($_GET['inp_main_category_id'])){
					$inp_main_category_id = $_GET['inp_main_category_id'];
					$inp_main_category_id = strip_tags(stripslashes($inp_main_category_id));
					$inp_main_category_id_mysql = quote_smart($link, $inp_main_category_id);

					// Fetch sub category
					$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main WHERE main_category_id=$inp_main_category_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_main_category_id, $get_current_main_category_name) = $row;
					if($get_current_main_category_id != ""){
						// Update
						$result = mysqli_query($link, "UPDATE $t_food_index SET food_main_category_id=$get_current_main_category_name WHERE food_id=$food_id_mysql");

						// results
						$ft = "success";
						$fm = "saving_changes";

						// Pass new values
						$get_current_food_main_category_id = $get_current_main_category_id;
					}
					else{
						// results
						$ft = "error";
						$fm = "main_category_not_found";
					}
					

				}
				else{
					$inp_food_main_category_id = "";
				}
			}
			elseif($action == "change_sub_category"){

				if(isset($_GET['inp_sub_category_id'])){
					$inp_sub_category_id = $_GET['inp_sub_category_id'];
					$inp_sub_category_id = strip_tags(stripslashes($inp_sub_category_id));
					$inp_sub_category_id_mysql = quote_smart($link, $inp_sub_category_id);

					// Fetch sub category
					$query = "SELECT sub_category_id, sub_category_name FROM $t_food_categories_sub WHERE sub_category_id=$inp_sub_category_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_sub_category_id, $get_current_sub_category_name) = $row;
					if($get_current_sub_category_id != ""){
						// Update
						$result = mysqli_query($link, "UPDATE $t_food_index SET food_sub_category_id=$get_current_sub_category_id WHERE food_id=$food_id_mysql") or die(mysqli_error($link));

						// results
						$ft = "success";
						$fm = "saving_changes";

						// Pass new values
						$get_current_food_sub_category_id = $inp_sub_category_id;
					}
					else{
						// results
						$ft = "error";
						$fm = "sub_category_not_found";
					}
					

				}
				else{
					$inp_food_sub_category_id = "";
				}
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
				<a href=\"edit_food_category.php?food_id=$food_id&amp;l=$l\">$l_category</a>
				</p>
			<!-- //Where am I? -->



			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_recipe_title\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_changes_saved";
					echo"<meta http-equiv=refresh content=\"1; url=edit_food_category.php?food_id=$food_id&amp;l=$l\">";
				}
				elseif($fm == "saving_changes"){
					$fm = "<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_saving_changes";
					echo"<meta http-equiv=refresh content=\"1; url=edit_food_category.php?food_id=$food_id&amp;l=$l\">";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- Form -->
			";
			if($action == ""){
				echo"
			<form method=\"post\" action=\"new_food.php?l=$l&amp;mode=process\" enctype=\"multipart/form-data\">
			<h2>$l_category</h2>
			<table>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				<p><b>$l_language:</b></p>
			  </td>
			  <td>
				<script>
				\$(function(){
					\$('#inp_l').on('change', function () {
						var url = \$(this).val(); // get selected value
						if (url) { // require a URL
 							window.location = url; // redirect
						}
						return false;
					});
				});
				</script>
				<p>
				<select id=\"inp_l\">
				<option value=\"index.php?l=$l\">- $l_please_select -</option>
				<option value=\"index.php?l=$l\"></option>\n";


				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

					$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
	
					echo"	<option value=\"edit_food_category.php?action=edit_language&amp;food_id=$food_id&amp;inp_food_language=$get_language_active_iso_two&amp;l=$get_language_active_iso_two\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($get_current_food_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
				}
				echo"
				</select>
				</p>
			  </td>
			 </tr>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				<p><b>$l_category:</b></p>
			  </td>
			  <td>
				<script>
				\$(function(){
					\$('#inp_c').on('change', function () {
						var url = \$(this).val(); // get selected value
						if (url) { // require a URL
 							window.location = url; // redirect
						}
						return false;
					});
				});
				</script>


				<p>
				<select id=\"inp_c\">
				<option value=\"edit_food_category.php?food_id=$get_current_food_id&amp;l=$l\">- $l_please_select -</option>
				<option value=\"edit_food_category.php?food_id=$get_current_food_id&amp;l=$l\"> </option>
				";

				
				// Get main categories
				$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_main_category_id, $get_main_category_name) = $row;
				
					// Translation
					$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_main_category_id AND main_category_translation_language=$l_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_main_category_translation_id, $get_main_category_translation_value) = $row_t;

					echo"
					<option value=\"edit_food_category.php?action=change_main_category&amp;food_id=$get_current_food_id&amp;inp_main_category_id=$get_main_category_id&amp;l=$l\""; if($get_main_category_id == "$get_current_food_main_category_id"){ echo" selected=\"selected\""; } echo">$get_main_category_translation_value</option>\n";
				
				}




				echo"
				</select>
				</p>
			  </td>
			 </tr>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				
			  </td>
			  <td>
				<script>
				\$(function(){
					\$('#inp_s').on('change', function () {
						var url = \$(this).val(); // get selected value
						if (url) { // require a URL
 							window.location = url; // redirect
						}
						return false;
					});
				});
				</script>


				<p>
				<select id=\"inp_s\">
				<option value=\"edit_food_category.php?food_id=$get_current_food_id&amp;l=$l\">- Please select -</option>
				<option value=\"edit_food_category.php?food_id=$get_current_food_id&amp;l=$l\"> </option>

				";
				// Get sub categories
				$open_main_category_id_mysql = quote_smart($link, $open_main_category_id);
				$query = "SELECT sub_category_id, sub_category_name, sub_category_symbolic_link_to_category_id FROM $t_food_categories_sub WHERE sub_category_parent_id=$get_current_main_category_id ORDER BY sub_category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_sub_category_id, $get_sub_category_name, $get_sub_category_symbolic_link_to_category_id) = $row;
				
					// Translation
					$query_t = "SELECT sub_category_translation_id, sub_category_translation_value FROM $t_food_categories_sub_translations WHERE sub_category_id=$get_sub_category_id AND sub_category_translation_language=$l_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_sub_category_translation_id, $get_sub_category_translation_value) = $row_t;

					

					// Link
					if($get_sub_category_symbolic_link_to_category_id == "0"){
						$category_id = "$get_sub_category_id";
					}
					else{
						$category_id = "$get_sub_category_symbolic_link_to_category_id";
					}

					echo"
					<option value=\"edit_food_category.php?action=change_sub_category&amp;food_id=$get_current_food_id&amp;inp_sub_category_id=$get_sub_category_id&amp;l=$l\""; if($get_sub_category_id == "$get_current_food_sub_category_id"){ echo" selected=\"selected\""; } echo">$get_sub_category_translation_value</option>\n";
					
				}
				echo"
				</select>
				</p>

			  </td>
			 </tr>
			</table>
			<!-- //Form -->
				";
			} // action == "";
			echo"

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