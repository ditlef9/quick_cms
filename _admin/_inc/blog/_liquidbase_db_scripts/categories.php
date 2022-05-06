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
	<!-- blog_categories -->
	";

	
	$query = "SELECT * FROM $t_blog_categories";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_categories: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_categories(
	  	 blog_category_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(blog_category_id), 
	  	   blog_category_user_id INT,
	  	   blog_category_language VARCHAR(50),
	  	   blog_category_title VARCHAR(250), 
	  	   blog_category_posts INT)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //blog_categories -->

";
?>