<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_comments_per_month 	= $mysqlPrefixSav . "stats_comments_per_month";
	$t_stats_comments_per_year 	= $mysqlPrefixSav . "stats_comments_per_year";



	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_comments_per_month") or die(mysqli_error());



	$query = "SELECT * FROM $t_stats_comments_per_month LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_comments_per_month(
		   stats_comments_id INT NOT NULL AUTO_INCREMENT,
		   PRIMARY KEY(stats_comments_id), 
		   stats_comments_month INT,
		   stats_comments_month_full VARCHAR(50),
		   stats_comments_month_short VARCHAR(50),
		   stats_comments_year INT,
		   stats_comments_language VARCHAR(5),
		   stats_comments_comments_written INT,
		   stats_comments_comments_written_diff_from_last_month INT,
		   stats_comments_last_updated DATETIME,
		   stats_comments_last_updated_day INT,
		   stats_comments_last_updated_month INT,
		   stats_comments_last_updated_year INT)")
		or die(mysqli_error($link));
	}


}
?>