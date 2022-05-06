<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_bots_per_month	= $mysqlPrefixSav . "stats_bots_per_month";
	$t_stats_bots_per_year	= $mysqlPrefixSav . "stats_bots_per_year";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_bots_per_year") or die(mysqli_error());



	// Stats :: Bots
	$query = "SELECT * FROM $t_stats_bots_per_year LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_bots_per_year(
					stats_bot_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_bot_id), 
					stats_bot_year INT,
		  			stats_bot_language VARCHAR(5),
					stats_bot_name VARCHAR(250),
					stats_bot_unique INT,
					stats_bot_hits INT)")
					or die(mysqli_error($link));
	}
}
?>