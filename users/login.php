<?php
/**
*
* File: users/index.php
* Version 14:05 19.10.2020
* Copyright (c) 2009-2020 Sindre Andre Ditlefsen
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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Tables ------------------------------------------------------------------------------------ */
include("_tables_users.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_users_login - $l_users";
include("$root/_webdesign/header.php");


/*- Variables --------------------------------------------------------------------------- */
if(isset($_GET['referer'])) {
	$referer = $_GET["referer"];
	$referer = output_html($referer);
	if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $referer)){
		echo"Server error 403, invalid parameters";
		die;
	}
}
else{
	$referer = "";
}

/*- Content --------------------------------------------------------------------------- */




if($action == "check"){

	if(isset($_POST['inp_referer'])){
		$inp_referer = $_POST['inp_referer'];
		$inp_referer = stripslashes(strip_tags($inp_referer));

	}
	else{
		$inp_referer = "";
	}


	if(isset($_POST['inp_email'])){
		$inp_email = $_POST['inp_email'];
	}
	else{
		$inp_email = "";
	}
	$inp_email = output_html($inp_email);
	$inp_email = strtolower($inp_email);
	$inp_email_mysql = quote_smart($link, $inp_email);
	

	if(isset($_POST['inp_password'])){
		$inp_password = $_POST['inp_password'];
	}
	else{
		$inp_password = "";
	}

	if(isset($_POST['inp_remember'])) {
		$inp_remember = $_POST['inp_remember'];
		if($inp_remember != "on"){
			$inp_remember = "off";
		}
	}
	else{
		$inp_remember = "off";
	}

	
	if(empty($inp_email)){
		$url = "login.php?l=$l";
		
						
		$url = $url . "&referer=$inp_referer&ft=warning&fm=please_enter_your_email_address";

		if($process == "1"){
			header("Location: $url");
		}
		else{
			echo"<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
		}
		exit;
	}
	if(empty($inp_password)){
		$url = "login.php?l=$l";
						
		$url = $url . "&referer=$inp_referer&ft=warning&fm=please_enter_your_password&inp_email=$inp_email";

		if($process == "1"){
			header("Location: $url");
		}
		else{
			echo"<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
		}
		exit;
	}
	
	if($action == "check"){
		// Find
		
		$query = "SELECT user_id, user_email, user_name, user_password, user_salt, user_security, user_language, user_verified_by_moderator, user_login_tries FROM $t_users WHERE user_email=$inp_email_mysql OR user_name=$inp_email_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_verified_by_moderator, $get_user_login_tries) = $row;

		if($get_user_id == ""){
			// Email not found
			$url = "login.php?l=$l";
			if(isset($r_action) && $r_action != ""){ $url = $url . "&r_action=$r_action"; } 
			$url = $url . "&referer=$inp_referer&ft=warning&fm=email_address_not_found";

			if($process == "1"){
				header("Location: $url");
			}
			else{
				echo"<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
			}
			exit;

		}
		else{
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j.M Y H:i");
			$year = date("Y");
			$month = date("m");
			$week = date("W");

			// Hostname
			$my_hostname = "$my_ip";
			if($configSiteUseGethostbyaddrSav == "1"){
				$my_hostname = gethostbyaddr($my_ip); // Some servers in local network cant use getostbyaddr because of nameserver missing
			}
			$my_hostname = output_html($my_hostname);
			$my_hostname_mysql = quote_smart($link, $my_hostname);
			
			
			// Fetch country :: Find my country based on IP
			// Country :: IP Type
			$ip_type = "";
			$get_ip_id = "";
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

			// echo"Type=$ip_type<br />";
			// echo"in_addr=$in_addr<br />";

			$get_my_country_name = "";
			$get_my_country_iso_two = "";
			if($get_ip_id != ""){
				$country_iso_two_mysql = quote_smart($link, $get_country);
				$query = "SELECT country_id, country_name, country_iso_two FROM $t_languages_countries WHERE country_iso_two=$country_iso_two_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_country_id, $get_my_country_name, $get_my_country_iso_two) = $row;
			}

			$inp_country_mysql = quote_smart($link, $get_my_country_name);


			// Fetch Browser and OS 
			$my_user_agent_mysql = quote_smart($link, $my_user_agent);
			$query = "SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index WHERE stats_user_agent_string=$my_user_agent_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_stats_user_agent_id, $get_stats_user_agent_string, $get_stats_user_agent_type, $get_stats_user_agent_browser, $get_stats_user_agent_browser_version, $get_stats_user_agent_browser_icon, $get_stats_user_agent_os, $get_stats_user_agent_os_version, $get_stats_user_agent_os_icon, $get_stats_user_agent_bot, $get_stats_user_agent_bot_icon, $get_stats_user_agent_bot_website, $get_stats_user_agent_banned) = $row;


			$inp_browser_mysql = quote_smart($link, $get_stats_user_agent_browser);
			$inp_os_mysql = quote_smart($link, $get_stats_user_agent_os);
			$inp_os_icon = clean($get_stats_user_agent_os);
			$inp_os_icon = $inp_os_icon . "_32x32.png";
			$inp_os_icon_mysql = quote_smart($link, $inp_os_icon);
			$inp_type_mysql = quote_smart($link, $get_stats_user_agent_type);

			$inp_accpeted_language_mysql = quote_smart($link, $inp_accept_language);
			$inp_language = output_html($l);
			$inp_language_mysql = quote_smart($link, $inp_language);

			$inp_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$inp_url = htmlspecialchars($inp_url, ENT_QUOTES, 'UTF-8');
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);


			mysqli_query($link, "INSERT INTO $t_users_logins (login_id, login_user_id, login_datetime, login_datetime_saying, login_year, 
				login_month, login_ip, login_hostname, login_user_agent, login_country, 
				login_browser, login_os, login_type, login_accepted_language, login_language, 
				login_successfully, login_url, login_warning_sent)
				VALUES(
				NULL, $get_user_id, '$datetime', '$datetime_saying', '$year',
				'$month', $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, $inp_country_mysql,
				$inp_browser_mysql, $inp_os_mysql, $inp_type_mysql, $inp_accpeted_language_mysql, $inp_language_mysql,
				0,  $inp_url_mysql, 0)") or die(mysqli_error($link));

			// Get this login attemt
			$query = "SELECT login_id FROM $t_users_logins WHERE login_user_id=$get_user_id AND login_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_login_id) = $row;


			// E-mail found, check if we can continue try to login or if we have used all login attemts
			if($get_user_login_tries > 5){
				// Can we reset it?
				// Get prev lost login attemt
				$query = "SELECT login_id, login_datetime FROM $t_users_logins WHERE login_user_id=$get_user_id ORDER BY login_id DESC LIMIT 1,1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_prev_login_id, $get_prev_login_datetime) = $row;


				$array = explode(" ", $get_prev_login_datetime);
				$time  = explode(":", $array[1]);
				$hour  = $time[0];
				$now   = date("H");
				if($hour == "$now"){
					// Update login attemt
					mysqli_query($link, "UPDATE $t_users_logins SET login_successfully=0, login_unsuccessfully_reason='Too many login attempts' WHERE login_id=$get_current_login_id") or die(mysqli_error($link));

					// Header
						$url = "login.php?l=$l";
					if(isset($r_action) && $r_action != ""){ $url = $url . "&r_action=$r_action"; } 
					$url = $url . "&referer=$inp_referer&ft=warning&fm=account_temporarily_banned_please_wait_one_hour_before_trying_again";
					header("Location: $url");
					exit;
				}
			}

			// Check password 
			$inp_password_encrypted = sha1($inp_password);

			// $test = "x5E3EMI";
			// $test_e = sha1($test);
			// echo"$inp_password_encrypted == $get_user_password<br />$test_e";
			// die;

			if($inp_password_encrypted == "$get_user_password"){
				// Correct password
				$host = $_SERVER['HTTP_HOST'];




				// I am approved?
				if($get_user_verified_by_moderator == "1"){

					// -> Cookie
					if($inp_remember == "on"){
						$salt = substr (md5($get_user_password), 0, 2);
						$cookie = base64_encode ("$get_user_id:" . md5 ($get_user_password, $salt));


						setcookie ('remember_user', $cookie, strtotime( '+10 months' ), '/', $host);
					}

					// Set security pin
					if($get_user_security == ""){
						$year = date("Y");
						$pin = rand(0,9999);
						$get_user_security = $year . $pin;
						$result = mysqli_query($link, "UPDATE $t_users SET user_security='$get_user_security' WHERE user_id='$get_user_id'") or die(mysqli_error($link));
					}
					else{
						// Check if pin is from this year, if not then generate a new one
						$year = date("Y");
						$len = strlen($get_user_security);
						if($len > 4){
							$check = substr($get_user_security, 0, 4);
						}
						else{
							$check = "1970";
						}
						if($year != "$check"){
							// Genereate new pin
							$year = date("Y");
							$pin = rand(0,9999);
							$get_user_security = $year . $pin;
							$result = mysqli_query($link, "UPDATE $t_users SET user_security='$get_user_security' WHERE user_id='$get_user_id'") or die(mysqli_error($link));
						}
					}


					// -> Logg brukeren inn
					$_SESSION['user_id'] = "$get_user_id";
					$_SESSION['security'] = "$get_user_security";
					$_SESSION['l'] = "$get_user_language";
					$user_last_ip = $_SERVER['REMOTE_ADDR'];
					$user_last_ip = output_html($user_last_ip);
					$user_last_ip_mysql = quote_smart($link, $user_last_ip);


					// Update login attemt
					mysqli_query($link, "UPDATE $t_users_logins SET login_successfully=1 WHERE login_id=$get_current_login_id") or die(mysqli_error($link));

					// Update last logged in
					$inp_user_last_online = date("Y-m-d H:i:s");
					$inp_user_last_online_time = time();
					$result = mysqli_query($link, "UPDATE $t_users SET 
									user_last_online='$inp_user_last_online', 
									user_last_online_time='$inp_user_last_online_time', 
									user_last_ip=$user_last_ip_mysql,
									user_login_tries=0 WHERE user_id='$get_user_id'");

				

					// Check if I am known
					$inp_fingerprint = $my_hostname . "|" . $get_my_country_name . "|" . $get_stats_user_agent_os . "|" . $get_stats_user_agent_browser  . "|" . $inp_accept_language;
					// $inp_fingerprint = md5($inp_fingerprint);
					$inp_fingerprint_mysql = quote_smart($link, $inp_fingerprint);

					$query = "SELECT known_device_id FROM $t_users_known_devices WHERE known_device_user_id=$get_user_id AND known_device_fingerprint=$inp_fingerprint_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_known_device_id) = $row;
					if($get_current_known_device_id == ""){
						// New device
						mysqli_query($link, "INSERT INTO $t_users_known_devices (known_device_id, known_device_user_id, known_device_fingerprint, known_device_created_datetime, known_device_created_datetime_saying, 
								known_device_updated_datetime, known_device_updated_datetime_saying, known_device_updated_year, known_device_created_ip, known_device_created_hostname, 
								known_device_last_ip, known_device_last_hostname, known_device_user_agent, known_device_country, known_device_browser, 
								known_device_os, known_device_os_icon, known_device_type, known_device_accepted_language, known_device_language, 
								known_device_last_url)
								VALUES(
								NULL, $get_user_id, $inp_fingerprint_mysql, '$datetime', '$datetime_saying',
								 '$datetime', '$datetime_saying', $year, $my_ip_mysql, $my_hostname_mysql, 
								$my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, $inp_country_mysql, $inp_browser_mysql, 
								$inp_os_mysql, $inp_os_icon_mysql, $inp_type_mysql, $inp_accpeted_language_mysql, $inp_language_mysql, $inp_url_mysql)") or die(mysqli_error($link));

						// Email to owner that there is a new login

						$subject = "$l_new_login_at $configWebsiteTitleSav $l_at_lowercase $datetime_saying";
			
						$message = "<html>\n";
						$message = $message. "<head>\n";
						$message = $message. "  <title>$subject</title>\n";
						$message = $message. " </head>\n";
						$message = $message. "<body>\n";

						$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
						$message = $message . "<h1>$l_new_login_at $configWebsiteTitleSav</h1>\n\n";
						$message = $message . "<p>$l_hi $get_user_name,<br /><br />\n";
						$message = $message . "$l_there_is_a_new_login_to_your_account.\n";
						$message = $message . "$l_if_you_dont_recognize_the_login_then_please_change_your_password_and_contact_us.\n";
						$message = $message . "$l_if_it_was_you_then_you_can_ignore_this_email.</p>\n";

						$message = $message . "<table>\n\n";

						$message = $message . " <tr>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span><b>$l_ip:</b></span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span>$my_ip</span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . " </tr>\n\n";

						$message = $message . " <tr>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span><b>$l_hostname:</b></span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span>$my_hostname</span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . " </tr>\n\n";

						$message = $message . " <tr>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span><b>$l_os:</b></span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span>$get_stats_user_agent_os</span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . " </tr>\n\n";


						$message = $message . " <tr>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span><b>$l_browser:</b></span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span>$get_stats_user_agent_browser</span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . " </tr>\n\n";


						$message = $message . " <tr>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span><b>$l_country:</b></span>\n";
						$message = $message . "  </td>\n\n";
						$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
						$message = $message . "     <span>$get_my_country_name</span>\n";
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
							mail($inp_email, $subject, $message, implode("\r\n", $headers));
						}
					}
					else{
						// Update last seen
						mysqli_query($link, "UPDATE $t_users_known_devices SET 
									known_device_updated_datetime='$datetime', 
									known_device_updated_datetime_saying='$datetime_saying',
									known_device_last_ip=$my_ip_mysql,
									known_device_last_hostname=$my_hostname_mysql
				  				   WHERE known_device_id=$get_current_known_device_id") or die(mysqli_error($link));
		
					}

					// Delete old logins (users_logins and users_known_devices)
					$one_year_ago = $year-1;
					$one_months_ago = $month-1;
					mysqli_query($link, "DELETE FROM $t_users_logins WHERE login_year < $year OR login_month < $one_months_ago") or die(mysqli_error($link));
					mysqli_query($link, "DELETE FROM $t_users_known_devices WHERE known_device_updated_year < $one_year_ago") or die(mysqli_error($link));


					// Refer?
					if(isset($_POST['inp_referer'])){
						$inp_referer = $_POST['inp_referer'];
						$inp_referer = stripslashes(strip_tags($inp_referer));
						$inp_referer = str_replace("&amp;", "&", $inp_referer);
						$inp_referer = str_replace("amp;", "&", $inp_referer);
						$url = "../$inp_referer";
					}
					else{
						$url = "my_profile.php?l=$get_user_language"; 
					}

					if($process == "1"){
						header("Location: $url");
					}
					else{
						echo"
						<table>
						 <tr> 
						  <td style=\"padding-right: 6px;vertical-align: top;\">
							<span>
							<img src=\"$root/users/_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
							</span>
						  </td>
						  <td>
							<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">$l_users_loading...</h1>
					 	 </td>
						 </tr>
						</table>

						<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
					}
					exit;
				}
				else{
					// Not approved yet
					$url = "create_free_account_awaiting_approvement.php?l=$l"; 
					if($process == "1"){
						header("Location: $url");
					}
					else{
						echo"
						<table>
						 <tr> 
						  <td style=\"padding-right: 6px;vertical-align: top;\">
							<span>
							<img src=\"$root/users/_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
							</span>
						  </td>
						  <td>
							<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">$l_users_loading...</h1>
					 	 </td>
						 </tr>
						</table>

						<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
					}
					exit;

				}
			}
			else{
				// Wrong password
				$inp_login_attempts = $get_user_login_tries+1;
				$input_registered_date 	= date("Y-m-d H:i:s");
				$input_registered_time 	= time();

				// Update login attemt
				mysqli_query($link, "UPDATE $t_users SET user_login_tries=$inp_login_attempts WHERE user_id=$get_user_id") or die(mysqli_error($link));
				mysqli_query($link, "UPDATE $t_users_logins SET login_successfully=0, login_unsuccessfully_reason='Wrong password' WHERE login_id=$get_current_login_id") or die(mysqli_error($link));


				if($inp_login_attempts > 5){

					// Email to owner that there are five login attempts
					$subject = "$l_unsuccessful_login_attempt_to_your_account_at $configWebsiteTitleSav $l_at_lowercase $datetime_saying";
			
					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
					$message = $message . "<h1>$l_unsuccessful_login_attempt_at $configWebsiteTitleSav</h1>\n\n";
					$message = $message . "<p>$l_hi $get_user_name,<br /><br />\n";
					$message = $message . "$l_this_email_is_a_warning_that_there_has_been_entered_wrong_password_for_your_account $inp_login_attempts $l_times_lowercase.\n";
					$message = $message . "$l_please_dont_hesitate_to_contact_us_if_you_have_any_questions.</p>\n";

					$message = $message . "<table>\n\n";

					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_ip:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$my_ip</span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . " </tr>\n\n";

					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_hostname:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$my_hostname</span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . " </tr>\n\n";

					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_os:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$get_stats_user_agent_os</span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . " </tr>\n\n";


					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_browser:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$get_stats_user_agent_browser</span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . " </tr>\n\n";


					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_country:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$get_my_country_name</span>\n";
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
						mail($inp_email, $subject, $message, implode("\r\n", $headers));
					}



					// Email to moderator of the week

					// Who is moderator of the week?
					$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
					if($get_moderator_user_id == ""){
						// Create moderator of the week
						include("../_functions/create_moderator_of_the_week.php");
				
						$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
					}

					$subject = "$l_unsuccessful_login_attempt_for $get_user_name $l_at_lowercase $configWebsiteTitleSav $l_at_lowercase $datetime_saying";
			
					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
					$message = $message . "<h1>$l_unsuccessful_login_attempt_at $configWebsiteTitleSav</h1>\n\n";
					$message = $message . "<p>$l_hi $get_moderator_user_name,<br /><br />\n";
					$message = $message . "$l_the_account_for_the_user $get_user_name $l_is_locked_for_one_hour_because_of_to_many_unsuccessful_login_attempts_lowercase.\n";
					$message = $message . "$l_this_email_contains_information_about_the_login_attempt.\n";
					$message = $message . "$l_you_can_ban_ip_hostname_and_user_agent_on_the_control_panel .</p>\n";

					$message = $message . "<table>\n\n";

					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_ip:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$my_ip</span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . " </tr>\n\n";

					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_hostname:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$my_hostname</span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . " </tr>\n\n";

					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_os:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$get_stats_user_agent_os</span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . " </tr>\n\n";


					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_browser:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$get_stats_user_agent_browser</span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . " </tr>\n\n";


					$message = $message . " <tr>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span><b>$l_country:</b></span>\n";
					$message = $message . "  </td>\n\n";
					$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
					$message = $message . "     <span>$get_my_country_name</span>\n";
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


				}

				// Header
				$url = "login.php?l=$l";
				if(isset($r_action) && $r_action != ""){ $url = $url . "&r_action=$r_action"; } 
				$url = $url . "&referer=$inp_referer&ft=warning&fm=wrong_password&inp_email=$inp_email";

				if($process == "1"){
					header("Location: $url");
				}
				else{
					echo"<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
				}
				exit;

			}
		}
	}
}
if($action == ""){
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		echo"

		<table>
		 <tr> 
		  <td style=\"padding-right: 6px;vertical-align: top;\">
			<span>
			<img src=\"$root/users/_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
			</span>
		  </td>
		  <td>
			<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">$l_users_loading...</h1>
		  </td>
		 </tr>
		</table>



		<p>$l_users_you_are_beeing_transfered_back.</p>

			
		<meta http-equiv=\"refresh\" content=\"1;url=index.php?l=$l\">
				
		";

	}
	else{
		echo"


		<div id=\"login_page\">
			<h1>$l_users_login</h1>
			<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "please_enter_your_email_address"){
					$fm = "$l_please_enter_your_email_address";
				}
				elseif($fm == "please_enter_your_password"){
					$fm = "$l_please_enter_your_password";
				}
				elseif($fm == "email_address_not_found"){
					$fm = "$l_email_address_not_found";
				}
				elseif($fm == "wrong_password"){
					$fm = "$l_wrong_password";
				}
				else{
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->

			<!-- Login / Create profile tabs -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"login.php?l=$l"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\" class=\"active\">$l_login</a>
						<li><a href=\"create_free_account.php?l=$l"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\">$l_registrer</a>
					</ul>
				</div>
				<div class=\"clear\" style=\"height: 20px;\"></div>
			<!-- //Login / Create profile tabs -->

			<form method=\"POST\" action=\"login.php?action=check&amp;process=1&amp;l=$l\" enctype=\"multipart/form-data\">


				<!-- Referer -->
				";

				echo"
				<!-- //Referer -->
				$l_email_address:<br /></span>
				<p>
				<input type=\"text\" name=\"inp_email\" size=\"30\" style=\"width: 240px;\" value=\""; 
				if(isset($_GET['inp_email'])) {
					$inp_email = $_GET['inp_email'];
					$inp_email = output_html($inp_email);
					$inp_email = strtolower($inp_email); echo"$inp_email"; 
				} echo"\" tabindex=\"1\" />
				</p>

				<div id=\"login_page_password_left\">
					<span>$l_password:</span>
				</div>
				<div id=\"login_page_password_right\">
					<span><a href=\"forgot_password.php?l=$l"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\">$l_forgot_password_question</a></span>
				</div>
				<div class=\"clear\"></div>
				<p>
				<input type=\"password\" name=\"inp_password\" size=\"30\" style=\"width: 240px;\" value=\""; if(isset($inp_password)){ echo"$inp_password"; } echo"\" tabindex=\"2\" /><br />
				<input type=\"hidden\" name=\"inp_referer\" value=\"$referer\" />
				</p>

				<p>
				<input type=\"checkbox\" name=\"inp_remember\" "; if(isset($inp_remember)){ if($inp_remember == "on"){ echo" checked=\"checked\""; } } else{ echo" checked=\"checked\""; } echo" />
				$l_remember_me<br />
				</p>


				<p>
				<input type=\"submit\" value=\"$l_login\" class=\"btn_default\" tabindex=\"3\" style=\"width: 240px;\" />
				</p>

				<p class=\"login_page_create_free_account\">
				<a href=\"create_free_account.php?l=$l"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\" class=\"btn_default\" style=\"width: 240px;\">$l_new_user &dash; $l_create_free_account</a>
				</p>

				</form>
		
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_email\"]').focus();
				});
				</script>
			<!-- //Focus -->


			

		</div> <!-- //login_page -->
		";
	}
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>