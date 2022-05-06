<?php
/**
*
* File: _admin/_inc/food/edit_food_images.php
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

if(isset($_GET['image'])){
	$image = $_GET['image'];
	$image = strip_tags(stripslashes($image));
}
else{
	$image = "";
}

/*- Functions -------------------------------------------------------------------------- */
include("_functions/get_extension.php");

/*- Settings ---------------------------------------------------------------------------- */
$settings_image_width = "847";
$settings_image_height = "847";

$root = "..";

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
				<li><a href=\"index.php?open=$open&amp;page=edit_food_numbers_hundred_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\">Numbers</a>
				<li><a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Images</a>
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
	";


	if($action == ""){
		echo"
		<h2>Images</h2>

		<table>
		 <tr>
		  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
			<p><b>product:</b></p>
		  </td>
		  <td>
			<p>";
			if($get_current_food_image_a != ""){
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
				<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=rotate&amp;image=a&amp;l=$l&amp;process=1\" class=\"btn btn_default\">Rotate</a>
				<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=delete&amp;image=a&amp;l=$l\" class=\"btn btn_default\">Delete</a>
				";
			}
			echo"
			<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=upload_new&amp;image=a&amp;l=$l\" class=\"btn btn_default\">Upload new</a>
			</p>
		  </td>
		 </tr>

		 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
						<p><b>Food_table:</b></p>
					  </td>
					  <td>
						<p>";
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_b") && $get_current_food_image_b != ""){
							// Thumb B medium
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
							<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=rotate&amp;image=b&amp;l=$l&amp;process=1\" class=\"btn btn_default\">Rotate</a>
							<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=delete&amp;image=b&amp;l=$l\" class=\"btn btn_default\">Delete</a>
							";
						}
						echo"
						<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=upload_new&amp;image=b&amp;l=$l\" class=\"btn btn_default\">Upload new</a>
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
						<p><b>Other:</b></p>
				 	 </td>
				 	 <td>
						<p>";
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_c") && $get_current_food_image_c != ""){
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
							<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=rotate&amp;image=c&amp;l=$l&amp;process=1\" class=\"btn btn_default\">Rotate</a>
							<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=delete&amp;image=c&amp;l=$l\" class=\"btn btn_default\">Delete</a>
							";
						}
						echo"
						<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=upload_new&amp;image=c&amp;l=$l\" class=\"btn btn_default\">Upload new</a>
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align: top;\">
						<p><b>Inspiration:</b></p>
					  </td>
					  <td>
						<p>";
						if(file_exists("../$get_current_food_image_path/$get_current_food_image_d") && $get_current_food_image_d != ""){
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
							<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=rotate&amp;image=d&amp;l=$l&amp;process=1\" class=\"btn btn_default\">Rotate</a>
							<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=delete&amp;image=d&amp;l=$l\" class=\"btn btn_default\">Delete</a>
							";
						}
						echo"
						<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=upload_new&amp;image=d&amp;l=$l\" class=\"btn btn_default\">Upload new</a>
						</p>
					  </td>
					 </tr>
					</table>
				<!-- //Images -->


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


				$url = "index.php?open=$open&page=edit_food_images_admin&main_category_id=$get_current_food_main_category_id&sub_category_id=$get_current_food_sub_category_id&food_id=$get_current_food_id&editor_language=$editor_language&l=$l&ft=success&fm=image_rotated";
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



					$url = "index.php?open=$open&page=edit_food_images_admin&main_category_id=$get_current_food_main_category_id&sub_category_id=$get_current_food_sub_category_id&food_id=$get_current_food_id&editor_language=$editor_language&ft=success&fm=image_deleted&image=$image";
					header("Location: $url");
					exit;

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

				<!-- Delete -->

					
					<h2>Images</h2>

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
						<p>Are you sure you want to delete?
						The action cant be undone.
						</p>

						<p><img src=\"$root/$current_photo_path\" alt=\"$current_photo_path\" />
						</p>

						<p>
						<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=delete&amp;image=$image&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a>
						<a href=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn btn_default\">Cancel</a>		
						</p>
						";
					}

					echo"
				<!-- //Delete -->


				";
	} // action == "rotate"
	elseif($action == "upload_new" && $image != ""){
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

					// Clean name
					$food_name_clean = clean($get_current_food_name);
					$food_manufacturer_name_clean = clean($get_current_food_manufacturer_name);


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
				
					/*- Image upload ------------------------------------------------------------------------------------------ */
					$name = stripslashes($_FILES['inp_food_image']['name']);
					$extension = get_extension($name);
					$extension = strtolower($extension);

					if($name){
						if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
							$ft_image_a = "warning";
							$fm_image_a = "unknown_file_extension";
						}
						else{
					
 
							// Give new name
							$food_manufacturer_name_clean = clean($get_current_food_manufacturer_name);


							if($image == "a"){
								$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_a." . $extension;
							}
							elseif($image == "b"){
								$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_b." . $extension;
							}
							elseif($image == "c"){
								$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_c." . $extension;
							}
							elseif($image == "d"){
								$new_name = $food_manufacturer_name_clean . "_" . $food_name_clean . "_d." . $extension;
							}
							else{
								echo"image number?";
								die;
							}
						
							$new_path = "../_uploads/food/_img/$l/$get_current_food_id/";
							$uploaded_file = $new_path . $new_name;
							// Upload file
							if (move_uploaded_file($_FILES['inp_food_image']['tmp_name'], $uploaded_file)) {
	

								// Get image size
								$file_size = filesize($uploaded_file);
						
								// Check with and height
								list($width,$height) = getimagesize($uploaded_file);
	
								if($width == "" OR $height == ""){
									$ft_image = "warning";
									$fm_image = "getimagesize_failed";
								}
								else{

								// Resize to 847x847
									$uploaded_file_new = $uploaded_file;
									if($width > 847 OR $height > 847){
										resize_crop_image($settings_image_width, $settings_image_height, $uploaded_file, $uploaded_file_new, $quality = 80);
									}

									$inp_food_image_path = "_uploads/food/_img/$l/$get_current_food_id";
									$inp_food_image_path = output_html($inp_food_image_path);
									$inp_food_image_path_mysql = quote_smart($link, $inp_food_image_path);
									$inp_food_image_mysql = quote_smart($link, $new_name);

									if($image == "a"){
										$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_path=$inp_food_image_path_mysql, food_image_a=$inp_food_image_mysql, food_thumb_a_small='', food_thumb_a_medium='', food_thumb_a_large='' WHERE food_id='$get_current_food_id'");
									}
									elseif($image == "b"){
										$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_path=$inp_food_image_path_mysql, food_image_b=$inp_food_image_mysql, food_thumb_b_small='', food_thumb_b_medium='', food_thumb_b_large='' WHERE food_id='$get_current_food_id'");
									}
									elseif($image == "c"){
										$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_path=$inp_food_image_path_mysql, food_image_c=$inp_food_image_mysql, food_thumb_c_small='', food_thumb_c_medium='', food_thumb_c_large='' WHERE food_id='$get_current_food_id'");
									}
									elseif($image == "d"){
										$result = mysqli_query($link, "UPDATE $t_food_index SET food_image_path=$inp_food_image_path_mysql, food_image_d=$inp_food_image_mysql, food_thumb_d_small='', food_thumb_d_medium='', food_thumb_d_large='' WHERE food_id='$get_current_food_id'");
									}

								}  // if($width == "" OR $height == ""){
							} // move_uploaded_file
							else{
								switch ($_FILES['inp_food_image']['error']) {
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
								}	
							}
	
						} // extension check
					} // if($image){


					// Search engine
					include("new_food_00_add_update_search_engine.php");

					// Feedback
					if(isset($fm_image)){
					// Feedback with error
					$url = "index.php?open=$open&page=edit_food_images_admin&main_category_id=$get_current_food_main_category_id&sub_category_id=$get_current_food_sub_category_id&food_id=$get_current_food_id&editor_language=$editor_language&action=upload_new&image=$image&l=$l";
					if(isset($fm_image)){
						$url = $url . "&fm_image=$fm_image";
					}
					header("Location: $url");
					exit;
					}
					else{
						// Feedback without error
						$url = "index.php?open=$open&page=edit_food_images_admin&main_category_id=$get_current_food_main_category_id&sub_category_id=$get_current_food_sub_category_id&food_id=$get_current_food_id&editor_language=$editor_language&l=$l";
						header("Location: $url");
						exit;
					}

				}
				echo"
				<!-- Feedback -->
			";
			if(isset($_GET['fm_image'])){
				echo"
				<div class=\"info\">
					<p>
					";
					if(isset($_GET['fm_image'])){
						$fm_image = $_GET['fm_image'];

						if($fm_image == "unknown_file_extension"){
							echo"Product image: Unknown file extension<br />\n";
						}
						elseif($fm_image == "getimagesize_failed"){
							echo"Product image: Could not get with and height of image<br />\n";
						}
						elseif($fm_image == "image_to_big"){
							echo"Product image: Image file size to big<br />\n";
						}
						elseif($fm_image == "to_big_size_in_configuration"){
							echo"Product image: Image file size to big (in config)<br />\n";
						}
						elseif($fm_image == "to_big_size_in_form"){
							echo"Product image: Image file size to big (in form)<br />\n";
						}
						elseif($fm_image == "unknown_error"){
							echo"Product image: Unknown error<br />\n";
						}

					}
				echo"
				</div>
				";
			}
			echo"
				<!-- //Feedback -->

				<!-- Upload new -->
					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_food_image\"]').focus();
						});
						</script>
					<!-- //Focus -->

					<form method=\"post\" action=\"index.php?open=$open&amp;page=edit_food_images_admin&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;editor_language=$editor_language&amp;action=upload_new&amp;l=$l&amp;image=$image&amp;process=1\" enctype=\"multipart/form-data\">
					";

					if($image == "a"){
						echo"<h2>Upload product image</h2>";
					}
					elseif($image == "b"){
						echo"<h2>Upload food table image</h2>";
					}
					elseif($image == "c"){
						echo"<h2>Upload other image</h2>";
					}
					elseif($image == "d"){
						echo"<h2>Upload inspiration image</h2>";
					}

					echo"

					<p>
					<b>Select image (jpg $settings_image_width x $settings_image_height px)</b><br />
					<input type=\"file\" name=\"inp_food_image\" /> 
					<input type=\"submit\" value=\"Upload\" class=\"btn\" />
					</p>

				
				<!-- //Upload new -->


		";
	} // action == "rotate"


} // food found
?>