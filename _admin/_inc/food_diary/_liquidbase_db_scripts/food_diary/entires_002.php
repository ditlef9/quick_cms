<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/age_restrictions_accepted.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_diary_entires") or die(mysqli_error($link)); 


echo"


	<!-- food_diary_entires -->
	";
	$query = "SELECT * FROM $t_food_diary_entires";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_diary_entires: $row_cnt</p>
		";
	}
	else{
		
		mysqli_query($link, "CREATE TABLE $t_food_diary_entires(
	  	 entry_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(entry_id), 
	  	   entry_user_id INT,
	  	   entry_date DATE,
	  	   entry_date_saying VARCHAR(50),
	  	   entry_hour_name VARCHAR(20),
	  	   entry_food_id INT,
	  	   entry_recipe_id INT,
	  	   entry_meal_id INT,
	  	   entry_name VARCHAR(250),
	  	   entry_manufacturer_name VARCHAR(250),
	  	   entry_serving_size DOUBLE,
	  	   entry_serving_size_measurement VARCHAR(250),

	  	   entry_energy_per_entry DOUBLE,
	  	   entry_fat_per_entry DOUBLE,
	  	   entry_saturated_fat_per_entry DOUBLE,
	  	   entry_monounsaturated_fat_per_entry DOUBLE,
	  	   entry_polyunsaturated_fat_per_entry DOUBLE,
	  	   entry_cholesterol_per_entry DOUBLE,
	  	   entry_carbohydrates_per_entry DOUBLE,
	  	   entry_carbohydrates_of_which_sugars_per_entry DOUBLE,
	  	   entry_dietary_fiber_per_entry DOUBLE,
	  	   entry_proteins_per_entry DOUBLE,
	  	   entry_salt_per_entry DOUBLE,
	  	   entry_sodium_per_entry INT,

	  	   entry_text TEXT,
	  	   entry_deleted INT,
	  	   entry_updated_datetime DATETIME,
	  	   entry_synchronized VARCHAR(50))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //food_diary_entires -->

";
?>