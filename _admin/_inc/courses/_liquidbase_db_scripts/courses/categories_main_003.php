<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_courses.php
* Version 3
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




<!-- courses categories -->
	<p>Create table: $t_courses_categories_main</p>
";

$mysqli->query("DROP TABLE IF EXISTS $t_courses_categories_main");

if (!$mysqli -> query("CREATE TABLE $t_courses_categories_main(
	main_category_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(main_category_id), 
	 main_category_title VARCHAR(200), 
	 main_category_title_clean VARCHAR(200), 
	 main_category_description TEXT, 
	 main_category_language VARCHAR(10), 
	 main_category_icon_path VARCHAR(150), 
	 main_category_icon_16x16 VARCHAR(150), 
	 main_category_icon_18x18 VARCHAR(150), 
	 main_category_icon_24x24 VARCHAR(150), 
	 main_category_icon_32x32 VARCHAR(150), 
	 main_category_icon_36x36 VARCHAR(150), 
	 main_category_icon_48x48 VARCHAR(150), 
	 main_category_icon_96x96 VARCHAR(150), 
	 main_category_icon_192x192 VARCHAR(150), 
	 main_category_icon_260x260 VARCHAR(150), 
	 main_category_header_logo VARCHAR(150), 
	 main_category_webdesign VARCHAR(150), 
	 main_category_created DATETIME,
	 main_category_updated DATETIME)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"
<!-- //courses categories -->


";
?>