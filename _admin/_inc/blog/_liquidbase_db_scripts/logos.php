<?php
/**
*
* File: _admin/_inc/blog/_liquibase/info.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */



echo"

	<!-- blog_logos -->
	";
	$query = "SELECT * FROM $t_blog_logos";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_logos: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_logos(
	  	 logo_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(logo_id), 
	  	   logo_blog_info_id INT,
	  	   logo_user_id INT,
	  	   logo_path VARCHAR(200),
	  	   logo_thumb VARCHAR(200),
	  	   logo_file VARCHAR(200),
	  	   logo_uploaded_datetime DATETIME,
	  	   logo_uploaded_ip VARCHAR(200),
	  	   logo_reported INT,
	  	   logo_reported_checked VARCHAR(200))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //blog_logos -->



";
?>