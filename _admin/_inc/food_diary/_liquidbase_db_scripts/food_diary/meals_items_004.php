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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_diary_meals_items") or die(mysqli_error($link)); 


echo"

	<!-- food_diary_meals -->
	";
	$query = "SELECT * FROM $t_food_diary_meals_items";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_diary_meals_items: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_diary_meals_items(
	  	 item_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(item_id), 
	  	   item_user_id INT,
	  	   item_meal_id INT,
	  	   item_food_id INT,
	  	   item_recipe_id INT,
	  	   item_name VARCHAR(250),
	  	   item_name_short VARCHAR(250),
	  	   item_manufacturer_name VARCHAR(250),
	  	   item_main_category_id INT,
	  	   item_sub_category_id INT,
	  	   item_image_path VARCHAR(200),
	  	   item_image_file VARCHAR(200),
	  	   item_image_thumb_66x132 VARCHAR(200),
	  	   item_image_thumb_100x100 VARCHAR(200),
	  	   item_image_thumb_100x200 VARCHAR(200),
	  	   item_image_thumb_132x132 VARCHAR(200),
	  	   item_serving_size DOUBLE,
	  	   item_serving_size_measurement VARCHAR(250),

	  	   item_metric_or_us VARCHAR(20),
	  	   item_selected_serving_size DOUBLE,
	  	   item_selected_measurement VARCHAR(20),

	  	   item_serving_size_metric DOUBLE,
	  	   item_serving_size_measurement_metric VARCHAR(50),
	  	   item_serving_size_us DOUBLE,
	  	   item_serving_size_measurement_us VARCHAR(50),

	  	   item_serving_size_pcs DOUBLE,
	  	   item_serving_size_pcs_measurement VARCHAR(50),


	  	   item_energy_metric DOUBLE,
	  	   item_fat_metric DOUBLE,
	  	   item_saturated_fat_metric DOUBLE,
	  	   item_monounsaturated_fat_metric DOUBLE,
	  	   item_polyunsaturated_fat_metric DOUBLE,
	  	   item_cholesterol_metric DOUBLE,
	  	   item_carbohydrates_metric DOUBLE,
	  	   item_carbohydrates_of_which_sugars_metric DOUBLE,
	  	   item_dietary_fiber_metric DOUBLE,
	  	   item_proteins_metric DOUBLE,
	  	   item_salt_metric DOUBLE,
	  	   item_sodium_metric INT,

	  	   item_energy_us DOUBLE,
	  	   item_fat_us DOUBLE,
	  	   item_saturated_fat_us DOUBLE,
	  	   item_monounsaturated_fat_us DOUBLE,
	  	   item_polyunsaturated_fat_us DOUBLE,
	  	   item_cholesterol_us DOUBLE,
	  	   item_carbohydrates_us DOUBLE,
	  	   item_carbohydrates_of_which_sugars_us DOUBLE,
	  	   item_dietary_fiber_us DOUBLE,
	  	   item_proteins_us DOUBLE,
	  	   item_salt_us DOUBLE,
	  	   item_sodium_us INT,

	  	   item_energy_serving DOUBLE,
	  	   item_fat_serving DOUBLE,
	  	   item_saturated_fat_serving DOUBLE,
	  	   item_monounsaturated_fat_serving DOUBLE,
	  	   item_polyunsaturated_fat_serving DOUBLE,
	  	   item_cholesterol_serving DOUBLE,
	  	   item_carbohydrates_serving DOUBLE,
	  	   item_carbohydrates_of_which_sugars_serving DOUBLE,
	  	   item_dietary_fiber_serving DOUBLE,
	  	   item_proteins_serving DOUBLE,
	  	   item_salt_serving DOUBLE,
	  	   item_sodium_serving INT

			)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //food_diary_meals -->

";
?>