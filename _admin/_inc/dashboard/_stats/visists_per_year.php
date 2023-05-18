<?php
/**
*
* File: _admin/_inc/dashboard/_stats/visists_per_year.php
* Version 2
* Copyright (c) 2021-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Visits per year
$stmt = $mysqli->prepare("SELECT stats_visit_per_year_id, stats_visit_per_year_year, stats_visit_per_year_human_unique, stats_visit_per_year_human_unique_diff_from_last_year, stats_visit_per_year_human_average_duration, stats_visit_per_year_human_new_visitor_unique, stats_visit_per_year_human_returning_visitor_unique, stats_visit_per_year_unique_desktop, stats_visit_per_year_unique_mobile, stats_visit_per_year_unique_bots, stats_visit_per_year_hits_total, stats_visit_per_year_hits_human, stats_visit_per_year_hits_desktop, stats_visit_per_year_hits_mobile, stats_visit_per_year_hits_bots FROM $t_stats_visists_per_year WHERE stats_visit_per_year_year=? AND stats_visit_per_year_language=?"); 
$stmt->bind_param("ss", $get_unprocessed_year, $inp_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_visit_per_year_id, $get_stats_visit_per_year_year, $get_stats_visit_per_year_human_unique, $get_stats_visit_per_year_human_unique_diff_from_last_year, $get_stats_visit_per_year_human_average_duration, $get_stats_visit_per_year_human_new_visitor_unique, $get_stats_visit_per_year_human_returning_visitor_unique, $get_stats_visit_per_year_unique_desktop, $get_stats_visit_per_year_unique_mobile, $get_stats_visit_per_year_unique_bots, $get_stats_visit_per_year_hits_total, $get_stats_visit_per_year_hits_human, $get_stats_visit_per_year_hits_desktop, $get_stats_visit_per_year_hits_mobile, $get_stats_visit_per_year_hits_bots) = $row;
if($get_stats_visit_per_year_id == ""){
	// Create new year
	$zero = 0;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_visists_per_year
		(stats_visit_per_year_id, stats_visit_per_year_year, stats_visit_per_year_language, stats_visit_per_year_human_unique, stats_visit_per_year_human_unique_diff_from_last_year, 
		stats_visit_per_year_human_average_duration, stats_visit_per_year_human_new_visitor_unique, stats_visit_per_year_human_returning_visitor_unique, stats_visit_per_year_unique_desktop, stats_visit_per_year_unique_mobile, 
		stats_visit_per_year_unique_bots, stats_visit_per_year_hits_total, stats_visit_per_year_hits_human, stats_visit_per_year_hits_desktop, stats_visit_per_year_hits_mobile, 
		stats_visit_per_year_hits_bots) 
		VALUES 
		(NULL,?,?,?,?,
		?,?,?,?,?,
		?,?,?,?,?,
		?)");
	$stmt->bind_param("sssssssssssssss", $get_unprocessed_year, $inp_language, $zero, $zero, 
		$zero, $zero, $zero, $zero, $zero, 
		$zero, $zero, $zero, $zero, $zero, 
		$zero); 
	$stmt->execute();

	
		// Get new ID
		$stmt = $mysqli->prepare("SELECT stats_visit_per_year_id, stats_visit_per_year_year, stats_visit_per_year_human_unique, stats_visit_per_year_human_unique_diff_from_last_year, stats_visit_per_year_human_average_duration, stats_visit_per_year_human_new_visitor_unique, stats_visit_per_year_human_returning_visitor_unique, stats_visit_per_year_unique_desktop, stats_visit_per_year_unique_mobile, stats_visit_per_year_unique_bots, stats_visit_per_year_hits_total, stats_visit_per_year_hits_human, stats_visit_per_year_hits_desktop, stats_visit_per_year_hits_mobile, stats_visit_per_year_hits_bots FROM $t_stats_visists_per_year WHERE stats_visit_per_year_year=? AND stats_visit_per_year_language=?"); 
		$stmt->bind_param("ss", $get_unprocessed_year, $inp_language);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_visit_per_year_id, $get_stats_visit_per_year_year, $get_stats_visit_per_year_human_unique, $get_stats_visit_per_year_human_unique_diff_from_last_year, $get_stats_visit_per_year_human_average_duration, $get_stats_visit_per_year_human_new_visitor_unique, $get_stats_visit_per_year_human_returning_visitor_unique, $get_stats_visit_per_year_unique_desktop, $get_stats_visit_per_year_unique_mobile, $get_stats_visit_per_year_unique_bots, $get_stats_visit_per_year_hits_total, $get_stats_visit_per_year_hits_human, $get_stats_visit_per_year_hits_desktop, $get_stats_visit_per_year_hits_mobile, $get_stats_visit_per_year_hits_bots) = $row;


		$query = "SELECT stats_visit_per_year_id, stats_visit_per_year_year, stats_visit_per_year_human_unique, stats_visit_per_year_human_unique_diff_from_last_year, stats_visit_per_year_human_average_duration, stats_visit_per_year_human_new_visitor_unique, stats_visit_per_year_human_returning_visitor_unique, stats_visit_per_year_unique_desktop, stats_visit_per_year_unique_mobile, stats_visit_per_year_unique_bots, stats_visit_per_year_hits_total, stats_visit_per_year_hits_human, stats_visit_per_year_hits_desktop, stats_visit_per_year_hits_mobile, stats_visit_per_year_hits_bots FROM $t_stats_visists_per_year WHERE stats_visit_per_year_year=$get_unprocessed_year AND stats_visit_per_year_language=$inp_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_visit_per_year_id, $get_stats_visit_per_year_year, $get_stats_visit_per_year_human_unique, $get_stats_visit_per_year_human_unique_diff_from_last_year, $get_stats_visit_per_year_human_average_duration, $get_stats_visit_per_year_human_new_visitor_unique, $get_stats_visit_per_year_human_returning_visitor_unique, $get_stats_visit_per_year_unique_desktop, $get_stats_visit_per_year_unique_mobile, $get_stats_visit_per_year_unique_bots, $get_stats_visit_per_year_hits_total, $get_stats_visit_per_year_hits_human, $get_stats_visit_per_year_hits_desktop, $get_stats_visit_per_year_hits_mobile, $get_stats_visit_per_year_hits_bots) = $row;
		
		// Truncate temp data
		$mysqli->query("TRUNCATE TABLE $t_stats_visists_per_year_ips");
		$mysqli->query("TRUNCATE TABLE $t_stats_pages_visits_per_year_ips");
	}



?>