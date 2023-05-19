<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_courses.php
* Version 2
* Copyright (c) 2019-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"

<!-- courses_modules_exams_user_tries -->
	<p>Create table: $t_courses_exams_user_tries</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_exams_user_tries");

if (!$mysqli -> query("CREATE TABLE $t_courses_exams_user_tries(
	try_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(try_id), 
	 try_course_id INT,
	 try_course_title VARCHAR(200),
	 try_exam_id INT,
	 try_user_id INT,
	 try_started_datetime DATETIME,
	 try_started_time VARCHAR(200),
	 try_started_saying VARCHAR(200),
	 try_is_closed INT,
	 try_ended_datetime DATETIME,
	 try_ended_time VARCHAR(200),
	 try_ended_saying VARCHAR(200),
	 try_finished_saying VARCHAR(200),
	 try_time_used VARCHAR(200),
	 try_percentage INT,
	 try_passed INT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}

echo"
<!-- //courses_exams_user_tries -->


";
?>