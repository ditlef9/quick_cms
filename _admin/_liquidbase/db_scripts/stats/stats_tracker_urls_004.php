<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_tracker_urls = $mysqlPrefixSav . "stats_tracker_urls";



	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_tracker_urls") or die(mysqli_error());



	$query = "SELECT * FROM $t_stats_tracker_urls LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_tracker_urls (
					url_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(url_id), 
					url_tracker_id INT,
					url_value VARCHAR(500),
					url_title VARCHAR(500),
					url_title_fetched INT,
					url_day INT,
					url_month INT,
					url_month_short VARCHAR(10),
					url_year INT,
					url_time_start INT,
					url_hour_minute_start VARCHAR(5),
					url_time_end INT,
					url_hour_minute_end VARCHAR(5),
					url_seconds_spent INT,
					url_time_spent VARCHAR(25))")
					or die(mysqli_error($link));
	}

}
?>