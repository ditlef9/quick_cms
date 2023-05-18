<?php
/**
*
* File: food/edit_food_images.php
* Version 1.0.0
* Date 11:11 06.04.2022
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


/*- Settings ---------------------------------------------------------------------------- */
$settings_image_width = "1280";
$settings_image_height = "1280";


/*- Functions --------------------------------------------------------------------------- */


/*- Translations ---------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/food/ts_view_food.php");
include("$root/_admin/_translations/site/$l/food/ts_edit_food.php");
include("$root/_admin/_translations/site/$l/food/ts_new_food.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['image'])){
	$image= $_GET['image'];
	$image = strip_tags(stripslashes($image));
}
else{
	$image = "";
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

			if($action == ""){


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
					<a href=\"edit_food_images.php?food_id=$food_id&amp;l=$l\">$l_images</a>
					</p>
				<!-- //Where am I? -->

				<!-- Feedback -->
					";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst(str_replace("_", " ", $fm));
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					if(isset($_GET['ft_image_a']) && isset($_GET['fm_image_a'])){
						$ft_image_a = $_GET['ft_image_a'];
						$ft_image_a = output_html($ft_image_a);

						$fm_image_a = $_GET['fm_image_a'];
						$fm_image_a = output_html($fm_image_a);
						$fm_image_a = ucfirst(str_replace("_", " ", $fm_image_a));

						echo"<div class=\"$ft_image_a\"><span>$fm_image_a</span></div>";
					}
					echo"	
				<!-- //Feedback -->

				<!-- Images-->

					<form method=\"post\" action=\"edit_food_images.php?action=upload_new&amp;food_id=$get_current_food_id&amp;l=$l&amp;image=$image&amp;process=1\" enctype=\"multipart/form-data\">

					
					<h2>$l_images</h2>

					<table>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
						<p><b>$l_product:</b><br />
						</p>
					  </td>
					  <td>
						<p>";
						if($get_current_food_image_a != ""){
							if(file_exists("../$get_current_food_image_path/$get_current_food_image_a")){
								// Thumb A medium
								if(!(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_medium")) OR $get_current_food_thumb_a_medium == ""){
									$ext = get_extension("$get_current_food_image_a");
									$inp_thumb_name = str_replace(".$ext", "", $get_current_food_image_a);
									$get_current_food_thumb_a_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
									$inp_food_thumb_a_medium_mysql = quote_smart($link, $get_current_food_thumb_a_medium);
									$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_medium=$inp_food_thumb_a_medium_mysql WHERE food_id=$get_current_food_id") or die(mysqli_error($link));
				
									resize_crop_image(200, 200, "$root/$get_current_food_image_path/$get_current_food_image_a", "$root/$get_current_food_image_path/$get_current_food_thumb_a_medium");
								}
								echo"
								<a href=\"$root/$get_current_food_image_path/$get_current_food_image_a\"><img src=\"$root/$get_current_food_image_path/$get_current_food_thumb_a_medium\" alt=\"$get_current_food_thumb_a_medium\" /></a><br />
								<a href=\"edit_food_images.php?action=rotate&amp;food_id=$food_id&amp;image=a&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_rotate</a>
								<a href=\"edit_food_images.php?action=delete&amp;food_id=$food_id&amp;image=a&amp;l=$l\" class=\"btn btn_default\">$l_delete</a>
								";
							}
							else{
								// Remove image
								$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_a='', food_thumb_a_small='', food_thumb_a_medium='', food_thumb_a_large='' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
								echo"<p>Removed image a from food because it was not found.</p>\n";
							}
						}
						echo"
						</p>

						<p>$l_upload_new (jpg $settings_image_width x $settings_image_height px)<br />
						<input type=\"file\" name=\"inp_food_image_a\" /> 
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
						<p><b>$l_food_table:</b><br />
					  </td>
					  <td>
						<p>";
						if($get_current_food_image_b != ""){
							// Thumb B medium
							if(file_exists("../$get_current_food_image_path/$get_current_food_image_b")){
								if(!(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_medium")) OR $get_current_food_thumb_b_medium == ""){
									$ext = get_extension("$get_current_food_image_b");
									$inp_thumb_name = str_replace(".$ext", "", $get_current_food_image_b);
									$get_current_food_thumb_b_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
									$inp_food_thumb_b_medium_mysql = quote_smart($link, $get_current_food_thumb_b_medium);
									$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_b_medium=$inp_food_thumb_b_medium_mysql WHERE food_id=$get_current_food_id") or die(mysqli_error($link));
				
									resize_crop_image(200, 200, "$root/$get_current_food_image_path/$get_current_food_image_b", "$root/$get_current_food_image_path/$get_current_food_thumb_b_medium");
								}
								echo"
								<a href=\"$root/$get_current_food_image_path/$get_current_food_image_b\"><img src=\"$root/$get_current_food_image_path/$get_current_food_thumb_b_medium\" alt=\"$get_current_food_thumb_b_medium\" /></a><br />
								<a href=\"edit_food_images.php?action=rotate&amp;food_id=$food_id&amp;image=b&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_rotate</a>
								<a href=\"edit_food_images.php?action=delete&amp;food_id=$food_id&amp;image=b&amp;l=$l\" class=\"btn btn_default\">$l_delete</a>
								";
							}
							else{
								// Remove image
								$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_b='', food_thumb_b_small='', food_thumb_b_medium='', food_thumb_b_large='' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
								echo"<p>Removed image b from food because it was not found.</p>\n";
							}
						}
						echo"
						</p>

						<p>$l_upload_new (jpg $settings_image_width x $settings_image_height px)<br />
						<input type=\"file\" name=\"inp_food_image_b\" /> 
						</p>

					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
						<p><b>$l_other:</b><br />
				 	 </td>
				 	 <td>
						<p>";
						if($get_current_food_image_c != ""){
							// Thumb C medium
							if(file_exists("../$get_current_food_image_path/$get_current_food_image_c")){
								// Thumb C medium
								if(!(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_medium")) OR $get_current_food_thumb_c_medium == ""){
									$ext = get_extension("$get_current_food_image_c");
									$inp_thumb_name = str_replace(".$ext", "", $get_current_food_image_c);
									$get_current_food_thumb_c_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
									$inp_food_thumb_c_medium_mysql = quote_smart($link, $get_current_food_thumb_c_medium);
									$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_c_medium=$inp_food_thumb_c_medium_mysql WHERE food_id=$get_current_food_id") or die(mysqli_error($link));
				
									resize_crop_image(200, 200, "$root/$get_current_food_image_path/$get_current_food_image_c", "$root/$get_current_food_image_path/$get_current_food_thumb_c_medium");
								}

								echo"
								<a href=\"$root/$get_current_food_image_path/$get_current_food_image_c\"><img src=\"$root/$get_current_food_image_path/$get_current_food_thumb_c_medium\" alt=\"$get_current_food_thumb_c_medium\" /></a><br />
								<a href=\"edit_food_images.php?action=rotate&amp;food_id=$food_id&amp;image=c&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_rotate</a>
								<a href=\"edit_food_images.php?action=delete&amp;food_id=$food_id&amp;image=c&amp;l=$l\" class=\"btn btn_default\">$l_delete</a>
								";
							}
							else{
								// Remove image
								$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_c='', food_thumb_c_small='', food_thumb_c_medium='', food_thumb_c_large='' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
								echo"<p>Removed image c from food because it was not found.</p>\n";
							}
						}
						echo"
						</p>

						<p>$l_upload_new (jpg $settings_image_width x $settings_image_height px)<br />
						<input type=\"file\" name=\"inp_food_image_c\" /> 
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
						<p><b>$l_inspiration:</b><br />
					  </td>
					  <td>
						<p>";
						if($get_current_food_image_d != ""){
							if(file_exists("../$get_current_food_image_path/$get_current_food_image_d")){
								if(!(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_medium")) OR $get_current_food_thumb_d_medium == ""){
									$ext = get_extension("$get_current_food_image_d");
									$inp_thumb_name = str_replace(".$ext", "", $get_current_food_image_d);
									$get_current_food_thumb_d_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
									$inp_food_thumb_d_medium_mysql = quote_smart($link, $get_current_food_thumb_d_medium);
									$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_d_medium=$inp_food_thumb_d_medium_mysql WHERE food_id=$get_current_food_id") or die(mysqli_error($link));
				
									resize_crop_image(200, 200, "$root/$get_current_food_image_path/$get_current_food_image_d", "$root/$get_current_food_image_path/$get_current_food_thumb_d_medium");
								}

								echo"
								<a href=\"$root/$get_current_food_image_path/$get_current_food_image_d\"><img src=\"$root/$get_current_food_image_path/$get_current_food_thumb_d_medium\" alt=\"$get_current_food_thumb_d_medium\" /></a><br />
								<a href=\"edit_food_images.php?action=rotate&amp;food_id=$food_id&amp;image=d&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_rotate</a>
								<a href=\"edit_food_images.php?action=delete&amp;food_id=$food_id&amp;image=d&amp;l=$l\" class=\"btn btn_default\">$l_delete</a>
								";
							}
							else{
								// Remove image
								$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_d='', food_thumb_d_small='', food_thumb_d_medium='', food_thumb_d_large='' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
								echo"<p>Removed image d from food because it was not found.</p>\n";
							}
						}
						echo"
						</p>

						<p>$l_upload_new (jpg $settings_image_width x $settings_image_height px)<br />
						<input type=\"file\" name=\"inp_food_image_d\" /> 
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
					
					  </td>
					  <td>
						<p>
						<input type=\"submit\" value=\"$l_upload_images\" class=\"btn_default\" />
						</p>
					  </td>
					 </tr>
					</table>

				<!-- //Images -->

				<!-- Back -->
					<p>
					<a href=\"edit_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
					<a href=\"edit_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;l=$l\">$l_edit $get_current_food_name</a>
					</p>
				<!-- //Back -->

				";
			} // action == ""
			elseif($action == "rotate" && $process == 1 && $image != ""){
				// Delete all old thumbnails
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_small") && $get_current_food_thumb_a_small != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_a_small");

				}
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_medium") && $get_current_food_thumb_a_medium != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_a_medium");
				}
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_large") && $get_current_food_thumb_a_large != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_a_large");
				}


				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_small") && $get_current_food_thumb_b_small != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_b_small");
				}
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_medium") && $get_current_food_thumb_b_medium != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_b_medium");
				}
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_large") && $get_current_food_thumb_b_large != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_b_large");
				}

				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_small") && $get_current_food_thumb_c_small != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_c_small");
				}
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_medium") && $get_current_food_thumb_c_medium != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_c_medium");
				}
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_large") && $get_current_food_thumb_c_large != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_c_large");
				}

				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_small") && $get_current_food_thumb_d_small != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_d_small");
				}
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_medium") && $get_current_food_thumb_d_medium != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_d_medium");
				}
				if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_large") && $get_current_food_thumb_d_large != ""){
					unlink("../$get_current_food_image_path/$get_current_food_thumb_d_large");
				}

				// Determine current photo
				$current_photo_path = "";
			
				if($image == "a"){
					if(file_exists("../$get_current_food_image_path/$get_current_food_image_a")){
						$current_photo_path = "$get_current_food_image_path/$get_current_food_image_a";
					}
				}
				elseif($image == "b"){
					if(file_exists("../$get_current_food_image_path/$get_current_food_image_b")){
						$current_photo_path = "$get_current_food_image_path/$get_current_food_image_b";
					}
				}
				elseif($image == "c"){
					if(file_exists("../$get_current_food_image_path/$get_current_food_image_c")){
						$current_photo_path = "$get_current_food_image_path/$get_current_food_image_c";
					}
				}
				elseif($image == "d"){
					if(file_exists("../$get_current_food_image_path/$get_current_food_image_d")){
						$current_photo_path = "$get_current_food_image_path/$get_current_food_image_d";
					}
				}


				if($current_photo_path == ""){
					$url = "edit_food_images.php?food_id=$food_id&l=$l&ft=error&fm=image_not_found";
					header("Location: $url");
					exit;
				}


				// Random id
				$seed = str_split('abcdefghijklmnopqrstuvwxyz' . '0123456789');
				shuffle($seed); // probably optional since array_is randomized; this may be redundant
				$random_string = '';
				foreach (array_rand($seed, 2) as $k) $random_string .= $seed[$k];

				// extension
				$extension = get_extension($current_photo_path);
				$extension = strtolower($extension);

				// New name
				$inp_food_manufacturer_name = clean($get_current_food_manufacturer_name);
				$inp_food_name_clean = clean($get_current_food_name);

				$image_final_path = "../" . $get_current_food_image_path . "/" . $inp_food_manufacturer_name . "_" . $inp_food_name_clean . "_" . $random_string . "_" . $image . ".$extension";


				// Load
				if($extension == "jpg"){
					$source = imagecreatefromjpeg("../$current_photo_path");
				}
				elseif($extension == "gif"){
					$source = ImageCreateFromGif("../$current_photo_path");
				}
				else{
					$source = ImageCreateFromPNG("../$current_photo_path");
				}


				$original_x = imagesx($source);
				$original_y = imagesy($source);

				$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
   
				// Rotate
   				$rotate = imagerotate($source, 270, $bgColor);
   				imagesavealpha($rotate, true);
   				imagepng($rotate, $image_final_path);



				// Free memory
				imagedestroy($source);
				imagedestroy($rotate); 

				// Delete old image
				unlink("../$current_photo_path");

				// Update
				if($extension == "jpg"){
					$inp_image = $inp_food_manufacturer_name . "_" . $inp_food_name_clean . "_" . $random_string . "_" . $image . ".jpg";
				}
				elseif($extension == "gif"){
					$inp_image = $inp_food_manufacturer_name . "_" . $inp_food_name_clean . "_" . $random_string . "_" . $image . ".gif";
				}
				else{
					$inp_image = $inp_food_manufacturer_name . "_" . $inp_food_name_clean . "_" . $random_string . "_" . $image . ".png";
				}
				$inp_image_mysql = quote_smart($link, $inp_image);

				if($image == "a"){
					mysqli_query($link, "UPDATE $t_food_index SET food_image_a=$inp_image_mysql, food_thumb_a_small='', food_thumb_a_medium='', food_thumb_a_large='' WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
				}
				elseif($image == "b"){
					mysqli_query($link, "UPDATE $t_food_index SET food_image_b=$inp_image_mysql, food_thumb_b_small='', food_thumb_b_medium='', food_thumb_b_large='' WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
				}
				elseif($image == "c"){
					mysqli_query($link, "UPDATE $t_food_index SET food_image_c=$inp_image_mysql, food_thumb_c_small='', food_thumb_c_medium='', food_thumb_c_large='' WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
				}
				elseif($image == "d"){
					mysqli_query($link, "UPDATE $t_food_index SET food_image_d=$inp_image_mysql, food_thumb_d_small='', food_thumb_d_medium='', food_thumb_d_large='' WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
				}




				// Search engine
				include("new_food_00_add_update_search_engine.php");


				$url = "edit_food_images.php?food_id=$food_id&l=$l&ft=success&fm=image_rotated";
				header("Location: $url");
				exit;
			} // action == "rotate"
			elseif($action == "delete" && isset($_GET['image'])){
				$image = $_GET['image'];
				$image = strip_tags(stripslashes($image));

				if($process == 1){

					// Delete all old thumbnails
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_small") && $get_current_food_thumb_a_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_medium") && $get_current_food_thumb_a_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_large") && $get_current_food_thumb_a_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_large");
					}

					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_small") && $get_current_food_thumb_b_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_medium") && $get_current_food_thumb_b_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_large") && $get_current_food_thumb_b_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_large");
					}

					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_small") && $get_current_food_thumb_c_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_medium") && $get_current_food_thumb_c_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_large") && $get_current_food_thumb_c_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_large");
					}

					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_small") && $get_current_food_thumb_d_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_medium") && $get_current_food_thumb_d_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_large") && $get_current_food_thumb_d_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_large");
					}


					if($image == "a"){
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_a")){
							unlink("../$get_current_food_image_path/$get_current_food_image_a");
						}
						mysqli_query($link, "UPDATE $t_food_index SET food_image_a='', food_thumb_a_small='', food_thumb_a_medium='', food_thumb_a_large='' WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
					}
					elseif($image == "b"){
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_b")){
							unlink("../$get_current_food_image_path/$get_current_food_image_b");
						}
						mysqli_query($link, "UPDATE $t_food_index SET food_image_b='', food_thumb_b_small='', food_thumb_b_medium='', food_thumb_b_large='' WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
					}
					elseif($image == "c"){
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_c")){
							unlink("../$get_current_food_image_path/$get_current_food_image_c");
						}
						mysqli_query($link, "UPDATE $t_food_index SET food_image_c='', food_thumb_c_small='', food_thumb_c_medium='', food_thumb_c_large='' WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
					}
					elseif($image == "d"){
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_d")){
							unlink("../$get_current_food_image_path/$get_current_food_image_d");
						}
						mysqli_query($link, "UPDATE $t_food_index SET food_image_d='', food_thumb_d_small='', food_thumb_d_medium='', food_thumb_d_large='' WHERE food_id=$food_id_mysql") or die(mysqli_error($link));
					}



					// Search engine
					include("new_food_00_add_update_search_engine.php");


					$url = "edit_food_images.php?food_id=$food_id&l=$l&ft=success&fm=image_deleted&image=$image";
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
					<a href=\"edit_food_images.php?food_id=$food_id&amp;l=$l\">$l_images</a>
					</p>
				<!-- //Where am I? -->


				<!-- Feedback -->
					";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->

				<!-- Delete -->

					
					<h2>$l_images</h2>

					";

					$current_photo_path = "";
					if($image == "a" && file_exists("../$get_current_food_image_path/$get_current_food_image_a")){
						$current_photo_path = "$get_current_food_image_path/$get_current_food_image_a";
					}
					elseif($image == "b" && file_exists("../$get_current_food_image_path/$get_current_food_image_b")){
						$current_photo_path = "$get_current_food_image_path/$get_current_food_image_b";
					}
					elseif($image == "c" && file_exists("../$get_current_food_image_path/$get_current_food_image_c")){
						$current_photo_path = "$get_current_food_image_path/$get_current_food_image_c";
					}
					elseif($image == "d" && file_exists("../$get_current_food_image_path/$get_current_food_image_d")){
						$current_photo_path = "$get_current_food_image_path/$get_current_food_image_d";
					}
					if($current_photo_path != ""){
						echo"
						<p>$l_are_you_sure_you_want_to_delete
						$l_the_action_cant_be_undone
						</p>

						<p><img src=\"$root/$current_photo_path\" alt=\"$current_photo_path\" />
						</p>

						<p>
						<a href=\"edit_food_images.php?action=delete&amp;food_id=$food_id&amp;image=$image&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_delete</a>
						<a href=\"edit_food_images.php?food_id=$food_id&amp;l=$l\" class=\"btn btn_default\">$l_cancel</a>		
						</p>
						";
					}

					echo"
				<!-- //Delete -->

				<!-- Back -->
					<p>
					<a href=\"my_food.php?l=$l#food$get_current_food_id\" class=\"btn btn_default\">$l_my_food</a>
					</p>
				<!-- //Back -->

				";
			} // action == "rotate"
			elseif($action == "upload_new"){
				if($process == 1){
					// Delete all old thumbnails
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_small") && $get_current_food_thumb_a_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_medium") && $get_current_food_thumb_a_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_large") && $get_current_food_thumb_a_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_large");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_small") && $get_current_food_thumb_b_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_medium") && $get_current_food_thumb_b_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_large") && $get_current_food_thumb_b_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_large");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_small") && $get_current_food_thumb_c_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_medium") && $get_current_food_thumb_c_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_large") && $get_current_food_thumb_c_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_large");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_small") && $get_current_food_thumb_d_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_medium") && $get_current_food_thumb_d_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_large") && $get_current_food_thumb_d_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_large");
					}
						

					// Directory for storing
					if(!(is_dir("../_uploads"))){
						mkdir("../_uploads");
					}
					if(!(is_dir("../_uploads/food"))){
						mkdir("../_uploads/food");
					}
					if(!(is_dir("../_uploads/food/_img"))){
						mkdir("../_uploads/food/_img");
					}
					if(!(is_dir("../_uploads/food/_img/$l"))){
						mkdir("../_uploads/food/_img/$l");
					}
					if(!(is_dir("../_uploads/food/_img/$l/$get_current_food_id"))){
						mkdir("../_uploads/food/_img/$l/$get_current_food_id");
					}

					// Upload image a,b,c,d
					$ft_image_a = "";
					$fm_image_a = "";
					$ft_image_b = "";
					$fm_image_b = "";
					$ft_image_c = "";
					$fm_image_c = "";
					$ft_image_d = "";
					$fm_image_d = "";
					$inp_names_array = array("inp_food_image_a", "inp_food_image_b", "inp_food_image_c", "inp_food_image_d");
					for($x=0;$x<4;$x++){

						// Clean name
						$food_name_clean = clean($get_current_food_name);
						$food_manufacturer_name_clean = clean($get_current_food_manufacturer_name);


						/*- Image upload ------------------------------------------------------------------------------------------ */
						$name = stripslashes($_FILES["$inp_names_array[$x]"]['name']);
						$extension = get_extension($name);
						$extension = strtolower($extension);

						if($name){
							if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
								if($inp_names_array[$x] == "inp_food_image_a"){
									$ft_image_a = "warning";
									$fm_image_a = "unknown_file_extension";
								}
								elseif($inp_names_array[$x] == "inp_food_image_b"){
									$ft_image_b = "warning";
									$fm_image_b = "unknown_file_extension";
								}
								elseif($inp_names_array[$x] == "inp_food_image_c"){
									$ft_image_c = "warning";
									$fm_image_c = "unknown_file_extension";
								}
								elseif($inp_names_array[$x] == "inp_food_image_d"){
									$ft_image_d = "warning";
									$fm_image_d = "unknown_file_extension";
								}
							}
							else{
					
 
								// Give new name
								$food_manufacturer_name_clean = clean($get_current_food_manufacturer_name);

								$rand = rand(0, 1000);
								if($inp_names_array[$x] == "inp_food_image_a"){
									$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_a_$rand." . $extension;
								}
								elseif($inp_names_array[$x] == "inp_food_image_b"){
									$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_b_$rand." . $extension;
								}
								elseif($inp_names_array[$x] == "inp_food_image_c"){
									$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_c_$rand." . $extension;
								}
								elseif($inp_names_array[$x] == "inp_food_image_d"){
									$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_d_$rand." . $extension;
								}
								else{
									echo"image number?";
									die;
								}
						
								$new_path = "../_uploads/food/_img/$l/$get_current_food_id/";
								$uploaded_file = $new_path . $new_name;

								// Upload file
								if (move_uploaded_file($_FILES["$inp_names_array[$x]"]['tmp_name'], $uploaded_file)) {
	

									// Get image size
									$file_size = filesize($uploaded_file);
						
									// Check with and height
									list($width,$height) = getimagesize($uploaded_file);
		
									if($width == "" OR $height == ""){
										if($inp_names_array[$x] == "inp_food_image_a"){
											$ft_image_a = "warning";
											$fm_image_a = "getimagesize_failed";
										}
										elseif($inp_names_array[$x] == "inp_food_image_b"){
											$ft_image_b = "warning";
											$fm_image_b = "getimagesize_failed";
										}
										elseif($inp_names_array[$x] == "inp_food_image_c"){
											$ft_image_c = "warning";
											$fm_image_c = "getimagesize_failed";
										}
										elseif($inp_names_array[$x] == "inp_food_image_d"){
											$ft_image_d = "warning";
											$fm_image_d = "getimagesize_failed";
										}
										unlink($uploaded_file);
									}
									else{

										// Resize to 1280x1280
										$uploaded_file_new = $uploaded_file;
										if($width > $settings_image_width OR $height > $settings_image_height){
											resize_crop_image($settings_image_width, $settings_image_height, $uploaded_file, $uploaded_file_new, $quality = 80);
										}

										$inp_food_image_path = "_uploads/food/_img/$l/$get_current_food_id";
										$inp_food_image_path = output_html($inp_food_image_path);
										$inp_food_image_path_mysql = quote_smart($link, $inp_food_image_path);
										$inp_food_image_mysql = quote_smart($link, $new_name);

										// Thumb small
										$inp_thumb_name = str_replace(".$extension", "", $new_name);
										$inp_thumb_small = $inp_thumb_name . "_thumb_132x132_$rand." . $extension;
										$inp_thumb_small_mysql = quote_smart($link, $inp_thumb_small);
										resize_crop_image(132, 132, "$root/_uploads/food/_img/$l/$get_current_food_id/$new_name", "$root/_uploads/food/_img/$l/$get_current_food_id/$inp_thumb_small");
							

										// Thumb medium
										$inp_thumb_medium = $inp_thumb_name . "_thumb_200x200_$rand." . $extension;
										$inp_thumb_medium_mysql = quote_smart($link, $inp_thumb_medium);
										resize_crop_image(200, 200, "$root/_uploads/food/_img/$l/$get_current_food_id/$new_name", "$root/_uploads/food/_img/$l/$get_current_food_id/$inp_thumb_medium");
							

										// Logo over image
										// Config
										include("$root/_admin/_data/food.php");
										if($foodPrintLogoOnImagesSav == "1"){
											include("$root/_admin/_functions/stamp_image.php");
											include("$root/_admin/_data/logo.php");
											$stamp = "$logoFileStampImages1280x720Sav";
											list($width,$height) = getimagesize("$root/_uploads/food/_img/$l/$get_current_food_id/$new_name");

											if($width < 1280){ // Width less than 1280
												$stamp = "$logoFileStampImages1280x720Sav";
											}
											elseif($width > 1280 && $width < 1920){  // Width bigger than 1280 and less than 1920
												$stamp = "$logoFileStampImages1920x1080Sav";
											}
											elseif($width > 1921 && $width < 2560){
												$stamp = "$logoFileStampImages2560x1440Sav";
											}
											else{
												$stamp = "$logoFileStampImages7680x4320Sav";
											}
											stamp_image("$root/_uploads/food/_img/$l/$get_current_food_id/$new_name", "$root/$logoPathSav/$stamp");
										}



										if($inp_names_array[$x] == "inp_food_image_a"){
											$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_path=$inp_food_image_path_mysql, food_image_a=$inp_food_image_mysql, food_thumb_a_small=$inp_thumb_small_mysql, food_thumb_a_medium=$inp_thumb_medium_mysql, food_thumb_a_large='' WHERE food_id='$get_current_food_id'");
											$ft_image_a = "success";
											$fm_image_a = "image_uploaded";
										}
										elseif($inp_names_array[$x] == "inp_food_image_b"){
											$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_path=$inp_food_image_path_mysql, food_image_b=$inp_food_image_mysql, food_thumb_b_small=$inp_thumb_small_mysql, food_thumb_b_medium=$inp_thumb_medium_mysql, food_thumb_b_large='' WHERE food_id='$get_current_food_id'");
											$ft_image_b = "success";
											$fm_image_b = "image_uploaded";
										}
										elseif($inp_names_array[$x] == "inp_food_image_c"){
											$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_path=$inp_food_image_path_mysql, food_image_c=$inp_food_image_mysql, food_thumb_c_small=$inp_thumb_small_mysql, food_thumb_c_medium=$inp_thumb_medium_mysql, food_thumb_c_large='' WHERE food_id='$get_current_food_id'");
											$ft_image_c = "success";
											$fm_image_c = "image_uploaded";
										}
										elseif($inp_names_array[$x] == "inp_food_image_d"){
											$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_path=$inp_food_image_path_mysql, food_image_d=$inp_food_image_mysql, food_thumb_d_small=$inp_thumb_small_mysql, food_thumb_d_medium=$inp_thumb_medium_mysql, food_thumb_d_large='' WHERE food_id='$get_current_food_id'");
											$ft_image_d = "success";
											$fm_image_d = "image_uploaded";
										}

									}  // if($width == "" OR $height == ""){
								} // move_uploaded_file
								else{
									switch ($_FILES["$inp_names_array[$x]"]['error']) {
										case UPLOAD_ERR_OK:
           										if($inp_names_array[$x] == "inp_food_image_a"){
												$fm_image_a = "There is no error, the file uploaded with success.";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_b"){
												$fm_image_b = "There is no error, the file uploaded with success.";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_c"){
												$fm_image_c = "There is no error, the file uploaded with success.";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_d"){
												$fm_image_d = "There is no error, the file uploaded with success.";
											}
											break;
										case UPLOAD_ERR_NO_FILE:
           										// $fm_image = "no_file_uploaded";
											break;
										case UPLOAD_ERR_INI_SIZE:
           										if($inp_names_array[$x] == "inp_food_image_a"){
           											$fm_image_a = "to_big_size_in_configuration";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_b"){
												$fm_image_b = "to_big_size_in_configuration";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_c"){
												$fm_image_c = "to_big_size_in_configuration";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_d"){
												$fm_image_d = "to_big_size_in_configuration";
											}
											break;
										case UPLOAD_ERR_FORM_SIZE:
           										if($inp_names_array[$x] == "inp_food_image_a"){
           											$fm_image_a = "to_big_size_in_form";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_b"){
												$fm_image_b = "to_big_size_in_form";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_c"){
												$fm_image_c = "to_big_size_in_form";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_d"){
												$fm_image_d = "to_big_size_in_form";
											}
											break;
										default:
           										if($inp_names_array[$x] == "inp_food_image_a"){
           											$fm_image_a = "unknown_error";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_b"){
												$fm_image_b = "unknown_error";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_c"){
												$fm_image_c = "unknown_error";
											}
           										elseif($inp_names_array[$x] == "inp_food_image_d"){
												$fm_image_d = "unknown_error";
											}
											break;
									} // switch	
								}
	
							} // extension check
						} // if($image){

					} // for upload images




					// Search engine
					include("new_food_00_add_update_search_engine.php");

					// Feedback
					$url = "edit_food_images.php?food_id=$food_id&l=$l";
					$url = $url . "&ft_image_a=$ft_image_a&fm_image_a=$fm_image_a";
					$url = $url . "&ft_image_b=$ft_image_b&fm_image_b=$fm_image_b";
					$url = $url . "&ft_image_c=$ft_image_c&fm_image_c=$fm_image_c";
					$url = $url . "&ft_image_d=$ft_image_d&fm_image_d=$fm_image_d";
					header("Location: $url");
					exit;

				} // process == 1
			} // action == "upload new"
			elseif($action == "upload" && isset($_GET['image'])){
				$image = $_GET['image'];
				$image = strip_tags(stripslashes($image));
				if($image != "a" && $image != "b" && $image != "c" && $image != "d"){
					echo"Invalid variable image";
					die;
				}
				if($process == "1"){
					// Delete all old thumbnails
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_small") && $get_current_food_thumb_a_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_medium") && $get_current_food_thumb_a_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_a_large") && $get_current_food_thumb_a_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_a_large");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_small") && $get_current_food_thumb_b_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_medium") && $get_current_food_thumb_b_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_b_large") && $get_current_food_thumb_b_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_b_large");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_small") && $get_current_food_thumb_c_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_medium") && $get_current_food_thumb_c_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_c_large") && $get_current_food_thumb_c_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_c_large");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_small") && $get_current_food_thumb_d_small != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_small");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_medium") && $get_current_food_thumb_d_medium != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_medium");
					}
					if(file_exists("../$get_current_food_image_path/$get_current_food_thumb_d_large") && $get_current_food_thumb_d_large != ""){
						unlink("../$get_current_food_image_path/$get_current_food_thumb_d_large");
					}


					// Directory for storing
					if(!(is_dir("../_uploads"))){
						mkdir("../_uploads");
					}
					if(!(is_dir("../_uploads/food"))){
						mkdir("../_uploads/food");
					}
					if(!(is_dir("../_uploads/food/_img"))){
						mkdir("../_uploads/food/_img");
					}
					if(!(is_dir("../_uploads/food/_img/$l"))){
						mkdir("../_uploads/food/_img/$l");
					}
					if(!(is_dir("../_uploads/food/_img/$l/$get_current_food_id"))){
						mkdir("../_uploads/food/_img/$l/$get_current_food_id");
					}




					$name = stripslashes($_FILES["inp_image"]['name']);
					$extension = get_extension($name);
					$extension = strtolower($extension);

					if($name){
						if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
							$ft_image = "warning";
							$fm_image = "unknown_file_extension";
						}
						else{

							// Clean name
							$food_name_clean = clean($get_current_food_name);
							$food_manufacturer_name_clean = clean($get_current_food_manufacturer_name);

							// Give new unique name
							$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_" . $image . "." . $extension;
		
							$new_path = "../_uploads/food/_img/$l/$get_current_food_id/";
							$uploaded_file = $new_path . $new_name;

							// Upload file
							if (move_uploaded_file($_FILES["inp_image"]['tmp_name'], $uploaded_file)) {
	

								// Get image size
								$file_size = filesize($uploaded_file);
						
								// Check with and height
								list($width,$height) = getimagesize($uploaded_file);
		
								if($width == "" OR $height == ""){
									$ft_image = "warning";
									$fm_image = "getimagesize_failed";
									unlink($uploaded_file);
								}
								else{
									// Resize to 1280x1280
									$uploaded_file_new = $uploaded_file;
									if($width > $settings_image_width OR $height > $settings_image_height){
										// resize_crop_image($settings_image_width, $settings_image_height, $uploaded_file, $uploaded_file_new, $quality = 80);
									}
				
									// Give feedback starts
				
									// Image path
									$inp_image_path = "_uploads/$new_name";
			

									// Thumb
									$inp_thumb_name = str_replace(".$extension", "", $new_name);
									$inp_thumb  = $inp_thumb_name . "_thumb_200x200." . $extension;
									// resize_crop_image(200, 200, "_uploads/$new_name", "_uploads/$inp_thumb");
							
									// Give feedback
									$ft_image = "success";
									$fm_image = "image_uploaded";
								}
							} // move_uploaded_file
							else{
								switch ($_FILES["$inp_names_array[$x]"]['error']) {
									case UPLOAD_ERR_OK:
           									$fm_image = "There is no error, the file uploaded with success.";
										break;
									case UPLOAD_ERR_NO_FILE:
           									// $fm_image = "no_file_uploaded";
										break;
									case UPLOAD_ERR_INI_SIZE:
           									$fm_image = "to_big_size_in_configuration";
										break;
									case UPLOAD_ERR_FORM_SIZE:
           									$fm_image = "to_big_size_in_form";
										break;
									default:
           									$fm_image = "unknown_error";
										break;
									} // switch	
							}
						} // extension check
					} // if($image){

				}
				echo"
				<h1>$l_upload</h1>

				<!-- cropzee.js -->
					<script src=\"$root/_admin/_javascripts/cropzee/cropzee.js\" defer></script>
					<style>
						.image-previewer {
							height: 300px;
							width: 300px;
							display: flex;
							border-radius: 10px;
							border: 1px solid lightgrey;
						}
					</style>
				<!-- //cropzee.js -->


				<!-- cropzee upload form -->
					<form method=\"post\" action=\"edit_food_images.php?action=upload&amp;food_id=$get_current_food_id&amp;image=$image&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

					
					<label for=\"cropzee-input\" class=\"image-previewer\" data-cropzee=\"cropzee-input\"></label>
					<input id=\"cropzee-input\" type=\"file\" name=\"inp_image\" accept=\"image/*\">
					<input type=\"submit\" onclick=\"cropzeeGetImage('cropzee-input')\" value=\"Get Image (as blob / data-url)\" />
					<script>
						\$(document).ready(function(){
							if (window.location.href.indexOf(\"#\") > -1) {
								window.location = window.location.href.replace('#', '');
							}
							\$(\"#cropzee-input\").cropzee({startSize: [85, 85, '%'],});
						});
					</script>
					</form>
				<!-- //cropzee upload form -->

				";
			} // action == "upload"
		}
	}
	else{
		echo"<p>Please log in</p>";
	}
}
/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>