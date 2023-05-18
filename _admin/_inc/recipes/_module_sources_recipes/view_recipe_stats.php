<?php 
/**
*
* File: recipes/view_recipe_stats.php
* Version 4.0
* Date 02:09 02.04.2022
* Copyright (c) 2022 Localhost
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



/*- Tables ----------------------------------------------------------------------------- */
include("_tables.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}



/*- Get recipe ------------------------------------------------------------------------- */
// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction) = $row;

/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$get_recipe_title $l_stats_headline - $l_recipes";
}

if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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
	// Category
	$query = "SELECT category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_category_translation_title) = $row;


	echo"
	<h1>$get_recipe_title $l_stats_headline</h1>

	<!-- Charts javascript -->
		<script src=\"$root/_admin/_javascripts/amcharts/index.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts/xy.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts/themes/Animated.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts/percent.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts/map.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts/geodata/worldLow.js\"></script>
	<!-- //Charts javascript -->

	<!-- You are here -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_recipes</a>
		&gt;
		<a href=\"categories_browse.php?category_id=$get_recipe_category_id\">$get_category_translation_title</a>
		&gt;
		<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\">$get_recipe_title</a>
		&gt;
		<a href=\"view_recipe_stats.php?recipe_id=$get_recipe_id&amp;l=$l\">$l_stats</a>
		</p>
	<!-- //You are here -->


	<!-- Visits -->
		<a id=\"visits\"></a>
		<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_unique_visits_per_month</h2>

		<div id=\"chartdiv_visits_per_month\" style=\"width: 100%;height: 400px;\"></div>
		";
		$rand = rand(0,100);
		$cache_file = "recipe_visits_per_month_$get_recipe_id.js";
		include("view_recipe_stats_visits_per_month.php");
		echo"
		<script src=\"../_cache/recipes/stats/$cache_file?rand=$rand\"></script>



	<!-- //Visits per month -->

	<!-- Favorited -->
		<a id=\"favorited\"></a>
		<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_favorited</h2>

		<div id=\"chartdiv_favorited_per_month\" style=\"width: 100%;height: 400px;\"></div>
		";
		$cache_file = "recipe_favorited_per_month_$get_recipe_id.js";
		include("view_recipe_stats_favorited_per_month.php");
		echo"
		<script src=\"../_cache/recipes/stats/$cache_file?rand=$rand\"></script>


	<!-- //Favorited -->



	";
} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>