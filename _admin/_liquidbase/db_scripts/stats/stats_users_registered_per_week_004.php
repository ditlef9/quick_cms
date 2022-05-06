<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_users_registered_per_week = $mysqlPrefixSav . "stats_users_registered_per_week";



	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_users_registered_per_week") or die(mysqli_error());



	$query = "SELECT * FROM $t_stats_users_registered_per_week LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_users_registered_per_week (
					stats_registered_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_registered_id), 
					stats_registered_week INT,
					stats_registered_year INT,
					stats_registered_language VARCHAR(5),
					stats_registered_users_registed INT,
					stats_registered_users_registed_diff_from_last_week INT)")
					or die(mysqli_error($link));
	}

}
?>