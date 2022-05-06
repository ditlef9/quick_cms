<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_tags.php
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

	<!-- tags -->
	";
	$query = "SELECT * FROM $t_recipes_tags";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_tags: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_tags(
	  	 tag_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(tag_id), 
	  	   tag_language VARCHAR(20),
	  	   tag_recipe_id INT,
		   tag_title VARCHAR(250),
		   tag_title_clean VARCHAR(250),
	  	   tag_user_id INT
	  	   )")
		   or die(mysqli_error());
	}
	echo"
	<!-- //tags -->
	
";
?>