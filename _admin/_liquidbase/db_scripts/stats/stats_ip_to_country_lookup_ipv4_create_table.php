<?php
if(isset($_SESSION['admin_user_id'])){


	// Database from https://db-ip.com/db/
	// Updated 2021-05-12	

	/*- Tables -------------------------------------------------------------------------- */
	$t_stats_ip_to_country_lookup_ipv4 	= $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv4";
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_ip_to_country_lookup_ipv4") or die(mysqli_error());


	// Stats
	$query = "SELECT * FROM $t_stats_ip_to_country_lookup_ipv4 LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{

		mysqli_query($link, "CREATE TABLE $t_stats_ip_to_country_lookup_ipv4 (
					ip_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(ip_id), 
					`addr_type` enum('ipv4','ipv6') NOT NULL,
					`ip_start` varbinary(16) NOT NULL,
					`ip_end` varbinary(16) NOT NULL,
					`country` char(2) NOT NULL)") or die(mysqli_error($link));

	} // Create table

	
} // admin
?>