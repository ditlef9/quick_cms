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


<!-- courses -->
	<p>Create table: $t_courses_index</p>
";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_index");

if (!$mysqli -> query("CREATE TABLE $t_courses_index(
	course_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(course_id), 
	 course_title VARCHAR(200), 
	 course_title_clean VARCHAR(200), 
	 course_is_active INT, 
	 course_front_page_intro TEXT, 
	 course_description TEXT, 
	 course_contents TEXT, 
	 course_language VARCHAR(10), 
	 course_main_category_id INT, 
	 course_main_category_title VARCHAR(200), 
	 course_sub_category_id INT, 
	 course_sub_category_title VARCHAR(200), 
	 course_intro_video_embedded VARCHAR(200), 
	 course_image_file VARCHAR(200),  
	 course_image_thumb VARCHAR(200),  
	 course_icon_16 VARCHAR(200), 
	 course_icon_32 VARCHAR(200), 
	 course_icon_48 VARCHAR(200), 
	 course_icon_64 VARCHAR(200),  
	 course_icon_96 VARCHAR(200),  
	 course_icon_260 VARCHAR(200),  
	 course_modules_count INT, 
	 course_lessons_count INT, 
	 course_quizzes_count INT, 
	 course_users_enrolled_count INT, 
	 course_read_times INT,
	 course_read_times_ip_block TEXT,
	 course_created DATETIME,
	 course_updated DATETIME)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}



echo"
<!-- //courses -->

";
?>