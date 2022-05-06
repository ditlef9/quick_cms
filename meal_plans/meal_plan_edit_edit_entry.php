<?php 
/**
*
* File: meal_plans/meal_plan_edit.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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
include("_tables_meal_plans.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/meal_plans/ts_new_meal_plan.php");
include("$root/_admin/_translations/site/$l/meal_plans/ts_meal_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['meal_plan_id'])){
	$meal_plan_id = $_GET['meal_plan_id'];
	$meal_plan_id = output_html($meal_plan_id);
}
else{
	$meal_plan_id = "";
}
if(isset($_GET['entry_day_number'])){
	$entry_day_number = $_GET['entry_day_number'];
	$entry_day_number = output_html($entry_day_number);
}
else{
	$entry_day_number = "";
}
if(isset($_GET['entry_meal_number'])){
	$entry_meal_number = $_GET['entry_meal_number'];
	$entry_meal_number = output_html($entry_meal_number);
}
else{
	$entry_meal_number = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_meal_plan - $l_meal_plans";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get meal_plan
	$meal_plan_id_mysql = quote_smart($link, $meal_plan_id);
	$query = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, meal_plan_total_energy_without_training, meal_plan_total_fat_without_training, meal_plan_total_carb_without_training, meal_plan_total_protein_without_training, meal_plan_total_energy_with_training, meal_plan_total_fat_with_training, meal_plan_total_carb_with_training, meal_plan_total_protein_with_training, meal_plan_average_kcal_without_training, meal_plan_average_fat_without_training, meal_plan_average_carb_without_training, meal_plan_average_protein_without_training, meal_plan_average_kcal_with_training, meal_plan_average_fat_with_training, meal_plan_average_carb_with_training, meal_plan_average_protein_with_training, meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_image_path, meal_plan_image_file, meal_plan_views, meal_plan_views_ip_block, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_rating_ip_block, meal_plan_comments FROM $t_meal_plans WHERE meal_plan_id=$meal_plan_id_mysql AND meal_plan_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_meal_plan_id, $get_current_meal_plan_user_id, $get_current_meal_plan_language, $get_current_meal_plan_title, $get_current_meal_plan_title_clean, $get_current_meal_plan_number_of_days, $get_current_meal_plan_introduction, $get_current_meal_plan_total_energy_without_training, $get_current_meal_plan_total_fat_without_training, $get_current_meal_plan_total_carb_without_training, $get_current_meal_plan_total_protein_without_training, $get_current_meal_plan_total_energy_with_training, $get_current_meal_plan_total_fat_with_training, $get_current_meal_plan_total_carb_with_training, $get_current_meal_plan_total_protein_with_training, $get_current_meal_plan_average_kcal_without_training, $get_current_meal_plan_average_fat_without_training, $get_current_meal_plan_average_carb_without_training, $get_current_meal_plan_average_protein_without_training, $get_current_meal_plan_average_kcal_with_training, $get_current_meal_plan_average_fat_with_training, $get_current_meal_plan_average_carb_with_training, $get_current_meal_plan_average_protein_with_training, $get_current_meal_plan_created, $get_current_meal_plan_updated, $get_current_meal_plan_user_ip, $get_current_meal_plan_image_path, $get_current_meal_plan_image_file, $get_current_meal_plan_views, $get_current_meal_plan_views_ip_block, $get_current_meal_plan_likes, $get_current_meal_plan_dislikes, $get_current_meal_plan_rating, $get_current_meal_plan_rating_ip_block, $get_current_meal_plan_comments) = $row;
	
	

	if($get_current_meal_plan_id == ""){
		echo"<p>Meal plan not found.</p>";
	}
	else{
		

		// Find entry
		$entry_id = $_GET['entry_id'];
		$entry_id = output_html($entry_id);
		$entry_id_mysql = quote_smart($link, $entry_id);
		$query = "SELECT entry_id, entry_meal_plan_id, entry_day_number, entry_meal_number, entry_weight, entry_food_id, entry_recipe_id, entry_name, entry_manufacturer_name, entry_main_category_id, entry_sub_category_id, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry, entry_text FROM $t_meal_plans_entries WHERE entry_id=$entry_id_mysql AND entry_meal_plan_id=$get_current_meal_plan_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_entry_id, $get_current_entry_meal_plan_id, $get_current_entry_day_number, $get_current_entry_meal_number, $get_current_entry_weight, $get_current_entry_food_id, $get_current_entry_recipe_id, $get_current_entry_name, $get_current_entry_manufacturer_name, $get_current_entry_main_category_id, $get_current_entry_sub_category_id, $get_current_entry_serving_size, $get_current_entry_serving_size_measurement, $get_current_entry_energy_per_entry, $get_current_entry_fat_per_entry, $get_current_entry_carb_per_entry, $get_current_entry_protein_per_entry, $get_current_entry_text) = $row;
		if($get_current_entry_id == ""){
			echo"Entry not found";
		}
		else{
			if($get_current_entry_food_id != "0"){
				if($process == 1){
					
					$inp_entry_food_serving_size = $_POST['inp_entry_food_serving_size'];
					$inp_entry_food_serving_size = output_html($inp_entry_food_serving_size);
					$inp_entry_food_serving_size = str_replace(",", ".", $inp_entry_food_serving_size);
					$inp_entry_food_serving_size_mysql = quote_smart($link, $inp_entry_food_serving_size);
					if($inp_entry_food_serving_size == ""){
						$url = "meal_plan_edit_edit_entry.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=$action&entry_id=$entry_id";
						$url = $url . "&ft=error&fm=missing_amount";
						header("Location: $url");
						exit;
					}

					// get food
					$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$get_current_entry_food_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

					if($get_food_id == ""){
						$url = "meal_plan_edit_edit_entry.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=$action&entry_id=$entry_id";
						$url = $url . "&ft=error&fm=food_not_found__maby_its_deleted";
						header("Location: $url");
						exit;
					}
				
				
					$inp_entry_food_name = output_html($get_food_name);
					$inp_entry_food_name = str_replace("&amp;amp;", "&amp;", $inp_entry_food_name);
					$inp_entry_food_name_mysql = quote_smart($link, $inp_entry_food_name);

					$inp_entry_food_manufacturer_name = output_html($get_food_manufacturer_name);
					$inp_entry_food_manufacturer_name_mysql = quote_smart($link, $inp_entry_food_manufacturer_name);


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

					// Update
					$result = mysqli_query($link, "UPDATE $t_meal_plans_entries SET 
									entry_name=$inp_entry_food_name_mysql, 
									entry_manufacturer_name=$inp_entry_food_manufacturer_name_mysql, 
									entry_serving_size=$inp_entry_food_serving_size_mysql, 
									entry_serving_size_measurement=$inp_entry_food_serving_size_measurement_mysql, 
									entry_energy_per_entry=$inp_entry_food_energy_per_entry_mysql, 
									entry_fat_per_entry=$inp_entry_food_fat_per_entry_mysql, 
									entry_carb_per_entry=$inp_entry_food_carb_per_entry_mysql, 
									entry_protein_per_entry=$inp_entry_food_protein_per_entry_mysql 	
									WHERE entry_id='$get_current_entry_id'") or die(mysqli_error($link));
 


					$url = "meal_plan_edit.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l";
					$url = $url . "&ft=success&fm=changes_saved";
					header("Location: $url");
					exit;
				}
				echo"
				<h1>$get_current_meal_plan_title</h1>
	
				<!-- You are here -->
					<p><b>$l_you_are_here</b><br />
					<a href=\"index.php?l=$l\">$l_meal_plans</a>
					&gt;
					<a href=\"index.php?l=$l\">$l_my_meal_plans</a>
					&gt;
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number\">$get_current_meal_plan_title</a>
					&gt;
					<a href=\"index.php?l=$l\">$l_edit $get_current_entry_name</a>
					</p>
				<!-- //You are here -->

				";
				if($entry_day_number > 0 && $entry_day_number < 8){
					if($entry_day_number == "1"){
						echo"<h2>$l_monday</h2>";
					}
					elseif($entry_day_number == "2"){
						echo"<h2>$l_tuesday</h2>";
					}
					elseif($entry_day_number == "3"){
						echo"<h2>$l_wednesday</h2>";
					}
					elseif($entry_day_number == "4"){
						echo"<h2>$l_thursday</h2>";
					}
					elseif($entry_day_number == "5"){
						echo"<h2>$l_friday</h2>";
					}
					elseif($entry_day_number == "6"){
						echo"<h2>$l_saturday</h2>";
					}
					elseif($entry_day_number == "7"){
						echo"<h2>$l_sunday</h2>";
					}
					echo"
				
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



					<!-- User adaptet view -->";
						if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
							$my_user_id = $_SESSION['user_id'];
							$my_user_id = output_html($my_user_id);
							$my_user_id_mysql = quote_smart($link, $my_user_id);
	
							$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_meal_plans_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
						}
						else{
							// IP
							$my_user_ip = $_SERVER['REMOTE_ADDR'];
							$my_user_ip = output_html($my_user_ip);
							$my_user_ip_mysql = quote_smart($link, $my_user_ip);
	
							$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_meal_plans_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;

						}
						if($get_current_view_id == ""){
							$get_current_view_system = "metric";
							$get_current_view_hundred_metric = 1;
							$get_current_view_pcs_metric = 1;
						}
						$r = "meal_plan_edit_edit_entry.php?meal_plan_id=$meal_plan_id" . "amp;entry_day_number=$entry_day_number" . "amp;entry_meal_number=$entry_meal_number" . "amp;l=$l" . "amp;entry_id=$entry_id";
						echo"
						<p><a id=\"adapter_view\"></a>
						<b>$l_show_per:</b>
						<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\"  data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
						<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
						<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
						<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; }  echo" /> $l_pcs_oz
						</p>

						<!-- On check go to URL -->
							<script>
							\$(function() {
								\$(\".onclick_go_to_url\").change(function(){
									var item=\$(this);
									window.location.href= item.data(\"target\")
								});
   							});
							</script>
						<!-- //On check go to URL -->

					<!-- //User adaptet view -->

					
					
					<!-- About -->
						";
						// get food
						$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$get_current_entry_food_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

						echo"
						<div style=\"float: left;\">
							<h2>$get_food_manufacturer_name $get_food_name</h2>
							";

							if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1" OR $get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
				
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
										<span class=\"nutritional_number\">$get_food_energy_metric</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$get_food_fat_metric</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$get_food_carbohydrates_metric</span>
									  </td>
									  <td style=\"text-align: center;\">
										<span class=\"nutritional_number\">$get_food_proteins_metric</span>
									  </td>
									 </tr>
									";
								}
								if($get_current_view_pcs_metric == "1"){
									echo"
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\" title=\"$get_food_serving_size_metric $get_food_serving_size_measurement_metric\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_energy_calculated_metric</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_fat_calculated_metric</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_carbohydrates_calculated_metric</span>
									  </td>
									  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_proteins_calculated_metric</span>
									  </td>
									 </tr>
									";
								}
								if($get_current_view_eight_us == "1"){
									echo"
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$l_per_eight_abbr_lowercase</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_energy_us</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_fat_us</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_carbohydrates_us</span>
									  </td>
									  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_proteins_us</span>
								 	 </td>
									 </tr>
									";
								}
								if($get_current_view_pcs_us == "1"){
									echo"
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\" title=\"$get_food_serving_size_us $get_food_serving_size_measurement_us\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_energy_calculated_us</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_fat_calculated_us</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_carbohydrates_calculated_us</span>
									  </td>
									  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_food_proteins_calculated_us</span>
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
							} // get_current_view_hundred_metric

							echo"
						</div> <!-- //float: left; -->
						";
						if($get_food_id != ""){
							// 845/4 = 211
							if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){
								echo"
								<div style=\"float: left;padding-left: 15px;\">
									<img src=\"../$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_thumb_a_small\" />
								</div>";
							}
						}
						echo"
					<!-- //About -->


					<!-- Edit form -->
						<div class=\"clear\"></div>
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_entry_food_serving_size\"]').focus();
						});
						</script>
		
						<form method=\"post\" action=\"meal_plan_edit_edit_entry.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l&amp;action=edit_entry&amp;entry_id=$entry_id&amp;process=1\" enctype=\"multipart/form-data\">
						
							<p><b>$l_meal</b><br />";
							if($entry_meal_number == 0){
								echo"$l_breakfast ";
							}
							elseif($entry_meal_number == 1){
								echo"$l_lunch";
							}
							elseif($entry_meal_number == 2){
								echo"$l_before_training  ";
							}
							elseif($entry_meal_number == 3){
								echo"$l_after_training ";
							}
							elseif($entry_meal_number == 4){
								echo"$l_dinnar";
							}
							elseif($entry_meal_number == 5){
								echo"$l_snacks";
							}
							elseif($entry_meal_number == 6){
								echo"$l_supper ";
							}
							else{
								echo"x out of range";
							}
							echo"</p>

							<p><b>$l_amount:</b><br />
							";
							if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1"){
								if($get_food_serving_size_pcs_measurement == "g"){
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_current_entry_serving_size\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									";
								}
								else{
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_current_entry_serving_size\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
									";
								}
							} // metric
							if($get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
								echo"
								<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_current_entry_serving_size\" />
								<input type=\"submit\" name=\"inp_submit_us\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
								<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
								";
							} // us
							echo"
							</p>
						</form>
					<!-- //Edit form -->
					";


				}
				echo"
				";
			} // food
			else{
				// Edit entry :: Recipe
				if($process == 1){
					
					$inp_entry_serving_size = $_POST['inp_entry_serving_size'];
					$inp_entry_serving_size = output_html($inp_entry_serving_size);
					$inp_entry_serving_size = str_replace(",", ".", $inp_entry_serving_size);
					$inp_entry_serving_size_mysql = quote_smart($link, $inp_entry_serving_size);
					if($inp_entry_serving_size == ""){
						$url = "meal_plan_edit_edit_entry.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=$action&entry_id=$entry_id";
						$url = $url . "&ft=error&fm=missing_amount";
						header("Location: $url");
						exit;
					}

				
					// get recipe
					$recipe_id_mysql = quote_smart($link, $get_current_entry_recipe_id);
					$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;
					if($get_recipe_id == ""){
						$url = "meal_plan_edit_new_entry_recipe.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=new_entry_food";
						$url = $url . "&ft=error&fm=recipe_specified_not_found";
						header("Location: $url");
						exit;
					}

					// get numbers
					$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
					$result_n = mysqli_query($link, $query_n);
					$row_n = mysqli_fetch_row($result_n);
					list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;

					$inp_entry_name = output_html($get_recipe_title);
					$inp_entry_name_mysql = quote_smart($link, $inp_entry_name);

					$inp_entry_manufacturer_name = output_html("");
					$inp_entry_manufacturer_name_mysql = quote_smart($link, $inp_entry_manufacturer_name);

					if($inp_entry_serving_size == "1"){
						$inp_entry_serving_size_measurement = output_html(strtolower($l_serving_abbreviation));
					}
					else{
						$inp_entry_serving_size_measurement = output_html(strtolower($l_servings_abbreviation));
					}
					$inp_entry_serving_size_measurement_mysql = quote_smart($link, $inp_entry_serving_size_measurement);

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

					// Update
					$result = mysqli_query($link, "UPDATE $t_meal_plans_entries SET 
									entry_name=$inp_entry_name_mysql, 
									entry_manufacturer_name=$inp_entry_manufacturer_name_mysql, 
									entry_serving_size=$inp_entry_serving_size_mysql, 
									entry_serving_size_measurement=$inp_entry_serving_size_measurement_mysql, 
									entry_energy_per_entry=$inp_entry_energy_per_entry_mysql, 
									entry_fat_per_entry=$inp_entry_fat_per_entry_mysql, 
									entry_carb_per_entry=$inp_entry_carbohydrates_per_entry_mysql, 
									entry_protein_per_entry=$inp_entry_proteins_per_entry_mysql 
									WHERE entry_id='$get_current_entry_id'") or die(mysqli_error($link));
 
				

					$url = "meal_plan_edit.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l";
					$url = $url . "&ft=success&fm=changes_saved";
					header("Location: $url");
					exit;
				}
				echo"
				<h1>$get_current_meal_plan_title</h1>
	
				<!-- You are here -->
					<p><b>$l_you_are_here</b><br />
					<a href=\"index.php?l=$l\">$l_meal_plans</a>
					&gt;
					<a href=\"index.php?l=$l\">$l_my_meal_plans</a>
					&gt;
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number\">$get_current_meal_plan_title</a>
					&gt;
					<a href=\"index.php?l=$l\">$l_edit $get_current_entry_name</a>
					</p>
				<!-- //You are here -->


				<!-- Current day -->
				";
				if($entry_day_number > 0 && $entry_day_number < 8){
					if($entry_day_number == "1"){
						echo"<h2>$l_monday</h2>";
					}
					elseif($entry_day_number == "2"){
						echo"<h2>$l_tuesday</h2>";
					}
					elseif($entry_day_number == "3"){
						echo"<h2>$l_wednesday</h2>";
					}
					elseif($entry_day_number == "4"){
						echo"<h2>$l_thursday</h2>";
					}
					elseif($entry_day_number == "5"){
						echo"<h2>$l_friday</h2>";
					}
					elseif($entry_day_number == "6"){
						echo"<h2>$l_saturday</h2>";
					}
					elseif($entry_day_number == "7"){
						echo"<h2>$l_sunday</h2>";
					}
					echo"
				
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



					<!-- User adaptet view -->";
						if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
							$my_user_id = $_SESSION['user_id'];
							$my_user_id = output_html($my_user_id);
							$my_user_id_mysql = quote_smart($link, $my_user_id);
	
							$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_meal_plans_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
						}
						else{
							// IP
							$my_user_ip = $_SERVER['REMOTE_ADDR'];
							$my_user_ip = output_html($my_user_ip);
							$my_user_ip_mysql = quote_smart($link, $my_user_ip);
	
							$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_meal_plans_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;

						}
						if($get_current_view_id == ""){
							$get_current_view_system = "metric";
							$get_current_view_hundred_metric = 1;
							$get_current_view_pcs_metric = 1;
						}
						$r = "new_meal_plan_step_2_entries_edit_entry.php?meal_plan_id=$meal_plan_id" . "amp;entry_day_number=$entry_day_number" . "amp;entry_meal_number=$entry_meal_number" . "amp;entry_id=$entry_id";
						echo"
						<p><a id=\"adapter_view\"></a>
						<b>$l_show_per:</b>
						<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\"  data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
						<input type=\"checkbox\" name=\"inp_show_serving\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
						<input type=\"checkbox\" name=\"inp_show_eight_us\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
						</p>
						<!-- On check go to URL -->
							<script>
							\$(function() {
								\$(\".onclick_go_to_url\").change(function(){
									var item=\$(this);
									window.location.href= item.data(\"target\")
								});
   							});
							</script>
						<!-- //On check go to URL -->

					<!-- //User adaptet view -->

					
					<!-- About -->
						";
						// get recipe
						$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$get_current_entry_recipe_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

						// get numbers
						$query = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id='$get_recipe_id'";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row;

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
						";
						if($get_recipe_image != ""){
							// 845/4 = 211
							if(file_exists("$root/$get_recipe_image_path/$get_recipe_image")){
				
								echo"
								<div style=\"float: left;padding-left: 15px;\">
									<img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_278x156\" alt=\"$get_recipe_image\" />
								</div>";
							}
						}
						echo"
					<!-- //About -->


					<!-- Edit form Recipe -->
						<div class=\"clear\"></div>
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_entry_food_serving_size\"]').focus();
						});
						</script>
		
						<form method=\"post\" action=\"meal_plan_edit_edit_entry.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l&amp;action=edit_entry&amp;entry_id=$entry_id&amp;process=1\" enctype=\"multipart/form-data\">
						
							<p><b>$l_meal</b><br />";
							if($entry_meal_number == 0){
								echo"$l_breakfast ";
							}
							elseif($entry_meal_number == 1){
								echo"$l_lunch";
							}
							elseif($entry_meal_number == 2){
								echo"$l_before_training  ";
							}
							elseif($entry_meal_number == 3){
								echo"$l_after_training ";
							}
							elseif($entry_meal_number == 4){
								echo"$l_dinnar";
							}
							elseif($entry_meal_number == 5){
								echo"$l_snacks";
							}
							elseif($entry_meal_number == 6){
								echo"$l_supper ";
							}
							else{
								echo"x out of range";
							}
							echo"</p>

							<p>
							<b>$l_amount:</b><br />
							<input type=\"text\" name=\"inp_entry_serving_size\" value=\"$get_current_entry_serving_size\" size=\"3\" />
							</p>

							<p>
							<input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" />
							</p>
						</form>
					<!-- //Edit form Recipe -->
					";


				}
				echo"
				<!-- //Current day -->
				";

			} // recipe
		} // entry found
	} // meal plan found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>