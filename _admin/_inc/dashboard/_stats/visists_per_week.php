<?php
/**
*
* File: _admin/_inc/dashboard/_stats/visists_per_week.php
* Version 2
* Copyright (c) 2021-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


// Visits per week
$stmt = $mysqli->prepare("SELECT stats_visit_per_week_id, stats_visit_per_week_human_unique, stats_visit_per_week_human_unique_diff_from_last_week, stats_visit_per_week_human_average_duration, stats_visit_per_week_human_new_visitor_unique, stats_visit_per_week_human_returning_visitor_unique, stats_visit_per_week_unique_desktop, stats_visit_per_week_unique_mobile, stats_visit_per_week_unique_bots, stats_visit_per_week_hits_total, stats_visit_per_week_hits_human, stats_visit_per_week_hits_desktop, stats_visit_per_week_hits_mobile, stats_visit_per_week_hits_bots FROM $t_stats_visists_per_week WHERE stats_visit_per_week_week=? AND stats_visit_per_week_year=? AND stats_visit_per_week_language=?"); 
$stmt->bind_param("sss", $get_unprocessed_week, $get_unprocessed_year, $inp_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_visit_per_week_id, $get_stats_visit_per_week_human_unique, $get_stats_visit_per_week_human_unique_diff_from_last_week, $get_stats_visit_per_week_human_average_duration, $get_stats_visit_per_week_human_new_visitor_unique, $get_stats_visit_per_week_human_returning_visitor_unique, $get_stats_visit_per_week_unique_desktop, $get_stats_visit_per_week_unique_mobile, $get_stats_visit_per_week_unique_bots, $get_stats_visit_per_week_hits_total, $get_stats_visit_per_week_hits_human, $get_stats_visit_per_week_hits_desktop, $get_stats_visit_per_week_hits_mobile, $get_stats_visit_per_week_hits_bots) = $row;
if($get_stats_visit_per_week_id == ""){
	// Create new week
	$zero = 0;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_visists_per_week
		(stats_visit_per_week_id, stats_visit_per_week_week, stats_visit_per_week_month, stats_visit_per_week_month_short, stats_visit_per_week_year, 
		stats_visit_per_week_language, stats_visit_per_week_human_unique, stats_visit_per_week_human_unique_diff_from_last_week, stats_visit_per_week_human_average_duration, stats_visit_per_week_human_new_visitor_unique, 
		stats_visit_per_week_human_returning_visitor_unique, stats_visit_per_week_unique_desktop, stats_visit_per_week_unique_mobile, stats_visit_per_week_unique_bots, stats_visit_per_week_hits_total, 
		stats_visit_per_week_hits_human, stats_visit_per_week_hits_desktop, stats_visit_per_week_hits_mobile, stats_visit_per_week_hits_bots) 
		VALUES 
		(NULL,?,?,?,?,
		?,?,?,?,?,
		?,?,?,?,?,
		?,?,?,?)");
	$stmt->bind_param("ssssssssssssssssss", $get_unprocessed_week, $get_unprocessed_month, $inp_month_short,  $get_unprocessed_year,
		$inp_language, $zero, $zero, $zero, $zero,
		$zero, $zero, $zero, $zero, $zero,
		$zero, $zero, $zero, $zero); 
	$stmt->execute();


	// Get new ID
	$stmt = $mysqli->prepare("SELECT stats_visit_per_week_id, stats_visit_per_week_human_unique, stats_visit_per_week_human_unique_diff_from_last_week, stats_visit_per_week_human_average_duration, stats_visit_per_week_human_new_visitor_unique, stats_visit_per_week_human_returning_visitor_unique, stats_visit_per_week_unique_desktop, stats_visit_per_week_unique_mobile, stats_visit_per_week_unique_bots, stats_visit_per_week_hits_total, stats_visit_per_week_hits_human, stats_visit_per_week_hits_desktop, stats_visit_per_week_hits_mobile, stats_visit_per_week_hits_bots FROM $t_stats_visists_per_week WHERE stats_visit_per_week_week=? AND stats_visit_per_week_year=? AND stats_visit_per_week_language=?"); 
	$stmt->bind_param("sss", $get_unprocessed_week, $get_unprocessed_year, $inp_language);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_stats_visit_per_week_id, $get_stats_visit_per_week_human_unique, $get_stats_visit_per_week_human_unique_diff_from_last_week, $get_stats_visit_per_week_human_average_duration, $get_stats_visit_per_week_human_new_visitor_unique, $get_stats_visit_per_week_human_returning_visitor_unique, $get_stats_visit_per_week_unique_desktop, $get_stats_visit_per_week_unique_mobile, $get_stats_visit_per_week_unique_bots, $get_stats_visit_per_week_hits_total, $get_stats_visit_per_week_hits_human, $get_stats_visit_per_week_hits_desktop, $get_stats_visit_per_week_hits_mobile, $get_stats_visit_per_week_hits_bots) = $row;

	// Truncate temp data
	if ($mysqli->query("TRUNCATE TABLE $t_stats_visists_per_week_ips") !== TRUE) { echo "Error MySQLi truncate: " . $mysqli->error; die; }

}


?>