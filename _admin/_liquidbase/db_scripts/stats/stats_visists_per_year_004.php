<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_visists_per_day = $mysqlPrefixSav . "stats_visists_per_day";
	$t_stats_visists_per_month = $mysqlPrefixSav . "stats_visists_per_month";
	$t_stats_visists_per_year = $mysqlPrefixSav . "stats_visists_per_year";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_visists_per_year") or die(mysqli_error());


	// Stats :: Dayli
	$query = "SELECT * FROM $t_stats_visists_per_year LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_visists_per_year(
					stats_visit_per_year_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_visit_per_year_id), 
					stats_visit_per_year_year YEAR,
					stats_visit_per_year_language VARCHAR(5),
					stats_visit_per_year_human_unique INT,
					stats_visit_per_year_human_unique_diff_from_last_year INT,
					stats_visit_per_year_human_unique_diff_percentage double,
					stats_visit_per_year_human_average_duration VARCHAR(200),
					stats_visit_per_year_human_new_visitor_unique INT,
					stats_visit_per_year_human_returning_visitor_unique INT,
					stats_visit_per_year_unique_desktop INT,
					stats_visit_per_year_unique_mobile INT,
					stats_visit_per_year_unique_bots INT,
					stats_visit_per_year_hits_total INT,
					stats_visit_per_year_hits_human INT,
					stats_visit_per_year_hits_desktop INT,
					stats_visit_per_year_hits_mobile INT,
					stats_visit_per_year_hits_bots INT)")
					or die(mysqli_error($link));
	}

}
?>