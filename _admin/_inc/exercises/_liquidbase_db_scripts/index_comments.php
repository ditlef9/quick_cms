<?php
/**
*
* File: _admin/_inc/exercises/_liquibase/index.php
* Version 1.0.0
* Date 12:57 24.03.2021
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_exercise_index_comments") or die(mysqli_error($link)); 


echo"

	<!-- exercise_index_comments -->
	";
	$query = "SELECT * FROM $t_exercise_index_comments";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_exercise_index_comments: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_exercise_index_comments(
	  	 comment_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(comment_id), 
	  	   comment_exercise_id INT,
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
	<!-- //exercise_index_comments -->


";
?>