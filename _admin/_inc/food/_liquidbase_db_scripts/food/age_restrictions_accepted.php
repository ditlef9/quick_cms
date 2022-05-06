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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_age_restrictions_accepted") or die(mysqli_error($link)); 


echo"



	<!-- food_age_restrictions_accepted -->
	";
	$query = "SELECT * FROM $t_food_age_restrictions_accepted";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_age_restrictions_accepted: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_age_restrictions_accepted(
	  	 accepted_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(accepted_id), 
	  	 accepted_ip VARCHAR(250),
	  	 accepted_year INT,
	  	 accepted_month INT,
	  	 accepted_country VARCHAR(250)
	  	   )")
		   or die(mysqli_error());

	}
	echo"
	<!-- //food_age_restrictions_accepted -->
";
?>