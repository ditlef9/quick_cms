<?php
/**
*
* File: _admin/_inc/recipes/edit_recipe_categorization.php
* Version 1.0.0
* Date 11:43 12.11.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables --------------------------------------------------------------------------- */
include("_inc/recipes/_tables.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index = $mysqlPrefixSav . "search_engine_index";

/*- Functions --------------------------------------------------------------------------- */
include("_functions/get_extension.php");


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}
/*- Translations --------------------------------------------------------------------- */
	include("_translations/admin/$l/recipes/t_view_recipe.php");

// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Back</a>
	</p>
	";
}
else{
	// Get number of servings
	$query = "SELECT number_servings, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_number_servings, $get_number_total_calories, $get_number_total_proteins, $get_number_total_fat, $get_number_total_carbs) = $row;

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
			
					
			$inp_number_serving_calories = round($get_number_total_calories/$inp_number_servings);
			$inp_number_serving_proteins = round($get_number_total_proteins/$inp_number_servings);
			$inp_number_serving_fat      = round($get_number_total_fat/$inp_number_servings);
			$inp_number_serving_carbs    = round($get_number_total_carbs/$inp_number_servings);
	
			$inp_number_serving_calories_mysql = quote_smart($link, $inp_number_serving_calories);
			$inp_number_serving_proteins_mysql = quote_smart($link, $inp_number_serving_proteins);
			$inp_number_serving_fat_mysql      = quote_smart($link, $inp_number_serving_fat);
			$inp_number_serving_carbs_mysql    = quote_smart($link, $inp_number_serving_carbs);



			// Update
			$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET number_servings=$inp_number_servings_mysql, number_serving_calories=$inp_number_serving_calories_mysql, number_serving_proteins=$inp_number_serving_proteins_mysql, number_serving_fat=$inp_number_serving_fat_mysql, number_serving_carbs=$inp_number_serving_carbs_mysql WHERE number_recipe_id=$recipe_id_mysql");
			



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



		$inp_recipe_user_id = $_POST['inp_recipe_user_id'];
		$inp_recipe_user_id = output_html($inp_recipe_user_id);
		$inp_recipe_user_id_mysql = quote_smart($link, $inp_recipe_user_id);


		if(isset($_POST['inp_published'])){
			$inp_published = $_POST['inp_published'];
			$inp_published = output_html($inp_published);
		}
		else{
			$inp_published = "0";
		}
		$inp_published_mysql = quote_smart($link, $inp_published);

		$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_user_id=$inp_recipe_user_id_mysql, recipe_country=$inp_recipe_country_mysql, recipe_language=$inp_recipe_language_mysql, recipe_age_restriction=$inp_age_restriction_mysql, recipe_published=$inp_published_mysql WHERE recipe_id=$recipe_id_mysql");
		
		
		// Search engine
		include("edit_recipe_include_update_search_engine.php");


		// Header
		$url = "index.php?open=$open&page=$page&recipe_id=$recipe_id&editor_language=$editor_language&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	echo"
	<!-- Headline -->
		<div class=\"recipes_headline\">
			<h1>$get_recipe_title</h1>
		</div>
		<div class=\"recipes_buttons\">
			<p>
			<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"btn_default\">View</a>
			</p>
		</div>
		<div class=\"clear\"></div>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Categorization</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">General</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Ingredients</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">Categorization</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
				<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
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



	<!-- Edit Categorization -->

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_recipe_country\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
	
		<p><b>Country</b><br />
		<select name=\"inp_recipe_country\">";
		$query = "SELECT language_flag FROM $t_languages ORDER BY language_flag ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_flag) = $row;

			$country = str_replace("_", " ", $get_language_flag);
			$country = ucwords($country);
			if($country != "$prev_country"){
				echo"			";
				echo"<option value=\"$country\""; if($get_recipe_country == "$country"){ echo" selected=\"selected\""; } echo">$country</option>\n";
			}
			$prev_country = "$country";
		}
		echo"
		</select>
		</p>

		<p><b>Servings</b><br />
		<select name=\"inp_number_servings\">
			<option value=\"\""; if($get_number_servings == ""){ echo" selected=\"selected\""; } echo">None</option>\n";
			for($x=1;$x<21;$x++){
				echo"						";
				echo"<option value=\"$x\""; if($get_number_servings == $x){ echo" selected=\"selected\""; } echo">$x</option>\n";
			}
			echo"
		</select>
		</p>

		<p><b>Category</b><br />
		<select name=\"inp_recipe_category_id\">\n";
			$recipe_language_mysql = quote_smart($link, $get_recipe_language);
			$query = "SELECT category_id, category_translation_value FROM $t_recipes_categories_translations WHERE category_translation_language=$recipe_language_mysql ORDER BY category_translation_value ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_translation_value) = $row;
				echo"						";
				echo"<option value=\"$get_category_id\""; if($get_recipe_category_id == $get_category_id){ echo" selected=\"selected\""; } echo">$get_category_translation_value</option>\n";
			}
			echo"
		</select>
		</p>

		<p><b>Cusine</b><br />
		<select name=\"inp_recipe_cusine_id\">
			<option value=\"\""; if($get_recipe_cusine_id == ""){ echo" selected=\"selected\""; } echo">None</option>\n";
			$query = "SELECT cuisine_id, cuisine_translation_value FROM $t_recipes_cuisines_translations WHERE cuisine_translation_language=$recipe_language_mysql ORDER BY cuisine_translation_value ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_cuisine_id, $get_cuisine_translation_value) = $row;
				echo"						";
				echo"<option value=\"$get_cuisine_id\""; if($get_recipe_cusine_id == $get_cuisine_id){ echo" selected=\"selected\""; } echo">$get_cuisine_translation_value</option>\n";
			}
			echo"
		</select>
		</p>

		<p><b>Occasion</b><br />
		<select name=\"inp_recipe_occasion_id\">
			<option value=\"\""; if($get_recipe_occasion_id == ""){ echo" selected=\"selected\""; } echo">None</option>\n";
			$query = "SELECT occasion_id, occasion_translation_value FROM $t_recipes_occasions_translations WHERE occasion_translation_language=$recipe_language_mysql ORDER BY occasion_translation_value ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_occasion_id, $get_occasion_translation_value) = $row;
				echo"						";
				echo"<option value=\"$get_occasion_id\""; if($get_recipe_occasion_id == $get_occasion_id){ echo" selected=\"selected\""; } echo">$get_occasion_translation_value</option>\n";
			}
		echo"
		</select>
		</p>

		<p><b>Season</b><br />
		<select name=\"inp_recipe_season_id\">
			<option value=\"\""; if($get_recipe_season_id == ""){ echo" selected=\"selected\""; } echo">None</option>\n";
			$query = "SELECT season_id, season_translation_value FROM $t_recipes_seasons_translations WHERE season_translation_language=$recipe_language_mysql ORDER BY season_translation_value ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_season_id, $get_season_translation_value) = $row;
				echo"						";
				echo"<option value=\"$get_season_id\""; if($get_recipe_season_id == $get_season_id){ echo" selected=\"selected\""; } echo">$get_season_translation_value</option>\n";
			}
		echo"
		</select>
		</p>

		<p><b>Language</b><br />
		<select name=\"inp_recipe_language\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
	
			$flag_path 	= "$root/_webdesign/images/footer/flag_$get_language_active_flag" . "_16x16.png";

			echo"						";
			echo"<option value=\"$get_language_active_iso_two\""; if($get_recipe_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p><b>Age restriction:</b><br />
		<select name=\"inp_age_restriction\">
			<option value=\"0\""; if($get_recipe_age_restriction == "0"){ echo" selected=\"selected\""; } echo">No</option>
			<option value=\"1\""; if($get_recipe_age_restriction == "1"){ echo" selected=\"selected\""; } echo">Yes</option>
		</select>
		<br /><em>Example alcohol</em></p>

		<p><b>Author</b><br />
		<select name=\"inp_recipe_user_id\">\n";
		$query = "SELECT user_id, user_name FROM $t_users ORDER BY user_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_user_id, $get_user_name) = $row;
	

			echo"						";
			echo"<option value=\"$get_user_id\""; if($get_recipe_user_id == "$get_user_id"){ echo" selected=\"selected\""; } echo">$get_user_name</option>\n";
		}
		echo"
		</select>
		</p>
		<p><b>Published:</b><br />
		<select name=\"inp_published\">
			<option value=\"0\""; if($get_recipe_published == "0"){ echo" selected=\"selected\""; } echo">Draft</option>
			<option value=\"1\""; if($get_recipe_published == "1"){ echo" selected=\"selected\""; } echo">Published</option>
		</select></p>


		<p><b>User IP</b><br />
		$get_recipe_user_ip</p>

		<p>
		<input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>


	<!-- //Edit Categorization -->

	";
} // recipe found
?>