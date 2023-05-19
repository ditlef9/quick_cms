<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_courses.php
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

echo"

<!-- courses_lessons_comments -->
	<p>Create table: $t_courses_lessons_comments</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_lessons_comments");

if (!$mysqli -> query("CREATE TABLE $t_courses_lessons_comments (
	comment_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(comment_id), 
	 comment_course_id INT,
	 comment_course_title VARCHAR(200),
	 comment_module_id INT,
	 comment_module_title VARCHAR(200),
	 comment_lesson_id INT,
	 comment_lesson_title VARCHAR(200),
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
	 comment_spam_checked_comment TEXT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"
<!-- //courses_lessons_comments  -->

";
?>