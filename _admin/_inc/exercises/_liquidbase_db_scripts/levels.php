<?php
/**
*
* File: _admin/_inc/exercises/_liquibase/index.php
* Version 1.0.0
* Date 12:57 24.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_levels") or die(mysqli_error($link)); 


echo"

	<!-- levels -->
	";
	$query = "SELECT * FROM $t_exercise_levels";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_levels: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_exercise_levels(
	  	 level_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(level_id), 
	  	   level_title VARCHAR(250))")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_exercise_levels
		(level_id, level_title) 
		VALUES 
		(NULL, 'Beginner'),
		(NULL, 'Intermediate'),
		(NULL, 'Expert')")
		or die(mysqli_error($link)); 
	}
	echo"
	<!-- //levels -->


";
?>