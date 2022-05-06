<?php
/**
*
* File: _admin/_inc/blog/_liquibase/titles.php
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
	<!-- blog_titles -->
	";

	
	$query = "SELECT * FROM $t_blog_titles";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_titles: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_titles(
	  	 title_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(title_id), 
	  	   title_language VARCHAR(50),
	  	   title_value VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //blog_titles -->

";
?>