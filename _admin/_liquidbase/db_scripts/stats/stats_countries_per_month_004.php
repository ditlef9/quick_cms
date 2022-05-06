<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_countries_per_year  = $mysqlPrefixSav . "stats_countries_per_year";
	$t_stats_countries_per_month = $mysqlPrefixSav . "stats_countries_per_month";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_countries_per_month") or die(mysqli_error());


	// Stats :: Country 
	$query = "SELECT * FROM $t_stats_countries_per_month LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_countries_per_month(
	 	  			stats_country_id INT NOT NULL AUTO_INCREMENT,
	 	   			PRIMARY KEY(stats_country_id), 
					stats_country_month INT,
			   		stats_country_year YEAR,
					stats_country_language VARCHAR(5),
			   		stats_country_name VARCHAR(200),
			   		stats_country_alpha_2 VARCHAR(2),
			   		stats_country_unique INT,
			   		stats_country_hits INT)")
	   				or die(mysqli_error($link));
	}

}
?>