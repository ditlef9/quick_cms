<?php

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


?>