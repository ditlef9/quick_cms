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
";

$query = "SELECT * FROM $t_courses_title_translations LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_courses_title_translations: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_courses_title_translations(
	  courses_title_translation_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(courses_title_translation_id), 
	   courses_title_translation_title VARCHAR(500), 
	   courses_title_translation_language VARCHAR(10))")
	   or die(mysqli_error());


	// Insert all languages
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
		
		mysqli_query($link, "INSERT INTO $t_courses_title_translations
		(courses_title_translation_id, courses_title_translation_title, courses_title_translation_language) 
		VALUES 
		(NULL, 'Courses', '$get_language_active_iso_two')")
		or die(mysqli_error($link));
	}
}
echo"
<!-- //courses title translations -->



";
?>