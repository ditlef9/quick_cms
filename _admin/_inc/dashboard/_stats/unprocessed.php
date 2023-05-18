<?php
/**
*
* File: _admin/_inc/dashboard/_stats/process_unprocessed.php
* Version 1
* Date 14.05.2023
* Copyright (c) 2021-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Variables - */
$time = time();
$hour_minute = date("H:i");
$test = 0;

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/get_between.php");

/*- Tables ---------------------------------------------------------------------------- */

$t_stats_unprocessed 	   		= $mysqlPrefixSav . "stats_unprocessed";
$t_stats_accepted_languages_per_month	= $mysqlPrefixSav . "stats_accepted_languages_per_month";
$t_stats_accepted_languages_per_year	= $mysqlPrefixSav . "stats_accepted_languages_per_year";

$t_stats_browsers_per_month	= $mysqlPrefixSav . "stats_browsers_per_month";
$t_stats_browsers_per_year	= $mysqlPrefixSav . "stats_browsers_per_year";

$t_stats_comments_per_month 	= $mysqlPrefixSav . "stats_comments_per_month";
$t_stats_comments_per_year 	= $mysqlPrefixSav . "stats_comments_per_year";

$t_stats_countries_per_year  = $mysqlPrefixSav . "stats_countries_per_year";
$t_stats_countries_per_month = $mysqlPrefixSav . "stats_countries_per_month";

$t_stats_ip_to_country_lookup_ipv4 = $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv4";
$t_stats_ip_to_country_lookup_ipv6 = $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv6";

$t_languages_countries	      = $mysqlPrefixSav . "languages_countries";

$t_stats_languages_per_year	= $mysqlPrefixSav . "stats_languages_per_year";
$t_stats_languages_per_month	= $mysqlPrefixSav . "stats_languages_per_month";

$t_stats_os_per_month = $mysqlPrefixSav . "stats_os_per_month";
$t_stats_os_per_year = $mysqlPrefixSav . "stats_os_per_year";

$t_stats_referers_per_year  = $mysqlPrefixSav . "stats_referers_per_year";
$t_stats_referers_per_month = $mysqlPrefixSav . "stats_referers_per_month";

$t_stats_user_agents_index = $mysqlPrefixSav . "stats_user_agents_index";

$t_stats_users_registered_per_month = $mysqlPrefixSav . "stats_users_registered_per_month";
$t_stats_users_registered_per_year = $mysqlPrefixSav . "stats_users_registered_per_year";

$t_stats_bots_per_month	= $mysqlPrefixSav . "stats_bots_per_month";
$t_stats_bots_per_year	= $mysqlPrefixSav . "stats_bots_per_year";

$t_stats_pages_visits_per_year		= $mysqlPrefixSav . "stats_pages_visits_per_year";
$t_stats_pages_visits_per_year_ips 	= $mysqlPrefixSav . "stats_pages_visits_per_year_ips";

$t_stats_visists_per_day 	= $mysqlPrefixSav . "stats_visists_per_day";
$t_stats_visists_per_day_ips 	= $mysqlPrefixSav . "stats_visists_per_day_ips";
$t_stats_visists_per_week 	= $mysqlPrefixSav . "stats_visists_per_week";
$t_stats_visists_per_week_ips 	= $mysqlPrefixSav . "stats_visists_per_week_ips";
$t_stats_visists_per_month 	= $mysqlPrefixSav . "stats_visists_per_month";
$t_stats_visists_per_month_ips 	= $mysqlPrefixSav . "stats_visists_per_month_ips";
$t_stats_visists_per_year 	= $mysqlPrefixSav . "stats_visists_per_year";
$t_stats_visists_per_year_ips 	= $mysqlPrefixSav . "stats_visists_per_year_ips";

$t_stats_tracker_index = $mysqlPrefixSav . "stats_tracker_index";
$t_stats_tracker_urls  = $mysqlPrefixSav . "stats_tracker_urls";

$t_languages_active  = $mysqlPrefixSav . "languages_active";


