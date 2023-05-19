<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_modules_quizzes_index.php
* Version 1.0.0
* Date 21:19 28.08.2023
* Copyright (c) 2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"


<!-- modules_quizzes_index -->
<p>Create table: $t_courses_modules_quizzes_index</p>
";



$mysqli->query("DROP TABLE IF EXISTS $t_courses_modules_quizzes_index");

if (!$mysqli -> query("CREATE TABLE $t_courses_modules_quizzes_index (
	quiz_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(quiz_id), 
	 quiz_course_id INT,
	 quiz_course_title VARCHAR(200),
	 quiz_language VARCHAR(20),
	 quiz_total_questions INT,
	 quiz_total_points INT,
	 quiz_points_needed_to_pass INT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}



echo"
<!-- //modules_quizzes_index -->

";
?>