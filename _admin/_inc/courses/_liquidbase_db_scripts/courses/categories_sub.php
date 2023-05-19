<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_courses.php
* Version 2
* Copyright (c) 2019-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"


<!-- courses categories sub -->
<p>Create table: $t_courses_categories_sub</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_categories_sub");

if (!$mysqli -> query("CREATE TABLE $t_courses_categories_sub(
	sub_category_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(sub_category_id), 
	 sub_category_title VARCHAR(200), 
	 sub_category_title_clean VARCHAR(200), 
	 sub_category_description TEXT, 
	 sub_category_main_category_id INT,
	 sub_category_main_category_title VARCHAR(200), 
	 sub_category_language VARCHAR(10), 
	 sub_category_created DATETIME,
	 sub_category_updated DATETIME)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}

echo"
<!-- //courses categories sub -->

";
?>