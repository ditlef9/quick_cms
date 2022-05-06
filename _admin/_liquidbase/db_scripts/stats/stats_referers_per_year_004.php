<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_referers_per_year  = $mysqlPrefixSav . "stats_referers_per_year";
	$t_stats_referers_per_month = $mysqlPrefixSav . "stats_referers_per_month";

	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_referers_per_year") or die(mysqli_error());



	// Stats :: REFERER
	$query = "SELECT * FROM $t_stats_referers_per_year LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_referers_per_year(
					stats_referer_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_referer_id), 
					stats_referer_year YEAR,
					stats_referer_language VARCHAR(5),
					stats_referer_from_url VARCHAR(500),
					stats_referer_to_url VARCHAR(500),
					stats_referer_unique INT,
					stats_referer_hits INT)")
					 or die(mysqli_error($link));
	}


}
?>