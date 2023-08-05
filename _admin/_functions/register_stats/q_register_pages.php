<?php
// Page URL
$page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8');
$page_url = output_html($page_url);

$inp_request_uri = $_SERVER['REQUEST_URI'];
$inp_request_uri = output_html($inp_request_uri);
$inp_request_uri_len = strlen($inp_request_uri);
if($inp_request_uri_len > 200){
	$inp_request_uri = substr($inp_request_uri, 0, 200);
	$inp_request_uri = $inp_request_uri . "...";
}


// Pages :: Year (Humans)
if($configSiteDaysToKeepPageVisitsSav != "0"){
	$page_url = $configSiteURLSav . "$get_unprocessed_first_request_uri";
	$page_url_len = strlen($page_url);
	if($page_url_len > 190){
		$page_url = substr($page_url, 0, 190);
		$page_url = $page_url . "...";
	}
	$inp_stats_page_url = "$page_url";
	$inp_stats_page_title = "";

	$stmt = $mysqli->prepare("SELECT stats_pages_per_year_id, stats_pages_per_year_human_unique, stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_year=? AND stats_pages_per_year_language=? AND stats_pages_per_year_url=?"); 
	$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_stats_page_url);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_stats_pages_per_year_id, $get_stats_pages_per_year_human_unique, $get_stats_pages_per_year_unique_desktop, $get_stats_pages_per_year_unique_mobile) = $row;
	if($get_stats_pages_per_year_id == ""){
		// This is a new page
		$inp_unique_desktop = 0;
		$inp_unique_mobile = 0;
		if($get_stats_user_agent_type == "desktop"){
			$inp_unique_desktop = 1;
		}
		elseif($get_stats_user_agent_type == "mobile"){
			$inp_unique_mobile = 1;
		}
		
		$zero = 0;
		$one = 1;
		$stmt = $mysqli->prepare("INSERT INTO $t_stats_pages_visits_per_year 
			(stats_pages_per_year_id, stats_pages_per_year_year, stats_pages_per_year_language, stats_pages_per_year_url, stats_pages_per_year_title, 
			stats_pages_per_year_title_fetched, stats_pages_per_year_human_unique,  stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile, stats_pages_per_year_unique_bots, 
			stats_pages_per_year_updated_time) 
			VALUES 
			(NULL,?,?,?,?,
			?,?,?,?,?,
			?)");
		$stmt->bind_param("ssssssssss", $get_unprocessed_year, $inp_language, $inp_stats_page_url, $inp_stats_page_title, 
			$zero, $one, $inp_unique_desktop, $inp_unique_mobile, $zero, 
			$time); 
		$stmt->execute();


		// Get page ID
		$stmt = $mysqli->prepare("SELECT stats_pages_per_year_id, stats_pages_per_year_human_unique, stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_year=? AND stats_pages_per_year_language=? AND stats_pages_per_year_url=?"); 
		$stmt->bind_param("sss", $get_unprocessed_year, $inp_language, $inp_stats_page_url);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_pages_per_year_id, $get_stats_pages_per_year_human_unique, $get_stats_pages_per_year_unique_desktop, $get_stats_pages_per_year_unique_mobile) = $row;
	
	
		// IPBlock
		$stmt = $mysqli->prepare("INSERT INTO $t_stats_pages_visits_per_year_ips 
		(stats_pages_per_year_ip_id, stats_pages_per_year_ip_year, stats_pages_per_year_ip_language, stats_pages_per_year_ip_page_id, stats_pages_per_year_ip_ip) 
		VALUES 
		(NULL,?,?,?,?)");
		$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $get_stats_pages_per_year_id, $my_ip); 
		$stmt->execute();

	}
	else{
		// We have record, if unique
		$stmt = $mysqli->prepare("SELECT stats_pages_per_year_ip_id FROM $t_stats_pages_visits_per_year_ips WHERE stats_pages_per_year_ip_year=? AND stats_pages_per_year_ip_language=? AND stats_pages_per_year_ip_page_id=? AND stats_pages_per_year_ip_ip=?"); 
		$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $get_stats_pages_per_year_id, $my_ip);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_stats_pages_per_year_ip_id) = $row;
		if($get_stats_pages_per_year_ip_id == ""){
			// New visitor for this page this year
			// echo"We have record, if unique: New visitor for this page this year<br />";
			$stmt = $mysqli->prepare("INSERT INTO $t_stats_pages_visits_per_year_ips 
				(stats_pages_per_year_ip_id, stats_pages_per_year_ip_year, stats_pages_per_year_ip_language, stats_pages_per_year_ip_page_id, stats_pages_per_year_ip_ip) 
				VALUES 
				(NULL,?,?,?,?");
			$stmt->bind_param("ssss", $get_unprocessed_year, $inp_language, $get_stats_pages_per_year_id, $my_ip); 
			$stmt->execute();

			// Unique
			$inp_unique_desktop = $get_stats_pages_per_year_unique_desktop;
			$inp_unique_mobile = $get_stats_pages_per_year_unique_mobile;
			if($get_stats_user_agent_type == "desktop"){
				$inp_unique_desktop = $inp_unique_desktop+1;
			}
			elseif($get_stats_user_agent_type == "mobile"){
				$inp_unique_mobile = $inp_unique_mobile+1;
			}
			$inp_human_unique = $inp_unique_desktop+$inp_unique_mobile;

			if ($mysqli->query("UPDATE $t_stats_pages_visits_per_year SET stats_pages_per_year_human_unique=$inp_human_unique, stats_pages_per_year_unique_desktop=$inp_unique_desktop, stats_pages_per_year_unique_mobile=$inp_unique_mobile, stats_pages_per_year_updated_time='$time' WHERE stats_pages_per_year_id=$get_stats_pages_per_year_id") !== TRUE) {
				echo "Error MySQLi update (Pages :: Year (Humans): " . $mysqli->error; die;
			}
			// echo"UPDATE $t_stats_pages_visits_per_year SET stats_pages_per_year_unique_desktop=$inp_unique_desktop, stats_pages_per_year_unique_mobile=$inp_unique_mobile, stats_pages_per_year_updated_time='$time' WHERE stats_pages_per_year_id=$get_stats_pages_per_year_id<br />";
		}
		else{
			// Delete old entries
			// echo"We have record, if unique: Delete old entries, increase hits<br />";
			// $configSiteDaysToKeepPageVisitsSav
			if ($mysqli->query("DELETE FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_updated_time < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL $configSiteDaysToKeepPageVisitsSav DAY))") !== TRUE) {
				echo "Error MySQLi delete (Pages :: Year (Humans): " . $mysqli->error; die;
			}
		}
	}
}

?>