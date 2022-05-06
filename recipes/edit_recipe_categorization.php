<?php 
/**
*
* File: recipes/edit_recipe_c.php
* Version 1.0.0
* Date 13:43 18.11.2017
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


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}

$l_mysql = quote_smart($link, $l);



/*- Get recipe ------------------------------------------------------------------------- */
// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_ingredient_id, recipe_ingredient_title, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_ingredient_id, $get_recipe_ingredient_title, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

// Translations
include("$root/_admin/_translations/site/$l/recipes/ts_edit_recipe.php");

/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$l_edit_recipe $get_recipe_title - $l_my_recipes";
}
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php\">Back</a>
	</p>
	";
}
else{
	if(isset($_SESSION['user_id'])){
		// Get my user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

		// Access to recipe edit
		if($get_recipe_user_id == "$my_user_id" OR $get_user_rank == "admin"){

	// Get number of servings
	$query = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row;

	if($process == 1){
		

		$inp_recipe_country = $_POST['inp_recipe_country'];
		$inp_recipe_country = output_html($inp_recipe_country);
		$inp_recipe_country_mysql = quote_smart($link, $inp_recipe_country);


		// Servings
		$inp_number_servings = $_POST['inp_number_servings'];
		$inp_number_servings = output_html($inp_number_servings);
		$inp_number_servings_mysql = quote_smart($link, $inp_number_servings);

		if($inp_number_servings != "$get_number_servings"){
			// Update the rest of the numbers
			
			$inp_number_energy_serving	 = round($get_number_energy_total/$inp_number_servings);
			$inp_number_energy_serving_mysql = quote_smart($link, $inp_number_energy_serving);

			$inp_number_fat_serving	 = round($get_number_fat_total/$inp_number_servings);
			$inp_number_fat_serving_mysql = quote_smart($link, $inp_number_fat_serving);

			$inp_number_saturated_fat_serving	 = round($get_number_saturated_fat_total/$inp_number_servings);
			$inp_number_saturated_fat_serving_mysql = quote_smart($link, $inp_number_saturated_fat_serving);

			$inp_number_monounsaturated_fat_serving	 = round($get_number_monounsaturated_fat_total/$inp_number_servings);
			$inp_number_monounsaturated_fat_serving_mysql = quote_smart($link, $inp_number_monounsaturated_fat_serving);

			$inp_number_polyunsaturated_fat_serving	 = round($get_number_polyunsaturated_fat_total/$inp_number_servings);
			$inp_number_polyunsaturated_fat_serving_mysql = quote_smart($link, $inp_number_polyunsaturated_fat_serving);

			$inp_number_cholesterol_serving	 = round($get_number_cholesterol_total/$inp_number_servings);
			$inp_number_cholesterol_serving_mysql = quote_smart($link, $inp_number_cholesterol_serving);

			$inp_number_carbohydrates_serving	 = round($get_number_carbohydrates_total/$inp_number_servings);
			$inp_number_carbohydrates_serving_mysql = quote_smart($link, $inp_number_carbohydrates_serving);

			$inp_number_carbohydrates_of_which_sugars_serving	 = round($get_number_carbohydrates_of_which_sugars_total/$inp_number_servings);
			$inp_number_carbohydrates_of_which_sugars_serving_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_serving);

			$inp_number_dietary_fiber_serving	 = round($get_number_dietary_fiber_total/$inp_number_servings);
			$inp_number_dietary_fiber_serving_mysql = quote_smart($link, $inp_number_dietary_fiber_serving);

			$inp_number_proteins_serving	 = round($get_number_proteins_total/$inp_number_servings);
			$inp_number_proteins_serving_mysql = quote_smart($link, $inp_number_proteins_serving);

			$inp_number_salt_serving	 = round($get_number_salt_total/$inp_number_servings);
			$inp_number_salt_serving_mysql = quote_smart($link, $inp_number_salt_serving);

			$inp_number_sodium_serving	 = round($get_number_sodium_total/$inp_number_servings);
			$inp_number_sodium_serving_mysql = quote_smart($link, $inp_number_sodium_serving);





			// Update
			$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET 
							number_servings=$inp_number_servings_mysql,
							number_energy_serving=$inp_number_energy_serving_mysql, 
							number_fat_serving=$inp_number_fat_serving_mysql, 
							number_saturated_fat_serving=$inp_number_saturated_fat_serving_mysql, 
							number_monounsaturated_fat_serving=$inp_number_monounsaturated_fat_serving_mysql, 
							number_polyunsaturated_fat_serving=$inp_number_polyunsaturated_fat_serving_mysql, 
							number_cholesterol_serving=$inp_number_cholesterol_serving_mysql, 
							number_carbohydrates_serving=$inp_number_carbohydrates_serving_mysql, 
							number_carbohydrates_of_which_sugars_serving=$inp_number_carbohydrates_of_which_sugars_serving_mysql, 
							number_dietary_fiber_serving=$inp_number_dietary_fiber_serving_mysql, 
							number_proteins_serving=$inp_number_proteins_serving_mysql, 
							number_salt_serving=$inp_number_salt_serving_mysql
							 WHERE number_recipe_id=$recipe_id_mysql")  or die(mysqli_error($link));
			



		}



		$inp_recipe_category_id = $_POST['inp_recipe_category_id'];
		$inp_recipe_category_id = output_html($inp_recipe_category_id);
		$inp_recipe_category_id_mysql = quote_smart($link, $inp_recipe_category_id);
		$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_category_id=$inp_recipe_category_id_mysql WHERE recipe_id=$recipe_id_mysql");

		$inp_recipe_cusine_id = $_POST['inp_recipe_cusine_id'];
		if($inp_recipe_cusine_id != ""){
			$inp_recipe_cusine_id = output_html($inp_recipe_cusine_id);
			$inp_recipe_cusine_id_mysql = quote_smart($link, $inp_recipe_cusine_id);
			$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_cusine_id=$inp_recipe_cusine_id_mysql WHERE recipe_id=$recipe_id_mysql");
		}

		$inp_recipe_occasion_id = $_POST['inp_recipe_occasion_id'];
		if($inp_recipe_occasion_id != ""){
			$inp_recipe_occasion_id = output_html($inp_recipe_occasion_id);
			$inp_recipe_occasion_id_mysql = quote_smart($link, $inp_recipe_occasion_id);
			$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_occasion_id=$inp_recipe_occasion_id_mysql WHERE recipe_id=$recipe_id_mysql");
		}

		$inp_recipe_season_id = $_POST['inp_recipe_season_id'];
		if($inp_recipe_season_id != ""){
			$inp_recipe_season_id = output_html($inp_recipe_season_id);
			$inp_recipe_season_id_mysql = quote_smart($link, $inp_recipe_season_id);
			$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_season_id=$inp_recipe_season_id_mysql WHERE recipe_id=$recipe_id_mysql");
		}

		if(isset($_POST['inp_recipe_marked_as_spam'])){
			$inp_recipe_marked_as_spam = $_POST['inp_recipe_marked_as_spam'];
			if($inp_recipe_marked_as_spam == "on"){
				$inp_recipe_marked_as_spam = 1;
			}
		}
		else{
			$inp_recipe_marked_as_spam = 0;
		}
		


		if(isset($_POST['inp_recipe_language'])){
			$inp_recipe_language = $_POST['inp_recipe_language'];
			$inp_recipe_language = output_html($inp_recipe_language);
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


		$inp_main_ingredient_id = $_POST['inp_main_ingredient_id'];
		$inp_main_ingredient_id = output_html($inp_main_ingredient_id);
		$inp_main_ingredient_id_mysql = quote_smart($link, $inp_main_ingredient_id);

		$query = "SELECT ingredient_id, ingredient_title FROM $t_recipes_main_ingredients WHERE ingredient_id=$inp_main_ingredient_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_ingredient_id, $get_ingredient_title) = $row;
	
		if($get_ingredient_id == ""){
			$url = "edit_recipe_categorization.php?recipe_id=$get_recipe_id&l=$l&ft=error&fm=not_found";
			header("Location: $url");
			exit;
		}
			
		// Get translation
		$recipe_language_mysql = quote_smart($link, $get_recipe_language);
		$query_translation = "SELECT translation_id, translation_value FROM $t_recipes_main_ingredients_translations WHERE translation_ingredient_id=$get_ingredient_id AND translation_language=$recipe_language_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_translation_id, $get_translation_value) = $row_translation;
				
		// Inputs
		$inp_ingredient_id_mysql = quote_smart($link, $get_ingredient_id);
		$inp_ingredient_title_mysql = quote_smart($link, $get_translation_value);
		

		if(isset($_POST['inp_published'])){
			$inp_published = $_POST['inp_published'];
			$inp_published = output_html($inp_published);
		}
		else{
			$inp_published = "0";
		}
		$inp_published_mysql = quote_smart($link, $inp_published);


		$result = mysqli_query($link, "UPDATE $t_recipes SET 
							recipe_country=$inp_recipe_country_mysql, 
							recipe_language=$inp_recipe_language_mysql, 
							recipe_ingredient_id=$inp_ingredient_id_mysql, 
							recipe_ingredient_title=$inp_ingredient_title_mysql, 
							recipe_age_restriction=$inp_age_restriction_mysql, 
							recipe_published=$inp_published_mysql WHERE recipe_id=$recipe_id_mysql");
		
		


		// Search engine
		include("edit_recipe_include_update_search_engine.php");



		// Header
		$url = "edit_recipe_categorization.php?recipe_id=$recipe_id&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}



	echo"
	<h1>$get_recipe_title</h1>

	
	<!-- You are here -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_recipes</a>
			&gt;
			<a href=\"my_recipes.php?l=$l#recipe_id=$recipe_id\">$l_my_recipes</a>
			&gt;
			<a href=\"view_recipe.php?recipe_id=$recipe_id&amp;l=$l\">$get_recipe_title</a>
			&gt;
			<a href=\"edit_recipe_categorization.php?recipe_id=$recipe_id&amp;l=$l\">$l_categorization</a>
			</p>
	<!-- //You are here -->

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"edit_recipe.php?recipe_id=$recipe_id&amp;l=$l\">$l_general</a></li>
				<li><a href=\"edit_recipe_ingredients.php?recipe_id=$recipe_id&amp;l=$l\">$l_ingredients</a></li>
				<li><a href=\"edit_recipe_categorization.php?recipe_id=$recipe_id&amp;l=$l\" class=\"active\">$l_categorization</a></li>
				<li><a href=\"edit_recipe_image.php?recipe_id=$recipe_id&amp;l=$l\">$l_image</a></li>
				<li><a href=\"edit_recipe_video.php?recipe_id=$recipe_id&amp;l=$l\">$l_video</a></li>
				<li><a href=\"edit_recipe_tags.php?recipe_id=$recipe_id&amp;l=$l\">$l_tags</a></li>
				<li><a href=\"edit_recipe_links.php?recipe_id=$recipe_id&amp;l=$l\">$l_links</a></li>
			</ul>
		</div><p>&nbsp;</p>
	<!-- //Menu -->


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



	<!-- Form -->
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_recipe_country\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"edit_recipe_categorization.php?recipe_id=$recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

		<p><b>$l_country</b><br />
		<select name=\"inp_recipe_country\">";
		$query = "SELECT country_id, country_name FROM $t_languages_countries ORDER BY country_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_country_id, $get_country_name) = $row;
			echo"			";
			echo"<option value=\"$get_country_name\""; if($get_recipe_country == "$get_country_name"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
			
		}
		echo"
		</select>
		</p>

		<p><b>$l_servings</b><br />
		<input type=\"text\" name=\"inp_number_servings\" size=\"4\" value=\"$get_number_servings\" /> 
		</p>

		<p><b>$l_category</b><br />
		<select name=\"inp_recipe_category_id\">\n";
			$query = "SELECT category_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_translation_language=$l_mysql ORDER BY category_translation_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_translation_title) = $row;
				echo"						";
				echo"<option value=\"$get_category_id\""; if($get_recipe_category_id == $get_category_id){ echo" selected=\"selected\""; } echo">$get_category_translation_title</option>\n";
			}
			echo"
		</select>
		</p>

		<p><b>$l_cusine</b><br />
		<select name=\"inp_recipe_cusine_id\">
			<option value=\"\""; if($get_recipe_cusine_id == ""){ echo" selected=\"selected\""; } echo">$l_none</option>\n";
			$query = "SELECT cuisine_id, cuisine_translation_value FROM $t_recipes_cuisines_translations WHERE cuisine_translation_language=$l_mysql ORDER BY cuisine_translation_value ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_cuisine_id, $get_cuisine_translation_value) = $row;
				echo"						";
				echo"<option value=\"$get_cuisine_id\""; if($get_recipe_cusine_id == $get_cuisine_id){ echo" selected=\"selected\""; } echo">$get_cuisine_translation_value</option>\n";
			}
			echo"
		</select>
		</p>

		<p><b>$l_occasion</b><br />
		<select name=\"inp_recipe_occasion_id\">
			<option value=\"\""; if($get_recipe_occasion_id == ""){ echo" selected=\"selected\""; } echo">$l_none</option>\n";
			$query = "SELECT occasion_id, occasion_translation_value FROM $t_recipes_occasions_translations WHERE occasion_translation_language=$l_mysql ORDER BY occasion_translation_value ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_occasion_id, $get_occasion_translation_value) = $row;
				echo"						";
				echo"<option value=\"$get_occasion_id\""; if($get_recipe_occasion_id == $get_occasion_id){ echo" selected=\"selected\""; } echo">$get_occasion_translation_value</option>\n";
			}
		echo"
		</select>
		</p>

		<p><b>$l_season</b><br />
		<select name=\"inp_recipe_season_id\">
			<option value=\"\""; if($get_recipe_season_id == ""){ echo" selected=\"selected\""; } echo">$l_none</option>\n";
			$query = "SELECT season_id, season_translation_value FROM $t_recipes_seasons_translations WHERE season_translation_language=$l_mysql ORDER BY season_translation_value ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_season_id, $get_season_translation_value) = $row;
				echo"						";
				echo"<option value=\"$get_season_id\""; if($get_recipe_season_id == $get_season_id){ echo" selected=\"selected\""; } echo">$get_season_translation_value</option>\n";
			}
		echo"
		</select>
		</p>

		<p><b>$l_language</b><br />
		<select name=\"inp_recipe_language\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
	

			echo"						";
			echo"<option value=\"$get_language_active_iso_two\""; if($get_recipe_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p><b>$l_age_restriction:</b><br />
		<select name=\"inp_age_restriction\">
			<option value=\"0\""; if($get_recipe_age_restriction == "0"){ echo" selected=\"selected\""; } echo">$l_no</option>
			<option value=\"1\""; if($get_recipe_age_restriction == "1"){ echo" selected=\"selected\""; } echo">$l_yes</option>
		</select>
		<br />
		<em>$l_example_alcohol</em></p>

		<p><b>$l_main_ingredient</b><br />
		<select name=\"inp_main_ingredient_id\">\n";
		$recipe_language_mysql = quote_smart($link, $get_recipe_language);
		$query = "SELECT translation_id, translation_ingredient_id, translation_language, translation_value FROM $t_recipes_main_ingredients_translations WHERE translation_language=$recipe_language_mysql ORDER BY translation_value ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_translation_id, $get_translation_ingredient_id, $get_translation_language, $get_translation_value) = $row;

			$query_m = "SELECT ingredient_id, ingredient_icon_path, ingredient_icon_18x18_inactive, ingredient_icon_18x18_active, ingredient_category_id FROM $t_recipes_main_ingredients WHERE ingredient_id=$get_translation_ingredient_id";
			$result_m = mysqli_query($link, $query_m);
			$row_m = mysqli_fetch_row($result_m);
			list($get_ingredient_id, $get_ingredient_icon_path, $get_ingredient_icon_18x18_inactive, $get_ingredient_icon_18x18_active, $get_ingredient_category_id) = $row_m;
			
			if($get_ingredient_category_id == "$get_recipe_category_id"){
				echo"						";
				echo"<option value=\"$get_translation_ingredient_id\""; if($get_translation_ingredient_id == "$get_recipe_ingredient_id"){ echo" selected=\"selected\""; } echo">$get_translation_value</option>\n";
			}
		}
		echo"
		</select>
		</p>

		<p><b>$l_published:</b><br />
		<select name=\"inp_published\">
			<option value=\"0\""; if($get_recipe_published == "0"){ echo" selected=\"selected\""; } echo">$l_draft</option>
			<option value=\"1\""; if($get_recipe_published == "1"){ echo" selected=\"selected\""; } echo">$l_published</option>
		</select></p>



		<p>
		<input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>

	<!-- //Form -->


	<!-- Buttons -->
		<p style=\"margin-top: 20px;\">
		<a href=\"my_recipes.php?l=$l#recipe$recipe_id\" class=\"btn btn_default\">$l_my_recipes</a>
		<a href=\"view_recipe.php?recipe_id=$recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_view_recipe</a>

		</p>
	<!-- //Buttons -->
	";
		} // is owner or admin
		else{
			echo"<p>Server error 403</p>
			<p>Only the owner and admin can edit the recipe</p>
			";
		}
	} // Isset user id
	else{
		echo"
		<h1>Log in</h1>
		<p><a href=\"$root/users/login.php?l=$l\">Please log in</a>
		</p>
		";
	}
} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>