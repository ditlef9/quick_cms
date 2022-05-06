<?php 
/**
*
* File: recipes/comment_recipe_edit.php
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
if(isset($_GET['recipe_comment_id'])){
	$recipe_comment_id = $_GET['recipe_comment_id'];
	$recipe_comment_id = output_html($recipe_comment_id);
}
else{
	$recipe_comment_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_delete_comment - $l_recipes";
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

	// Get my profile image
	$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
	$r = mysqli_query($link, $q);
	$rowb = mysqli_fetch_row($r);
	list($get_my_photo_id, $get_my_photo_destination) = $rowb;

	// Get comment
	$recipe_comment_id_mysql = quote_smart($link, $recipe_comment_id);
	$query = "SELECT recipe_comment_id, recipe_comment_recipe_id, recipe_comment_user_id, recipe_comment_user_alias, recipe_comment_user_photo, recipe_comment_user_ip, recipe_comment_text, recipe_comment_likes, recipe_comment_datetime, recipe_comment_reported, recipe_comment_reported_checked, recipe_comment_reported_reason FROM $t_recipes_comments WHERE recipe_comment_id=$recipe_comment_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_comment_id, $get_recipe_comment_recipe_id, $get_recipe_comment_user_id, $get_recipe_comment_user_alias, $get_recipe_comment_user_photo, $get_recipe_comment_user_ip, $get_recipe_comment_text, $get_recipe_comment_likes, $get_recipe_comment_datetime, $get_recipe_comment_reported, $get_recipe_comment_reported_checked, $get_recipe_comment_reported_reason) = $row;

	if($get_recipe_comment_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Comment not found.
		</p>
		";
	}
	else{
		// Get recipe
		$recipe_id_mysql = quote_smart($link, $get_recipe_comment_recipe_id);


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
			if($get_recipe_comment_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
			if($process == 1){
			

				// Update
				$result = mysqli_query($link, "DELETE FROM $t_recipes_comments WHERE recipe_comment_id=$get_recipe_comment_id");


			 
				// Header
				$ft = "success";
				$fm = "comment_deleted";
				$url = "view_recipe.php?recipe_id=$get_recipe_id&l=$l&comment_ft=$ft&comment_fm=$fm#comments";
				header("Location: $url");
				exit;
			} // process


			echo"
			<h1>$l_delete_comment</h1>


			<div style=\"float: left;padding-right: 10px;\">
				<p>
				";
				if($get_my_photo_id != ""){
					echo"
					<img src=\"$root/image.php?width=648&amp;height=64&amp;cropratio=1:1&amp;image=/_uploads/users/images/$my_user_id/$get_my_photo_destination\" alt=\"$get_my_photo_destination\" />
					";
				}
				else{
					echo"<img src=\"$root/recipes/_gfx/avatar_blank_64.png\" alt=\"avatar_blank_64.png\" />";
				}
				echo"
				</p>
			</div>
			<div style=\"float: left\">
				<p>$get_recipe_comment_text
				</p>
			</div>

				<div class=\"clear\"></div>
				<p>
				$l_are_you_sure_you_want_to_delete
				$l_this_action_cant_be_undone
				</p>

				<p style=\"margin-top: 20px;\">
				<a href=\"comment_recipe_delete.php?recipe_comment_id=$get_recipe_comment_id&amp;l=$l&amp;process=1\" class=\"btn\">$l_delete</a>
				<a href=\"view_recipe.php?recipe_id=$get_recipe_comment_recipe_id&amp;l=$l\" class=\"btn bt_default\">$l_cancel</a>
				</p>
				";	
			}
			else{
				echo"
				<h1>Access denined</h1>

				<p>Only the owner, admin or moderator can edit the comment.</p>

				<p>
				<a href=\"view_recipe.php?recipe_id=$get_recipe_comment_recipe_id&amp;l=$l\" class=\"btn bt_default\">$l_back</a>
				</p>
				
				";
			}
		} // recipe found
	} // comment not found
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