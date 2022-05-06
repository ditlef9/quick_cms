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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_index_muscles_images") or die(mysqli_error($link)); 


echo"

	<!-- muscles -->
	";
	$query = "SELECT * FROM $t_exercise_index_muscles_images";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_index_muscles_images: $row_cnt</p>
		";
	}
	else{
echo"<pre>CREATE TABLE $t_exercise_index_muscles_images(
	  	 exercise_muscle_image_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(exercise_muscle_image_id), 
	  	   exercise_muscle_image_exercise_id INT,
	  	   exercise_muscle_image_file VARCHAR(250),
	  	   exercise_muscle_image_main_muscle_ids VARCHAR(250),
	  	   exercise_muscle_image_assistant_muscle_ids VARCHAR(250)</pre>";
		mysqli_query($link, "CREATE TABLE $t_exercise_index_muscles_images(
	  	 exercise_muscle_image_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(exercise_muscle_image_id), 
	  	   exercise_muscle_image_exercise_id INT,
	  	   exercise_muscle_image_file VARCHAR(250),
	  	   exercise_muscle_image_main_muscle_ids VARCHAR(250),
	  	   exercise_muscle_image_assistant_muscle_ids VARCHAR(250))") or die(mysqli_error());

		// Will be added automatically
	}
	echo"
	<!-- //muscles -->


";
?>