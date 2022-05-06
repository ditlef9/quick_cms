<?php
/**
*
* File: _admin/_inc/recipes/edit_recipe.php
* Version 1.0.0
* Date 00:18 06.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("_functions/resize_crop_image.php");

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
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;

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



	echo"
	<h1>$l_edit</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
		&gt;
		<a href=\"index.php?open=recipes&amp;page=edit_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language&amp;l=$l\">$get_recipe_title</a>
		</p>
	<!-- //Where am I? -->

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">$l_general</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">$l_ingredients</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">$l_image</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">$l_video</a></li>
				<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">$l_delete</a>
			</ul>
		</div><p>&nbsp;</p>
	<!-- //Menu -->



	";
} // recipe found
?>