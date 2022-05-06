<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/countries_used.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_countries_used") or die(mysqli_error($link)); 


echo"




	<!-- food_countries_used -->
	";
	$query = "SELECT * FROM $t_food_countries_used";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_countries_used: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_countries_used(
	  	 food_country_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(food_country_id), 
	  	   food_country_name VARCHAR(250),
		   food_country_count_food INT
	  	   )")
		   or die(mysqli_error());
	}
	echo"
	<!-- //food_countries_used -->
";
?>