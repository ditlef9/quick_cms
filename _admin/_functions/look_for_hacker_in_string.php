<?php
/**
*
* File: _admin/_functions/look_for_hacker_in_string.php
* Version: 3
* Date: 10:28 25.04.2022
* Copyright (c) 2022 Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/* Usage:
* if(isset($_GET['l'])) {
*	$l = $_GET['l'];
*	// Look for hacker in string (this will ban user if hacker string is used more than 5 times)
*	$variable = "$l";
*	$is_numeric = false;
*	include("_functions/look_for_hacker_in_string.php");
	
*	$length = strlen($l);
*	if($length > 5){
*		echo"l lenght to long";
*		die;
*	}
*	$l = strip_tags(stripslashes($l));
* }
* 
* if a hacker is found in search_string then a email is sendt to moderator
* if the severity is high then the IP is banned
*/



// Is hacker?
$is_hacker = 0;
$is_hacker_reason = "";
$is_hacker_severity = ""; // Scale from low to high


// Trim and line space
$variable = trim($variable);



// SQL hacks check
$sql_hacks = array(
		'"', '")', '";', '`',
		'`)',
		'`;',
		"'", 
		"%'27", 
		'[t]', 
		"%'", 
		'"', 
		'/', 
		'//', 
		'\\', 
		';',
		"'='", 
		"'=0--+", 
		"AND",
		"or",
		"OR",
		"LIKE", 
		"-false", 
		"-true", 
		"ORDER BY",
		"WHERE", 
		"UNION"
		);

	
foreach($sql_hacks as $r){
	$position = stripos($variable, $r);
	if($position !== false ){
		if($is_hacker == "0"){
			$is_hacker = 1;
			$is_hacker_severity = "high";
			$r = str_replace('"', '&quot;', $r);
			$r = str_replace("'", '&apos;', $r);
			$is_hacker_reason  = "Used $r in variable $variable";
		}
	}
}


// Numeric check
if($is_numeric){
	if(!(is_numeric($variable))){
		if($is_hacker == "0"){
			$is_hacker = 1;
			$is_hacker_severity = "high";
			$is_hacker_reason  = "Used non-numeric input for variable $variable";
		}
	}
}


