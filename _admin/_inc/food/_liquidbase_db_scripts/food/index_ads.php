<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/index_ads.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_index_ads") or die(mysqli_error($link)); 


echo"



	<!-- food_index_ads -->
	";
	$query = "SELECT * FROM $t_food_index_ads";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_index_ads: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_index_ads(
	  	 ad_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(ad_id), 
	  	   ad_food_language VARCHAR(20),
	  	   ad_food_id INT,
		   ad_text TEXT,
		   ad_url VARCHAR(250),
	  	   ad_food_created_datetime DATETIME,
	  	   ad_food_created_by_user_id INT,
	  	   ad_food_updated_datetime DATETIME,
	  	   ad_food_updated_by_user_id INT,
	  	   ad_food_unique_clicks INT,
	  	   ad_food_unique_clicks_ip_block TEXT
	  	   )")
		   or die(mysqli_error());
	}
	echo"
	<!-- //food_ads -->
";
?>