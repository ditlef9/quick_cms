<?php 
/**
*
* File: recipes/favorite_food_add.php
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
include("_tables_food.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['food_id'])){
	$food_id = $_GET['food_id'];
	$food_id = output_html($food_id);
}
else{
	$food_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);
// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$get_current_title_value";
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

	// Get food
	$food_id_mysql = quote_smart($link, $food_id);
	$query = "SELECT food_id, food_user_id, food_name, food_main_category_id, food_sub_category_id FROM $t_food_index WHERE food_id=$food_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_food_id, $get_food_user_id, $get_food_name, $get_food_main_category_id, $get_food_sub_category_id) = $row;

	if($get_food_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Food not found.
		</p>
		";
	}
	else{
		// Check if I alreaddy have it
		$q = "SELECT food_favorite_id FROM $t_food_favorites WHERE food_favorite_food_id=$get_food_id AND food_favorite_user_id=$my_user_id_mysql";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_food_favorite_id) = $rowb;
		if($get_food_favorite_id == ""){

			// Header
			$ft = "info";
			$fm = "food_favorite_not_found";
			$url = "view_food.php?main_category_id=$get_food_main_category_id&sub_category_id=$get_food_sub_category_id&food_id=$get_food_id&l=$l&ft=success&fm=$fm#info_and_rating";
			header("Location: $url");
			exit;

			
		}
		else{
			$result = mysqli_query($link, "DELETE FROM $t_food_favorites WHERE food_favorite_id=$get_food_favorite_id");
			
			$ft = "success";
			$fm = "food_favorite_removed";
			$url = "view_food.php?main_category_id=$get_food_main_category_id&sub_category_id=$get_food_sub_category_id&food_id=$get_food_id&l=$l&ft=success&fm=$fm#info_and_rating";
			header("Location: $url");
			exit;
		}



	} // food found
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