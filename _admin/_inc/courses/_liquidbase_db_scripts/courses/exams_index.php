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


<!-- courses_exams_index -->
<p>Create table: $t_courses_exams_index</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_exams_index");

if (!$mysqli -> query("CREATE TABLE $t_courses_exams_index (
	exam_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(exam_id), 
	 exam_course_id INT,
	 exam_course_title VARCHAR(200),
	 exam_language VARCHAR(20),
	 exam_total_questions INT,
	 exam_total_points INT,
	 exam_points_needed_to_pass INT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}

echo"
<!-- //courses_exams_index -->

";
?>