<?php
/**
*
* File: _admin/_inc/chat/_liquibase/talk/001c_talk.php
* Version 1.0.0
* Date 11:46 24.03.2021
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_contact_forms_questions") or die(mysqli_error($link)); 


echo"


	<!-- $t_contact_forms_questions -->
	";
	$query = "SELECT * FROM $t_contact_forms_questions";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_contact_forms_questions: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_contact_forms_questions(
	  	 question_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(question_id), 
	  	   question_form_id INT,
	  	   question_title VARCHAR(250),
	  	   question_field_name VARCHAR(250),
	  	   question_weight INT,
	  	   question_type VARCHAR(250),
	  	   question_size INT,
	  	   question_rows INT,
	  	   question_cols INT,
	  	   question_required INT,
	  	   question_answer VARCHAR(250))")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_contact_forms_questions (`question_id`, `question_form_id`, `question_title`, `question_field_name`, `question_weight`, `question_type`, `question_size`, `question_rows`, `question_cols`, `question_required`, `question_answer`) 
		VALUES
		(NULL, 1, 'Name', 'name', 0, 'text', 25, 8, 40, 1, ''),
		(NULL, 1, 'E-mail', 'email', 1, 'text', 25, 8, 40, 1, ''),
		(NULL, 1, 'Website', 'website', 2, 'text', 25, 8, 40, 0, ''),
		(NULL, 1, 'Message', 'message', 3, 'textarea', 25, 8, 40, 1, ''),
		(NULL, 2, 'Navn', 'name', 0, 'text', 25, 8, 40, 1, ''),
		(NULL, 2, 'E-post', 'email', 1, 'text', 25, 8, 40, 1, ''),
		(NULL, 2, 'Webside', 'webside', 2, 'text', 25, 8, 40, 0, ''),
		(NULL, 2, 'Beskjed', 'message', 3, 'textarea', 25, 8, 40, 1, ''),
		(NULL, 3, 'E-mail', 'email', 1, 'text', 25, 8, 40, 1, ''),
		(NULL, 3, 'Message', 'message', 3, 'textarea', 25, 8, 40, 1, ''),
		(NULL, 4, 'E-post', 'email', 1, 'text', 25, 8, 40, 1, ''),
		(NULL, 4, 'Beskjed', 'message', 3, 'textarea', 25, 8, 40, 1, '')")
		   or die(mysqli_error());

	}
	echo"
	<!-- //contact_forms_questions -->



";
?>