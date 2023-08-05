<?php


// Accept language
$inp_accept_language = "ZZ";
if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	$inp_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$inp_accept_language = output_html($inp_accept_language);
	$inp_accept_language = strtolower($inp_accept_language);
}


// Accepted languages :: Year
$stmt = $mysqli->prepare("SELECT stats_accepted_language_id, stats_accepted_language_year, stats_accepted_language_name, stats_accepted_language_unique, stats_accepted_language_hits FROM $t_stats_accepted_languages_per_year WHERE stats_accepted_language_year=? AND stats_accepted_language_language=? AND stats_accepted_language_name=?"); 
$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_accept_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_accepted_language_id, $get_stats_accepted_language_year, $get_stats_accepted_language_name, $get_stats_accepted_language_unique, $get_stats_accepted_language_hits) = $row;

if($get_stats_accepted_language_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_accepted_languages_per_year
	(stats_accepted_language_id, stats_accepted_language_year, stats_accepted_language_language, stats_accepted_language_name, stats_accepted_language_unique, 
	stats_accepted_language_hits) 
	VALUES 
	(NULL,?,?,?,?,
	?)");
	$stmt->bind_param("sssss", $get_unprocessed_year, $inp_language, $inp_accept_language, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_accepted_language_unique+1;
		$inp_hits   = $get_stats_accepted_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_accepted_languages_per_year SET stats_accepted_language_unique=$inp_unique, stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") !== TRUE) {
			echo "Error MySQLi update (Accepted languages :: Year :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_accepted_language_unique+1;
		if ($mysqli->query("UPDATE $t_stats_accepted_languages_per_year SET stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") !== TRUE) {
			echo "Error MySQLi update (Accepted languages :: Year :: Hits): " . $mysqli->error; die;
		}
	}
}

// Accepted languages :: Month
$stmt = $mysqli->prepare("SELECT stats_accepted_language_id, stats_accepted_language_month, stats_accepted_language_year, stats_accepted_language_name, stats_accepted_language_unique, stats_accepted_language_hits FROM $t_stats_accepted_languages_per_month WHERE stats_accepted_language_month=? AND stats_accepted_language_year=? AND stats_accepted_language_language=? AND stats_accepted_language_name=?"); 
$stmt->bind_param("ssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_accept_language);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_accepted_language_id, $get_stats_accepted_language_month, $get_stats_accepted_language_year, $get_stats_accepted_language_name, $get_stats_accepted_language_unique, $get_stats_accepted_language_hits) = $row;

if($get_stats_accepted_language_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_accepted_languages_per_month
	(stats_accepted_language_id, stats_accepted_language_month, stats_accepted_language_year, stats_accepted_language_language, stats_accepted_language_name, 
	stats_accepted_language_unique, stats_accepted_language_hits) 
	VALUES 
	(NULL,?,?,?,?,
	?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_accept_language, $one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_month_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_accepted_language_unique+1;
		$inp_hits   = $get_stats_accepted_language_hits+1;
		if ($mysqli->query("UPDATE $t_stats_accepted_languages_per_month SET stats_accepted_language_unique=$inp_unique, stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") !== TRUE) {
			echo "Error MySQLi update (Accepted languages :: Month :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_accepted_language_unique+1;
		if ($mysqli->query("UPDATE $t_stats_accepted_languages_per_month SET stats_accepted_language_hits=$inp_hits WHERE stats_accepted_language_id=$get_stats_accepted_language_id") !== TRUE) {
			echo "Error MySQLi update (Accepted languages :: Month :: Hits): " . $mysqli->error; die;
		}
	}
}

?>