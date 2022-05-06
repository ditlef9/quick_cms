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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_levels_translations") or die(mysqli_error($link)); 


echo"



	<!-- level_translations -->
	";
	$query = "SELECT * FROM $t_exercise_levels_translations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_levels_translations: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_exercise_levels_translations(
	  	 level_translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(level_translation_id), 
	  	   level_id INT,
	  	   level_translation_language VARCHAR(20),
	  	   level_translation_value VARCHAR(250))")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_exercise_levels_translations
		(level_translation_id, level_id, level_translation_language, level_translation_value) 
		VALUES 
		(NULL, '1', 'en', 'Beginner'),
		(NULL, '2', 'en', 'Intermediate'),
		(NULL, '3', 'en', 'Expert')")
		or die(mysqli_error($link)); 

		mysqli_query($link, "INSERT INTO $t_exercise_levels_translations
		(level_translation_id, level_id, level_translation_language, level_translation_value) 
		VALUES 
		(NULL, '1', 'no', 'Nybegynner'),
		(NULL, '2', 'no', 'Medium'),
		(NULL, '3', 'no', 'Ekspert')")
		or die(mysqli_error($link)); 
	}
	echo"
	<!-- //levels_translations -->



";
?>