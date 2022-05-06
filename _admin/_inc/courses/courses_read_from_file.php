<?php
/**
*
* File: _admin/_inc/comments/courses_read_from_file.php
* Version 
* Date 15:13 15.09.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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


/*- Tables search --------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['course_id'])){
	$course_id = $_GET['course_id'];
	$course_id = strip_tags(stripslashes($course_id));
}
else{
	$course_id = "";
}
$course_id_mysql = quote_smart($link, $course_id);

$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=$course_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

if($get_current_course_id == ""){
	echo"<p>Server error 404.</p>";
}
else{
	// Find category
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=$get_current_course_main_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_courses_categories_sub WHERE sub_category_id=$get_current_course_sub_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;


	// Title
	$l_mysql = quote_smart($link, $get_current_course_language);
	$query = "SELECT courses_title_translation_id, courses_title_translation_title FROM $t_courses_title_translations WHERE courses_title_translation_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_courses_title_translation_id, $get_current_courses_title_translation_title) = $row;
	if($get_current_courses_title_translation_id == ""){
		mysqli_query($link, "INSERT INTO $t_courses_title_translations
		(courses_title_translation_id, courses_title_translation_title, courses_title_translation_language) 
		VALUES 
		(NULL, 'Courses', $l_mysql)")
		or die(mysqli_error($link));
		$get_current_courses_title_translation_title = "Courses";
	}


	if($action == ""){
		if($process == "1"){
			// Datetime
			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j M Y H:i");
			$fm = "";
			$fm_detailed = "";

			// _course.php
			if(file_exists("../$get_current_course_title_clean/_course.php")){
				include("../$get_current_course_title_clean/_course.php");
				$fm_detailed = $fm_detailed . "|" . "Found _course.php";

				$inp_course_title_mysql 		= quote_smart($link, $course_title_sav);
				$inp_course_title_clean_mysql 		= quote_smart($link, $course_title_clean_sav);
				
				if($course_is_active_sav == ""){ $course_is_active_sav = "1"; } 
				$inp_course_is_active_mysql 		= quote_smart($link, $course_is_active_sav);
				$inp_course_front_page_mysql 		= quote_smart($link, $course_front_page_intro_sav);
				$inp_course_description_mysql 		= quote_smart($link, $course_description_sav);
				$inp_course_contents_mysql 		= quote_smart($link, $course_contents_sav);
				$inp_course_language_mysql 		= quote_smart($link, $course_language_sav);
				$inp_course_main_category_title_mysql 	= quote_smart($link, $course_main_category_title_sav);
				$inp_course_sub_category_title_mysql 	= quote_smart($link, $course_sub_category_title_sav);
				$inp_course_intro_video_embedded_mysql 	= quote_smart($link, $course_intro_video_embedded_sav);
				$inp_course_image_file_mysql 		= quote_smart($link, $course_image_file_sav);
				$inp_course_image_thumb_mysql 		= quote_smart($link, $course_image_thumb_sav);
				$inp_course_icon_a_mysql 		= quote_smart($link, $course_icon_a_sav);
				$inp_course_icon_b_mysql 		= quote_smart($link, $course_icon_b_sav);
				$inp_course_icon_c_mysql 		= quote_smart($link, $course_icon_c_sav);
				$inp_course_icon_d_mysql 		= quote_smart($link, $course_icon_d_sav);
				$inp_course_icon_e_mysql 		= quote_smart($link, $course_icon_e_sav);
				$inp_course_icon_f_mysql 		= quote_smart($link, $course_icon_f_sav);



				// Main category
				$query = "SELECT main_category_id, main_category_title, main_category_title_clean FROM $t_courses_categories_main WHERE main_category_title=$inp_course_main_category_title_mysql AND main_category_language=$inp_course_language_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean) = $row;

				if($get_current_main_category_id == ""){
					// Create main category
					$inp_course_main_category_title_clean 		= clean($course_main_category_title_sav);
					$inp_course_main_category_title_clean_mysql 	= quote_smart($link, $inp_course_main_category_title_clean);
					mysqli_query($link, "INSERT INTO $t_courses_categories_main
					(main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated) 
					VALUES 
					(NULL, $inp_course_main_category_title_mysql, $inp_course_main_category_title_clean_mysql, '', $inp_course_language_mysql, '$datetime', '$datetime')")
					or die(mysqli_error($link));

					$query = "SELECT main_category_id, main_category_title, main_category_title_clean FROM $t_courses_categories_main WHERE main_category_title=$inp_course_main_category_title_mysql AND main_category_language=$inp_course_language_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean) = $row;


					$fm_detailed = $fm_detailed . "|" . "Inserted main category";
				}

				// Sub category
				$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_courses_categories_sub WHERE sub_category_title=$inp_course_sub_category_title_mysql AND sub_category_language=$inp_course_language_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

				if($get_current_sub_category_id == ""){
					// Create sub category
					$inp_course_sub_category_title_clean 		= clean($course_sub_category_title_sav);
					$inp_course_sub_category_title_clean_mysql 	= quote_smart($link, $inp_course_sub_category_title_clean);

					mysqli_query($link, "INSERT INTO $t_courses_categories_sub
					(sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated) 
					VALUES 
					(NULL, $inp_course_sub_category_title_mysql, $inp_course_sub_category_title_clean_mysql, '', $get_current_main_category_id, $inp_course_main_category_title_mysql, $inp_course_language_mysql, '$datetime', '$datetime')")
					or die(mysqli_error($link));

					$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_courses_categories_sub WHERE sub_category_title=$inp_course_sub_category_title_mysql AND sub_category_language=$inp_course_language_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

					$fm_detailed = $fm_detailed . "|" . "Inserted sub category";
				}


				
				$result = mysqli_query($link, "UPDATE $t_courses_index SET 
							course_title=$inp_course_title_mysql,
							course_title_clean=$inp_course_title_clean_mysql,
						 	course_is_active=$inp_course_is_active_mysql,
						 	course_front_page_intro=$inp_course_front_page_mysql,
						 	course_description=$inp_course_description_mysql,
							course_contents=$inp_course_contents_mysql,
						 	course_language=$inp_course_language_mysql,
						 	course_main_category_id=$get_current_main_category_id,
						 	course_main_category_title=$inp_course_main_category_title_mysql,
						 	course_sub_category_id=$get_current_sub_category_id,
							course_sub_category_title=$inp_course_sub_category_title_mysql,
 							course_intro_video_embedded=$inp_course_intro_video_embedded_mysql,
 							course_image_file=$inp_course_image_file_mysql,
						 	course_image_thumb=$inp_course_image_thumb_mysql,
						 	course_icon_16=$inp_course_icon_a_mysql,
						 	course_icon_32=$inp_course_icon_b_mysql,
							course_icon_48=$inp_course_icon_c_mysql,
							course_icon_64=$inp_course_icon_d_mysql,
							course_icon_96=$inp_course_icon_e_mysql,
							course_icon_260=$inp_course_icon_f_mysql,
							course_read_times_ip_block='',
							course_updated='$datetime'
							WHERE course_id=$get_current_course_id") or die(mysqli_error($link));

					$fm_detailed = $fm_detailed . "|" . "Updated courses_index";

		


			} // ../$get_current_course_title_clean/_course.php



			if(file_exists("../$get_current_course_title_clean/_exam.php")){
				include("../$get_current_course_title_clean/_exam.php");
				$fm_detailed = $fm_detailed . "|" . "Found _exam.php";
				
				if(isset($exam_question_sav)){

					// Delete old questions
					$result = mysqli_query($link, "DELETE FROM $t_courses_exams_qa WHERE qa_course_id=$get_current_course_id") or die(mysqli_error($link));
			
					// Get Exam ID
					$query = "SELECT exam_id, exam_course_id, exam_course_title, exam_language, exam_total_questions, exam_total_points, exam_points_needed_to_pass FROM $t_courses_exams_index WHERE exam_course_id=$get_current_course_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_exam_id, $get_current_exam_course_id, $get_current_exam_course_title, $get_current_exam_language, $get_current_exam_total_questions, $get_current_exam_total_points, $get_current_exam_points_needed_to_pass) = $row;
					if($get_current_exam_id == ""){
						// Insert exam
						$inp_title_mysql = quote_smart($link, $get_current_course_title);
						$inp_title_clean_mysql = quote_smart($link, $get_current_course_title_clean);
						$inp_language_mysql = quote_smart($link, $get_current_course_language);

						mysqli_query($link, "INSERT INTO $t_courses_exams_index 
						(exam_id, exam_course_id, exam_course_title, exam_language, 
						exam_total_questions, exam_total_points, exam_points_needed_to_pass) 
						VALUES 
						(NULL, $get_current_course_id, $inp_title_mysql, $inp_language_mysql, 
						'0', '0', '0')")
						or die(mysqli_error($link));
		
						$query = "SELECT exam_id, exam_course_id, exam_course_title, exam_language, exam_total_questions, exam_total_points, exam_points_needed_to_pass FROM $t_courses_exams_index WHERE exam_course_id=$get_current_course_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_exam_id, $get_current_exam_course_id, $get_current_exam_course_title, $get_current_exam_language, $get_current_exam_total_questions, $get_current_exam_total_points, $get_current_exam_points_needed_to_pass) = $row;

					}

					// Ready inputs
					$inp_course_title_mysql = quote_smart($link, $get_current_course_title);

					$size = sizeof($exam_question_sav);
					$total_questions = 1;
					for($x=0;$x<$size;$x++){
					
						$inp_question_mysql 	= quote_smart($link, $exam_question_sav[$x]);
						$inp_text_mysql 	= quote_smart($link, $exam_question_text_sav[$x]);
						$inp_type_mysql 	= quote_smart($link, $exam_question_type_sav[$x]);
						$inp_alt_a_mysql	= quote_smart($link, $exam_alternative_a_sav[$x]);
						$inp_alt_b_mysql	= quote_smart($link, $exam_alternative_b_sav[$x]);
						$inp_alt_c_mysql	= quote_smart($link, $exam_alternative_c_sav[$x]);
						$inp_alt_d_mysql	= quote_smart($link, $exam_alternative_d_sav[$x]);
						$inp_alt_e_mysql	= quote_smart($link, $exam_alternative_e_sav[$x]);
						$inp_alt_f_mysql	= quote_smart($link, $exam_alternative_f_sav[$x]);
						$inp_alt_g_mysql	= quote_smart($link, $exam_alternative_g_sav[$x]);
						$inp_alt_h_mysql	= quote_smart($link, $exam_alternative_h_sav[$x]);
						$inp_alt_i_mysql	= quote_smart($link, $exam_alternative_i_sav[$x]);
						$inp_alt_j_mysql	= quote_smart($link, $exam_alternative_j_sav[$x]);
						$inp_alt_k_mysql	= quote_smart($link, $exam_alternative_k_sav[$x]);
						$inp_alt_l_mysql	= quote_smart($link, $exam_alternative_l_sav[$x]);
						$inp_alt_m_mysql	= quote_smart($link, $exam_alternative_m_sav[$x]);
						$inp_alt_n_mysql	= quote_smart($link, $exam_alternative_n_sav[$x]);
						$inp_correct_alternatives_mysql = quote_smart($link, $exam_correct_alternatives_sav[$x]);
						$inp_points_mysql = quote_smart($link, $exam_points_sav[$x]);
						$inp_hint_mysql = quote_smart($link, $exam_hint_sav[$x]);
						$inp_explanation_mysql = quote_smart($link, $exam_explanation_sav[$x]);

						mysqli_query($link, "INSERT INTO $t_courses_exams_qa
						(qa_id, qa_course_id, qa_course_title, qa_exam_id, 
						qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, 
						qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, 
						qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, 
						qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, 
						qa_hint, qa_explanation) 
						VALUES 
						(NULL, $get_current_course_id, $inp_course_title_mysql, $get_current_exam_id, 
						$total_questions, $inp_question_mysql, $inp_text_mysql, $inp_type_mysql, $inp_alt_a_mysql, 
						$inp_alt_b_mysql, $inp_alt_c_mysql, $inp_alt_d_mysql, $inp_alt_e_mysql, $inp_alt_f_mysql,
						$inp_alt_g_mysql, $inp_alt_h_mysql, $inp_alt_i_mysql, $inp_alt_j_mysql, $inp_alt_k_mysql,
						$inp_alt_l_mysql, $inp_alt_m_mysql, $inp_alt_n_mysql, $inp_correct_alternatives_mysql, $inp_points_mysql,
						$inp_hint_mysql, $inp_explanation_mysql)")
						or die(mysqli_error($link));

						$total_questions = $total_questions+1;
					}
				} // isset $exam_question_sav
			} // ../$get_current_course_title_clean/_exam.php

			// _modules_and_lessons.txt
			if(file_exists("../$get_current_course_title_clean/_modules_and_lessons.php")){
				$fm_detailed = $fm_detailed . "|" . "Found _modules_and_lessons.php";
				

				// Read
				include("../$get_current_course_title_clean/_modules_and_lessons.php");


				// vars
				$datetime = date("Y-m-d H:i:s");
				$date_formatted = date("j M Y");
				$module_counter = 0;
				$content_counter = 0;
				$inp_module_number = 0;
				$inp_lesson_number = 0;

				// Lang
				$inp_language_mysql = quote_smart($link, $get_current_course_language);

				// Course
				$inp_module_course_title_mysql = quote_smart($link, $get_current_course_title);

				// Todo: update comments references

				// Delete all modules
				$result = mysqli_query($link, "DELETE FROM $t_courses_modules WHERE module_course_id=$get_current_course_id") or die(mysqli_error($link));

				// Delete all lessons
				$result = mysqli_query($link, "DELETE FROM $t_courses_lessons WHERE lesson_course_id=$get_current_course_id") or die(mysqli_error($link));
				
				// Loop trough file
				
				for($x=0;$x<sizeof($module_title_sav);$x++){
					$inp_module_number = $inp_module_number+1;
					
					$inp_module_title = trim($module_title_sav[$x]);
					$inp_module_title = output_html($inp_module_title);
					$inp_module_title = str_replace("&iuml;&raquo;&iquest;", "", $inp_module_title);
					$inp_module_title_mysql = quote_smart($link, $inp_module_title);

					$inp_module_title_clean = clean($inp_module_title);
					$inp_module_title_clean_mysql = quote_smart($link, $inp_module_title_clean);
					
					// Does it exists?
					$query = "SELECT module_id FROM $t_courses_modules WHERE module_course_id=$get_current_course_id AND module_title=$inp_module_title_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_module_id) = $row;
					if($get_current_module_id == ""){
						mysqli_query($link, "INSERT INTO $t_courses_modules
						(module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_created) 
						VALUES 
						(NULL, $get_current_course_id, $inp_module_course_title_mysql, $inp_module_number, $inp_module_title_mysql, $inp_module_title_clean_mysql, 0, '$datetime')")
						or die(mysqli_error($link));

						// Get ID
						$query = "SELECT module_id FROM $t_courses_modules WHERE module_course_id=$get_current_course_id AND module_number=$inp_module_number";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_module_id) = $row;
					}
					
					// Look for lessons
					if(isset($lesson_title_sav[$x])){
						$lessons_array_size = sizeof($lesson_title_sav[$x]);
						for($y=0;$y<$lessons_array_size;$y++){

							$inp_lesson_number = $inp_lesson_number+1;
 
							$inp_lesson_title = $lesson_title_sav[$x][$y];
							$inp_lesson_title = output_html($inp_lesson_title);
							$inp_lesson_title_mysql = quote_smart($link, $inp_lesson_title);

							$inp_lesson_title_clean = clean($inp_lesson_title);
							$inp_lesson_title_clean_mysql = quote_smart($link, $inp_lesson_title_clean);

							$inp_lesson_title_length = strlen($inp_lesson_title);
							$inp_lesson_title_length_mysql = quote_smart($link, $inp_lesson_title_length);

							if($inp_lesson_title_length  > 27){
								$inp_lesson_title_short = substr($inp_lesson_title, 0, 27);
								$inp_lesson_title_short = $inp_lesson_title_short . "...";
							}
							else{
								$inp_lesson_title_short = "";
							}
							$inp_lesson_title_short_mysql = quote_smart($link, $inp_lesson_title_short);


							// Does it exists?
							$query = "SELECT lesson_id FROM $t_courses_lessons WHERE lesson_course_id=$get_current_course_id AND lesson_title=$inp_lesson_title_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_lesson_id) = $row;
							if($get_current_lesson_id == ""){
								mysqli_query($link, "INSERT INTO $t_courses_lessons
								(lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_title_length, lesson_title_short, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_created_datetime, lesson_created_date_formatted) 
								VALUES 
								(NULL, $inp_lesson_number, $inp_lesson_title_mysql, $inp_lesson_title_clean_mysql, $inp_lesson_title_length_mysql, $inp_lesson_title_short_mysql, '', '', $get_current_course_id, $inp_module_course_title_mysql, $get_current_module_id, $inp_module_title_mysql, 0, '$datetime', '$date_formatted')")
								or die(mysqli_error($link));
							}
						} // for lessons
					} // isset lessons
				} // for modules
			} // file_exists("../$get_current_course_title_clean/_modules_and_lessons.php")



			// Search engine :: 1) Find course  Delete search engine course
			$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='courses' AND index_reference_name='course_id' AND index_reference_id='$get_current_course_id'") or die(mysqli_error($link));

			// Search engine :: 2) Find all modules
			$query_m = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_course_id=$get_current_course_id";
			$result_m = mysqli_query($link, $query_m);
			while($row_m = mysqli_fetch_row($result_m)) {
				list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean, $get_module_read_times, $get_module_read_ipblock, $get_module_created, $get_module_updated, $get_module_last_read_datetime, $get_module_last_read_date_formatted) = $row_m;

				// Delete search engine module	
				$result_delete = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='courses' AND index_reference_name='module_id' AND index_reference_id=$get_module_id") or die(mysqli_error($link));

				// Lessons
				$query_l = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_title_length, lesson_title_short, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_module_id=$get_module_id";
				$result_l = mysqli_query($link, $query_l);
				while($row_l = mysqli_fetch_row($result_l)) {
					list($get_lesson_id, $get_lesson_number, $get_lesson_title, $get_lesson_title_clean, $get_lesson_title_length, $get_lesson_title_short, $get_lesson_description, $get_lesson_content, $get_lesson_course_id, $get_lesson_course_title, $get_lesson_module_id, $get_lesson_module_title, $get_lesson_read_times, $get_lesson_read_times_ipblock, $get_lesson_created_datetime, $get_lesson_created_date_formatted, $get_lesson_last_read_datetime, $get_lesson_last_read_date_formatted) = $row_l;

					// Delete search engine lessons
					$result_delete = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='courses' AND index_reference_name='lesson_id' AND index_reference_id=$get_lesson_id") or die(mysqli_error($link));
				}
				
			}

	
			// Search engine :: 3) Insert course
			$inp_index_title = "$get_current_course_title | $get_current_courses_title_translation_title";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "$get_current_course_title_clean";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);

			$inp_short_description_mysql = quote_smart($link, $get_current_course_front_page_intro);

			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
			index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
			index_unique_hits) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_short_description_mysql, '', 
			'courses', 'course', 0, 'course_id', $get_current_course_id, 
			0, 0, '$datetime', '$datetime_saying', $l_mysql,
			0)")
			or die(mysqli_error($link));


			// Search engine :: 3) Insert  Modules
			$query_m = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_course_id=$get_current_course_id";
			$result_m = mysqli_query($link, $query_m);
			while($row_m = mysqli_fetch_row($result_m)) {
				list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean, $get_module_read_times, $get_module_read_ipblock, $get_module_created, $get_module_updated, $get_module_last_read_datetime, $get_module_last_read_date_formatted) = $row_m;

	

				$inp_index_title = "$get_module_title | $get_current_course_title | $get_current_courses_title_translation_title";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_current_course_title_clean/$get_module_title_clean/index.php?course_id=$get_current_course_id&module_id=$get_module_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);


				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', '', 
				'courses', 'module', '0', 'module_id', $get_module_id,
				'0', 0, '$datetime', '$datetime_saying', $l_mysql,
				0)")
				or die(mysqli_error($link));



				// Lessons
				$query_l = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_title_length, lesson_title_short, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_module_id=$get_module_id";
				$result_l = mysqli_query($link, $query_l);
				while($row_l = mysqli_fetch_row($result_l)) {
					list($get_lesson_id, $get_lesson_number, $get_lesson_title, $get_lesson_title_clean, $get_lesson_title_length, $get_lesson_title_short, $get_lesson_description, $get_lesson_content, $get_lesson_course_id, $get_lesson_course_title, $get_lesson_module_id, $get_lesson_module_title, $get_lesson_read_times, $get_lesson_read_times_ipblock, $get_lesson_created_datetime, $get_lesson_created_date_formatted, $get_lesson_last_read_datetime, $get_lesson_last_read_date_formatted) = $row_l;

					$inp_index_title = "$get_lesson_title | $get_current_course_title | $get_current_courses_title_translation_title";
					$inp_index_title_mysql = quote_smart($link, $inp_index_title);

					$inp_index_url = "$get_current_course_title_clean/$get_module_title_clean/$get_lesson_title_clean.php?course_id=$get_current_course_id&module_id=$get_module_id&lesson_id=$get_lesson_id";
					$inp_index_url_mysql = quote_smart($link, $inp_index_url);

					$inp_index_short_description = "$get_lesson_description";
					$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);


					mysqli_query($link, "INSERT INTO $t_search_engine_index 
					(index_id, index_title, index_url, index_short_description, index_keywords, 
					index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
					index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
					index_unique_hits) 
					VALUES 
					(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', '', 
					'courses', 'lesson', '0', 'lesson_id', $get_lesson_id,
					'0', 0, '$datetime', '$datetime_saying', $l_mysql,
					0)")
					or die(mysqli_error($link));

				} // lessons

			} // modules


			echo"
			<meta http-equiv=refresh content=\"1; URL=index.php?open=courses&amp;page=$page&amp;course_id=$get_current_course_id&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=data_read&amp;fm_detailed=$fm_detailed\">
			";
			exit;
		}
		echo"
		<h1>$get_current_course_title</h1>
				

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses menu</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_main_category&amp;main_category_id=$get_current_course_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_main_category_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_sub_category&amp;sub_category_id=$get_current_course_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_sub_category_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Course navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_modules_and_lessons&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Course navigation -->

		<!-- Files -->
			<p><b>Files to read from:</b></p>
			<table>
			 <tr>
			  <td style=\"padding-right: 6px;\">
				<span>_course.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_course_title_clean/_course.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_course_title_clean/_course.php"));
					echo"<span>$modified</span>";
				}
				else{
					echo"<span style=\"color:red;\">Doesnt exits</a>";
				}
				echo"
			  </td>
			 </tr>
			 <tr>
			  <td style=\"padding-right: 6px;\">
				<span>_exam.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_course_title_clean/_exam.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_course_title_clean/_exam.php"));
					echo"<span>$modified</span>";
				}
				else{
					echo"<span style=\"color:red;\">Doesnt exits</a>";
				}
				echo"
			  </td>
			 </tr>
			 <tr>
			  <td style=\"padding-right: 6px;\">
				<span>_modules_and_lessons.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_course_title_clean/_modules_and_lessons.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_course_title_clean/_modules_and_lessons.php"));
					echo"<span>$modified</span>";
				}
				else{
					echo"<span style=\"color:red;\">Doesnt exits</a>";
				}
				echo"
			  </td>
			 </tr>
			</table>

	
		<!-- //Files -->

		<!-- Actions -->
			<p><b>Actions:</b><br />
			Do you want to read from the files and save the data into the database?
			</p>

			<p>
			<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Read from files</a>
			</p>
		<!-- //Actions -->

		";
	} // action == ""
} // found
?>