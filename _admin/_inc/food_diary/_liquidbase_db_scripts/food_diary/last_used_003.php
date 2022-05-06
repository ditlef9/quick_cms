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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_diary_last_used") or die(mysqli_error($link)); 


echo"
	<!-- food_diary_last_used -->
	";
	$query = "SELECT * FROM $t_food_diary_last_used";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_diary_last_used: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_diary_last_used(
	  	 last_used_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(last_used_id), 
	  	   last_used_user_id INT,
	  	   last_used_hour_name VARCHAR(20),
	  	   last_used_food_id INT,
	  	   last_used_recipe_id INT,
	  	   last_used_meal_id INT,
	  	   last_used_times INT,
	  	   last_used_created_datetime DATETIME,
	  	   last_used_updated_datetime DATETIME,
	  	   last_used_name VARCHAR(200),
	  	   last_used_manufacturer VARCHAR(200),
	  	   last_used_image_path VARCHAR(200),
	  	   last_used_image_thumb_132x132 VARCHAR(200),

	  	   last_used_main_category_id INT,
	  	   last_used_sub_category_id INT,

	  	   last_used_metric_or_us VARCHAR(20),
	  	   last_used_selected_serving_size DOUBLE,
	  	   last_used_selected_measurement VARCHAR(20),

	  	   last_used_serving_size_metric DOUBLE,
	  	   last_used_serving_size_measurement_metric VARCHAR(50),
	  	   last_used_serving_size_us DOUBLE,
	  	   last_used_serving_size_measurement_us VARCHAR(50),

	  	   last_used_serving_size_pcs DOUBLE,
	  	   last_used_serving_size_pcs_measurement VARCHAR(50),


	  	   last_used_energy_metric DOUBLE,
	  	   last_used_fat_metric DOUBLE,
	  	   last_used_saturated_fat_metric DOUBLE,
	  	   last_used_monounsaturated_fat_metric DOUBLE,
	  	   last_used_polyunsaturated_fat_metric DOUBLE,
	  	   last_used_cholesterol_metric DOUBLE,
	  	   last_used_carbohydrates_metric DOUBLE,
	  	   last_used_carbohydrates_of_which_sugars_metric DOUBLE,
	  	   last_used_dietary_fiber_metric DOUBLE,
	  	   last_used_proteins_metric DOUBLE,
	  	   last_used_salt_metric DOUBLE,
	  	   last_used_sodium_metric INT,

	  	   last_used_energy_us DOUBLE,
	  	   last_used_fat_us DOUBLE,
	  	   last_used_saturated_fat_us DOUBLE,
	  	   last_used_monounsaturated_fat_us DOUBLE,
	  	   last_used_polyunsaturated_fat_us DOUBLE,
	  	   last_used_cholesterol_us DOUBLE,
	  	   last_used_carbohydrates_us DOUBLE,
	  	   last_used_carbohydrates_of_which_sugars_us DOUBLE,
	  	   last_used_dietary_fiber_us DOUBLE,
	  	   last_used_proteins_us DOUBLE,
	  	   last_used_salt_us DOUBLE,
	  	   last_used_sodium_us INT,

	  	   last_used_energy_serving DOUBLE,
	  	   last_used_fat_serving DOUBLE,
	  	   last_used_saturated_fat_serving DOUBLE,
	  	   last_used_monounsaturated_fat_serving DOUBLE,
	  	   last_used_polyunsaturated_fat_serving DOUBLE,
	  	   last_used_cholesterol_serving DOUBLE,
	  	   last_used_carbohydrates_serving DOUBLE,
	  	   last_used_carbohydrates_of_which_sugars_serving DOUBLE,
	  	   last_used_dietary_fiber_serving DOUBLE,
	  	   last_used_proteins_serving DOUBLE,
	  	   last_used_salt_serving DOUBLE,
	  	   last_used_sodium_serving INT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //food_diary_last_used -->
";
?>