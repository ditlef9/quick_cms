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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_contact_forms_messages_index") or die(mysqli_error($link)); 


echo"


	<!-- contact_forms_messages_index -->
	";
	$query = "SELECT * FROM $t_contact_forms_messages_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_contact_forms_messages_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_contact_forms_messages_index(
	  	 message_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(message_id), 
	  	   message_form_id INT,
	  	   message_password VARCHAR(250),
	  	   message_notes TEXT,
	  	   message_source VARCHAR(250),
	  	   message_created_datetime DATETIME,
	  	   message_updated_datetime DATETIME,
	  	   message_ip VARCHAR(250),
	  	   message_hostname VARCHAR(250),
	  	   message_agent VARCHAR(250))")
		   or die(mysqli_error());
	}
	echo"
	<!-- //contact_forms_messages_index -->

";
?>