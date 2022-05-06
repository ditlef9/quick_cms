<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_searches.php
* Version 1.0.0
* Date 17:21 31.12.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"


	<!-- Searches -->
	";
	$query = "SELECT * FROM $t_recipes_searches";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_searches: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_searches(
	  	 search_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(search_id), 
	  	   search_query VARCHAR(20),
	  	   search_language VARCHAR(20),
	  	   search_unique_count INT,
	  	   search_unique_ip_block TEXT,
	  	   search_first_datetime DATETIME,
	  	   search_first_saying VARCHAR(100),
	  	   search_last_datetime DATETIME,
	  	   search_last_saying VARCHAR(100),
		   search_found_recipes INT
	  	   )")
		   or die(mysqli_error());
	}
	echo"
	<!-- //Searches -->
";
?>