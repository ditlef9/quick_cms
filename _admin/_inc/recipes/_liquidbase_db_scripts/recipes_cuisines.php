<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_cuisines.php
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

	<!-- $t_recipes_cuisines -->
	";
	$query = "SELECT * FROM $t_recipes_cuisines";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_cuisines: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_cuisines(
	  	 cuisine_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(cuisine_id), 
	  	   cuisine_name VARCHAR(250), 
	  	   cuisine_image VARCHAR(250), 
	  	   cuisine_last_updated DATE)")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_recipes_cuisines 
		(cuisine_id, cuisine_name) 
		VALUES 
		(NULL, 'American'),
		(NULL, 'Chinese'),
		(NULL, 'Continental'),
		(NULL, 'Cuban'),
		(NULL, 'French'),
		(NULL, 'Greek'),
		(NULL, 'Indian'),
		(NULL, 'Indonesian'),
		(NULL, 'Italian'),
		(NULL, 'Japanese'),
		(NULL, 'Korean'),
		(NULL, 'Lebanese'),
		(NULL, 'Malaysian'),
		(NULL, 'Mexican'),
		(NULL, 'Pakistani'),
		(NULL, 'Russian'),
		(NULL, 'Singapore'),
		(NULL, 'Spanish'),
		(NULL, 'Thai'),
		(NULL, 'Tibetan'),
		(NULL, 'Vietnamese'),
		(NULL, 'Italian'),
		(NULL, 'Norwegian')")
		or die(mysqli_error($link));
	}
	echo"
	<!-- //$t_recipes_cuisines -->


";
?>