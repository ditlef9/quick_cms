<?php
/**
*
* File: _admin/_inc/food_diary/_liquidbase_db_scripts/food_diary/lifestyle_selected_per_day.php
* Version 1.0.0
* Date 13:57 03.06.2021
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_diary_lifestyle_selected_per_day") or die(mysqli_error($link)); 


echo"

	<!-- food_diary_lifestyle_selected_per_day -->
	";
	$query = "SELECT * FROM $t_food_diary_lifestyle_selected_per_day";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_diary_lifestyle_selected_per_day: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_diary_lifestyle_selected_per_day(
	  	 lifestyle_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(lifestyle_id), 
	  	   lifestyle_user_id INT,
	  	   lifestyle_count_active_mon INT,
	  	   lifestyle_count_active_tue INT,
	  	   lifestyle_count_active_wed INT,
	  	   lifestyle_count_active_thu INT,
	  	   lifestyle_count_active_fri INT,
	  	   lifestyle_count_active_sat INT,
	  	   lifestyle_count_active_sun INT,
	  	   lifestyle_count_sedentary_mon INT,
	  	   lifestyle_count_sedentary_tue INT,
	  	   lifestyle_count_sedentary_wed INT,
	  	   lifestyle_count_sedentary_thu INT,
	  	   lifestyle_count_sedentary_fri INT,
	  	   lifestyle_count_sedentary_sat INT,
	  	   lifestyle_count_sedentary_sun INT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //food_diary_lifestyle_selected_per_day -->

";
?>