<?php

// Referer
$inp_referer = "";
if(isset($_SERVER['HTTP_REFERER']) ){
	$inp_referer = $_SERVER['HTTP_REFERER'];
	$inp_referer = output_html($inp_referer);
	$inp_referer_len = strlen($inp_referer);
	if($inp_referer_len > 200){
		$inp_referer = substr($inp_referer, 0, 200);
	}
}


// Referer
if($get_unprocessed_first_referer != "" && $configSiteURLSav != ""){
	$inp_stats_referer_from_url = $get_unprocessed_first_referer;
	if (strpos($get_unprocessed_first_referer, $configSiteURLSav) !== false) {
				
	}
	else{
		// Referer :: Year
		$stmt = $mysqli->prepare("SELECT stats_referer_id, stats_referer_year, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits FROM $t_stats_referers_per_year WHERE stats_referer_year=? AND stats_referer_language=? AND stats_referer_from_url=? AND stats_referer_to_url=?"); 
		$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $inp_stats_referer_from_url, $inp_request_uri);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_referer_id, $get_stats_referer_year, $get_stats_referer_from_url, $get_stats_referer_to_url, $get_stats_referer_unique, $get_stats_referer_hits) = $row;
		if($get_stats_referer_id == ""){
			$one = 1;
			$stmt = $mysqli->prepare("INSERT INTO $t_stats_referers_per_year 
			(stats_referer_id, stats_referer_year, stats_referer_language, stats_referer_from_url, stats_referer_to_url, 
			stats_referer_unique, stats_referer_hits) 
			VALUES 
			(NULL,?,?,?,?,
			?,?)");
			$stmt->bind_param("ssssss", $get_unprocessed_year, $inp_language, $inp_stats_referer_from_url, $inp_request_uri, $one, $one); 
			$stmt->execute();
			
		}
		else{
			// We have record, if unique
			if($get_stats_visit_per_year_ip_id == ""){
				// Unique + hits
				$inp_unique = $get_stats_referer_unique+1;
				$inp_hits   = $get_stats_referer_hits+1;
				if ($mysqli->query("UPDATE $t_stats_referers_per_year SET stats_referer_unique=$inp_unique, stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") !== TRUE) {
					echo "Error MySQLi update (Referer :: Unique + hits): " . $mysqli->error; die;
				}
			}
			else{
				// Hits
				$inp_hits = $get_stats_referer_unique+1;
				if ($mysqli->query("UPDATE $t_stats_referers_per_year SET stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") !== TRUE) {
					echo "Error MySQLi update (Referer :: Hits): " . $mysqli->error; die;
				}
			}
		}

		// Referer :: Month
		$stmt = $mysqli->prepare("SELECT stats_referer_id, stats_referer_month, stats_referer_year, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits FROM $t_stats_referers_per_month WHERE stats_referer_month=? AND stats_referer_year=? AND stats_referer_language=? AND stats_referer_from_url=? AND stats_referer_to_url=?"); 
		$stmt->bind_param("sssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_stats_referer_from_url, $inp_request_uri);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_referer_id, $get_stats_referer_month, $get_stats_referer_year, $get_stats_referer_from_url, $get_stats_referer_to_url, $get_stats_referer_unique, $get_stats_referer_hits) = $row;
		if($get_stats_referer_id == ""){
			$one = 1;
			$stmt = $mysqli->prepare("INSERT INTO $t_stats_referers_per_month 
			(stats_referer_id, stats_referer_month, stats_referer_year, stats_referer_language, stats_referer_from_url, 
			stats_referer_to_url, stats_referer_unique, stats_referer_hits) 
			VALUES 
			(NULL,?,?,?,?,
			?,?)");
			$stmt->bind_param("ssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_stats_referer_from_url, $inp_request_uri,
				$one, $one); 
			$stmt->execute();
			
		}
		else{
			// We have record, if unique
			if($get_stats_visit_per_month_ip_id == ""){
				// Unique + hits
				$inp_unique = $get_stats_referer_unique+1;
				$inp_hits   = $get_stats_referer_hits+1;
				if ($mysqli->query("UPDATE $t_stats_referers_per_month SET stats_referer_unique=$inp_unique, stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") !== TRUE) {
					echo "Error MySQLi update (Referer :: Unique + hits): " . $mysqli->error; die;
				}
			}
			else{
				// Hits
				$inp_hits = $get_stats_referer_unique+1;
				if ($mysqli->query("UPDATE $t_stats_referers_per_year month SET stats_referer_hits=$inp_hits WHERE stats_referer_id=$get_stats_referer_id") !== TRUE) {
					echo "Error MySQLi update (Referer :: Hits): " . $mysqli->error; die;
				}
			}
		}
	}
}


?>