<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_rating.php
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
mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes_rating") or die(mysqli_error());

echo"

	<!-- $t_recipes_rating -->
	";
	$query = "SELECT * FROM $t_recipes_rating";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_rating: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_rating(
	  	 rating_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(rating_id), 
	  	   rating_recipe_id INT,
	  	   rating_recipe_lang VARCHAR(20),
	  	   rating_1 INT,
	  	   rating_2 INT,
	  	   rating_3 INT,
	  	   rating_4 INT,
	  	   rating_5 INT,
	  	   rating_total_votes INT,
	  	   rating_average INT,
	  	   rating_votes_plus_average INT,
	  	   rating_ip_block TEXT)")
		   or die(mysqli_error());


	}
	echo"
	<!-- //$t_recipes_rating -->


";
?>