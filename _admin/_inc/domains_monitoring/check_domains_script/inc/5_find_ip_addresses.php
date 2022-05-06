<?php
/* Functions */
function get_domain($host){
	$host_array = explode(".", $host);
	$host_array_size = sizeof($host_array);
	if($host_array_size < 2){
		$domain = "$host";
	}
	else{
		$sld = $host_array[$host_array_size-2]; // one
		$tld = $host_array[$host_array_size-1]; // com
		$domain = "$sld.$tld";
	}
	return $domain;
}
echo"
";

// Find IP addresses
$query = "SELECT filtered_id, filtered_domain_id, filtered_domain_value, filtered_group_id, filtered_by_user_id, filtered_date_saying, filtered_datetime, filtered_domain_sld, filtered_domain_tld, filtered_domain_sld_length, filtered_score, filtered_domain_registered_date, filtered_domain_registered_date_saying, filtered_domain_registered_datetime, filtered_domain_seen_before_times, filtered_domain_ip, filtered_domain_host_name, filtered_domain_host_url, filtered_domain_filters_activated, filtered_domain_seen_by_group, filtered_domain_emailed, filtered_notes FROM $t_domains_monitoring_domains_filtered WHERE filtered_domain_ip='' ORDER BY filtered_id DESC LIMIT 0,1";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_filtered_id, $get_current_filtered_domain_id, $get_current_filtered_domain_value, $get_current_filtered_group_id, $get_current_filtered_by_user_id, $get_current_filtered_date_saying, $get_current_filtered_datetime, $get_current_filtered_domain_sld, $get_current_filtered_domain_tld, $get_current_filtered_domain_sld_length, $get_current_filtered_score, $get_current_filtered_domain_registered_date, $get_current_filtered_domain_registered_date_saying, $get_current_filtered_domain_registered_datetime, $get_current_filtered_domain_seen_before_times, $get_current_filtered_domain_ip, $get_current_filtered_domain_host_name, $get_current_filtered_domain_host_url, $get_current_filtered_domain_filters_activated, $get_current_filtered_domain_seen_by_group, $get_current_filtered_domain_emailed, $get_current_filtered_notes) = $row;
if($get_current_filtered_id == ""){
	echo"<h2>No IPs to find</h2>";

	// Header
	echo"
	<meta http-equiv=\"refresh\" content=\"2;url=check_domains_script.php?inc=1_check_ip_and_host&amp;domain_id=$get_current_filtered_domain_id&amp;start_time=$start_time\" />
	";
}
else{
	// Headline H3 :: Domain
	echo"
	<h2>$get_current_filtered_domain_value</h2>";

	// IP
	$inp_domain_ip = gethostbyname($get_current_filtered_domain_value); 
	if($inp_domain_ip == "" OR $inp_domain_ip == "$get_current_filtered_domain_value"){
		$inp_domain_ip = "NA";
	}
	$inp_domain_ip = output_html($inp_domain_ip);
	$inp_domain_ip_mysql = quote_smart($link, $inp_domain_ip);

	// Host addr
	$inp_host_addr = "NA";
	if (filter_var($inp_domain_ip, FILTER_VALIDATE_IP)) {
		$inp_host_addr = gethostbyaddr($inp_domain_ip);
		if($inp_host_addr == "" OR $inp_host_addr == "$inp_domain_ip"){
			$inp_host_addr = "NA";
		}
		$inp_host_addr = output_html($inp_host_addr);
	}
	$inp_host_addr_mysql = quote_smart($link, $inp_host_addr);

	// Host name and URL
	$inp_host_name = "NA";
	$inp_host_url = "NA";
	if($inp_host_addr != "NA"){
		$inp_host_url = get_domain($inp_host_addr);
		$inp_host_name = ucfirst($inp_host_url);
		$get_current_domain_host_url = "https://$inp_host_url";
	}
	$inp_host_name = output_html($inp_host_name);
	$inp_host_name_mysql = quote_smart($link, $inp_host_name);

	$inp_host_url = output_html($inp_host_url);
	$inp_host_url_mysql = quote_smart($link, $inp_host_url);

	mysqli_query($link, "UPDATE $t_domains_monitoring_domains_filtered SET 
					filtered_domain_ip=$inp_domain_ip_mysql,
					filtered_domain_host_addr=$inp_host_addr_mysql,
					filtered_domain_host_name=$inp_host_name_mysql,
					filtered_domain_host_url=$inp_host_url_mysql
					 WHERE filtered_id=$get_current_filtered_id") or die(mysql_error($link));
	
	// Header
	echo"
	<meta http-equiv=\"refresh\" content=\"0;url=check_domains_script.php?inc=1_check_ip_and_host&amp;domain_id=$get_current_filtered_domain_id&amp;start_time=$start_time\" />
	";
} // domains not checked




?>