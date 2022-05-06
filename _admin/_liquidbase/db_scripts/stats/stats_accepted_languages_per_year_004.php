<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_accepted_languages_per_month	= $mysqlPrefixSav . "stats_accepted_languages_per_month";
	$t_stats_accepted_languages_per_year	= $mysqlPrefixSav . "stats_accepted_languages_per_year";

	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_accepted_languages_per_year") or die(mysqli_error());


	// Stats :: Accepted language
	$query = "SELECT * FROM $t_stats_accepted_languages_per_year LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_accepted_languages_per_year(
		   			stats_accepted_language_id INT NOT NULL AUTO_INCREMENT,
		   			  PRIMARY KEY(stats_accepted_language_id), 
		  			   stats_accepted_language_year INT,
		  			   stats_accepted_language_language VARCHAR(5),
		  			   stats_accepted_language_name VARCHAR(250),
		  			   stats_accepted_language_unique INT,
		  			   stats_accepted_language_hits INT)")
	  				or die(mysqli_error($link));
	}


}
?>