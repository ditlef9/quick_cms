<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_pages_visits_per_month = $mysqlPrefixSav . "stats_pages_visits_per_month";
	$t_stats_pages_visits_per_year 	= $mysqlPrefixSav . "stats_pages_visits_per_year";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_pages_visits_per_year") or die(mysqli_error());


	// Stats :: Dayli
	$query = "SELECT * FROM $t_stats_pages_visits_per_year LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_pages_visits_per_year(
					stats_pages_per_year_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_pages_per_year_id), 
					stats_pages_per_year_year YEAR,
					stats_pages_per_year_language VARCHAR(5),
					stats_pages_per_year_url VARCHAR(200),
					stats_pages_per_year_title VARCHAR(200),
					stats_pages_per_year_title_fetched INT,
					stats_pages_per_year_human_unique INT,
					stats_pages_per_year_unique_desktop INT,
					stats_pages_per_year_unique_mobile INT,
					stats_pages_per_year_unique_bots INT,
					stats_pages_per_year_updated_time INT)")
					or die(mysqli_error($link));
	}

}
?>