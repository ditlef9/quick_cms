<?php
/**
*
* File: _admin/_inc/workout_diary/_liquidbase_db_scripts/workout_diary_plans.php
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

	<!-- $t_workout_diary_plans -->
	";
	$query = "SELECT * FROM $t_workout_diary_plans";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_diary_plans: $row_cnt</p>
		";
	}
	else{


		mysqli_query($link, "CREATE TABLE $t_workout_diary_plans(
	  	 workout_diary_plan_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(workout_diary_plan_id), 
	  	   workout_diary_plan_user_id INT,
		   workout_diary_plan_weight INT,
	  	   workout_diary_plan_period_id INT,
	  	   workout_diary_plan_session_id INT,
	  	   workout_diary_plan_weekly_id INT,
	  	   workout_diary_plan_yearly_id INT,
	  	   workout_diary_plan_title VARCHAR(200),
	  	   workout_diary_plan_date DATE,
	  	   workout_diary_plan_notes TEXT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //workout_diary_plans -->
";
?>