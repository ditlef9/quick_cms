<?php
if(isset($_SESSION['admin_user_id'])){

	$t_stats_visists_per_week = $mysqlPrefixSav . "stats_visists_per_week";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_visists_per_week") or die(mysqli_error());


	// Stats :: Dayli
	$query = "SELECT * FROM $t_stats_visists_per_week LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_visists_per_week(
					stats_visit_per_week_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_visit_per_week_id), 
					stats_visit_per_week_week INT,
					stats_visit_per_week_month VARCHAR(50),
					stats_visit_per_week_month_short VARCHAR(50),
					stats_visit_per_week_year YEAR,
					stats_visit_per_week_language VARCHAR(5),
					stats_visit_per_week_human_unique INT,
					stats_visit_per_week_human_unique_diff_from_last_week INT,
					stats_visit_per_week_human_unique_diff_percentage double,
					stats_visit_per_week_human_average_duration VARCHAR(200),
					stats_visit_per_week_human_new_visitor_unique INT,
					stats_visit_per_week_human_returning_visitor_unique INT,
					stats_visit_per_week_unique_desktop INT,
					stats_visit_per_week_unique_mobile INT,
					stats_visit_per_week_unique_bots INT,
					stats_visit_per_week_hits_total INT,
					stats_visit_per_week_hits_human INT,
					stats_visit_per_week_hits_desktop INT,
					stats_visit_per_week_hits_mobile INT,
					stats_visit_per_week_hits_bots INT)")
					or die(mysqli_error($link));
	}

}
?>