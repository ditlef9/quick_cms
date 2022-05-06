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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_meal_plans_days") or die(mysqli_error($link)); 


echo"

	<!-- meal_plans_days -->
	";
	$query = "SELECT * FROM $t_meal_plans_days";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_meal_plans_days: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_meal_plans_days(
	  	 day_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(day_id), 
	  	   day_meal_plan_id INT,
	  	   day_number INT,

	  	   day_energy_without_training DOUBLE,
	  	   day_fat_without_training DOUBLE,
	  	   day_carb_without_training DOUBLE,
	  	   day_protein_without_training DOUBLE,

	  	   day_sum_without_training DOUBLE,
	  	   day_fat_without_training_percentage INT,
	  	   day_carb_without_training_percentage INT,
	  	   day_protein_without_training_percentage INT,

	  	   day_energy_with_training DOUBLE,
	  	   day_fat_with_training DOUBLE,
	  	   day_carb_with_training DOUBLE,
	  	   day_protein_with_training DOUBLE,

	  	   day_sum_with_training DOUBLE,
	  	   day_fat_with_training_percentage INT,
	  	   day_carb_with_training_percentage INT,
	  	   day_protein_with_training_percentage INT)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //meal_plans_days -->
";
?>