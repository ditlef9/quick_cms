<?php
/**
*
* File: _admin/_inc/exercises/_liquibase/index_translated.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_index_translated") or die(mysqli_error($link)); 


echo"

	<!-- exercise_index_translated -->
	";
	$query = "SELECT * FROM $t_exercise_index_translations_relations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_index_translations_relations: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_exercise_index_translations_relations(
	  	 relation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(relation_id), 
	  	   exercise_original_id INT,
	  	   exercise_target_id INT,
	  	   exercise_language VARCHAR(250),
	  	   exercise_translated INT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //exercise_index_translated-->

";
?>