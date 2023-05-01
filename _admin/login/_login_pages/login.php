<?php
// Language
include("../_translations/site/en/users/ts_login.php");


if($process == "1"){


	// Variables
	if(isset($_POST['inp_email'])) {
		$inp_email = $_POST['inp_email'];
		$inp_email = output_html($inp_email);
		$inp_email = strtolower($inp_email);
		if(empty($inp_email)){
			header("Location: index.php?ft=error&fm=please_enter_your_email&l=$l");
			exit;
		}
		$inp_email_mysql = quote_smart($link, $inp_email);

		// Validate email
		// if (!filter_var($inp_email, FILTER_VALIDATE_EMAIL)) {
		//	header("Location: index.php?ft=error&fm=invalid_email_format");
		//	exit;
		// }
	}
	else{
		header("Location: index.php?ft=error&fm=please_enter_your_email&l=$l");
		exit;
	}
	if(isset($_POST['inp_password'])) {
		$inp_password = $_POST['inp_password'];
		$inp_password = output_html($inp_password);
		if(empty($inp_password)){
			header("Location: index.php?ft=error&fm=please_enter_your_password&l=$l");
			exit;
		}
	}
	else{
		header("Location: index.php?ft=error&fm=please_enter_your_password&l=$l");
		exit;
	}


	// We got mail and password, look for user
	$query = "SELECT user_id, user_name, user_password, user_salt, user_security, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_email=$inp_email_mysql OR user_name=$inp_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_last_online, $get_user_rank, $get_user_login_tries) = $row;

	if($get_user_id == ""){
		header("Location: index.php?ft=error&fm=unknown_email_address&l=$l");
		exit;
	}
	
	// Dates
	$datetime = date("Y-m-d H:i:s");
	$datetime_saying = date("j.M Y H:i");
	$year = date("Y");
	$month = date("m");
	$week = date("W");


	// Country :: Find my country based on IP
	$ip_type = "";
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

	$inp_browser_mysql = quote_smart($link, $get_stats_user_agent_browser);

	$inp_os_mysql = quote_smart($link, $get_stats_user_agent_os);

	$inp_os_icon = clean($get_stats_user_agent_os);
	$inp_os_icon = $inp_os_icon . "_32x32.png";
	$inp_os_icon_mysql = quote_smart($link, $inp_os_icon);
	
	$inp_type_mysql = quote_smart($link, $get_stats_user_agent_type);

	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
		$inp_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$inp_accept_language = output_html($inp_accept_language);
		$inp_accept_language = strtolower($inp_accept_language);
	}
	else{
		$inp_accept_language = "ZZ";
	}
	$inp_accpeted_language = substr("$inp_accept_language", 0,2);
	$inp_accpeted_language_mysql = quote_smart($link, $inp_accpeted_language);

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

	// E-mail found
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
			header("Location: index.php?ft=warning&fm=account_temporarily_banned_please_wait_one_hour_before_trying_again&inp_mail=$inp_mail&l=$l");
			exit;
		}
	}
		
	// Password
	$inp_password_encrypted = sha1($inp_password);

	if($inp_password_encrypted != "$get_user_password"){
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
			$message = $message . "     <span>$get_stats_user_agent_os $get_stats_user_agent_os_version</span>\n";
			$message = $message . "  </td>\n\n";
			$message = $message . " </tr>\n\n";


			$message = $message . " <tr>\n\n";
			$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
			$message = $message . "     <span><b>$l_browser:</b></span>\n";
			$message = $message . "  </td>\n\n";
			$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
			$message = $message . "     <span>$get_stats_user_agent_browser $get_stats_user_agent_browser_version</span>\n";
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
			$message = $message . "     <span>$get_stats_user_agent_os $get_stats_user_agent_os_version</span>\n";
			$message = $message . "  </td>\n\n";
			$message = $message . " </tr>\n\n";


			$message = $message . " <tr>\n\n";
			$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
			$message = $message . "     <span><b>$l_browser:</b></span>\n";
			$message = $message . "  </td>\n\n";
			$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
			$message = $message . "     <span>$get_stats_user_agent_browser $get_stats_user_agent_browser_version</span>\n";
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
		$url = "index.php?ft=error&fm=wrong_password_please_enter_your_password&l=$l";
		if(isset($inp_email)){
			$url = $url . "&inp_email=$inp_email";
		}
		header("Location: index.php?ft=error&fm=wrong_password_please_enter_your_password&inp_email=$inp_email&l=$l");
		exit;
	}

	// Rank	
	if($get_user_rank == "admin" OR $get_user_rank == "moderator"){
		// Access OK!
	}
	else{
		// Update login attemt
		mysqli_query($link, "UPDATE $t_users_logins SET login_successfully=0, login_unsuccessfully_reason='Access to admin denied' WHERE login_id=$get_current_login_id") or die(mysqli_error($link));

		header("Location: index.php?ft=warning&fm=access_denied_please_contact_administrator&inp_mail=$inp_mail&l=$l");
		exit;
	}
				
	// Login success
	$input_registered_date 	= date("Y-m-d H:i:s");
	$input_registered_time 	= time();
	$inp_ip			= $_SERVER['REMOTE_ADDR'];
	if($configSiteUseGethostbyaddrSav == "1"){
		$inp_host_by_addr = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	}
	else{
		$inp_host_by_addr = "";
	}

	// Add session
	$_SESSION['admin_user_id']  = "$get_user_id";
	$_SESSION['admin_security'] = "$get_user_security";
	$_SESSION['user_id'] = "$get_user_id";
	$_SESSION['security'] = "$get_user_security";
	

	// Update login attemt
	mysqli_query($link, "UPDATE $t_users_logins SET login_successfully=1 WHERE login_id=$get_current_login_id") or die(mysqli_error($link));

	// Check if I am known
	$inp_fingerprint = $my_hostname . "|" . $get_my_country_name . "|" . $get_stats_user_agent_os . "|" . $get_stats_user_agent_browser . "|" . $inp_accpeted_language;
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
				known_device_last_ip, known_device_last_hostname, known_device_user_agent, known_device_country, known_device_browser, known_device_os, known_device_os_icon, known_device_type, 
				known_device_accepted_language, known_device_language, known_device_last_url)
				VALUES(
				NULL, $get_user_id, $inp_fingerprint_mysql, '$datetime', '$datetime_saying',
				 '$datetime', '$datetime_saying', $year, $my_ip_mysql, $my_hostname_mysql, 
				$my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, $inp_country_mysql, $inp_browser_mysql, $inp_os_mysql, $inp_os_icon_mysql, $inp_type_mysql,
				$inp_accpeted_language_mysql, $inp_language_mysql, $inp_url_mysql)") or die(mysqli_error($link));

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
		$message = $message . "     <span>$get_stats_user_agent_os $get_stats_user_agent_os_version</span>\n";
		$message = $message . "  </td>\n\n";
		$message = $message . " </tr>\n\n";


		$message = $message . " <tr>\n\n";
		$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
		$message = $message . "     <span><b>$l_browser:</b></span>\n";
		$message = $message . "  </td>\n\n";
		$message = $message . "  <td style=\"padding-right: 4px;\">\n\n";
		$message = $message . "     <span>$get_stats_user_agent_browser $get_stats_user_agent_browser_version</span>\n";
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


	// Update login attemts
	mysqli_query($link, "UPDATE $t_users SET user_login_tries=0 WHERE user_id=$get_user_id") or die(mysqli_error($link));


	// Delete old logins (users_logins and users_known_devices)
	$one_year_ago = $year-1;
	$one_months_ago = $month-1;
	mysqli_query($link, "DELETE FROM $t_users_logins WHERE login_year < $year OR login_month < $one_months_ago") or die(mysqli_error($link));
	mysqli_query($link, "DELETE FROM $t_users_known_devices WHERE known_device_updated_year < $one_year_ago") or die(mysqli_error($link));


	// Move to admin-panel
	header("Location: ../_liquidbase/liquidbase.php?l=$l");
	exit;
}

