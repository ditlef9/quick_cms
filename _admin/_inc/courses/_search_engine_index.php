<?php
/**
*
* File: _admin/_inc/courses/_search_engine_index.php
* Version 21:08 16.01.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_courses_liquidbase 	 = $mysqlPrefixSav . "courses_liquidbase";


$t_courses_title_translations	 = $mysqlPrefixSav . "courses_title_translations";
$t_courses_index		 = $mysqlPrefixSav . "courses_index";
$t_courses_index_stats_monthly	 = $mysqlPrefixSav . "courses_index_stats_monthly";
$t_courses_users_enrolled 	 = $mysqlPrefixSav . "courses_users_enrolled";

$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
$t_courses_categories_sub 	 = $mysqlPrefixSav . "courses_categories_sub";
$t_courses_modules		 = $mysqlPrefixSav . "courses_modules";
$t_courses_modules_read		 = $mysqlPrefixSav . "courses_modules_read";

$t_courses_lessons 	 	= $mysqlPrefixSav . "courses_lessons";
$t_courses_lessons_read 	= $mysqlPrefixSav . "courses_lessons_read";
$t_courses_lessons_comments	= $mysqlPrefixSav . "courses_lessons_comments";

$t_courses_modules_quizzes_index  	= $mysqlPrefixSav . "courses_modules_quizzes_index";
$t_courses_modules_quizzes_qa 		= $mysqlPrefixSav . "courses_modules_quizzes_qa";
$t_courses_modules_quizzes_user_records	= $mysqlPrefixSav . "courses_modules_quizzes_user_records";

$t_courses_exams_index  		= $mysqlPrefixSav . "courses_exams_index";
$t_courses_exams_qa			= $mysqlPrefixSav . "courses_exams_qa";
$t_courses_exams_user_tries		= $mysqlPrefixSav . "courses_exams_user_tries";
$t_courses_exams_user_tries_qa		= $mysqlPrefixSav . "courses_exams_user_tries_qa";


/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_courses_index";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	


	/* courses index */
	$query_w = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_course_id, $get_course_title, $get_course_title_clean, $get_course_is_active, $get_course_front_page_intro, $get_course_description, $get_course_contents, $get_course_language, $get_course_main_category_id, $get_course_main_category_title, $get_course_sub_category_id, $get_course_sub_category_title, $get_course_intro_video_embedded, $get_course_image_file, $get_course_image_thumb, $get_course_icon_16, $get_course_icon_32, $get_course_icon_48, $get_course_icon_64, $get_course_icon_96, $get_course_icon_260, $get_course_modules_count, $get_course_lessons_count, $get_course_quizzes_count, $get_course_users_enrolled_count, $get_course_read_times, $get_course_read_times_ip_block, $get_course_created, $get_course_updated) = $row_w;

	

		$inp_index_title = "$get_course_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "$get_course_title_clean";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_course_front_page_intro, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "courses");

		$inp_index_module_part_name_mysql = quote_smart($link, "course");

		$inp_index_reference_name_mysql = quote_smart($link, "course_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_course_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 1);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_course_language);

		// Check if course exists
		$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_index_id) = $row_exists;
		if($get_index_id == ""){
			// Insert
			echo"<span>Insert $inp_index_title<br /></span>\n";
			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
			index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
			index_unique_hits) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
			$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
			'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
			0)")
			or die(mysqli_error($link));
		}

		// Modules
		$query_m = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_course_id=$get_course_id";
		$result_m = mysqli_query($link, $query_m);
		while($row_m = mysqli_fetch_row($result_m)) {
			list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean, $get_module_read_times, $get_module_read_ipblock, $get_module_created, $get_module_updated, $get_module_last_read_datetime, $get_module_last_read_date_formatted) = $row_m;

	

			$inp_index_title = "$get_module_title | $get_course_title";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "$get_course_title_clean/$get_module_title_clean/index.php?course_id=$get_course_id&module_id=$get_module_id";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);

			$inp_index_short_description = "";
			$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

			// tags
			$inp_index_keywords = "";
			$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

			$inp_index_module_name_mysql = quote_smart($link, "courses");

			$inp_index_module_part_name_mysql = quote_smart($link, "module");

			$inp_index_reference_name_mysql = quote_smart($link, "module_id");

			$inp_index_reference_id_mysql = quote_smart($link, "$get_module_id");

			$inp_index_has_access_control_mysql = quote_smart($link, 1);

			$inp_index_is_ad_mysql = quote_smart($link, 0);
	
			$inp_index_language_mysql = quote_smart($link, $get_course_language);

			// Check if module exists
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id == ""){
				// Insert
				echo"<span>Insert $inp_index_title<br /></span>\n";
				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
				$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
				'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
				0)")
				or die(mysqli_error($link));
			}



			// Lessons
			$query_l = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_title_length, lesson_title_short, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_module_id=$get_module_id";
			$result_l = mysqli_query($link, $query_l);
			while($row_l = mysqli_fetch_row($result_l)) {
				list($get_lesson_id, $get_lesson_number, $get_lesson_title, $get_lesson_title_clean, $get_lesson_title_length, $get_lesson_title_short, $get_lesson_description, $get_lesson_content, $get_lesson_course_id, $get_lesson_course_title, $get_lesson_module_id, $get_lesson_module_title, $get_lesson_read_times, $get_lesson_read_times_ipblock, $get_lesson_created_datetime, $get_lesson_created_date_formatted, $get_lesson_last_read_datetime, $get_lesson_last_read_date_formatted) = $row_l;

	

				$inp_index_title = "$get_lesson_title | $get_module_title | $get_course_title";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_course_title_clean/$get_module_title_clean/$get_lesson_title_clean.php?course_id=$get_course_id&module_id=$get_module_id&lesson_id=$get_lesson_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				$inp_index_short_description = "$get_lesson_description";
				$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

				// tags
				$inp_index_keywords = "";
				$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

				$inp_index_module_name_mysql = quote_smart($link, "courses");

				$inp_index_module_part_name_mysql = quote_smart($link, "lesson");

				$inp_index_reference_name_mysql = quote_smart($link, "lesson_id");

				$inp_index_reference_id_mysql = quote_smart($link, "$get_lesson_id");

				$inp_index_has_access_control_mysql = quote_smart($link, 1);

				$inp_index_is_ad_mysql = quote_smart($link, 0);
	
				$inp_index_language_mysql = quote_smart($link, $get_course_language);

				// Check if course exists
				$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
				$result_exists = mysqli_query($link, $query_exists);
				$row_exists = mysqli_fetch_row($result_exists);
				list($get_index_id) = $row_exists;
					if($get_index_id == ""){
					// Insert
					echo"<span>Insert $inp_index_title<br /></span>\n";
					mysqli_query($link, "INSERT INTO $t_search_engine_index 
					(index_id, index_title, index_url, index_short_description, index_keywords, 
					index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
					index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
					index_unique_hits) 
					VALUES 
					(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
					$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
					'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
					0)")
					or die(mysqli_error($link));
				}
			} // lesson
		} // modules

		
	} // all courses



} // table exists
?>