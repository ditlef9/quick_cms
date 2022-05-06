<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/categories.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_index_prices") or die(mysqli_error($link)); 


echo"


	<!-- food_prices -->
	";
	$query = "SELECT * FROM $t_food_index_prices";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_index_prices: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_index_prices(
	  	 food_price_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(food_price_id), 
	  	   food_price_food_id INT, 
	  	   food_price_store_id INT,
	  	   food_price_store_name VARCHAR(200), 
	  	   food_price_price DOUBLE,
	  	   food_price_currency VARCHAR(200), 
	  	   food_price_offer INT,
	  	   food_price_offer_valid_from DATETIME,
	  	   food_price_offer_valid_to DATETIME,
	  	   food_price_user_id INT, 
	  	   food_price_user_ip VARCHAR(200),
	  	   food_price_added_datetime DATETIME,
	  	   food_price_added_datetime_print VARCHAR(200), 
	  	   food_price_updated DATETIME, 
	  	   food_price_updated_print VARCHAR(200), 
	  	   food_price_reported INT,
	  	   food_price_reported_checked INT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //food_prices -->

";
?>