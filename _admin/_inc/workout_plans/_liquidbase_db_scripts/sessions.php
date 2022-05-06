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

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_plans_sessions");


echo"

	<!-- session_plans -->
	";
	$query = "SELECT * FROM $t_workout_plans_sessions";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_plans_sessions: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_workout_plans_sessions(
	  	 workout_session_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(workout_session_id), 
	  	   workout_session_user_id INT,
		   workout_session_weekly_id INT,
	  	   workout_session_weight INT,
	  	   workout_session_title VARCHAR(250),
	  	   workout_session_title_clean VARCHAR(250),
	  	   workout_session_duration VARCHAR(250),
	  	   workout_session_intensity VARCHAR(250),
	  	   workout_session_repeat VARCHAR(250),
	  	   workout_session_pause VARCHAR(250),
	  	   workout_session_goal TEXT,
	  	   workout_session_warmup TEXT,
	  	   workout_session_end TEXT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //session_plans -->
";
?>