if($is_hacker == "1"){
	// Hacker count file
	if(!(isset($my_ip))){
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = strip_tags(stripslashes($my_ip));
		$my_ip = output_html($my_ip);
	}
	$my_ip_sum = md5("$my_ip");
	$count_file = "$root/_cache/look_for_hacker_$my_ip_sum.txt";
	$count_file = "$root/_cache/look_for_hacker_$my_ip_sum" . "_" . $is_hacker_severity . ".txt";


	// Open hacker count file.
	if(!(file_exists("$count_file"))){
		$myfile = fopen("$count_file", "w") or die("Unable to open file!");
		fwrite($myfile, "0");
		fclose($myfile);
	}
	else{
		// Read counter
		$fh = fopen("$count_file", "r");
		$counter = fread($fh, filesize("$count_file"));
		fclose($fh);

		$inp_counter = $counter+1;

		// Write new number
		$myfile = fopen("$count_file", "w") or die("Unable to open file!");
		fwrite($myfile, $inp_counter);
		fclose($myfile);

		// If counter is over 5 then send email
		if($counter == "5"){
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$datedatime_saying = date("j M Y H:i:s");

			// High severity? Then ban!
			if($is_hacker_severity == "high"){
				$inp_reason = "$is_hacker_reason &middot; Page URL was $page_url";
				$inp_reason = output_html($inp_reason);
				$inp_reason_mysql = quote_smart($link, $inp_reason);
				$my_ip_mysql	  = quote_smart($link, $my_ip);
				mysqli_query($link, "INSERT INTO $t_banned_ips
				(banned_ip_id, banned_ip, banned_ip_reason, banned_ip_datetime) 
				VALUES 
				(NULL, $my_ip_mysql, $inp_reason_mysql, '$datetime')")
				or die(mysqli_error($link));
			}


			// My user agent
			if(!(isset($my_user_agent))){
				$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
				$my_user_agent = output_html($my_user_agent);
			}

			// My accept language
			if(!(isset($my_accept_language))){
				if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
					$my_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
					$my_accept_language = output_html($my_accept_language);
					$my_accept_language = strtolower($my_accept_language);
				}
				else{
					$my_accept_language = "ZZ";
				}
			}

			// Logo
			include("$root/_admin/_data/logo.php");

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
				

			// Sum
			$my_ip_sum = md5("$my_ip");
			$my_user_agent_sum = md5("$my_user_agent");

			// Clean
			$is_hacker_reason = output_html($is_hacker_reason);

			// Write mail
			$subject = "Hacker attempt at $configWebsiteTitleSav the $datedatime_saying";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
			
			$message = $message . "<p>Dear $get_moderator_user_name,<br />\n";
			$message = $message . "</p>";
			$message = $message . "<p>\n";
			$message = $message . "You are the moderator of the week at $configWebsiteTitleSav and because of this you receive this email.\n";
			$message = $message . "</p>";
			$message = $message . "<p>There was a hacking attempt at $configWebsiteTitleSav.\n";
			$message = $message . "The user has searched for hacking words for more than four times.\n";
			$message = $message . "</p>";

			if($is_hacker_severity == "high"){
				$message = $message . "<p style=\"color: red;\">\n";
				$message = $message . "The IP is banned because the severity is high.\n";
				$message = $message . "</p>\n";
			}
			else{
				$message = $message . "<p style=\"color: green;\">\n";
				$message = $message . "The IP is not banned. You have to ban it manually because the severity is low.\n";
				$message = $message . "</p>\n";
			}
			$message = $message . "<p>\n";
			$message = $message . "You should take action to make sure that your site stays safe.\n";
			$message = $message . "No more emails will be sendt about this IP.\n";
			$message = $message . "</p>\n";
			$message = $message . "<p>\n";
			$message = $message . "To find more information look at the \n";
			$message = $message . "<a href=\"$configControlPanelURLSav/index.php?open=dashboard&page=statistics_year&stats_year=$year&editor_language=$l&amp;ip_sum=$my_ip_sum#trackers\">trackers</a>.";
			$message = $message . "This email was sent by the function look_for_hacker_in_string.\n";
			$message = $message . "</p>";


			$message = $message . "<table>\n\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>Is hacker reason:</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>$is_hacker_reason</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "  </td>\n";
			$message = $message . " </tr>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>IP:</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>$my_ip</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>[<a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=banned&amp;action=add_new_banned_ip\">Ban</a>]</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . " </tr>\n";


			$message = $message . " <tr>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>User agent:</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>$my_user_agent</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>[<a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=user_agents&amp;mode=ban_hostname&amp;user_agent_sum=$my_user_agent_sum\">Ban</a>]</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . " </tr>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>Accpeted language:</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>$my_accept_language</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "  </td>\n";
			$message = $message . " </tr>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>Language:</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>$l</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "  </td>\n";
			$message = $message . " </tr>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>Datetime:</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>$datedatime_saying</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "  </td>\n";
			$message = $message . " </tr>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>URL:</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . "  <td style=\"padding: 4px 4px 4px 0px;\">\n";
			$message = $message . "		<span>$page_url</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . " </tr>\n";
			$message = $message . "</table>\n\n";

			$message = $message . "<p>\n\n--<br />\nSincerely yours<br />\n$configWebsiteTitleSav<br />\n<a href=\"$configSiteURLSav\">$configSiteURLSav</a></p>";
			$message = $message. "</body>\n";
			$message = $message. "</html>\n";

			// Preferences for Subject field
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=utf-8';
			$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
			if($configMailSendActiveSav == "1"){
				mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers));
			}
		} // hacker count == 5
	} // hacker file exists
} // user may be hacker


?>