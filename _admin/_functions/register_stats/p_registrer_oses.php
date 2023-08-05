<?php

	
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
			echo "Error MySQLi update (OS :: Year :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_os_unique+1;
		if ($mysqli->query("UPDATE $t_stats_os_per_year SET stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") !== TRUE) {
			echo "Error MySQLi update (OS :: Year :: Hits): " . $mysqli->error; die;
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
			echo "Error MySQLi update (OS :: Month :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_os_unique+1;
		if ($mysqli->query("UPDATE $t_stats_os_per_month SET stats_os_hits=$inp_hits WHERE stats_os_id=$get_stats_os_id") !== TRUE) {
			echo "Error MySQLi update (OS :: Month :: Hits): " . $mysqli->error; die;
		}
	}
}

?>