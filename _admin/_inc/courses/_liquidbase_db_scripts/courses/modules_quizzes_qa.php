<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_modules_quizzes_qa.php
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

<!-- modules_quizzes_qa -->
<p>Create table: $t_courses_modules_quizzes_qa</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_modules_quizzes_qa");

if (!$mysqli -> query("CREATE TABLE $t_courses_modules_quizzes_qa(
	qa_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(qa_id), 
	 qa_course_id INT,
	 qa_course_title VARCHAR(200),
	 qa_quiz_id INT,
	 qa_question_number INT,
	 qa_question VARCHAR(200),
	 qa_text TEXT,
	 qa_type VARCHAR(200),
	 qa_alt_a VARCHAR(200),
	 qa_alt_b VARCHAR(200),
	 qa_alt_c VARCHAR(200),
	 qa_alt_d VARCHAR(200),
	 qa_alt_e VARCHAR(200),
	 qa_alt_f VARCHAR(200),
	 qa_alt_g VARCHAR(200),
	 qa_alt_h VARCHAR(200),
	 qa_alt_i VARCHAR(200),
	 qa_alt_j VARCHAR(200),
	 qa_alt_k VARCHAR(200),
	 qa_alt_l VARCHAR(200),
	 qa_alt_m VARCHAR(200),
	 qa_alt_n VARCHAR(200),
	 qa_correct_alternatives VARCHAR(200),
	 qa_points INT,
	 qa_hint TEXT,
	 qa_explanation TEXT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"
<!-- //modules_quizzes_qa -->

";
?>