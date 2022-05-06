<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/age_restrictions.php
* Version 1.0.0
* Date 15:43 18.10.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_diary_goals") or die(mysqli_error($link)); 


echo"

	<!-- diet_goal -->
	";
	$query = "SELECT * FROM $t_food_diary_goals";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_diary_goals: $row_cnt</p>
		";
	}
	else{

		mysqli_query($link, "CREATE TABLE $t_food_diary_goals(
	  	 goal_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(goal_id), 
	  	   goal_user_id INT,
	  	   goal_kg_feet_inches VARCHAR(50),
	  	   goal_current_weight INT,
	  	   goal_current_fat_percentage INT,
	  	   goal_target_weight INT,
	  	   goal_target_fat_percentage INT,
	  	   goal_i_want_to VARCHAR(50),
	  	   goal_weekly_goal VARCHAR(50),
	  	   goal_date DATE,
	  	   goal_activity_level DOUBLE,
	  	   goal_current_bmi INT,
	  	   goal_target_bmi INT,
	  	   goal_current_bmr_calories INT,
	  	   goal_current_bmr_fat INT,
	  	   goal_current_bmr_carbs INT,
	  	   goal_current_bmr_proteins INT,
	  	   goal_current_sedentary_calories INT,
	  	   goal_current_sedentary_fat INT,
	  	   goal_current_sedentary_carbs INT,
	  	   goal_current_sedentary_proteins INT,
	  	   goal_current_with_activity_calories INT,
	  	   goal_current_with_activity_fat INT,
	  	   goal_current_with_activity_carbs INT,
	  	   goal_current_with_activity_proteins INT,
	  	   goal_target_bmr_calories INT,
	  	   goal_target_bmr_fat INT,
	  	   goal_target_bmr_carbs INT,
	  	   goal_target_bmr_proteins INT,
	  	   goal_target_sedentary_calories INT,
	  	   goal_target_sedentary_fat INT,
	  	   goal_target_sedentary_carbs INT,
	  	   goal_target_sedentary_proteins INT,
	  	   goal_target_with_activity_calories INT,
	  	   goal_target_with_activity_fat INT,
	  	   goal_target_with_activity_carbs INT,
	  	   goal_target_with_activity_proteins INT,
	  	   goal_updated DATETIME,
	  	   goal_synchronized VARCHAR(50),
	  	   goal_notes VARCHAR(50))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //diet_goal -->

";
?>