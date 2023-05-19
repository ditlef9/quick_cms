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


<!-- courses title translations -->
<p>Create table: $t_courses_title_translations</p>
";



$mysqli->query("DROP TABLE IF EXISTS $t_courses_title_translations");

if (!$mysqli -> query("CREATE TABLE $t_courses_title_translations(
	courses_title_translation_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(courses_title_translation_id), 
	 courses_title_translation_title VARCHAR(500), 
	 courses_title_translation_language VARCHAR(10))")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


// Insert all languages
$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
$result = $mysqli->query($query);
while($row = $result->fetch_row()) {
	list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
	
	$query = "INSERT INTO $t_courses_title_translations
	(courses_title_translation_id, courses_title_translation_title, courses_title_translation_language) 
	VALUES 
	(NULL, 'Courses', '$get_language_active_iso_two')";
	$mysqli->query($query);


}
echo"
<!-- //courses title translations -->



";
?>