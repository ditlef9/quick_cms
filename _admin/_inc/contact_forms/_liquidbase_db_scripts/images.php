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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_contact_forms_images") or die(mysqli_error($link)); 


echo"


	<!-- $t_contact_forms_images -->
	";
	$query = "SELECT * FROM $t_contact_forms_images";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_contact_forms_images: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_contact_forms_images(
	  	 image_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(image_id), 
	  	   image_contact_form_id INT,
	  	   image_path VARCHAR(250),
	  	   image_file VARCHAR(250))")
		   or die(mysqli_error());


	}
	echo"
	<!-- //contact_forms_images -->



";
?>