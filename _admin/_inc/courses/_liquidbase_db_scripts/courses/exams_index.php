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


<!-- $t_courses_exams_index -->
";

$query = "SELECT * FROM $t_courses_exams_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_courses_exams_index: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_courses_exams_index (
	  exam_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(exam_id), 
	   exam_course_id INT,
	   exam_course_title VARCHAR(200),
	   exam_language VARCHAR(20),
	   exam_total_questions INT,
	   exam_total_points INT,
	   exam_points_needed_to_pass INT)")
	   or die(mysqli_error());
}
echo"
<!-- //courses_exams_index -->

";
?>