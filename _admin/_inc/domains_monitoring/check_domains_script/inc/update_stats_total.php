<?php


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



// last checked
$time = time();

if($get_current_total_id == ""){
	
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
else{
	mysqli_query($link, "UPDATE $t_domains_monitoring_stats_total SET 
									total_domains=$count_total_domains, 

									total_domains_other_checked=$count_total_domains_checked_other, 
									total_domains_other_checked_percentage=$total_domains_checked_other_percentage, 
									total_domains_other_not_checked=$count_total_domains_not_checked_other, 

									total_domains_starts_with_ends_with_checked=$count_total_domains_checked_swew, 
									total_domains_starts_with_ends_with_checked_percentage=$total_domains_checked_swew_percentage, 
									total_domains_starts_with_ends_with_not_checked=$count_total_domains_not_checked_swew,



									total_domains_filtered=$count_total_domains_filtered, 
									total_domains_monitored=$count_total_domains_monitored,
									total_last_checked_time='$time'
									WHERE total_id=$get_current_total_id") or die(mysql_error($link));
}

?>