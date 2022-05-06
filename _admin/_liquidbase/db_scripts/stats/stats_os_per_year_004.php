<?php
if(isset($_SESSION['admin_user_id'])){

	$t_stats_os_per_year = $mysqlPrefixSav . "stats_os_per_year";

	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_os_per_year") or die(mysqli_error());




	// Stats :: OS
	$query = "SELECT * FROM $t_stats_os_per_year LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_os_per_year(
					stats_os_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_os_id), 
		   			stats_os_year YEAR,
					stats_os_language VARCHAR(5),
		   			stats_os_name VARCHAR(250),
		   			stats_os_type VARCHAR(250),
		   			stats_os_unique INT,
		   			stats_os_hits INT)")
	   			or die(mysqli_error($link));
	}

}
?>