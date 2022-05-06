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

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_plans_sessions_main");


echo"


	<!-- workout_plans_sessions_main -->
	";
	$query = "SELECT * FROM $t_workout_plans_sessions_main";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_plans_sessions_main: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_workout_plans_sessions_main(
	  	 workout_session_main_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(workout_session_main_id), 
	  	   workout_session_main_user_id INT,
		   workout_session_main_session_id INT,
	  	   workout_session_main_weight INT,
	  	   workout_session_main_exercise_id INT,
	  	   workout_session_main_exercise_title VARCHAR(250),
	  	   workout_session_main_reps INT,
	  	   workout_session_main_sets INT,
	  	   workout_session_main_velocity_a double,
	  	   workout_session_main_velocity_b double,
	  	   workout_session_main_distance INT,
	  	   workout_session_main_duration INT,
	  	   workout_session_main_intensity INT,
	  	   workout_session_main_text TEXT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //workout_plans_sessions_main -->
";
?>