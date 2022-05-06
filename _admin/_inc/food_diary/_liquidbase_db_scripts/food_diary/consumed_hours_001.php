<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/categories_translations.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_diary_consumed_hours") or die(mysqli_error($link)); 


echo"

	<!-- food_diary_totals_meals -->
	";
	$query = "SELECT * FROM $t_food_diary_consumed_hours";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_diary_consumed_hours: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_diary_consumed_hours(
	  	 consumed_hour_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(consumed_hour_id), 
	  	   consumed_hour_user_id INT,
	  	   consumed_hour_date DATE,
	  	   consumed_hour_name VARCHAR(50),
	  	   consumed_hour_energy DOUBLE,
	  	   consumed_hour_fat DOUBLE,
	  	   consumed_hour_saturated_fat DOUBLE,
	  	   consumed_hour_monounsaturated_fat DOUBLE,
	  	   consumed_hour_polyunsaturated_fat DOUBLE,
	  	   consumed_hour_cholesterol DOUBLE,
	  	   consumed_hour_carbohydrates DOUBLE,
	  	   consumed_hour_carbohydrates_of_which_sugars DOUBLE,
	  	   consumed_hour_dietary_fiber DOUBLE,
	  	   consumed_hour_proteins DOUBLE,
	  	   consumed_hour_salt DOUBLE,
	  	   consumed_hour_sodium INT,
	  	   consumed_hour_updated_datetime DATETIME,
	  	   consumed_hour_synchronized VARCHAR(50))")
		   or die(mysqli_error());
	}
	echo"
	<!-- //food_diary_entires -->

";
?>