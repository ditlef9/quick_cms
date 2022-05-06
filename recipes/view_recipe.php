<?php 
/**
*
* File: recipes/view_recipe.php
* Version 3.0.0
* Date 17:36 01.01.2021
* Copyright (c) 2021 Localhost
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
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");
include("$root/_admin/_translations/site/$l/recipes/ts_view_recipe.php");

/*- Tables ----------------------------------------------------------------------------- */
$t_recipes_similar_recipes	= $mysqlPrefixSav . "recipes_similar_recipes";
$t_recipes_similar_loaded	= $mysqlPrefixSav . "recipes_similar_loaded";

$t_recipes_pairing_recipes	= $mysqlPrefixSav . "recipes_pairing_recipes";
$t_recipes_pairing_loaded	= $mysqlPrefixSav . "recipes_pairing_loaded";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}

if(isset($_GET['servings'])) {
	$servings = $_GET['servings'];
	$servings = strip_tags(stripslashes($servings));
	$servings = str_replace(",", ".", $servings);

	if(!is_numeric($servings)){
		echo"
		<h1>Server error</h1>
		<p>The servings has to be numeric.</p>
		";
		die;
	}
	if($servings < 0 OR $servings > 999){
		echo"
		<h1>Server error</h1>
		<p>Are you really going to feed so many people?</p>
		";
		die;
	}
}
else{
	$servings = "";
}


$l_mysql = quote_smart($link, $l);


/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");


/*- Get recipe ------------------------------------------------------------------------- */
// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_ingredient_id, recipe_ingredient_title, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_ingredient_id, $get_recipe_ingredient_title, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;


