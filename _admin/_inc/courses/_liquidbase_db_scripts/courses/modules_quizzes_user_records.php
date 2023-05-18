<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_modules_quizzes_user_records.php
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

<!-- modules_quizzes_user_records -->
";

$query = "SELECT * FROM $t_courses_modules_quizzes_user_records LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_courses_modules_quizzes_user_records: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_courses_modules_quizzes_user_records(
	  record_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(try_id), 
	   record_course_id INT,
	   record_course_title VARCHAR(200),
	   record_quiz_id INT,
	   record_user_id INT,
	   record_started_datetime DATETIME,
	   record_started_time VARCHAR(200),
	   record_started_saying VARCHAR(200),
	   record_is_closed INT,
	   record_ended_datetime DATETIME,
	   record_ended_time VARCHAR(200),
	   record_ended_saying VARCHAR(200),
	   record_finished_saying VARCHAR(200),
	   record_time_used VARCHAR(200),
	   record_percentage INT,
	   record_passed INT)")
	   or die(mysqli_error());
}
echo"
<!-- //modules_quizzes_user_records -->


";
?>