<?php
/**
*
* File: _admin/_inc/recipes/view_recipe.php
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


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}

// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;

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
	// Select Nutrients
	$query = "SELECT number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_carbs, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_carbs, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs, number_servings FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_number_id, $get_number_recipe_id, $get_number_hundred_calories, $get_number_hundred_proteins, $get_number_hundred_fat, $get_number_hundred_carbs, $get_number_serving_calories, $get_number_serving_proteins, $get_number_serving_fat, $get_number_serving_carbs, $get_number_total_weight, $get_number_total_calories, $get_number_total_proteins, $get_number_total_fat, $get_number_total_carbs, $get_number_servings) = $row;


	// Check integritet
	if($get_recipe_date == ""){
		$get_recipe_date = date("Y-m-d");
		$get_recipe_time = date("H:i");
		$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_date='$get_recipe_date', recipe_time='$get_recipe_time' WHERE recipe_id=$recipe_id_mysql");
	}

	// Author
	$query = "SELECT user_alias FROM $t_users WHERE user_id=$get_recipe_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_alias) = $row;

	// Date
	$recipe_year = substr($get_recipe_date, 0, 4);
	$recipe_month = substr($get_recipe_date, 5, 2);
	$recipe_day = substr($get_recipe_date, 8, 2);
	
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
		<a href=\"index.php?open=$open&amp;page=view_recipe&amp;recipe_id=$get_recipe_id&amp;editor_language=$editor_language\">$get_recipe_title</a>
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
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
				<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
			</ul>
		</div><p>&nbsp;</p>
	<!-- //Menu -->


	<iframe src=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" width=\"1200\" height=\"800\" onload=\"this.width=screen.width;this.height=screen.height;\"></iframe>





	";
} // recipe found
?>