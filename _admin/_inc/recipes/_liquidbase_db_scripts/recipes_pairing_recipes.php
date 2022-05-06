<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_pairing_recipes.php
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

	<!-- recipes_pairing_recipes -->
	";
	$query = "SELECT * FROM $t_recipes_pairing_recipes";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_pairing_recipes: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_pairing_recipes(
	  	 pairing_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(pairing_id), 
	  	 pairing_this_recipe_id INT,
	  	 pairing_this_category_id INT,
	  	 pairing_other_recipe_id INT,
	  	 pairing_other_category_id INT,
	  	 pairing_other_title VARCHAR(250),
	  	 pairing_other_image_path VARCHAR(250),
	  	 pairing_other_image_image VARCHAR(250),
	  	 pairing_other_image_thumb VARCHAR(250),
	  	 pairing_counter INT
	  	   )")
		   or die(mysqli_error());

	}
	echo"
	<!-- //recipes_pairing_recipes -->
";
?>