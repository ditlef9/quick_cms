<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_comments.php
* Version 1.0.0
* Date 17:21 31.12.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"


	<!-- Comments -->
	";
	$query = "SELECT * FROM $t_recipes_comments";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_links: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_comments(
	  	 comment_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(comment_id), 
	  	   comment_recipe_id INT,
	  	   comment_language VARCHAR(20),
	  	   comment_approved INT,
		   comment_datetime DATETIME,
		   comment_time VARCHAR(200),
		   comment_date_print VARCHAR(200),
		   comment_user_id INT,
		   comment_user_alias VARCHAR(250),
		   comment_user_image_path VARCHAR(250),
		   comment_user_image_file VARCHAR(250),
		   comment_user_ip VARCHAR(250),
		   comment_user_hostname VARCHAR(250),
		   comment_user_agent VARCHAR(250),
		   comment_title VARCHAR(250),
		   comment_text TEXT, 
		   comment_rating INT, 
	  	   comment_helpful_clicks INT,
	  	   comment_useless_clicks INT,
	  	   comment_marked_as_spam INT,
		   comment_spam_checked INT,
		   comment_spam_checked_comment TEXT
	  	   )")
		   or die(mysqli_error());
	}
	echo"
	<!-- //Comments -->
";
?>