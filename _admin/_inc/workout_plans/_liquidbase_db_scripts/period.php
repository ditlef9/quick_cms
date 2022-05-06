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

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_plans_period");


echo"

	<!-- $t_workout_plans_period -->
	";
	$query = "SELECT * FROM $t_workout_plans_period";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_plans_period: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_workout_plans_period(
	  	 workout_period_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(workout_period_id), 
	  	   workout_period_user_id INT,
	  	   workout_period_yearly_id INT,
	  	   workout_period_weight INT,
		   workout_period_language VARCHAR(20),
	  	   workout_period_title VARCHAR(250),
	  	   workout_period_title_clean VARCHAR(250),
	  	   workout_period_introduction TEXT,
	  	   workout_period_goal TEXT,
	  	   workout_period_text TEXT,
	  	   workout_period_from VARCHAR(200),
	  	   workout_period_to VARCHAR(200),
	  	   workout_period_image_path VARCHAR(250),
	  	   workout_period_image_file VARCHAR(250),
	  	   workout_period_created DATETIME,
	  	   workout_period_updated DATETIME,
	  	   workout_period_unique_hits INT,
	  	   workout_period_unique_hits_ip_block TEXT,
	  	   workout_period_comments INT,
	  	   workout_period_likes INT,
	  	   workout_period_dislikes INT,
	  	   workout_period_rating INT,
	  	   workout_period_ip_block TEXT,
		   workout_period_user_ip VARCHAR(250),
	  	   workout_period_notes TEXT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //$t_workout_plans_period -->
";
?>