<?php
/**
*
* File: _admin/_inc/domains_monitoring/_liquidbase_db_scripts/stats_per_day.php
* Version 1.0.0
* Date 14:28 25.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

mysqli_query($link, "DROP TABLE IF EXISTS $t_domains_monitoring_stats_total");


echo"

	<!-- domains_monitoring_stats_total -->
	";
	$query = "SELECT * FROM $t_domains_monitoring_stats_total";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_domains_monitoring_stats_total: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_domains_monitoring_stats_total(
	  	 total_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(total_id), 
		   total_date DATE,
		   total_date_saying VARCHAR(100),
		   total_domains INT,

		   total_domains_other_checked INT,
		   total_domains_other_checked_percentage INT,
		   total_domains_other_not_checked INT,

		   total_domains_starts_with_ends_with_checked INT,
		   total_domains_starts_with_ends_with_checked_percentage INT,
		   total_domains_starts_with_ends_with_not_checked INT,

		   total_domains_filtered INT,
		   total_domains_monitored INT,
		   total_last_checked_time INT
		   )")
		   or die(mysqli_error());

		// Total domains
		$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($count_total_domains) = $row;

		// Total checked :: Other
		$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index WHERE domain_checked_other_by_script=1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($count_total_domains_checked_other) = $row;

		// Not checked percentage :: Other
		if($count_total_domains == "0"){
			$total_domains_checked_other_percentage = 100;
		}
		else{
			$total_domains_checked_other_percentage = ($count_total_domains_checked_other/$count_total_domains)*100;
			$total_domains_checked_other_percentage = round($total_domains_checked_other_percentage, 0);
		}

		// Total not checked :: Other
		$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index WHERE domain_checked_other_by_script=0";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($count_total_domains_not_checked_other) = $row;




		// Total checked :: Stars with, ends with
		$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index WHERE domain_checked_starts_with_ends_with_by_script=1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($count_total_domains_checked_swew) = $row;

		// Not checked percentage :: Stars with, ends with
		if($count_total_domains == "0"){
			$total_domains_checked_swew_percentage = 100;
		}
		else{
			$total_domains_checked_swew_percentage = ($count_total_domains_checked_swew/$count_total_domains)*100;
			$total_domains_checked_swew_percentage = round($total_domains_checked_swew_percentage, 0);
		}

		// Total not checked :: Stars with, ends with
		$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index WHERE domain_checked_starts_with_ends_with_by_script=0";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($count_total_domains_not_checked_swew) = $row;




		// Total filtered
		$query = "SELECT count(filtered_id) FROM $t_domains_monitoring_domains_filtered";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($count_total_domains_filtered) = $row;

		// Total monitored
		$query = "SELECT count(monitored_id) FROM $t_domains_monitoring_domains_monitored";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($count_total_domains_monitored) = $row;
		
		// Dates
		$time = time();
		

		mysqli_query($link, "INSERT INTO $t_domains_monitoring_stats_total
		(total_id, total_domains, total_domains_other_checked, total_domains_other_checked_percentage, total_domains_other_not_checked, 
		total_domains_starts_with_ends_with_checked, total_domains_starts_with_ends_with_checked_percentage, total_domains_starts_with_ends_with_not_checked, total_domains_filtered, total_domains_monitored, 
		total_last_checked_time)
		VALUES 
		(NULL, $count_total_domains, $count_total_domains_checked_other, $total_domains_checked_other_percentage, $count_total_domains_not_checked_other, 
		$count_total_domains_checked_swew, $total_domains_checked_swew_percentage, $count_total_domains_not_checked_swew, $count_total_domains_filtered, $count_total_domains_monitored,
		'$time')")
		or die(mysqli_error($link));
	}


	echo"
	<!-- //domains_monitoring_stats_total -->
";
?>