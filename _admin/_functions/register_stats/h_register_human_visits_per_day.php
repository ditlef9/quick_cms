<?php
/*
* This inserts into stats_vistis_per_day todays visits.
* Input: $datetime_day, $datetime_month, $datetime_year, $l, $my_ip
*/


// Visists :: Day :: IPs
$stmt = $mysqli->prepare("SELECT stats_visit_per_day_ip_id, stats_visit_per_day_ip_day, stats_visit_per_day_ip_month, stats_visit_per_day_ip_year, stats_visit_per_day_type, stats_visit_per_day_ip FROM $t_stats_visists_per_day_ips WHERE stats_visit_per_day_ip_day=? AND stats_visit_per_day_ip_month=? AND stats_visit_per_day_ip_year=? AND stats_visit_per_day_ip_language=? AND stats_visit_per_day_ip=?"); 
$stmt->bind_param("sssss", $datetime_day, $datetime_month, $datetime_year, $l, $my_ip);
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
	$stmt->bind_param("ssssss", $datetime_day, $datetime_month, $datetime_year, $l, 
		$sql_stats_user_agent_type, $my_ip); 
	$stmt->execute();

	// Update unique
	$inp_visit_per_day_human_unique = $get_stats_visit_per_day_human_unique+1;
	if($sql_stats_user_agent_type == "desktop"){
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
		echo "Error MySQLi update (Visists :: Day :: IPs): " . $mysqli->error; die;
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
		echo "Error MySQLi update (Visists :: Day :: Update hits): " . $mysqli->error; die;
	}

			
} // Visits :: Day



?>