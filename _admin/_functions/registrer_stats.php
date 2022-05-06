<?php
/**
*
* File: _admin/_functions/registrer_stats.php
* Version 2.0.0
* Date 09:46 15.10.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Tables ---------------------------------------------------------------------------------- */
$t_languages_active	      = $mysqlPrefixSav . "languages_active";
$t_languages_countries	      = $mysqlPrefixSav . "languages_countries";

$t_stats_unprocessed 	= $mysqlPrefixSav . "stats_unprocessed";
$t_stats_tracker_index 	= $mysqlPrefixSav . "stats_tracker_index";
$t_stats_tracker_urls	= $mysqlPrefixSav . "stats_tracker_urls";

/*- Dates ---------------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$year = date("Y");
$month = date("m");
$day = date("d");
$week = date("W");
$time = time();
$hour_minute = date("H:i");

/*- Find me based on user ------------------------------------------------------------------- */
$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
$my_user_agent = output_html($my_user_agent);
if($my_user_agent == ""){ echo"406 Not Acceptable"; die; }
$my_user_agent_mysql = quote_smart($link, $my_user_agent);

$my_ip = $_SERVER['REMOTE_ADDR'];
$my_ip = output_html($my_ip);
$my_ip_mysql = quote_smart($link, $my_ip);

$my_ip_masked = md5($my_ip);
$my_ip_masked_mysql = quote_smart($link, $my_ip_masked);

// Accept language
if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	$inp_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$inp_accept_language = output_html($inp_accept_language);
	$inp_accept_language = strtolower($inp_accept_language);
}
else{
	$inp_accept_language = "ZZ";
}
$inp_accept_language_mysql = quote_smart($link, $inp_accept_language);

// Language
$inp_language = "";
if(isset($l)){
	$inp_language = "$l";
}
else{
	if(isset($_GET['l'])){
		$inp_language = $_GET['l'];
	}
}
$inp_language = output_html($inp_language);
$inp_language_mysql = quote_smart($link, $inp_language);

$page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8');
$page_url = output_html($page_url);
$inp_page_url_mysql = quote_smart($link, $page_url);

$inp_request_uri = $_SERVER['REQUEST_URI'];
$inp_request_uri = output_html($inp_request_uri);
$inp_request_uri_len = strlen($inp_request_uri);
if($inp_request_uri_len > 200){
	$inp_request_uri = substr($inp_request_uri, 0, 200);
	$inp_request_uri = $inp_request_uri . "...";
}
$inp_request_uri_mysql = quote_smart($link, $inp_request_uri);

// Referer
$inp_referer = "";
if(isset($_SERVER['HTTP_REFERER']) ){
	$inp_referer = $_SERVER['HTTP_REFERER'];
	$inp_referer = output_html($inp_referer);
	$inp_referer_len = strlen($inp_referer);
	if($inp_referer_len > 200){
		$inp_referer = substr($inp_referer, 0, 200);
	}
}
$inp_referer_mysql = quote_smart($link, $inp_referer);

// Check if banned IP
$query = "SELECT banned_ip_id FROM $t_banned_ips WHERE banned_ip=$my_ip_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_banned_ip_id) = $row;
if($get_banned_ip_id != ""){
	echo"Server error 403 - Your IP is banned";	
	die;
}

