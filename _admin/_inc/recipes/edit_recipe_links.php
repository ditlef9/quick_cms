<?php
/**
*
* File: _admin/_inc/recipes/edit_recipe_links.php
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
$t_recipes 	 	= $mysqlPrefixSav . "recipes";
$t_recipes_ingredients	= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_groups	= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items	= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers	= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating	= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines	= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_seasons	= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_occasions	= $mysqlPrefixSav . "recipes_occasions";
$t_recipes_categories	= $mysqlPrefixSav . "recipes_categories";

$t_recipes_tags		= $mysqlPrefixSav . "recipes_tags";
$t_recipes_links	= $mysqlPrefixSav . "recipes_links";


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
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction) = $row;

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
		// Delete all old links
		$result = mysqli_query($link, "DELETE FROM $t_recipes_links WHERE link_recipe_id=$get_recipe_id");
				
		// Lang
		$inp_language_mysql = quote_smart($link, $get_recipe_language);

		$inp_title_a = $_POST['inp_title_a'];
		$inp_title_a = output_html($inp_title_a);
		$inp_title_a_mysql = quote_smart($link, $inp_title_a);

		$inp_url_a = $_POST['inp_url_a'];
		$inp_url_a = output_html($inp_url_a);
		$size = strlen($inp_url_a);
		if($size > 3){
			$check = substr($inp_url_a, 0, 3);
			if($check != "htt"){
				$inp_url_a = "http://" . $inp_url_a;
			}
		}
		$inp_url_a_mysql = quote_smart($link, $inp_url_a);

		if($inp_title_a != "" && $inp_url_a != ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_recipes_links 
			(link_id, link_language, link_recipe_id, link_title, link_url, link_unique_click, link_unique_click_ipblock, link_user_id) 
			VALUES 
			(NULL, $inp_language_mysql, $get_recipe_id, $inp_title_a_mysql, $inp_url_a_mysql, '0', '', $my_user_id_mysql)")
			or die(mysqli_error($link));
		}


		$inp_title_b = $_POST['inp_title_b'];
		$inp_title_b = output_html($inp_title_b);
		$inp_title_b_mysql = quote_smart($link, $inp_title_b);

		$inp_url_b = $_POST['inp_url_b'];
		$inp_url_b = output_html($inp_url_b);
		$size = strlen($inp_url_b);
		if($size > 3){
			$check = substr($inp_url_b, 0, 3);
			if($check != "htt"){
				$inp_url_b = "http://" . $inp_url_b;
			}
		}
		$inp_url_b_mysql = quote_smart($link, $inp_url_b);

		if($inp_title_b != "" && $inp_url_b != ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_recipes_links 
			(link_id, link_language, link_recipe_id, link_title, link_url, link_unique_click, link_unique_click_ipblock, link_user_id) 
			VALUES 
			(NULL, $inp_language_mysql, $get_recipe_id, $inp_title_b_mysql, $inp_url_b_mysql, '0', '', $my_user_id_mysql)")
			or die(mysqli_error($link));
		}

		$inp_title_c = $_POST['inp_title_c'];
		$inp_title_c = output_html($inp_title_c);
		$inp_title_c_mysql = quote_smart($link, $inp_title_c);

		$inp_url_c = $_POST['inp_url_c'];
		$inp_url_c = output_html($inp_url_c);
		$size = strlen($inp_url_c);
		if($size > 3){
			$check = substr($inp_url_c, 0, 3);
			if($check != "htt"){
				$inp_url_c = "http://" . $inp_url_c;
			}
		}
		$inp_url_c_mysql = quote_smart($link, $inp_url_c);

		if($inp_title_c != "" && $inp_url_c != ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_recipes_links 
			(link_id, link_language, link_recipe_id, link_title, link_url, link_unique_click, link_unique_click_ipblock, link_user_id) 
			VALUES 
			(NULL, $inp_language_mysql, $get_recipe_id, $inp_title_c_mysql, $inp_url_c_mysql, '0', '', $my_user_id_mysql)")
			or die(mysqli_error($link));
		}



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
		<a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Links</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">General</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Ingredients</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Categorization</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">Links</a></li>
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

	<!-- Form -->
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_link_a\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
	



		";
				// Fetch links
				$y = 1;
				$query = "SELECT link_id, link_title, link_url FROM $t_recipes_links WHERE link_recipe_id=$get_recipe_id ORDER BY link_id ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_link_id, $get_link_title, $get_link_url) = $row;
				
					if($y == "1"){
						$letter = "_a";
					}
					elseif($y == "2"){
						$letter = "_b";
					}
					elseif($y == "3"){
						$letter = "_c";
					}
					echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Link $y</b></p>
					<p>Title:<br />
					<input type=\"text\" name=\"inp_title$letter\" value=\"$get_link_title\" size=\"20\" />
					</p>
					<p>URL:<br />
					<input type=\"text\" name=\"inp_url$letter\" value=\"$get_link_url\" size=\"20\" /></p>
					";
					$y++;
				}
				
				
				if($y == 1){
					echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Link 1</b></p>
					<p>Title:<br />
					<input type=\"text\" name=\"inp_title_a\" value=\"\" size=\"20\" /></p>
					<p>URL:<br />
					<input type=\"text\" name=\"inp_url_a\" value=\"\" size=\"20\" /></p>

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Link 2</b></p>
					<p>Title:<br />
					<input type=\"text\" name=\"inp_title_b\" value=\"\" size=\"20\" /></p>
					<p>URL:<br />
					<input type=\"text\" name=\"inp_url_b\" value=\"\" size=\"20\" /></p>

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Link 3</b></p>
					<p>Title:<br />
					<input type=\"text\" name=\"inp_title_c\" value=\"\" size=\"20\" /></p>
					<p>URL:<br />
					<input type=\"text\" name=\"inp_url_c\" value=\"\" size=\"20\" /></p>
					";

				}
				elseif($y == 2){
					echo"

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Link 2</b></p>
					<p>Title:<br />
					<input type=\"text\" name=\"inp_title_b\" value=\"\" size=\"20\" /></p>
					<p>URL:<br />
					<input type=\"text\" name=\"inp_url_b\" value=\"\" size=\"20\" /></p>

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Link 3</b></p>
					<p>Title:<br />
					<input type=\"text\" name=\"inp_title_c\" value=\"\" size=\"20\" /></p>
					<p>URL:<br />
					<input type=\"text\" name=\"inp_url_c\" value=\"\" size=\"20\" /></p>
					";

				}
				elseif($y == 3){
					echo"

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Link 3</b></p>
					<p>Title:<br />
					<input type=\"text\" name=\"inp_title_c\" value=\"\" size=\"20\" /></p>
					<p>URL:<br />
					<input type=\"text\" name=\"inp_url_c\" value=\"\" size=\"20\" /></p>
					";

				}
				echo"

	

		<p>
		<input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
			
		</form>

	<!-- //Form -->




	";
} // recipe found
?>