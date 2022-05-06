<?php
/**
*
* File: _admin/_inc/food/open_food.php
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
if(isset($_GET['system'])){
	$system = $_GET['system'];
	$system = strip_tags(stripslashes($system));
	if($system != "all" && $system != "metric" && $system != "us"){
		echo"Unknown system";
		die;
	}
}
else{
	$system = "metric";
}


/*- Settings ---------------------------------------------------------------------------- */
$settings_image_width = "847";
$settings_image_height = "847";

/*- Languages -------------------------------------------------------------------------- */
include("_translations/site/$l/food/ts_view_food.php");

// Get variables
$food_id = $_GET['food_id'];
$food_id = strip_tags(stripslashes($food_id));
$food_id_mysql = quote_smart($link, $food_id);
$editor_language_mysql = quote_smart($link, $editor_language);

// Select food
$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$food_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_food_id, $get_current_food_user_id, $get_current_food_name, $get_current_food_clean_name, $get_current_food_manufacturer_name, $get_current_food_manufacturer_name_and_food_name, $get_current_food_description, $get_current_food_country, $get_current_food_net_content_metric, $get_current_food_net_content_measurement_metric, $get_current_food_net_content_us, $get_current_food_net_content_measurement_us, $get_current_food_net_content_added_measurement, $get_current_food_serving_size_metric, $get_current_food_serving_size_measurement_metric, $get_current_food_serving_size_us, $get_current_food_serving_size_measurement_us, $get_current_food_serving_size_added_measurement, $get_current_food_serving_size_pcs, $get_current_food_serving_size_pcs_measurement, $get_current_food_energy_metric, $get_current_food_fat_metric, $get_current_food_saturated_fat_metric, $get_current_food_monounsaturated_fat_metric, $get_current_food_polyunsaturated_fat_metric, $get_current_food_cholesterol_metric, $get_current_food_carbohydrates_metric, $get_current_food_carbohydrates_of_which_sugars_metric, $get_current_food_dietary_fiber_metric, $get_current_food_proteins_metric, $get_current_food_salt_metric, $get_current_food_sodium_metric, $get_current_food_energy_us, $get_current_food_fat_us, $get_current_food_saturated_fat_us, $get_current_food_monounsaturated_fat_us, $get_current_food_polyunsaturated_fat_us, $get_current_food_cholesterol_us, $get_current_food_carbohydrates_us, $get_current_food_carbohydrates_of_which_sugars_us, $get_current_food_dietary_fiber_us, $get_current_food_proteins_us, $get_current_food_salt_us, $get_current_food_sodium_us, $get_current_food_score, $get_current_food_energy_calculated_metric, $get_current_food_fat_calculated_metric, $get_current_food_saturated_fat_calculated_metric, $get_current_food_monounsaturated_fat_calculated_metric, $get_current_food_polyunsaturated_fat_calculated_metric, $get_current_food_cholesterol_calculated_metric, $get_current_food_carbohydrates_calculated_metric, $get_current_food_carbohydrates_of_which_sugars_calculated_metric, $get_current_food_dietary_fiber_calculated_metric, $get_current_food_proteins_calculated_metric, $get_current_food_salt_calculated_metric, $get_current_food_sodium_calculated_metric, $get_current_food_energy_calculated_us, $get_current_food_fat_calculated_us, $get_current_food_saturated_fat_calculated_us, $get_current_food_monounsaturated_fat_calculated_us, $get_current_food_polyunsaturated_fat_calculated_us, $get_current_food_cholesterol_calculated_us, $get_current_food_carbohydrates_calculated_us, $get_current_food_carbohydrates_of_which_sugars_calculated_us, $get_current_food_dietary_fiber_calculated_us, $get_current_food_proteins_calculated_us, $get_current_food_salt_calculated_us, $get_current_food_sodium_calculated_us, $get_current_food_barcode, $get_current_food_main_category_id, $get_current_food_sub_category_id, $get_current_food_image_path, $get_current_food_image_a, $get_current_food_thumb_a_small, $get_current_food_thumb_a_medium, $get_current_food_thumb_a_large, $get_current_food_image_b, $get_current_food_thumb_b_small, $get_current_food_thumb_b_medium, $get_current_food_thumb_b_large, $get_current_food_image_c, $get_current_food_thumb_c_small, $get_current_food_thumb_c_medium, $get_current_food_thumb_c_large, $get_current_food_image_d, $get_current_food_thumb_d_small, $get_current_food_thumb_d_medium, $get_current_food_thumb_d_large, $get_current_food_image_e, $get_current_food_thumb_e_small, $get_current_food_thumb_e_medium, $get_current_food_thumb_e_large, $get_current_food_last_used, $get_current_food_language, $get_current_food_synchronized, $get_current_food_accepted_as_master, $get_current_food_notes, $get_current_food_unique_hits, $get_current_food_unique_hits_ip_block, $get_current_food_comments, $get_current_food_likes, $get_current_food_dislikes, $get_current_food_likes_ip_block, $get_current_food_user_ip, $get_current_food_created_date, $get_current_food_last_viewed, $get_current_food_age_restriction) = $row;

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

	// Main category translation
	$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_food_main_category_id AND category_translation_language=$editor_language_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_main_category_translation_value) = $row_t;


	// Sub category translation
	$query_t = "SELECT category_translation_id, category_id, category_translation_language, category_translation_value, category_translation_no_food, category_translation_last_updated, category_stats_last_updated_year, category_calories_min_metric, category_calories_med_metric, category_calories_max_metric, category_fat_min_metric, category_fat_med_metric, category_fat_max_metric, category_saturated_fat_min_metric, category_saturated_fat_med_metric, category_saturated_fat_max_metric, category_monounsaturated_fat_min_metric, category_monounsaturated_fat_med_metric, category_monounsaturated_fat_max_metric, category_polyunsaturated_fat_min_metric, category_polyunsaturated_fat_med_metric, category_polyunsaturated_fat_max_metric, category_cholesterol_min_metric, category_cholesterol_med_metric, category_cholesterol_max_metric, category_carb_min_metric, category_carb_med_metric, category_carb_max_metric, category_carb_of_which_sugars_min_metric, category_carb_of_which_sugars_med_metric, category_carb_of_which_sugars_max_metric, category_dietary_fiber_min_metric, category_dietary_fiber_med_metric, category_dietary_fiber_max_metric, category_proteins_min_metric, category_proteins_med_metric, category_proteins_max_metric, category_salt_min_metric, category_salt_med_metric, category_salt_max_metric, category_sodium_min_metric, category_sodium_med_metric, category_sodium_max_metric, category_calories_min_us, category_calories_med_us, category_calories_max_us, category_fat_min_us, category_fat_med_us, category_fat_max_us, category_saturated_fat_min_us, category_saturated_fat_med_us, category_saturated_fat_max_us, category_monounsaturated_fat_min_us, category_monounsaturated_fat_med_us, category_monounsaturated_fat_max_us, category_polyunsaturated_fat_min_us, category_polyunsaturated_fat_med_us, category_polyunsaturated_fat_max_us, category_cholesterol_min_us, category_cholesterol_med_us, category_cholesterol_max_us, category_carb_min_us, category_carb_med_us, category_carb_max_us, category_carb_of_which_sugars_min_us, category_carb_of_which_sugars_med_us, category_carb_of_which_sugars_max_us, category_dietary_fiber_min_us, category_dietary_fiber_med_us, category_dietary_fiber_max_us, category_proteins_min_us, category_proteins_med_us, category_proteins_max_us, category_salt_min_us, category_salt_med_us, category_salt_max_us, category_sodium_min_us, category_sodium_med_us, category_sodium_max_us FROM $t_food_categories_translations WHERE category_id=$get_current_food_sub_category_id AND category_translation_language=$editor_language_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_category_translation_id, $get_current_sub_category_id, $get_current_sub_category_translation_language, $get_current_sub_category_translation_value, $get_current_sub_category_translation_no_food, $get_current_sub_category_translation_last_updated, $get_current_sub_category_stats_last_updated_year, $get_current_sub_category_calories_min_metric, $get_current_sub_category_calories_med_metric, $get_current_sub_category_calories_max_metric, $get_current_sub_category_fat_min_metric, $get_current_sub_category_fat_med_metric, $get_current_sub_category_fat_max_metric, $get_current_sub_category_saturated_fat_min_metric, $get_current_sub_category_saturated_fat_med_metric, $get_current_sub_category_saturated_fat_max_metric, $get_current_sub_category_monounsaturated_fat_min_metric, $get_current_sub_category_monounsaturated_fat_med_metric, $get_current_sub_category_monounsaturated_fat_max_metric, $get_current_sub_category_polyunsaturated_fat_min_metric, $get_current_sub_category_polyunsaturated_fat_med_metric, $get_current_sub_category_polyunsaturated_fat_max_metric, $get_current_sub_category_cholesterol_min_metric, $get_current_sub_category_cholesterol_med_metric, $get_current_sub_category_cholesterol_max_metric, $get_current_sub_category_carb_min_metric, $get_current_sub_category_carb_med_metric, $get_current_sub_category_carb_max_metric, $get_current_sub_category_carb_of_which_sugars_min_metric, $get_current_sub_category_carb_of_which_sugars_med_metric, $get_current_sub_category_carb_of_which_sugars_max_metric, $get_current_sub_category_dietary_fiber_min_metric, $get_current_sub_category_dietary_fiber_med_metric, $get_current_sub_category_dietary_fiber_max_metric, $get_current_sub_category_proteins_min_metric, $get_current_sub_category_proteins_med_metric, $get_current_sub_category_proteins_max_metric, $get_current_sub_category_salt_min_metric, $get_current_sub_category_salt_med_metric, $get_current_sub_category_salt_max_metric, $get_current_sub_category_sodium_min_metric, $get_current_sub_category_sodium_med_metric, $get_current_sub_category_sodium_max_metric, $get_current_sub_category_calories_min_us, $get_current_sub_category_calories_med_us, $get_current_sub_category_calories_max_us, $get_current_sub_category_fat_min_us, $get_current_sub_category_fat_med_us, $get_current_sub_category_fat_max_us, $get_current_sub_category_saturated_fat_min_us, $get_current_sub_category_saturated_fat_med_us, $get_current_sub_category_saturated_fat_max_us, $get_current_sub_category_monounsaturated_fat_min_us, $get_current_sub_category_monounsaturated_fat_med_us, $get_current_sub_category_monounsaturated_fat_max_us, $get_current_sub_category_polyunsaturated_fat_min_us, $get_current_sub_category_polyunsaturated_fat_med_us, $get_current_sub_category_polyunsaturated_fat_max_us, $get_current_sub_category_cholesterol_min_us, $get_current_sub_category_cholesterol_med_us, $get_current_sub_category_cholesterol_max_us, $get_current_sub_category_carb_min_us, $get_current_sub_category_carb_med_us, $get_current_sub_category_carb_max_us, $get_current_sub_category_carb_of_which_sugars_min_us, $get_current_sub_category_carb_of_which_sugars_med_us, $get_current_sub_category_carb_of_which_sugars_max_us, $get_current_sub_category_dietary_fiber_min_us, $get_current_sub_category_dietary_fiber_med_us, $get_current_sub_category_dietary_fiber_max_us, $get_current_sub_category_proteins_min_us, $get_current_sub_category_proteins_med_us, $get_current_sub_category_proteins_max_us, $get_current_sub_category_salt_min_us, $get_current_sub_category_salt_med_us, $get_current_sub_category_salt_max_us, $get_current_sub_category_sodium_min_us, $get_current_sub_category_sodium_med_us, $get_current_sub_category_sodium_max_us) = $row_t;
		


	// Author
	$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$get_current_food_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_food_author_user_id, $get_current_food_author_user_email, $get_current_food_author_user_name, $get_current_food_author_user_alias) = $row;

	if($action == ""){
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
					<li><a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">View</a>
					<li><a href=\"index.php?open=$open&amp;page=edit_food_general_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
					<li><a href=\"index.php?open=$open&amp;page=edit_food_numbers_hundred_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Numbers</a>
					<li><a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Images</a>
					<li><a href=\"index.php?open=$open&amp;page=delete_food_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Food Menu -->

		<!-- View food -->
			
			<!-- Images, width = 845 -->
				<p style=\"padding-bottom: 0;margin-bottom: 0;\">";
				
				// 845/4 = 211
				if($mode == "show_image" && isset($_GET['image'])){
					echo"<a id=\"image\"></a>";
					$image = $_GET['image'];
					$image = strip_tags(stripslashes($image));
	
					if($image == "a" && file_exists("../$get_current_food_image_path/$get_current_food_image_a") && $get_current_food_image_a != ""){
				
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_b")){
							echo"<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;mode=show_image&amp;image=b&amp;l=$l#image\"><img src=\"../$get_current_food_image_path/$get_current_food_image_a\" alt=\"$get_current_food_image_a\" /></a>";
						}
						else{
							echo"<img src=\"../$get_current_food_image_path/$get_current_food_image_a\" alt=\"$get_current_food_image_a\" />";
						}
					}
					if($image == "b" && file_exists("../$get_current_food_image_path/$get_current_food_image_b") && $get_current_food_image_b != ""){
				
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_c")){
							echo"<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;mode=show_image&amp;image=c&amp;l=$l#image\"><img src=\"../$get_current_food_image_path/$get_current_food_image_b\" alt=\"$get_current_food_image_b\" /></a>";
						}
						else{
							echo"<a href=\"view_food.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;l=$l#image\"><img src=\"../$get_current_food_image_path/$get_current_food_image_b\" alt=\"$get_current_food_image_b\" /></a>";
						}
					}
					if($image == "c" && file_exists("../$get_current_food_image_path/$get_current_food_image_c") && $get_current_food_image_c != ""){
						
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_d")){
							echo"<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;mode=show_image&amp;image=d&amp;l=$l#image\"><img src=\"../$get_current_food_image_path/$get_current_food_image_c\" alt=\"$get_current_food_image_c\" /></a>";
						}
						else{
							echo"<a href=\"view_food.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$food_id&amp;l=$l#image\"><img src=\"../$get_current_food_image_path/$get_current_food_image_c\" alt=\"$get_current_food_image_c\" /></a>";
						}
					}
					if($image == "d" && file_exists("../$get_current_food_image_path/$get_current_food_image_d") && $get_current_food_image_d != ""){
				
						echo"<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"../$get_current_food_image_path/$get_current_food_image_d\" alt=\"$get_current_food_image_d\" /></a>";
				
					}
					echo"<br />";

				}

				if($get_current_food_image_a != ""){
					echo"<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;mode=show_image&amp;image=a&amp;l=$l#image\" style=\"margin-right: 11px;\"><img src=\"../$get_current_food_image_path/$get_current_food_thumb_a_medium\" alt=\"$get_current_food_thumb_a_medium\" /></a>";
				}

				if($get_current_food_image_b != ""){
					echo"<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;mode=show_image&amp;image=b&amp;l=$l#image\" style=\"margin-right: 11px;\"><img src=\"../$get_current_food_image_path/$get_current_food_thumb_b_medium\" alt=\"$get_current_food_thumb_b_medium\" /></a>";
				}
				if($get_current_food_image_c != ""){
					echo"<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;mode=show_image&amp;image=c&amp;l=$l#image\" style=\"margin-right: 11px;\"><img src=\"../$get_current_food_image_path/$get_current_food_thumb_c_medium\" alt=\"$get_current_food_thumb_c_medium\" /></a>";
				}
				if($get_current_food_image_d != "" ){
					echo"<a href=\"index.php?open=$open&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;mode=show_image&amp;image=d&amp;l=$l#image\" style=\"margin-right: 11px;\"><img src=\"../$get_current_food_image_path/$get_current_food_thumb_d_medium\" alt=\"$get_current_food_thumb_d_medium\" /></a>";
				}
				echo"
				</p>
			<!-- //Images -->
	
			<!-- Favorite, edit, delete -->
				<div class=\"clear\"></div>
				
				<p style=\"margin:0;padding:0;\">
				Published by <a href=\"../users/view_profile.php?user_id=$get_current_food_user_id&amp;l=$l\">$get_current_food_author_user_alias</a><br />
				$get_current_food_unique_hits  unique views
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
						<a href=\"../food/view_tag.php?tag=$get_tag_title&amp;l=$l\">#$get_tag_title</a>
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
				<a id=\"numbers\"></a>
				
				<div style=\"float: left;\">
					<h2>$l_numbers</h2>
				</div>
				<div style=\"float: left;padding-left: 10px;\">
					<p>
					<a href=\"index.php?open=food&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;system=all&amp;l=$l#numbers\""; if($system == "all"){ echo" style=\"font-weight:bold;\""; } echo">$l_all</a>
					&middot;
					<a href=\"index.php?open=food&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;system=metric&amp;l=$l#numbers\""; if($system == "metric"){ echo" style=\"font-weight:bold;\""; } echo">$l_metric</a>
					&middot;
					<a href=\"index.php?open=food&amp;page=open_food&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;system=us&amp;l=$l#numbers\""; if($system == "us"){ echo" style=\"font-weight:bold;\""; } echo">$l_us</a>
					</p>
				</div>
				<div class=\"clear\"></div>
				
				<table class=\"hor-zebra\" style=\"width: auto;min-width: 0;display: table;\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
				   </th>";
				if($system == "all" OR $system == "metric"){
					echo"
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 8px;vertical-align: bottom;\">
						<span>$l_per_100</span>
		 			  </th>
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\">
						<span>$l_serving<br />$get_current_food_serving_size_metric $get_current_food_serving_size_measurement_metric ($get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement)</span>
					   </th>
					";
				}
				if($system == "all" OR $system == "us"){
					echo"
					  <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 8px;vertical-align: bottom;\">
						<span>$l_per_8 $get_current_food_net_content_measurement_us_system</span>
		 			   </th>
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\">
						<span>$l_serving<br />$get_current_food_serving_size_us_system $get_current_food_serving_size_measurement_us_system ($get_current_food_serving_size_pcs $get_current_food_serving_size_pcs_measurement)</span>
					   </th>
					";
				}
				echo"
				   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\" class=\"current_sub_category_calories_med\">
					<span>$l_median_for<br />
					$get_current_sub_category_translation_value</span>
				   </th>
				   <th scope=\"col\" style=\"text-align: center;padding: 6px 8px 6px 8px;\" class=\"current_sub_category_calories_diff\">
					<span>$l_diff</span>
				   </th>
				  </tr>
				 </thead>
				 <tbody>
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_calories</span>
				   </td>";
				if($system == "all" OR $system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_energy_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_energy_calculated_metric</span>
					   </td>
					";
				}
				if($system == "all" OR $system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_energy_us_system</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_energy_calculated_us_system</span>
					   </td>
					";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
					<span>$get_current_sub_category_calories_med</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
					$energy_diff_med = round($get_current_food_energy_metric-$get_current_sub_category_calories_med, 0);

					if($energy_diff_med > 0){
						echo"<span style=\"color: red;\">$energy_diff_med</span>";
					}
					elseif($energy_diff_med < 0){
						echo"<span style=\"color: green;\">$energy_diff_med</span>";
					}
					else{
						echo"<span>$energy_diff_med</span>";
						// $product_score_description = $product_score_description . " $l_have_an_ok_amount_of_calories_lowercase, ";
					}
					echo"
				   </td>
				  </tr>

				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_fat<br /></span>
					<span>$l_dash_of_which_saturated_fatty_acids</span>
				   </td>";
				if($system == "all" OR $system == "metric"){
					echo"
		 			  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_fat_metric<br /></span>
						<span>$get_current_food_fat_of_which_saturated_fatty_acids_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_fat_calculated_metric<br /></span>
						<span>$get_current_food_fat_of_which_saturated_fatty_acids_calculated_metric</span>
					   </td>";
				}
				if($system == "all" OR $system == "us"){
					echo"
		 			  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_fat_us_system<br /></span>
						<span>$get_current_food_fat_of_which_saturated_fatty_acids_us_system</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_fat_calculated_us_system<br /></span>
						<span>$get_current_food_fat_of_which_saturated_fatty_acids_calculated_us_system</span>
					   </td>";
				}
				echo"
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
					<span>$get_current_sub_category_fat_med<br /></span>
					<span>$get_current_sub_category_fat_of_which_saturated_fatty_acids_med</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
					$fat_diff_med = round($get_current_food_fat_metric-$get_current_sub_category_fat_med, 0);

					if($fat_diff_med > 0){
						echo"<span style=\"color: red;\">$fat_diff_med</span>";
					}
					elseif($fat_diff_med < 0){
						echo"<span style=\"color: green;\">$fat_diff_med</span>";
					}
					else{
						echo"<span>$fat_diff_med</span>";
						// $product_score_description = $product_score_description . " $l_ok_amount_of_fat_lowercase, ";
					}

					$food_fat_of_which_saturated_fatty_acids_diff_med = round($get_current_food_fat_of_which_saturated_fatty_acids_metric-$get_current_sub_category_fat_of_which_saturated_fatty_acids_med, 0);
			
					if($food_fat_of_which_saturated_fatty_acids_diff_med > 0){
						echo"<span style=\"color: red;\"><br />$food_fat_of_which_saturated_fatty_acids_diff_med</span>";
					}
					elseif($food_fat_of_which_saturated_fatty_acids_diff_med < 0){
						echo"<span style=\"color: green;\"><br />$food_fat_of_which_saturated_fatty_acids_diff_med</span>";
					}
					else{
						echo"<span><br />$food_fat_of_which_saturated_fatty_acids_diff_med</span>";
						// $product_score_description = $product_score_description . " $l_ok_amount_of_fat_lowercase, ";
					}
					echo"
				   </td>
				  </tr>

				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_carbs<br /></span>
					<span>$l_dash_of_which_sugars</span>
				   </td>";
				if($system == "all" OR $system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_carbohydrates_metric<br /></span>
						<span>$get_current_food_carbohydrates_of_which_sugars_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_carbohydrates_calculated_metric<br /></span>
						<span>$get_current_food_carbohydrates_of_which_sugars_calculated_metric</span>
					   </td>
					";
				}
				if($system == "all" OR $system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_carbohydrates_us_system<br /></span>
						<span>$get_current_food_carbohydrates_of_which_sugars_us_system</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_carbohydrates_calculated_us_system<br /></span>
						<span>$get_current_food_carbohydrates_of_which_sugars_calculated_us_system</span>
					   </td>
					";
				}
				echo"
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
			<span>$get_current_sub_category_carb_med<br /></span>
			<span>$get_current_sub_category_carb_of_which_sugars_med</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
			$carbohydrate_diff_med = round($get_current_food_carbohydrates_metric-$get_current_sub_category_carb_med, 0);
			
			if($carbohydrate_diff_med > 0){
				echo"<span style=\"color: red;\">$carbohydrate_diff_med</span>";
			}
			elseif($carbohydrate_diff_med < 0){
				echo"<span style=\"color: green;\">$carbohydrate_diff_med</span>";
			}
			else{
				echo"<span>$carbohydrate_diff_med</span>";
			}
			// Sugar
			$food_carbohydrates_of_which_sugars_diff_med = round($get_current_food_carbohydrates_of_which_sugars_metric-$get_current_sub_category_carb_of_which_sugars_med, 0);
			
			if($food_carbohydrates_of_which_sugars_diff_med > 0){
				echo"<span style=\"color: red;\"><br />$food_carbohydrates_of_which_sugars_diff_med</span>";
			}
			elseif($food_carbohydrates_of_which_sugars_diff_med < 0){
				echo"<span style=\"color: green;\"><br />$food_carbohydrates_of_which_sugars_diff_med</span>";
			}
			else{
				echo"<span><br />$food_carbohydrates_of_which_sugars_diff_med</span>";
			}
			echo"
		   </td>
		  </tr>



		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_dietary_fiber<br /></span>
		   </td>";
				if($system == "all" OR $system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_dietary_fiber_metric<br /></span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_dietary_fiber_calculated_metric<br /></span>
					   </td>
					";
				}
				if($system == "all" OR $system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_dietary_fiber_us_system<br /></span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_dietary_fiber_calculated_us_system<br /></span>
					   </td>
					";
				}
		echo"
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
			<span>$get_current_sub_category_carb_of_which_dietary_fiber_med</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
			// Fiber
			$food_dietary_fiber_diff_med = round($get_current_food_dietary_fiber_metric-$get_current_sub_category_carb_of_which_dietary_fiber_med, 0);
			
			if($food_dietary_fiber_diff_med > 0){
				echo"<span style=\"color: red;\"><br />$food_dietary_fiber_diff_med</span>";
			}
			elseif($food_dietary_fiber_diff_med < 0){
				echo"<span style=\"color: green;\"><br />$food_dietary_fiber_diff_med</span>";
			}
			else{
				echo"<span><br />$food_dietary_fiber_diff_med</span>";
			}
			echo"
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_proteins</span>
		   </td>";
				if($system == "all" OR $system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_proteins_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_proteins_calculated_metric</span>
		 			  </td>
					";
				}
				if($system == "all" OR $system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_proteins_us_system</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_proteins_calculated_us_system</span>
		 			  </td>
					";
				}
		echo"
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
			<span>$get_current_sub_category_proteins_med</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
			$proteins_diff_med = round($get_current_food_proteins_metric-$get_current_sub_category_proteins_med, 0);
			$proteins_diff_med = $proteins_diff_med*-1;
			
			if($proteins_diff_med < 0){
				echo"<span style=\"color: green;\">$proteins_diff_med</span>";
			}
			elseif($proteins_diff_med > 0){
				echo"<span style=\"color: red;\">$proteins_diff_med</span>";
			}
			else{
				echo"<span>$proteins_diff_med*</span>";
			}
			echo"
		   </td>
		  </tr>
		 </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_salt_in_gram<br />
			$l_dash_of_which_sodium_in_mg</span>
		   </td>";
				if($system == "all" OR $system == "metric"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_salt_metric<br />
						$get_current_food_sodium_metric</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_salt_calculated_metric<br />
						$get_current_food_sodium_calculated_metric</span>
					   </td>
					";
				}
				if($system == "all" OR $system == "us"){
					echo"
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_salt_us_system<br />
						$get_current_food_sodium_us_system</span>
					   </td>
					   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<span>$get_current_food_salt_calculated_us_system<br />
						$get_current_food_sodium_calculated_us_system</span>
					   </td>
					";
				}
		echo"
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_med\">
			<span>$get_current_sub_category_salt_med</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"current_sub_category_calories_diff\">";
			$salt_diff_med = round($get_current_food_salt_metric-$get_current_sub_category_salt_med, 0);
			
			if($salt_diff_med > 0){
				echo"<span style=\"color: red;\">$salt_diff_med</span>";
			}
			elseif($salt_diff_med < 0){
				echo"<span style=\"color: green;\">$salt_diff_med</span>";
			}
			else{
				echo"<span>$salt_diff_med</span>";
			}
			echo"
		   </td>
		  </tr>
		</table>

		<script>
		\$(document).ready(function(){
			\$(\".a_show_score\").click(function () {
				\$(\".current_sub_category_calories_med\").toggle();
				\$(\".current_sub_category_calories_diff\").toggle();
				\$(\".protein_diff\").toggle();
			});
		});
		</script>

		<p>
		<a href=\"#numbers\" class=\"a_show_score\">Score:</a> ";

			$score_number = $energy_diff_med+$fat_diff_med+$food_fat_of_which_saturated_fatty_acids_diff_med+$carbohydrate_diff_med+$food_dietary_fiber_diff_med+$food_carbohydrates_of_which_sugars_diff_med+$proteins_diff_med+$salt_diff_med;

			if($get_current_food_score != $score_number){
				$result = mysqli_query($link, "UPDATE $t_food_index SET food_score='$score_number' WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));
			}

			if($score_number > 0){
				echo"
				<em style=\"color: red;\">$score_number</em>
				:-(";
			}
			elseif($score_number < 0){
				echo"
				<em style=\"color: green;\">$score_number</em>
				:-)";
			}
			else{
				echo"
				<em>$score_number</em>
				:-/";
			}
					echo"
					</p>
				<p class=\"protein_diff\">*$l_protein_diff_is_multiplied_with_minus_one_to_get_correct_calculation</p>
			<!-- //Numbers -->


			<!-- Info -->
				<h2>Info</h2>

			<table class=\"hor-zebra\" style=\"width: auto;min-width: 0;display: table;\">
			 <tbody>
			  <tr>
			   <td style=\"padding: 8px 4px 6px 8px;\">
				<span><b>Manufacturer:</b></span>
			   </td>
			   <td style=\"padding: 0px 4px 0px 4px;\">
				<span><a href=\"../food/search.php?manufacturer_name=$get_current_food_manufacturer_name&amp;l=$l\">$get_current_food_manufacturer_name</a></span>
			   </td>
			  </tr>
			  <tr>
			   <td style=\"padding: 8px 4px 6px 8px;\">
				<span><b>Barcode:</b></span>
			   </td>
			   <td style=\"padding: 0px 4px 0px 4px;\">
				<span><a href=\"../food/search.php?barcode=$get_current_food_barcode&amp;l=$l\">$get_current_food_barcode</a></span>
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
			<span><b>$l_us_system:</b></span>
		   </td>
		   <td style=\"padding: 0px 4px 0px 4px;\">
			<span>$get_current_food_net_content_us_system $get_current_food_net_content_measurement_us_system</span>
		   </td>
		  </tr>
			  <tr>
			   <td style=\"padding: 8px 4px 6px 8px;\">
				<span><b>Stores:</b></span>
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
				<a href=\"../food/search.php?q=&amp;barcode=&amp;manufacturer_name=&amp;store_id=$get_current_food_store_store_id&amp;order_by=food_score&amp;order_method=asc&amp;l=$l\">$get_current_food_store_store_name</a>";
				
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
			 </tbody>
			</table>

			<!-- //Info -->
		<!-- //View food -->
		";
	} // action == ""
} // food found
?>