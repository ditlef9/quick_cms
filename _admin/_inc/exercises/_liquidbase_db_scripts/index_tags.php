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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_index_tags") or die(mysqli_error($link)); 


echo"
	<!-- exercise_tags -->
	";
	$query = "SELECT * FROM $t_exercise_index_tags";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_index_tags: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_exercise_index_tags(
	  	 tag_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(tag_id), 
	  	   tag_exercise_id INT,
	  	   tag_language VARCHAR(20),
	  	   tag_text VARCHAR(200),
	  	   tag_clean VARCHAR(200))")
		   or die(mysqli_error());
	}
	echo"
	<!-- //exercise_tags -->
";
?>