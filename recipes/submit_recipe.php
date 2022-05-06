<?php 
/**
*
* File: recipes/my_recipes.php
* Version 1.0.0
* Date 23:59 27.11.2017
* Copyright (c) 2011-2017 Localhost
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


/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}
if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
}
else{
	$recipe_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_submit_recipe - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	if($mode == "process"){

		$inp_recipe_country = $_POST['inp_recipe_country'];
		$inp_recipe_country = output_html($inp_recipe_country);
		$inp_recipe_country_mysql = quote_smart($link, $inp_recipe_country);


		$inp_recipe_title = $_POST['inp_recipe_title'];
		$inp_recipe_title = output_html($inp_recipe_title);
		$inp_recipe_title_mysql = quote_smart($link, $inp_recipe_title);
		if(empty($inp_recipe_title)){
			$ft = "error";
			$fm = $l_recipe_title_cant_be_empty;
			$mode = "";
		}
		else{
			// Check if we alreaddy have that recipe
			$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_title=$inp_recipe_title_mysql AND recipe_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_recipe_id) = $row;

			if($get_recipe_id != ""){
				$ft = "info";
				$fm = $l_we_already_have_a_recipe_with_that_name;
			
				$mode = "";
			}
		}

		$inp_recipe_introduction = $_POST['inp_recipe_introduction'];
		$inp_recipe_introduction = output_html($inp_recipe_introduction);
		$inp_recipe_introduction_mysql = quote_smart($link, $inp_recipe_introduction);
		if(empty($inp_recipe_introduction)){
			$ft = "error";
			$fm = $l_recipe_introduction_cant_be_empty;
			$mode = "";
		}
		
		$inp_recipe_directions = "<ul>
	<li><p><b>$l_step 1</b><br />
	-</p></li>
	<li><p><b>$l_step 2</b><br />
	-</p></li>
	<li><p><b>$l_step 3</b><br />
	-</p></li>
	<li><p><b>$l_step 4</b><br />
	-</p></li>
</ul>
<p>&nbsp;</p>";
		$inp_recipe_directions_mysql = quote_smart($link, $inp_recipe_directions);

		$inp_recipe_category_id = $_POST['inp_recipe_category_id'];
		$inp_recipe_category_id = output_html($inp_recipe_category_id);
		$inp_recipe_category_id_mysql = quote_smart($link, $inp_recipe_category_id);
		
		$inp_recipe_cusine_id = $_POST['inp_recipe_cusine_id'];
		$inp_recipe_cusine_id = output_html($inp_recipe_cusine_id);
		$inp_recipe_cusine_id_mysql = quote_smart($link, $inp_recipe_cusine_id);

		$inp_recipe_occasion_id = $_POST['inp_recipe_occasion_id'];
		$inp_recipe_occasion_id = output_html($inp_recipe_occasion_id);
		$inp_recipe_occasion_id_mysql = quote_smart($link, $inp_recipe_occasion_id);

		$inp_recipe_season_id = $_POST['inp_recipe_season_id'];
		$inp_recipe_season_id = output_html($inp_recipe_season_id);
		$inp_recipe_season_id_mysql = quote_smart($link, $inp_recipe_season_id);

		$inp_number_servings = $_POST['inp_number_servings'];
		$inp_number_servings = output_html($inp_number_servings);
		$inp_number_servings_mysql = quote_smart($link, $inp_number_servings);
		if(empty($inp_number_servings)){
			$ft = "error";
			$fm = $l_servings_cant_be_empty;
			$mode = "";
		}


		$inp_recipe_user_id = $_SESSION['user_id'];
		$inp_recipe_user_id = output_html($inp_recipe_user_id);
		$inp_recipe_user_id_mysql = quote_smart($link, $inp_recipe_user_id);

		// Dates
		$date = date("Y-m-d");
		$date_saying = date("j M Y");
		$time = date("H:i:s");
		$year = date("Y");
		$month = date("m");
		$month_full = date("F");
		$month_short = date("M");

		$inp_recipe_user_ip = $_SERVER['REMOTE_ADDR'];
		$inp_recipe_user_ip = output_html($inp_recipe_user_ip);
		$inp_recipe_user_ip_mysql = quote_smart($link, $inp_recipe_user_ip);

		$inp_recipe_password = $date . $time . $inp_recipe_user_ip;
		$inp_recipe_password = sha1($inp_recipe_password);
		$inp_recipe_password_mysql = quote_smart($link, $inp_recipe_password);
		
		$inp_recipe_last_viewed = $date . " " . $time;

		
		if(isset($_POST['inp_recipe_language'])){
			$inp_recipe_language = $_POST['inp_recipe_language'];
			$inp_recipe_language = output_html($inp_recipe_language);
			$l = "$inp_recipe_language";
		}
		else{
			$inp_recipe_language = "$l";
		}
		$inp_recipe_language_mysql = quote_smart($link, $inp_recipe_language);

		if(isset($_POST['inp_age_restriction'])){
			$inp_age_restriction = $_POST['inp_age_restriction'];
			$inp_age_restriction = output_html($inp_age_restriction);
		}
		else{
			$inp_age_restriction = "$l";
		}
		$inp_age_restriction_mysql = quote_smart($link, $inp_age_restriction);

		if(isset($_POST['inp_published'])){
			$inp_published = $_POST['inp_published'];
			$inp_published = output_html($inp_published);
		}
		else{
			$inp_published = "0";
		}
		$inp_published_mysql = quote_smart($link, $inp_published);


		if($mode == "process"){
			
			// recipes
			mysqli_query($link, "INSERT INTO $t_recipes
			(recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, 
			recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_date, 
			recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, 
			recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, 
			recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, 
			recipe_published) 
			VALUES 
			(NULL, $inp_recipe_user_id_mysql, $inp_recipe_title_mysql, $inp_recipe_category_id_mysql, $inp_recipe_language_mysql, 
			$inp_recipe_country_mysql, $inp_recipe_introduction_mysql, $inp_recipe_directions_mysql, '', '$date', 
			'$date_saying', '$time', $inp_recipe_cusine_id_mysql, $inp_recipe_season_id_mysql, $inp_recipe_occasion_id_mysql, 
			'', '0', '', 0, 0, 
			$inp_recipe_user_ip_mysql, 'E-mail not sent to administrators', $inp_recipe_password_mysql, '$inp_recipe_last_viewed', $inp_age_restriction_mysql, 
			$inp_published_mysql)")
			or die(mysqli_error($link));

			// Get recipe ID
			$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_user_id=$inp_recipe_user_id_mysql AND recipe_date='$date' AND recipe_time='$time'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_recipe_id) = $row;


			// recipes_groups
			$inp_group_title = "$l_ingredients";
			$inp_group_title = output_html($inp_group_title);
			$inp_group_title_mysql = quote_smart($link, $inp_group_title);

			mysqli_query($link, "INSERT INTO $t_recipes_groups
			(group_id, group_recipe_id, group_title) 
			VALUES 
			(NULL, '$get_recipe_id', $inp_group_title_mysql)")
			or die(mysqli_error($link));

			// recipes_items

			// recipes_numbers
			mysqli_query($link, "INSERT INTO $t_recipes_numbers
			(number_id, number_recipe_id, number_servings) 
			VALUES 
			(NULL, '$get_recipe_id', $inp_number_servings_mysql)")
			or die(mysqli_error($link));

			// recipes_rating
			mysqli_query($link, "INSERT INTO $t_recipes_rating
			(rating_id, rating_recipe_id, rating_recipe_lang, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_votes_plus_average, rating_ip_block) 
			VALUES 
			(NULL, '$get_recipe_id', $inp_recipe_language_mysql, '0', '0', '0', '0', '0', '0', '0', '0', '')")
			or die(mysqli_error($link));


			// Find group ID
			$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;
			

			// Author
			$query = "SELECT user_name, user_alias FROM $t_users WHERE user_id=$inp_recipe_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_user_name, $get_user_alias) = $row;

			// Author Photo
			$q = "SELECT photo_id, photo_user_id, photo_destination, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id=$inp_recipe_user_id_mysql AND photo_profile_image='1'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_photo_id, $get_photo_user_id, $get_photo_destination, $get_photo_thumb_50, $get_photo_thumb_60, $get_photo_thumb_200) = $rowb;
	

			// Make sure I have a view
			$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_user_id=$inp_recipe_user_id_mysql";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
			if($get_current_view_id == ""){
				$year = date("Y");
				mysqli_query($link, "INSERT INTO $t_recipes_user_adapted_view 
				(view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_eight_us) 
				VALUES 
				(NULL, $inp_recipe_user_id_mysql, 0, $year, 'metric', 1, 1, 0)")
				or die(mysqli_error($link));
			}


			echo"
			<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float: left;padding: 2px 4px 0px 0px\" /> $l_submit_recipe</h1>
			
			<meta http-equiv=\"refresh\" content=\"1;url=submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;action=add_items&amp;group_id=$get_group_id&amp;l=$l\">

			<p>
			<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;action=add_items&amp;group_id=$get_group_id&amp;l=$l\" class=\"btn\">$l_continue</a>
			</p>

			";
		}
	}
	if($mode == ""){


		if(isset($_POST['inp_recipe_language'])){
			$inp_recipe_language = $_POST['inp_recipe_language'];
			$inp_recipe_language = output_html($inp_recipe_language);
		}
		else{
			$inp_recipe_language = "$l";
		}
		if(isset($_POST['inp_recipe_title'])){
			$inp_recipe_title = $_POST['inp_recipe_title'];
			$inp_recipe_title = output_html($inp_recipe_title);
		}
		else{
			$inp_recipe_title = "";
		}
		if(isset($_POST['inp_recipe_introduction'])){
			$inp_recipe_introduction = $_POST['inp_recipe_introduction'];
			$inp_recipe_introduction = output_html($inp_recipe_introduction);
		}
		else{
			$inp_recipe_introduction = "";
		}
		if(isset($_POST['inp_recipe_category_id'])){
			$inp_recipe_category_id = $_POST['inp_recipe_category_id'];
			$inp_recipe_category_id = output_html($inp_recipe_category_id);
		}
		else{
			$inp_recipe_category_id = "";
		}
		if(isset($_POST['inp_recipe_cusine_id'])){
			$inp_recipe_cusine_id = $_POST['inp_recipe_cusine_id'];
			$inp_recipe_cusine_id = output_html($inp_recipe_cusine_id);
		}
		else{
			$inp_recipe_cusine_id = "";
		}
		if(isset($_POST['inp_recipe_occasion_id'])){
			$inp_recipe_occasion_id = $_POST['inp_recipe_occasion_id'];
			$inp_recipe_occasion_id = output_html($inp_recipe_occasion_id);
		}
		else{
			$inp_recipe_occasion_id = "";
		}
		if(isset($_POST['inp_recipe_season_id'])){
			$inp_recipe_season_id = $_POST['inp_recipe_season_id'];
			$inp_recipe_season_id = output_html($inp_recipe_season_id);
		}
		else{
			$inp_recipe_season_id = date("n");
		}
		if(isset($_POST['inp_number_servings'])){
			$inp_number_servings = $_POST['inp_number_servings'];
			$inp_number_servings = output_html($inp_number_servings);
		}
		else{
			$inp_number_servings = "1";
		}
		if(isset($_POST['inp_age_restriction'])){
			$inp_age_restriction = $_POST['inp_age_restriction'];
			$inp_age_restriction = output_html($inp_age_restriction);
		}
		else{
			$inp_age_restriction = "0";
		}
		if(isset($_POST['inp_published'])){
			$inp_published = $_POST['inp_published'];
			$inp_published = output_html($inp_published);
		}
		else{
			$inp_published = "0";
		}
	


		echo" 
		<h1>$l_submit_recipe</h1>
	

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
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Form -->

			<form method=\"post\" action=\"submit_recipe.php?l=$l&amp;mode=process\" enctype=\"multipart/form-data\">
	
			<p><b>$l_language</b><br />
			<select name=\"inp_recipe_language\">\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
	
				echo"						";
				echo"<option value=\"$get_language_active_iso_two\""; if($inp_recipe_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>$l_country*:</b><br />\n";

			if(isset($_GET['inp_food_country'])){
				$inp_food_country = $_GET['inp_food_country'];
				$inp_food_country = strip_tags(stripslashes($inp_food_country));
			}
			else{
				$inp_food_country = "";
			}

			// Find the country the last person registrered used
			$inp_user_id = $_SESSION['user_id'];
			$inp_user_id = output_html($inp_user_id);
			$inp_user_id_mysql = quote_smart($link, $inp_user_id);

			$query = "SELECT recipe_country FROM $t_recipes WHERE recipe_user_id=$inp_user_id_mysql ORDER BY recipe_id DESC LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($inp_recipe_country) = $row;

			echo"
			<select name=\"inp_recipe_country\">";
			$query = "SELECT country_name FROM $t_languages_countries ORDER BY country_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_country_name) = $row;
				echo"			";
				echo"<option value=\"$get_country_name\""; if($get_country_name == "$inp_recipe_country"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
				
			}
			echo"
			</select>
			</p>



			<p><b>$l_title</b><br />
			<input type=\"text\" name=\"inp_recipe_title\" value=\"$inp_recipe_title\" size=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>$l_introduction</b><br />
			<textarea name=\"inp_recipe_introduction\" rows=\"3\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$inp_recipe_introduction</textarea>
			</p>


	
			<p><b>$l_category</b><br />
			<select name=\"inp_recipe_category_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
			$query = "SELECT category_id, category_name FROM $t_recipes_categories ORDER BY category_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_name) = $row;

				// Get translation
				$query_translation = "SELECT category_translation_id, category_translation_language, category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$l_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_category_translation_id, $get_category_translation_language, $get_category_translation_title) = $row_translation;
				if($get_category_translation_id == ""){
					$get_category_translation_title = $get_category_name;
				}

				echo"						";
				echo"<option value=\"$get_category_id\""; if($inp_recipe_category_id == $get_category_id){ echo" selected=\"selected\""; } echo">$get_category_translation_title</option>\n";
			}
			echo"
			</select>
			</p>


			<p><b>$l_servings</b><br />
			<input type=\"text\" name=\"inp_number_servings\" size=\"4\" value=\"$inp_number_servings\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>$l_cusine</b><br />
			<select name=\"inp_recipe_cusine_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"0\""; if($inp_recipe_cusine_id == ""){ echo" selected=\"selected\""; } echo">$l_none</option>\n";
			$query = "SELECT cuisine_id, cuisine_name FROM $t_recipes_cuisines ORDER BY cuisine_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
			list($get_cuisine_id, $get_cuisine_name) = $row;

			// Translation
			$query_translation = "SELECT cuisine_translation_id, cuisine_translation_value FROM $t_recipes_cuisines_translations WHERE cuisine_id=$get_cuisine_id AND cuisine_translation_language=$l_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_cuisine_translation_id, $get_cuisine_translation_value) = $row_translation;
			if($get_cuisine_translation_id == ""){
				$get_cuisine_translation_value = $get_cuisine_name;
			}

			echo"						";
			echo"<option value=\"$get_cuisine_id\""; if($inp_recipe_cusine_id == $get_cuisine_id){ echo" selected=\"selected\""; } echo">$get_cuisine_translation_value</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>$l_occasion</b><br />
			<select name=\"inp_recipe_occasion_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"0\""; if($inp_recipe_occasion_id == ""){ echo" selected=\"selected\""; } echo">$l_none</option>\n";
			$query = "SELECT occasion_id, occasion_name FROM $t_recipes_occasions ORDER BY occasion_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
			list($get_occasion_id, $get_occasion_name) = $row;
			echo"						";

			// Translation
			$query_translation = "SELECT occasion_translation_id, occasion_translation_language, occasion_translation_value FROM $t_recipes_occasions_translations WHERE occasion_id=$get_occasion_id AND occasion_translation_language=$l_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_occasion_translation_id, $get_occasion_translation_language, $get_occasion_translation_value) = $row_translation;
			if($get_occasion_translation_id == ""){
				$get_occasion_translation_value = $get_occasion_name;
			}

			echo"<option value=\"$get_occasion_id\""; if($inp_recipe_occasion_id == $get_occasion_id){ echo" selected=\"selected\""; } echo">$get_occasion_translation_value</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>$l_season</b><br />
			<select name=\"inp_recipe_season_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"0\""; if($inp_recipe_season_id == ""){ echo" selected=\"selected\""; } echo">$l_none</option>\n";
			$query = "SELECT season_id, season_name FROM $t_recipes_seasons";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_season_id, $get_season_name) = $row;

				// Translation
				$query_translation = "SELECT season_translation_id, season_translation_language,season_translation_value FROM $t_recipes_seasons_translations WHERE season_id=$get_season_id AND season_translation_language=$l_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_season_translation_id, $get_season_translation_language, $get_season_translation_value) = $row_translation;
				if($get_season_translation_id == ""){
					$get_season_translation_value = $get_season_name;
				}

				echo"						";
				echo"<option value=\"$get_season_id\""; if($inp_recipe_season_id == "$get_season_id"){ echo" selected=\"selected\""; } echo">$get_season_translation_value</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>$l_age_restriction:</b><br />
			<select name=\"inp_age_restriction\">
				<option value=\"0\""; if($inp_age_restriction == "0" OR $inp_age_restriction == ""){ echo" selected=\"selected\""; } echo">$l_no</option>
				<option value=\"1\""; if($inp_age_restriction == "1"){ echo" selected=\"selected\""; } echo">$l_yes</option>
			</select>
			<br />
			<em>$l_example_alcohol</em></p>

			<p><b>$l_published:</b><br />
			<select name=\"inp_published\">
				<option value=\"0\""; if($inp_published == "0" OR $inp_published == ""){ echo" selected=\"selected\""; } echo">$l_draft</option>
				<option value=\"1\""; if($inp_published == "1"){ echo" selected=\"selected\""; } echo">$l_published</option>
			</select></p>

			<p>
			<input type=\"submit\" value=\"$l_continue\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
		<!-- //Form -->
		";
	} // mode == ""
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/recipes/submit_recipe.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>