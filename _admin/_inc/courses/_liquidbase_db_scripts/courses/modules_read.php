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

<!-- courses_modules_read -->
<p>Create table: $t_courses_modules_read</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_modules_read");

if (!$mysqli -> query("CREATE TABLE $t_courses_modules_read(
	module_read_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(module_read_id), 

	 read_course_id INT,
	 read_course_title VARCHAR(200),

	 read_module_id INT,
	 read_module_title VARCHAR(200),

	 read_user_id INT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"
<!-- //courses_modules_read -->


";
?>