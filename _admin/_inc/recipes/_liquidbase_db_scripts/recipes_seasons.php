<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_seasons.php
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



	<!-- $t_recipes_seasons -->
	";
	$query = "SELECT * FROM $t_recipes_seasons";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_seasons: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_seasons(
	  	 season_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(season_id), 
	  	   season_name VARCHAR(250),
	  	   season_image VARCHAR(250),
	  	   season_last_updated DATE)")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_recipes_seasons
		(season_id, season_name) 
		VALUES 
		(NULL, 'January'),
		(NULL, 'February'),
		(NULL, 'March'),
		(NULL, 'April'),
		(NULL, 'May'),
		(NULL, 'June'),
		(NULL, 'July'),
		(NULL, 'August'),
		(NULL, 'September'),
		(NULL, 'October'),
		(NULL, 'November'),
		(NULL, 'December')")
		or die(mysqli_error($link));
	}
	echo"
	<!-- //$t_recipes_seasons -->

";
?>