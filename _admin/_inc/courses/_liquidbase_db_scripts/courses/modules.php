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

<!-- courses_modules -->
<p>Create table: $t_courses_modules</p>
";

$mysqli->query("DROP TABLE IF EXISTS $t_courses_modules");

if (!$mysqli -> query("CREATE TABLE $t_courses_modules(
	module_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(module_id), 
	 module_course_id INT,
	 module_course_title VARCHAR(200),
	 module_number INT,
	 module_title VARCHAR(200),
	 module_title_clean VARCHAR(200),
	 module_content TEXT,
	 module_read_times INT,
	 module_read_ipblock TEXT,
	 module_created DATETIME,
	 module_updated DATETIME,
	 module_last_read_datetime DATETIME,
	 module_last_read_date_formatted VARCHAR(60))")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"
<!-- //courses_modules -->

";
?>