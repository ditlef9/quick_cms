<?php
/**
*
* File: _admin/_inc/blog/_liquibase/posts_comments_replies.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_blog_posts_comments_replies");

echo"
<!-- posts_comments_replies -->
	";

	
	$query = "SELECT * FROM $t_blog_posts_comments_replies";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_posts_comments_replies: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_posts_comments_replies(
	  	 reply_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(reply_id), 
	  	   reply_comment_id INT,
	  	   reply_blog_post_id INT,
	  	   reply_blog_info_id INT,
	  	   reply_text TEXT,
	  	   reply_by_user_id INT,
	  	   reply_by_user_name VARCHAR(50),
	  	   reply_by_user_image_path VARCHAR(250),
	  	   reply_by_user_image_file VARCHAR(50),
	  	   reply_by_user_image_thumb_60 VARCHAR(50),
	  	   reply_by_user_ip VARCHAR(200),
	  	   reply_created DATETIME,
	  	   reply_created_saying VARCHAR(50),
	  	   reply_created_timestamp VARCHAR(50),
	  	   reply_updated DATETIME,
	  	   reply_updated_saying VARCHAR(50),
	  	   reply_likes INT,
	  	   reply_dislikes INT,
	  	   reply_number_of_replies INT,
	  	   reply_read_blog_owner INT,
	  	   reply_reported INT,
	  	   reply_reported_by_user_id INT,
	  	   reply_reported_reason TEXT,
	  	   reply_reported_checked INT)")
		   or die(mysqli_error());

	}
	echo"
<!-- //posts_comments_replies -->

";
?>