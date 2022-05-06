<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_weekly_subscriptions_checked_ingredients.php
* Version 1.0.0
* Date 14:16 12.02.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes_weekly_subscriptions_checked_ingredients");


echo"


	<!-- recipes_weekly_subscriptions_customizations -->
	";
	
	$query = "SELECT * FROM $t_recipes_weekly_subscriptions_checked_ingredients";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_weekly_subscriptions_checked_ingredients: $row_cnt</p>
		";
	}
	else{


		mysqli_query($link, "CREATE TABLE $t_recipes_weekly_subscriptions_checked_ingredients(
	  	 checked_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(checked_id), 
	  	   checked_subscription_id INT,
	  	   checked_user_id INT,
	  	   checked_day_no INT,
	  	   checked_category_id INT,
	  	   checked_category_name VARCHAR(200),
	  	   checked_ingredient_id INT,
	  	   checked_ingredient_title VARCHAR(200))")
		   or die(mysqli_error());

		
	}


	echo"
	<!-- //recipes_weekly_subscriptions_customizations -->


";
?>