<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_languages_per_year	= $mysqlPrefixSav . "stats_languages_per_year";

	// Drop table
	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_languages_per_year") or die(mysqli_error());


	// Stats :: Accepted language
	$query = "SELECT * FROM $t_stats_languages_per_year LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_languages_per_year(
		   			stats_language_id INT NOT NULL AUTO_INCREMENT,
		   			  PRIMARY KEY(stats_language_id), 
		  			   stats_language_year INT,
		  			   stats_language_name VARCHAR(250),
		  			   stats_language_iso_two VARCHAR(2),
		  			   stats_language_flag_path_16x16 VARCHAR(100),
		  			   stats_language_flag_16x16 VARCHAR(100),
		  			   stats_language_unique INT,
		  			   stats_language_hits INT)")
	  				or die(mysqli_error($link));
	}


}
?>