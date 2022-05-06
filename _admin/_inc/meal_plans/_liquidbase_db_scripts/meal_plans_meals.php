<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/downloads_main_categories.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_meal_plans_meals") or die(mysqli_error($link)); 


echo"

	<!-- meal_plans_meals -->
	";
	$query = "SELECT * FROM $t_meal_plans_meals";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_meal_plans_meals: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_meal_plans_meals(
	  	 meal_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(meal_id), 
	  	   meal_meal_plan_id INT,
	  	   meal_day_number INT,
	  	   meal_number INT,
	  	   meal_energy DOUBLE,
	  	   meal_fat DOUBLE,
	  	   meal_carb DOUBLE,
	  	   meal_protein DOUBLE)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //meal_plans_days -->

";
?>