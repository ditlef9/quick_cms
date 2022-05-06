<?php
/**
*
* File: users/status_like.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");
include("$root/_admin/_data/logo.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Variables --------------------------------------------------------------------------- */
if(isset($_GET['status_id'])) {
	$status_id = $_GET['status_id'];
	$status_id = strip_tags(stripslashes($status_id));
}
else{
	$status_id = "";
	echo"
	<h1>Error</h1>
	
	<p>Status not found</p>
	";
	die;
}
$status_id_mysql = quote_smart($link, $status_id);



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_report - $l_users";
include("$root/_webdesign/header.php");

// Get status
$q = "SELECT status_id, status_user_id, status_created_by_user_id, status_created_by_user_alias, status_created_by_user_image, status_created_by_ip, status_text, status_photo, status_datetime, status_datetime_print, status_time, status_language, status_likes, status_comments, status_reported, status_reported_checked, status_reported_reason, status_seen FROM $t_users_status WHERE status_id=$status_id_mysql";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_status_id, $get_status_user_id, $get_status_created_by_user_id, $get_status_created_by_user_alias, $get_status_created_by_user_image, $get_status_created_by_ip, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_datetime_print, $get_status_time, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked, $get_status_reported_reason, $get_status_seen) = $rowb;




if($get_status_id == ""){
	echo"
	<h1>Error</h1>
	
	<p>Status not found</p>
	";
	
}
else{
	if($get_status_reported == "1"){
		echo"
		<h1>$l_report</h1>

		<p>$l_this_status_has_been_reported_previously</p>

		<p>
		<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
		<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$l\">$l_previous</a>
		</p>
		";

	}
	else{
		// Thumb
		$inp_new_x = 40; // 950
		$inp_new_y = 40; // 640
		if(file_exists("$root/_uploads/users/images/$get_status_created_by_user_id/$get_status_created_by_user_image") && $get_status_created_by_user_image != ""){
			$thumb_full_path = "$root/_cache/user_" . $get_status_created_by_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			if(!(file_exists("$thumb_full_path"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_status_created_by_user_id/$get_status_created_by_user_image", "$thumb_full_path");
			}
			$thumb_full_path = "_cache/user_" . $get_status_created_by_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
		}
		else{
			$thumb_full_path = "users/_gfx/avatar_blank_40.png";
		}



		if(isset($_SESSION['user_id'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;

			if($process == "1"){
				
				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);
				if($inp_text == ""){
					$url = "status_report.php?status_id=$status_id&amp;l=$l&ft=error&fm=missing_text";
					header("Location: $url");
					die;

				}
				$inp_text = $inp_text . "\nReport by $get_my_user_alias";
	
				// Report
				$result = mysqli_query($link, "UPDATE $t_users_status SET status_reported='1', status_reported_reason=$inp_text_mysql WHERE status_id=$get_status_id") or die(mysqli_error($link));

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
				$my_ip = $_SERVER['REMOTE_ADDR'];
				$my_ip = output_html($my_ip);
			
				$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
				$my_user_agent = output_html($my_user_agent);
	
				$subject = "Status $get_status_id reported";

				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				$message = $message . "<h1>Status $get_status_id reported</h1>\n\n";
				$message = $message . "<h2>Status</h2>\n\n";

				$message = $message . "			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"width: 45px;vertical-align: top;\">
				<p><img src=\"$configSiteURLSav/$thumb_full_path\" alt=\"$thumb_full_path\" /></p>
			  </td>
			  <td class=\"status\" style=\"vertical-align: top;\">
				<p style=\"padding-bottom: 0;margin-bottom: 0;\">
				<b>$get_status_created_by_user_alias</b>
				<span class=\"smal_grey\">$get_status_datetime_print</span>
				</p>
					
				<p style=\"padding-top:0;margin-top:0;\">
				$get_status_text
				</p>
			  </td>
			 </tr>
			</table>";
				$message = $message . "<p>IP: $get_status_created_by_ip<br />\n";
				$message = $message . "Datetime: $get_status_datetime<br />\n";
				$message = $message . "</p>\n";

				$message = $message . "<h2>Report</h2>\n\n";
				$message = $message . "<p>Reason:<br />\n";
				$message = $message . "$inp_text</p>\n";
				$message = $message . "<p>User: <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\">$get_my_user_alias</a><br />\n";
				$message = $message . "IP: $my_ip<br />\n";
				$message = $message . "User agent: $my_user_agent</p>\n";
		

				$message = $message . "<p><b>Actions:</b><br />\n";
				$message = $message . "<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_status_user_id#status$get_status_id\">View</a> &middot; \n";
				$message = $message . "<a href=\"$configSiteURLSav/users/status_edit.php?status_id=$get_status_id\">Edit status</a> &middot; \n";
				$message = $message . "<a href=\"$configSiteURLSav/users/status_delete.php?status_id=$get_status_id\">Delete status</a>\n";
				$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteWebmasterSav<br />\n$configWebsiteTitleSav\n</p>";
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
				$header .= "From: $configFromEmailSav \r\n";
				$header .= "MIME-Version: 1.0 \r\n";
				$header .= "Content-Transfer-Encoding: 8bit \r\n";
				$header .= "Date: ".date("r (T)")." \r\n";
				$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

				mail($get_moderator_user_email, $subject, $message, $header);




				$url = "view_profile.php?user_id=$get_status_user_id&l=$l&ft=success&fm=comment_reported#status$get_status_id";
				header("Location: $url");
				die;
	

			} // process

	
			echo"
			<h1>$l_report</h1>

			<!-- Status -->
				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"width: 45px;vertical-align: top;\">
					<p><img src=\"$root/$thumb_full_path\" alt=\"$thumb_full_path\" /></p>
				  </td>
				  <td class=\"status\" style=\"vertical-align: top;\">
					<p style=\"padding-bottom: 0;margin-bottom: 0;\">
					<b>$get_status_created_by_user_alias</b>
					<span class=\"smal_grey\">$get_status_datetime_print</span>
					</p>
					
					<p style=\"padding-top:0;margin-top:0;\">
					$get_status_text
					</p>
				  </td>
				 </tr>
				</table>
			<!-- //Status -->

			<!-- My report -->
				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_text\"]').focus();
					});
					</script>
				<!-- //Focus -->

				<form method=\"post\" action=\"status_report.php?status_id=$get_status_id&amp;l=$l&amp;process=1\" />
			
				<p>
				$l_reason:<br />
				<textarea name=\"inp_text\" rows=\"3\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
				</p>

				<p>
				<input type=\"submit\" value=\"$l_send_report\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				</form>		
			<!-- //My report -->

			<!-- Back -->
				<p>
				<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$l\">$l_previous</a>
				</p>
			<!-- //Back -->
			";

		} // im logged in
		else{
			$url = "$root/users/login.php?l=$l&referer=$root/users/stats_report.php?status_id=$status_id";
			header("Location: $url");
			die;
		}
	} // alreaddy reported
} // status found
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>