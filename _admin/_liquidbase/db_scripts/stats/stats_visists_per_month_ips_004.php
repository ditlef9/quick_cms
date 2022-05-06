<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_visists_per_day 	= $mysqlPrefixSav . "stats_visists_per_day";
	$t_stats_visists_per_day_ips 	= $mysqlPrefixSav . "stats_visists_per_day_ips";
	$t_stats_visists_per_month 	= $mysqlPrefixSav . "stats_visists_per_month";
	$t_stats_visists_per_month_ips 	= $mysqlPrefixSav . "stats_visists_per_month_ips";
	$t_stats_visists_per_year 	= $mysqlPrefixSav . "stats_visists_per_year";
	$t_stats_visists_per_year_ips 	= $mysqlPrefixSav . "stats_visists_per_year_ips";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_visists_per_month_ips") or die(mysqli_error());


	// Stats :: Dayli
	$query = "SELECT * FROM $t_stats_visists_per_month_ips LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_visists_per_month_ips(
					stats_visit_per_month_ip_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_visit_per_month_ip_id), 
					stats_visit_per_month_ip_month INT,
					stats_visit_per_month_ip_year YEAR,
					stats_visit_per_month_ip_language VARCHAR(5),
					stats_visit_per_month_type VARCHAR(50),
					stats_visit_per_month_ip VARCHAR(500))")
					or die(mysqli_error($link));
	}

}
?>