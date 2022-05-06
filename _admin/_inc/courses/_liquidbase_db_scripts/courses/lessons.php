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

<!-- courses_lessons -->
";

$query = "SELECT * FROM $t_courses_lessons LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_courses_lessons: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_courses_lessons(
	  lesson_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(lesson_id), 
	   lesson_number INT,
	   lesson_title VARCHAR(200),
	   lesson_title_clean VARCHAR(200),
	   lesson_title_length INT,
	   lesson_title_short VARCHAR(90),
	   lesson_description TEXT,
	   lesson_content TEXT,
	   lesson_course_id INT,
	   lesson_course_title VARCHAR(200),
	   lesson_module_id INT,
	   lesson_module_title VARCHAR(200),
	   lesson_read_times INT,
	   lesson_read_times_ipblock TEXT,
	   lesson_created_datetime DATETIME,
	   lesson_created_date_formatted VARCHAR(60),
	   lesson_last_read_datetime DATETIME,
	   lesson_last_read_date_formatted VARCHAR(60))")
	   or die(mysqli_error());
}
echo"
<!-- //courses_modules_contents -->

";
?>