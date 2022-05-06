<?php
/**
*
* File: food_diary/food_diary_edit_entry.php
* Version 1.0.0.
* Date 12:42 21.01.2018
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

/*- Tables --------------------------------------------------------------------------- */
include("_tables.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food_diary/ts_index.php");


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_entry - $l_food_diary";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security']) && isset($_GET['entry_id'])) {

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_gender, user_height, user_measurement, user_dob FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_gender, $get_my_user_height, $get_my_user_measurement, $get_my_user_dob) = $row;
	
	// Get entry

	$entry_id = $_GET['entry_id'];
	$entry_id = strip_tags(stripslashes($entry_id));
	$entry_id_mysql = quote_smart($link, $entry_id);

	$query = "SELECT entry_id, entry_user_id, entry_date, entry_date_saying, entry_hour_name, entry_food_id, entry_recipe_id, entry_meal_id, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, entry_salt_per_entry, entry_sodium_per_entry, entry_text, entry_deleted, entry_updated_datetime, entry_synchronized FROM $t_food_diary_entires WHERE entry_id=$entry_id_mysql AND entry_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_entry_id, $get_current_entry_user_id, $get_current_entry_date, $get_current_entry_date_saying, $get_current_entry_hour_name, $get_current_entry_food_id, $get_current_entry_recipe_id, $get_current_entry_meal_id, $get_current_entry_name, $get_current_entry_manufacturer_name, $get_current_entry_serving_size, $get_current_entry_serving_size_measurement, $get_current_entry_energy_per_entry, $get_current_entry_fat_per_entry, $get_current_entry_saturated_fat_per_entry, $get_current_entry_monounsaturated_fat_per_entry, $get_current_entry_polyunsaturated_fat_per_entry, $get_current_entry_cholesterol_per_entry, $get_current_entry_carbohydrates_per_entry, $get_current_entry_carbohydrates_of_which_sugars_per_entry, $get_current_entry_dietary_fiber_per_entry, $get_current_entry_proteins_per_entry, $get_current_entry_salt_per_entry, $get_current_entry_sodium_per_entry, $get_current_entry_text, $get_current_entry_deleted, $get_current_entry_updated_datetime, $get_current_entry_synchronized) = $row;
	
	if($get_current_entry_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>Entry not found.</p>
		";
	}
	else{
		if($process == "1"){

			$inp_updated = date("Y-m-d H:i:s");
			$inp_updated_mysql = quote_smart($link, $inp_updated);


			// Calculate
			if($get_current_entry_food_id != "0" && $get_current_entry_recipe_id == "0" && $get_current_entry_meal_id == "0"){
				$inp_entry_food_serving_size = $_POST['inp_entry_food_serving_size'];
				$inp_entry_food_serving_size = output_html($inp_entry_food_serving_size);
				$inp_entry_food_serving_size = str_replace(",", ".", $inp_entry_food_serving_size);
				$inp_entry_food_serving_size_mysql = quote_smart($link, $inp_entry_food_serving_size);

				// get food
				$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$get_current_entry_food_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;



				// Gram or pcs?
				if (isset($_POST['inp_submit_metric'])) {
					// Gram
					$inp_entry_food_serving_size_measurement = output_html($get_food_serving_size_measurement_metric);
					$inp_entry_food_serving_size_measurement_mysql = quote_smart($link, $inp_entry_food_serving_size_measurement);

					$inp_entry_food_energy_per_entry = round(($inp_entry_food_serving_size*$get_food_energy_metric)/100, 1);
					$inp_entry_food_energy_per_entry_mysql = quote_smart($link, $inp_entry_food_energy_per_entry);

					$inp_entry_food_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_fat_metric)/100, 1);
					$inp_entry_food_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_fat_per_entry);

					$inp_entry_food_saturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_saturated_fat_metric)/100, 1);
					$inp_entry_food_saturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_saturated_fat_per_entry);

					$inp_entry_food_monounsaturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_monounsaturated_fat_metric)/100, 1);
					$inp_entry_food_monounsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_monounsaturated_fat_per_entry);

					$inp_entry_food_polyunsaturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_polyunsaturated_fat_metric)/100, 1);
					$inp_entry_food_polyunsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_polyunsaturated_fat_per_entry);

					$inp_entry_food_cholesterol_per_entry = round(($inp_entry_food_serving_size*$get_food_cholesterol_metric)/100, 0);
					$inp_entry_food_cholesterol_per_entry_mysql = quote_smart($link, $inp_entry_food_cholesterol_per_entry);

					$inp_entry_food_carb_per_entry = round(($inp_entry_food_serving_size*$get_food_carbohydrates_metric)/100, 1);
					$inp_entry_food_carb_per_entry_mysql = quote_smart($link, $inp_entry_food_carb_per_entry);
	
					$inp_entry_food_carbohydrates_of_which_sugars_per_entry = round(($inp_entry_food_serving_size*$get_food_carbohydrates_of_which_sugars_metric)/100, 1);
					$inp_entry_food_carbohydrates_of_which_sugars_per_entry_mysql = quote_smart($link, $inp_entry_food_carbohydrates_of_which_sugars_per_entry);

					$inp_entry_food_dietary_fiber_per_entry = round(($inp_entry_food_serving_size*$get_food_dietary_fiber_metric)/100, 1);
					$inp_entry_food_dietary_fiber_per_entry_mysql = quote_smart($link, $inp_entry_food_dietary_fiber_per_entry);

					$inp_entry_food_protein_per_entry = round(($inp_entry_food_serving_size*$get_food_proteins_metric)/100, 1);
					$inp_entry_food_protein_per_entry_mysql = quote_smart($link, $inp_entry_food_protein_per_entry);

					$inp_entry_food_salt_per_entry = round(($inp_entry_food_serving_size*$get_food_salt_metric)/100, 1);
					$inp_entry_food_salt_per_entry_mysql = quote_smart($link, $inp_entry_food_salt_per_entry);

					$inp_entry_food_sodium_per_entry = round(($inp_entry_food_serving_size*$get_food_sodium_metric)/100, 1);
					$inp_entry_food_sodium_per_entry_mysql = quote_smart($link, $inp_entry_food_sodium_per_entry);

			
				} // metric gram/ml
				else{
					if (isset($_POST['inp_submit_us'])) {
						echo"No yet implimented";
						die;
					}
					else{
						// PCS
						$inp_entry_food_serving_size_measurement = output_html($get_food_serving_size_pcs_measurement);
						$inp_entry_food_serving_size_measurement_mysql = quote_smart($link, $inp_entry_food_serving_size_measurement);

						$inp_entry_food_energy_per_entry = round(($inp_entry_food_serving_size*$get_food_energy_calculated_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_energy_per_entry_mysql = quote_smart($link, $inp_entry_food_energy_per_entry);

						$inp_entry_food_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_fat_calculated_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_fat_per_entry);

						$inp_entry_food_saturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_saturated_fat_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_saturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_saturated_fat_per_entry);

						$inp_entry_food_monounsaturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_monounsaturated_fat_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_monounsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_monounsaturated_fat_per_entry);

						$inp_entry_food_polyunsaturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_polyunsaturated_fat_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_polyunsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_polyunsaturated_fat_per_entry);

						$inp_entry_food_cholesterol_per_entry = round(($inp_entry_food_serving_size*$get_food_cholesterol_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_cholesterol_per_entry_mysql = quote_smart($link, $inp_entry_food_cholesterol_per_entry);


						$inp_entry_food_carb_per_entry = round(($inp_entry_food_serving_size*$get_food_carbohydrates_calculated_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_carb_per_entry_mysql = quote_smart($link, $inp_entry_food_carb_per_entry);

						$inp_entry_food_carbohydrates_of_which_sugars_per_entry = round(($inp_entry_food_serving_size*$get_food_carbohydrates_of_which_sugars_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_carbohydrates_of_which_sugars_per_entry_mysql = quote_smart($link, $inp_entry_food_carbohydrates_of_which_sugars_per_entry);

						$inp_entry_food_dietary_fiber_per_entry = round(($inp_entry_food_serving_size*$get_food_dietary_fiber_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_dietary_fiber_per_entry_mysql = quote_smart($link, $inp_entry_food_dietary_fiber_per_entry);

						$inp_entry_food_protein_per_entry = round(($inp_entry_food_serving_size*$get_food_proteins_calculated_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_protein_per_entry_mysql = quote_smart($link, $inp_entry_food_protein_per_entry);

						$inp_entry_food_salt_per_entry = round(($inp_entry_food_serving_size*$get_food_salt_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_salt_per_entry_mysql = quote_smart($link, $inp_entry_food_salt_per_entry);

						$inp_entry_food_sodium_per_entry = round(($inp_entry_food_serving_size*$get_food_sodium_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_sodium_per_entry_mysql = quote_smart($link, $inp_entry_food_sodium_per_entry);
					} // metric PCS
				} // US oz, US fl oz, US pcs, metric pcs

			

				$result = mysqli_query($link, "UPDATE $t_food_diary_entires SET 
								entry_serving_size=$inp_entry_food_serving_size_mysql, 
								entry_serving_size_measurement=$inp_entry_food_serving_size_measurement_mysql, 
								entry_energy_per_entry=$inp_entry_food_energy_per_entry_mysql,  
								entry_fat_per_entry=$inp_entry_food_fat_per_entry_mysql,  
								entry_saturated_fat_per_entry=$inp_entry_food_saturated_fat_per_entry_mysql, 
								entry_monounsaturated_fat_per_entry=$inp_entry_food_monounsaturated_fat_per_entry_mysql,  
								entry_polyunsaturated_fat_per_entry=$inp_entry_food_polyunsaturated_fat_per_entry_mysql, 
								entry_cholesterol_per_entry=$inp_entry_food_cholesterol_per_entry_mysql, 
								entry_carbohydrates_per_entry=$inp_entry_food_carb_per_entry_mysql, 
								entry_carbohydrates_of_which_sugars_per_entry=$inp_entry_food_carbohydrates_of_which_sugars_per_entry_mysql, 
								entry_dietary_fiber_per_entry=$inp_entry_food_dietary_fiber_per_entry_mysql, 
								entry_proteins_per_entry=$inp_entry_food_protein_per_entry_mysql,
								entry_salt_per_entry=$inp_entry_food_salt_per_entry_mysql, 
								entry_sodium_per_entry=$inp_entry_food_sodium_per_entry_mysql, 
								entry_updated_datetime='$datetime', 
								entry_synchronized=0
								WHERE entry_id=$entry_id_mysql AND entry_user_id=$my_user_id_mysql");

			} // food
			elseif($get_current_entry_food_id == "0" && $get_current_entry_recipe_id != "0" && $get_current_entry_meal_id == "0"){
				// get recipe
				$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$get_current_entry_recipe_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

				// get numbers
				$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
				$result_n = mysqli_query($link, $query_n);
				$row_n = mysqli_fetch_row($result_n);
				list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;


				$inp_entry_serving_size = $_POST['inp_entry_serving_size'];
				$inp_entry_serving_size = output_html($inp_entry_serving_size);
				$inp_entry_serving_size = str_replace(",", ".", $inp_entry_serving_size);
				$inp_entry_serving_size_mysql = quote_smart($link, $inp_entry_serving_size);

				// Number inputs
				$inp_entry_energy_per_entry = round($inp_entry_serving_size*$get_number_energy_serving, 1);
				$inp_entry_energy_per_entry_mysql = quote_smart($link, $inp_entry_energy_per_entry);

				$inp_entry_fat_per_entry = round($inp_entry_serving_size*$get_number_fat_serving, 1);
				$inp_entry_fat_per_entry_mysql = quote_smart($link, $inp_entry_fat_per_entry);

				$inp_entry_saturated_fat_per_entry = round($inp_entry_serving_size*$get_number_saturated_fat_serving, 1);
				$inp_entry_saturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_saturated_fat_per_entry);

				$inp_entry_monounsaturated_fat_per_entry = round($inp_entry_serving_size*$get_number_monounsaturated_fat_serving, 1);
				$inp_entry_monounsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_monounsaturated_fat_per_entry);

				$inp_entry_polyunsaturated_fat_per_entry = round($inp_entry_serving_size*$get_number_polyunsaturated_fat_serving, 1);
				$inp_entry_polyunsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_polyunsaturated_fat_per_entry);

				$inp_entry_cholesterol_per_entry = round($inp_entry_serving_size*$get_number_cholesterol_serving, 1);
				$inp_entry_cholesterol_per_entry_mysql = quote_smart($link, $inp_entry_cholesterol_per_entry);

				$inp_entry_carbohydrates_per_entry = round($inp_entry_serving_size*$get_number_carbohydrates_serving, 1);
				$inp_entry_carbohydrates_per_entry_mysql = quote_smart($link, $inp_entry_carbohydrates_per_entry);

				$inp_entry_carbohydrates_of_which_sugars_per_entry = round($inp_entry_serving_size*$get_number_carbohydrates_of_which_sugars_serving, 1);
				$inp_entry_carbohydrates_of_which_sugars_per_entry_mysql = quote_smart($link, $inp_entry_carbohydrates_of_which_sugars_per_entry);

				$inp_entry_dietary_fiber_per_entry = round($inp_entry_serving_size*$get_number_dietary_fiber_serving, 1);
				$inp_entry_dietary_fiber_per_entry_mysql = quote_smart($link, $inp_entry_dietary_fiber_per_entry);

				$inp_entry_proteins_per_entry = round($inp_entry_serving_size*$get_number_proteins_serving, 1);
				$inp_entry_proteins_per_entry_mysql = quote_smart($link, $inp_entry_proteins_per_entry);

				$inp_entry_salt_per_entry = round($inp_entry_serving_size*$get_number_salt_serving, 1);
				$inp_entry_salt_per_entry_mysql = quote_smart($link, $inp_entry_salt_per_entry);

				$inp_entry_sodium_per_entry = round($inp_entry_serving_size*$get_number_sodium_serving, 1);
				$inp_entry_sodium_per_entry_mysql = quote_smart($link, $inp_entry_sodium_per_entry);


				$result = mysqli_query($link, "UPDATE $t_food_diary_entires SET 
								entry_serving_size=$inp_entry_serving_size_mysql, 
								entry_energy_per_entry=$inp_entry_energy_per_entry_mysql,  
								entry_fat_per_entry=$inp_entry_fat_per_entry_mysql,  
								entry_saturated_fat_per_entry=$inp_entry_saturated_fat_per_entry_mysql, 
								entry_monounsaturated_fat_per_entry=$inp_entry_monounsaturated_fat_per_entry_mysql,  
								entry_polyunsaturated_fat_per_entry=$inp_entry_polyunsaturated_fat_per_entry_mysql, 
								entry_cholesterol_per_entry=$inp_entry_cholesterol_per_entry_mysql, 
								entry_carbohydrates_per_entry=$inp_entry_carbohydrates_per_entry_mysql, 
								entry_carbohydrates_of_which_sugars_per_entry=$inp_entry_carbohydrates_of_which_sugars_per_entry_mysql, 
								entry_dietary_fiber_per_entry=$inp_entry_dietary_fiber_per_entry_mysql, 
								entry_proteins_per_entry=$inp_entry_proteins_per_entry_mysql,
								entry_salt_per_entry=$inp_entry_salt_per_entry_mysql, 
								entry_sodium_per_entry=$inp_entry_sodium_per_entry_mysql, 
								entry_updated_datetime='$datetime', 
								entry_synchronized=0
								WHERE entry_id=$entry_id_mysql AND entry_user_id=$my_user_id_mysql");



			} // recipe
			elseif($get_current_entry_food_id == "0" && $get_current_entry_recipe_id == "0" && $get_current_entry_meal_id != "0"){
				// get meal
				$query_n = "SELECT meal_id, meal_user_id, meal_hour_name, meal_last_used_date, meal_used_times, meal_entries, meal_entries_count, meal_selected_serving_size, meal_selected_measurement, meal_energy_serving, meal_fat_serving, meal_saturated_fat_serving, meal_monounsaturated_fat_serving, meal_polyunsaturated_fat_serving, meal_cholesterol_serving, meal_carbohydrates_serving, meal_carbohydrates_of_which_sugars_serving, meal_dietary_fiber_serving, meal_proteins_serving, meal_salt_serving, meal_sodium_serving, meal_energy_total, meal_fat_total, meal_saturated_total, meal_monounsaturated_fat_total, meal_polyunsaturated_fat_total, meal_cholesterol_total, meal_carbohydrates_total, meal_carbohydrates_of_which_sugars_total, meal_dietary_fiber_total, meal_proteins_total, meal_salt_total, meal_sodium_total FROM $t_food_diary_meals_index WHERE meal_id=$get_current_entry_meal_id";
				$result_n = mysqli_query($link, $query_n);
				$row_n = mysqli_fetch_row($result_n);
				list($get_meal_id, $get_meal_user_id, $get_meal_hour_name, $get_meal_last_used_date, $get_meal_used_times, $get_meal_entries, $get_meal_entries_count, $get_meal_selected_serving_size, $get_meal_selected_measurement, $get_meal_energy_serving, $get_meal_fat_serving, $get_meal_saturated_fat_serving, $get_meal_monounsaturated_fat_serving, $get_meal_polyunsaturated_fat_serving, $get_meal_cholesterol_serving, $get_meal_carbohydrates_serving, $get_meal_carbohydrates_of_which_sugars_serving, $get_meal_dietary_fiber_serving, $get_meal_proteins_serving, $get_meal_salt_serving, $get_meal_sodium_serving, $get_meal_energy_total, $get_meal_fat_total, $get_meal_saturated_total, $get_meal_monounsaturated_fat_total, $get_meal_polyunsaturated_fat_total, $get_meal_cholesterol_total, $get_meal_carbohydrates_total, $get_meal_carbohydrates_of_which_sugars_total, $get_meal_dietary_fiber_total, $get_meal_proteins_total, $get_meal_salt_total, $get_meal_sodium_total) = $row_n;


				$inp_entry_serving_size = $_POST['inp_entry_serving_size'];
				$inp_entry_serving_size = output_html($inp_entry_serving_size);
				$inp_entry_serving_size = str_replace(",", ".", $inp_entry_serving_size);
				$inp_entry_serving_size_mysql = quote_smart($link, $inp_entry_serving_size);

				// Number inputs
				$inp_entry_energy_per_entry = round($get_meal_energy_serving*$inp_entry_serving_size, 0);
				$inp_entry_energy_per_entry_mysql = quote_smart($link, $inp_entry_energy_per_entry);

				$inp_entry_fat_per_entry = round($get_meal_fat_serving*$inp_entry_serving_size, 0);
				$inp_entry_fat_per_entry_mysql = quote_smart($link, $inp_entry_fat_per_entry);

				$inp_entry_saturated_fat_per_entry = round($get_meal_saturated_fat_serving*$inp_entry_serving_size, 0);
				$inp_entry_saturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_saturated_fat_per_entry);

				$inp_entry_monounsaturated_fat_per_entry = round($get_meal_monounsaturated_fat_serving*$inp_entry_serving_size, 0);
				$inp_entry_monounsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_monounsaturated_fat_per_entry);

				$inp_entry_polyunsaturated_fat_per_entry = round($get_meal_polyunsaturated_fat_serving*$inp_entry_serving_size, 0);
				$inp_entry_polyunsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_polyunsaturated_fat_per_entry);

				$inp_entry_cholesterol_per_entry = round($get_meal_cholesterol_serving*$inp_entry_serving_size, 0);
				$inp_entry_cholesterol_per_entry_mysql = quote_smart($link, $inp_entry_cholesterol_per_entry);

				$inp_entry_carb_per_entry = round($get_meal_carbohydrates_serving*$inp_entry_serving_size, 0);
				$inp_entry_carb_per_entry_mysql = quote_smart($link, $inp_entry_carb_per_entry);

				$inp_entry_carbohydrates_of_which_sugars_per_entry = round($get_meal_carbohydrates_of_which_sugars_serving*$inp_entry_serving_size, 0);
				$inp_entry_carbohydrates_of_which_sugars_per_entry_mysql = quote_smart($link, $inp_entry_carbohydrates_of_which_sugars_per_entry);

				$inp_entry_dietary_fiber_per_entry = round($get_meal_dietary_fiber_serving*$inp_entry_serving_size, 0);
				$inp_entry_dietary_fiber_per_entry_mysql = quote_smart($link, $inp_entry_dietary_fiber_per_entry);

				$inp_entry_protein_per_entry = round($get_meal_proteins_serving*$inp_entry_serving_size, 0);
				$inp_entry_protein_per_entry_mysql = quote_smart($link, $inp_entry_protein_per_entry);

				$inp_entry_salt_per_entry = round($get_meal_salt_serving*$inp_entry_serving_size, 0);
				$inp_entry_salt_per_entry_mysql = quote_smart($link, $inp_entry_salt_per_entry);
	
				$inp_entry_sodium_per_entry = round($get_meal_sodium_serving*$inp_entry_serving_size, 0);
				$inp_entry_sodium_per_entry_mysql = quote_smart($link, $inp_entry_sodium_per_entry);


				$result = mysqli_query($link, "UPDATE $t_food_diary_entires SET 
								entry_serving_size=$inp_entry_serving_size_mysql, 
								entry_energy_per_entry=$inp_entry_energy_per_entry_mysql,  
								entry_fat_per_entry=$inp_entry_fat_per_entry_mysql,  
								entry_saturated_fat_per_entry=$inp_entry_saturated_fat_per_entry_mysql, 
								entry_monounsaturated_fat_per_entry=$inp_entry_monounsaturated_fat_per_entry_mysql,  
								entry_polyunsaturated_fat_per_entry=$inp_entry_polyunsaturated_fat_per_entry_mysql, 
								entry_cholesterol_per_entry=$inp_entry_cholesterol_per_entry_mysql, 
								entry_carbohydrates_per_entry=$inp_entry_carb_per_entry_mysql, 
								entry_carbohydrates_of_which_sugars_per_entry=$inp_entry_carbohydrates_of_which_sugars_per_entry_mysql, 
								entry_dietary_fiber_per_entry=$inp_entry_dietary_fiber_per_entry_mysql, 
								entry_proteins_per_entry=$inp_entry_protein_per_entry_mysql,
								entry_salt_per_entry=$inp_entry_salt_per_entry_mysql, 
								entry_sodium_per_entry=$inp_entry_sodium_per_entry_mysql, 
								entry_updated_datetime='$datetime', 
								entry_synchronized=0
								WHERE entry_id=$entry_id_mysql AND entry_user_id=$my_user_id_mysql");
				
				$result = mysqli_query($link, "UPDATE $t_food_diary_meals_index SET 
								meal_selected_serving_size=$inp_entry_serving_size_mysql, 
								meal_energy_total=$inp_entry_energy_per_entry_mysql,  
								meal_fat_total=$inp_entry_fat_per_entry_mysql,  
								meal_saturated_total=$inp_entry_saturated_fat_per_entry_mysql, 
								meal_monounsaturated_fat_total=$inp_entry_monounsaturated_fat_per_entry_mysql,  
								meal_polyunsaturated_fat_total=$inp_entry_polyunsaturated_fat_per_entry_mysql, 
								meal_cholesterol_total=$inp_entry_cholesterol_per_entry_mysql, 
								meal_carbohydrates_total=$inp_entry_carb_per_entry_mysql, 
								meal_carbohydrates_of_which_sugars_total=$inp_entry_carbohydrates_of_which_sugars_per_entry_mysql, 
								meal_dietary_fiber_total=$inp_entry_dietary_fiber_per_entry_mysql, 
								meal_proteins_total=$inp_entry_protein_per_entry_mysql,
								meal_salt_total=$inp_entry_salt_per_entry_mysql, 
								meal_sodium_total=$inp_entry_sodium_per_entry_mysql
								WHERE meal_id=$get_current_entry_meal_id");
				
			} // meal

			
			// 2) Update Consumed Hours (Example breakfast, lunch, dinner)
			$inp_hour_energy = 0;
			$inp_hour_fat = 0;
			$inp_hour_saturated_fat = 0;
			$inp_hour_monounsaturated_fat = 0;
			$inp_hour_polyunsaturated_fat = 0;
			$inp_hour_cholesterol = 0;
			$inp_hour_carbohydrates = 0;
			$inp_hour_carbohydrates_of_which_sugars = 0;
			$inp_hour_dietary_fiber = 0;
			$inp_hour_proteins = 0;
			$inp_hour_salt = 0;
			$inp_hour_sodium = 0;
			
			if($get_current_entry_hour_name == ""){
				echo"Error - current_entry_hour_name is blank"; 
				die;
			}
			$hour_name_mysql = quote_smart($link, $get_current_entry_hour_name);
			$query = "SELECT entry_id, entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, entry_salt_per_entry, entry_sodium_per_entry FROM $t_food_diary_entires WHERE entry_user_id=$my_user_id_mysql AND entry_date='$get_current_entry_date' AND entry_hour_name=$hour_name_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
    				list($get_entry_id, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_saturated_fat_per_entry, $get_entry_monounsaturated_fat_per_entry, $get_entry_polyunsaturated_fat_per_entry, $get_entry_cholesterol_per_entry, $get_entry_carbohydrates_per_entry, $get_entry_carbohydrates_of_which_sugars_per_entry, $get_entry_dietary_fiber_per_entry, $get_entry_proteins_per_entry, $get_entry_salt_per_entry, $get_entry_sodium_per_entry) = $row;

				$inp_hour_energy = $inp_hour_energy+$get_entry_energy_per_entry;
				$inp_hour_fat = $inp_hour_fat+$get_entry_fat_per_entry;
				$inp_hour_saturated_fat = $inp_hour_saturated_fat+$get_entry_saturated_fat_per_entry;
				$inp_hour_monounsaturated_fat = $inp_hour_monounsaturated_fat+$get_entry_monounsaturated_fat_per_entry;
				$inp_hour_polyunsaturated_fat = $inp_hour_polyunsaturated_fat+$get_entry_polyunsaturated_fat_per_entry;
				$inp_hour_cholesterol = $inp_hour_cholesterol+$get_entry_cholesterol_per_entry;
				$inp_hour_carbohydrates = $inp_hour_carbohydrates+$get_entry_carbohydrates_per_entry;
				$inp_hour_carbohydrates_of_which_sugars = $inp_hour_carbohydrates_of_which_sugars+$get_entry_carbohydrates_of_which_sugars_per_entry;
				$inp_hour_dietary_fiber = $inp_hour_dietary_fiber+$get_entry_dietary_fiber_per_entry;
				$inp_hour_proteins = $inp_hour_proteins+$get_entry_proteins_per_entry;
				$inp_hour_salt = $inp_hour_salt+$get_entry_salt_per_entry;
				$inp_hour_sodium = $inp_hour_sodium+$get_entry_sodium_per_entry;
				
			}

			$inp_hour_energy = round($inp_hour_energy, 0);
			$inp_hour_fat = round($inp_hour_fat, 0);
			$inp_hour_saturated_fat = round($inp_hour_saturated_fat, 0);
			$inp_hour_monounsaturated_fat = round($inp_hour_monounsaturated_fat, 0);
			$inp_hour_polyunsaturated_fat = round($inp_hour_polyunsaturated_fat, 0);
			$inp_hour_cholesterol = round($inp_hour_cholesterol, 0);
			$inp_hour_carbohydrates = round($inp_hour_carbohydrates, 0);
			$inp_hour_carbohydrates_of_which_sugars = round($inp_hour_carbohydrates_of_which_sugars, 0);
			$inp_hour_dietary_fiber = round($inp_hour_dietary_fiber, 0);
			$inp_hour_proteins = round($inp_hour_proteins, 0);
			$inp_hour_salt = round($inp_hour_salt, 0);
			$inp_hour_sodium = round($inp_hour_sodium, 0);
			
			$date = date("Y-m-d");
			$datetime = date("Y-m-d H:i:s");

			$result = mysqli_query($link, "UPDATE $t_food_diary_consumed_hours SET 
							consumed_hour_energy=$inp_hour_energy,
							consumed_hour_fat=$inp_hour_fat,
							consumed_hour_saturated_fat='$inp_hour_saturated_fat',
							consumed_hour_monounsaturated_fat='$inp_hour_monounsaturated_fat',
							consumed_hour_polyunsaturated_fat='$inp_hour_polyunsaturated_fat',
							consumed_hour_cholesterol='$inp_hour_cholesterol',
							consumed_hour_carbohydrates='$inp_hour_carbohydrates',
							consumed_hour_carbohydrates_of_which_sugars='$inp_hour_carbohydrates_of_which_sugars',
							consumed_hour_dietary_fiber='$inp_hour_dietary_fiber',
							consumed_hour_proteins='$inp_hour_proteins',
							consumed_hour_salt='$inp_hour_salt',
							consumed_hour_sodium='$inp_hour_sodium',
							consumed_hour_updated_datetime='$datetime',
							consumed_hour_synchronized=0
							 WHERE consumed_hour_user_id=$my_user_id_mysql AND consumed_hour_date='$get_current_entry_date' AND consumed_hour_name=$hour_name_mysql") or die(mysqli_error($link));

			// 3) Update Consumed Days (first calculate calories, fat etc used)
			$inp_consumed_day_energy = 0;
			$inp_consumed_day_fat = 0;
			$inp_consumed_day_saturated_fat = 0;
			$inp_consumed_day_monounsaturated_fat = 0;
			$inp_consumed_day_polyunsaturated_fat = 0;
			$inp_consumed_day_cholesterol = 0;
			$inp_consumed_day_carbohydrates = 0;
			$inp_consumed_day_carbohydrates_of_which_sugars = 0;
			$inp_consumed_day_dietary_fiber = 0;
			$inp_consumed_day_proteins = 0;
			$inp_consumed_day_salt = 0;
			$inp_consumed_day_sodium = 0;
			
			$query = "SELECT entry_id, entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, entry_salt_per_entry, entry_sodium_per_entry FROM $t_food_diary_entires WHERE entry_user_id=$my_user_id_mysql AND entry_date='$get_current_entry_date'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
    				list($get_entry_id, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_saturated_fat_per_entry, $get_entry_monounsaturated_fat_per_entry, $get_entry_polyunsaturated_fat_per_entry, $get_entry_cholesterol_per_entry, $get_entry_carbohydrates_per_entry, $get_entry_carbohydrates_of_which_sugars_per_entry, $get_entry_dietary_fiber_per_entry, $get_entry_proteins_per_entry, $get_entry_salt_per_entry, $get_entry_sodium_per_entry) = $row;

				$inp_consumed_day_energy 			= $inp_consumed_day_energy+$get_entry_energy_per_entry;
				$inp_consumed_day_fat 				= $inp_consumed_day_fat+$get_entry_fat_per_entry;
				$inp_consumed_day_saturated_fat 		= $inp_consumed_day_saturated_fat+$get_entry_saturated_fat_per_entry;
				$inp_consumed_day_monounsaturated_fat 		= $inp_consumed_day_monounsaturated_fat+$get_entry_monounsaturated_fat_per_entry;
				$inp_consumed_day_polyunsaturated_fat 		= $inp_consumed_day_polyunsaturated_fat+$get_entry_polyunsaturated_fat_per_entry;
				$inp_consumed_day_cholesterol 			= $inp_consumed_day_cholesterol+$get_entry_cholesterol_per_entry;
				$inp_consumed_day_carbohydrates 		= $inp_consumed_day_carbohydrates+$get_entry_carbohydrates_per_entry;
				$inp_consumed_day_carbohydrates_of_which_sugars = $inp_consumed_day_carbohydrates_of_which_sugars+$get_entry_carbohydrates_of_which_sugars_per_entry;
				$inp_consumed_day_dietary_fiber 		= $inp_consumed_day_dietary_fiber+$get_entry_dietary_fiber_per_entry;
				$inp_consumed_day_proteins 			= $inp_consumed_day_proteins+$get_entry_proteins_per_entry;
				$inp_consumed_day_salt 				= $inp_consumed_day_salt+$get_entry_salt_per_entry;
				$inp_consumed_day_sodium 			= $inp_consumed_day_sodium+$get_entry_sodium_per_entry;
				
			}

			$inp_consumed_day_energy 			= round($inp_consumed_day_energy, 0);
			$inp_consumed_day_fat 				= round($inp_consumed_day_fat, 0);
			$inp_consumed_day_saturated_fat 		= round($inp_consumed_day_saturated_fat, 0);
			$inp_consumed_day_monounsaturated_fat 		= round($inp_consumed_day_monounsaturated_fat, 0);
			$inp_consumed_day_polyunsaturated_fat 		= round($inp_consumed_day_polyunsaturated_fat, 0);
			$inp_consumed_day_cholesterol 			= round($inp_consumed_day_cholesterol, 0);
			$inp_consumed_day_carbohydrates 		= round($inp_consumed_day_carbohydrates, 0);
			$inp_consumed_day_carbohydrates_of_which_sugars = round($inp_consumed_day_carbohydrates_of_which_sugars, 0);
			$inp_consumed_day_dietary_fiber 		= round($inp_consumed_day_dietary_fiber, 0);
			$inp_consumed_day_proteins 			= round($inp_consumed_day_proteins, 0);
			$inp_consumed_day_salt 				= round($inp_consumed_day_salt, 0);
			$inp_consumed_day_sodium 			= round($inp_consumed_day_sodium, 0);
			
			$query = "SELECT consumed_day_id, consumed_day_user_id, consumed_day_year, consumed_day_month, consumed_day_month_saying, consumed_day_day, consumed_day_day_saying, consumed_day_date, consumed_day_energy, consumed_day_fat, consumed_day_saturated_fat, consumed_day_monounsaturated_fat, consumed_day_polyunsaturated_fat, consumed_day_cholesterol, consumed_day_carbohydrates, consumed_day_carbohydrates_of_which_sugars, consumed_day_dietary_fiber, consumed_day_proteins, consumed_day_salt, consumed_day_sodium, consumed_day_target_sedentary_energy, consumed_day_target_sedentary_fat, consumed_day_target_sedentary_carb, consumed_day_target_sedentary_protein, consumed_day_target_with_activity_energy, consumed_day_target_with_activity_fat, consumed_day_target_with_activity_carb, consumed_day_target_with_activity_protein, consumed_day_diff_sedentary_energy, consumed_day_diff_sedentary_fat, consumed_day_diff_sedentary_carb, consumed_day_diff_sedentary_protein, consumed_day_diff_with_activity_energy, consumed_day_diff_with_activity_fat, consumed_day_diff_with_activity_carb, consumed_day_diff_with_activity_protein, consumed_day_updated_datetime, consumed_day_synchronized FROM $t_food_diary_consumed_days WHERE consumed_day_user_id=$my_user_id_mysql AND consumed_day_date='$get_current_entry_date'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_consumed_day_id, $get_consumed_day_user_id, $get_consumed_day_year, $get_consumed_day_month, $get_consumed_day_month_saying, $get_consumed_day_day, $get_consumed_day_day_saying, $get_consumed_day_date, $get_consumed_day_energy, $get_consumed_day_fat, $get_consumed_day_saturated_fat, $get_consumed_day_monounsaturated_fat, $get_consumed_day_polyunsaturated_fat, $get_consumed_day_cholesterol, $get_consumed_day_carbohydrates, $get_consumed_day_carbohydrates_of_which_sugars, $get_consumed_day_dietary_fiber, $get_consumed_day_proteins, $get_consumed_day_salt, $get_consumed_day_sodium, $get_consumed_day_target_sedentary_energy, $get_consumed_day_target_sedentary_fat, $get_consumed_day_target_sedentary_carb, $get_consumed_day_target_sedentary_protein, $get_consumed_day_target_with_activity_energy, $get_consumed_day_target_with_activity_fat, $get_consumed_day_target_with_activity_carb, $get_consumed_day_target_with_activity_protein, $get_consumed_day_diff_sedentary_energy, $get_consumed_day_diff_sedentary_fat, $get_consumed_day_diff_sedentary_carb, $get_consumed_day_diff_sedentary_protein, $get_consumed_day_diff_with_activity_energy, $get_consumed_day_diff_with_activity_fat, $get_consumed_day_diff_with_activity_carb, $get_consumed_day_diff_with_activity_protein, $get_consumed_day_updated_datetime, $get_consumed_day_synchronized) = $row;



			$inp_consumed_day_diff_sedentary_energy 	= $get_consumed_day_target_sedentary_energy-$inp_consumed_day_energy;
			$inp_consumed_day_diff_sedentary_fat 		= $get_consumed_day_target_sedentary_fat-$inp_consumed_day_fat;
			$inp_consumed_day_diff_sedentary_carb		= $get_consumed_day_target_sedentary_carb-$inp_consumed_day_carbohydrates;
			$inp_consumed_day_diff_sedentary_protein 	= $get_consumed_day_target_sedentary_protein-$inp_consumed_day_proteins;
	

			$inp_consumed_day_diff_with_activity_energy = $get_consumed_day_target_with_activity_energy-$inp_consumed_day_energy;
			$inp_consumed_day_diff_with_activity_fat = $get_consumed_day_target_with_activity_fat-$inp_consumed_day_fat;
			$inp_consumed_day_diff_with_activity_carb = $get_consumed_day_target_with_activity_carb-$inp_consumed_day_carbohydrates;
			$inp_consumed_day_diff_with_activity_protein = $get_consumed_day_target_with_activity_protein-$inp_consumed_day_proteins;

			$result = mysqli_query($link, "UPDATE $t_food_diary_consumed_days SET 
							consumed_day_energy='$inp_consumed_day_energy', 
							consumed_day_fat='$inp_consumed_day_fat', 
							consumed_day_saturated_fat='$inp_consumed_day_saturated_fat', 
							consumed_day_monounsaturated_fat='$inp_consumed_day_monounsaturated_fat', 
							consumed_day_polyunsaturated_fat='$inp_consumed_day_polyunsaturated_fat', 
							consumed_day_cholesterol='$inp_consumed_day_cholesterol', 
							consumed_day_carbohydrates='$inp_consumed_day_carbohydrates', 
							consumed_day_carbohydrates_of_which_sugars='$inp_consumed_day_carbohydrates_of_which_sugars', 
							consumed_day_dietary_fiber='$inp_consumed_day_dietary_fiber', 
							consumed_day_proteins='$inp_consumed_day_proteins', 
							consumed_day_salt='$inp_consumed_day_salt', 
							consumed_day_sodium='$inp_consumed_day_sodium', 
						
							consumed_day_diff_sedentary_energy='$inp_consumed_day_diff_sedentary_energy', 
							consumed_day_diff_sedentary_fat='$inp_consumed_day_diff_sedentary_fat', 
							consumed_day_diff_sedentary_carb='$inp_consumed_day_diff_sedentary_carb', 
							consumed_day_diff_sedentary_protein='$inp_consumed_day_diff_sedentary_protein',

							consumed_day_diff_with_activity_energy='$inp_consumed_day_diff_with_activity_energy', 
							consumed_day_diff_with_activity_fat='$inp_consumed_day_diff_with_activity_fat', 
							consumed_day_diff_with_activity_carb='$inp_consumed_day_diff_with_activity_carb', 
							consumed_day_diff_with_activity_protein='$inp_consumed_day_diff_with_activity_protein',

							consumed_day_updated_datetime='$datetime', 
							consumed_day_synchronized='0'
							 WHERE consumed_day_user_id=$my_user_id_mysql AND consumed_day_date='$get_current_entry_date'") or die(mysqli_error($link));


			$url = "index.php?date=$get_current_entry_date&l=$l&ft=success&fm=changes_saved#meal$get_current_entry_meal_id";
			header("Location: $url");
			exit;
		} // process

		// View			
		$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;

		
		echo"
		<h1>$get_current_entry_name</h1>

	
		<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
		<!-- //Feedback -->


		<!-- You are here -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"index.php?l=$l\">$l_food_diary</a>
			&gt;
			<a href=\"index.php?date=$get_current_entry_date&amp;l=$l\">$get_current_entry_date_saying</a>
			&gt;
			<a href=\"index.php?date=$get_current_entry_date&amp;l=$l#hour$get_current_entry_hour_name\">";

			if($get_current_entry_hour_name == "breakfast"){
				echo"$l_breakfast";
			}
			elseif($get_current_entry_hour_name == "lunch"){
				echo"$l_lunch";
			}
			elseif($get_current_entry_hour_name == "before_training"){
				echo"$l_before_training";
			}
			elseif($get_current_entry_hour_name == "after_training"){
				echo"$l_after_training";
			}
			elseif($get_current_entry_hour_name == "linner"){
				echo"$l_linner";
			}
			elseif($get_current_entry_hour_name == "dinner"){
				echo"$l_dinner";
			}
			elseif($get_current_entry_hour_name == "snacks"){
				echo"$l_snacks";
			}
			elseif($get_current_entry_hour_name == "before_supper"){
				echo"$l_before_supper";
			}
			elseif($get_current_entry_hour_name == "supper"){
				echo"$l_supper";
			}
			elseif($get_current_entry_hour_name == "night_meal"){
				echo"$l_night_meal";
			}
			else{
				echo"Unknown entry_hour_name";die;
			}
			echo"</a>
			&gt;
			<a href=\"food_diary_edit_entry.php?entry_id=$entry_id&amp;l=$l\">$get_current_entry_name</a>
			</p>
		<!-- //You are here -->



		<!-- About -->
		";
			if($get_current_entry_food_id != "0" && $get_current_entry_recipe_id == "0" && $get_current_entry_meal_id == "0"){
				// get food
				$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$get_current_entry_food_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

				echo"
				<div style=\"float: left;\">
					<p><a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;action=show_image&amp;image=a&amp;l=$l\" class=\"h2\">$get_food_manufacturer_name $get_food_name</a>
					</p>

					<!-- Food Numbers -->

							<table style=\"width: 350px\">
							 <thead>
							  <tr>
							   <th scope=\"col\">
							   </th>
							   <th scope=\"col\" style=\"text-align: center;padding: 8px 4px 8px 4px;\">
								<span style=\"font-weight: normal;\">$l_energy</span>
							   </th>
							   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;\">
								<span style=\"font-weight: normal;\">$l_proteins</span>
							   </th>
							   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;\">
								<span style=\"font-weight: normal;\">$l_carbs</span>
							   </th>
							   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;\">
								<span style=\"font-weight: normal;\">$l_fat</span>
							   </th>
							  </tr>
							 </thead>
							 <tbody>
							  <tr>
							   <td style=\"text-align: right;padding: 8px 4px 6px 8px;\">
								<span>$l_per_100:</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span>$get_food_energy_metric</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span>$get_food_proteins_metric</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span>$get_food_carbohydrates_metric</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span>$get_food_fat_metric</span>
							  </td>
							 </tr>
							  <tr>
							   <td style=\"text-align: right;padding: 8px 4px 6px 8px;\">
								<span>$l_serving:</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span>$get_food_energy_calculated_metric</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span>$get_food_proteins_calculated_metric</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span>$get_food_carbohydrates_calculated_metric</span>
							   </td>
							   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
								<span>$get_food_fat_calculated_metric</span>
							   </td>
							  </tr>
							 </tbody>
							</table>
					<!-- //Food Numbers -->

				</div>
				";
				if($get_food_id != ""){
					// 845/4 = 211
					if(file_exists("$root/$get_food_image_path/$get_food_image_a")){
						echo"
						<div style=\"float: left;padding-left: 15px;\">
							<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;action=show_image&amp;image=a&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_medium\" alt=\"$get_food_image_a\" /></a>
						</div>";
					}
				}
				echo"

				<!-- Edit form food -->
					<div class=\"clear\"></div>
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_entry_food_serving_size\"]').focus();
					});
					</script>
		
					<form method=\"post\" action=\"food_diary_edit_entry.php?entry_id=$entry_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					<p>
					<b>$l_amount:</b><br />
					<input type=\"text\" name=\"inp_entry_food_serving_size\" value=\"$get_current_entry_serving_size\" size=\"3\" />
					$get_current_entry_serving_size_measurement


					<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
					";
					if($get_food_serving_size_pcs_measurement != "g"){
						echo"<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />";
					}
					echo"</p>

					<p>
					<a href=\"food_diary_delete_entry.php?entry_id=$entry_id&amp;l=$l\" class=\"btn btn_warning\">$l_delete</a>
					</p>

					</form>
				<!-- //Edit form food -->

				";
			} // food
			elseif($get_current_entry_food_id == "0" && $get_current_entry_recipe_id != "0" && $get_current_entry_meal_id == "0"){
				// get recipe
				$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$get_current_entry_recipe_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

				// get numbers
				$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
				$result_n = mysqli_query($link, $query_n);
				$row_n = mysqli_fetch_row($result_n);
				list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;

						echo"

						<div style=\"float: left;\">
							<h2>$get_recipe_title</h2>


							<!-- Recipe numbers -->
							";
							if($get_current_view_hundred_metric == "1" OR $get_current_view_serving == "1"){
				
								echo"
								<table style=\"margin: 0px auto;\">
								";
								if($get_current_view_hundred_metric == "1"){
									echo"
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_hundred</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$get_number_energy_metric</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$get_number_fat_metric</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$get_number_carbohydrates_metric</span>
									  </td>
									  <td style=\"text-align: center;\">
										<span class=\"nutritional_number\">$get_number_proteins_metric</span>
									  </td>
									 </tr>
									";
								}
								if($get_current_view_serving == "1"){
									echo"
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$l_serving</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_number_energy_serving</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_number_fat_serving</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_number_carbohydrates_serving</span>
									  </td>
									  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_number_proteins_serving</span>
									  </td>
									 </tr>
									";
								}
								echo"
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;\">
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_calories_abbr_short_lowercase</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_fat_abbr_short_lowercase</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_carbohydrates_abbr_short_lowercase</span>
									  </td>
									  <td style=\"text-align: center;\">
										<span class=\"nutritional_number\">$l_proteins_abbr_short_lowercase</span>
									  </td>
									 </tr>
									</table>
								";
							} // show numbers
							echo"
							<!-- //Recipe numbers -->

						</div>
						
						<div style=\"float: left;padding-left: 15px;\">
							<img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_278x156\" alt=\"$get_recipe_image\" style=\"margin-bottom: 5px;\" />
						</div>

				<!-- Edit form recipe-->
					<div class=\"clear\"></div>
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_entry_serving_size\"]').focus();
					});
					</script>
		
					<form method=\"post\" action=\"food_diary_edit_entry.php?entry_id=$entry_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					<p>
					<b>$l_amount:</b><br />
					<input type=\"text\" name=\"inp_entry_serving_size\" value=\"$get_current_entry_serving_size\" size=\"3\" />
					$get_current_entry_serving_size_measurement
					</p>

					<p>
					<input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" />
					<a href=\"food_diary_delete_entry.php?entry_id=$entry_id&amp;l=$l\" class=\"btn btn_warning\">$l_delete</a>
					</p>

					</form>
				<!-- //Edit form recipe -->

				";
					
			} // recipe
			elseif($get_current_entry_food_id == "0" && $get_current_entry_recipe_id == "0" && $get_current_entry_meal_id != "0"){
				// get meal
				$query_n = "SELECT meal_id, meal_user_id, meal_hour_name, meal_last_used_date, meal_used_times, meal_entries, meal_entries_count, meal_selected_serving_size, meal_selected_measurement, meal_energy_serving, meal_fat_serving, meal_saturated_fat_serving, meal_monounsaturated_fat_serving, meal_polyunsaturated_fat_serving, meal_cholesterol_serving, meal_carbohydrates_serving, meal_carbohydrates_of_which_sugars_serving, meal_dietary_fiber_serving, meal_proteins_serving, meal_salt_serving, meal_sodium_serving, meal_energy_total, meal_fat_total, meal_saturated_total, meal_monounsaturated_fat_total, meal_polyunsaturated_fat_total, meal_cholesterol_total, meal_carbohydrates_total, meal_carbohydrates_of_which_sugars_total, meal_dietary_fiber_total, meal_proteins_total, meal_salt_total, meal_sodium_total FROM $t_food_diary_meals_index WHERE meal_id=$get_current_entry_meal_id";
				$result_n = mysqli_query($link, $query_n);
				$row_n = mysqli_fetch_row($result_n);
				list($get_meal_id, $get_meal_user_id, $get_meal_hour_name, $get_meal_last_used_date, $get_meal_used_times, $get_meal_entries, $get_meal_entries_count, $get_meal_selected_serving_size, $get_meal_selected_measurement, $get_meal_energy_serving, $get_meal_fat_serving, $get_meal_saturated_fat_serving, $get_meal_monounsaturated_fat_serving, $get_meal_polyunsaturated_fat_serving, $get_meal_cholesterol_serving, $get_meal_carbohydrates_serving, $get_meal_carbohydrates_of_which_sugars_serving, $get_meal_dietary_fiber_serving, $get_meal_proteins_serving, $get_meal_salt_serving, $get_meal_sodium_serving, $get_meal_energy_total, $get_meal_fat_total, $get_meal_saturated_total, $get_meal_monounsaturated_fat_total, $get_meal_polyunsaturated_fat_total, $get_meal_cholesterol_total, $get_meal_carbohydrates_total, $get_meal_carbohydrates_of_which_sugars_total, $get_meal_dietary_fiber_total, $get_meal_proteins_total, $get_meal_salt_total, $get_meal_sodium_total) = $row_n;
				if($get_meal_id == ""){
					echo"<p>Error: meal not found</p>";
					mysqli_query($link, "DELETE FROM $t_food_diary_last_used WHERE last_used_id=$get_current_entry_meal_id") or die(mysqli_error($link));
					mysqli_query($link, "DELETE FROM $t_food_diary_meals_items WHERE item_meal_id=$get_current_entry_meal_id") or die(mysqli_error($link));
					mysqli_query($link, "DELETE FROM $t_food_diary_entires WHERE entry_meal_id=$get_current_entry_meal_id") or die(mysqli_error($link));
				}
				echo"

						<div style=\"float: left;\">
							<h2>$get_current_entry_name</h2>


							<!-- Meal numbers -->
							";
							if($get_current_view_hundred_metric == "1" OR $get_current_view_serving == "1"){
				
								echo"
								<table style=\"margin: 0px auto;\">
								
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">1 $l_pcs_lowercase</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_meal_energy_serving</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_meal_fat_serving</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_meal_saturated_fat_serving</span>
									  </td>
									  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_meal_monounsaturated_fat_serving</span>
									  </td>
									 </tr>
								
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;\">
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_calories_abbr_short_lowercase</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_fat_abbr_short_lowercase</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_carbohydrates_abbr_short_lowercase</span>
									  </td>
									  <td style=\"text-align: center;\">
										<span class=\"nutritional_number\">$l_proteins_abbr_short_lowercase</span>
									  </td>
									 </tr>
									</table>
								";
							} // show numbers
							echo"
							<!-- //Meal numbers -->

						</div>
						
						<div style=\"float: left;padding-left: 15px;\">
							<p>";
							// meal image

							$query_i = "SELECT item_id, item_user_id, item_meal_id, item_food_id, item_recipe_id, item_name, item_manufacturer_name, item_image_path, item_image_file, item_image_thumb_132x132, item_image_thumb_66x132, item_serving_size, item_serving_size_measurement, item_energy_serving, item_fat_serving, item_saturated_fat_serving, item_monounsaturated_fat_serving, item_polyunsaturated_fat_serving, item_cholesterol_serving, item_carbohydrates_serving, item_carbohydrates_of_which_sugars_serving, item_dietary_fiber_serving, item_proteins_serving, item_salt_serving, item_sodium_serving FROM $t_food_diary_meals_items WHERE item_meal_id=$get_meal_id";
							$result_i = mysqli_query($link, $query_i);
							while($row_i = mysqli_fetch_row($result_i)) {
								list($get_item_id, $get_item_user_id, $get_item_meal_id, $get_item_food_id, $get_item_recipe_id, $get_item_name, $get_item_manufacturer_name, $get_item_image_path, $get_item_image_file, $get_item_image_thumb_132x132, $get_item_image_thumb_66x132, $get_item_serving_size, $get_item_serving_size_measurement, $get_item_energy_serving, $get_item_fat_serving, $get_item_saturated_fat_serving, $get_item_monounsaturated_fat_serving, $get_item_polyunsaturated_fat_serving, $get_item_cholesterol_serving, $get_item_carbohydrates_serving, $get_item_carbohydrates_of_which_sugars_serving, $get_item_dietary_fiber_serving, $get_item_proteins_serving, $get_item_salt_serving, $get_item_sodium_serving) = $row_i;


								// Image
								if(file_exists("$root/$get_item_image_path/$get_item_image_file") && $get_item_image_file != ""){
								
									if(!(file_exists("$root/$get_item_image_path/$get_item_image_thumb_66x132")) && $get_item_image_thumb_66x132 != ""){
										resize_crop_image(66, 132, "$root/$get_item_image_path/$get_item_image_file", "$root/$get_item_image_path/$get_item_image_thumb_66x132");
									}
									// Size is 2, width= 132/2=66
									echo"<img src=\"$root/$get_item_image_path/$get_item_image_thumb_66x132\" alt=\"$get_item_image_thumb_66x132\" width=\"66\" height=\"132\" />";
								
								}

							}
							echo"</p>
						</div>

				<!-- Edit form meal -->
					<div class=\"clear\"></div>
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_entry_serving_size\"]').focus();
					});
					</script>
		
					<form method=\"post\" action=\"food_diary_edit_entry.php?entry_id=$entry_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					<p>
					<b>$l_amount:</b><br />
					<input type=\"text\" name=\"inp_entry_serving_size\" value=\"$get_current_entry_serving_size\" size=\"3\" />
					$get_current_entry_serving_size_measurement
					</p>

					<p>
					<input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" />
					<a href=\"food_diary_delete_entry.php?entry_id=$entry_id&amp;l=$l\" class=\"btn btn_warning\">$l_delete</a>
					</p>

					</form>
				<!-- //Edit form meal -->
				";
			} // meal

		echo"
		<!-- //About -->


		";
		
	} // entry found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login?l=$l&amp;referer=$root/food_diary/index.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>