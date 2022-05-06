<?php
/**
*
* File: _admin/_inc/dashboard/_stats/visists_per_week.php
* Version 1
* Date 09:55 28.11.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


	// Visits per week
	$query = "SELECT stats_visit_per_week_id, stats_visit_per_week_human_unique, stats_visit_per_week_human_unique_diff_from_last_week, stats_visit_per_week_human_average_duration, stats_visit_per_week_human_new_visitor_unique, stats_visit_per_week_human_returning_visitor_unique, stats_visit_per_week_unique_desktop, stats_visit_per_week_unique_mobile, stats_visit_per_week_unique_bots, stats_visit_per_week_hits_total, stats_visit_per_week_hits_human, stats_visit_per_week_hits_desktop, stats_visit_per_week_hits_mobile, stats_visit_per_week_hits_bots FROM $t_stats_visists_per_week WHERE stats_visit_per_week_week=$get_unprocessed_week AND stats_visit_per_week_year=$get_unprocessed_year AND stats_visit_per_week_language=$inp_language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_stats_visit_per_week_id, $get_stats_visit_per_week_human_unique, $get_stats_visit_per_week_human_unique_diff_from_last_week, $get_stats_visit_per_week_human_average_duration, $get_stats_visit_per_week_human_new_visitor_unique, $get_stats_visit_per_week_human_returning_visitor_unique, $get_stats_visit_per_week_unique_desktop, $get_stats_visit_per_week_unique_mobile, $get_stats_visit_per_week_unique_bots, $get_stats_visit_per_week_hits_total, $get_stats_visit_per_week_hits_human, $get_stats_visit_per_week_hits_desktop, $get_stats_visit_per_week_hits_mobile, $get_stats_visit_per_week_hits_bots) = $row;
	if($get_stats_visit_per_week_id == ""){
		// Create new week
		mysqli_query($link, "INSERT INTO $t_stats_visists_per_week
		(stats_visit_per_week_id, stats_visit_per_week_week, stats_visit_per_week_month, stats_visit_per_week_month_short, stats_visit_per_week_year, 
		stats_visit_per_week_language, stats_visit_per_week_human_unique, stats_visit_per_week_human_unique_diff_from_last_week, stats_visit_per_week_human_average_duration, stats_visit_per_week_human_new_visitor_unique, stats_visit_per_week_human_returning_visitor_unique, 
		stats_visit_per_week_unique_desktop, stats_visit_per_week_unique_mobile, stats_visit_per_week_unique_bots, stats_visit_per_week_hits_total, stats_visit_per_week_hits_human, 
		stats_visit_per_week_hits_desktop, stats_visit_per_week_hits_mobile, stats_visit_per_week_hits_bots) 
		VALUES
		(NULL, $get_unprocessed_week, $get_unprocessed_month, '$inp_month_short',  $get_unprocessed_year,
		$inp_language_mysql, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0,
		0, 0, 0)") or die(mysqli_error($link));

		// Get new ID
		$query = "SELECT stats_visit_per_week_id, stats_visit_per_week_human_unique, stats_visit_per_week_human_unique_diff_from_last_week, stats_visit_per_week_human_average_duration, stats_visit_per_week_human_new_visitor_unique, stats_visit_per_week_human_returning_visitor_unique, stats_visit_per_week_unique_desktop, stats_visit_per_week_unique_mobile, stats_visit_per_week_unique_bots, stats_visit_per_week_hits_total, stats_visit_per_week_hits_human, stats_visit_per_week_hits_desktop, stats_visit_per_week_hits_mobile, stats_visit_per_week_hits_bots FROM $t_stats_visists_per_week WHERE stats_visit_per_week_week=$get_unprocessed_week AND stats_visit_per_week_year=$get_unprocessed_year AND stats_visit_per_week_language=$inp_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_visit_per_week_id, $get_stats_visit_per_week_human_unique, $get_stats_visit_per_week_human_unique_diff_from_last_week, $get_stats_visit_per_week_human_average_duration, $get_stats_visit_per_week_human_new_visitor_unique, $get_stats_visit_per_week_human_returning_visitor_unique, $get_stats_visit_per_week_unique_desktop, $get_stats_visit_per_week_unique_mobile, $get_stats_visit_per_week_unique_bots, $get_stats_visit_per_week_hits_total, $get_stats_visit_per_week_hits_human, $get_stats_visit_per_week_hits_desktop, $get_stats_visit_per_week_hits_mobile, $get_stats_visit_per_week_hits_bots) = $row;

		// Truncate temp data
		mysqli_query($link,"TRUNCATE TABLE $t_stats_visists_per_week_ips") or die(mysqli_error());
	}


?>