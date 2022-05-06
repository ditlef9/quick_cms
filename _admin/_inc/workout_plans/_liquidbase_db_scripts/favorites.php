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

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_plans_favorites");


echo"

	<!-- workout_plans_favorites -->
	";
	$query = "SELECT * FROM $t_workout_plans_favorites";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_plans_favorites: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_workout_plans_favorites(
	  	 workout_plan_favorite_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(workout_plan_favorite_id), 
	  	   workout_plan_favorite_user_id INT,
		   workout_plan_favorite_weight INT,
	  	   workout_plan_favorite_period_id INT,
	  	   workout_plan_favorite_session_id INT,
	  	   workout_plan_favorite_weekly_id INT,
	  	   workout_plan_favorite_yearly_id INT,
	  	   workout_plan_favorite_title VARCHAR(200),
	  	   workout_plan_favorite_date DATE,
	  	   workout_plan_favorite_notes TEXT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //workout_plans_favorites -->

";
?>