<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_visists_per_week_ips 	= $mysqlPrefixSav . "stats_visists_per_week_ips";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_visists_per_week_ips") or die(mysqli_error());


	// Stats :: Dayli
	$query = "SELECT * FROM $t_stats_visists_per_week_ips LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_visists_per_week_ips(
					stats_visit_per_week_ip_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_visit_per_week_ip_id), 
					stats_visit_per_week_ip_week INT,
					stats_visit_per_week_ip_month INT,
					stats_visit_per_week_ip_year YEAR,
					stats_visit_per_week_ip_language VARCHAR(5),
					stats_visit_per_week_type VARCHAR(50),
					stats_visit_per_week_ip VARCHAR(500))")
					or die(mysqli_error($link));
	}

}
?>