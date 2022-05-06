<?php 
/**
*
* File: recipes/index_search_for_recipe_autocomplete.php
* Version 1.0
* Date 11:52 31.12.2020
* Copyright (c) 2020 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");


/*- Tables ---------------------------------------------------------------------------- */
$t_recipes 		= $mysqlPrefixSav . "recipes";
$t_recipes_numbers 	= $mysqlPrefixSav . "recipes_numbers";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
	$l = output_html($l);
	$l = substr($l, 0, 2);
	if(!(file_exists("$root/_admin/_translations/site/$l/recipes/ts_search.php"))){
		echo"Error lang";
		die;
	}
}
else{
	$l = "en";
}

// Language
include("$root/_admin/_translations/site/$l/recipes/ts_search.php");
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Query --------------------------------------------------------------------------- */

if(isset($_GET['q']) && $_GET['q'] != ''){
	$q = $_GET['q'];
	$q = strip_tags(stripslashes($q));
	$q = trim($q);
	$q = strtolower($q);
	$q = output_html($q);

	// Check for hacker
	$search_query  = "$q";
	include("$root/_admin/_functions/look_for_hacker_in_string.php");

	$q = "%" . $q . "%";
	$q_mysql = quote_smart($link, $q);


	$l_mysql = quote_smart($link, $l);

	// 1. Search for recipe
	$search_results_count  = 0;
	$x = 0;
	$query_r = "SELECT recipe_id, recipe_title, recipe_introduction, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156, recipe_unique_hits FROM $t_recipes WHERE $t_recipes.recipe_title LIKE $q_mysql AND $t_recipes.recipe_language=$l_mysql";
	$result_r = mysqli_query($link, $query_r);
	while($row_r = mysqli_fetch_row($result_r)) {
		list($get_recipe_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_unique_hits) = $row_r;

		if(file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a") && $get_recipe_image_h_a != ""){
		

			// Get rating
			$query_rating = "SELECT rating_id, rating_average FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
			$result_rating = mysqli_query($link, $query_rating);
			$row_rating = mysqli_fetch_row($result_rating);
			list($get_rating_id, $get_rating_average) = $row_rating;

			// Get numbers
			$query_rating = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id='$get_recipe_id'";
			$result_rating = mysqli_query($link, $query_rating);
			$row_rating = mysqli_fetch_row($result_rating);
			list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_rating;

			
			// Thumb
			if($get_recipe_thumb_h_a_278x156 == "" OR !(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156"))){
				$inp_new_x = 278; // from HD 1920x1080
				$inp_new_y = 156;

				$ext = get_extension($get_recipe_image_h_a);


				$thumb = $get_recipe_id . "_thumb_" . $inp_new_x . "x" . $inp_new_y . ".png";
				$thumb_mysql = quote_smart($link, $thumb);
				resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$get_recipe_image_path/$thumb");
				echo"<div class=\"info\"><p>Creating recipe thumb $inp_new_x x $inp_new_y  px</p></div>";
				mysqli_query($link, "UPDATE $t_recipes SET recipe_thumb_h_a_278x156=$thumb_mysql WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));
			}





			if($x == 0){
				echo"
				<div class=\"clear\"></div>
				<div class=\"left_center_center_right_left\">
				";
			}
			elseif($x == 1){
				echo"
				<div class=\"left_center_center_left_right_center\">
				";
			}
			elseif($x == 2){
				echo"
				<div class=\"left_center_center_right_right_center\">
				";
			}
			elseif($x == 3){
				echo"
				<div class=\"left_center_center_right_right\">
				";
			}
		
			echo"
					<!-- skriver ut bilder per oppskrift -->
					<p class=\"recipe_open_category_img_p\">
					<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_thumb_h_a_278x156\" /></a><br />
					</p>
					<p class=\"recipe_open_category_p\">
					<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"recipe_open_category_a\">$get_recipe_title</a>
					</p>


					<div class=\"recipe_open_category_unique_hits\">
						<img src=\"_gfx/icons/ic_eye_grey_18px.png\" alt=\"eye.png\" style=\"float:left;padding-right: 5px;\" /> 
						<span class=\"recipe_open_category_unique_hits_span\">
						$get_recipe_unique_hits 
						</span>
					</div>



				</div>
			";

			// Increment
			$x++;
		
			// Reset
			if($x == 4){
				$x = 0;
			}

			$search_results_count++;
		} // get_recipe_image
	}


	if($x == 1){
		echo"
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_right\">
			</div>
		";
	
	}
	elseif($x == 2){
		echo"
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_right\">
			</div>
		";

	}
	elseif($x == 3){
		echo"
			<div class=\"left_center_center_right_right\">
			</div>
		";

	}

	// Search into database
	$my_ip = $_SERVER['REMOTE_ADDR'];
	$my_ip = output_html($my_ip);
	$my_ip_mysql = quote_smart($link, $my_ip);

	$datetime = date("Y-m-d H:i:s");
	$datetime_saying = date("j M Y");

	$search_query = str_replace("%", "", $q);
	$search_query_mysql = quote_smart($link, $search_query);
	$query = "SELECT search_id, search_unique_count, search_unique_ip_block FROM $t_recipes_searches WHERE search_query=$search_query_mysql AND search_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_search_id, $get_search_unique_count, $get_search_unique_ip_block) = $row;

	if($get_search_id == ""){
		mysqli_query($link, "INSERT INTO $t_recipes_searches 
		(search_id, search_query, search_language, search_unique_count, search_unique_ip_block, search_first_datetime, search_first_saying, search_last_datetime, search_last_saying, search_found_recipes) 
		VALUES 
		(NULL, $search_query_mysql, $l_mysql, 1, $my_ip_mysql, '$datetime', '$datetime_saying', '$datetime', '$datetime_saying', $search_results_count)")
		or die(mysqli_error($link));
	}
	else{
		$unique_ip_block_array = explode("\n", $get_search_unique_ip_block);
		$size = sizeof($unique_ip_block_array);
		if($size > 10){
			$size = 1;
		}
		
		$count_me = 1;

		for($x=0;$x<$size;$x++){
			$ip = $unique_ip_block_array[$x];
			if($ip == "$my_ip"){
				$count_me = 0;
				break;
			}
		}
		
		if($count_me == "1"){
			$inp_search_unique_count = $get_search_unique_count+1;
			$inp_search_unique_ip_block = $get_search_unique_ip_block . "\n" . $my_ip;
		}
		else{
			$inp_search_unique_count = $get_search_unique_count;
			$inp_search_unique_ip_block = $get_search_unique_ip_block . "\n" . $my_ip;
		}
		$inp_search_unique_ip_block_mysql = quote_smart($link, $inp_search_unique_ip_block);


		$result = mysqli_query($link, "UPDATE $t_recipes_searches SET search_unique_count=$inp_search_unique_count, search_unique_ip_block=$inp_search_unique_ip_block_mysql, search_last_datetime='$datetime', search_last_saying='$datetime_saying', search_found_recipes=$search_results_count WHERE search_id=$get_search_id") or die(mysqli_error($link));

	}

	if($search_results_count == 0){
		echo"
		<p>$l_no_recipes_found</p>
		";

		// Send email to moderator
		$q = str_replace("%", "", $q);
		$q_encrypted = md5("$q");
		$q_antispam_file = "$root/_cache/recipe_search_no_results_" . $q_encrypted . ".txt";
		
		if(!(file_exists("$q_antispam_file"))){
			
			$fh = fopen($q_antispam_file, "w") or die("can not open file");
			fwrite($fh, "$q");
			fclose($fh);
			
		
			// Who is moderator of the week?
			$week = date("W");
			$year = date("Y");
	
			$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
			if($get_moderator_user_id == ""){
				// Create moderator of the week
				include("$root/_admin/_functions/create_moderator_of_the_week.php");
					
				$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
			}




			// Mail from
			$search_link = $configSiteURLSav . "/recipes/search.php?q=$q&amp;l=$l";
			$subject = "No search result for $q at $configWebsiteTitleSav";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
			$message = $message . "<p><b>Summary:</b><br />A user has searched for <em>$q</em> and got no search results at $configWebsiteTitleSav for lanugage $l.\n";
			$message = $message . "Please consider to add a recipe for that query.</p>\n\n";

			$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Search information:</b></p>\n";
			$message = $message . "<table>\n";
			$message = $message . " <tr><td><span>Query:</span></td><td><span>$q</span></td></tr>\n";
			$message = $message . " <tr><td><span>Link:</span></td><td><span><a href=\"$search_link\">$search_link</a></span></td></tr>\n";
			$message = $message . "</table>\n";

			$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav</p>";
			$message = $message. "</body>\n";
			$message = $message. "</html>\n";


			$encoding = "utf-8";

			// Preferences for Subject field
			$subject_preferences = array(
			       "input-charset" => $encoding,
			       "output-charset" => $encoding,
			       "line-length" => 76,
			       "line-break-chars" => "\r\n"
			);
			$header = "Content-type: text/html; charset=".$encoding." \r\n";
			$header .= "From: ".$configFromNameSav." <".$configFromEmailSav."> \r\n";
			$header .= "MIME-Version: 1.0 \r\n";
			$header .= "Content-Transfer-Encoding: 8bit \r\n";
			$header .= "Date: ".date("r (T)")." \r\n";
			$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

			mail($get_moderator_user_email, $subject, $message, $header);

			echo"<p>Our moderator $get_moderator_user_name will look at this query and maybe add a recipe for it later.</p>";
		}
	}
}
else{
	echo"Missing q";
}

?>