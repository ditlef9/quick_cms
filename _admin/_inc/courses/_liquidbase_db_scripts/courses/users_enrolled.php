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

<!-- courses_users_enrolled -->
<p>Create table: $t_courses_users_enrolled</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_users_enrolled");

if (!$mysqli -> query("CREATE TABLE $t_courses_users_enrolled(
	enrolled_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(enrolled_id), 
	 enrolled_course_id INT, 
	 enrolled_course_title VARCHAR(200), 
	 enrolled_course_title_clean VARCHAR(200), 
	 enrolled_user_id INT, 
	 enrolled_started_datetime DATETIME,
	 enrolled_started_saying VARCHAR(200), 
	 enrolled_percentage_done INT,
	 enrolled_has_completed_exam INT,
	 enrolled_exam_total_questions INT,
	 enrolled_exam_correct_answers INT,
	 enrolled_exam_correct_percentage INT,
	 enrolled_completed_datetime DATETIME,
	 enrolled_completed_saying VARCHAR(200))")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"
<!-- //courses_users_enrolled -->


";
?>