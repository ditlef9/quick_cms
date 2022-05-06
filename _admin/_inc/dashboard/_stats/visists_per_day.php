<?php
/**
*
* File: _admin/_inc/dashboard/_stats/visists_per_day.php
* Version 1
* Date 09:55 28.11.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

	// Visits per day
	$query = "SELECT stats_visit_per_day_id, stats_visit_per_day_human_unique, stats_visit_per_day_human_unique_diff_from_yesterday, stats_visit_per_day_human_average_duration, stats_visit_per_day_human_new_visitor_unique, stats_visit_per_day_human_returning_visitor_unique, stats_visit_per_day_unique_desktop, stats_visit_per_day_unique_mobile, stats_visit_per_day_unique_bots, stats_visit_per_day_hits_total, stats_visit_per_day_hits_human, stats_visit_per_day_hits_desktop, stats_visit_per_day_hits_mobile, stats_visit_per_day_hits_bots FROM $t_stats_visists_per_day WHERE stats_visit_per_day_day=$get_unprocessed_day AND stats_visit_per_day_month=$get_unprocessed_month AND stats_visit_per_day_year=$get_unprocessed_year AND stats_visit_per_day_language=$inp_language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_stats_visit_per_day_id, $get_stats_visit_per_day_human_unique, $get_stats_visit_per_day_human_unique_diff_from_yesterday, $get_stats_visit_per_day_human_average_duration, $get_stats_visit_per_day_human_new_visitor_unique, $get_stats_visit_per_day_human_returning_visitor_unique, $get_stats_visit_per_day_unique_desktop, $get_stats_visit_per_day_unique_mobile, $get_stats_visit_per_day_unique_bots, $get_stats_visit_per_day_hits_total, $get_stats_visit_per_day_hits_human, $get_stats_visit_per_day_hits_desktop, $get_stats_visit_per_day_hits_mobile, $get_stats_visit_per_day_hits_bots) = $row;
	if($get_stats_visit_per_day_id == ""){
		// Create
		mysqli_query($link, "INSERT INTO $t_stats_visists_per_day
		(stats_visit_per_day_id, stats_visit_per_day_day, stats_visit_per_day_day_full, stats_visit_per_day_day_three, stats_visit_per_day_day_single, 
		stats_visit_per_day_month, stats_visit_per_day_month_full, stats_visit_per_day_month_short, 
		stats_visit_per_day_year, stats_visit_per_day_language, stats_visit_per_day_human_unique, stats_visit_per_day_human_unique_diff_from_yesterday, stats_visit_per_day_human_average_duration, stats_visit_per_day_human_new_visitor_unique, 
		stats_visit_per_day_human_returning_visitor_unique, stats_visit_per_day_unique_desktop, stats_visit_per_day_unique_mobile, stats_visit_per_day_unique_bots, stats_visit_per_day_hits_total, 
		stats_visit_per_day_hits_human, stats_visit_per_day_hits_desktop, stats_visit_per_day_hits_mobile, stats_visit_per_day_hits_bots) 
		VALUES
		(NULL, $get_unprocessed_day, '$inp_day_full', '$inp_day_short', '$inp_day_single', $get_unprocessed_month, '$inp_month_full', '$inp_month_short',
		$get_unprocessed_year, $inp_language_mysql, '0', '0','0','0', 
		'0', '0', '0', '0', '0', 
		'0', '0', '0', '0')") or die(mysqli_error($link));

		$query = "SELECT stats_visit_per_day_id, stats_visit_per_day_human_unique, stats_visit_per_day_human_unique_diff_from_yesterday, stats_visit_per_day_human_average_duration, stats_visit_per_day_human_new_visitor_unique, stats_visit_per_day_human_returning_visitor_unique, stats_visit_per_day_unique_desktop, stats_visit_per_day_unique_mobile, stats_visit_per_day_unique_bots, stats_visit_per_day_hits_total, stats_visit_per_day_hits_human, stats_visit_per_day_hits_desktop, stats_visit_per_day_hits_mobile, stats_visit_per_day_hits_bots FROM $t_stats_visists_per_day WHERE stats_visit_per_day_day=$get_unprocessed_day AND stats_visit_per_day_month=$get_unprocessed_month AND stats_visit_per_day_year=$get_unprocessed_year AND stats_visit_per_day_language=$inp_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_visit_per_day_id, $get_stats_visit_per_day_human_unique, $get_stats_visit_per_day_human_unique_diff_from_yesterday, $get_stats_visit_per_day_human_average_duration, $get_stats_visit_per_day_human_new_visitor_unique, $get_stats_visit_per_day_human_returning_visitor_unique, $get_stats_visit_per_day_unique_desktop, $get_stats_visit_per_day_unique_mobile, $get_stats_visit_per_day_unique_bots, $get_stats_visit_per_day_hits_total, $get_stats_visit_per_day_hits_human, $get_stats_visit_per_day_hits_desktop, $get_stats_visit_per_day_hits_mobile, $get_stats_visit_per_day_hits_bots) = $row;

		// Truncate temp data
		mysqli_query($link,"TRUNCATE TABLE $t_stats_visists_per_day_ips") or die(mysqli_error());
	}

?>