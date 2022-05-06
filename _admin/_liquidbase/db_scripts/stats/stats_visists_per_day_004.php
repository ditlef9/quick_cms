<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_visists_per_day 	= $mysqlPrefixSav . "stats_visists_per_day";
	$t_stats_visists_per_month 	= $mysqlPrefixSav . "stats_visists_per_month";
	$t_stats_visists_per_year	= $mysqlPrefixSav . "stats_visists_per_year";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_visists_per_day") or die(mysqli_error());


	// Stats :: Dayli
	$query = "SELECT * FROM $t_stats_visists_per_day LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_visists_per_day(
					stats_visit_per_day_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_visit_per_day_id), 
					stats_visit_per_day_day INT,
					stats_visit_per_day_day_full VARCHAR(16),
					stats_visit_per_day_day_three VARCHAR(3),
					stats_visit_per_day_day_single VARCHAR(1),
					stats_visit_per_day_month INT,
					stats_visit_per_day_month_full VARCHAR(16),
					stats_visit_per_day_month_short VARCHAR(10),
					stats_visit_per_day_year YEAR,
					stats_visit_per_day_language VARCHAR(5),
					stats_visit_per_day_human_unique INT,
					stats_visit_per_day_human_unique_diff_from_yesterday INT,
					stats_visit_per_day_human_unique_diff_percentage double,
					stats_visit_per_day_human_average_duration VARCHAR(200),
					stats_visit_per_day_human_new_visitor_unique INT,
					stats_visit_per_day_human_returning_visitor_unique INT,
					stats_visit_per_day_unique_desktop INT,
					stats_visit_per_day_unique_mobile INT,
					stats_visit_per_day_unique_bots INT,
					stats_visit_per_day_hits_total INT,
					stats_visit_per_day_hits_human INT,
					stats_visit_per_day_hits_desktop INT,
					stats_visit_per_day_hits_mobile INT,
					stats_visit_per_day_hits_bots INT)")
					or die(mysqli_error($link));
	}

}
?>