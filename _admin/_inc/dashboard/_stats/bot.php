<?php
/**
*
* File: _admin/_inc/dashboard/_stats/bot.php
* Version 1
* Date 09:55 28.11.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Visists :: Year :: IPs
$query = "SELECT stats_visit_per_year_ip_id, stats_visit_per_year_ip_year, stats_visit_per_year_type, stats_visit_per_year_ip FROM $t_stats_visists_per_year_ips WHERE stats_visit_per_year_ip_year='$get_unprocessed_year' AND stats_visit_per_year_ip=$my_ip_mysql AND stats_visit_per_year_ip_language=$inp_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_visit_per_year_ip_id, $get_stats_visit_per_year_ip_year, $get_stats_visit_per_year_type, $get_stats_visit_per_year_ip) = $row;
if($get_stats_visit_per_year_ip_id == ""){
	// New visitor this year
	mysqli_query($link, "INSERT INTO $t_stats_visists_per_year_ips 
	(stats_visit_per_year_ip_id, stats_visit_per_year_ip_year, stats_visit_per_year_ip_language, stats_visit_per_year_type, stats_visit_per_year_ip) 
	VALUES
	(NULL, '$get_unprocessed_year', '$get_stats_user_agent_type', $inp_language_mysql, $my_ip_mysql)") or die(mysqli_error($link));
			
	// Update unique
	$inp_visit_per_year_bots_unique = $get_stats_visit_per_year_unique_bots+1;
	$inp_visit_per_year_hits_bots = $get_stats_visit_per_year_hits_bots+1;
	$inp_visit_per_year_hits_total = $get_stats_visit_per_year_hits_total+1;
			
	// Update
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_year SET 
							stats_visit_per_year_unique_bots=$inp_visit_per_year_bots_unique,
							stats_visit_per_year_hits_total=$inp_visit_per_year_hits_total,
							stats_visit_per_year_hits_bots=$inp_visit_per_year_hits_bots
							WHERE stats_visit_per_year_id=$get_stats_visit_per_year_id") or die(mysqli_error($link));

}
else{
	// Update hits
	$inp_visit_per_year_hits_total = $get_stats_visit_per_year_hits_total+1;
	$inp_visit_per_year_hits_bots = $get_stats_visit_per_year_hits_bots+1;
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_year SET 
							stats_visit_per_year_hits_total=$inp_visit_per_year_hits_total,
							stats_visit_per_year_hits_bots=$inp_visit_per_year_hits_bots
							WHERE stats_visit_per_year_id=$get_stats_visit_per_year_id") or die(mysqli_error($link));
} // Visits :: Year

// Visists :: Month :: IPs
$query = "SELECT stats_visit_per_month_ip_id, stats_visit_per_month_ip_month, stats_visit_per_month_ip_year, stats_visit_per_month_type, stats_visit_per_month_ip FROM $t_stats_visists_per_month_ips WHERE stats_visit_per_month_ip_month='$get_unprocessed_month' AND stats_visit_per_month_ip_year='$get_unprocessed_year' AND stats_visit_per_month_ip_language=$inp_language_mysql AND stats_visit_per_month_ip=$my_ip_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_visit_per_month_ip_id, $get_stats_visit_per_month_ip_month, $get_stats_visit_per_month_ip_year, $get_stats_visit_per_month_type, $get_stats_visit_per_month_ip) = $row;
if($get_stats_visit_per_month_ip_id == ""){
	// New visitor this month
	mysqli_query($link, "INSERT INTO $t_stats_visists_per_month_ips 
	(stats_visit_per_month_ip_id, stats_visit_per_month_ip_month, stats_visit_per_month_ip_year, stats_visit_per_month_ip_language, stats_visit_per_month_type, stats_visit_per_month_ip) 
	VALUES
	(NULL, '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, '$get_stats_user_agent_type', $my_ip_mysql)") or die(mysqli_error($link));
			
	// Update unique
	$inp_visit_per_month_bots_unique = $get_stats_visit_per_month_unique_bots+1;
	$inp_visit_per_month_hits_bots = $get_stats_visit_per_month_hits_bots+1;
	$inp_visit_per_month_hits_total = $get_stats_visit_per_month_hits_total+1;
			
	// Update
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_month SET 
							stats_visit_per_month_unique_bots=$inp_visit_per_month_bots_unique,
							stats_visit_per_month_hits_total=$inp_visit_per_month_hits_total,
							stats_visit_per_month_hits_bots=$inp_visit_per_month_hits_bots
							WHERE stats_visit_per_month_id=$get_stats_visit_per_month_id") or die(mysqli_error($link));

}
else{
	// Update hits for bots
	$inp_visit_per_month_hits_total = $get_stats_visit_per_month_hits_total+1;
	$inp_visit_per_month_hits_bots = $get_stats_visit_per_month_hits_bots+1;
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_month SET 
							stats_visit_per_month_hits_total=$inp_visit_per_month_hits_total,
							stats_visit_per_month_hits_bots=$inp_visit_per_month_hits_bots
							WHERE stats_visit_per_month_id=$get_stats_visit_per_month_id") or die(mysqli_error($link));
			
} // Visits :: Month

// Visists :: Week :: IPs
$query = "SELECT stats_visit_per_week_ip_id, stats_visit_per_week_ip_week, stats_visit_per_week_ip_year, stats_visit_per_week_type, stats_visit_per_week_ip FROM $t_stats_visists_per_week_ips WHERE stats_visit_per_week_ip_week='$get_unprocessed_week' AND stats_visit_per_week_ip_year='$get_unprocessed_year' AND stats_visit_per_week_ip_language=$inp_language_mysql AND stats_visit_per_week_ip=$my_ip_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_visit_per_week_ip_id, $get_stats_visit_per_week_ip_week, $get_stats_visit_per_week_ip_year, $get_stats_visit_per_week_type, $get_stats_visit_per_week_ip) = $row;
if($get_stats_visit_per_week_ip_id == ""){
	// New visitor this day
	mysqli_query($link, "INSERT INTO $t_stats_visists_per_week_ips 
			(stats_visit_per_week_ip_id, stats_visit_per_week_ip_week, stats_visit_per_week_ip_year, stats_visit_per_week_ip_language, stats_visit_per_week_type, stats_visit_per_week_ip) 
			VALUES
			(NULL, '$get_unprocessed_week', '$get_unprocessed_year', $inp_language_mysql, '$get_stats_user_agent_type', $my_ip_mysql)") or die(mysqli_error($link));
			
	// Update unique
	$inp_visit_per_week_bots_unique = $get_stats_visit_per_week_unique_bots+1;
	$inp_visit_per_week_hits_bots   = $get_stats_visit_per_week_hits_bots+1;
	$inp_visit_per_week_hits_total  = $get_stats_visit_per_week_hits_total+1;
			
	// Update
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_week SET 
							stats_visit_per_week_unique_bots=$inp_visit_per_week_bots_unique,
							stats_visit_per_week_hits_total=$inp_visit_per_week_hits_total,
							stats_visit_per_week_hits_bots=$inp_visit_per_week_hits_bots
							WHERE stats_visit_per_week_id=$get_stats_visit_per_week_id") or die(mysqli_error($link));

}
else{
	// Update hits
	$inp_visit_per_week_hits_bots   = $get_stats_visit_per_week_hits_bots+1;
	$inp_visit_per_week_hits_total  = $get_stats_visit_per_week_hits_total+1;
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_week SET 
							stats_visit_per_week_hits_total=$inp_visit_per_week_hits_total,
							stats_visit_per_week_hits_bots=$inp_visit_per_week_hits_bots
							WHERE stats_visit_per_week_id=$get_stats_visit_per_week_id") or die(mysqli_error($link));
} // Visits :: Week



// Visists :: Day :: IPs
$query = "SELECT stats_visit_per_day_ip_id, stats_visit_per_day_ip_day, stats_visit_per_day_ip_month, stats_visit_per_day_ip_year, stats_visit_per_day_type, stats_visit_per_day_ip FROM $t_stats_visists_per_day_ips WHERE stats_visit_per_day_ip_day='$get_unprocessed_day' AND stats_visit_per_day_ip_month='$get_unprocessed_month' AND stats_visit_per_day_ip_year='$get_unprocessed_year' AND stats_visit_per_day_ip_language=$inp_language_mysql AND stats_visit_per_day_ip=$my_ip_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_visit_per_day_ip_id, $get_stats_visit_per_day_ip_day, $get_stats_visit_per_day_ip_month, $get_stats_visit_per_day_ip_year, $get_stats_visit_per_day_type, $get_stats_visit_per_day_ip) = $row;
if($get_stats_visit_per_day_ip_id == ""){
	// New visitor this day
	mysqli_query($link, "INSERT INTO $t_stats_visists_per_day_ips 
			(stats_visit_per_day_ip_id, stats_visit_per_day_ip_day, stats_visit_per_day_ip_month, stats_visit_per_day_ip_year, stats_visit_per_day_ip_language, stats_visit_per_day_type, stats_visit_per_day_ip) 
			VALUES
			(NULL, '$get_unprocessed_day', '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, '$get_stats_user_agent_type', $my_ip_mysql)") or die(mysqli_error($link));
			
	// Update unique
	$inp_visit_per_day_bots_unique = $get_stats_visit_per_day_unique_bots+1;
	$inp_visit_per_day_hits_bots = $get_stats_visit_per_day_hits_bots+1;
	$inp_visit_per_day_hits_total = $get_stats_visit_per_day_hits_total+1;
			
	// Update
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_day SET 
							stats_visit_per_day_unique_bots=$inp_visit_per_day_bots_unique,
							stats_visit_per_day_hits_total=$inp_visit_per_day_hits_total,
							stats_visit_per_day_hits_bots=$inp_visit_per_day_hits_bots
							WHERE stats_visit_per_day_id=$get_stats_visit_per_day_id") or die(mysqli_error($link));

}
else{
	// Update hits
	$inp_visit_per_day_hits_total = $get_stats_visit_per_day_hits_total+1;
	$inp_visit_per_day_hits_bots = $get_stats_visit_per_day_hits_bots+1;
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_day SET 
							stats_visit_per_day_hits_total=$inp_visit_per_day_hits_total,
							stats_visit_per_day_hits_bots=$inp_visit_per_day_hits_bots
							WHERE stats_visit_per_day_id=$get_stats_visit_per_day_id") or die(mysqli_error($link));
} // Visits :: Day


// Bots :: Year
$inp_bot_mysql = quote_smart($link, $get_stats_user_agent_bot);
$query = "SELECT stats_bot_id, stats_bot_unique, stats_bot_hits FROM $t_stats_bots_per_year WHERE stats_bot_year='$get_unprocessed_year' AND stats_bot_language=$inp_language_mysql AND stats_bot_name=$inp_bot_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_bot_id, $get_stats_bot_unique, $get_stats_bot_hits) = $row;
if($get_stats_bot_id == ""){

	mysqli_query($link, "INSERT INTO $t_stats_bots_per_year
	(stats_bot_id, stats_bot_year, stats_bot_language, stats_bot_name, stats_bot_unique, stats_bot_hits) 
	VALUES
	(NULL, '$get_unprocessed_year', $inp_language_mysql, $inp_bot_mysql, '1', '1')") or die(mysqli_error($link));
}
else{
	$inp_stats_bot_hits = $get_stats_bot_hits+1;
	$result = mysqli_query($link, "UPDATE $t_stats_bots_per_year SET stats_bot_hits='$inp_stats_bot_hits' WHERE stats_bot_id='$get_stats_bot_id'") or die(mysqli_error($link));
}

// Bots :: Month
$query = "SELECT stats_bot_id, stats_bot_unique, stats_bot_hits FROM $t_stats_bots_per_month WHERE stats_bot_month='$get_unprocessed_month' AND stats_bot_year='$get_unprocessed_year' AND stats_bot_language=$inp_language_mysql AND stats_bot_name=$inp_bot_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_bot_id, $get_stats_bot_unique, $get_stats_bot_hits) = $row;
if($get_stats_bot_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_bots_per_month
	(stats_bot_id, stats_bot_month, stats_bot_year, stats_bot_language, stats_bot_name, stats_bot_unique, stats_bot_hits) 
	VALUES
	(NULL, '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, $inp_bot_mysql, '1', '1')") or die(mysqli_error($link));
}
else{
	$inp_stats_bot_unique = $get_stats_bot_unique+1;
	$inp_stats_bot_hits = $get_stats_bot_hits+1;
	$result = mysqli_query($link, "UPDATE $t_stats_bots_per_month SET stats_bot_unique='$inp_stats_bot_unique', stats_bot_hits='$inp_stats_bot_hits' WHERE stats_bot_id='$get_stats_bot_id'");
}


// Pages :: Year (Bots)
if($configSiteDaysToKeepPageVisitsSav != "0"){	
	$page_url = $configSiteURLSav . "$get_unprocessed_first_request_uri";
	$page_url_len = strlen($page_url);
	if($page_url_len > 190){
		$page_url = substr($page_url, 0, 190);
		$page_url = $page_url . "...";
	}
	$inp_stats_page_url_mysql = quote_smart($link, $page_url);
	$inp_stats_page_title_mysql = quote_smart($link, "");
	$query = "SELECT stats_pages_per_year_id, stats_pages_per_year_unique_bots FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_year='$get_unprocessed_year' AND stats_pages_per_year_language=$inp_language_mysql AND stats_pages_per_year_url=$inp_stats_page_url_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_stats_pages_per_year_id, $get_stats_pages_per_year_unique_bots) = $row;
	if($get_stats_pages_per_year_id == ""){
		// This is a new page
		mysqli_query($link, "INSERT INTO $t_stats_pages_visits_per_year 
		(stats_pages_per_year_id, stats_pages_per_year_year, stats_pages_per_year_language, stats_pages_per_year_url, stats_pages_per_year_title, stats_pages_per_year_title_fetched, 
		stats_pages_per_year_human_unique,  stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile, stats_pages_per_year_unique_bots, stats_pages_per_year_updated_time) 
		VALUES
		(NULL, '$get_unprocessed_year', $inp_language_mysql, $inp_stats_page_url_mysql, $inp_stats_page_title_mysql, 0, 
		0, 0, 0, 1, '$time')") or die(mysqli_error($link));

		// Get page ID
		$query = "SELECT stats_pages_per_year_id, stats_pages_per_year_human_unique, stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_year='$get_unprocessed_year' AND stats_pages_per_year_url=$inp_stats_page_url_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_pages_per_year_id, $get_stats_pages_per_year_human_unique, $get_stats_pages_per_year_unique_desktop, $get_stats_pages_per_year_unique_mobile) = $row;

		// IPBlock
		mysqli_query($link, "INSERT INTO $t_stats_pages_visits_per_year_ips 
		(stats_pages_per_year_ip_id, stats_pages_per_year_ip_year, stats_pages_per_year_ip_language, stats_pages_per_year_ip_page_id, stats_pages_per_year_ip_ip) 
		VALUES
		(NULL, '$get_unprocessed_year', $inp_language_mysql, $get_stats_pages_per_year_id, $my_ip_mysql)") or die(mysqli_error($link));
	}
	else{
		// We have record, if unique
		$query = "SELECT stats_pages_per_year_ip_id FROM $t_stats_pages_visits_per_year_ips WHERE stats_pages_per_year_ip_year='$get_unprocessed_year' AND stats_pages_per_year_ip_language=$inp_language_mysql AND stats_pages_per_year_ip_page_id=$get_stats_pages_per_year_id AND stats_pages_per_year_ip_ip=$my_ip_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_pages_per_year_ip_id) = $row;
		if($get_stats_pages_per_year_ip_id == ""){
			// New visitor for this page this year
			mysqli_query($link, "INSERT INTO $t_stats_pages_visits_per_year_ips 
			(stats_pages_per_year_ip_id, stats_pages_per_year_ip_year, stats_pages_per_year_ip_language, stats_pages_per_year_ip_page_id, stats_pages_per_year_ip_ip) 
			VALUES
			(NULL, '$get_unprocessed_year', $inp_language_mysql, $get_stats_pages_per_year_id, $my_ip_mysql)") or die(mysqli_error($link));

			// Unique
			$inp_count = $get_stats_pages_per_year_unique_bots+1;
			mysqli_query($link, "UPDATE $t_stats_pages_visits_per_year SET stats_pages_per_year_unique_bots=$inp_count, stats_pages_per_year_updated_time='$time' WHERE stats_pages_per_year_id=$get_stats_pages_per_year_id") or die(mysqli_error($link));
		}
	}
}


?>