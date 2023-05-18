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
$stmt = $mysqli->prepare("SELECT stats_visit_per_year_ip_id, stats_visit_per_year_ip_year, stats_visit_per_year_type, stats_visit_per_year_ip FROM $t_stats_visists_per_year_ips WHERE stats_visit_per_year_ip_year=? AND stats_visit_per_year_ip_language=? AND stats_visit_per_year_ip=?"); 
$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $my_ip);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_visit_per_year_ip_id, $get_stats_visit_per_year_ip_year, $get_stats_visit_per_year_type, $get_stats_visit_per_year_ip) = $row;
if($get_stats_visit_per_year_ip_id == ""){
	// New visitor this year
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_visists_per_year_ips 
	(stats_visit_per_year_ip_id, stats_visit_per_year_ip_year, stats_visit_per_year_ip_language, stats_visit_per_year_type, stats_visit_per_year_ip) 
	VALUES 
	(NULL,?,?,?,?)");
	$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $get_stats_user_agent_type, $my_ip); 
	$stmt->execute();


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
	if ($mysqli->query("UPDATE $t_stats_visists_per_year SET 
		stats_visit_per_year_human_unique=$inp_visit_per_year_human_unique,
		stats_visit_per_year_unique_desktop=$inp_visit_per_year_unique_desktop, 
		stats_visit_per_year_unique_mobile=$inp_visit_per_year_unique_mobile,
		stats_visit_per_year_hits_total=$inp_visit_per_year_hits_total,
		stats_visit_per_year_hits_human=$inp_visit_per_year_hits_human
		WHERE stats_visit_per_year_id=$get_stats_visit_per_year_id") !== TRUE) {
		echo "Error MySQLi update: " . $mysqli->error; die;
	}

}
else{
	// Update hits
	$inp_visit_per_year_hits_total = $get_stats_visit_per_year_hits_total+1;
	$inp_visit_per_year_hits_human = $get_stats_visit_per_year_hits_human+1;
	
	if ($mysqli->query("UPDATE $t_stats_visists_per_year SET 
	stats_visit_per_year_hits_total=$inp_visit_per_year_hits_total,
	stats_visit_per_year_hits_human=$inp_visit_per_year_hits_human
	WHERE stats_visit_per_year_id=$get_stats_visit_per_year_id") !== TRUE) {
		echo "Error MySQLi update: " . $mysqli->error; die;
	}

} // Visits :: Year


// Visists :: Month :: IPs

$stmt = $mysqli->prepare("SELECT stats_visit_per_month_ip_id, stats_visit_per_month_ip_month, stats_visit_per_month_ip_year, stats_visit_per_month_type, stats_visit_per_month_ip FROM $t_stats_visists_per_month_ips WHERE stats_visit_per_month_ip_month=? AND stats_visit_per_month_ip_year=? AND stats_visit_per_month_ip_language=? AND stats_visit_per_month_ip=?"); 
$stmt->bind_param("ssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $my_ip);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_visit_per_month_ip_id, $get_stats_visit_per_month_ip_month, $get_stats_visit_per_month_ip_year, $get_stats_visit_per_month_type, $get_stats_visit_per_month_ip) = $row;
if($get_stats_visit_per_month_ip_id == ""){
	// New visitor this month
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_visists_per_month_ips 
		(stats_visit_per_month_ip_id, stats_visit_per_month_ip_month, stats_visit_per_month_ip_year, stats_visit_per_month_ip_language, stats_visit_per_month_type, 
		stats_visit_per_month_ip) 
		VALUES 
		(NULL,?,?,?,?,
		?)");
	$stmt->bind_param("sssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $get_stats_user_agent_type, 
	$my_ip); 
	$stmt->execute();


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
	if ($mysqli->query("UPDATE $t_stats_visists_per_month SET 
	stats_visit_per_month_human_unique=$inp_visit_per_month_human_unique,
	stats_visit_per_month_unique_desktop=$inp_visit_per_month_unique_desktop, 
	stats_visit_per_month_unique_mobile=$inp_visit_per_month_unique_mobile,
	stats_visit_per_month_hits_total=$inp_visit_per_month_hits_total,
	stats_visit_per_month_hits_human=$inp_visit_per_month_hits_human
	WHERE stats_visit_per_month_id=$get_stats_visit_per_month_id") !== TRUE) {
		echo "Error MySQLi update: " . $mysqli->error; die;
	}

}
else{
	// Update hits
	$inp_visit_per_month_hits_total = $get_stats_visit_per_month_hits_total+1;
	$inp_visit_per_month_hits_human = $get_stats_visit_per_month_hits_human+1;

	if ($mysqli->query("UPDATE $t_stats_visists_per_month SET 
	stats_visit_per_month_hits_total=$inp_visit_per_month_hits_total,
	stats_visit_per_month_hits_human=$inp_visit_per_month_hits_human
	WHERE stats_visit_per_month_id=$get_stats_visit_per_month_id") !== TRUE) {
		echo "Error MySQLi update: " . $mysqli->error; die;
	}
			
} // Visits :: Month

// Visists :: Week :: IPs
$stmt = $mysqli->prepare("SELECT stats_visit_per_week_ip_id, stats_visit_per_week_ip_week, stats_visit_per_week_ip_month, stats_visit_per_week_ip_year, stats_visit_per_week_type, stats_visit_per_week_ip FROM $t_stats_visists_per_week_ips WHERE stats_visit_per_week_ip_week=? AND stats_visit_per_week_ip_year=? AND stats_visit_per_week_ip_language=? AND stats_visit_per_week_ip=?"); 
$stmt->bind_param("ssss", $get_unprocessed_week, $get_unprocessed_year, $inp_language, $my_ip);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_visit_per_week_ip_id, $get_stats_visit_per_week_ip_week, $get_stats_visit_per_week_ip_month, $get_stats_visit_per_week_ip_year, $get_stats_visit_per_week_type, $get_stats_visit_per_week_ip) = $row;
if($get_stats_visit_per_week_ip_id == ""){
	// New visitor this day
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_visists_per_week_ips 
		(stats_visit_per_week_ip_id, stats_visit_per_week_ip_week, stats_visit_per_week_ip_month, stats_visit_per_week_ip_year, stats_visit_per_week_ip_language, 
		stats_visit_per_week_type, stats_visit_per_week_ip) 
		VALUES 
		(NULL,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_week, $get_unprocessed_month, $get_unprocessed_year, $inp_language, 
		$get_stats_user_agent_type, $my_ip); 
	$stmt->execute();

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
	if ($mysqli->query("UPDATE $t_stats_visists_per_week SET 
	stats_visit_per_week_human_unique=$inp_visit_per_week_human_unique,
	stats_visit_per_week_unique_desktop=$inp_visit_per_week_unique_desktop, 
	stats_visit_per_week_unique_mobile=$inp_visit_per_week_unique_mobile,
	stats_visit_per_week_hits_total=$inp_visit_per_week_hits_total,
	stats_visit_per_week_hits_human=$inp_visit_per_week_hits_human
	WHERE stats_visit_per_week_id=$get_stats_visit_per_week_id") !== TRUE) {
		echo "Error MySQLi update: " . $mysqli->error; die;
	}



}
else{
	// Update hits
	$inp_visit_per_week_hits_total = $get_stats_visit_per_week_hits_total+1;
	$inp_visit_per_week_hits_human = $get_stats_visit_per_week_hits_human+1;
	if ($mysqli->query("UPDATE $t_stats_visists_per_week SET 
	stats_visit_per_week_hits_total=$inp_visit_per_week_hits_total,
	stats_visit_per_week_hits_human=$inp_visit_per_week_hits_human
	WHERE stats_visit_per_week_id=$get_stats_visit_per_week_id") !== TRUE) {
		echo "Error MySQLi update: " . $mysqli->error; die;
	}


} // Visits :: Day

// Visists :: Day :: IPs
$stmt = $mysqli->prepare("SELECT stats_visit_per_day_ip_id, stats_visit_per_day_ip_day, stats_visit_per_day_ip_month, stats_visit_per_day_ip_year, stats_visit_per_day_type, stats_visit_per_day_ip FROM $t_stats_visists_per_day_ips WHERE stats_visit_per_day_ip_day=? AND stats_visit_per_day_ip_month=? AND stats_visit_per_day_ip_year=? AND stats_visit_per_day_ip_language=? AND stats_visit_per_day_ip=?"); 
$stmt->bind_param("sssss", $get_unprocessed_day, $get_unprocessed_month, $get_unprocessed_year, $inp_language, $my_ip);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_visit_per_day_ip_id, $get_stats_visit_per_day_ip_day, $get_stats_visit_per_day_ip_month, $get_stats_visit_per_day_ip_year, $get_stats_visit_per_day_type, $get_stats_visit_per_day_ip) = $row;
if($get_stats_visit_per_day_ip_id == ""){
	// New visitor this day
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_visists_per_day_ips 
		(stats_visit_per_day_ip_id, stats_visit_per_day_ip_day, stats_visit_per_day_ip_month, stats_visit_per_day_ip_year, stats_visit_per_day_ip_language, 
		stats_visit_per_day_type, stats_visit_per_day_ip) 
		VALUES 
		(NULL,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_day, $get_unprocessed_month, $get_unprocessed_year, $inp_language, 
		$get_stats_user_agent_type, $my_ip); 
	$stmt->execute();

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
	if ($mysqli->query("UPDATE $t_stats_visists_per_day SET 
		stats_visit_per_day_human_unique=$inp_visit_per_day_human_unique,
		stats_visit_per_day_unique_desktop=$inp_visit_per_day_unique_desktop, 
		stats_visit_per_day_unique_mobile=$inp_visit_per_day_unique_mobile,
		stats_visit_per_day_hits_total=$inp_visit_per_day_hits_total,
		stats_visit_per_day_hits_human=$inp_visit_per_day_hits_human
		WHERE stats_visit_per_day_id=$get_stats_visit_per_day_id") !== TRUE) {
		echo "Error MySQLi update: " . $mysqli->error; die;
	}
}
else{
	// Update hits
	$inp_visit_per_day_hits_total = $get_stats_visit_per_day_hits_total+1;
	$inp_visit_per_day_hits_human = $get_stats_visit_per_day_hits_human+1;
	if ($mysqli->query("UPDATE $t_stats_visists_per_day SET 
		stats_visit_per_day_hits_total=$inp_visit_per_day_hits_total,
		stats_visit_per_day_hits_human=$inp_visit_per_day_hits_human
		WHERE stats_visit_per_day_id=$get_stats_visit_per_day_id") !== TRUE) {
		echo "Error MySQLi update: " . $mysqli->error; die;
	}

			
} // Visits :: Day


// Browsers :: Year
$stmt = $mysqli->prepare("SELECT stats_browser_id, stats_browser_year, stats_browser_name, stats_browser_unique, stats_browser_hits FROM $t_stats_browsers_per_year WHERE stats_browser_year=? AND stats_browser_language=? AND stats_browser_name=?"); 
$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_browser);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_browser_id, $get_stats_browser_year, $get_stats_browser_name, $get_stats_browser_unique, $get_stats_browser_hits) = $row;
if($get_stats_browser_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_browsers_per_year
		(stats_browser_id, stats_browser_year, stats_browser_language, stats_browser_name, stats_browser_unique, 
		stats_browser_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?)");
	$stmt->bind_param("sssss", $get_unprocessed_year, $inp_language, $inp_browser, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_browser_unique+1;
		$inp_hits   = $get_stats_browser_hits+1;
		if ($mysqli->query("UPDATE $t_stats_browsers_per_year SET stats_browser_unique=$inp_unique, stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_browser_hits+1;
		if ($mysqli->query("UPDATE $t_stats_browsers_per_year SET stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}


// Browsers :: Month
$stmt = $mysqli->prepare("SELECT stats_browser_id, stats_browser_month, stats_browser_year, stats_browser_name, stats_browser_unique, stats_browser_hits FROM $t_stats_browsers_per_month WHERE stats_browser_month=? AND stats_browser_year=? AND stats_browser_language=? AND stats_browser_name=?"); 
$stmt->bind_param("ssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_browser);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_browser_id, $get_stats_browser_month, $get_stats_browser_year, $get_stats_browser_name, $get_stats_browser_unique, $get_stats_browser_hits) = $row;
if($get_stats_browser_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_browsers_per_month
		(stats_browser_id, stats_browser_month, stats_browser_year, stats_browser_language, stats_browser_name, 
		stats_browser_unique, stats_browser_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_browser, 
	$one, $one); 
	$stmt->execute();


}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_browser_unique+1;
		$inp_hits   = $get_stats_browser_hits+1;
		if ($mysqli->query("UPDATE $t_stats_browsers_per_month SET stats_browser_unique=$inp_unique, stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}

	
	}
	else{
		// Hits
		$inp_hits = $get_stats_browser_hits+1;
		if ($mysqli->query("UPDATE $t_stats_browsers_per_month SET stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}
	
// OS :: Year
$stmt = $mysqli->prepare("SELECT stats_os_id, stats_os_year, stats_os_name, stats_os_type, stats_os_unique, stats_os_hits FROM $t_stats_os_per_year WHERE stats_os_year=? AND stats_os_language=? AND stats_os_name=?"); 
$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_os);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_os_id, $get_stats_os_year, $get_stats_os_name, $get_stats_os_type, $get_stats_os_unique, $get_stats_os_hits) = $row;
if($get_stats_os_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_os_per_year
	(stats_os_id, stats_os_year, stats_os_language, stats_os_name, stats_os_type, 
	stats_os_unique, stats_os_hits)  
	VALUES 
	(NULL,?,?,?,?,
	?,?)");
$stmt->bind_param("ssssss", $get_unprocessed_year, $inp_language, $inp_os, $inp_user_agent_type, $one, $one); 
$stmt->execute();


}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_os_unique+1;
		$inp_hits   = $get_stats_os_hits+1;
		if ($mysqli->query("UPDATE $t_stats_os_per_year SET stats_os_unique=$inp_unique, stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_os_unique+1;
		if ($mysqli->query("UPDATE $t_stats_os_per_year SET stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}

	
// OS :: Month
$stmt = $mysqli->prepare("SELECT stats_os_id, stats_os_month, stats_os_year, stats_os_name, stats_os_type, stats_os_unique, stats_os_hits FROM $t_stats_os_per_month WHERE stats_os_month=? AND stats_os_year=? AND stats_os_language=? AND stats_os_name=?"); 
$stmt->bind_param("ssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_os);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_os_id, $get_stats_os_month, $get_stats_os_year, $get_stats_os_name, $get_stats_os_type, $get_stats_os_unique, $get_stats_os_hits) = $row;

if($get_stats_os_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_os_per_month 
		(stats_os_id, stats_os_month, stats_os_year, stats_os_language, stats_os_name, 
		stats_os_type, stats_os_unique, stats_os_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?,?)");
	$stmt->bind_param("sssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_os, 
	$inp_user_agent_type, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_os_unique+1;
		$inp_hits   = $get_stats_os_hits+1;
		if ($mysqli->query("UPDATE $t_stats_os_per_month SET stats_os_unique=$inp_unique, stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_os_unique+1;
		if ($mysqli->query("UPDATE $t_stats_os_per_month SET stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}


// Accepted languages :: Year
$stmt = $mysqli->prepare("SELECT stats_accepted_language_id, stats_accepted_language_year, stats_accepted_language_name, stats_accepted_language_unique, stats_accepted_language_hits FROM $t_stats_accepted_languages_per_year WHERE stats_accepted_language_year=? AND stats_accepted_language_language=? AND stats_accepted_language_name=?"); 
$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_accept_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_accepted_language_id, $get_stats_accepted_language_year, $get_stats_accepted_language_name, $get_stats_accepted_language_unique, $get_stats_accepted_language_hits) = $row;

if($get_stats_accepted_language_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_accepted_languages_per_year
	(stats_accepted_language_id, stats_accepted_language_year, stats_accepted_language_language, stats_accepted_language_name, stats_accepted_language_unique, 
	stats_accepted_language_hits) 
	VALUES 
	(NULL,?,?,?,?,
	?)");
	$stmt->bind_param("sssss", $get_unprocessed_year, $inp_language, $inp_accept_language, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_accepted_language_unique+1;
		$inp_hits   = $get_stats_accepted_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_accepted_languages_per_year SET stats_accepted_language_unique=$inp_unique, stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_accepted_language_unique+1;
		if ($mysqli->query("UPDATE $t_stats_accepted_languages_per_year SET stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}

// Accepted languages :: Month
$stmt = $mysqli->prepare("SELECT stats_accepted_language_id, stats_accepted_language_month, stats_accepted_language_year, stats_accepted_language_name, stats_accepted_language_unique, stats_accepted_language_hits FROM $t_stats_accepted_languages_per_month WHERE stats_accepted_language_month=? AND stats_accepted_language_year=? AND stats_accepted_language_language=? AND stats_accepted_language_name=?"); 
$stmt->bind_param("ssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_accept_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_accepted_language_id, $get_stats_accepted_language_month, $get_stats_accepted_language_year, $get_stats_accepted_language_name, $get_stats_accepted_language_unique, $get_stats_accepted_language_hits) = $row;

if($get_stats_accepted_language_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_accepted_languages_per_month
	(stats_accepted_language_id, stats_accepted_language_month, stats_accepted_language_year, stats_accepted_language_language, stats_accepted_language_name, 
	stats_accepted_language_unique, stats_accepted_language_hits) 
	VALUES 
	(NULL,?,?,?,?,
	?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_accept_language, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_accepted_language_unique+1;
		$inp_hits   = $get_stats_accepted_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_accepted_languages_per_month SET stats_accepted_language_unique=$inp_unique, stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_accepted_language_unique+1;
		if ($mysqli->query("UPDATE $t_stats_accepted_languages_per_month SET stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}

// Referer
if($get_unprocessed_first_referer != "" && $configSiteURLSav != ""){
	$inp_stats_referer_from_url_mysql = quote_smart($link, $get_unprocessed_first_referer);
	if (strpos($get_unprocessed_first_referer, $configSiteURLSav) !== false) {
				
	}
	else{
		// Referer :: Year
		$stmt = $mysqli->prepare("SELECT stats_referer_id, stats_referer_year, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits FROM $t_stats_referers_per_year WHERE stats_referer_year=? AND stats_referer_language=? AND stats_referer_from_url=? AND stats_referer_to_url=?"); 
		$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $inp_stats_referer_from_url, $inp_request_uri);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_referer_id, $get_stats_referer_year, $get_stats_referer_from_url, $get_stats_referer_to_url, $get_stats_referer_unique, $get_stats_referer_hits) = $row;
		if($get_stats_referer_id == ""){
			$one = 1;
			$stmt = $mysqli->prepare("INSERT INTO $t_stats_referers_per_year 
			(stats_referer_id, stats_referer_year, stats_referer_language, stats_referer_from_url, stats_referer_to_url, 
			stats_referer_unique, stats_referer_hits) 
			VALUES 
			(NULL,?,?,?,?,
			?,?)");
			$stmt->bind_param("ssssss", $get_unprocessed_year, $inp_language, $inp_stats_referer_from_url, $inp_request_uri, $one, $one); 
			$stmt->execute();
			
		}
		else{
			// We have record, if unique
			if($get_stats_visit_per_year_ip_id == ""){
				// Unique + hits
				$inp_unique = $get_stats_referer_unique+1;
				$inp_hits   = $get_stats_referer_hits+1;
				if ($mysqli->query("UPDATE $t_stats_referers_per_year SET stats_referer_unique=$inp_unique, stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") !== TRUE) {
					echo "Error MySQLi update: " . $mysqli->error; die;
				}
			}
			else{
				// Hits
				$inp_hits = $get_stats_referer_unique+1;
				if ($mysqli->query("UPDATE $t_stats_referers_per_year SET stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") !== TRUE) {
					echo "Error MySQLi update: " . $mysqli->error; die;
				}
			}
		}

		// Referer :: Month
		$stmt = $mysqli->prepare("SELECT stats_referer_id, stats_referer_month, stats_referer_year, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits FROM $t_stats_referers_per_month WHERE stats_referer_month=? AND stats_referer_year=? AND stats_referer_language=? AND stats_referer_from_url=? AND stats_referer_to_url=?"); 
		$stmt->bind_param("sssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_stats_referer_from_url, $inp_request_uri);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_referer_id, $get_stats_referer_month, $get_stats_referer_year, $get_stats_referer_from_url, $get_stats_referer_to_url, $get_stats_referer_unique, $get_stats_referer_hits) = $row;
		if($get_stats_referer_id == ""){
			$one = 1;
			$stmt = $mysqli->prepare("INSERT INTO $t_stats_referers_per_month 
			(stats_referer_id, stats_referer_month, stats_referer_year, stats_referer_language, stats_referer_from_url, 
			stats_referer_to_url, stats_referer_unique, stats_referer_hits) 
			VALUES 
			(NULL,?,?,?,?,
			?,?)");
			$stmt->bind_param("ssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_stats_referer_from_url, $inp_request_uri,
				$one, $one); 
			$stmt->execute();
			
		}
		else{
			// We have record, if unique
			if($get_stats_visit_per_month_ip_id == ""){
				// Unique + hits
				$inp_unique = $get_stats_referer_unique+1;
				$inp_hits   = $get_stats_referer_hits+1;
				if ($mysqli->query("UPDATE $t_stats_referers_per_month SET stats_referer_unique=$inp_unique, stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") !== TRUE) {
					echo "Error MySQLi update: " . $mysqli->error; die;
				}
			}
			else{
				// Hits
				$inp_hits = $get_stats_referer_unique+1;
				if ($mysqli->query("UPDATE $t_stats_referers_per_year month SET stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") !== TRUE) {
					echo "Error MySQLi update: " . $mysqli->error; die;
				}
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

	$stmt = $mysqli->prepare("SELECT * FROM $t_stats_ip_to_country_lookup_ipv4 WHERE addr_type=? AND ip_start <= ? ORDER BY ip_start DESC LIMIT 1"); 
	$stmt->bind_param("ss", $ip_type, $in_addr);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;
} else if (preg_match('/^[0-9a-fA-F:]+$/', $my_ip) && @inet_pton($my_ip)) {
	$ip_type = "ipv6";

	$in_addr = inet_pton($my_ip);


	$stmt = $mysqli->prepare("SELECT * FROM $t_stats_ip_to_country_lookup_ipv6 WHERE addr_type=? AND ip_start <= ? ORDER BY ip_start DESC LIMIT 1"); 
	$stmt->bind_param("ss", $ip_type, $in_addr);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;
}

// echo"Type=$ip_type<br />";
// echo"in_addr=$in_addr<br />";

$get_my_country_name = "";
$get_my_country_iso_two = "";
if($get_ip_id != ""){
	$stmt = $mysqli->prepare("SELECT country_id, country_name, country_iso_two FROM $t_languages_countries WHERE country_iso_two=?"); 
	$stmt->bind_param("s", $get_country);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_country_id, $get_my_country_name, $get_my_country_iso_two) = $row;
}
		

// Country :: Year
$inp_geoname_country_iso_code = "$get_my_country_iso_two";
$inp_geoname_country_name = "$get_my_country_name";
$stmt = $mysqli->prepare("SELECT stats_country_id, stats_country_unique, stats_country_hits FROM $t_stats_countries_per_year WHERE stats_country_year=? AND stats_country_language=? AND stats_country_name=?"); 
$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_geoname_country_name);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_country_id, $get_stats_country_unique, $get_stats_country_hits) = $row;
if($get_stats_country_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_countries_per_year
		(stats_country_id, stats_country_year, stats_country_language, stats_country_name, stats_country_alpha_2, 
		stats_country_unique, stats_country_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_year, $inp_language, $inp_geoname_country_name, $inp_geoname_country_iso_code,
		$one, $one); 
	$stmt->execute();
}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_country_unique+1;
		$inp_hits   = $get_stats_country_hits+1;
		if ($mysqli->query("UPDATE $t_stats_countries_per_year SET stats_country_unique=$inp_unique, stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_country_hits+1;
		if ($mysqli->query("UPDATE $t_stats_countries_per_year SET stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}

// Country :: Month
$stmt = $mysqli->prepare("SELECT stats_country_id, stats_country_unique, stats_country_hits FROM $t_stats_countries_per_month WHERE stats_country_month=? AND stats_country_year=? AND stats_country_language=? AND stats_country_name=?"); 
$stmt->bind_param("ssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_geoname_country_name);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_country_id, $get_stats_country_unique, $get_stats_country_hits) = $row;
if($get_stats_country_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_countries_per_month
		(stats_country_id, stats_country_month, stats_country_year, stats_country_language, stats_country_name, 
		stats_country_alpha_2, stats_country_unique, stats_country_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?,?)");
	$stmt->bind_param("sssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_geoname_country_name, $inp_geoname_country_iso_code, 
		$one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_country_unique+1;
		$inp_hits   = $get_stats_country_hits+1;
		if ($mysqli->query("UPDATE $t_stats_countries_per_month SET stats_country_unique=$inp_unique, stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_country_hits+1;
		if ($mysqli->query("UPDATE $t_stats_countries_per_month SET stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
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
	$inp_stats_page_url = "$page_url";
	$inp_stats_page_title = "";

	$stmt = $mysqli->prepare("SELECT stats_pages_per_year_id, stats_pages_per_year_human_unique, stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_year=? AND stats_pages_per_year_language=? AND stats_pages_per_year_url=?"); 
	$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_stats_page_url);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
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
		
		$zero = 0;
		$one = 1;
		$stmt = $mysqli->prepare("INSERT INTO $t_stats_pages_visits_per_year 
			(stats_pages_per_year_id, stats_pages_per_year_year, stats_pages_per_year_language, stats_pages_per_year_url, stats_pages_per_year_title, 
			stats_pages_per_year_title_fetched, stats_pages_per_year_human_unique,  stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile, stats_pages_per_year_unique_bots, 
			stats_pages_per_year_updated_time) 
			VALUES 
			(NULL,?,?,?,?,
			?,?,?,?,?,
			?)");
		$stmt->bind_param("ssssssssss", $get_unprocessed_year, $inp_language, $inp_stats_page_url, $inp_stats_page_title, 
			$zero, $one, $inp_unique_desktop, $inp_unique_mobile, $zero, 
			$time); 
		$stmt->execute();


		// Get page ID
		$stmt = $mysqli->prepare("SELECT stats_pages_per_year_id, stats_pages_per_year_human_unique, stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_year=? AND stats_pages_per_year_language=? AND stats_pages_per_year_url=?"); 
		$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_stats_page_url);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_pages_per_year_id, $get_stats_pages_per_year_human_unique, $get_stats_pages_per_year_unique_desktop, $get_stats_pages_per_year_unique_mobile) = $row;
	
	
		// IPBlock
		$stmt = $mysqli->prepare("INSERT INTO $t_stats_pages_visits_per_year_ips 
		(stats_pages_per_year_ip_id, stats_pages_per_year_ip_year, stats_pages_per_year_ip_language, stats_pages_per_year_ip_page_id, stats_pages_per_year_ip_ip) 
		VALUES 
		(NULL,?,?,?,?)");
		$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $get_stats_pages_per_year_id, $my_ip); 
		$stmt->execute();

	}
	else{
		// We have record, if unique
		$stmt = $mysqli->prepare("SELECT stats_pages_per_year_ip_id FROM $t_stats_pages_visits_per_year_ips WHERE stats_pages_per_year_ip_year=? AND stats_pages_per_year_ip_language=? AND stats_pages_per_year_ip_page_id=? AND stats_pages_per_year_ip_ip=?"); 
		$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $get_stats_pages_per_year_id, $my_ip);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_pages_per_year_ip_id) = $row;
		if($get_stats_pages_per_year_ip_id == ""){
			// New visitor for this page this year
			// echo"We have record, if unique: New visitor for this page this year<br />";
			$stmt = $mysqli->prepare("INSERT INTO $t_stats_pages_visits_per_year_ips 
				(stats_pages_per_year_ip_id, stats_pages_per_year_ip_year, stats_pages_per_year_ip_language, stats_pages_per_year_ip_page_id, stats_pages_per_year_ip_ip) 
				VALUES 
				(NULL,?,?,?,?");
			$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $get_stats_pages_per_year_id, $my_ip); 
			$stmt->execute();

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

			if ($mysqli->query("UPDATE $t_stats_pages_visits_per_year SET stats_pages_per_year_human_unique=$inp_human_unique, stats_pages_per_year_unique_desktop=$inp_unique_desktop, stats_pages_per_year_unique_mobile=$inp_unique_mobile, stats_pages_per_year_updated_time='$time' WHERE stats_pages_per_year_id=$get_stats_pages_per_year_id") !== TRUE) {
				echo "Error MySQLi update: " . $mysqli->error; die;
			}
			// echo"UPDATE $t_stats_pages_visits_per_year SET stats_pages_per_year_unique_desktop=$inp_unique_desktop, stats_pages_per_year_unique_mobile=$inp_unique_mobile, stats_pages_per_year_updated_time='$time' WHERE stats_pages_per_year_id=$get_stats_pages_per_year_id<br />";
		}
		else{
			// Delete old entries
			// echo"We have record, if unique: Delete old entries, increase hits<br />";
			// $configSiteDaysToKeepPageVisitsSav
			if ($mysqli->query("DELETE FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_updated_time < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL $configSiteDaysToKeepPageVisitsSav DAY))") !== TRUE) {
				echo "Error MySQLi delete: " . $mysqli->error; die;
			}
		}
	}
}

// Language :: Year

$stmt = $mysqli->prepare("SELECT stats_language_id, stats_language_unique, stats_language_hits FROM $t_stats_languages_per_year WHERE stats_language_year=? AND stats_language_iso_two=?"); 
$stmt->bind_param("ss", $get_unprocessed_year, $inp_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_language_id, $get_stats_language_unique, $get_stats_language_hits) = $row;
if($get_stats_language_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_languages_per_year 
		(stats_language_id, stats_language_year, stats_language_name, stats_language_iso_two, stats_language_flag_path_16x16, 
		stats_language_flag_16x16, stats_language_unique, stats_language_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_year, $inp_language, $inp_language, $get_language_active_flag_path_16x16, $get_language_active_flag_active_16x16, 
		$one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_language_unique+1;
		$inp_hits   = $get_stats_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_languages_per_year SET stats_language_unique=$inp_unique, stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_languages_per_year SET stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}

// Language :: Month
$stmt = $mysqli->prepare("SELECT stats_language_id, stats_language_unique, stats_language_hits FROM $t_stats_languages_per_month WHERE stats_language_month=? AND stats_language_year=? AND stats_language_iso_two=?"); 
$stmt->bind_param("sss", $get_unprocessed_month, $get_unprocessed_year, $inp_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_language_id, $get_stats_language_unique, $get_stats_language_hits) = $row;
if($get_stats_language_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_languages_per_month 
	(stats_language_id, stats_language_month, stats_language_year, stats_language_name, stats_language_iso_two, 
	stats_language_flag_path_16x16, stats_language_flag_16x16, stats_language_unique, stats_language_hits)
	VALUES 
	(NULL,?,?,?,?,
	?,?,?,?)");
	$stmt->bind_param("ssssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_language, 
	$get_language_active_flag_path_16x16, $get_language_active_flag_path_16x16, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_language_unique+1;
		$inp_hits   = $get_stats_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_languages_per_month SET stats_language_unique=$inp_unique, stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_languages_per_month SET stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}
	}
}


?>