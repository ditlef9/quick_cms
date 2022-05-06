<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_comments_per_week = $mysqlPrefixSav . "stats_comments_per_week";



	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_comments_per_week") or die(mysqli_error());



	$query = "SELECT * FROM $t_stats_comments_per_week LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_comments_per_week(
		   stats_comments_id INT NOT NULL AUTO_INCREMENT,
		   PRIMARY KEY(stats_comments_id), 
		   stats_comments_week INT,
		   stats_comments_month INT,
		   stats_comments_year INT,
		   stats_comments_language VARCHAR(5),
		   stats_comments_comments_written INT,
		   stats_comments_comments_written_diff_from_last_week INT)")
		or die(mysqli_error($link));
	}


}
?>