<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_unprocessed = $mysqlPrefixSav . "stats_unprocessed";


	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_unprocessed") or die(mysqli_error());


	// Stats :: Unprocessed
	$query = "SELECT * FROM $t_stats_unprocessed LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_unprocessed(
		   			unprocessed_id INT NOT NULL AUTO_INCREMENT,
		   			  PRIMARY KEY(unprocessed_id), 
		   			  unprocessed_first_datetime DATETIME,
		   			  unprocessed_last_datetime DATETIME,
		   			  unprocessed_year INT,
		   			  unprocessed_month INT,
		   			  unprocessed_day INT,
		   			  unprocessed_week INT,
		   			  unprocessed_ip VARCHAR(250),
		   			  unprocessed_user_agent VARCHAR(250),
		   			  unprocessed_accept_language VARCHAR(250),
		   			  unprocessed_language VARCHAR(5),
		   			  unprocessed_first_request_uri VARCHAR(250),
		   			  unprocessed_last_request_uri VARCHAR(250),
		   			  unprocessed_first_referer VARCHAR(250),
		   			  unprocessed_last_referer VARCHAR(250),
		   			  unprocessed_hits INT)")
	  				or die(mysqli_error($link));
	}


}
?>