<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/courses.php
* Version 18:17 13.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ------------------------------------------------------------------------ */
$t_courses_liquidbase 	 = $mysqlPrefixSav . "courses_liquidbase";


$t_courses_title_translations	 = $mysqlPrefixSav . "courses_title_translations";
$t_courses_index		 = $mysqlPrefixSav . "courses_index";
$t_courses_index_stats_monthly	 = $mysqlPrefixSav . "courses_index_stats_monthly";
$t_courses_users_enrolled 	 = $mysqlPrefixSav . "courses_users_enrolled";

$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
$t_courses_categories_sub 	 = $mysqlPrefixSav . "courses_categories_sub";
$t_courses_modules		 = $mysqlPrefixSav . "courses_modules";
$t_courses_modules_images	 = $mysqlPrefixSav . "courses_modules_images";
$t_courses_modules_read		 = $mysqlPrefixSav . "courses_modules_read";

$t_courses_lessons 	 	= $mysqlPrefixSav . "courses_lessons";
$t_courses_lessons_images	= $mysqlPrefixSav . "courses_lessons_images";
$t_courses_lessons_read 	= $mysqlPrefixSav . "courses_lessons_read";
$t_courses_lessons_comments	= $mysqlPrefixSav . "courses_lessons_comments";

$t_courses_modules_quizzes_index  	= $mysqlPrefixSav . "courses_modules_quizzes_index";
$t_courses_modules_quizzes_qa 		= $mysqlPrefixSav . "courses_modules_quizzes_qa";
$t_courses_modules_quizzes_user_records	= $mysqlPrefixSav . "courses_modules_quizzes_user_records";

$t_courses_exams_index  		= $mysqlPrefixSav . "courses_exams_index";
$t_courses_exams_qa			= $mysqlPrefixSav . "courses_exams_qa";
$t_courses_exams_user_tries		= $mysqlPrefixSav . "courses_exams_user_tries";
$t_courses_exams_user_tries_qa		= $mysqlPrefixSav . "courses_exams_user_tries_qa";




$tables_truncate_array = array();

$tables_backup_array = array(
			"$t_courses_liquidbase", 

			"$t_courses_title_translations", 
			"$t_courses_index", 
			"$t_courses_index_stats_monthly", 
			"$t_courses_users_enrolled", 

			"$t_courses_categories_main", 
			"$t_courses_categories_sub", 
			"$t_courses_modules", 
			"$t_courses_modules_images", 
			"$t_courses_modules_read", 

			"$t_courses_lessons", 
			"$t_courses_lessons_images", 
			"$t_courses_lessons_read", 
			"$t_courses_lessons_comments", 

			"$t_courses_modules_quizzes_index", 
			"$t_courses_modules_quizzes_qa", 
			"$t_courses_modules_quizzes_user_records", 

			"$t_courses_exams_index", 
			"$t_courses_exams_qa", 
			"$t_courses_exams_user_tries", 
			"$t_courses_exams_user_tries_qa"
);

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array();

?>