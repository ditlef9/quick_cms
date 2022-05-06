<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_measurements.php
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

echo"



	<!-- $t_recipes_measurements -->
	";
	$query = "SELECT * FROM $t_recipes_measurements";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_measurements: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_measurements(
	  	measurement_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(measurement_id), 
	  	   measurement_name VARCHAR(250), 
	  	   measurement_g double, 
	  	   measurement_ml double)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //$t_recipes_measurements -->



";
?>