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


/*- Translations ---------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/recipes/ts_view_recipe.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_comment - $l_recipes";
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
	$query = "SELECT recipe_comment_id, recipe_comment_recipe_id, recipe_comment_user_id, recipe_comment_user_alias, recipe_comment_user_photo, recipe_comment_user_ip, recipe_comment_text, recipe_comment_stars, recipe_comment_likes, recipe_comment_datetime, recipe_comment_reported, recipe_comment_reported_checked, recipe_comment_reported_reason FROM $t_recipes_comments WHERE recipe_comment_id=$recipe_comment_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_comment_id, $get_recipe_comment_recipe_id, $get_recipe_comment_user_id, $get_recipe_comment_user_alias, $get_recipe_comment_user_photo, $get_recipe_comment_user_ip, $get_recipe_comment_text, $get_recipe_comment_stars, $get_recipe_comment_likes, $get_recipe_comment_datetime, $get_recipe_comment_reported, $get_recipe_comment_reported_checked, $get_recipe_comment_reported_reason) = $row;

	if($get_recipe_comment_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Comment not found.
		</p>
		";
	}
	else{
		if($get_recipe_comment_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){


		

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
				if($process == 1){
			
				$inp_recipe_comment_text = $_POST['inp_recipe_comment_text'];
				$inp_recipe_comment_text = output_html($inp_recipe_comment_text);
				$inp_recipe_comment_text_mysql = quote_smart($link, $inp_recipe_comment_text);
				if(empty($inp_recipe_comment_text)){
					$ft = "error";
					$fm = "no_comment_added";
					$url = "comment_recipe_edit.php?recipe_comment_id=$recipe_comment_id&l=$l&comment_ft=$ft&comment_fm=$fm";
					header("Location: $url");
					exit;
				}

				$inp_recipe_comment_user_alias_mysql = quote_smart($link, $get_my_user_alias);

				if($get_my_photo_destination != ""){
					$inp_recipe_comment_user_photo_mysql = quote_smart($link, $get_my_photo_destination);
				}
				else{
					$inp_recipe_comment_user_photo_mysql = "''";
				}

				$inp_recipe_comment_user_ip = $_SERVER['REMOTE_ADDR'];
				$inp_recipe_comment_user_ip = output_html($inp_recipe_comment_user_ip);
				$inp_recipe_comment_user_ip_mysql = quote_smart($link, $inp_recipe_comment_user_ip);

				$inp_recipe_comment_edited_datetime = date("Y-m-d H:i:s");

				// Update
				$result = mysqli_query($link, "UPDATE $t_recipes_comments SET recipe_comment_user_alias=$inp_recipe_comment_user_alias_mysql, recipe_comment_user_photo=$inp_recipe_comment_user_photo_mysql, recipe_comment_user_ip=$inp_recipe_comment_user_ip_mysql, recipe_comment_text=$inp_recipe_comment_text_mysql, recipe_comment_edited_datetime='$inp_recipe_comment_edited_datetime' WHERE recipe_comment_id=$get_recipe_comment_id");


			 
				// Header
				$ft = "success";
				$fm = "changes_saved";
				$url = "view_recipe.php?recipe_id=$get_recipe_id&l=$l&comment_ft=$ft&comment_fm=$fm#comments";
				header("Location: $url");
				exit;
				} // process


				echo"
				<h1>$l_edit_comment</h1>


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
				<script>
				$(document).ready(function() {
					\$('#inp_recipe_comment_text').focus(function () {
					    \$(this).animate({rows: 6,cols: 50}, 500);
					});
				});
				</script>
			
				<form method=\"post\" action=\"comment_recipe_edit.php?recipe_comment_id=$recipe_comment_id&amp;l=$l&amp;process=1\" />
					<p>$l_rate_this_recipe:<br />";


					$rating_count = 1;
					$stars = 0;
					for($x=0;$x<$get_recipe_comment_stars;$x++){
						$stars = $x+1;
						echo"			";
						echo"<a href=\"#\"><img src=\"$root/_webdesign/images/recipes/star_on.png\" alt=\"$stars\" title=\"$stars\" /></a>\n ";
			
						$rating_count++;
					}

				
					$rest = 5-$get_recipe_comment_stars;
					$rating_count = $get_recipe_comment_stars+1;
					for($x=0;$x<$rest;$x++){
						$stars = $stars+1;
						echo"			";
						echo"<a href=\"#\"><img src=\"$root/_webdesign/images/recipes/star_off.png\" alt=\"$stars\" title=\"$stars\" /></a>\n";

						$rating_count++;
					}
					echo"
					</p>

					<p>
					$l_add_your_comment:<br />
					<textarea name=\"inp_recipe_comment_text\" rows=\"2\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" id=\"inp_recipe_comment_text\">"; $get_recipe_comment_text = str_replace("<br />", "\n", $get_recipe_comment_text); echo"$get_recipe_comment_text</textarea><br />
					<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"margin-top: 4px;\" />
					</p>
					</form>
				</div>

				<!-- Back -->
				<div class=\"clear\"></div>
				<p style=\"margin-top: 20px;\">
				<a href=\"view_recipe.php?recipe_id=$get_recipe_comment_recipe_id&amp;l=$l\" class=\"btn bt_default\">$l_back</a>
				</p>
				<!-- Back -->
				";
			} // recipe found
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