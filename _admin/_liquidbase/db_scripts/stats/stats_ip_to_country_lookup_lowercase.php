<?php
if(isset($_SESSION['admin_user_id'])){

	/*- Tables -------------------------------------------------------------------------- */
	$t_stats_ip_to_country_lookup_ipv4 	= $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv4";
	$t_stats_ip_to_country_lookup_ipv6 	= $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv6";

	$query = "SELECT ip_id, country FROM $t_stats_ip_to_country_lookup_ipv4";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_ip_id, $get_country) = $row;
	
		$inp_country = strtolower($get_country);

		mysqli_query($link, "UPDATE $t_stats_ip_to_country_lookup_ipv4 SET country='$inp_country' WHERE ip_id=$get_ip_id") or die(mysqli_error($link));
	}

	$query = "SELECT ip_id, country FROM $t_stats_ip_to_country_lookup_ipv6";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_ip_id, $get_country) = $row;
	
		$inp_country = strtolower($get_country);

		mysqli_query($link, "UPDATE $t_stats_ip_to_country_lookup_ipv6 SET country='$inp_country' WHERE ip_id=$get_ip_id") or die(mysqli_error($link));
	}

} // admin
?>