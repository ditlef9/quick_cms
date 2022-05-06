<?php
/**
*
* File: _admin/_inc/meal_plans/_liquibase/user_adapted_view.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_meal_plans_user_adapted_view") or die(mysqli_error($link)); 


echo"



	<!-- meal_plans_user_adapted_view -->
	";
	$query = "SELECT * FROM $t_meal_plans_user_adapted_view";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_meal_plans_user_adapted_view: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_meal_plans_user_adapted_view (
	  	 view_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(view_id), 
	  	 view_user_id INT,
	  	 view_ip VARCHAR(200),
	  	 view_year INT,
	  	 view_system VARCHAR(20),
	  	 view_hundred_metric INT,
	  	 view_serving INT,
	  	 view_pcs_metric INT,
	  	 view_eight_us INT,
	  	 view_pcs_us INT
	  	   )")
		   or die(mysqli_error());

	}
	echo"
	<!-- //meal_plans_user_adapted_view -->
";
?>