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

<!-- courses_exams_user_tries_qa -->
	<p>Create table: $t_courses_exams_user_tries_qa</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_exams_user_tries_qa");

if (!$mysqli -> query("CREATE TABLE $t_courses_exams_user_tries_qa(
	try_qa_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(try_qa_id), 
	 try_qa_course_id INT,
	 try_qa_course_title VARCHAR(200),
	 try_qa_exam_id INT,
	 try_qa_try_id INT,
	 try_qa_user_id INT,
	 try_qa_qa_id INT,
	 try_qa_alt_a VARCHAR(200),
	 try_qa_alt_b VARCHAR(200),
	 try_qa_alt_c VARCHAR(200),
	 try_qa_alt_d VARCHAR(200),
	 try_qa_alt_e VARCHAR(200),
	 try_qa_alt_f VARCHAR(200),
	 try_qa_alt_g VARCHAR(200),
	 try_qa_alt_h VARCHAR(200),
	 try_qa_alt_i VARCHAR(200),
	 try_qa_alt_j VARCHAR(200),
	 try_qa_alt_k VARCHAR(200),
	 try_qa_alt_l VARCHAR(200),
	 try_qa_alt_m VARCHAR(200),
	 try_qa_alt_n VARCHAR(200),
	 try_qa_points_awarded DOUBLE,
	 try_qa_is_correct INT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}

echo"
<!-- //courses_exams_user_tries_qa -->

";
?>