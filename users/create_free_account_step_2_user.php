<?php
/**
*
* File: users/index.php
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
include("$root/_admin/_data/config/user_system.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_users.php");
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

$t_stats_users_registered_per_month = $mysqlPrefixSav . "stats_users_registered_per_month";
$t_stats_users_registered_per_year  = $mysqlPrefixSav . "stats_users_registered_per_year";
$t_stats_users_registered_per_week  = $mysqlPrefixSav . "stats_users_registered_per_week";

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");
include("$root/_admin/_translations/site/$l/users/ts_create_free_account.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_menu_create_free_account - $l_users";
include("$root/_webdesign/header.php");


/*- Variables ----------------------------------------------------------------------- */
if(isset($_GET['referer'])) {
	$referer = $_GET["referer"];
	$referer = output_html($referer);
}
else{
	$referer = "";
}


if(!(isset($_SESSION['user_id']))){

	if(isset($_SESSION['antispam_ok'])){

		if($action == "create_new_user"){
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j M Y H:i");
			$date_saying = date("j M Y");


			// User information
			$inp_user_name = "";
			if(isset($_POST['inp_user_name'])){
				$inp_user_name = $_POST['inp_user_name'];
			}
			$inp_user_name = preg_replace("/[^ \w]+/", "", $inp_user_name);
			$inp_user_name = output_html($inp_user_name);
			$inp_user_name = substr($inp_user_name, 0, 20);
			$inp_user_name_mysql = quote_smart($link, $inp_user_name);
			if(empty($inp_user_name)){
				$ft = "warning";
				$fm = "please_enter_a_user_name";
				$action = "";
			}

			$inp_email = "";
			if(isset($_POST['inp_email'])){
				$inp_email = $_POST['inp_email'];
			}
			$inp_email = output_html($inp_email);
			$inp_email = strtolower($inp_email);
			$inp_email_mysql = quote_smart($link, $inp_email);
			if(empty($inp_email)){
				$ft = "warning";
				$fm = "users_please_enter_your_email_address";
				$action = "";
			}
			else{
				// Does that alias belong to someone else?
				$query = "SELECT user_id FROM $t_users WHERE user_name=$inp_user_name_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_user_id) = $row;
				if($get_user_id != ""){
					$ft = "warning";
					$fm = "user_name_taken";
					$action = "";
				}

				// Does that e-mail belong to someone else?
				$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_email_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_user_id) = $row;
				if($get_user_id != ""){
					$ft = "warning";
					$fm = "email_taken";
					$action = "";
				}
			}

			$inp_password = "";
			if(isset($_POST['inp_password'])){
				$inp_password = $_POST['inp_password'];
			}
			if(empty($inp_password)){
				$ft = "warning";
				$fm = "users_please_enter_a_password";
				$action = "";
			}

			// Country
			$inp_country_id = "";
			if(isset($_POST['inp_country_id'])){
				$inp_country_id = $_POST['inp_country_id'];
			}
			$inp_country_id = output_html($inp_country_id);
			$inp_country_id = ucfirst($inp_country_id);
			$inp_country_id_mysql = quote_smart($link, $inp_country_id);
			if(empty($inp_country_id)){
				$ft = "warning";
				$fm = "please_select_a_country";
				$action = "";
			}
			if(!(is_numeric($inp_country_id))){
				$ft = "warning";
				$fm = "country_id_is_not_numeric";
				$action = "";
			}
			$query = "SELECT country_id, country_name FROM $t_languages_countries WHERE country_id=$inp_country_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_country_id, $get_country_name) = $row;

			$inp_country_name = "$get_country_name";
			$inp_country_name_mysql = quote_smart($link, $inp_country_name);
	
			

			// Find the time zone and measurment the last person registrered used
			$query = "SELECT user_id, user_timezone_utc_diff, user_timezone_value, user_measurement FROM $t_users WHERE user_country_id=$inp_country_id_mysql ORDER BY user_id DESC LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_user_id, $get_user_timezone_utc_diff, $get_user_timezone_value, $get_last_user_measurement) = $row;
			if($get_last_user_id != ""){
				$inp_mesurment = "$get_last_user_measurement";
				$inp_timezone_utc_diff = "$get_user_timezone_utc_diff";
				$inp_timezone_value = "$get_user_timezone_value";

				if($inp_timezone_utc_diff == ""){
					$inp_mesurment = "metric";
					$inp_timezone_utc_diff = "2";
					$inp_timezone_value = "(GMT+02:00) Europe, Oslo";
				}
			}
			else{
				$inp_mesurment = "metric";
				$inp_timezone_utc_diff = "2";
				$inp_timezone_value = "(GMT+02:00) Europe, Oslo";
			}

			$inp_timezone_utc_diff_mysql = quote_smart($link, $inp_timezone_utc_diff);
			$inp_timezone_value_mysql = quote_smart($link, $inp_timezone_value);


			if($inp_mesurment == ""){	
				if($inp_country == "United States"){
					$inp_mesurment = "imperial";
				}
				else{
					$inp_mesurment = "metric";
				}
			}
			$inp_mesurment = output_html($inp_mesurment);
			$inp_mesurment_mysql = quote_smart($link, $inp_mesurment);

			$inp_newsletter = "0";
			if(isset($_POST['inp_newsletter'])){
				$inp_newsletter = $_POST['inp_newsletter'];
			}
			$inp_newsletter = $_POST['inp_newsletter'];
			$inp_newsletter = output_html($inp_newsletter);
			$inp_newsletter_mysql = quote_smart($link, $inp_newsletter);

	
			if($action == "create_new_user"){
			
				// Create salt
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    				$charactersLength = strlen($characters);
    				$salt = '';
    				for ($i = 0; $i < 6; $i++) {
        				$salt .= $characters[rand(0, $charactersLength - 1)];
    				}
				$inp_user_salt_mysql = quote_smart($link, $salt);

				// Password
				$inp_user_password_encrypted =  sha1($inp_password);
				$inp_user_password_mysql = quote_smart($link, $inp_user_password_encrypted);

				// Security
				$inp_user_security = rand(0,9999);

				// Language
				$inp_user_language = output_html($l);
				$inp_user_language_mysql = quote_smart($link, $inp_user_language);

				// Registered
				$datetime = date("Y-m-d H:i:s");
				$time = time();
				$date = date("Y-m-d");

				// Date format
				if($l == "no"){
					$inp_user_date_format = "l d. f Y";
				}
				else{
					$inp_user_date_format = "l jS \of F Y";
				}
				$inp_user_date_format_mysql = quote_smart($link, $inp_user_date_format);


				// Ip
				$inp_user_last_ip_mysql = quote_smart($link, $my_ip);


				// Insert user
				mysqli_query($link, "INSERT INTO $t_users
				(user_id, user_email, user_name, user_alias, user_password, 
				user_password_replacement, user_password_date, user_salt, user_security, user_rank, 
				user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, 
				user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, 
				user_measurement, user_date_format, user_registered, user_registered_time, user_registered_date_saying, user_newsletter,
				user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, 
				user_likes, user_dislikes, user_status, user_login_tries, user_last_online, 
				user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer) 
				VALUES 
				(NULL, $inp_email_mysql, $inp_user_name_mysql, $inp_user_name_mysql, $inp_user_password_mysql, 
				'', '$date', $inp_user_salt_mysql, '$inp_user_security', 'user', 
				1, '', '', '', $inp_user_language_mysql, 
				$inp_country_id_mysql, $inp_country_name_mysql, '', $inp_timezone_utc_diff_mysql, $inp_timezone_value_mysql, 
				$inp_mesurment_mysql, $inp_user_date_format_mysql, '$datetime', '$time', '$date_saying', $inp_newsletter_mysql, 
				'public', 0, '', 0, 'Newbie',
				0, 0, 'Just joined!', 0, '$datetime', 
				$time, $inp_user_last_ip_mysql, 0, '', 0)")
				or die(mysqli_error($link));

				// Get user id
				$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_email_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_my_user_id) = $row;



				// Search engine
				if($configShowUsersOnSearchEngineIndexSav == "1"){

					$inp_index_title = "$inp_user_name | $l_users";
					$inp_index_title_mysql = quote_smart($link, $inp_index_title);

					$inp_index_url = "users/view_profile.php?user_id=$get_my_user_id";
					$inp_index_url_mysql = quote_smart($link, $inp_index_url);

					// Insert
					mysqli_query($link, "INSERT INTO $t_search_engine_index 
					(index_id, index_title, index_url, index_short_description, index_keywords, 
					index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
					index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
					index_unique_hits) 
					VALUES 
					(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', '', 
					'users', 'users', '0', 'user_id', '$get_my_user_id',
					'0', '0', '$datetime', '$datetime_saying', '',
					0)")
					or die(mysqli_error($link));

				}

			
				// Send welcome mail
				$host = $_SERVER['HTTP_HOST'];

				$subject = $l_welcome_to . " " . $configWebsiteTitleSav;
			
				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				if($logoFileSav != "" && file_exists("$root/$logoPathSav/$logoFileSav")){
					$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
				}
				$message = $message . "<h1>$l_welcome_to $configWebsiteTitleSav</h1>\n\n";
				$message = $message . "<p>$l_hi $inp_user_name,<br /><br />\n";
				$message = $message . "$l_thank_you_for_signing_up\n";
				$message = $message . "$l_we_hope_you_will_be_pleased_with_your_membership</p>";

				$message = $message . "<p><b>$l_your_information</b><br />\n\n";
				$message = $message . "$l_email_address: $inp_email<br />\n";
				$message = $message . "$l_username: $inp_user_name</p>\n";


				if($configUsersHasToBeVerifiedByModeratorSav == "1"){
					$message = $message . "<p>$l_your_account_will_be_examined_by_a_moderator_shortly\n";
					$message = $message . "$l_it_will_after_examination_be_approved</p>\n";
				}

				$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n<a href=\"$configSiteURLSav\">$configSiteURLSav</a></p>";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";


				// Preferences for Subject field
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=utf-8';
				$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
				if($configMailSendActiveSav == "1"){
					mail($inp_email, $subject, $message, implode("\r\n", $headers));
				}
				
			

				// Setup email notifications
				$inp_es_user_id = quote_smart($link, $get_my_user_id);
				mysqli_query($link, "INSERT INTO $t_users_email_subscriptions
				(es_id, es_user_id, es_type, es_on_off) 
				VALUES 
				(NULL, $inp_es_user_id, 'friend_request', '1'),
				(NULL, $inp_es_user_id, 'status_comments', '1'),
				(NULL, $inp_es_user_id, 'status_replies', '1'),
				(NULL, $inp_es_user_id, 'my_birthday', '1')")
				or die(mysqli_error($link));

				// Statistics
				// --> weekly
				$day = date("d");
				$month = date("m");
				$month_saying = date("M");
				$week = date("W");
				$year = date("Y");
				$week = date("W");

				// User registered :: Year
				$query = "SELECT stats_registered_id, stats_registered_users_registed FROM $t_stats_users_registered_per_year WHERE stats_registered_year=$year";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_registered_id, $get_stats_registered_users_registed) = $row;
				if($get_stats_registered_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_users_registered_per_year 
					(stats_registered_id, stats_registered_year, stats_registered_users_registed) 
					VALUES 
					(NULL, $year, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_registered_users_registed+1;

					$result = mysqli_query($link, "UPDATE $t_stats_users_registered_per_year SET stats_registered_users_registed=$inp_counter WHERE stats_registered_id=$get_stats_registered_id") or die(mysqli_error($link));
				}

				// User registered :: Month
				$query = "SELECT stats_registered_id, stats_registered_users_registed FROM $t_stats_users_registered_per_month WHERE stats_registered_month=$month AND stats_registered_year=$year";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_registered_id, $get_stats_registered_users_registed) = $row;
				if($get_stats_registered_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_users_registered_per_month 
					(stats_registered_id, stats_registered_month, stats_registered_month_saying, stats_registered_year, stats_registered_users_registed) 
					VALUES 
					(NULL, $month, '$month_saying', $year, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_registered_users_registed+1;

					$result = mysqli_query($link, "UPDATE $t_stats_users_registered_per_month SET stats_registered_users_registed=$inp_counter WHERE stats_registered_id=$get_stats_registered_id") or die(mysqli_error($link));
				}

				// User registered :: Week
				$query = "SELECT stats_registered_id, stats_registered_users_registed FROM $t_stats_users_registered_per_week WHERE stats_registered_week=$week AND stats_registered_year=$year";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_registered_id, $get_stats_registered_users_registed) = $row;
				if($get_stats_registered_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_users_registered_per_week 
					(stats_registered_id, stats_registered_week, stats_registered_year, stats_registered_users_registed, stats_registered_users_registed_diff_from_last_week) 
					VALUES 
					(NULL, $week, $year, 1, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_registered_users_registed+1;

					$result = mysqli_query($link, "UPDATE $t_stats_users_registered_per_week SET stats_registered_users_registed=$inp_counter WHERE stats_registered_id=$get_stats_registered_id") or die(mysqli_error($link));
				}


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


				// Do we need to approve/verify user?
				if($configUsersHasToBeVerifiedByModeratorSav == "0"){
			

					// Set user verified
					$result = mysqli_query($link, "UPDATE $t_users SET user_verified_by_moderator='1' WHERE user_id='$get_my_user_id'");


					// Login user
					$_SESSION['user_id'] = "$get_my_user_id";
					$_SESSION['security'] = "$inp_user_security";


					// Send e-mail to moderators that there is a new user
					
					$subject = "New user $inp_user_name at $configWebsiteTitleSav";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					if($logoFileSav != "" && file_exists("$root/$logoPathSav/$logoFileSav")){
						$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
					}
					$message = $message . "<h1>New user $inp_user_name</h1>\n\n";
					$message = $message . "<p>\n";
					$message = $message . "E-mail: $inp_email<br />\n";
					$message = $message . "Username: $inp_user_name<br />\n";
					$message = $message . "Country: $inp_country_name<br />\n";
					$message = $message . "Newsletter: $inp_newsletter<br />\n";
					$message = $message . "</p>\n";
					$message = $message . "<p><a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\">View profile</a></p>\n";

					$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$get_moderator_user_name at $configWebsiteTitleSav<br />\n";
					$message = $message . "<a href=\"$configSiteURLSav/index.php?l=$l\">$configSiteURLSav</a></p>";
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";



					// Preferences for Subject field
					$headers_mail[] = 'MIME-Version: 1.0';
					$headers_mail[] = 'Content-type: text/html; charset=utf-8';
					$headers_mail[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
					if($configMailSendActiveSav == "1"){
						mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers_mail));
					}


					if($referer != ""){
						$url = "create_free_account_step_3_city.php?l=$l&referer=$referer";
					}
					else{
						$url = "create_free_account_step_3_city.php?l=$l";
					}
					echo"
					<table>
					 <tr> 
					  <td style=\"padding-right: 6px;vertical-align: top;\">
						<span>
						<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
						</span>
					  </td>
					  <td>
						<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">$l_hi $inp_user_name!</h1>
					  </td>
					 </tr>
					</table>

					<p>$l_nice_to_meet_you</p>
					

					<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
				}
				else{

					// Set user not verified
					$result = mysqli_query($link, "UPDATE $t_users SET user_verified_by_moderator='0' WHERE user_id='$get_my_user_id'");



					// Mail approve URLs
					$pageURL = 'http';
					$pageURL .= "://";

					if ($_SERVER["SERVER_PORT"] != "80") {
						$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
					} 
					else {
						$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
					}
	
					$view_link = $configSiteURLSav . "/users/view_profile.php?user_id=$get_my_user_id";
					$approve_link = $configControlPanelURLSav . "/index.php?open=users&page=pending_users&action=approve&user_id=$get_my_user_id";
					$disapprove_link = $configControlPanelURLSav . "/index.php?open=users&page=pending_users&action=disapprove&user_id=$get_my_user_id";

					$user_agent = $_SERVER['HTTP_USER_AGENT'];
					$user_agent = output_html($user_agent);

					$subject = "Please approve user $inp_user_name at $configWebsiteTitleSav";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
					$message = $message . "<p><b>Summary:</b><br />There is a new user at $host. Please approve or disapprove.</p>\n\n";
					$message = $message . "<p style=\"margin-bottom:0;padding-bottom:0;\"><b>User:</b><br />\n<ul style=\"margin:0px 0px 5px 5px;padding:0px 0px 5px 5px;\">\n<li><span>E-mail: $inp_email</span></li>\n<li><span>User name: $inp_user_name</span></li>\n<li><span>Language: $inp_user_language</span></li>\n<li><span>Date: $datetime</span></li>\n<li><span>Agent: $user_agent</span></li>\n<li><span>IP: <a href=\"https://www.google.no/search?q=$inp_ip\">$inp_ip</a></span></li>\n</ul>\n\n";
					$message = $message . "<p><b>Actions:</b><br />\n";
					$message = $message . "View: <a href=\"$view_link\">$view_link</a><br />";
					$message = $message . "Approve: <a href=\"$approve_link\">$approve_link</a><br />";
					$message = $message . "Disapprove: <a href=\"$disapprove_link\">$disapprove_link</a></p>";
					$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$host</p>";
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";


					// Preferences for Subject field
					$headers_email[] = 'MIME-Version: 1.0';
					$headers_email[] = 'Content-type: text/html; charset=utf-8';
					$headers_email[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
					if($configMailSendActiveSav == "1"){
						mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers_email));
					}


					$url = "index.php?page=create_free_account_awaiting_approvement&l=$l"; 
					if($process == "1"){
						header("Location: $url");
					}
					else{
						echo"
						<table>
						 <tr> 
						  <td style=\"padding-right: 6px;vertical-align: top;\">
							<span>
							<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
							</span>
						  </td>
						  <td>
							<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">Loading</h1>
						  </td>
						 </tr>
						</table>

						<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
					}
					exit;

				}
			
			}
		}
		if($action == "" OR $action == "check_antispam"){

			echo"
			<h1>$l_menu_create_free_account</h1>

			<form method=\"POST\" action=\"create_free_account_step_2_user.php?action=create_new_user&amp;l=$l"; if($referer != ""){ echo "&amp;referer=$referer"; } echo"\" enctype=\"multipart/form-data\">

			<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "please_enter_a_user_name"){
					$fm = "$l_please_enter_a_user_name";
				}
				elseif($fm == "user_name_taken"){
					$fm = "$l_user_name_taken";
				}
				elseif($fm == "email_taken"){
					$fm = "$l_email_taken";
				}
				elseif($fm == "please_enter_a_password"){
					$fm = "$l_please_enter_a_password";
				}
				elseif($fm == "server_error"){
					$fm = "$l_server_error";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
			<!-- //Feedback -->


			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_user_name\"]').focus();
			});
			</script>
			<!-- //Focus -->


			<p>
			$l_users_about_registration
			</p>

			<h2>$l_user_information</h2>
			<p>
			$l_username*:<br />
			<input type=\"text\" name=\"inp_user_name\" size=\"45\" value=\""; if(isset($inp_user_name)){ echo"$inp_user_name"; } echo"\" style=\"width: 99%;\" /><br />
			</p>

			<p>
			$l_email_address*:<br />
			<input type=\"text\" name=\"inp_email\" size=\"45\" value=\""; if(isset($inp_email)){ echo"$inp_email"; } echo"\" style=\"width: 99%;\" /><br />
			</p>

			<p>
			$l_wanted_password*:<br />
			<input type=\"password\" name=\"inp_password\" size=\"45\" value=\""; if(isset($inp_password)){ echo"$inp_password"; } echo"\" style=\"width: 99%;\" /><br />
			</p>

			<p>
			$l_country*:<br />";

			if(!(isset($inp_country_id))){
				// Find country based on my IP
				$get_ip_id = "";
				$get_country = "";
				if (ip2long($my_ip) !== false) {
					$ip_type = "ipv4";

					$in_addr = inet_pton($my_ip);
					$in_addr_mysql = quote_smart($link, $in_addr);

					$query = "select * from $t_stats_ip_to_country_lookup_ipv4 where addr_type = '$ip_type' and ip_start <= $in_addr_mysql order by ip_start desc limit 1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;
				} else if (preg_match('/^[0-9a-fA-F:]+$/', $my_ip) && @inet_pton($my_ip)) {
					$ip_type = "ipv6";

					$in_addr = inet_pton($my_ip);
					$in_addr_mysql = quote_smart($link, $in_addr);

					$query = "select * from $t_stats_ip_to_country_lookup_ipv6 where addr_type = '$ip_type' and ip_start <= $in_addr_mysql order by ip_start desc limit 1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;
				}
				
				$inp_country_id = "";
				$get_my_country_name = "";
				$get_my_country_iso_two = "";
				if($get_ip_id != ""){
					$get_country = strtolower($get_country);
					$country_iso_two_mysql = quote_smart($link, $get_country);
					$query = "SELECT country_id, country_name, country_iso_two FROM $t_languages_countries WHERE country_iso_two=$country_iso_two_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_country_id, $get_my_country_name, $get_my_country_iso_two) = $row;
		
					$inp_country_id = "$get_country_id";
				}

				if($inp_country_id == ""){
					// Could not find country based on IP, use last used country name
					$query = "SELECT user_country_id FROM $t_users ORDER BY user_id DESC LIMIT 0,1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($inp_country_id) = $row;
				}
			}
			echo"
			<select name=\"inp_country_id\">\n";
			$query = "SELECT country_id, country_name FROM $t_languages_countries ORDER BY country_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_country_id, $get_country_name) = $row;
				echo"			";
				echo"<option value=\"$get_country_id\""; if(isset($inp_country_id) && $inp_country_id == "$get_country_id"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
				
			}
			echo"
			</select>
			</p>

			<p>";
			if(!(isset($inp_newsletter))){
				$inp_newsletter = 0;
			}
			echo"$l_newsletter*:<br />
			<input type=\"radio\" name=\"inp_newsletter\" value=\"1\""; if(isset($inp_newsletter) && $inp_newsletter == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes
			&nbsp;
			<input type=\"radio\" name=\"inp_newsletter\" value=\"0\""; if(isset($inp_newsletter) && $inp_newsletter == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
			</p>

			<p>
			<input type=\"submit\" value=\"$l_user_create\" class=\"btn\" />
			</p>

			</form>

			";

		}
	} // antispam ok
	else{
		echo"<h1>Could not confirm anti spam session</h1>
		<p>Please start at the beginning..</p>

		<p>
		<a href=\"create_free_account.php?l=$l"; if(isset($referer) && $referer != ""){ echo"&amp;referer=$referer"; } echo"\">Create free account</a>
		</p>
		<meta http-equiv=\"refresh\" content=\"1;url=create_free_account.php?l=$l"; if(isset($referer) && $referer != ""){ echo"&amp;referer=$referer"; } echo"\" />";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
	<p>You are registered!</p>
	<p>
	<a href=\"$root/index.php\" class=\"btn\">Home</a></p>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>