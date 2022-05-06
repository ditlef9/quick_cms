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

<!-- courses_lessons_read -->
";

$query = "SELECT * FROM $t_courses_lessons_read LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_courses_lessons_read: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_courses_lessons_read (
	  lesson_read_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(lesson_read_id), 
	   read_course_id INT,
	   read_course_title VARCHAR(200),

	   read_module_id INT,
	   read_module_title VARCHAR(200),

	   read_lesson_id INT,
	   read_lesson_title VARCHAR(200),

	   read_user_id INT)")
	   or die(mysqli_error());
}
echo"
<!-- //courses_lessons_read -->

";
?>