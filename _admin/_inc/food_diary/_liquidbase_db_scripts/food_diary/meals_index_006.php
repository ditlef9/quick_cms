<?php
/**
*
* File: _admin/_inc/food_diary/_liquidbase_db_scripts/food_diary/meals.php
* Version 1.0.0
* Date 11:50 20.03.2021
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_diary_meals_index") or die(mysqli_error($link)); 


echo"

	<!-- food_diary_meals_index -->
	";
	$query = "SELECT * FROM $t_food_diary_meals_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_diary_meals_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_diary_meals_index(
	  	 meal_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(meal_id), 
	  	   meal_user_id INT,
	  	   meal_hour_name VARCHAR(50),
	  	   meal_name VARCHAR(250),
	  	   meal_last_used_date DATE,
	  	   meal_used_times INT, 
	  	   meal_entries TEXT,
	  	   meal_entries_count INT, 

	  	   meal_image_path VARCHAR(250),
	  	   meal_image_file VARCHAR(250),

	  	   meal_selected_serving_size DOUBLE,
	  	   meal_selected_measurement VARCHAR(20),

	  	   meal_energy_metric DOUBLE,
	  	   meal_fat_metric DOUBLE,
	  	   meal_saturated_fat_metric DOUBLE,
	  	   meal_monounsaturated_fat_metric DOUBLE,
	  	   meal_polyunsaturated_fat_metric DOUBLE,
	  	   meal_cholesterol_metric DOUBLE,
	  	   meal_carbohydrates_metric DOUBLE,
	  	   meal_carbohydrates_of_which_sugars_metric DOUBLE,
	  	   meal_dietary_fiber_metric DOUBLE,
	  	   meal_proteins_metric DOUBLE,
	  	   meal_salt_metric DOUBLE,
	  	   meal_sodium_metric INT,

	  	   meal_energy_us DOUBLE,
	  	   meal_fat_us DOUBLE,
	  	   meal_saturated_fat_us DOUBLE,
	  	   meal_monounsaturated_fat_us DOUBLE,
	  	   meal_polyunsaturated_fat_us DOUBLE,
	  	   meal_cholesterol_us DOUBLE,
	  	   meal_carbohydrates_us DOUBLE,
	  	   meal_carbohydrates_of_which_sugars_us DOUBLE,
	  	   meal_dietary_fiber_us DOUBLE,
	  	   meal_proteins_us DOUBLE,
	  	   meal_salt_us DOUBLE,
	  	   meal_sodium_us INT,

	  	   meal_energy_serving DOUBLE,
	  	   meal_fat_serving DOUBLE,
	  	   meal_saturated_fat_serving DOUBLE,
	  	   meal_monounsaturated_fat_serving DOUBLE,
	  	   meal_polyunsaturated_fat_serving DOUBLE,
	  	   meal_cholesterol_serving DOUBLE,
	  	   meal_carbohydrates_serving DOUBLE,
	  	   meal_carbohydrates_of_which_sugars_serving DOUBLE,
	  	   meal_dietary_fiber_serving DOUBLE,
	  	   meal_proteins_serving DOUBLE,
	  	   meal_salt_serving DOUBLE,
	  	   meal_sodium_serving INT,

	  	   meal_energy_total DOUBLE,
	  	   meal_fat_total DOUBLE,
	  	   meal_saturated_total DOUBLE,
	  	   meal_monounsaturated_fat_total DOUBLE,
	  	   meal_polyunsaturated_fat_total DOUBLE,
	  	   meal_cholesterol_total DOUBLE,
	  	   meal_carbohydrates_total DOUBLE,
	  	   meal_carbohydrates_of_which_sugars_total DOUBLE,
	  	   meal_dietary_fiber_total DOUBLE,
	  	   meal_proteins_total DOUBLE,
	  	   meal_salt_total DOUBLE,
	  	   meal_sodium_total INT)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //food_diary_meals_index -->

";
?>