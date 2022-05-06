<?php
/**
*
* File: _admin/_inc/exercises/_liquibase/index.php
* Version 1.0.0
* Date 12:57 24.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_search_queries") or die(mysqli_error($link)); 


echo"


	<!-- search_queries -->
	";
	$query = "SELECT * FROM $t_exercise_search_queries";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_search_queries: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_exercise_search_queries(
				 query_id INT NOT NULL AUTO_INCREMENT,
				 PRIMARY KEY(query_id), 
				 query_name VARCHAR(90) NOT NULL,
				 query_language VARCHAR(2),
				 query_times BIGINT,
				 query_last_use DATETIME,
				 query_hidden INT,
				 query_no_of_results INT,
				 query_email_sendt_month INT)")
				 or die(mysql_error());
	}
	echo"
	<!-- //search_queries -->


";
?>