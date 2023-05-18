<?php 
/**
*
* File: recipes/favorite_recipe_edit_comment.php
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


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
}
else{
	$recipe_id = "";
}
if(isset($_GET['referer'])){
	$referer = $_GET['referer'];
	$referer = output_html($referer);
}
else{
	$referer = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_favorite_comment - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;

	// Get recipe
	$recipe_id_mysql = quote_smart($link, $recipe_id);
	$query = "SELECT recipe_id, recipe_user_id, recipe_title FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_user_id, $get_recipe_title) = $row;

	if($get_recipe_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Recipe not found.
		</p>
		";
	}
	else{
		// Check if I alreaddy have it
		$q = "SELECT recipe_favorite_id, recipe_favorite_comment FROM $t_recipes_favorites WHERE recipe_favorite_recipe_id=$get_recipe_id AND recipe_favorite_user_id=$my_user_id_mysql";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_recipe_favorite_id, $get_recipe_favorite_comment) = $rowb;
		if($get_recipe_favorite_id != ""){
		
			if($process == "1"){
				$inp_recipe_favorite_comment = $_POST['inp_recipe_favorite_comment'];
				$inp_recipe_favorite_comment = output_html($inp_recipe_favorite_comment);
				$inp_recipe_favorite_comment_mysql = quote_smart($link, $inp_recipe_favorite_comment);

				// Update
				$result = mysqli_query($link, "UPDATE $t_recipes_favorites SET recipe_favorite_comment=$inp_recipe_favorite_comment_mysql WHERE recipe_favorite_id=$get_recipe_favorite_id");
		
				// Header
				$ft = "success";
				$fm = "recipe_favorite_removed";

				$url = "my_favorites.php?l=$l&ft=success&fm=$fm";
				header("Location: $url");
				exit;
			}

			echo"
			<h1>$l_edit_favorite_comment</h1>

			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_recipe_favorite_comment\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"favorite_recipe_edit_comment.php?recipe_id=$recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<p>
			<textarea name=\"inp_recipe_favorite_comment\" rows=\"5\" cols=\"45\">"; $get_recipe_favorite_comment = str_replace("<br />", "\n", $get_recipe_favorite_comment); echo"$get_recipe_favorite_comment</textarea>
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
			<a href=\"my_favorites.php?l=$l\" class=\"btn btn_default\">$l_back</a>
			</p>

			</form>
			
			";
		}


	} // recipe found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>