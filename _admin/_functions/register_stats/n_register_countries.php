<?php

// Country :: Find my country based on IP
// Country :: IP Type
$ip_type = "";
$get_ip_id = "";
if (ip2long($my_ip) !== false) {
	$ip_type = "ipv4";

	$in_addr = inet_pton($my_ip);

	$stmt = $mysqli->prepare("SELECT * FROM $t_stats_ip_to_country_lookup_ipv4 WHERE addr_type=? AND ip_start <= ? ORDER BY ip_start DESC LIMIT 1"); 
	$stmt->bind_param("ss", $ip_type, $in_addr);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;
} else if (preg_match('/^[0-9a-fA-F:]+$/', $my_ip) && @inet_pton($my_ip)) {
	$ip_type = "ipv6";

	$in_addr = inet_pton($my_ip);


	$stmt = $mysqli->prepare("SELECT * FROM $t_stats_ip_to_country_lookup_ipv6 WHERE addr_type=? AND ip_start <= ? ORDER BY ip_start DESC LIMIT 1"); 
	$stmt->bind_param("ss", $ip_type, $in_addr);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;
}

// echo"Type=$ip_type<br />";
// echo"in_addr=$in_addr<br />";

$get_my_country_name = "";
$get_my_country_iso_two = "";
if($get_ip_id != ""){
	$stmt = $mysqli->prepare("SELECT country_id, country_name, country_iso_two FROM $t_languages_countries WHERE country_iso_two=?"); 
	$stmt->bind_param("s", $get_country);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_country_id, $get_my_country_name, $get_my_country_iso_two) = $row;
}
		

// Country :: Year
$inp_geoname_country_iso_code = "$get_my_country_iso_two";
$inp_geoname_country_name = "$get_my_country_name";
$stmt = $mysqli->prepare("SELECT stats_country_id, stats_country_unique, stats_country_hits FROM $t_stats_countries_per_year WHERE stats_country_year=? AND stats_country_language=? AND stats_country_name=?"); 
$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_geoname_country_name);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_country_id, $get_stats_country_unique, $get_stats_country_hits) = $row;
if($get_stats_country_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_countries_per_year
		(stats_country_id, stats_country_year, stats_country_language, stats_country_name, stats_country_alpha_2, 
		stats_country_unique, stats_country_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssss", $get_unprocessed_year, $inp_language, $inp_geoname_country_name, $inp_geoname_country_iso_code,
		$one, $one); 
	$stmt->execute();
}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_country_unique+1;
		$inp_hits   = $get_stats_country_hits+1;
		if ($mysqli->query("UPDATE $t_stats_countries_per_year SET stats_country_unique=$inp_unique, stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") !== TRUE) {
			echo "Error MySQLi update (Country :: Year :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_country_hits+1;
		if ($mysqli->query("UPDATE $t_stats_countries_per_year SET stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") !== TRUE) {
			echo "Error MySQLi update (Country :: Year :: Hits): " . $mysqli->error; die;
		}
	}
}

// Country :: Month
$stmt = $mysqli->prepare("SELECT stats_country_id, stats_country_unique, stats_country_hits FROM $t_stats_countries_per_month WHERE stats_country_month=? AND stats_country_year=? AND stats_country_language=? AND stats_country_name=?"); 
$stmt->bind_param("ssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_geoname_country_name);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_stats_country_id, $get_stats_country_unique, $get_stats_country_hits) = $row;
if($get_stats_country_id == ""){
	$one = 1;
	$stmt = $mysqli->prepare("INSERT INTO $t_stats_countries_per_month
		(stats_country_id, stats_country_month, stats_country_year, stats_country_language, stats_country_name, 
		stats_country_alpha_2, stats_country_unique, stats_country_hits) 
		VALUES 
		(NULL,?,?,?,?,
		?,?,?)");
	$stmt->bind_param("sssssss", $get_unprocessed_month, $get_unprocessed_year, $inp_language, $inp_geoname_country_name, $inp_geoname_country_iso_code, 
		$one, $one); 
	$stmt->execute();

}
else{
	// We have record, if unique
	if($get_stats_visit_per_year_ip_id == ""){
		// Unique + hits
		$inp_unique = $get_stats_country_unique+1;
		$inp_hits   = $get_stats_country_hits+1;
		if ($mysqli->query("UPDATE $t_stats_countries_per_month SET stats_country_unique=$inp_unique, stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") !== TRUE) {
			echo "Error MySQLi update (Country :: Month :: Unique + hits): " . $mysqli->error; die;
		}
	}
	else{
		// Hits
		$inp_hits = $get_stats_country_hits+1;
		if ($mysqli->query("UPDATE $t_stats_countries_per_month SET stats_country_hits=$inp_hits WHERE stats_country_id=$get_stats_country_id") !== TRUE) {
			echo "Error MySQLi update (Country :: Month :: Hits): " . $mysqli->error; die;
		}
	}
}


?>