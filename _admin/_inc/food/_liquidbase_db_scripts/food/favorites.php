<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/favorites.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_favorites") or die(mysqli_error($link)); 


echo"


	<!-- food_favorites -->
	";
	$query = "SELECT * FROM $t_food_favorites";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_favorites: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_favorites(
	  	food_favorite_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(food_favorite_id), 
	  	   food_favorite_food_id INT, 
	  	   food_favorite_user_id INT,
	  	   food_favorite_comment VARCHAR(250))")
		   or die(mysqli_error());



	}
	echo"
	<!-- //food_favorites -->
";
?>