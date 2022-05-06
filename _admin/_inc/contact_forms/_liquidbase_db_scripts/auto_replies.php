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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_contact_forms_auto_replies") or die(mysqli_error($link)); 


echo"

	<!-- contact_forms_auto_replies -->
	";
	$query = "SELECT * FROM $t_contact_forms_auto_replies";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_contact_forms_auto_replies: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_contact_forms_auto_replies(
	  	 auto_reply_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(auto_reply_id), 
	  	   auto_reply_form_id INT,
	  	   auto_reply_from_email VARCHAR(200),
	  	   auto_reply_from_name VARCHAR(200),
	  	   auto_reply_subject VARCHAR(200),
	  	   auto_reply_text TEXT,
	  	   auto_reply_delay VARCHAR(200),
	  	   auto_reply_attachment_a VARCHAR(200),
	  	   auto_reply_attachment_b VARCHAR(200),
	  	   auto_reply_attachment_c VARCHAR(200),
	  	   auto_reply_active INT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //contact_forms_auto_replies -->


";
?>