// Unprosessed : Find me
$query = "SELECT unprocessed_id, unprocessed_hits FROM $t_stats_unprocessed WHERE unprocessed_year=$year AND unprocessed_month=$month AND unprocessed_day=$day AND unprocessed_ip=$my_ip_mysql AND unprocessed_user_agent=$my_user_agent_mysql AND unprocessed_language=$inp_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_unprocessed_id, $get_current_unprocessed_hits) = $row;
if($get_current_unprocessed_id == ""){


	// Create new visitor
	mysqli_query($link, "INSERT INTO $t_stats_unprocessed
	(unprocessed_id, unprocessed_first_datetime, unprocessed_last_datetime, unprocessed_year, unprocessed_month, 
	unprocessed_day, unprocessed_week, unprocessed_ip, unprocessed_user_agent, unprocessed_accept_language, 
	unprocessed_language, unprocessed_first_request_uri, unprocessed_last_request_uri, unprocessed_first_referer, 
	unprocessed_last_referer, unprocessed_hits) 
	VALUES
	(NULL, '$datetime', '$datetime', $year, $month, 
	$day, $week, $my_ip_mysql, $my_user_agent_mysql, $inp_accept_language_mysql, 
	$inp_language_mysql, $inp_request_uri_mysql,'', $inp_referer_mysql, '', 
	1)") or die(mysqli_error($link));

	// Get my ID
	$query = "SELECT unprocessed_id, unprocessed_hits FROM $t_stats_unprocessed WHERE unprocessed_year=$year AND unprocessed_month=$month AND unprocessed_day=$day AND unprocessed_ip=$my_ip_mysql AND unprocessed_user_agent=$my_user_agent_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_unprocessed_id, $get_current_unprocessed_hits) = $row;



}
else{
	// Update visitor
	$inp_unprocessed_hits = $get_current_unprocessed_hits+1;
	mysqli_query($link, "UPDATE $t_stats_unprocessed SET 
				unprocessed_last_datetime='$datetime',
				unprocessed_last_request_uri=$inp_request_uri_mysql,
				unprocessed_last_referer=$inp_referer_mysql, 
				unprocessed_hits=$inp_unprocessed_hits 
				WHERE unprocessed_id=$get_current_unprocessed_id") or die(mysqli_error($link));
}

// Tracker
$query = "SELECT tracker_id, tracker_hits FROM $t_stats_tracker_index WHERE tracker_last_month=$month AND tracker_last_year=$year AND tracker_ip=$my_ip_mysql AND tracker_user_agent=$my_user_agent_mysql AND tracker_accept_language=$inp_accept_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_tracker_id, $get_current_tracker_hits) = $row;
if($get_current_tracker_id == ""){


	mysqli_query($link, "INSERT INTO $t_stats_tracker_index 
	(tracker_id, tracker_ip, tracker_ip_masked, tracker_hostname, tracker_start_day, 
	tracker_start_month, tracker_start_month_short, tracker_start_year, tracker_start_time, tracker_start_hour_minute, 
	tracker_last_day, tracker_last_month, tracker_last_month_short, tracker_last_year, tracker_last_time, 
	tracker_last_hour_minute, tracker_seconds_spent, tracker_time_spent, tracker_user_agent, tracker_os, 
	tracker_browser, tracker_type, tracker_country_name, tracker_accept_language, tracker_language, 
	tracker_last_url_value, tracker_last_url_title, tracker_last_url_title_fetched, tracker_hits) 
	VALUES
	(NULL, $my_ip_mysql, $my_ip_masked_mysql, -1, $day, 
	$month, -1, $year, $time, '$hour_minute', 
	$day, $month, -1, $year, $time, 
	'$hour_minute', 0, 0, $my_user_agent_mysql, -1,
	-1, -1, -1, $inp_accept_language_mysql, $inp_language_mysql, 
	$inp_page_url_mysql, '-1', 0, 1)") or die(mysqli_error($link));

	// Get tracker ID
	$query = "SELECT tracker_id, tracker_hits FROM $t_stats_tracker_index WHERE tracker_last_month=$month AND tracker_last_year=$year AND tracker_ip=$my_ip_mysql AND tracker_user_agent=$my_user_agent_mysql AND tracker_accept_language=$inp_accept_language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_tracker_id) = $row;
}
else{
	// Update tracker
	$inp_hits = $get_current_tracker_hits+1;
	mysqli_query($link, "UPDATE $t_stats_tracker_index SET 
				tracker_last_day='$day',
				tracker_last_url_value=$inp_page_url_mysql, 
				tracker_last_url_title=-1,
				tracker_last_url_title_fetched=0,
				tracker_hits=$inp_hits WHERE tracker_id='$get_current_tracker_id'") or die(mysqli_error($link));
}

// Insert tracker page
mysqli_query($link, "INSERT INTO $t_stats_tracker_urls 
(url_id, url_tracker_id, url_value, url_title, url_title_fetched, 
url_day, url_month, url_month_short, url_year, url_time_start, 
url_hour_minute_start, url_time_end, url_hour_minute_end, url_seconds_spent, 
url_time_spent) 
VALUES
(NULL, $get_current_tracker_id, $inp_page_url_mysql, '', 0, 
$day,  $month, '-1', $year, $time, 
'$hour_minute', 0, -1, -1, 
-1)") or die(mysqli_error($link));


?>