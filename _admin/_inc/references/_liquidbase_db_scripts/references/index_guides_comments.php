<?php
/**
*
* File: _admin/_inc/references/_liquibase/courses/001_references.php
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
mysqli_query($link, "DROP TABLE IF EXISTS $t_references_index_guides_comments")  or die(mysqli_error($link));

echo"



<!-- references_index_guides_comments -->
";

$query = "SELECT * FROM $t_references_index_guides_comments LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_references_index_guides_comments: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_references_index_guides_comments(
	  comment_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(comment_id), 
	   comment_reference_id INT,
	   comment_reference_title VARCHAR(200),
	   comment_group_id INT,
	   comment_group_title VARCHAR(200),
	   comment_guide_id INT,
	   comment_guide_title VARCHAR(200),
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
	   comment_spam_checked_comment TEXT)")
	   or die(mysqli_error());
}
echo"
<!-- //references_index_guides_comments  -->




";
?>