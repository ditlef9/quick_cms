<?php
/**
*
* File: _admin/_inc/dashboard/_stats/visists_per_month.php
* Version 2
* Copyright (c) 2021-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


// Visits per month
$stmt = $mysqli->prepare("SELECT stats_visit_per_month_id, stats_visit_per_month_human_unique, stats_visit_per_month_human_unique_diff_from_last_month, stats_visit_per_month_human_average_duration, stats_visit_per_month_human_new_visitor_unique, stats_visit_per_month_human_returning_visitor_unique, stats_visit_per_month_unique_desktop, stats_visit_per_month_unique_mobile, stats_visit_per_month_unique_bots, stats_visit_per_month_hits_total, stats_visit_per_month_hits_human, stats_visit_per_month_hits_desktop, stats_visit_per_month_hits_mobile, stats_visit_per_month_hits_bots FROM $t_stats_visists_per_month WHERE stats_visit_per_month_month=? AND stats_visit_per_month_year=? AND stats_visit_per_month_language=?"); 
$stmt->bind_param("sss", $get_unprocessed_month, $get_unprocessed_year, $inp_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_visit_per_month_id, $get_stats_visit_per_month_human_unique, $get_stats_visit_per_month_human_unique_diff_from_last_month, $get_stats_visit_per_month_human_average_duration, $get_stats_visit_per_month_human_new_visitor_unique, $get_stats_visit_per_month_human_returning_visitor_unique, $get_stats_visit_per_month_unique_desktop, $get_stats_visit_per_month_unique_mobile, $get_stats_visit_per_month_unique_bots, $get_stats_visit_per_month_hits_total, $get_stats_visit_per_month_hits_human, $get_stats_visit_per_month_hits_desktop, $get_stats_visit_per_month_hits_mobile, $get_stats_visit_per_month_hits_bots) = $row;
if($get_stats_visit_per_month_id == ""){


	// Create new month
	$zero = 0;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_visists_per_month
	(stats_visit_per_month_id, stats_visit_per_month_month, stats_visit_per_month_month_full, stats_visit_per_month_month_short, stats_visit_per_month_year,
	stats_visit_per_month_language, stats_visit_per_month_human_unique, stats_visit_per_month_human_unique_diff_from_last_month, stats_visit_per_month_human_average_duration, stats_visit_per_month_human_new_visitor_unique, 
	stats_visit_per_month_human_returning_visitor_unique, stats_visit_per_month_unique_desktop, stats_visit_per_month_unique_mobile, stats_visit_per_month_unique_bots, stats_visit_per_month_hits_total, 
	stats_visit_per_month_hits_human, stats_visit_per_month_hits_desktop, stats_visit_per_month_hits_mobile, stats_visit_per_month_hits_bots) 
	VALUES 
	(NULL,?,?,?,?,
	?,?,?,?,?,
	?,?,?,?,?,
	?,?,?,?)");
	$stmt->bind_param("ssssssssssssssssss", $get_unprocessed_month, $inp_month_full, $inp_month_short,  $get_unprocessed_year,
	$inp_language_mysql, $zero, $zero, $zero, $zero, 
	$zero, $zero, $zero, $zero, $zero, 
	$zero, $zero, $zero, $zero); 
	$stmt->execute();

	// Get new ID
	$stmt = $mysqli->prepare("SELECT stats_visit_per_month_id, stats_visit_per_month_human_unique, stats_visit_per_month_human_unique_diff_from_last_month, stats_visit_per_month_human_average_duration, stats_visit_per_month_human_new_visitor_unique, stats_visit_per_month_human_returning_visitor_unique, stats_visit_per_month_unique_desktop, stats_visit_per_month_unique_mobile, stats_visit_per_month_unique_bots, stats_visit_per_month_hits_total, stats_visit_per_month_hits_human, stats_visit_per_month_hits_desktop, stats_visit_per_month_hits_mobile, stats_visit_per_month_hits_bots FROM $t_stats_visists_per_month WHERE stats_visit_per_month_month=? AND stats_visit_per_month_year=? AND stats_visit_per_month_language=?"); 
	$stmt->bind_param("sss", $get_unprocessed_month, $get_unprocessed_year, $inp_language);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_stats_visit_per_month_id, $get_stats_visit_per_month_human_unique, $get_stats_visit_per_month_human_unique_diff_from_last_month, $get_stats_visit_per_month_human_average_duration, $get_stats_visit_per_month_human_new_visitor_unique, $get_stats_visit_per_month_human_returning_visitor_unique, $get_stats_visit_per_month_unique_desktop, $get_stats_visit_per_month_unique_mobile, $get_stats_visit_per_month_unique_bots, $get_stats_visit_per_month_hits_total, $get_stats_visit_per_month_hits_human, $get_stats_visit_per_month_hits_desktop, $get_stats_visit_per_month_hits_mobile, $get_stats_visit_per_month_hits_bots) = $row;


	// Truncate temp data
	if ($mysqli->query("TRUNCATE TABLE $t_stats_visists_per_month_ips") !== TRUE) { echo "Error MySQLi truncate: " . $mysqli->error; die; }
	if ($mysqli->query("TRUNCATE TABLE $t_stats_tracker_index") !== TRUE) { echo "Error MySQLi truncate: " . $mysqli->error; die; }
	if ($mysqli->query("TRUNCATE TABLE $t_stats_tracker_urls") !== TRUE) { echo "Error MySQLi truncate: " . $mysqli->error; die; }

}



?>