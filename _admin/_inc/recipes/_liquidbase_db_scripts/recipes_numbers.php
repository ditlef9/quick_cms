<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_numbers.php
* Version 1.0.0
* Date 17:21 31.12.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes_numbers") or die(mysqli_error());


echo"

	<!-- $t_recipes_numbers -->
	";
	$query = "SELECT * FROM $t_recipes_numbers";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_numbers: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_numbers(
	  	 number_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(number_id), 
	  	   number_recipe_id INT,
	  	   number_servings INT,

		  number_energy_metric double DEFAULT NULL,
		  number_fat_metric double DEFAULT NULL,
		  number_saturated_fat_metric double DEFAULT NULL,
		  number_monounsaturated_fat_metric double DEFAULT NULL,
		  number_polyunsaturated_fat_metric double DEFAULT NULL,
	  	  number_cholesterol_metric DOUBLE,
		  number_carbohydrates_metric double DEFAULT NULL,
		  number_carbohydrates_of_which_sugars_metric double DEFAULT NULL,
		  number_dietary_fiber_metric double DEFAULT NULL,
		  number_proteins_metric double DEFAULT NULL,
		  number_salt_metric double DEFAULT NULL,
 		  number_sodium_metric int(11) DEFAULT NULL,

		  number_energy_serving double DEFAULT NULL,
		  number_fat_serving double DEFAULT NULL,
		  number_saturated_fat_serving double DEFAULT NULL,
		  number_monounsaturated_fat_serving double DEFAULT NULL,
		  number_polyunsaturated_fat_serving double DEFAULT NULL,
	  	  number_cholesterol_serving DOUBLE,
		  number_carbohydrates_serving double DEFAULT NULL,
		  number_carbohydrates_of_which_sugars_serving double DEFAULT NULL,
		  number_dietary_fiber_serving double DEFAULT NULL,
		  number_proteins_serving double DEFAULT NULL,
		  number_salt_serving double DEFAULT NULL,
 		  number_sodium_serving int(11) DEFAULT NULL,

		  number_energy_total double DEFAULT NULL,
		  number_fat_total double DEFAULT NULL,
		  number_saturated_fat_total double DEFAULT NULL,
		  number_monounsaturated_fat_total double DEFAULT NULL,
		  number_polyunsaturated_fat_total double DEFAULT NULL,
	  	  number_cholesterol_total DOUBLE,
		  number_carbohydrates_total double DEFAULT NULL,
		  number_carbohydrates_of_which_sugars_total double DEFAULT NULL,
		  number_dietary_fiber_total double DEFAULT NULL,
		  number_proteins_total double DEFAULT NULL,
		  number_salt_total double DEFAULT NULL,
 		  number_sodium_total int(11) DEFAULT NULL)")
		   or die(mysqli_error());

		// Loop trough all and calculate?
		$query_r = "SELECT recipe_id, recipe_title FROM $t_recipes";
		$result_r = mysqli_query($link, $query_r);
		while($row_r = mysqli_fetch_row($result_r)) {
			list($get_recipe_id, $get_recipe_title) = $row_r;


			

			// Calculating total numbers
			$inp_number_energy_metric		= 0;
			$inp_number_fat_metric	 		= 0;
			$inp_number_saturated_fat_metric	= 0;
			$inp_number_monounsaturated_fat_metric	= 0;
			$inp_number_polyunsaturated_fat_metric	= 0;
			$inp_number_cholesterol_metric	 	= 0;
			$inp_number_carbohydrates_metric	= 0;
			$inp_number_carbohydrates_of_which_sugars_metric  = 0;
			$inp_number_dietary_fiber_metric	= 0;
			$inp_number_proteins_metric	 	= 0;
			$inp_number_salt_metric			= 0;
			$inp_number_sodium_metric		= 0;

			$inp_number_energy_serving		= 0;
			$inp_number_fat_serving			= 0;
			$inp_number_saturated_fat_serving	= 0;
			$inp_number_monounsaturated_fat_serving	= 0;
			$inp_number_polyunsaturated_fat_serving	= 0;
			$inp_number_cholesterol_serving		= 0;
			$inp_number_carbohydrates_serving	= 0;
			$inp_number_carbohydrates_of_which_sugars_serving	 = 0;
			$inp_number_dietary_fiber_serving	= 0;
			$inp_number_proteins_serving	 	= 0;
			$inp_number_salt_serving		= 0;
			$inp_number_sodium_serving		= 0;

			$inp_number_energy_total		= 0;
			$inp_number_fat_total			= 0;
			$inp_number_saturated_fat_total	 	= 0;
			$inp_number_monounsaturated_fat_total	= 0;
			$inp_number_polyunsaturated_fat_total	= 0;
			$inp_number_cholesterol_total		= 0;
			$inp_number_carbohydrates_total		= 0;
			$inp_number_carbohydrates_of_which_sugars_total = 0;
			$inp_number_dietary_fiber_total		= 0;
			$inp_number_proteins_total		= 0;
			$inp_number_salt_total			= 0;
			$inp_number_sodium_total		= 0;

		
			$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
			$result_groups = mysqli_query($link, $query_groups);
			while($row_groups = mysqli_fetch_row($result_groups)) {
				list($get_group_id, $get_group_title) = $row_groups;

				$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$get_group_id";
				$result_items = mysqli_query($link, $query_items);
				$row_cnt = mysqli_num_rows($result_items);
				while($row_items = mysqli_fetch_row($result_items)) {
					list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;


					$inp_number_energy_metric		= $inp_number_energy_metric+$get_item_energy_metric;
					$inp_number_fat_metric	 		= $inp_number_fat_metric+$get_item_fat_metric;
					$inp_number_saturated_fat_metric	= $inp_number_saturated_fat_metric+$get_item_saturated_fat_metric;
					$inp_number_monounsaturated_fat_metric	= $inp_number_monounsaturated_fat_metric+$get_item_monounsaturated_fat_metric;
					$inp_number_polyunsaturated_fat_metric	= $inp_number_polyunsaturated_fat_metric+$get_item_polyunsaturated_fat_metric;
					$inp_number_cholesterol_metric	 	= $inp_number_cholesterol_metric+$get_item_cholesterol_metric;
					$inp_number_carbohydrates_metric	= $inp_number_carbohydrates_metric+$get_item_carbohydrates_metric;
					$inp_number_carbohydrates_of_which_sugars_metric  = $inp_number_carbohydrates_of_which_sugars_metric+$get_item_carbohydrates_of_which_sugars_metric;
					$inp_number_dietary_fiber_metric	= $inp_number_dietary_fiber_metric+$get_item_dietary_fiber_metric;
					$inp_number_proteins_metric	 	= $inp_number_proteins_metric+$get_item_proteins_metric;
					$inp_number_salt_metric			= $inp_number_salt_metric+$get_item_salt_metric;
					$inp_number_sodium_metric		= $inp_number_sodium_metric+$get_item_sodium_metric;

					$inp_number_energy_total		= $inp_number_energy_total+$get_item_energy_calculated;
					$inp_number_fat_total			= $inp_number_fat_total+$get_item_fat_calculated;
					$inp_number_saturated_fat_total	 	= $inp_number_saturated_fat_total+$get_item_saturated_fat_calculated;
					$inp_number_monounsaturated_fat_total	= $inp_number_monounsaturated_fat_total+$get_item_monounsaturated_fat_calculated;
					$inp_number_polyunsaturated_fat_total	= $inp_number_polyunsaturated_fat_total+$get_item_polyunsaturated_fat_calculated;
					$inp_number_cholesterol_total		= $inp_number_cholesterol_total+$get_item_cholesterol_calculated;
					$inp_number_carbohydrates_total		= $inp_number_carbohydrates_total+$get_item_carbohydrates_calculated;
					$inp_number_carbohydrates_of_which_sugars_total = $inp_number_carbohydrates_of_which_sugars_total+$get_item_carbohydrates_of_which_sugars_calculated;
					$inp_number_dietary_fiber_total		= $inp_number_dietary_fiber_total+$get_item_dietary_fiber_calculated;
					$inp_number_proteins_total		= $inp_number_proteins_total+$get_item_proteins_calculated;
					$inp_number_salt_total			= $inp_number_salt_total+$get_item_salt_calculated;
					$inp_number_sodium_total		= $inp_number_sodium_total+$get_item_sodium_calculated;

					


				} // items
			} // groups
					
			
			// Numbers : Per hundred
			$inp_number_energy_metric_mysql			= quote_smart($link, $inp_number_energy_metric);
			$inp_number_fat_metric_mysql 			= quote_smart($link, $inp_number_fat_metric);
			$inp_number_saturated_fat_metric_mysql		= quote_smart($link, $inp_number_saturated_fat_metric);
			$inp_number_monounsaturated_fat_metric_mysql	= quote_smart($link, $inp_number_monounsaturated_fat_metric);
			$inp_number_polyunsaturated_fat_metric_mysql	= quote_smart($link, $inp_number_polyunsaturated_fat_metric);
			$inp_number_cholesterol_metric_mysql	 	= quote_smart($link, $inp_number_cholesterol_metric);
			$inp_number_carbohydrates_metric_mysql		= quote_smart($link, $inp_number_carbohydrates_metric);
			$inp_number_carbohydrates_of_which_sugars_metric_mysql  = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_metric);
			$inp_number_dietary_fiber_metric_mysql		= quote_smart($link, $inp_number_dietary_fiber_metric);
			$inp_number_proteins_metric_mysql	 	= quote_smart($link, $inp_number_proteins_metric);
			$inp_number_salt_metric_mysql			= quote_smart($link, $inp_number_salt_metric);
			$inp_number_sodium_metric_mysql			= quote_smart($link, $inp_number_sodium_metric);

	
			// Numbers : Total 
			$inp_number_energy_total_mysql			= quote_smart($link, $inp_number_energy_total);
			$inp_number_fat_total_mysql			= quote_smart($link, $inp_number_fat_total);
			$inp_number_saturated_fat_total_mysql	 	= quote_smart($link, $inp_number_saturated_fat_total);
			$inp_number_monounsaturated_fat_total_mysql	= quote_smart($link, $inp_number_monounsaturated_fat_total);
			$inp_number_polyunsaturated_fat_total_mysql	= quote_smart($link, $inp_number_polyunsaturated_fat_total);
			$inp_number_cholesterol_total_mysql		= quote_smart($link, $inp_number_cholesterol_total);
			$inp_number_carbohydrates_total_mysql		= quote_smart($link, $inp_number_carbohydrates_total);
			$inp_number_carbohydrates_of_which_sugars_total_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_total);
			$inp_number_dietary_fiber_total_mysql		= quote_smart($link, $inp_number_dietary_fiber_total);
			$inp_number_proteins_total_mysql		= quote_smart($link, $inp_number_proteins_total);
			$inp_number_salt_total_mysql			= quote_smart($link, $inp_number_salt_total);
			$inp_number_sodium_total_mysql			= quote_smart($link, $inp_number_sodium_total);

			// Numbers : Per serving
			$get_number_servings = 1;

			$inp_number_energy_serving	 = round($inp_number_energy_total/$get_number_servings);
			$inp_number_energy_serving_mysql = quote_smart($link, $inp_number_energy_serving);

			$inp_number_fat_serving	 = round($inp_number_fat_total/$get_number_servings);
			$inp_number_fat_serving_mysql = quote_smart($link, $inp_number_fat_serving);

			$inp_number_saturated_fat_serving	 = round($inp_number_saturated_fat_total/$get_number_servings);
			$inp_number_saturated_fat_serving_mysql = quote_smart($link, $inp_number_saturated_fat_serving);

			$inp_number_monounsaturated_fat_serving	 = round($inp_number_monounsaturated_fat_total/$get_number_servings);
			$inp_number_monounsaturated_fat_serving_mysql = quote_smart($link, $inp_number_monounsaturated_fat_serving);

			$inp_number_polyunsaturated_fat_serving	 = round($inp_number_polyunsaturated_fat_total/$get_number_servings);
			$inp_number_polyunsaturated_fat_serving_mysql = quote_smart($link, $inp_number_polyunsaturated_fat_serving);

			$inp_number_cholesterol_serving	 = round($inp_number_cholesterol_total/$get_number_servings);
			$inp_number_cholesterol_serving_mysql = quote_smart($link, $inp_number_cholesterol_serving);

			$inp_number_carbohydrates_serving	 = round($inp_number_carbohydrates_total/$get_number_servings);
			$inp_number_carbohydrates_serving_mysql = quote_smart($link, $inp_number_carbohydrates_serving);

			$inp_number_carbohydrates_of_which_sugars_serving	 = round($inp_number_carbohydrates_of_which_sugars_total/$get_number_servings);
			$inp_number_carbohydrates_of_which_sugars_serving_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_serving);

			$inp_number_dietary_fiber_serving	 = round($inp_number_dietary_fiber_total/$get_number_servings);
			$inp_number_dietary_fiber_serving_mysql = quote_smart($link, $inp_number_dietary_fiber_serving);
	
			$inp_number_proteins_serving	 = round($inp_number_proteins_total/$get_number_servings);
			$inp_number_proteins_serving_mysql = quote_smart($link, $inp_number_proteins_serving);

			$inp_number_salt_serving	 = round($inp_number_salt_total/$get_number_servings);
			$inp_number_salt_serving_mysql = quote_smart($link, $inp_number_salt_serving);

			$inp_number_sodium_serving	 = round($inp_number_sodium_total/$get_number_servings);
			$inp_number_sodium_serving_mysql = quote_smart($link, $inp_number_sodium_serving);

			// Check if number exists
			$query = "SELECT number_id FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_number_id) = $row;
			if($get_number_id == ""){
				// Insert number
			mysqli_query($link, "INSERT INTO $t_recipes_numbers 
			(number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, 
			number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, 
			number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, 
			number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, 
			number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, 
			number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, 
			number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, 
			number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total) 
			VALUES 
			(NULL, $get_recipe_id, 1, 
$inp_number_energy_metric_mysql, 
$inp_number_fat_metric_mysql, 
$inp_number_saturated_fat_metric_mysql, 
$inp_number_monounsaturated_fat_metric_mysql, 
$inp_number_polyunsaturated_fat_metric_mysql, 
$inp_number_cholesterol_metric_mysql, 
$inp_number_carbohydrates_metric_mysql, $inp_number_carbohydrates_of_which_sugars_metric_mysql, $inp_number_dietary_fiber_metric_mysql, 
								$inp_number_proteins_metric_mysql, 
								$inp_number_salt_metric_mysql, 
								$inp_number_sodium_metric_mysql, 

								$inp_number_energy_serving_mysql, 
								$inp_number_fat_serving_mysql, 
								$inp_number_saturated_fat_serving_mysql, 
								$inp_number_monounsaturated_fat_serving_mysql, 
								$inp_number_polyunsaturated_fat_serving_mysql, 
								$inp_number_cholesterol_serving_mysql, 
							$inp_number_carbohydrates_serving_mysql, 
								$inp_number_carbohydrates_of_which_sugars_serving_mysql, 
								$inp_number_dietary_fiber_serving_mysql, 
								$inp_number_proteins_serving_mysql, 
								$inp_number_salt_serving_mysql, 

								$inp_number_sodium_serving_mysql, 
								$inp_number_energy_total_mysql, 
								$inp_number_fat_total_mysql, 
								$inp_number_saturated_fat_total_mysql, 
								$inp_number_monounsaturated_fat_total_mysql, 
								$inp_number_polyunsaturated_fat_total_mysql, 
								$inp_number_cholesterol_total_mysql, 
								$inp_number_carbohydrates_total_mysql, 
								$inp_number_carbohydrates_of_which_sugars_total_mysql, 
								$inp_number_dietary_fiber_total_mysql, 
								$inp_number_proteins_total_mysql, 
								$inp_number_salt_total_mysql, 
								$inp_number_sodium_total_mysql

)")
			or die(mysqli_error($link));
				
			}
			else{
				$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET 

								number_energy_metric=$inp_number_energy_metric_mysql, 
								number_fat_metric=$inp_number_fat_metric_mysql, 
								number_saturated_fat_metric=$inp_number_saturated_fat_metric_mysql, 
								number_monounsaturated_fat_metric=$inp_number_monounsaturated_fat_metric_mysql, 
								number_polyunsaturated_fat_metric=$inp_number_polyunsaturated_fat_metric_mysql, 
								number_cholesterol_metric=$inp_number_cholesterol_metric_mysql, 
								number_carbohydrates_metric=$inp_number_carbohydrates_metric_mysql, 
								number_carbohydrates_of_which_sugars_metric=$inp_number_carbohydrates_of_which_sugars_metric_mysql, 
								number_dietary_fiber_metric=$inp_number_dietary_fiber_metric_mysql, 
								number_proteins_metric=$inp_number_proteins_metric_mysql, 
								number_salt_metric=$inp_number_salt_metric_mysql, 
								number_sodium_metric=$inp_number_sodium_metric_mysql, 

								number_energy_serving=$inp_number_energy_serving_mysql, 
								number_fat_serving=$inp_number_fat_serving_mysql, 
								number_saturated_fat_serving=$inp_number_saturated_fat_serving_mysql, 
								number_monounsaturated_fat_serving=$inp_number_monounsaturated_fat_serving_mysql, 
								number_polyunsaturated_fat_serving=$inp_number_polyunsaturated_fat_serving_mysql, 
								number_cholesterol_serving=$inp_number_cholesterol_serving_mysql, 
								number_carbohydrates_serving=$inp_number_carbohydrates_serving_mysql, 
								number_carbohydrates_of_which_sugars_serving=$inp_number_carbohydrates_of_which_sugars_serving_mysql, 
								number_dietary_fiber_serving=$inp_number_dietary_fiber_serving_mysql, 
								number_proteins_serving=$inp_number_proteins_serving_mysql, 
								number_salt_serving=$inp_number_salt_serving_mysql, 

								number_sodium_serving=$inp_number_sodium_serving_mysql, 
								number_energy_total=$inp_number_energy_total_mysql, 
								number_fat_total=$inp_number_fat_total_mysql, 
								number_saturated_fat_total=$inp_number_saturated_fat_total_mysql, 
								number_monounsaturated_fat_total=$inp_number_monounsaturated_fat_total_mysql, 
								number_polyunsaturated_fat_total=$inp_number_polyunsaturated_fat_total_mysql, 
								number_cholesterol_total=$inp_number_cholesterol_total_mysql, 
								number_carbohydrates_total=$inp_number_carbohydrates_total_mysql, 
								number_carbohydrates_of_which_sugars_total=$inp_number_carbohydrates_of_which_sugars_total_mysql, 
								number_dietary_fiber_total=$inp_number_dietary_fiber_total_mysql, 
								number_proteins_total=$inp_number_proteins_total_mysql, 
								number_salt_total=$inp_number_salt_total_mysql, 
								number_sodium_total=$inp_number_sodium_total_mysql

					 WHERE number_recipe_id=$recipe_id_mysql") or die(mysqli_error($link));
			} // update number
		} // loop trough recipes


	}
	echo"
	<!-- //$t_recipes_numbers -->

";
?>