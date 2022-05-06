<?php
/**
*
* File: _admin/_inc/workout_diary/_liquidbase_db_scripts/entries.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_diary_entries");


echo"
	<!-- $t_workout_diary_entries -->
	";
	$query = "SELECT * FROM $t_workout_diary_entries";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_diary_entries: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_workout_diary_entries(
	  	 workout_diary_entry_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(workout_diary_entry_id), 
	  	   workout_diary_entry_user_id INT,
		   workout_diary_entry_session_id INT,
		   workout_diary_entry_session_main_id INT,
		   workout_diary_entry_date DATE,
		   workout_diary_entry_year INT,
		   workout_diary_entry_month INT,
		   workout_diary_entry_day INT,
		   workout_diary_entry_week INT,
		   workout_diary_entry_exercise_id INT,
		   workout_diary_entry_exercise_title VARCHAR(250),
		   workout_diary_entry_measurement VARCHAR(250),
		   workout_diary_entry_set_a_weight INT,
		   workout_diary_entry_set_a_reps INT,
		   workout_diary_entry_set_b_weight INT,
		   workout_diary_entry_set_b_reps INT,
		   workout_diary_entry_set_c_weight INT,
		   workout_diary_entry_set_c_reps INT,
		   workout_diary_entry_set_d_weight INT,
		   workout_diary_entry_set_d_reps INT,
		   workout_diary_entry_set_e_weight INT,
		   workout_diary_entry_set_e_reps INT,
		   workout_diary_entry_set_avg_weight INT,
		   workout_diary_entry_set_avg_reps INT,
		   workout_diary_entry_velocity_a double,
		   workout_diary_entry_velocity_b double,
		   workout_diary_entry_velocity_measurement VARCHAR(250),
		   workout_diary_entry_distance INT,
		   workout_diary_entry_distance_measurement VARCHAR(250),
		   workout_diary_entry_duration_hh INT,
		   workout_diary_entry_duration_mm INT,
		   workout_diary_entry_duration_ss INT,
		   workout_diary_entry_notes TEXT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //diary s -->
";
?>