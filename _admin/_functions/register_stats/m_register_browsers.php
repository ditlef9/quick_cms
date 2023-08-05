<?php

// Browsers :: Year
$stmt = $mysqli->prepare("SELECT stats_browser_id, stats_browser_year, stats_browser_name, stats_browser_unique, stats_browser_hits FROM $t_stats_browsers_per_year WHERE stats_browser_year=? AND stats_browser_language=? AND stats_browser_name=?"); 
$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_browser);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_browser_id, $get_stats_browser_year, $get_stats_browser_name, $get_stats_browser_unique, $get_stats_browser_hits) = $row;
if($get_stats_browser_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_browsers_per_year
		(stats_browser_id, stats_browser_year, stats_browser_language, stats_browser_name, stats_browser_unique, 
		stats_browser_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?)");
	$stmt->bind_param("sssss", $get_unprocessed_year, $inp_language, $inp_browser, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_browser_unique+1;
		$inp_hits   = $get_stats_browser_hits+1;
		if ($mysqli->query("UPDATE $t_stats_browsers_per_year SET stats_browser_unique=$inp_unique, stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") !== TRUE) {
			echo "Error MySQLi update (Browsers :: Year :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_browser_hits+1;
		if ($mysqli->query("UPDATE $t_stats_browsers_per_year SET stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") !== TRUE) {
			echo "Error MySQLi update (Browsers :: Year :: Hits): " . $mysqli->error; die;
		}
	}
}


// Browsers :: Month
$stmt = $mysqli->prepare("SELECT stats_browser_id, stats_browser_month, stats_browser_year, stats_browser_name, stats_browser_unique, stats_browser_hits FROM $t_stats_browsers_per_month WHERE stats_browser_month=? AND stats_browser_year=? AND stats_browser_language=? AND stats_browser_name=?"); 
$stmt->bind_param("ssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_browser);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_browser_id, $get_stats_browser_month, $get_stats_browser_year, $get_stats_browser_name, $get_stats_browser_unique, $get_stats_browser_hits) = $row;
if($get_stats_browser_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_browsers_per_month
		(stats_browser_id, stats_browser_month, stats_browser_year, stats_browser_language, stats_browser_name, 
		stats_browser_unique, stats_browser_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_browser, 
	$one, $one); 
	$stmt->execute();


}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_browser_unique+1;
		$inp_hits   = $get_stats_browser_hits+1;
		if ($mysqli->query("UPDATE $t_stats_browsers_per_month SET stats_browser_unique=$inp_unique, stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") !== TRUE) {
			echo "Error MySQLi update (Browsers :: Month :: Unique + hits): " . $mysqli->error; die;
		}

	
	}
	else{
		// Hits
		$inp_hits = $get_stats_browser_hits+1;
		if ($mysqli->query("UPDATE $t_stats_browsers_per_month SET stats_browser_hits=$inp_hits WHERE stats_browser_id=$get_stats_browser_id") !== TRUE) {
			echo "Error MySQLi update (Browsers :: Month :: Hits): " . $mysqli->error; die;
		}
	}
}
?>