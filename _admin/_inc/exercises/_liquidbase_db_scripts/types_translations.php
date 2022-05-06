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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_types_translations") or die(mysqli_error($link)); 


echo"


	<!-- type_translations -->
	";
	$query = "SELECT * FROM $t_exercise_types_translations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_types_translations: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_exercise_types_translations(
	  	 type_translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(type_translation_id), 
	  	   type_id INT,
	  	   type_translation_language VARCHAR(20),
	  	   type_translation_value VARCHAR(250))")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_exercise_types_translations
		(type_translation_id, type_id, type_translation_language, type_translation_value) 
		VALUES 
		(NULL, '1', 'en', 'CrossFit'),
		(NULL, '2', 'en', 'Strength'),
		(NULL, '3', 'en', 'Endurance Strength'),
		(NULL, '4', 'en', 'Yoga'),
		(NULL, '5', 'en', 'Cardio'),
		(NULL, '6', 'en', 'Other')")
		or die(mysqli_error($link)); 

		mysqli_query($link, "INSERT INTO $t_exercise_types_translations
		(type_translation_id, type_id, type_translation_language, type_translation_value) 
		VALUES 
		(NULL, '1', 'no', 'CrossFit'),
		(NULL, '2', 'no', 'Syrke'),
		(NULL, '3', 'no', 'Utholdene styrke'),
		(NULL, '4', 'no', 'Yoga'),
		(NULL, '5', 'no', 'Cardio'),
		(NULL, '6', 'no', 'Annet')")
		or die(mysqli_error($link)); 
	}
	echo"
	<!-- //types_translations -->


";
?>