// Language
if($l == ""){
	if(isset($_SESSION['l'])){
		$l = $_SESSION['l'];
	}
	else{
		$l = "en";
	}
}

echo"


<h1>$l_login</h1>

<!-- Administrator form -->

	<form method=\"post\" action=\"index.php?page=login&amp;process=1&amp;l=$l\" enctype=\"multipart/form-data\">

	<!-- Error -->
		";
		if(isset($ft) && isset($fm)){
		if($fm == "please_enter_your_email"){
			$fm = "$l_please_enter_your_email";
		}
		elseif($fm == "invalid_email_format"){
			$fm = "$l_invalid_email_format";
		}
		elseif($fm == "unknown_email_address"){
			$fm = "$l_unknown_email_address";
		}
		elseif($fm == "please_enter_your_password"){
			$fm = "$l_please_enter_your_password";
		}
		elseif($fm == "account_temporarily_banned_please_wait_one_hour_before_trying_again"){
			$fm = "$l_account_temporarily_banned_please_wait_one_hour_before_trying_again";
		}
		elseif($fm == "access_denied_please_contact_administrator"){
			$fm = "$l_access_denied_please_contact_administrator";
		}
		elseif($fm == "wrong_password_please_enter_your_password"){
			$fm = "$l_wrong_password_please_enter_your_password";
		}
		elseif($fm == "please_enter_your_password"){
			$fm = "$l_please_enter_your_password";
		}
		elseif($fm == "please_login_to_the_control_panel"){
			$fm = "$l_please_log_in_to_view_the_control_panel";
		}
		elseif($fm == "check_your_email"){
			$fm = "$l_check_your_email";
		}
		elseif($fm == "wrong_key"){
			$fm = "$l_wrong_key";
		}
		elseif($fm == "user_not_found"){
			$fm = "$l_user_not_found";
		}
		else{
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"
	<!-- //Error -->


	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_email\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<p>$l_email:<br />
	<input type=\"text\" name=\"inp_email\" value=\""; if(isset($inp_email)){ echo"$inp_email"; } echo"\" size=\"25\" style=\"width: 80%;\" tabindex=\"1\" class=\"inp_email\" />
	</p>


	<p>$l_password:<br />
	<input type=\"password\" name=\"inp_password\" value=\"\" size=\"25\" style=\"width: 80%;\" tabindex=\"2\" class=\"inp_password\" />
	</p>

	<p>
	<input type=\"submit\" value=\"$l_login\" class=\"inp_submit\" tabindex=\"3\" />
	</p>

	</form>

<!-- //Administrator form -->

<!-- Main Menu -->
	<p>
	<a href=\"index.php?page=forgot_password\">$l_forgot_password</a>
	</p>
<!-- //Main Menu -->

<!-- Stats -->
	<iframe src=\"process_stats_iframe.php\" width=\"200\" height=\"10\"></iframe>
<!-- //Stats -->

";
?>
