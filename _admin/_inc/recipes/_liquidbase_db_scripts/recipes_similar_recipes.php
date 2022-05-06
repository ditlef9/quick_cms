<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_similar_recipes.php
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

echo"


	<!-- $t_recipes_similar_recipes  -->
	";
	$query = "SELECT * FROM $t_recipes_similar_recipes";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_similar_recipes: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_similar_recipes(
	  	 similar_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(similar_id), 
	  		similar_this_recipe_id INT,
 similar_other_recipe_id INT, 
similar_other_title VARCHAR(250),
 similar_other_image_path VARCHAR(250),
 similar_other_image_image VARCHAR(250),
 similar_other_image_thumb VARCHAR(250),
 similar_other_recipe_age_restriction INT,
 similar_counter INT
	  	   )")
		   or die(mysqli_error());

	}
	echo"
	<!-- //$t_recipes_similar_recipes -->
	";
?>