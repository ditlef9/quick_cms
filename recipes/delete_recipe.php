<?php 
/**
*
* File: recipes/delete_recipe.php
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


/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");
/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index = $mysqlPrefixSav . "search_engine_index";


/*- Get recipe ------------------------------------------------------------------------- */
// Select
$user_id = $_SESSION['user_id'];
$recipe_user_id_mysql = quote_smart($link, $user_id);
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$recipe_id_mysql AND recipe_user_id=$recipe_user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;



/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$l_delete_recipe $get_recipe_title - $l_my_recipes";
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
	if($process == 1){
		// Delete
		$result = mysqli_query($link, "DELETE FROM $t_recipes WHERE recipe_id=$recipe_id_mysql");
		$result = mysqli_query($link, "DELETE FROM $t_recipes_groups WHERE group_recipe_id=$recipe_id_mysql");
		$result = mysqli_query($link, "DELETE FROM $t_recipes_items WHERE item_recipe_id=$recipe_id_mysql");
		$result = mysqli_query($link, "DELETE FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql");
		$result = mysqli_query($link, "DELETE FROM $t_recipes_rating WHERE rating_recipe_id=$recipe_id_mysql");
		$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='recipes' AND index_reference_name='recipe_id' AND index_reference_id=$recipe_id_mysql");
		
		// Image
		if($get_recipe_image != "" && file_exists("../$get_recipe_image_path/$get_recipe_image")){
			unlink("../$get_recipe_image_path/$get_recipe_image");
		}
		// Thumb
		if($get_recipe_thumb_278x156 != "" && file_exists("../$get_recipe_image_path/$get_recipe_thumb_278x156")){
			unlink("../$get_recipe_image_path/$get_recipe_thumb_278x156");
		}
			



		// Header
		$url = "my_recipes.php?l=$l&ft=success&fm=recipe_deleted";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>$get_recipe_title</h1>

	<p>
	$l_are_you_sure_you_want_to_delete_the_recipe 
	$l_this_action_cant_be_undone 
	</p>

	<p>
	<a href=\"delete_recipe.php?recipe_id=$recipe_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_delete</a>
	
	<a href=\"my_recipes.php?l=$l#recipe$get_recipe_id\" class=\"btn btn_default\">$l_cancel</a>
	</p>

	";
} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>