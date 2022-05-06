<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/downloads_comments.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_meal_plans") or die(mysqli_error($link)); 


echo"

	<!-- meal_plans -->
	";
	$query = "SELECT * FROM $t_meal_plans";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_meal_plans: $row_cnt</p>
		";
	}
	else{


		mysqli_query($link, "CREATE TABLE $t_meal_plans(
	  	 meal_plan_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(meal_plan_id), 
	  	   meal_plan_user_id INT,
	  	   meal_plan_language VARCHAR(50),
	  	   meal_plan_title VARCHAR(250),
	  	   meal_plan_title_clean VARCHAR(250),
	  	   meal_plan_number_of_days INT,
	  	   meal_plan_introduction TEXT,
	  	   meal_plan_text TEXT,

	  	   meal_plan_total_energy_without_training INT,
	  	   meal_plan_total_fat_without_training INT,
	  	   meal_plan_total_carb_without_training INT,
	  	   meal_plan_total_protein_without_training INT,

	  	   meal_plan_total_energy_with_training INT,
	  	   meal_plan_total_fat_with_training INT,
	  	   meal_plan_total_carb_with_training INT,
	  	   meal_plan_total_protein_with_training INT,

	  	   meal_plan_average_kcal_without_training INT,
	  	   meal_plan_average_fat_without_training INT,
	  	   meal_plan_average_carb_without_training INT,
	  	   meal_plan_average_protein_without_training INT,

	  	   meal_plan_average_kcal_with_training INT,
	  	   meal_plan_average_fat_with_training INT,
	  	   meal_plan_average_carb_with_training INT,
	  	   meal_plan_average_protein_with_training INT,

	  	   meal_plan_created DATETIME,
	  	   meal_plan_updated DATETIME,
	  	   meal_plan_user_ip VARCHAR(250),
	  	   meal_plan_image_path VARCHAR(250),
	  	   meal_plan_image_thumb_74x50 VARCHAR(250),
	  	   meal_plan_image_thumb_400x269 VARCHAR(250),
	  	   meal_plan_image_file VARCHAR(250),
	  	   meal_plan_views INT,
	  	   meal_plan_views_ip_block TEXT,
	  	   meal_plan_likes INT,
	  	   meal_plan_dislikes INT,
	  	   meal_plan_rating INT,
	  	   meal_plan_rating_ip_block TEXT,
	  	   meal_plan_comments INT)")
		   or die(mysqli_error());
		
	}
	echo"
	<!-- //meal_plans -->
";
?>