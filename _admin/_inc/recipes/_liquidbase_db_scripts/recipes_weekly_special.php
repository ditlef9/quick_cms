<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_weekly_special.php
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

	<!-- $t_recipes_weekly_special -->
	";
	$query = "SELECT * FROM $t_recipes_weekly_special";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_weekly_special: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_weekly_special(
	  	weekly_special_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(weekly_special_id), 
	  	   weekly_special_week INT, 
	  	   weekly_special_year INT, 
	  	   weekly_special_language VARCHAR(250), 
	  	   weekly_special_recipe_id INT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //$t_recipes_weekly_special -->


";
?>