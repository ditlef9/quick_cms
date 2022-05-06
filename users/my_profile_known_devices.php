<?php
/**
*
* File: users/my_profile_known_devices.php
* Version 19:56 22.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("$root/_admin/_data/config/user_system.php");
include("$root/_admin/_data/user_professional_allowed_settings.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_known_devices - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");


/*- Tables ---------------------------------------------------------------------------------- */
include("_tables_users.php");



/*- Content --------------------------------------------------------------------------- */

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
	if($get_my_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_professional_id, $get_my_professional_user_id, $get_my_professional_company, $get_my_professional_company_location, $get_my_professional_department, $get_my_professional_work_email, $get_my_professional_position, $get_my_professional_position_abbr, $get_my_professional_district) = $row;
	if($get_my_professional_id == ""){

		// Create professional profile
		mysqli_query($link, "INSERT INTO $t_users_professional 
		(professional_id, professional_user_id) 
		VALUES 
		(NULL, $get_my_user_id)")
		or die(mysqli_error($link));
	}


	if($action == ""){
		echo"
		<h1>$l_known_devices</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_known_devices.php?l=$l\">$l_known_devices</a>
				</p>
			</div>
		<!-- //You are here -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Known devices list -->

			<table>
			";

			$query = "SELECT known_device_id, known_device_updated_datetime_saying, known_device_country, known_device_browser, known_device_os, known_device_os_icon, known_device_type, known_device_accepted_language, known_device_language, known_device_last_url FROM $t_users_known_devices WHERE known_device_user_id=$get_my_user_id ORDER BY known_device_id DESC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_known_device_id, $get_known_device_updated_datetime_saying, $get_known_device_country, $get_known_device_browser, $get_known_device_os, $get_known_device_os_icon, $get_known_device_type, $get_known_device_accepted_language, $get_known_device_language, $get_known_device_last_url) = $row;

				echo"
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p><img src=\"_gfx/os/$get_known_device_os_icon\" alt=\"$get_known_device_os_icon\" /></p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p>$get_known_device_os &middot; $get_known_device_browser<br />
					$get_known_device_country &middot; $get_known_device_updated_datetime_saying
					</p>
				  </td>
				  <td>
					<p>
					<a href=\"my_profile_known_devices.php?action=report&amp;known_device_id=$get_known_device_id\"><img src=\"_gfx/report.png\" alt=\"report.png\" /></a>
					<a href=\"my_profile_known_devices.php?action=delete&amp;known_device_id=$get_known_device_id&amp;process=1\"><img src=\"_gfx/delete.png\" alt=\"delete.png\" /></a>
					</p>
				  </td>
				 </tr>
				";
			}
			echo"
			</table>
		<!-- //Known devices list -->

		";
	}
	elseif($action == "report"){
		$known_device_id = $_GET['known_device_id'];
		$known_device_id = output_html($known_device_id);
		if(!(is_numeric($known_device_id))){
			echo"Known device id not numeric";
			die;
		}
		$known_device_id_mysql = quote_smart($link, $known_device_id);
		$query = "SELECT known_device_id, known_device_user_id, known_device_fingerprint, known_device_created_datetime, known_device_created_datetime_saying, known_device_updated_datetime, known_device_updated_datetime_saying, known_device_updated_year, known_device_created_ip, known_device_created_hostname, known_device_last_ip, known_device_last_hostname, known_device_user_agent, known_device_country, known_device_type, known_device_os, known_device_os_icon, known_device_browser, known_device_accepted_language, known_device_language, known_device_last_url, known_device_reported FROM $t_users_known_devices WHERE known_device_id=$known_device_id_mysql AND known_device_user_id=$get_my_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_known_device_id, $get_current_known_device_user_id, $get_current_known_device_fingerprint, $get_current_known_device_created_datetime, $get_current_known_device_created_datetime_saying, $get_current_known_device_updated_datetime, $get_current_known_device_updated_datetime_saying, $get_current_known_device_updated_year, $get_current_known_device_created_ip, $get_current_known_device_created_hostname, $get_current_known_device_last_ip, $get_current_known_device_last_hostname, $get_current_known_device_user_agent, $get_current_known_device_country, $get_current_known_device_type, $get_current_known_device_os, $get_current_known_device_os_icon, $get_current_known_device_browser, $get_current_known_device_accepted_language, $get_current_known_device_language, $get_current_known_device_last_url, $get_current_known_device_reported) = $row;
		if($get_current_known_device_id == ""){
			echo"Known device id not found";
		}
		else{
			if($process == "1"){
				// Email to moderator of the week
				$year = date("Y");
				$week = date("W");

				// Who is moderator of the week?
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
				$subject = "$l_known_device_report_for $get_my_user_name $l_at_lowercase $configWebsiteTitleSav $l_at_lowercase $get_current_known_device_updated_datetime";
			
				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
				$message = $message . "<h1>$l_known_device_report_for $get_my_user_name</h1>\n\n";
				$message = $message . "<p>$l_hi $get_moderator_user_name,<br /><br />\n";
				$message = $message . "$l_the_user $get_my_user_name $l_has_reported_a_known_login_lowercase.\n";
				$message = $message . "</p>\n";

				$message = $message . "<hr />\n";
				$message = $message . "<h2>$l_user_information</h2>\n";
				$message = $message . "<table>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_user_id:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_my_user_id</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_email:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_my_user_email</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_username:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_my_user_name</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";
				$message = $message . "</table>\n\n";



				$message = $message . "<hr />\n";
				$message = $message . "<h2>$l_login_information:</h2>\n";
				$message = $message . "<table>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_created:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_created_datetime_saying</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_first_ip:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_created_ip</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_first_hostname:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_created_hostname</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_updated:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_updated_datetime_saying</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_last_ip:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_last_ip</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_last_hostname:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_last_hostname</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_country:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_country</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_user_agent:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_user_agent</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";


				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_type:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_type</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_os:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_os</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_browser:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_browser</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_accepted_language:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_accepted_language</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_language:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_language</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";

				$message = $message . " <tr>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span><b>$l_url:</b></span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
				$message = $message . "     <span>$get_current_known_device_last_url</span>\n";
				$message = $message . "  </td>\n\n";
				$message = $message . " </tr>\n\n";
				$message = $message . "</table>\n\n";

				$message = $message . "<p>\n\n--<br />\nBest regards<br />\n";
				$message = $message . "$configWebsiteTitleSav<br />\n";
				$message = $message . "$configFromEmailSav<br />\n";
				$message = $message . "<a href=\"$configSiteURLSav\">$configSiteURLSav</a>\n</p>";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";

				// Preferences for Subject field
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=utf-8';
				$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
				if($configMailSendActiveSav == "1"){
					mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers));
				}

				// Update
				mysqli_query($link, "UPDATE $t_users_known_devices SET known_device_reported=1 WHERE known_device_id=$get_current_known_device_id") or die(mysqli_error($link));



				// Header
				$url = "my_profile_known_devices.php?known_device_id=$get_current_known_device_id&l=$l&ft=success&fm=report_sent";
				header("Location: $url");
				exit;
				
			}

			echo"
			<h1>$l_report_known_device</h1>

			<!-- You are here -->
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_known_devices.php?l=$l\">$l_known_devices</a>
				&gt; 
				<a href=\"my_profile_known_devices.php?action=report&amp;known_device_id=$get_current_known_device_id&amp;l=$l\">$l_report</a>
				</p>
			<!-- //You are here -->

			<!-- Report content -->

				<table>
				 <tr>
				  <td style=\"padding-right: 4px;\">
					<span>$l_created:</span>
				  </td>
				  <td>
					<span>$get_current_known_device_created_datetime_saying</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 4px;\">
					<span>$l_updated:</span>
				  </td>
				  <td>
					<span>$get_current_known_device_updated_datetime_saying</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 4px;\">
					<span>$l_country:</span>
				  </td>
				  <td>
					<span>$get_current_known_device_country</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 4px;\">
					<span>$l_type:</span>
				  </td>
				  <td>
					<span>$get_current_known_device_type</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 4px;\">
					<span>$l_os:</span>
				  </td>
				  <td>
					<span>$get_current_known_device_os</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 4px;\">
					<span>$l_browser:</span>
				  </td>
				  <td>
					<span>$get_current_known_device_browser</span>
				  </td>
				 </tr>
				</table>


				<p>
				$l_do_you_want_to_send_a_report_to_the_moderator_with_this_known_device
				</p>

				<p>
				<a href=\"my_profile_known_devices.php?action=report&amp;known_device_id=$get_current_known_device_id&amp;process=1&amp;l=$l\" class=\"btn_default\">$l_send_report</a>
				<a href=\"my_profile_known_devices.php?l=$l#known_device_id$get_current_known_device_id\" class=\"btn_default\">$l_cancel</a>	
				</p>
			<!-- //Report content -->
			";
		} // device found
	} // report
	elseif($action == "delete"){
		$known_device_id = $_GET['known_device_id'];
		$known_device_id = output_html($known_device_id);
		if(!(is_numeric($known_device_id))){
			echo"Known device id not numeric";
			die;
		}
		$known_device_id_mysql = quote_smart($link, $known_device_id);
		$query = "SELECT known_device_id, known_device_user_id, known_device_fingerprint, known_device_created_datetime, known_device_created_datetime_saying, known_device_updated_datetime, known_device_updated_datetime_saying, known_device_updated_year, known_device_created_ip, known_device_created_hostname, known_device_last_ip, known_device_last_hostname, known_device_user_agent, known_device_country, known_device_type, known_device_os, known_device_os_icon, known_device_browser, known_device_accepted_language, known_device_language, known_device_last_url, known_device_reported FROM $t_users_known_devices WHERE known_device_id=$known_device_id_mysql AND known_device_user_id=$get_my_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_known_device_id, $get_current_known_device_user_id, $get_current_known_device_fingerprint, $get_current_known_device_created_datetime, $get_current_known_device_created_datetime_saying, $get_current_known_device_updated_datetime, $get_current_known_device_updated_datetime_saying, $get_current_known_device_updated_year, $get_current_known_device_created_ip, $get_current_known_device_created_hostname, $get_current_known_device_last_ip, $get_current_known_device_last_hostname, $get_current_known_device_user_agent, $get_current_known_device_country, $get_current_known_device_type, $get_current_known_device_os, $get_current_known_device_os_icon, $get_current_known_device_browser, $get_current_known_device_accepted_language, $get_current_known_device_language, $get_current_known_device_last_url, $get_current_known_device_reported) = $row;
		if($get_current_known_device_id == ""){
			echo"Known device id not found";
		}
		else{
		
			// Update
			mysqli_query($link, "DELETE FROM $t_users_known_devices WHERE known_device_id=$get_current_known_device_id") or die(mysqli_error($link));



			// Header
			$url = "my_profile_known_devices.php?known_device_id=$get_current_known_device_id&l=$l&ft=success&fm=known_login_deleted";
			header("Location: $url");
			exit;
				
		} // found
	} // delete
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"_gfx/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>