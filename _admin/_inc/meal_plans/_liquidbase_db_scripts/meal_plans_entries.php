<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/downloads_index.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_meal_plans_entries") or die(mysqli_error($link)); 


echo"





	<!-- meal_plans_entries -->
	";
	$query = "SELECT * FROM $t_meal_plans_entries";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_meal_plans_entries: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_meal_plans_entries(
	  	 entry_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(entry_id), 
	  	   entry_meal_plan_id INT,
	  	   entry_day_number INT,
	  	   entry_meal_number INT,
	  	   entry_weight INT,
	  	   entry_food_id INT,
	  	   entry_recipe_id INT,
	  	   entry_name VARCHAR(250),
	  	   entry_manufacturer_name VARCHAR(250),
	  	   entry_main_category_id INT,
	  	   entry_sub_category_id INT,
	  	   entry_serving_size DOUBLE,
	  	   entry_serving_size_measurement VARCHAR(250),
	  	   entry_energy_per_entry DOUBLE,
	  	   entry_fat_per_entry DOUBLE,
	  	   entry_carb_per_entry DOUBLE,
	  	   entry_protein_per_entry DOUBLE,
	  	   entry_text TEXT)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //meal_plans_entries -->

";
?>