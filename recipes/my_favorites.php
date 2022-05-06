<?php 
/**
*
* File: recipes/my_favorites.php
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


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_favorites - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
}
else{
	$action = "noshow";
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/recipes/my_favorites.php\">
	";
}

if($action == ""){

	echo"
	<h1>$l_my_favorites</h1>

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		elseif($fm == "recipe_deleted"){
			$fm = "$l_recipe_deleted";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- List all recipes -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_recipe</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_comment</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>


	";

	// Select recipes
	$x = 0;
	$user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $user_id);
	$query = "SELECT recipe_favorite_id, recipe_favorite_recipe_id, recipe_favorite_comment FROM $t_recipes_favorites WHERE recipe_favorite_user_id=$my_user_id_mysql ORDER BY recipe_favorite_id DESC";
	// echo $query;
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_recipe_favorite_id, $get_recipe_favorite_recipe_id, $get_recipe_favorite_comment) = $row;

		// Get recipe

		$query_recipe = "SELECT recipe_id, recipe_title, recipe_introduction, recipe_image_path, recipe_image, recipe_date, recipe_unique_hits FROM $t_recipes WHERE recipe_id=$get_recipe_favorite_recipe_id";
		$result_recipe = mysqli_query($link, $query_recipe);
		$row_recipe = mysqli_fetch_row($result_recipe);
		list($get_recipe_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image, $get_recipe_date, $get_recipe_unique_hits) = $row_recipe;


		// Get rating
		$query_rating = "SELECT rating_id, rating_average, rating_votes_plus_average FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
		$result_rating = mysqli_query($link, $query_rating);
		$row_rating = mysqli_fetch_row($result_rating);
		list($get_rating_id, $get_rating_average, $get_rating_votes_plus_average) = $row_rating;

	
		/*
		$inp_new_x = 110;
		$inp_new_y = 78;
		$thumb = $get_recipe_id . "-" . $inp_new_x . "x" . $inp_new_y . "png";
		if(!(file_exists("$root/_cache/$thumb"))){
			create_thumb("$root/$get_recipe_image_path/$get_recipe_image", "$root/_cache/$thumb", $inp_new_x, $inp_new_y);
		}
		*/

		// Style
		if(isset($style) && $style == ""){
			$style = "odd";
		}
		else{
			$style = "";
		}

		// Title
		$check = strlen($get_recipe_title);
		if($check > 33){
			$get_recipe_title = substr($get_recipe_title, 0, 30);
			$get_recipe_title = $get_recipe_title . "...";
		}

		// Intro
		$check = strlen($get_recipe_introduction);
		if($check > 33){
			$get_recipe_introduction = substr($get_recipe_introduction, 0, 30);
			$get_recipe_introduction = $get_recipe_introduction . "...";
		}



		echo"
		<tr>
		  <td class=\"$style\">
			 <table>
			  <tr>
			   <td style=\"padding-right: 10px;\">
				";
				if($get_recipe_image != ""){
					echo"<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id\"><img src=\"$root/image.php?width=100&amp;height=71&amp;image=/$get_recipe_image_path/$get_recipe_image\" alt=\"$get_recipe_image\" /></a>";
				}
				echo"
			   </td>
			   <td>
				<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id\" class=\"recipe_open_category_a\">$get_recipe_title</a><br />
				$get_recipe_introduction
				</p>
			   </td>
			  </tr>
			 </table>
			
		  </td>
		  <td class=\"$style\">
			<span>$get_recipe_favorite_comment</span>
		  </td>
		  <td class=\"$style\">
			<span>
			<a href=\"favorite_recipe_edit_comment.php?recipe_id=$get_recipe_id&amp;referer=my_favorites&amp;l=$l\">$l_comment</a>
			&middot;
			<a href=\"favorite_recipe_remove.php?recipe_id=$get_recipe_id&amp;referer=my_favorites&amp;l=$l&amp;process=1\">$l_remove</a>
			</span>
		 </td>
		</tr>
		";


	}

		echo"
		 </tbody>
		</table>
	";

}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>