// Unprocessed
$query_u = "SELECT unprocessed_id, unprocessed_first_datetime, unprocessed_last_datetime, unprocessed_year, unprocessed_month, unprocessed_day, unprocessed_week, unprocessed_ip, unprocessed_user_agent, unprocessed_accept_language, unprocessed_language, unprocessed_first_request_uri, unprocessed_last_request_uri, unprocessed_first_referer, unprocessed_last_referer, unprocessed_hits FROM $t_stats_unprocessed";
$result_u = $mysqli->query($query_u);
while($row_u = $result_u->fetch_row()) {
	list($get_unprocessed_id, $get_unprocessed_first_datetime, $get_unprocessed_last_datetime, $get_unprocessed_year, $get_unprocessed_month, $get_unprocessed_day, $get_unprocessed_week, $get_unprocessed_ip, $get_unprocessed_user_agent, $get_unprocessed_accept_language, $get_unprocessed_language, $get_unprocessed_first_request_uri, $get_unprocessed_last_request_uri, $get_unprocessed_first_referer, $get_unprocessed_last_referer, $get_unprocessed_hits) = $row_u;


	// Hostname
	$my_hostname = "$get_unprocessed_ip";
	if($configSiteUseGethostbyaddrSav == "1"){
		$my_hostname = gethostbyaddr($get_unprocessed_ip); // Some servers in local network cant use getostbyaddr because of nameserver missing
	}
	$my_hostname = output_html($my_hostname);

	// IP
	$my_ip = "$get_unprocessed_ip";

	// Find user agent. By looking for user agent we can know if it is human or bot
	if($get_unprocessed_user_agent == ""){
		echo"<p style=\"color: red;\"><b>Warning:</b> unprocessed.php: my_user_agent is blank. Deleting.<br /><b>Data:</b>
		$get_unprocessed_id, $get_unprocessed_first_datetime, $get_unprocessed_last_datetime, $get_unprocessed_year, $get_unprocessed_month, $get_unprocessed_day, $get_unprocessed_week, $get_unprocessed_ip, $get_unprocessed_user_agent, $get_unprocessed_accept_language, $get_unprocessed_language, $get_unprocessed_first_request_uri, $get_unprocessed_last_request_uri, $get_unprocessed_first_referer, $get_unprocessed_last_referer, $get_unprocessed_hits</p>\n";
		if ($mysqli->query("DELETE FROM $t_stats_unprocessed WHERE unprocessed_id=$get_unprocessed_id") !== TRUE) {
			echo "Error MySQLi delete: " . $mysqli->error; die;
		}
	
	}
	

	$stmt = $mysqli->prepare("SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index WHERE stats_user_agent_string=?"); 
	$stmt->bind_param("s", $get_unprocessed_user_agent);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_stats_user_agent_id, $get_stats_user_agent_string, $get_stats_user_agent_type, $get_stats_user_agent_browser, $get_stats_user_agent_browser_version, $get_stats_user_agent_browser_icon, $get_stats_user_agent_os, $get_stats_user_agent_os_version, $get_stats_user_agent_os_icon, $get_stats_user_agent_bot, $get_stats_user_agent_bot_icon, $get_stats_user_agent_bot_website, $get_stats_user_agent_banned) = $row;

	if($get_stats_user_agent_id == ""){
		include("_inc/dashboard/_stats/autoinsert_new_user_agent.php");

		$stmt = $mysqli->prepare("SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index WHERE stats_user_agent_string=?"); 
		$stmt->bind_param("s", $get_unprocessed_user_agent);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_user_agent_id, $get_stats_user_agent_string, $get_stats_user_agent_type, $get_stats_user_agent_browser, $get_stats_user_agent_browser_version, $get_stats_user_agent_browser_icon, $get_stats_user_agent_os, $get_stats_user_agent_os_version, $get_stats_user_agent_os_icon, $get_stats_user_agent_bot, $get_stats_user_agent_bot_icon, $get_stats_user_agent_bot_website, $get_stats_user_agent_banned) = $row;
		if($get_stats_user_agent_id == ""){
			echo"<p><span style=\"color:red;\">Error inserting new user agent!</span><br />
			<span>Algorithm in registrer_stats_autoinsert_new_user_agent.php failed</span><br />
			<b>Unprocessed user agent:</b> $get_unprocessed_user_agent<br />
			<b>Unprocessed IP:</b> $get_unprocessed_ip<br />
			<b>Unprocessed hostname:</b> $my_hostname<br />
			<b>Visitor type:</b> $visitor_type<br />
			Setting test=1 and trying again (for debugging)
			</p>";
			$test = 1;
			include("_inc/dashboard/_stats/autoinsert_new_user_agent.php");
			die;
		}
	}

	// User agent type
	$inp_user_agent_type = $get_stats_user_agent_type;

	// OS
	$inp_os = $get_stats_user_agent_os;

	// Browser
	$inp_browser = $get_stats_user_agent_browser;

	// Accept
	$inp_accept_language_array = explode(";", $get_unprocessed_accept_language);
	$inp_accept_language = $inp_accept_language_array[0];

	// Accept language preffered
	$inp_accept_language_array = explode(";", $get_unprocessed_accept_language);
	$inp_accept_language_preffered = $inp_accept_language_array[0];

	$inp_accept_language_preffered_array = explode(",", $inp_accept_language_preffered);
	$inp_accept_language_preffered = $inp_accept_language_preffered_array[0];
	$inp_accept_language = $inp_accept_language_preffered;

	// Language
	$inp_language = $get_unprocessed_language;

	// Active language
	$stmt = $mysqli->prepare("SELECT language_active_id, language_active_name, language_active_slug, language_active_native_name, language_active_iso_two, language_active_iso_three, language_active_iso_four, language_active_iso_two_alt_a, language_active_iso_two_alt_b, language_active_flag_path_16x16, language_active_flag_active_16x16 FROM $t_languages_active WHERE language_active_iso_two=?"); 
	$stmt->bind_param("s", $inp_language);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_language_active_id, $get_language_active_name, $get_language_active_slug, $get_language_active_native_name, $get_language_active_iso_two, $get_language_active_iso_three, $get_language_active_iso_four, $get_language_active_iso_two_alt_a, $get_language_active_iso_two_alt_b, $get_language_active_flag_path_16x16, $get_language_active_flag_active_16x16) = $row;

	// Request uri
	$inp_request_uri = $get_unprocessed_first_request_uri;

	// Month full and month short
	if($get_unprocessed_month == "01" OR $get_unprocessed_month == "1"){
		$inp_month_full = "January";
		$inp_month_short = "Jan";
	}
	elseif($get_unprocessed_month == "02" OR $get_unprocessed_month == "2"){
		$inp_month_full = "February";
		$inp_month_short = "Feb";
	}
	elseif($get_unprocessed_month == "03" OR $get_unprocessed_month == "3"){
		$inp_month_full = "March";
		$inp_month_short = "Mar";
	}
	elseif($get_unprocessed_month == "04" OR $get_unprocessed_month == "4"){
		$inp_month_full = "April";
		$inp_month_short = "Apr";
	}
	elseif($get_unprocessed_month == "05" OR $get_unprocessed_month == "5"){
		$inp_month_full = "May";
		$inp_month_short = "May";
	}
	elseif($get_unprocessed_month == "06" OR $get_unprocessed_month == "6"){
		$inp_month_full = "June";
		$inp_month_short = "Jun";
	}
	elseif($get_unprocessed_month == "07" OR $get_unprocessed_month == "7"){
		$inp_month_full = "July";
		$inp_month_short = "Jul";
	}
	elseif($get_unprocessed_month == "08" OR $get_unprocessed_month == "8"){
		$inp_month_full = "August";
		$inp_month_short = "Aug";
	}
	elseif($get_unprocessed_month == "09" OR $get_unprocessed_month == "9"){
		$inp_month_full = "September";
		$inp_month_short = "Sep";
	}
	elseif($get_unprocessed_month == "10"){
		$inp_month_full = "October";
		$inp_month_short = "Oct";
	}
	elseif($get_unprocessed_month == "11"){
		$inp_month_full = "November";
		$inp_month_short = "Nov";
	}
	elseif($get_unprocessed_month == "12"){
		$inp_month_full = "December";
		$inp_month_short = "Dec";
	}

	// Day
	$inp_day_full = date('l', strtotime($get_unprocessed_last_datetime));
	$inp_day_short = date('D', strtotime($get_unprocessed_last_datetime));
	$inp_day_single = substr($inp_day_short, 0, 1);

	// Visits per year
	include("$root/_admin/_inc/dashboard/_stats/visists_per_year.php");

	// Visits per month
	include("$root/_admin/_inc/dashboard/_stats/visists_per_month.php");

	// Visits per week
	include("$root/_admin/_inc/dashboard/_stats/visists_per_week.php");

	// Visits per day
	include("$root/_admin/_inc/dashboard/_stats/visists_per_day.php");

	// Bot or Human
	if($get_stats_user_agent_type == "bot"){
		include("$root/_admin/_inc/dashboard/_stats/bot.php");
	}
	elseif($get_stats_user_agent_type == "desktop" OR $get_stats_user_agent_type == "mobile"){
		include("$root/_admin/_inc/dashboard/_stats/desktop_or_mobile.php");
	}
	/*
	echo"
	<p>Working with $get_unprocessed_id</p>
	";
	*/


	// Delete
	if ($mysqli->query("DELETE FROM $t_stats_unprocessed WHERE unprocessed_id=$get_unprocessed_id") !== TRUE) {
		echo "Error MySQLi delete: " . $mysqli->error; die;
	}

}


?>