<?php
/**
*
* File: _admin/_inc/blog/_liquibase/post_comments.php
* Version 1.0.0
* Date 21:47 31.10.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_blog_posts_comments");

echo"
<!-- post_comments -->
	";

	
	$query = "SELECT * FROM $t_blog_posts_comments";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_posts_comments: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_posts_comments(
	  	 comment_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(comment_id), 
	  	   comment_blog_post_id INT,
	  	   comment_blog_info_id INT,
	  	   comment_text TEXT,
	  	   comment_by_user_id INT,
	  	   comment_by_user_name VARCHAR(50),
	  	   comment_by_user_image_path VARCHAR(250),
	  	   comment_by_user_image_file VARCHAR(50),
	  	   comment_by_user_image_thumb_60 VARCHAR(50),
	  	   comment_by_user_ip VARCHAR(200),
	  	   comment_created DATETIME,
	  	   comment_created_saying VARCHAR(50),
	  	   comment_created_timestamp VARCHAR(50),
	  	   comment_updated DATETIME,
	  	   comment_updated_saying VARCHAR(50),
	  	   comment_likes INT,
	  	   comment_dislikes INT,
	  	   comment_number_of_replies INT,
	  	   comment_read_blog_owner INT,
	  	   comment_reported INT,
	  	   comment_reported_by_user_id INT,
	  	   comment_reported_reason TEXT,
	  	   comment_reported_checked INT)")
		   or die(mysqli_error());

	}
	echo"
<!-- //blog_post_comments -->

";
?>