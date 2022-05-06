<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_weekly_subscriptions.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes_weekly_subscriptions");


echo"


	<!-- recipes_weekly_subscriptions -->
	";
	
	$query = "SELECT * FROM $t_recipes_weekly_subscriptions";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_weekly_subscriptions: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_weekly_subscriptions(
	  	 subscription_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(subscription_id), 
	  	   subscription_user_id INT,
	  	   subscription_user_email VARCHAR(200),
	  	   subscription_user_name VARCHAR(200),
	  	   subscription_language VARCHAR(5),
	  	   subscription_send_email INT,
	  	   subscription_post_blog INT,
	  	   subscription_key VARCHAR(50),
	  	   subscription_last_run_date DATE)")
		   or die(mysqli_error());

		
	}


	echo"
	<!-- //recipes_weekly_subscriptions -->


";
?>