<?php

// Language :: Year
$stmt = $mysqli->prepare("SELECT stats_language_id, stats_language_unique, stats_language_hits FROM $t_stats_languages_per_year WHERE stats_language_year=? AND stats_language_iso_two=?"); 
$stmt->bind_param("ss", $get_unprocessed_year, $inp_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_language_id, $get_stats_language_unique, $get_stats_language_hits) = $row;
if($get_stats_language_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_languages_per_year 
		(stats_language_id, stats_language_year, stats_language_name, stats_language_iso_two, stats_language_flag_path_16x16, 
		stats_language_flag_16x16, stats_language_unique, stats_language_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_year, $inp_language, $inp_language, $get_language_active_flag_path_16x16, $get_language_active_flag_active_16x16, 
		$one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_language_unique+1;
		$inp_hits   = $get_stats_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_languages_per_year SET stats_language_unique=$inp_unique, stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") !== TRUE) {
			echo "Error MySQLi update (Language :: Year :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_languages_per_year SET stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") !== TRUE) {
			echo "Error MySQLi update (Language :: Year :: Hits): " . $mysqli->error; die;
		}
	}
}

// Language :: Month
$stmt = $mysqli->prepare("SELECT stats_language_id, stats_language_unique, stats_language_hits FROM $t_stats_languages_per_month WHERE stats_language_month=? AND stats_language_year=? AND stats_language_iso_two=?"); 
$stmt->bind_param("sss", $get_unprocessed_month, $get_unprocessed_year, $inp_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_language_id, $get_stats_language_unique, $get_stats_language_hits) = $row;
if($get_stats_language_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_languages_per_month 
	(stats_language_id, stats_language_month, stats_language_year, stats_language_name, stats_language_iso_two, 
	stats_language_flag_path_16x16, stats_language_flag_16x16, stats_language_unique, stats_language_hits)
	VALUES 
	(NULL,?,?,?,?,
	?,?,?,?)");
	$stmt->bind_param("ssssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_language, 
	$get_language_active_flag_path_16x16, $get_language_active_flag_path_16x16, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_language_unique+1;
		$inp_hits   = $get_stats_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_languages_per_month SET stats_language_unique=$inp_unique, stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") !== TRUE) {
			echo "Error MySQLi update (Language :: Month :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_languages_per_month SET stats_language_hits=$inp_hits WHERE stats_language_id=$get_stats_language_id") !== TRUE) {
			echo "Error MySQLi update (Language :: Month :: Hits): " . $mysqli->error; die;
		}
	}
}
?>