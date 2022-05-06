<?php
/**
*
* File: _admin/_inc/dashboard/_stats/desktop_or_mobile.php
* Version 1
* Date 09:55 28.11.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Visists :: Year :: IPs
$query = "SELECT stats_visit_per_year_ip_id, stats_visit_per_year_ip_year, stats_visit_per_year_type, stats_visit_per_year_ip FROM $t_stats_visists_per_year_ips WHERE stats_visit_per_year_ip_year='$get_unprocessed_year' AND stats_visit_per_year_ip_language=$inp_language_mysql AND stats_visit_per_year_ip=$my_ip_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_visit_per_year_ip_id, $get_stats_visit_per_year_ip_year, $get_stats_visit_per_year_type, $get_stats_visit_per_year_ip) = $row;
if($get_stats_visit_per_year_ip_id == ""){
	// New visitor this year
	mysqli_query($link, "INSERT INTO $t_stats_visists_per_year_ips 
	(stats_visit_per_year_ip_id, stats_visit_per_year_ip_year, stats_visit_per_year_ip_language, stats_visit_per_year_type, stats_visit_per_year_ip) 
	VALUES
	(NULL, '$get_unprocessed_year', $inp_language_mysql, '$get_stats_user_agent_type', $my_ip_mysql)") or die(mysqli_error($link));
			
	// Update unique
	$inp_visit_per_year_human_unique = $get_stats_visit_per_year_human_unique+1;
	if($get_stats_user_agent_type == "desktop"){
		$inp_visit_per_year_unique_desktop = $get_stats_visit_per_year_unique_desktop+1;
		if($get_stats_visit_per_year_unique_mobile == ""){ $get_stats_visit_per_year_unique_mobile = "0"; }
		$inp_visit_per_year_unique_mobile = $get_stats_visit_per_year_unique_mobile;
	}
	else{
		$inp_visit_per_year_unique_desktop = $get_stats_visit_per_year_unique_desktop;
		$inp_visit_per_year_unique_mobile = $get_stats_visit_per_year_unique_mobile+1;
	}
	$inp_visit_per_year_hits_total = $get_stats_visit_per_year_hits_total+1;
	$inp_visit_per_year_hits_human = $get_stats_visit_per_year_hits_human+1;
			
	// Update new human visitor this year
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_year SET 
							stats_visit_per_year_human_unique=$inp_visit_per_year_human_unique,
							stats_visit_per_year_unique_desktop=$inp_visit_per_year_unique_desktop, 
							stats_visit_per_year_unique_mobile=$inp_visit_per_year_unique_mobile,
							stats_visit_per_year_hits_total=$inp_visit_per_year_hits_total,
							stats_visit_per_year_hits_human=$inp_visit_per_year_hits_human
							WHERE stats_visit_per_year_id=$get_stats_visit_per_year_id") or die(mysqli_error($link));

}
else{
	// Update hits
	$inp_visit_per_year_hits_total = $get_stats_visit_per_year_hits_total+1;
	$inp_visit_per_year_hits_human = $get_stats_visit_per_year_hits_human+1;
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_year SET 
							stats_visit_per_year_hits_total=$inp_visit_per_year_hits_total,
							stats_visit_per_year_hits_human=$inp_visit_per_year_hits_human
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
	$inp_visit_per_month_human_unique = $get_stats_visit_per_month_human_unique+1;
	if($get_stats_user_agent_type == "desktop"){
		$inp_visit_per_month_unique_desktop = $get_stats_visit_per_month_unique_desktop+1;
		$inp_visit_per_month_unique_mobile = $get_stats_visit_per_month_unique_mobile;
	}
	else{
		$inp_visit_per_month_unique_desktop = $get_stats_visit_per_month_unique_desktop;
		$inp_visit_per_month_unique_mobile = $get_stats_visit_per_month_unique_mobile+1;
	}
	$inp_visit_per_month_hits_total = $get_stats_visit_per_month_hits_total+1;
	$inp_visit_per_month_hits_human = $get_stats_visit_per_month_hits_human+1;
			
	// Update
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_month SET 
							stats_visit_per_month_human_unique=$inp_visit_per_month_human_unique,
							stats_visit_per_month_unique_desktop=$inp_visit_per_month_unique_desktop, 
							stats_visit_per_month_unique_mobile=$inp_visit_per_month_unique_mobile,
							stats_visit_per_month_hits_total=$inp_visit_per_month_hits_total,
							stats_visit_per_month_hits_human=$inp_visit_per_month_hits_human
							WHERE stats_visit_per_month_id=$get_stats_visit_per_month_id") or die(mysqli_error($link));

	}
	else{
		// Update hits
		$inp_visit_per_month_hits_total = $get_stats_visit_per_month_hits_total+1;
		$inp_visit_per_month_hits_human = $get_stats_visit_per_month_hits_human+1;
		$result = mysqli_query($link, "UPDATE $t_stats_visists_per_month SET 
							stats_visit_per_month_hits_total=$inp_visit_per_month_hits_total,
							stats_visit_per_month_hits_human=$inp_visit_per_month_hits_human
							WHERE stats_visit_per_month_id=$get_stats_visit_per_month_id") or die(mysqli_error($link));
			
} // Visits :: Month

// Visists :: Week :: IPs
$query = "SELECT stats_visit_per_week_ip_id, stats_visit_per_week_ip_week, stats_visit_per_week_ip_month, stats_visit_per_week_ip_year, stats_visit_per_week_type, stats_visit_per_week_ip FROM $t_stats_visists_per_week_ips WHERE stats_visit_per_week_ip_week='$get_unprocessed_week' AND stats_visit_per_week_ip_year='$get_unprocessed_year' AND stats_visit_per_week_ip_language=$inp_language_mysql AND stats_visit_per_week_ip=$my_ip_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_visit_per_week_ip_id, $get_stats_visit_per_week_ip_week, $get_stats_visit_per_week_ip_month, $get_stats_visit_per_week_ip_year, $get_stats_visit_per_week_type, $get_stats_visit_per_week_ip) = $row;
if($get_stats_visit_per_week_ip_id == ""){
	// New visitor this day
	mysqli_query($link, "INSERT INTO $t_stats_visists_per_week_ips 
	(stats_visit_per_week_ip_id, stats_visit_per_week_ip_week, stats_visit_per_week_ip_month, stats_visit_per_week_ip_year, stats_visit_per_week_ip_language, stats_visit_per_week_type, stats_visit_per_week_ip) 
	VALUES
	(NULL, '$get_unprocessed_week', '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, '$get_stats_user_agent_type', $my_ip_mysql)") or die(mysqli_error($link));
			
	// Update unique
	$inp_visit_per_week_human_unique = $get_stats_visit_per_week_human_unique+1;
	if($get_stats_user_agent_type == "desktop"){
		$inp_visit_per_week_unique_desktop = $get_stats_visit_per_week_unique_desktop+1;
		$inp_visit_per_week_unique_mobile  = $get_stats_visit_per_week_unique_mobile;
	}
	else{
		$inp_visit_per_week_unique_desktop = $get_stats_visit_per_week_unique_desktop;
		$inp_visit_per_week_unique_mobile  = $get_stats_visit_per_week_unique_mobile+1;
	}
	$inp_visit_per_week_hits_total = $get_stats_visit_per_week_hits_total+1;
	$inp_visit_per_week_hits_human = $get_stats_visit_per_week_hits_human+1;
			
	// Update
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_week SET 
							stats_visit_per_week_human_unique=$inp_visit_per_week_human_unique,
							stats_visit_per_week_unique_desktop=$inp_visit_per_week_unique_desktop, 
							stats_visit_per_week_unique_mobile=$inp_visit_per_week_unique_mobile,
							stats_visit_per_week_hits_total=$inp_visit_per_week_hits_total,
							stats_visit_per_week_hits_human=$inp_visit_per_week_hits_human
							WHERE stats_visit_per_week_id=$get_stats_visit_per_week_id") or die(mysqli_error($link));

}
else{
	// Update hits
	$inp_visit_per_week_hits_total = $get_stats_visit_per_week_hits_total+1;
	$inp_visit_per_week_hits_human = $get_stats_visit_per_week_hits_human+1;
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_week SET 
							stats_visit_per_week_hits_total=$inp_visit_per_week_hits_total,
							stats_visit_per_week_hits_human=$inp_visit_per_week_hits_human
							WHERE stats_visit_per_week_id=$get_stats_visit_per_week_id") or die(mysqli_error($link));
			
} // Visits :: Day

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
	$inp_visit_per_day_human_unique = $get_stats_visit_per_day_human_unique+1;
	if($get_stats_user_agent_type == "desktop"){
		$inp_visit_per_day_unique_desktop = $get_stats_visit_per_day_unique_desktop+1;
		$inp_visit_per_day_unique_mobile = $get_stats_visit_per_day_unique_mobile;
	}
	else{
		$inp_visit_per_day_unique_desktop = $get_stats_visit_per_day_unique_desktop;
		$inp_visit_per_day_unique_mobile = $get_stats_visit_per_day_unique_mobile+1;
	}
	$inp_visit_per_day_hits_total = $get_stats_visit_per_day_hits_total+1;
	$inp_visit_per_day_hits_human = $get_stats_visit_per_day_hits_human+1;
			
	// Update
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_day SET 
							stats_visit_per_day_human_unique=$inp_visit_per_day_human_unique,
							stats_visit_per_day_unique_desktop=$inp_visit_per_day_unique_desktop, 
							stats_visit_per_day_unique_mobile=$inp_visit_per_day_unique_mobile,
							stats_visit_per_day_hits_total=$inp_visit_per_day_hits_total,
							stats_visit_per_day_hits_human=$inp_visit_per_day_hits_human
							WHERE stats_visit_per_day_id=$get_stats_visit_per_day_id") or die(mysqli_error($link));

}
else{
	// Update hits
	$inp_visit_per_day_hits_total = $get_stats_visit_per_day_hits_total+1;
	$inp_visit_per_day_hits_human = $get_stats_visit_per_day_hits_human+1;
	$result = mysqli_query($link, "UPDATE $t_stats_visists_per_day SET 
							stats_visit_per_day_hits_total=$inp_visit_per_day_hits_total,
							stats_visit_per_day_hits_human=$inp_visit_per_day_hits_human
							WHERE stats_visit_per_day_id=$get_stats_visit_per_day_id") or die(mysqli_error($link));
			
} // Visits :: Day


// Browsers :: Year
$query = "SELECT stats_browser_id, stats_browser_year, stats_browser_name, stats_browser_unique, stats_browser_hits FROM $t_stats_browsers_per_year WHERE stats_browser_year='$get_unprocessed_year' AND stats_browser_language=$inp_language_mysql AND stats_browser_name=$inp_browser_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_browser_id, $get_stats_browser_year, $get_stats_browser_name, $get_stats_browser_unique, $get_stats_browser_hits) = $row;
if($get_stats_browser_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_browsers_per_year
	(stats_browser_id, stats_browser_year, stats_browser_language, stats_browser_name, stats_browser_unique, stats_browser_hits) 
	VALUES
	(NULL, '$get_unprocessed_year', $inp_language_mysql, $inp_browser_mysql, '1', '1')") or die(mysqli_error($link));
}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_browser_unique+1;
		$inp_hits   = $get_stats_browser_hits+1;
		mysqli_query($link, "UPDATE $t_stats_browsers_per_year SET stats_browser_unique=$inp_unique, stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") or die(mysqli_error($link));
	}
	else{
		// Hits
		$inp_hits = $get_stats_browser_hits+1;
		mysqli_query($link, "UPDATE $t_stats_browsers_per_year SET stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") or die(mysqli_error($link));
	}
}


// Browsers :: Month
$query = "SELECT stats_browser_id, stats_browser_month, stats_browser_year, stats_browser_name, stats_browser_unique, stats_browser_hits FROM $t_stats_browsers_per_month WHERE stats_browser_month='$get_unprocessed_month' AND stats_browser_year='$get_unprocessed_year' AND stats_browser_language=$inp_language_mysql AND stats_browser_name=$inp_browser_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_browser_id, $get_stats_browser_month, $get_stats_browser_year, $get_stats_browser_name, $get_stats_browser_unique, $get_stats_browser_hits) = $row;
if($get_stats_browser_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_browsers_per_month
	(stats_browser_id, stats_browser_month, stats_browser_year, stats_browser_language, stats_browser_name, stats_browser_unique, stats_browser_hits) 
	VALUES
	(NULL, '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, $inp_browser_mysql, '1', '1')") or die(mysqli_error($link));
}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_browser_unique+1;
		$inp_hits   = $get_stats_browser_hits+1;
		mysqli_query($link, "UPDATE $t_stats_browsers_per_month SET stats_browser_unique=$inp_unique, stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") or die(mysqli_error($link));
	}
	else{
		// Hits
		$inp_hits = $get_stats_browser_hits+1;
		mysqli_query($link, "UPDATE $t_stats_browsers_per_month SET stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") or die(mysqli_error($link));
	}
}
	
// OS :: Year
$query = "SELECT stats_os_id, stats_os_year, stats_os_name, stats_os_type, stats_os_unique, stats_os_hits FROM $t_stats_os_per_year WHERE stats_os_year='$get_unprocessed_year' AND stats_os_language=$inp_language_mysql AND stats_os_name=$inp_os_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_os_id, $get_stats_os_year, $get_stats_os_name, $get_stats_os_type, $get_stats_os_unique, $get_stats_os_hits) = $row;
if($get_stats_os_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_os_per_year
	(stats_os_id, stats_os_year, stats_os_language, stats_os_name, stats_os_type, stats_os_unique, stats_os_hits) 
	VALUES
	(NULL, '$get_unprocessed_year', $inp_language_mysql, $inp_os_mysql, $inp_user_agent_type_mysql, '1', '1')") or die(mysqli_error($link));
}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_os_unique+1;
		$inp_hits   = $get_stats_os_hits+1;
		mysqli_query($link, "UPDATE $t_stats_os_per_year SET stats_os_unique=$inp_unique, stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") or die(mysqli_error($link));
	}
	else{
		// Hits
		$inp_hits = $get_stats_os_unique+1;
		mysqli_query($link, "UPDATE $t_stats_os_per_year SET stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") or die(mysqli_error($link));
	}
}

	
// OS :: Month
$query = "SELECT stats_os_id, stats_os_month, stats_os_year, stats_os_name, stats_os_type, stats_os_unique, stats_os_hits FROM $t_stats_os_per_month WHERE stats_os_month='$get_unprocessed_month' AND stats_os_year='$get_unprocessed_year' AND stats_os_language=$inp_language_mysql AND stats_os_name=$inp_os_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_os_id, $get_stats_os_month, $get_stats_os_year, $get_stats_os_name, $get_stats_os_type, $get_stats_os_unique, $get_stats_os_hits) = $row;

if($get_stats_os_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_os_per_month 
	(stats_os_id, stats_os_month, stats_os_year, stats_os_language, stats_os_name, stats_os_type, stats_os_unique, stats_os_hits) 
	VALUES
	(NULL, '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, $inp_os_mysql, $inp_user_agent_type_mysql, '1', '1')") or die(mysqli_error($link));
}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_os_unique+1;
		$inp_hits   = $get_stats_os_hits+1;
		mysqli_query($link, "UPDATE $t_stats_os_per_month SET stats_os_unique=$inp_unique, stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") or die(mysqli_error($link));
	}
	else{
		// Hits
		$inp_hits = $get_stats_os_unique+1;
		mysqli_query($link, "UPDATE $t_stats_os_per_month SET stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") or die(mysqli_error($link));
	}
}


// Accepted languages :: Year
$query = "SELECT stats_accepted_language_id, stats_accepted_language_year, stats_accepted_language_name, stats_accepted_language_unique, stats_accepted_language_hits FROM $t_stats_accepted_languages_per_year WHERE stats_accepted_language_year='$get_unprocessed_year' AND stats_accepted_language_language=$inp_language_mysql AND stats_accepted_language_name=$inp_accept_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_accepted_language_id, $get_stats_accepted_language_year, $get_stats_accepted_language_name, $get_stats_accepted_language_unique, $get_stats_accepted_language_hits) = $row;

if($get_stats_accepted_language_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_accepted_languages_per_year
	(stats_accepted_language_id, stats_accepted_language_year, stats_accepted_language_language, stats_accepted_language_name, stats_accepted_language_unique, stats_accepted_language_hits) 
	VALUES
	(NULL, '$get_unprocessed_year', $inp_language_mysql, $inp_accept_language_mysql, '1', '1')") or die(mysqli_error($link));
}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_accepted_language_unique+1;
		$inp_hits   = $get_stats_accepted_language_hits+1;
		mysqli_query($link, "UPDATE $t_stats_accepted_languages_per_year SET stats_accepted_language_unique=$inp_unique, stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") or die(mysqli_error($link));
	}
	else{
		// Hits
		$inp_hits = $get_stats_accepted_language_unique+1;
		mysqli_query($link, "UPDATE $t_stats_accepted_languages_per_year SET stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") or die(mysqli_error($link));
	}
}

// Accepted languages :: Month
$query = "SELECT stats_accepted_language_id, stats_accepted_language_month, stats_accepted_language_year, stats_accepted_language_name, stats_accepted_language_unique, stats_accepted_language_hits FROM $t_stats_accepted_languages_per_month WHERE stats_accepted_language_month='$get_unprocessed_month' AND stats_accepted_language_year='$get_unprocessed_year' AND stats_accepted_language_language=$inp_language_mysql AND stats_accepted_language_name=$inp_accept_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_accepted_language_id, $get_stats_accepted_language_month, $get_stats_accepted_language_year, $get_stats_accepted_language_name, $get_stats_accepted_language_unique, $get_stats_accepted_language_hits) = $row;

if($get_stats_accepted_language_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_accepted_languages_per_month
	(stats_accepted_language_id, stats_accepted_language_month, stats_accepted_language_year, stats_accepted_language_language, stats_accepted_language_name, stats_accepted_language_unique, stats_accepted_language_hits) 
	VALUES
	(NULL, '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, $inp_accept_language_mysql, '1', '1')") or die(mysqli_error($link));
}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_accepted_language_unique+1;
		$inp_hits   = $get_stats_accepted_language_hits+1;
		mysqli_query($link, "UPDATE $t_stats_accepted_languages_per_month SET stats_accepted_language_unique=$inp_unique, stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") or die(mysqli_error($link));
	}
	else{
		// Hits
		$inp_hits = $get_stats_accepted_language_unique+1;
		mysqli_query($link, "UPDATE $t_stats_accepted_languages_per_month SET stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") or die(mysqli_error($link));
	}
}

// Referer
if($get_unprocessed_first_referer != "" && $configSiteURLSav != ""){
	$inp_stats_referer_from_url_mysql = quote_smart($link, $get_unprocessed_first_referer);
	if (strpos($get_unprocessed_first_referer, $configSiteURLSav) !== false) {
				
	}
	else{
		// Referer :: Year
		$query = "SELECT stats_referer_id, stats_referer_year, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits FROM $t_stats_referers_per_year WHERE stats_referer_year='$get_unprocessed_year' AND stats_referer_language=$inp_language_mysql AND stats_referer_from_url=$inp_stats_referer_from_url_mysql AND stats_referer_to_url=$inp_request_uri_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_referer_id, $get_stats_referer_year, $get_stats_referer_from_url, $get_stats_referer_to_url, $get_stats_referer_unique, $get_stats_referer_hits) = $row;
		if($get_stats_referer_id == ""){
			mysqli_query($link, "INSERT INTO $t_stats_referers_per_year 
			(stats_referer_id, stats_referer_year, stats_referer_language, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits) 
			VALUES
			(NULL,'$get_unprocessed_year', $inp_language_mysql, $inp_stats_referer_from_url_mysql, $inp_request_uri_mysql, '1', '1')") or die(mysqli_error($link));
		}
		else{
			// We have record, if unique
			if($get_stats_visit_per_year_ip_id == ""){
				// Unique + hits
				$inp_unique = $get_stats_referer_unique+1;
				$inp_hits   = $get_stats_referer_hits+1;
				mysqli_query($link, "UPDATE $t_stats_referers_per_year SET stats_referer_unique=$inp_unique, stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") or die(mysqli_error($link));
			}
			else{
				// Hits
				$inp_hits = $get_stats_referer_unique+1;
				mysqli_query($link, "UPDATE $t_stats_referers_per_year SET stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") or die(mysqli_error($link));
			}
		}

		// Referer :: Month
		$query = "SELECT stats_referer_id, stats_referer_month, stats_referer_year, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits FROM $t_stats_referers_per_month WHERE stats_referer_month='$get_unprocessed_month' AND stats_referer_year='$get_unprocessed_year' AND stats_referer_language=$inp_language_mysql AND stats_referer_from_url=$inp_stats_referer_from_url_mysql AND stats_referer_to_url=$inp_request_uri_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_referer_id, $get_stats_referer_month, $get_stats_referer_year, $get_stats_referer_from_url, $get_stats_referer_to_url, $get_stats_referer_unique, $get_stats_referer_hits) = $row;
		if($get_stats_referer_id == ""){
			mysqli_query($link, "INSERT INTO $t_stats_referers_per_month 
			(stats_referer_id, stats_referer_month, stats_referer_year, stats_referer_language, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits) 
			VALUES
			(NULL, '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, $inp_stats_referer_from_url_mysql, $inp_request_uri_mysql, '1', '1')") or die(mysqli_error($link));
		}
		else{
			// We have record, if unique
			if($get_stats_visit_per_month_ip_id == ""){
				// Unique + hits
				$inp_unique = $get_stats_referer_unique+1;
				$inp_hits   = $get_stats_referer_hits+1;
				mysqli_query($link, "UPDATE $t_stats_referers_per_month SET stats_referer_unique=$inp_unique, stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") or die(mysqli_error($link));
			}
			else{
				// Hits
				$inp_hits = $get_stats_referer_unique+1;
				mysqli_query($link, "UPDATE $t_stats_referers_per_year month SET stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") or die(mysqli_error($link));
			}
		}
	}
}


// Country :: Find my country based on IP
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
		

// Country :: Year
		$inp_geoname_country_iso_code_mysql = quote_smart($link, $get_my_country_iso_two);
		$inp_geoname_country_name_mysql = quote_smart($link, $get_my_country_name);
		$query = "SELECT stats_country_id, stats_country_unique, stats_country_hits FROM $t_stats_countries_per_year WHERE stats_country_year='$get_unprocessed_year' AND stats_country_language=$inp_language_mysql AND stats_country_name=$inp_geoname_country_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_country_id, $get_stats_country_unique, $get_stats_country_hits) = $row;
		if($get_stats_country_id == ""){
			mysqli_query($link, "INSERT INTO $t_stats_countries_per_year
			(stats_country_id, stats_country_year, stats_country_language, stats_country_name, stats_country_alpha_2, stats_country_unique, stats_country_hits) 
			VALUES
			(NULL, '$get_unprocessed_year', $inp_language_mysql, $inp_geoname_country_name_mysql, $inp_geoname_country_iso_code_mysql, 1, 1)") or die(mysqli_error($link));
		}
		else{
			// We have record, if unique
			if($get_stats_visit_per_year_ip_id == ""){
				// Unique + hits
				$inp_unique = $get_stats_country_unique+1;
				$inp_hits   = $get_stats_country_hits+1;
				mysqli_query($link, "UPDATE $t_stats_countries_per_year SET stats_country_unique=$inp_unique, stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") or die(mysqli_error($link));
			}
			else{
				// Hits
				$inp_hits = $get_stats_country_hits+1;
				mysqli_query($link, "UPDATE $t_stats_countries_per_year SET stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") or die(mysqli_error($link));
			}
		}

// Country :: Month
		$query = "SELECT stats_country_id, stats_country_unique, stats_country_hits FROM $t_stats_countries_per_month WHERE stats_country_month='$get_unprocessed_month' AND stats_country_year='$get_unprocessed_year' AND stats_country_language=$inp_language_mysql AND stats_country_name=$inp_geoname_country_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_country_id, $get_stats_country_unique, $get_stats_country_hits) = $row;
		if($get_stats_country_id == ""){
			mysqli_query($link, "INSERT INTO $t_stats_countries_per_month
			(stats_country_id, stats_country_month, stats_country_year, stats_country_language, stats_country_name, stats_country_alpha_2, stats_country_unique, stats_country_hits) 
			VALUES
			(NULL, '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, $inp_geoname_country_name_mysql, $inp_geoname_country_iso_code_mysql, 1, 1)") or die(mysqli_error($link));
		}
		else{
			// We have record, if unique
			if($get_stats_visit_per_year_ip_id == ""){
				// Unique + hits
				$inp_unique = $get_stats_country_unique+1;
				$inp_hits   = $get_stats_country_hits+1;
				mysqli_query($link, "UPDATE $t_stats_countries_per_month SET stats_country_unique=$inp_unique, stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") or die(mysqli_error($link));
			}
			else{
				// Hits
				$inp_hits = $get_stats_country_hits+1;
				mysqli_query($link, "UPDATE $t_stats_countries_per_month SET stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") or die(mysqli_error($link));
			}
		}


// Pages :: Year (Humans)
if($configSiteDaysToKeepPageVisitsSav != "0"){
	$page_url = $configSiteURLSav . "$get_unprocessed_first_request_uri";
	$page_url_len = strlen($page_url);
	if($page_url_len > 190){
		$page_url = substr($page_url, 0, 190);
		$page_url = $page_url . "...";
	}
	$inp_stats_page_url_mysql = quote_smart($link, $page_url);
	$inp_stats_page_title_mysql = quote_smart($link, "");
	$query = "SELECT stats_pages_per_year_id, stats_pages_per_year_human_unique, stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_year='$get_unprocessed_year' AND stats_pages_per_year_language=$inp_language_mysql AND stats_pages_per_year_url=$inp_stats_page_url_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_stats_pages_per_year_id, $get_stats_pages_per_year_human_unique, $get_stats_pages_per_year_unique_desktop, $get_stats_pages_per_year_unique_mobile) = $row;
	if($get_stats_pages_per_year_id == ""){
		// This is a new page
		$inp_unique_desktop = 0;
		$inp_unique_mobile = 0;
		if($get_stats_user_agent_type == "desktop"){
			$inp_unique_desktop = 1;
		}
		elseif($get_stats_user_agent_type == "mobile"){
			$inp_unique_mobile = 1;
		}
			
		mysqli_query($link, "INSERT INTO $t_stats_pages_visits_per_year 
		(stats_pages_per_year_id, stats_pages_per_year_year, stats_pages_per_year_language, stats_pages_per_year_url, stats_pages_per_year_title, stats_pages_per_year_title_fetched, 
		stats_pages_per_year_human_unique,  stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile, stats_pages_per_year_unique_bots, stats_pages_per_year_updated_time) 
		VALUES
		(NULL, '$get_unprocessed_year', $inp_language_mysql, $inp_stats_page_url_mysql, $inp_stats_page_title_mysql, 0, 
		1, $inp_unique_desktop, $inp_unique_mobile, 0, '$time')") or die(mysqli_error($link));
			
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
			// echo"We have record, if unique: New visitor for this page this year<br />";
			mysqli_query($link, "INSERT INTO $t_stats_pages_visits_per_year_ips 
			(stats_pages_per_year_ip_id, stats_pages_per_year_ip_year, stats_pages_per_year_ip_language, stats_pages_per_year_ip_page_id, stats_pages_per_year_ip_ip) 
			VALUES
			(NULL, '$get_unprocessed_year', $inp_language_mysql, $get_stats_pages_per_year_id, $my_ip_mysql)") or die(mysqli_error($link));
	
			// Unique
			$inp_unique_desktop = $get_stats_pages_per_year_unique_desktop;
			$inp_unique_mobile = $get_stats_pages_per_year_unique_mobile;
			if($get_stats_user_agent_type == "desktop"){
				$inp_unique_desktop = $inp_unique_desktop+1;
			}
			elseif($get_stats_user_agent_type == "mobile"){
				$inp_unique_mobile = $inp_unique_mobile+1;
			}
			$inp_human_unique = $inp_unique_desktop+$inp_unique_mobile;

			mysqli_query($link, "UPDATE $t_stats_pages_visits_per_year SET stats_pages_per_year_human_unique=$inp_human_unique, stats_pages_per_year_unique_desktop=$inp_unique_desktop, stats_pages_per_year_unique_mobile=$inp_unique_mobile, stats_pages_per_year_updated_time='$time' WHERE stats_pages_per_year_id=$get_stats_pages_per_year_id") or die(mysqli_error($link));
			// echo"UPDATE $t_stats_pages_visits_per_year SET stats_pages_per_year_unique_desktop=$inp_unique_desktop, stats_pages_per_year_unique_mobile=$inp_unique_mobile, stats_pages_per_year_updated_time='$time' WHERE stats_pages_per_year_id=$get_stats_pages_per_year_id<br />";
		}
		else{
			// Delete old entries
			// echo"We have record, if unique: Delete old entries, increase hits<br />";
			// $configSiteDaysToKeepPageVisitsSav
			mysqli_query($link, "DELETE FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_updated_time < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL $configSiteDaysToKeepPageVisitsSav DAY))") or die(mysqli_error($link));
		}
	}
}

// Language :: Year
$query = "SELECT stats_language_id, stats_language_unique, stats_language_hits FROM $t_stats_languages_per_year WHERE stats_language_year='$get_unprocessed_year' AND stats_language_iso_two=$inp_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_language_id, $get_stats_language_unique, $get_stats_language_hits) = $row;
if($get_stats_language_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_languages_per_year 
	(stats_language_id, stats_language_year, stats_language_name, stats_language_iso_two, stats_language_flag_path_16x16, stats_language_flag_16x16, stats_language_unique, stats_language_hits) 
	VALUES
	(NULL, '$get_unprocessed_year', $inp_language_mysql, $inp_language_mysql, '$get_language_active_flag_path_16x16', '$get_language_active_flag_active_16x16', 1, 1)") or die(mysqli_error($link));
}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_language_unique+1;
		$inp_hits   = $get_stats_language_hits+1;
		mysqli_query($link, "UPDATE $t_stats_languages_per_year SET stats_language_unique=$inp_unique, stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") or die(mysqli_error($link));
	}
	else{
		// Hits
		$inp_hits = $get_stats_language_hits+1;
		mysqli_query($link, "UPDATE $t_stats_languages_per_year SET stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") or die(mysqli_error($link));
	}
}

// Language :: Month
$query = "SELECT stats_language_id, stats_language_unique, stats_language_hits FROM $t_stats_languages_per_month WHERE stats_language_month='$get_unprocessed_month' AND stats_language_year='$get_unprocessed_year' AND stats_language_year='$get_unprocessed_year' AND stats_language_iso_two=$inp_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_stats_language_id, $get_stats_language_unique, $get_stats_language_hits) = $row;
if($get_stats_language_id == ""){
	mysqli_query($link, "INSERT INTO $t_stats_languages_per_month 
	(stats_language_id, stats_language_month, stats_language_year, stats_language_name, stats_language_iso_two, stats_language_flag_path_16x16, stats_language_flag_16x16, stats_language_unique, stats_language_hits) 
	VALUES
	(NULL, '$get_unprocessed_month', '$get_unprocessed_year', $inp_language_mysql, $inp_language_mysql, '$get_language_active_flag_path_16x16', '$get_language_active_flag_active_16x16', 1, 1)") or die(mysqli_error($link));
}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_language_unique+1;
		$inp_hits   = $get_stats_language_hits+1;
		mysqli_query($link, "UPDATE $t_stats_languages_per_month SET stats_language_unique=$inp_unique, stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") or die(mysqli_error($link));
	}
	else{
		// Hits
		$inp_hits = $get_stats_language_hits+1;
		mysqli_query($link, "UPDATE $t_stats_languages_per_month SET stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") or die(mysqli_error($link));
	}
}


?>