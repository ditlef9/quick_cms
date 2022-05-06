<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_pages_visits_per_year 		= $mysqlPrefixSav . "stats_pages_visits_per_year";
	$t_stats_pages_visits_per_year_ips 	= $mysqlPrefixSav . "stats_pages_visits_per_year_ips";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_pages_visits_per_year_ips") or die(mysqli_error());


	// Stats :: Dayli
	$query = "SELECT * FROM $t_stats_pages_visits_per_year_ips LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_pages_visits_per_year_ips(
					stats_pages_per_year_ip_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_pages_per_year_ip_id), 
					stats_pages_per_year_ip_year YEAR,
					stats_pages_per_year_ip_language VARCHAR(5),
					stats_pages_per_year_ip_page_id INT,
					stats_pages_per_year_ip_ip VARCHAR(500))")
					or die(mysqli_error($link));
	}

}
?>