/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$get_recipe_title - $l_recipes";
}
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to view was not found.
	</p>

	<p>
	<a href=\"index.php\">Back</a>
	</p>
	";
}
else{
	// Age

	$can_view_recipe = 1;
	$can_view_images = 1;
	if($get_recipe_age_restriction == "1"){
		// Check if I have accepted 
		$inp_ip_mysql = quote_smart($link, $inp_ip);
		$query_t = "SELECT accepted_id, accepted_country FROM $t_recipes_age_restrictions_accepted WHERE accepted_ip=$inp_ip_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_accepted_id, $get_accepted_country) = $row_t;
		
		if($get_accepted_id == ""){
			// Accept age restriction
			$can_view_recipe = 0;
			include("view_recipe_show_age_restriction_warning.php");
		}
		else{
			// Can I see recipe and images?
			$country_mysql = quote_smart($link, $get_accepted_country);
			$query = "SELECT restriction_id, restriction_country_iso, restriction_country_name, restriction_country_flag, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_can_view_recipe, restriction_can_view_image FROM $t_recipes_age_restrictions WHERE restriction_country_iso=$country_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_restriction_id, $get_restriction_country_iso, $get_restriction_country_name, $get_restriction_country_flag, $get_restriction_language, $get_restriction_age_limit, $get_restriction_title, $get_restriction_text, $get_restriction_can_view_recipe, $get_restriction_can_view_image) = $row;

			$can_view_recipe = $get_restriction_can_view_recipe;
			$can_view_images = $get_restriction_can_view_image;

			if($can_view_recipe == 0){
				echo"
				<h1 style=\"padding-bottom:0;margin-bottom:0;\">$get_recipe_title</h1>
				<p>$get_restriction_text</p>
				";
			}
		}
	}


	if($can_view_recipe == 1){
		// Common varaibles
		$inp_recipe_language_mysql = quote_smart($link, $get_recipe_language);


		// Unique hits
		$inp_ip = $_SERVER['REMOTE_ADDR'];
		$inp_ip = output_html($inp_ip);
		$inp_ip_mysql = quote_smart($link, $inp_ip);

		$recipe_unique_hits_ip_block_array = explode("\n", $get_recipe_unique_hits_ip_block);
		$recipe_unique_hits_ip_block_array_size = sizeof($recipe_unique_hits_ip_block_array);
		
		if($recipe_unique_hits_ip_block_array_size > 10){
			$recipe_unique_hits_ip_block_array_size = 5;
		}
	
		$has_seen_this_recipe_before = 0;


		$inp_unique_hits_ip_block = "";
		for($x=0;$x<$recipe_unique_hits_ip_block_array_size;$x++){
			if($recipe_unique_hits_ip_block_array[$x] == "$inp_ip"){
				$has_seen_this_recipe_before = 1;
				break;
			}
			if($inp_unique_hits_ip_block == ""){
				$inp_unique_hits_ip_block = $recipe_unique_hits_ip_block_array[$x];
			}
			else{
				$inp_unique_hits_ip_block = $inp_unique_hits_ip_block . "\n" . $recipe_unique_hits_ip_block_array[$x];
			}
		}
	
		if($has_seen_this_recipe_before == 0){
			$inp_unique_hits_ip_block = $inp_ip . "\n" . $inp_unique_hits_ip_block;
			$inp_unique_hits_ip_block_mysql = quote_smart($link, $inp_unique_hits_ip_block);
			$inp_recipe_unique_hits = $get_recipe_unique_hits + 1;
			$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_unique_hits=$inp_recipe_unique_hits, recipe_unique_hits_ip_block=$inp_unique_hits_ip_block_mysql WHERE recipe_id=$recipe_id_mysql") or die(mysqli_error($link));
		}



		// Author
		$query = "SELECT user_name, user_alias, user_city_name, user_country_name FROM $t_users WHERE user_id=$get_recipe_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_name, $get_user_alias, $get_user_city_name, $get_user_country_name) = $row;


		// Author Photo
		$q = "SELECT photo_id, photo_user_id, photo_destination, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_recipe_user_id' AND photo_profile_image='1'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_photo_id, $get_photo_user_id, $get_photo_destination, $get_photo_thumb_50, $get_photo_thumb_60, $get_photo_thumb_200) = $rowb;
	

		if($get_photo_id != ""){					
			if($get_photo_thumb_50 == ""){
				$extension = get_extension($get_photo_destination);
				$extension = strtolower($extension);
				$name = str_replace(".$extension", "", $get_photo_destination);
	
				// Small
				$thumb_a = $name . "_40." . $extension;
				$thumb_a_mysql = quote_smart($link, $thumb_a);

				// Medium
				$thumb_b = $name . "_50." . $extension;
				$thumb_b_mysql = quote_smart($link, $thumb_b);

				// Large
				$thumb_c = $name . "_60." . $extension;
				$thumb_c_mysql = quote_smart($link, $thumb_c);

				// Extra Large
				$thumb_d = $name . "_200." . $extension;
				$thumb_d_mysql = quote_smart($link, $thumb_d);
		
				// Update
				$result_update = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_thumb_40=$thumb_a_mysql, photo_thumb_50=$thumb_b_mysql, photo_thumb_60=$thumb_c_mysql, photo_thumb_200=$thumb_d_mysql WHERE photo_id=$get_photo_id");
				
				// Pass new variables
				$get_photo_thumb_40 = "$thumb_a";
				$get_photo_thumb_50 = "$thumb_b";
				$get_photo_thumb_60 = "$thumb_c";
				$get_photo_thumb_200 = "$thumb_d";
			}
			if($get_photo_destination != "" && !(file_exists("$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_50"))){
				// Thumb
				$inp_new_x = 50;
				$inp_new_y = 50;
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_photo_user_id/$get_photo_destination", "$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_50");
			} // thumb
			if($get_photo_destination != "" && !(file_exists("$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_60"))){
				// Thumb
				$inp_new_x = 60;
				$inp_new_y = 60;
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_photo_user_id/$get_photo_destination", "$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_60");
			} // thumb
		}



		// Category
		$query = "SELECT category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_category_translation_title) = $row;

		// Statistics :: Views :: Month
		$year = date("Y");
		$month = date("m");
		$month_full = date("F");
		$month_short = date("M");
		$inp_recipe_title_mysql = quote_smart($link, $get_recipe_title);
		$inp_recipe_image_path_mysql = quote_smart($link, $get_recipe_image_path);
		$inp_recipe_thumb_278x156_mysql = quote_smart($link, $get_recipe_thumb_h_a_278x156);

		if($get_recipe_published == "1"){
			$query = "SELECT stats_visit_per_month_ip_id FROM $t_recipes_stats_views_per_month_ips WHERE stats_visit_per_month_month=$month AND stats_visit_per_month_year=$year AND stats_visit_per_month_recipe_id=$get_recipe_id AND stats_visit_per_month_ip=$inp_ip_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_stats_visit_per_month_ip_id) = $row;
			if($get_stats_visit_per_month_ip_id == ""){
				// Insert IP block
				mysqli_query($link, "INSERT INTO $t_recipes_stats_views_per_month_ips 
				(stats_visit_per_month_ip_id, stats_visit_per_month_month, stats_visit_per_month_year, stats_visit_per_month_recipe_id, stats_visit_per_month_ip) 
				VALUES 
				(NULL, $month, $year, $get_recipe_id, $inp_ip_mysql)")
				or die(mysqli_error($link)); 

				// Delete old
				mysqli_query($link, "DELETE FROM $t_recipes_stats_views_per_month_ips WHERE stats_visit_per_month_month != $month") or die(mysqli_error($link)); 

				// +1
				$query = "SELECT stats_visit_per_month_id, stats_visit_per_month_count FROM $t_recipes_stats_views_per_month WHERE stats_visit_per_month_month=$month AND stats_visit_per_month_year=$year AND stats_visit_per_month_recipe_id=$get_recipe_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_visit_per_month_id, $get_stats_visit_per_month_count) = $row;
				if($get_stats_visit_per_month_id == ""){
					// First visit this month
					$inp_category_translated_mysql = quote_smart($link, $get_category_translation_title);
					mysqli_query($link, "INSERT INTO $t_recipes_stats_views_per_month 
					(stats_visit_per_month_id, stats_visit_per_month_month, stats_visit_per_month_month_full, stats_visit_per_month_month_short, stats_visit_per_month_year, 
					stats_visit_per_month_recipe_id, stats_visit_per_month_recipe_title, stats_visit_per_month_recipe_image_path, stats_visit_per_month_recipe_thumb_278x156, stats_visit_per_month_recipe_language, 
					stats_visit_per_month_recipe_category_id, stats_visit_per_month_recipe_category_translated, stats_visit_per_month_count) 
					VALUES 
					(NULL, $month, '$month_full', '$month_short', $year,
					$get_recipe_id, $inp_recipe_title_mysql, $inp_recipe_image_path_mysql, $inp_recipe_thumb_278x156_mysql, $inp_recipe_language_mysql,
					$get_recipe_category_id, $inp_category_translated_mysql, 1)")
					or die(mysqli_error($link)); 
				}
				else{
					// Update visit
					$inp_count = $get_stats_visit_per_month_count+1;
					mysqli_query($link, "UPDATE $t_recipes_stats_views_per_month SET stats_visit_per_month_count=$inp_count WHERE stats_visit_per_month_id=$get_stats_visit_per_month_id") or die(mysqli_error($link)); 
				}

				// Chef of the month
				$query = "SELECT stats_chef_of_the_month_id, stats_chef_of_the_month_recipes_posted_count, stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points FROM $t_recipes_stats_chef_of_the_month WHERE stats_chef_of_the_month_month=$month AND stats_chef_of_the_month_year=$year AND stats_chef_of_the_month_user_id=$get_recipe_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_chef_of_the_month_id, $get_stats_chef_of_the_month_recipes_posted_count, $get_stats_chef_of_the_month_recipes_posted_points, $get_stats_chef_of_the_month_got_visits_count, $get_stats_chef_of_the_month_got_visits_points, $get_stats_chef_of_the_month_got_favorites_count, $get_stats_chef_of_the_month_got_favorites_points, $get_stats_chef_of_the_month_got_comments_count, $get_stats_chef_of_the_month_got_comments_points, $get_stats_chef_of_the_month_total_points) = $row;
				if($get_stats_chef_of_the_month_id == ""){
					// Insert chef of the month
					$inp_user_name_mysql = quote_smart($link, $get_user_name);
					$inp_user_photo_path_mysql = quote_smart($link, "_uploads/users/images/$get_recipe_user_id");
					$inp_user_photo_thumb_mysql = quote_smart($link, $get_photo_thumb_200);

					mysqli_query($link, "INSERT INTO $t_recipes_stats_chef_of_the_month 
					(stats_chef_of_the_month_id, stats_chef_of_the_month_month, stats_chef_of_the_month_month_full, stats_chef_of_the_month_month_short, stats_chef_of_the_month_year, 
					stats_chef_of_the_month_user_id, stats_chef_of_the_month_user_name, stats_chef_of_the_month_user_photo_path, stats_chef_of_the_month_user_photo_thumb, stats_chef_of_the_month_recipes_posted_count, 
					stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, 
					stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points) 
					VALUES 
					(NULL, $month, '$month_full', '$month_short', $year,
					$get_recipe_user_id, $inp_user_name_mysql, $inp_user_photo_path_mysql, $inp_user_photo_thumb_mysql, 0,
					0, 1, 0.1, 0, 0, 
					0, 0, 1)")
					or die(mysqli_error($link));
				}
				else{
					// Update visit
					$inp_count = $get_stats_chef_of_the_month_got_visits_count+1;
					$inp_points = $inp_count*0.001;
					$inp_total_points = $get_stats_chef_of_the_month_recipes_posted_points+$inp_points+$get_stats_chef_of_the_month_got_favorites_points+$get_stats_chef_of_the_month_got_comments_points;
					mysqli_query($link, "UPDATE $t_recipes_stats_chef_of_the_month SET stats_chef_of_the_month_got_visits_count=$inp_count, stats_chef_of_the_month_got_visits_points=$inp_points, stats_chef_of_the_month_total_points=$inp_total_points WHERE stats_chef_of_the_month_id=$get_stats_chef_of_the_month_id") or die(mysqli_error($link)); 
				}

			}

			// Statistics :: Views :: Year
			$query = "SELECT stats_visit_per_year_ip_id FROM $t_recipes_stats_views_per_year_ips WHERE stats_visit_per_year_year=$year AND stats_visit_per_year_recipe_id=$get_recipe_id AND stats_visit_per_year_ip=$inp_ip_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_stats_visit_per_year_ip_id) = $row;
			if($get_stats_visit_per_year_ip_id == ""){
				// Insert IP block
				mysqli_query($link, "INSERT INTO $t_recipes_stats_views_per_year_ips 
				(stats_visit_per_year_ip_id, stats_visit_per_year_year, stats_visit_per_year_recipe_id, stats_visit_per_year_ip) 
				VALUES 
				(NULL, $year, $get_recipe_id, $inp_ip_mysql)")
				or die(mysqli_error($link)); 

				// Delete old
				mysqli_query($link, "DELETE FROM $t_recipes_stats_views_per_year_ips WHERE stats_visit_per_year_year != $year") or die(mysqli_error($link)); 

				// +1
				$query = "SELECT stats_visit_per_year_id, stats_visit_per_year_count FROM $t_recipes_stats_views_per_year WHERE stats_visit_per_year_year=$year AND stats_visit_per_year_recipe_id=$get_recipe_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_visit_per_year_id, $get_stats_visit_per_year_count) = $row;
				if($get_stats_visit_per_year_id == ""){
					// First visit this month
					$inp_category_translated_mysql = quote_smart($link, $get_category_translation_title);
					mysqli_query($link, "INSERT INTO $t_recipes_stats_views_per_year 
					(stats_visit_per_year_id, stats_visit_per_year_year, stats_visit_per_year_recipe_id, stats_visit_per_year_recipe_title, stats_visit_per_year_recipe_image_path, 
					stats_visit_per_year_recipe_thumb_278x156, stats_visit_per_year_recipe_language, stats_visit_per_year_recipe_category_id, stats_visit_per_year_recipe_category_translated, stats_visit_per_year_count) 
					VALUES 
					(NULL, $year, $get_recipe_id, $inp_recipe_title_mysql, $inp_recipe_image_path_mysql, 
					$inp_recipe_thumb_278x156_mysql, $inp_recipe_language_mysql, $get_recipe_category_id, $inp_category_translated_mysql, 1)")
					or die(mysqli_error($link)); 
				}
				else{
				// Update visit
						$inp_count = $get_stats_visit_per_year_count+1;
				mysqli_query($link, "UPDATE $t_recipes_stats_views_per_year SET stats_visit_per_year_count=$inp_count WHERE stats_visit_per_year_id=$get_stats_visit_per_year_id") or die(mysqli_error($link)); 
				}
			}
		} // recipe published for statistics

		// Select Nutrients
		$query = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row;


		// Check Date, Time
		if($get_recipe_date == ""){
			$get_recipe_date = date("Y-m-d");
			$get_recipe_time = date("H:i");
			$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_date='$get_recipe_date', recipe_time='$get_recipe_time' WHERE recipe_id=$recipe_id_mysql");
		}

		// Date
		$recipe_year = substr($get_recipe_date, 0, 4);
		$recipe_month = substr($get_recipe_date, 5, 2);
		$recipe_day = substr($get_recipe_date, 8, 2);

		if($recipe_day < 10){
			$recipe_day = substr($recipe_day, 1, 1);
		}
	
		if($recipe_month == "01"){
			$recipe_month_saying = $l_january;
		}
		elseif($recipe_month == "02"){
			$recipe_month_saying = $l_february;
		}
		elseif($recipe_month == "03"){
			$recipe_month_saying = $l_march;
		}
		elseif($recipe_month == "04"){
			$recipe_month_saying = $l_april;
		}
		elseif($recipe_month == "05"){
			$recipe_month_saying = $l_may;
		}
		elseif($recipe_month == "06"){
			$recipe_month_saying = $l_june;
		}
		elseif($recipe_month == "07"){
			$recipe_month_saying = $l_july;
		}
		elseif($recipe_month == "08"){
			$recipe_month_saying = $l_august;
		}
		elseif($recipe_month == "09"){
			$recipe_month_saying = $l_september;
		}
		elseif($recipe_month == "10"){
			$recipe_month_saying = $l_october;
		}
		elseif($recipe_month == "11"){
			$recipe_month_saying = $l_november;
		}
		else{
			$recipe_month_saying = $l_december;
		}

		// Time
		$get_recipe_time = substr($get_recipe_time, 0, 5);

		// Last viewed
		$inp_last_viewed = date("Y-m-d H:i:s");
		$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_last_viewed='$inp_last_viewed' WHERE recipe_id=$recipe_id_mysql");
	


		// Cusine
		if($get_recipe_cusine_id != 0){
		$query = "SELECT cuisine_translation_id, cuisine_translation_value FROM $t_recipes_cuisines_translations WHERE cuisine_id=$get_recipe_cusine_id AND cuisine_translation_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_cuisine_translation_id, $get_cusine_translation_value) = $row;
		}

		// Season
		if($get_recipe_season_id != 0){
			$query = "SELECT season_translation_id, season_translation_value FROM $t_recipes_seasons_translations WHERE season_id=$get_recipe_season_id AND season_translation_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_season_translation_id, $get_season_translation_value) = $row;
		}

		// Occasion
		if($get_recipe_occasion_id != 0){
			$query = "SELECT occasion_translation_id, occasion_translation_value FROM $t_recipes_occasions_translations WHERE occasion_id=$get_recipe_occasion_id AND occasion_translation_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_occasion_translation_id, $get_occasion_translation_value) = $row;
		}


		// Rating
		$query_rating = "SELECT rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_votes_plus_average, rating_ip_block FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
		$result_rating = mysqli_query($link, $query_rating);
		$row_rating = mysqli_fetch_row($result_rating);
		list($get_rating_id, $get_rating_recipe_id, $get_rating_1, $get_rating_2, $get_rating_3, $get_rating_4, $get_rating_5, $get_rating_total_votes, $get_rating_average, $get_rating_votes_plus_average, $get_rating_ip_block) = $row_rating;
		if($get_rating_average == ""){
			$get_rating_average = 0;
		}

		// My data
		$get_my_user_id = 0;
		$get_my_user_rank = "";
		$get_recipe_favorite_id = 0;
		if(isset($_SESSION['user_id'])){
		// My user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$q = "SELECT user_id, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_my_user_id, $get_my_user_rank) = $rowb;

		// Favorite
		$q = "SELECT recipe_favorite_id FROM $t_recipes_favorites WHERE recipe_favorite_recipe_id=$get_recipe_id AND recipe_favorite_user_id=$my_user_id_mysql";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_recipe_favorite_id) = $rowb;
		}

		echo"
		<!-- Headline -->

		<div class=\"recipe_headline\">
			<h1>$get_recipe_title</h1>

			<span>
			<a href=\"index.php?l=$l\">$l_recipes</a>
			&gt;
			<a href=\"categories_browse.php?category_id=$get_recipe_category_id&amp;l=$l\">$get_category_translation_title</a>
			&gt;
			<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\">$get_recipe_title</a>
			</span>

			<!-- Recipe image/video -->
			";
			if($get_recipe_video_h != ""){
				echo"
				<iframe width=\"847\" height=\"476\" src=\"$get_recipe_video_h\" frameborder=\"0\" allowfullscreen></iframe>
				";
			}
			else{
				if($get_recipe_image_h_a != ""){
					echo"<p><img src=\"$root/$get_recipe_image_path/$get_recipe_image_h_a\" alt=\"$get_recipe_image_h_a\" /></p>";
				}
			}
			echo"	
			<!-- //Recipe image/video -->



			<!-- Categorization -->
					<a id=\"favorite\"></a>
					<ul>
						<li>
						<a href=\"categories_browse.php?category_id=$get_recipe_category_id&amp;l=$l\"><img src=\"_gfx/icons/outline_folder_black_18dp.png\" alt=\"outline_folder_black_18dp.png\" />
						$get_category_translation_title</a>
						</li>
						";

						// Cusine
						if($get_recipe_cusine_id != 0){
							echo"
							<li>
							<a href=\"cuisines_browse.php?cuisine_id=$get_recipe_cusine_id&amp;l=$l\" title=\"$l_cuisine\"><img src=\"_gfx/icons/outline_public_black_18dp.png\" alt=\"outline_public_black_18dp.png\" />
							$get_cusine_translation_value</a>
							</li>
							";
						}

						// Season
						if($get_recipe_season_id != 0){
							echo"
							<li>
							<a href=\"seasons_browse.php?season_id=$get_recipe_season_id&amp;l=$l\" title=\"$l_season\"><img src=\"_gfx/icons/outline_brightness_4_black_18dp.png\" alt=\"outline_brightness_4_black_18dp.png\" />
							$get_season_translation_value</a>
							</li>
							";
						}

						// Occasion
						if($get_recipe_occasion_id != 0){
							echo"
							<li>
							<a href=\"occasions_browse.php?occasion_id=$get_recipe_occasion_id&amp;l=$l\" title=\"$l_occasion\"><img src=\"_gfx/icons/outline_cake_black_18dp.png\" alt=\"outline_cake_black_18dp.png\" />
							$get_occasion_translation_value</a>
							</li>
							";
						}



						echo"
					</ul>
				
			<!-- //Categorization -->
			<!-- Tags -->
				<ul>
			";

					$x = 0;
					$query = "SELECT tag_id, tag_title, tag_title_clean FROM $t_recipes_tags WHERE tag_recipe_id=$get_recipe_id ORDER BY tag_id ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_tag_id, $get_tag_title, $get_tag_title_clean) = $row;
						echo"
						<li><a href=\"view_tag.php?tag=$get_tag_title_clean&amp;l=$l\">#$get_tag_title</a></li>
						";
						$x++;
					}
					if($x == "0"){
						echo"<li>[<a href=\"suggest_tags.php?recipe_id=$recipe_id&amp;l=$l\">$l_suggest_tags</a>]</li>";
					}
			echo"
				</ul>
			<!-- //Tags -->


			<!-- Rating -->
				<div class=\"headline_hr\"></div>
				<ul>
					<li>";
					if($get_rating_total_votes == "0"){
						echo"
						<a href=\"view_recipe_write_comment.php?recipe_id=$recipe_id&amp;l=$l#comments\" class=\"rating_stars_onclick\">";
					}
					else{
						echo"
						<a href=\"view_recipe.php?recipe_id=$recipe_id&amp;l=$l#comments\" class=\"rating_stars_onclick\">";
					}
						// Rating stars
						$rating_count = 1;
						for($x=0;$x<$get_rating_average;$x++){
							echo"			";
							echo"<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />\n ";
							$rating_count++;
						}

						$rest = 5-$get_rating_average;
						$rating_count = $get_rating_average+1;
						for($x=0;$x<$rest;$x++){
							echo"			";
							echo"<img src=\"_gfx/icons/outline_star_outline_black_18dp.png\" alt=\"outline_star_outline_black_18dp.png\" /> ";

							$rating_count++;
						}
						echo"($get_rating_total_votes)</a>
					</li>
				</ul>
				<script>
				\$(document).ready(function(){
					\$(\".rating_stars_onclick\").click(function(){
						\$(\"#recipes_write_a_comment_btn\").removeClass(\"btn_default\").addClass(\"btn\");
					});
				});
				</script>
			<!-- //Rating -->

			<!-- Author -->
				<p>
				$l_published_by <a href=\"$root/users/view_profile.php?user_id=$get_recipe_user_id&amp;l=$l\">$get_user_alias</a><br />
				";
				if($get_user_city_name != ""){
					echo"
					<span class=\"grey\">$get_user_city_name";  if($get_user_country_name != ""){ echo", $get_user_country_name"; } echo"</span>
					";
				}
				echo"
				</p>
			<!-- //Author -->

			<!-- Intro -->
				<div class=\"headline_hr\"></div>
				<p class=\"recipe_intro\">
				$get_recipe_introduction
				</p>
			<!-- //Intro -->

		</div>
		<!-- //Headline -->


		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"clear\"></div><div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Info line + Edit/Delete -->
			<div class=\"recipe_info_line\">
				<ul>";
						if($get_recipe_favorite_id == ""){
							echo"
							<li>
							<a href=\"favorite_recipe_add.php?recipe_id=$get_recipe_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/outline_favorite_border_black_18dp.png\" alt=\"outline_favorite_border_black_18dp.png\" />
							$l_favorite</a>
							</li>
							";
						}
						else{
							echo"
							<li>
							<a href=\"favorite_recipe_remove.php?recipe_id=$get_recipe_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/outline_favorite_black_18dp.png\" alt=\"outline_favorite_black_18dp.png\" />
							$l_remove_favorite</a>
							</li>";
						}

					echo"
					<li>
					<a href=\"view_recipe_stats.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"_gfx/icons/eye_dark_grey.png\" alt=\"eye_dark_grey.png\" />
					$get_recipe_unique_hits</a>
					</li>


					";
					if($get_my_user_id == "$get_recipe_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
						echo"
						<li><a href=\"edit_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"_gfx/icons/outline_create_black_18dp.png\" alt=\"outline_create_black_18dp.png\" /> $l_edit</a></li>
						<li><a href=\"delete_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"_gfx/icons/outline_clear_black_18dp.png\" alt=\"outline_clear_black_18dp.png\" /> $l_delete</a></li>
						";
					}

					// Share buttons
					echo"
						<li>
					";
					$query = "SELECT button_id, button_title, button_url, button_code_preload, button_code_plugin, button_image_path, button_image_18x18 FROM $t_webdesign_share_buttons WHERE button_language=$inp_recipe_language_mysql ORDER BY button_id ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_button_id, $get_button_title, $get_button_url, $get_button_code_preload, $get_button_code_plugin, $get_button_image_path, $get_button_image_18x18) = $row;
						$page_url_encoded = urlencode($page_url);
						$page_url_encoded = str_replace("%26amp%3B", "%26", $page_url_encoded);
						$get_button_url = str_replace("%url%", $page_url_encoded, $get_button_url);
						$get_button_url = str_replace("%title%", $get_recipe_title, $get_button_url);
						echo"
						<a href=\"$get_button_url\"><img src=\"$root/$get_button_image_path/$get_button_image_18x18\" alt=\"$get_button_image_18x18\" class=\"share_button\" title=\"$get_button_title\" /></a>
						";
					}
					echo"
						</li>

				</ul>
			</div>
		<!-- //Info line + Edit/Delete -->





		<!-- Ad -->
		";
		include("$root/ad/_includes/ad_main_below_headline.php");
		echo"
		<!-- //Ad -->



		<!-- Ingredients -->
		<div class=\"ingredients\">
			<a id=\"ingredients\"></a>
			

        		<form method=\"get\" action=\"view_recipe.php\" enctype=\"multipart/form-data\">
			<input type=\"hidden\" name=\"recipe_id\" value=\"$get_recipe_id\" />
			<input type=\"hidden\" name=\"servings\" value=\""; if($servings == ""){ $servings = $get_number_servings; } echo"$servings\" />
			<input type=\"hidden\" name=\"l\" value=\"$l\" />
			


			";
			$x = 0;
			$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
			$result_groups = mysqli_query($link, $query_groups);
			while($row_groups = mysqli_fetch_row($result_groups)) {
				list($get_group_id, $get_group_title) = $row_groups;
				echo"

				";
				if($x == 0){
					// Servings
					if($servings == 1){
						$servings_minus = 1;
					}
					else{
						$servings_minus = $servings - 1;
					}
					if($servings == 999){
						$servings_plus = 999;
					}
					else{
						$servings_plus = $servings + 1;
					}
					$query_string = $_SERVER['QUERY_STRING'];
					$query_string = str_replace("recipe_id=$get_recipe_id", "", $query_string);
					$query_string = str_replace("l=$l", "", $query_string);
					$query_string = str_replace("servings=$servings", "", $query_string);
					$query_string = str_replace("&&", "", $query_string);
					$query_string = str_replace("&", "&amp;", $query_string);
					$query_string_len = strlen($query_string);
					if($query_string_len == "0" OR $query_string_len == "1"){
						$query_string = "";
					}
					echo"
					<table style=\"width: 100%;\">
					 <tr>
					  <td class=\"ingredients_headcell\">
						<h2 style=\"padding-bottom:0;margin-bottom:0;\">$get_group_title</h2>
					  </td>
					  <td class=\"ingredients_headcell_desktop\" style=\"text-align: center;\">
						<span>$l_calories</span>
					  </td>
					  <td class=\"ingredients_headcell_desktop\" style=\"text-align: center;\">
						<span>$l_fat</span>
					  </td>
					  <td class=\"ingredients_headcell_desktop\" style=\"text-align: center;\">
						<span>$l_carbs_abbreviation</span>
					  </td>
					  <td class=\"ingredients_headcell_desktop\" style=\"text-align: center;\">
						<span>$l_protein</span>
					  </td>
					  <td class=\"ingredients_headcell_desktop\" style=\"text-align: center;\">
					<span>";
					if($get_recipe_country == "United States"){
						echo"$l_sodium";
					}
					else{
						echo"$l_salt";
					}
					echo"</span>
					  </td>
					 </tr>
					 <tr>
					  <td class=\"ingredients_headcell\" cellspan=\"6\">
						<table>
						 <tr>
						  <td>
							<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;servings=$servings_minus&amp;l=$l$query_string#ingredients\"><img src=\"_gfx/icons/baseline_keyboard_arrow_down_black_18dp.png\" alt=\"baseline_keyboard_arrow_down_black_18dp.png\" /></a>
	 					  </td>
						  <td style=\"text-align:center;padding: 0px 6px 0px 6px;\">
							<p style=\"margin: 0px 0px 0px 0px;padding:0;\">$servings $l_servings_lowercase</p>
						  </td>
						  <td>
							<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;servings=$servings_plus&amp;l=$l$query_string#ingredients\"><img src=\"_gfx/icons/baseline_keyboard_arrow_up_black_18dp.png\" alt=\"baseline_keyboard_arrow_up_black_18dp.png\" /></a>
						  </td>
						 </tr>
						</table>
					  </td>
					 </tr>
					";
				} // servings
				else{
				// Headline
					echo"
					 <tr>
					  <td class=\"ingredients_headcell\" cellspan=\"6\">
						<h2>$get_group_title</h2>
					  </td>
					 </tr>
					
					";	
				}

				$items_calories_total 	= 0;
				$items_fat_total 	= 0;
				$items_carbs_total 	= 0;
				$items_protein_total 	= 0;
				$items_salt_total 	= 0;
				$items_sodium_total 	= 0;

				$items_calories_serving	= 0;
				$items_fat_serving 	= 0;
				$items_carbs_serving	= 0;
				$items_protein_serving	= 0;
				$items_salt_serving 	= 0;
				$items_sodium_serving 	= 0;
	
				$i = 0;
				$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$get_group_id ORDER BY item_weight ASC";
				$result_items = mysqli_query($link, $query_items);
				$row_cnt = mysqli_num_rows($result_items);
				while($row_items = mysqli_fetch_row($result_items)) {
					list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;


					// Style
					if(isset($style) && $style == "bodycell"){
						$style = "subcell";
					}
					else{
						$style = "bodycell";
					}

					// Amount
					if(isset($_GET["amount_$get_item_id"])) {
						$amount = $_GET["amount_$get_item_id"];
						$amount = strip_tags(stripslashes($amount));
						$amount = str_replace(",", ".", $amount);
	
						if(!is_numeric($amount)){
							$amount = ($get_item_amount/$get_number_servings)*$servings;
						}


						// Calories
						$get_item_energy_calculated = round(($get_item_energy_calculated/$get_item_amount)*$amount, 0);
						$get_item_fat_calculated = round(($get_item_fat_calculated/$get_item_amount)*$amount, 0);
						$get_item_carbohydrates_calculated = round(($get_item_carbohydrates_calculated/$get_item_amount)*$amount, 0);
						$get_item_proteins_calculated = round(($get_item_proteins_calculated/$get_item_amount)*$amount, 0);
						$get_item_salt_calculated = round(($get_item_salt_calculated/$get_item_amount)*$amount, 0);
						$get_item_sodium_calculated = round(($get_item_sodium_calculated/$get_item_amount)*$amount, 0);

					}
					else{
						if($servings == $get_number_servings){
							$amount = "$get_item_amount";
						}
						else{
							if($get_number_servings == ""){
								echo"<div class=\"error\"><p>Missing link between recipe and food. Please go to <b>Edit Recipe -&gt; Categorization</b> and  <b>Edit Recipe -&gt; Ingredients</b></p></div>\n";
								$get_number_servings = 1;
							}
							$amount = ($get_item_amount/$get_number_servings)*$servings;
						}
					}

					// Calories
					if($servings == $get_number_servings){
						$calories = "$get_item_energy_calculated";
					}
					else{
						if(isset($_GET["amount_$get_item_id"])) {
							$calories = $get_item_calories_calculated;
						}
						else{
							$calories = round(($get_item_energy_calculated/$get_number_servings)*$servings, 0);
						}
					}

					// Fat
					if($servings == $get_number_servings){
						$fat = "$get_item_fat_calculated";
					}
					else{
						if(isset($_GET["amount_$get_item_id"])) {
							$fat = $get_item_fat_calculated;
						}
						else{
							$fat = round(($get_item_fat_calculated/$get_number_servings)*$servings, 0);
						}
					}

					// Carbs
					if($servings == $get_number_servings){
						$carbs = "$get_item_carbohydrates_calculated";
					}
					else{
						if(isset($_GET["amount_$get_item_id"])) {
							$carbs = $get_item_carbs_calculated;
						}
						else{
							$carbs = round(($get_item_carbohydrates_calculated/$get_number_servings)*$servings, 0);
						}
					}


					// Protein
					if($servings == $get_number_servings){
						$protein = "$get_item_proteins_calculated";
					}
					else{
						if(isset($_GET["amount_$get_item_id"])) {
							$protein = $get_item_proteins_calculated;
						}
						else{
							$protein = round(($get_item_proteins_calculated/$get_number_servings)*$servings, 0);
						}
					}


					// Salt
					if($servings == $get_number_servings){
						$salt = "$get_item_salt_calculated";
					}
					else{
						if(isset($_GET["amount_$get_item_id"])) {
							$salt = $get_item_salt_calculated;
						}
						else{
							$salt = round(($get_item_salt_calculated/$get_number_servings)*$servings, 0);
						}
					}

					// Sodium
					if($servings == $get_number_servings){
						$sodium = "$get_item_sodium_calculated";
					}
					else{
						if(isset($_GET["amount_$get_item_id"])) {
							$sodium = $get_item_sodium_calculated;
						}
						else{
							$sodium = round(($get_item_sodium_calculated/$get_number_servings)*$servings, 0);
						}
					}

					// Total
					$items_calories_total 	= $items_calories_total + $calories;
					$items_fat_total 	= $items_fat_total + $fat;
					$items_carbs_total 	= $items_carbs_total + $carbs;
					$items_protein_total 	= $items_protein_total + $protein;
					$items_salt_total 	= $items_salt_total + $salt;
					$items_sodium_total	= $items_sodium_total + $sodium;
					
					if($get_number_servings == ""){
						echo"<div class=\"error\"><p>Missing servings! Please fix by going to <b>Edit recipe -&gt; Categorization</b></p></div>\n"; 
						$get_number_servings = 1;
					}

					$items_calories_serving	= $items_calories_serving + ($get_item_energy_calculated/$get_number_servings);
					$items_fat_serving 	= $items_fat_serving + ($get_item_fat_calculated/$get_number_servings);
					$items_carbs_serving	= $items_carbs_serving	+ ($get_item_carbohydrates_calculated/$get_number_servings);
					$items_protein_serving	= $items_protein_serving + ($get_item_proteins_calculated/$get_number_servings);
					$items_salt_serving 	= $items_salt_serving + ($get_item_salt_calculated/$get_number_servings);
					$items_sodium_serving 	= $items_sodium_serving + ($get_item_sodium_calculated/$get_number_servings);


					echo"
					 <tr>
					  <td class=\"ingredients_body_$style\">
						

						<span><input type=\"text\" name=\"amount_$get_item_id\" size=\"1\" value=\"$amount\" class=\"ingredients_amount\" />$get_item_measurement</span>
					
						<span>";
						if($get_item_food_id != "" && $get_item_food_id != "0"){
								echo"<a href=\"$root/food/view_food.php?food_id=$get_item_food_id&amp;l=$l\">$get_item_grocery</a>";
						}
						else{
							echo"$get_item_grocery";
						}
						echo"<br /></span>


						<div class=\"mobile_only\">
							<table>
							 <tr>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: center;\">
								<span class=\"grey_small\">$calories</span>
							  </td>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: center;\">
								<span class=\"grey_small\">$fat</span>
							  </td>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: center;\">
								<span class=\"grey_small\">$carbs</span>
							  </td>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: center;\">
								<span class=\"grey_small\">$protein</span>
							  </td>
							 </tr>
							 <tr>
							  <td style=\"padding-right: 10px;text-align: center;\">
								<span class=\"grey_small\">$l_calories</span>
							  </td>
							  <td style=\"padding-right: 10px;text-align: center;\">
								<span class=\"grey_small\">$l_fat</span>
							  </td>
							  <td style=\"padding-right: 10px;text-align: center;\">
								<span class=\"grey_small\">$l_carbs_abbreviation</span>
							  </td>
							  <td style=\"padding-right: 10px;text-align: center;\">
								<span class=\"grey_small\">$l_protein</span>
							  </td>
							 </tr>
							</table>
						</div>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<span>$calories</span>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<span>$fat</span>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<span>$carbs</span>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<span>$protein</span>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<span>";
						if($get_recipe_country == "United States"){
							echo"$sodium";
						}
						else{
							echo"$salt";
						}
						echo"</span>
					  </td>
					 </tr>
					 ";
					$i++;
				}

				// Serving and total
				$items_calories_serving	= round($items_calories_serving, 0);
				$items_fat_serving 	= round($items_fat_serving, 0);
				$items_carbs_serving	= round($items_carbs_serving, 0);
				$items_protein_serving	= round($items_protein_serving, 0);
				$items_salt_serving 	= round($items_salt_serving, 0);
				$items_sodium_serving 	= round($items_sodium_serving, 0);

				$items_calories_total 	= round($items_calories_total, 0);
				$items_fat_total 	= round($items_fat_total, 0);
				$items_carbs_total 	= round($items_carbs_total, 0);
				$items_protein_total 	= round($items_protein_total, 0);
				$items_salt_total 	= round($items_salt_total, 0);
				$items_sodium_total 	= round($items_sodium_total, 0);


				// Style
				if(isset($style) && $style == "bodycell"){
					$style = "subcell";
				}
				else{
					$style = "bodycell";
				}
				echo"
					 <tr>
					  <td class=\"ingredients_body_$style\">
						<span><input type=\"submit\" value=\"$l_update\" class=\"btn_default\" /></span>
						<div style=\"float: right;\" class=\"desktop_only\"><em>$l_serving</em></div>

						<div class=\"mobile_only\" style=\"clear:left;\">
							
							<table>
							 <tr>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: right\">
								<em>$l_serving</em>
							  </td>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: center;\">
								<em>$items_calories_serving</em>
							  </td>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: center;\">
								<em>$items_fat_serving</em>
							  </td>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: center;\">
								<em>$items_carbs_serving</em>
							  </td>
							  <td style=\"padding: 5px 10px 0px 0px;text-align: center;\">
								<em>$items_protein_serving</em>
							  </td>
							 </tr>
							 <tr>
							  <td style=\"padding-right: 10px;text-align: center;\">
								
							  </td>
							  <td style=\"padding-right: 10px;text-align: center;\">
								<span class=\"grey_small\">$l_calories</span>
							  </td>
							  <td style=\"padding-right: 10px;text-align: center;\">
								<span class=\"grey_small\">$l_fat</span>
							  </td>
							  <td style=\"padding-right: 10px;text-align: center;\">
								<span class=\"grey_small\">$l_carbs_abbreviation</span>
							  </td>
							  <td style=\"padding-right: 10px;text-align: center;\">
								<span class=\"grey_small\">$l_protein</span>
							  </td>
							 </tr>
							</table>
						</div> <!-- Mobile only -->


					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<p style=\"padding-top:0;margin-top:0;\"><em>$items_calories_serving</em></p>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<p style=\"padding-top:0;margin-top:0;\"><em>$items_fat_serving</em></p>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<p style=\"padding-top:0;margin-top:0;\"><em>$items_carbs_serving</em></p>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<p style=\"padding-top:0;margin-top:0;\"><em>$items_protein_serving</em></p>
					  </td>
					  <td class=\"ingredients_body_desktop_$style\" style=\"text-align: center;\">
						<p style=\"padding-top:0;margin-top:0;\"><em>";
						if($get_recipe_country == "United States"){
							echo"$items_sodium_serving";
						}
						else{
							echo"$items_salt_serving";
						}
						echo"</em></p>
				
					  </td>
					 </tr>";



				$x++;
				echo"
				";
			} // while groups
		echo"	
				</table>
			</form>
		</div>
		<!-- //Ingredients -->





	
		<!-- Directions -->
		<div class=\"directions\">

			<h2>$l_directions</h2>

			$get_recipe_directions
		</div>

		<div style=\"height:10px;\"></div>
		<hr />
		<!-- //Directions -->


		<!-- Comments -->
		<a id=\"comments\"></a>

		<h2>$l_comments</h2>

		<p>
		<a href=\"view_recipe_write_comment.php?recipe_id=$recipe_id&amp;l=$l\" class=\"btn_default\" id=\"recipes_write_a_comment_btn\">$l_write_a_comment</a>	
		</p>


		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- View comments -->
			";
			$query_groups = "SELECT comment_id, comment_recipe_id, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment FROM $t_recipes_comments WHERE comment_recipe_id=$get_recipe_id ORDER BY comment_id ASC";
			$result_groups = mysqli_query($link, $query_groups);
			while($row_groups = mysqli_fetch_row($result_groups)) {
				list($get_comment_id, $get_comment_recipe_id, $get_comment_language, $get_comment_approved, $get_comment_datetime, $get_comment_time, $get_comment_date_print, $get_comment_user_id, $get_comment_user_alias, $get_comment_user_image_path, $get_comment_user_image_file, $get_comment_user_ip, $get_comment_user_hostname, $get_comment_user_agent, $get_comment_title, $get_comment_text, $get_comment_rating, $get_comment_helpful_clicks, $get_comment_useless_clicks, $get_comment_marked_as_spam, $get_comment_spam_checked, $get_comment_spam_checked_comment) = $row_groups;
		
				echo"
				<a id=\"comment$get_comment_id\"></a>
				<div class=\"clear\" style=\"height:14px;\"></div>

				<div class=\"comment_item\">
					<table style=\"width: 100%;\">
					 <tr>
					  <td style=\"width: 80px;vertical-align:top;\">
						<!-- Image -->
							<p style=\"padding: 10px 0px 10px 0px;margin:0;\">
							<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\">";
							if($get_comment_user_image_file == "" OR !(file_exists("$root/$get_comment_user_image_path/$get_comment_user_image_file"))){ 
								echo"<img src=\"_gfx/avatar_blank_64.png\" alt=\"avatar_blank_64.png\" class=\"comment_avatar\" />";
							} 
							else{ 
								$inp_new_x = 65; // 950
								$inp_new_y = 65; // 640
								$thumb_full_path = "$root/$get_comment_user_image_path/user_" . $get_comment_user_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
								if(!(file_exists("$thumb_full_path"))){
									resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_comment_user_id/$get_comment_user_image_file", "$thumb_full_path");
								}

								echo"	<img src=\"$thumb_full_path\" alt=\"$get_comment_user_image_file\" class=\"comment_view_avatar\" />"; 
							} 
							echo"</a>
							</p>
							<!-- //Image -->
					  </td>
					  <td style=\"vertical-align:top;\">

						<!-- Stars, title and menu -->
						<table style=\"width: 100%;\">
						 <tr>
						  <td>
							<p style=\"margin:0;padding:0;\">
							";
							for($x=0;$x<$get_comment_rating;$x++){
								echo"<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" /> ";
							}
							$off = 5-$get_comment_rating;
							for($x=0;$x<$off;$x++){
								echo"<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" /> ";
							}
							echo"
							<b style=\"padding-left: 10px;\">$get_comment_title</b>
							</p>
						  </td>
						  <td style=\"text-align: right;\">


							<!-- Menu -->
							";
							if(isset($my_user_id)){
								if($get_comment_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
									echo"
									<a href=\"edit_comment.php?comment_id=$get_comment_id&amp;l=$l\"><img src=\"$root/users/_gfx/edit.png\" alt=\"edit.png\" title=\"$l_edit\" /></a>
									<a href=\"delete_comment.php?comment_id=$get_comment_id&amp;l=$l\"><img src=\"$root/users/_gfx/delete.png\" alt=\"delete.png\" title=\"$l_delete\" /></a>
									";
								}
								else{
									echo"
									<a href=\"report_comment.php?comment_id=$get_comment_id&amp;l=$l\"><img src=\"$root/comments/_gfx/report_grey.png\" alt=\"report_grey.png\" title=\"$l_report\" /></a>
									";
								}
							}
							echo"
							<!-- //Menu -->
						  </td>
						 </tr>
						</table>
						<!-- //Stars, title and menu -->


						<!-- Author + date -->
						<p style=\"margin:0;padding:0;\">
						<span class=\"recipes_comment_by\">$l_by</span>
						<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\" class=\"recipes_comment_author\">$get_comment_user_alias</a>
						<a href=\"#comment$get_comment_id\" class=\"recipes_comment_date\">$get_comment_date_print</a></span>
						</p>
						<!-- //Author + date -->

						<!-- Comment -->
							<p style=\"margin-top: 0px;padding-top: 0;\">$get_comment_text</p>
						<!-- Comment -->
					  </td>
					 </tr>
					</table>
				</div>
				";
			}
			echo"
		<!-- //View comments -->

		<div class=\"clear\" style=\"height:10px;\"></div>
		<hr />
		<!-- //Comments -->



		<!-- Back links -->
			<p>
			<a href=\"index.php?l=$l\" class=\"btn_default\">$l_recipes</a>
			<a href=\"categories_browse.php?category_id=$get_recipe_category_id&amp;l=$l#recipe$get_recipe_id\" class=\"btn_default\">$get_category_translation_title</a>\n";
			$query = "SELECT tag_id, tag_title, tag_title_clean FROM $t_recipes_tags WHERE tag_recipe_id=$get_recipe_id ORDER BY tag_id ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_tag_id, $get_tag_title, $get_tag_title_clean) = $row;
				echo"
				<a href=\"view_tag.php?tag=$get_tag_title_clean&amp;l=$l#recipe$get_recipe_id\" class=\"btn_default\">#$get_tag_title</a>
				";
			}
			echo"
			</p>
		<!-- //Back links -->

		";
		// Check main ingredient ID
		if($get_recipe_ingredient_id == ""){
			include("view_recipe_missing_main_ingredient_id.php");
		}
		echo"

		<!-- This months most popular recipes -->
		<h2>$l_this_months_most_popular_recipes</h2>";
		$x = 0;
		$query = "SELECT stats_visit_per_month_id, stats_visit_per_month_recipe_id, stats_visit_per_month_recipe_title, stats_visit_per_month_recipe_image_path, stats_visit_per_month_recipe_thumb_278x156 FROM $t_recipes_stats_views_per_month WHERE stats_visit_per_month_month=$month AND stats_visit_per_month_year=$year AND stats_visit_per_month_recipe_language=$l_mysql LIMIT 0,12";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_stats_visit_per_month_id, $get_stats_visit_per_month_recipe_id, $get_stats_visit_per_month_recipe_title, $get_stats_visit_per_month_recipe_image_path, $get_stats_visit_per_month_recipe_thumb_278x156) = $row;

			if($get_stats_visit_per_month_recipe_thumb_278x156 != "" && file_exists("$root/$get_stats_visit_per_month_recipe_image_path/$get_stats_visit_per_month_recipe_thumb_278x156")){
			
				if($x == "0"){
					echo"
					<div class=\"left_center_center_right_left\">
					";
				}
				elseif($x == "1"){
					echo"
					<div class=\"left_center_center_left_right_center\">
					";
				}
				elseif($x == "2"){
					echo"
					<div class=\"left_center_center_right_right_center\">
					";
				}
				elseif($x == "3"){
					echo"
					<div class=\"left_center_center_right_right\">
					";
				}
				echo"
				
						<p class=\"frontpage_post_image\">
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_stats_visit_per_month_recipe_id&amp;l=$l\"><img src=\"$root/$get_stats_visit_per_month_recipe_image_path/$get_stats_visit_per_month_recipe_thumb_278x156\" alt=\"$get_stats_visit_per_month_recipe_thumb_278x156\" /></a><br />
						</p>
						<p class=\"frontpage_post_title\">
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_stats_visit_per_month_recipe_id&amp;l=$l\" class=\"h3\">$get_stats_visit_per_month_recipe_title</a>
						</p>
					
					
					</div>
				";
			
				// Increment
				if($x == "3"){
					$x = -1;
					echo"
					<div class=\"clear\" style=\"padding-bottom: 10px;\"></div>
					";
				}
				$x = $x+1;
			} // thumb
		} // while
			if($x == "1"){
				echo"
					<div class=\"left_center_center_left_right_center\"></div>
					<div class=\"left_center_center_right_right_center\"></div>
					<div class=\"left_center_center_right_right\"></div>
					<div class=\"clear\"></div>
				";

			}
			elseif($x == "3"){
				echo"
					<div class=\"left_center_center_right_right\"></div>
					<div class=\"clear\"></div>
				";

			}
		echo"
		<!-- //This months most popular recipes -->
		";

	} // can view recipe
} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>