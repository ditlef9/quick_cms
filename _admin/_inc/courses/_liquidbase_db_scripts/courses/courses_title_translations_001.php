<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/courses_title_translations_001.php
* Version 2
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
<p>Create table: $t_courses_title_translations</p>

";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_title_translations");

if (!$mysqli -> query("CREATE TABLE $t_courses_title_translations(
	courses_title_translation_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(courses_title_translation_id), 
    courses_title_translation_title VARCHAR(128),
    courses_title_translation_language VARCHAR(10))")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"


";
?>