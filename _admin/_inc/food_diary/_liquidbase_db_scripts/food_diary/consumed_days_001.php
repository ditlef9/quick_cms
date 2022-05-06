<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/categories.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_diary_consumed_days") or die(mysqli_error($link)); 


echo"

	<!-- food_diary_totals_days -->
	";
	$query = "SELECT * FROM $t_food_diary_consumed_days";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_diary_consumed_days: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_diary_consumed_days(
	  	 consumed_day_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(consumed_day_id), 
	  	   consumed_day_user_id INT,
	  	   consumed_day_year INT,
	  	   consumed_day_month INT,
	  	   consumed_day_month_saying VARCHAR(50),
	  	   consumed_day_day INT,
	  	   consumed_day_day_saying VARCHAR(50),
	  	   consumed_day_date DATE,

		   consumed_day_lifestyle INT,

	  	   consumed_day_energy DOUBLE,
	  	   consumed_day_fat DOUBLE,
	  	   consumed_day_saturated_fat DOUBLE,
	  	   consumed_day_monounsaturated_fat DOUBLE,
	  	   consumed_day_polyunsaturated_fat DOUBLE,
	  	   consumed_day_cholesterol DOUBLE,
	  	   consumed_day_carbohydrates DOUBLE,
	  	   consumed_day_carbohydrates_of_which_sugars DOUBLE,
	  	   consumed_day_dietary_fiber DOUBLE,
	  	   consumed_day_proteins DOUBLE,
	  	   consumed_day_salt DOUBLE,
	  	   consumed_day_sodium INT,

	  	   consumed_day_target_sedentary_energy DOUBLE,
	  	   consumed_day_target_sedentary_fat DOUBLE,
	  	   consumed_day_target_sedentary_carb DOUBLE,
	  	   consumed_day_target_sedentary_protein DOUBLE,
	  	   consumed_day_target_with_activity_energy DOUBLE,
	  	   consumed_day_target_with_activity_fat DOUBLE,
	  	   consumed_day_target_with_activity_carb DOUBLE,
	  	   consumed_day_target_with_activity_protein DOUBLE,
	  	   consumed_day_diff_sedentary_energy DOUBLE,
	  	   consumed_day_diff_sedentary_fat DOUBLE,
	  	   consumed_day_diff_sedentary_carb DOUBLE,
	  	   consumed_day_diff_sedentary_protein DOUBLE,
	  	   consumed_day_diff_with_activity_energy DOUBLE,
	  	   consumed_day_diff_with_activity_fat DOUBLE,
	  	   consumed_day_diff_with_activity_carb DOUBLE,
	  	   consumed_day_diff_with_activity_protein DOUBLE,

		   consumed_day_updated_datetime DATETIME,
	  	   consumed_day_synchronized VARCHAR(50))")
		   or die(mysqli_error());
	}
	echo"
	<!-- //food_diary_entires -->

";
?>