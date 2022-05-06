<?php 
/**
*
* File: recipes/comment_recipe_report.php
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
$website_title = "$l_report_comment - $l_recipes";
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
		if($get_recipe_comment_reported == "1"){
			echo"
			<h1>$l_report_comment</h1>

			<p>This comment is already reported.</p>

			
			<!-- Back -->
				<div class=\"clear\"></div>
				<p style=\"margin-top: 20px;\">
				<a href=\"view_recipe.php?recipe_id=$get_recipe_comment_recipe_id&amp;l=$l\" class=\"btn bt_default\">$l_back</a>
				</p>
			<!-- Back -->
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
				if($process == 1){
			
					$inp_recipe_comment_reported_reason = $_POST['inp_recipe_comment_reported_reason'];
					$inp_recipe_comment_reported_reason = output_html($inp_recipe_comment_reported_reason);
					$inp_recipe_comment_reported_reason_mysql = quote_smart($link, $inp_recipe_comment_reported_reason);
					if(empty($inp_recipe_comment_reported_reason)){
						$ft = "error";
						$fm = "no_reason_added";
						$url = "comment_recipe_report.php?recipe_comment_id=$recipe_comment_id&l=$l&comment_ft=$ft&comment_fm=$fm";
						header("Location: $url");
						exit;
					}


					// Update
					$result = mysqli_query($link, "UPDATE $t_recipes_comments SET recipe_comment_reported='1', recipe_comment_reported_reason=$inp_recipe_comment_reported_reason_mysql WHERE recipe_comment_id=$get_recipe_comment_id");


					// Who is moderator of the week?
					$week = date("W");
					$year = date("Y");
			
					$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
					if($get_moderator_user_id == ""){
						// Create moderator of the week
						include("$root/_admin/_functions/create_moderator_of_the_week.php");
					
						$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
					}




					// Mail from
					$host = $_SERVER['HTTP_HOST'];
					$from = "post@" . $_SERVER['HTTP_HOST'];
					$reply = "post@" . $_SERVER['HTTP_HOST'];
			
					$view_link = $configSiteURLSav . "/recipes/view_recipe.php?recipe_id=$get_recipe_id#comment$get_recipe_comment_id";
					$edit_link = $configSiteURLSav . "/recipes/comment_recipe_edit.php?recipe_comment_id=$get_recipe_comment_id";
					$delete_link = $configSiteURLSav . "/recipes/comment_recipe_delete.php?recipe_comment_id=$get_recipe_comment_id";
				

					$subject = "Comment reportet for recipe $get_recipe_title at $host";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
					$message = $message . "<p><b>Summary:</b><br />One comment has been reported at $host for lanugage $l.</p>\n\n";

					$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Recipe:</b></p>\n";
					$message = $message . "<table>\n";
					$message = $message . " <tr><td><span>Recipe ID:</span></td><td><span>$get_recipe_id</span></td></tr>\n";
					$message = $message . " <tr><td><span>Title:</span></td><td><span>$get_recipe_title</span></td></tr>\n";
					$message = $message . "</table>\n";
		
					$message = $message . "<p><b>Comment:</b><br />\n";
					$message = $message . "$get_recipe_comment_user_alias wrote at $get_recipe_comment_datetime\n";
					$message = $message . "$get_recipe_comment_text</p>\n";
		
					$message = $message . "<p><b>Reason for report:</b><br />\n";
					$message = $message . "$inp_recipe_comment_reported_reason</p>\n";
		

					$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Commenter:</b></p>\n";
					$message = $message . "<table>\n";
					$message = $message . " <tr><td><span>User:</span></td><td><span>$get_recipe_comment_user_id</span></td></tr>\n";
					$message = $message . " <tr><td><span>Alias:</span></td><td><span>$get_recipe_comment_user_alias</span></td></tr>\n";
					$message = $message . " <tr><td><span>IP:</span></td><td><span>$get_recipe_comment_user_ip</span></td></tr>\n";
					$message = $message . "</table>\n";

				

					$message = $message . "<p><b>Actions:</b><br />\n";
					$message = $message . "View: <a href=\"$view_link\">$view_link</a><br />\n";
					$message = $message . "Edit: <a href=\"$edit_link\">$edit_link</a><br />\n";
					$message = $message . "Delete: <a href=\"$delete_link\">$delete_link</a></p>";
					$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$host</p>";
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";


					$encoding = "utf-8";

					// Preferences for Subject field
					$subject_preferences = array(
					       "input-charset" => $encoding,
					       "output-charset" => $encoding,
					       "line-length" => 76,
					       "line-break-chars" => "\r\n"
					);
					$header = "Content-type: text/html; charset=".$encoding." \r\n";
					$header .= "From: ".$host." <".$from."> \r\n";
					$header .= "MIME-Version: 1.0 \r\n";
					$header .= "Content-Transfer-Encoding: 8bit \r\n";
					$header .= "Date: ".date("r (T)")." \r\n";
					$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

					mail($get_moderator_user_email, $subject, $message, $header);
	
			 
					// Header
					$ft = "success";
					$fm = "report_sent";
					$url = "view_recipe.php?recipe_id=$get_recipe_id&l=$l&comment_ft=$ft&comment_fm=$fm#comments";
					header("Location: $url");
					exit;
				} // process


				echo"
				<h1>$l_report_comment</h1>

				<p><b>$l_comment:</b><br />
				$get_recipe_comment_text
				</p>


				<form method=\"post\" action=\"comment_recipe_report.php?recipe_comment_id=$recipe_comment_id&amp;l=$l&amp;process=1\" />
			
				<p><b>$l_reason_for_report:</b><br />
				<textarea name=\"inp_recipe_comment_reported_reason\" rows=\"5\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea><br />
				</p>

				<p>
				<input type=\"submit\" value=\"$l_submit\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"margin-top: 4px;\" />
				</p>
				</form>
			

				<!-- Back -->
					<div class=\"clear\"></div>
					<p style=\"margin-top: 20px;\">
					<a href=\"view_recipe.php?recipe_id=$get_recipe_comment_recipe_id&amp;l=$l\" class=\"btn bt_default\">$l_back</a>
					</p>
				<!-- Back -->
				";	
			}  // recipe found
		} // already reported
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