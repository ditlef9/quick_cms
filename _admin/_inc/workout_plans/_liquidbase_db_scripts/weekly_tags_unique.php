<?php
/**
*
* File: _admin/_inc/workout_plans/_liquidbase_db_scripts/entries.php
* Version 1.0.0
* Date 14:28 25.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_plans_weekly_tags_unique");


echo"


	<!-- workout_plans_weekly_tags_unique -->
	";
	$query = "SELECT * FROM $t_workout_plans_weekly_tags_unique";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_plans_weekly_tags_unique: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_workout_plans_weekly_tags_unique(
	  	 tag_unique_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(tag_unique_id), 
		   tag_unique_language VARCHAR(250),
	  	   tag_unique_title VARCHAR(250),
	  	   tag_unique_title_clean VARCHAR(250),
	  	   tag_unique_no_of_workout_plans INT,
	  	   tag_unique_hits INT,
	  	   tag_unique_hits_ipblock TEXT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //workout_plans_weekly_tags_unique -->
";
?>