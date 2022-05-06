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


	<!-- blog_posts_tags -->
	";
	$query = "SELECT * FROM $t_blog_posts_tags";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_posts_tags: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_posts_tags(
	  	 blog_post_tag_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(blog_post_tag_id), 
	  	   blog_post_id INT,
	  	   blog_post_tag_language VARCHAR(50),
	  	   blog_post_tag_title VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //blog_posts_tags -->

";
?>