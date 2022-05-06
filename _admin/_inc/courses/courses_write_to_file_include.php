<?php
/**
*
* File: _admin/_inc/comments/courses_write_to_file_include.php
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


			// Mkdir
			if(!(is_dir("../$get_current_course_title_clean"))){
				mkdir("../$get_current_course_title_clean");
			}

			// Create file
			$datetime_print = date("j M Y H:i");
			$year = date("Y");
			$page_id = date("ymdhis");
			$input="<?php
/**
*
* File: $get_current_course_title_clean/index.php
* Version 3.0.0
* Date $datetime_print
* Copyright (c) 2009-$year Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
* Written by courses_modules_and_lessons.php line 35
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
\$pageIdSav            = \"$page_id\";
\$pageNoColumnSav      = \"2\";
\$pageAllowCommentsSav = \"0\";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }

/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/*- Translation ------------------------------------------------------------------------------ */
// include(\"\$root/_admin/_translations/site/\$l/courses/ts_courses.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$get_current_course_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }";
if($get_current_main_category_webdesign != "same_as_website" && $get_current_main_category_webdesign !=""){
	$input = $input . "
\$webdesignSav = \"$get_current_main_category_webdesign\";";
	$input = $input . "
\$courseMainCategoryIdSav = \"$get_current_main_category_id\";";
}
$input = $input . "
include(\"\$root/_webdesign/\$webdesignSav/header.php\");

/* Course header ---------------------------------------------------------------------------- */
\$courseTitleSav = \"$get_current_course_title\";

include(\"\$root/courses/_includes/course.php\");

/*- Footer ---------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";

			$fh = fopen("../$get_current_course_title_clean/index.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);

			// _course.txt
			$datetime = date("Y-m-d H:i:s");
			$input_course_txt ="<?php
\$course_txt_file_generated_datetime	= \"$datetime\";

\$course_title_sav 			= \"$get_current_course_title\";
\$course_title_clean_sav 		= \"$get_current_course_title_clean\";
\$course_is_active_sav			= \"$get_current_course_is_active\";
\$course_front_page_intro_sav		= \"$get_current_course_front_page_intro\";
\$course_description_sav   		= \"$get_current_course_description\";
\$course_contents_sav			= \"$get_current_course_contents\";
\$course_language_sav			= \"$get_current_course_language\";
\$course_main_category_title_sav  	= \"$get_current_course_main_category_title\";
\$course_sub_category_title_sav		= \"$get_current_course_sub_category_title\";
\$course_intro_video_embedded_sav	= \"$get_current_course_intro_video_embedded\";
\$course_image_file_sav			= \"$get_current_course_image_file\";
\$course_image_thumb_sav  		= \"$get_current_course_image_thumb\";
\$course_icon_a_sav			= \"$get_current_course_icon_16\"; // 16x16
\$course_icon_b_sav			= \"$get_current_course_icon_32\"; // 32x32
\$course_icon_c_sav			= \"$get_current_course_icon_48\"; // 48x48
\$course_icon_d_sav			= \"$get_current_course_icon_64\"; // 64x64
\$course_icon_e_sav			= \"$get_current_course_icon_96\"; // 96x96
\$course_icon_f_sav			= \"$get_current_course_icon_260\"; // 260x260
?>";

			$fh = fopen("../$get_current_course_title_clean/_course.php", "w+") or die("can not open file");
			fwrite($fh, $input_course_txt);
			fclose($fh);


			// _modules_and_lessons.php + modules files
			$total_modules = 0;
			$total_lessons = 0;
			$input_modules_and_lessons = "<?php";
			$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_content FROM $t_courses_modules WHERE module_course_id=$get_current_course_id ORDER BY module_number ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean, $get_module_content) = $row;
				
				$input_modules_and_lessons = $input_modules_and_lessons . "

/*- $get_module_title -------------------------------------- */
\$module_title_sav[$total_modules] = \"$get_module_title\";";
				


				// Module dir
				if(!(is_dir("../$get_current_course_title_clean/$get_module_title_clean"))){
					mkdir("../$get_current_course_title_clean/$get_module_title_clean");
				}

				// Module index
				if($get_module_content != "" OR !(file_exists("../$get_current_course_title_clean/$get_module_title_clean/index.php"))){
					$input = "<?php
/**
*
* File: $get_current_course_title_clean/$get_module_title_clean/index.php
* Version 3.0.0
* Date $datetime_print
* Copyright (c) 2009-$year Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
* Written by courses_modules_and_lessons.php line 146
*
*/

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }


/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/*- Translation ------------------------------------------------------------------------------ */
// include(\"\$root/_admin/_translations/site/\$l/courses/ts_courses.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$get_module_title - $get_current_course_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }";
if($get_current_main_category_webdesign != "same_as_website" && $get_current_main_category_webdesign !=""){
	$input = $input . "
\$webdesignSav = \"$get_current_main_category_webdesign\";";
	$input = $input . "
\$courseMainCategoryIdSav = \"$get_current_main_category_id\";";
}
$input = $input . "
include(\"\$root/_webdesign/\$webdesignSav/header.php\");

/* Module content ------------------------------------------------------------------------------- */
echo\"
$get_module_content
\";

/*- Course ---------------------------------------------------------- */
include(\"\$root/courses/_includes/content_after_content.php\");


/*- Footer ----------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";

					$fh = fopen("../$get_current_course_title_clean/$get_module_title_clean/index.php", "w+") or die("can not open file");
					fwrite($fh, $input);
					fclose($fh);
				} // write to module index

				$lesson_counter = 0;
				$query_lessons = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_content FROM $t_courses_lessons WHERE lesson_module_id=$get_module_id ORDER BY lesson_number ASC";
				$result_lessons = mysqli_query($link, $query_lessons);
				while($row_lessons = mysqli_fetch_row($result_lessons)) {
					list($get_lesson_id, $get_lesson_number, $get_lesson_title, $get_lesson_title_clean, $get_lesson_content) = $row_lessons;

					$input_modules_and_lessons = $input_modules_and_lessons . "
\$lesson_title_sav[$total_modules][$lesson_counter] = \"$get_lesson_title\";";



					// Lesson
					if($get_lesson_content != "" OR !(file_exists("../$get_current_course_title_clean/$get_module_title_clean/$get_lesson_title_clean.php"))){
						$input = "<?php
/**
*
* File: $get_current_course_title_clean/$get_module_title_clean/$get_lesson_title_clean.php
* Version 3.0.0
* Date $datetime_print
* Copyright (c) 2009-$year Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
* Written by courses_modules_and_lessons.php line 222
*
*/

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }


/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/*- Translation ------------------------------------------------------------------------------ */
// include(\"\$root/_admin/_translations/site/\$l/courses/ts_courses.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$get_lesson_title - $get_module_title - $get_current_course_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }";
if($get_current_main_category_webdesign != "same_as_website" && $get_current_main_category_webdesign !=""){
	$input = $input . "
\$webdesignSav = \"$get_current_main_category_webdesign\";";
	$input = $input . "
\$courseMainCategoryIdSav = \"$get_current_main_category_id\";";
}
$input = $input . "
include(\"\$root/_webdesign/\$webdesignSav/header.php\");

/* Module content ------------------------------------------------------------------------------- */
echo\"
$get_lesson_content
\";

/*- Course ---------------------------------------------------------- */
include(\"\$root/courses/_includes/content_after_content.php\");


/*- Footer ----------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";

						$fh = fopen("../$get_current_course_title_clean/$get_module_title_clean/$get_lesson_title_clean.php", "w+") or die("can not open file");
						fwrite($fh, $input);
						fclose($fh);

					} // write lesson

					$lesson_counter = $lesson_counter+1;
					$total_lessons = $total_lessons+1;
				} // while lessons

				// Increment
				$total_modules = $total_modules+1;
			} // while modules

			$input_modules_and_lessons = $input_modules_and_lessons . "
?>";
			$fh = fopen("../$get_current_course_title_clean/_modules_and_lessons.php", "w+") or die("can not open file");
			fwrite($fh, $input_modules_and_lessons);
			fclose($fh);


			// _exam.php
			$input_exam = "<?php
";
			$x = 0;
			$query = "SELECT qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, qa_explanation FROM $t_courses_exams_qa WHERE qa_course_id=$get_current_course_id ORDER BY qa_question_number ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_qa_id, $get_qa_course_id, $get_qa_course_title, $get_qa_exam_id, $get_qa_question_number, $get_qa_question, $get_qa_text, $get_qa_type, $get_qa_alt_a, $get_qa_alt_b, $get_qa_alt_c, $get_qa_alt_d, $get_qa_alt_e, $get_qa_alt_f, $get_qa_alt_g, $get_qa_alt_h, $get_qa_alt_i, $get_qa_alt_j, $get_qa_alt_k, $get_qa_alt_l, $get_qa_alt_m, $get_qa_alt_n, $get_qa_correct_alternatives, $get_qa_points, $get_qa_hint, $get_qa_explanation) = $row;
				
				$input_exam = $input_exam . "
\$exam_question_sav[$x]		= \"$get_qa_question\";
\$exam_question_text_sav[$x]	= \"$get_qa_text\";
\$exam_question_type_sav[$x]	= \"$get_qa_type\";
\$exam_alternative_a_sav[$x]	= \"$get_qa_alt_a\";
\$exam_alternative_b_sav[$x]	= \"$get_qa_alt_b\";
\$exam_alternative_c_sav[$x]	= \"$get_qa_alt_c\";
\$exam_alternative_d_sav[$x]	= \"$get_qa_alt_d\";
\$exam_alternative_e_sav[$x]	= \"$get_qa_alt_e\";
\$exam_alternative_f_sav[$x]	= \"$get_qa_alt_f\";
\$exam_alternative_g_sav[$x]	= \"$get_qa_alt_g\";
\$exam_alternative_h_sav[$x]	= \"$get_qa_alt_h\";
\$exam_alternative_i_sav[$x]	= \"$get_qa_alt_i\";
\$exam_alternative_j_sav[$x]	= \"$get_qa_alt_j\";
\$exam_alternative_k_sav[$x]	= \"$get_qa_alt_k\";
\$exam_alternative_l_sav[$x]	= \"$get_qa_alt_l\";
\$exam_alternative_m_sav[$x]	= \"$get_qa_alt_m\";
\$exam_alternative_n_sav[$x]	= \"$get_qa_alt_n\";
\$exam_correct_alternatives_sav[$x]	= \"$get_qa_correct_alternatives\";
\$exam_points_sav[$x]		= \"$get_qa_points\";
\$exam_hint_sav[$x]			= \"$get_qa_hint\";
\$exam_explanation_sav[$x]		= \"$get_qa_explanation\";

";
				$x = $x+1;
			}
			$input_exam = $input_exam  . "?>";
			$fh = fopen("../$get_current_course_title_clean/_exam.php", "w+") or die("can not open file");
			fwrite($fh, $input_exam);
			fclose($fh);

			// Exam
			$input="<?php
/**
*
* File: $get_current_course_title_clean/exam.php
* Version 3.0.0
* Date $datetime_print
* Copyright (c) 2009-$year Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
* Generated by courses_modules_and_lessons.php line 273
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
\$pageIdSav            = \"$page_id\";
\$pageNoColumnSav      = \"2\";
\$pageAllowCommentsSav = \"0\";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }

/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/* Course exam ------------------------------------------------------------------------------- */
\$courseTitleSav = \"$get_current_course_title\";
include(\"\$root/courses/_includes/exam.php\");

?>";

			$fh = fopen("../$get_current_course_title_clean/exam.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);


			// Exam certificate
			$input="<?php
/**
*
* File: $get_current_course_title_clean/exam_certificate.php
* Version 3.0.0
* Date $datetime_print
* Copyright (c) 2009-$year Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
* Generated by courses_modules_and_lessons.php line 314
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
\$pageIdSav            = \"$page_id\";
\$pageNoColumnSav      = \"2\";
\$pageAllowCommentsSav = \"0\";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }

/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/* Course exam ------------------------------------------------------------------------------- */
\$courseTitleSav = \"$get_current_course_title\";
include(\"\$root/courses/_includes/exam_certificate.php\");

?>";

			$fh = fopen("../$get_current_course_title_clean/exam_certificate.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);


			// Navigation
			// copy("../courses/_includes/course_navigation.php", "../$get_current_course_title_clean/navigation.php");
			$inp_navigation="<?php
/**
*
* File: $get_current_course_title_clean/course_navigation.php
* Version 2.0.0
* Date $datetime
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
* Generated by courses_modules_and_lessons.php line 356
*
*/
/*- Course info --------------------------------------------------------------------- */
\$courseTitleSav = \"$get_current_course_title\";

/*- Functions ------------------------------------------------------------------------ */

/*- Variables ------------------------------------------------------------------------ */
if(isset(\$_GET['module_id'])) {
	\$module_id = \$_GET['module_id'];
	\$module_id = strip_tags(stripslashes(\$module_id));
}
else{
	\$module_id = \"\";
}
if(isset(\$_GET['lesson_id'])) {
	\$lesson_id = \$_GET['lesson_id'];
	\$lesson_id = strip_tags(stripslashes(\$lesson_id));
}
else{
	\$lesson_id = \"\";
}

/*- Tables ---------------------------------------------------------------------------- */
\$t_courses_title_translations	 = \$mysqlPrefixSav . \"courses_title_translations\";
\$t_courses_index		 = \$mysqlPrefixSav . \"courses_index\";
\$t_courses_users_enrolled 	 = \$mysqlPrefixSav . \"courses_users_enrolled\";

\$t_courses_categories_main	 = \$mysqlPrefixSav . \"courses_categories_main\";
\$t_courses_categories_sub 	 = \$mysqlPrefixSav . \"courses_categories_sub\";
\$t_courses_modules		 = \$mysqlPrefixSav . \"courses_modules\";
\$t_courses_modules_read		 = \$mysqlPrefixSav . \"courses_modules_read\";

\$t_courses_lessons 	 	= \$mysqlPrefixSav . \"courses_lessons\";
\$t_courses_lessons_read 	= \$mysqlPrefixSav . \"courses_lessons_read\";
\$t_courses_lessons_comments	= \$mysqlPrefixSav . \"courses_lessons_comments\";

\$t_courses_modules_quizzes_index  	= \$mysqlPrefixSav . \"courses_modules_quizzes_index\";
\$t_courses_modules_quizzes_qa 		= \$mysqlPrefixSav . \"courses_modules_quizzes_qa\";
\$t_courses_modules_quizzes_user_records	= \$mysqlPrefixSav . \"courses_modules_quizzes_user_records\";

\$t_courses_exams_index  		= \$mysqlPrefixSav . \"courses_exams_index\";
\$t_courses_exams_qa			= \$mysqlPrefixSav . \"courses_exams_qa\";
\$t_courses_exams_user_tries		= \$mysqlPrefixSav . \"courses_exams_user_tries\";
\$t_courses_exams_user_tries_qa		= \$mysqlPrefixSav . \"courses_exams_user_tries_qa\";


/*- Translations ---------------------------------------------------------------------- */
include(\"\$root/_admin/_translations/site/\$l/courses/ts_navigation.php\");

// Find course
\$course_title_mysql = quote_smart(\$link, \$courseTitleSav);
\$query = \"SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM \$t_courses_index WHERE course_title=\$course_title_mysql\";
\$result = mysqli_query(\$link, \$query);
\$row = mysqli_fetch_row(\$result);
list(\$get_current_course_id, \$get_current_course_title, \$get_current_course_title_clean, \$get_current_course_is_active, \$get_current_course_front_page_intro, \$get_current_course_description, \$get_current_course_contents, \$get_current_course_language, \$get_current_course_main_category_id, \$get_current_course_main_category_title, \$get_current_course_sub_category_id, \$get_current_course_sub_category_title, \$get_current_course_intro_video_embedded, \$get_current_course_image_file, \$get_current_course_image_thumb, \$get_current_course_icon_48, \$get_current_course_icon_64, \$get_current_course_icon_96, \$get_current_course_modules_count, \$get_current_course_lessons_count, \$get_current_course_quizzes_count, \$get_current_course_users_enrolled_count, \$get_current_course_read_times, \$get_current_course_read_times_ip_block, \$get_current_course_created, \$get_current_course_updated) = \$row;

if(\$get_current_course_id != \"\"){
	// Find me
	\$get_current_enrolled_id  = \"\";
	if(isset(\$_SESSION['user_id']) && isset(\$_SESSION['security'])){
		// Get user
		\$my_user_id = \$_SESSION['user_id'];
		\$my_user_id_mysql = quote_smart(\$link, \$my_user_id);
		\$my_security = \$_SESSION['security'];
		\$my_security_mysql = quote_smart(\$link, \$my_security);
	
		// Am I enrolled?
		\$query = \"SELECT enrolled_id, enrolled_course_id, enrolled_course_title, enrolled_course_title_clean, enrolled_user_id, enrolled_started_datetime, enrolled_started_saying, enrolled_percentage_done, enrolled_has_completed_exam, enrolled_exam_total_questions, enrolled_exam_correct_answers, enrolled_exam_correct_percentage, enrolled_completed_datetime, enrolled_completed_saying FROM \$t_courses_users_enrolled WHERE enrolled_course_id=\$get_current_course_id AND enrolled_user_id=\$my_user_id_mysql\";
		\$result = mysqli_query(\$link, \$query);
		\$row = mysqli_fetch_row(\$result);
		list(\$get_current_enrolled_id, \$get_current_enrolled_course_id, \$get_current_enrolled_course_title, \$get_current_enrolled_course_title_clean, \$get_current_enrolled_user_id, \$get_current_enrolled_started_datetime, \$get_current_enrolled_started_saying, \$get_current_enrolled_percentage_done, \$get_current_enrolled_has_completed_exam, \$get_current_enrolled_exam_total_questions, \$get_current_enrolled_exam_correct_answers, \$get_current_enrolled_exam_correct_percentage, \$get_current_enrolled_completed_datetime, \$get_current_enrolled_completed_saying) = \$row;
		if(\$get_current_enrolled_id == \"\"){
			// Insert
			\$datetime = date(\"Y-m-d H:i:s\");
			\$date_saying = date(\"j. M Y\");
			\$inp_course_title_mysql = quote_smart(\$link, \$get_current_course_title);
			\$inp_course_title_clean_mysql = quote_smart(\$link, \$get_current_course_title_clean);

			mysqli_query(\$link, \"INSERT INTO \$t_courses_users_enrolled 
			(enrolled_id, enrolled_course_id, enrolled_course_title, enrolled_course_title_clean, enrolled_user_id, enrolled_started_datetime, enrolled_started_saying, enrolled_percentage_done, enrolled_has_completed_exam) 
			VALUES 
			(NULL, \$get_current_course_id, \$inp_course_title_mysql, \$inp_course_title_clean_mysql, \$my_user_id_mysql, '\$datetime', '\$date_saying', '0', '0')\")
			or die(mysqli_error(\$link));

			// Endrolled in course
			\$inp_course_users_enrolled_count = \$get_current_course_users_enrolled_count + 1;
			\$result = mysqli_query(\$link, \"UPDATE \$t_courses_index SET course_users_enrolled_count=\$inp_course_users_enrolled_count WHERE course_id=\$get_current_course_id\");

			// Get enrolled ID
			\$query = \"SELECT enrolled_id FROM \$t_courses_users_enrolled WHERE enrolled_course_id=\$get_current_course_id AND enrolled_user_id=\$my_user_id_mysql\";
			\$result = mysqli_query(\$link, \$query);
			\$row = mysqli_fetch_row(\$result);
			list(\$get_current_enrolled_id) = \$row;


		}
		
	}
	// Nav start

	echo\"
				<ul class=\\\"toc\\\">\n\";


	// Modules
	\$query = \"SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean FROM \$t_courses_modules WHERE module_course_id=\$get_current_course_id ORDER BY module_number ASC\";
	\$result = mysqli_query(\$link, \$query);
	while(\$row = mysqli_fetch_row(\$result)) {
		list(\$get_module_id, \$get_module_course_id, \$get_module_course_title, \$get_module_number, \$get_module_title, \$get_module_title_clean) = \$row;

		// Did I complete this module?
		if(isset(\$my_user_id)){
			\$query_m = \"SELECT module_read_id FROM \$t_courses_modules_read WHERE read_course_id=\$get_current_course_id AND read_module_id=\$get_module_id AND read_user_id=\$my_user_id_mysql\";
			\$result_m = mysqli_query(\$link, \$query_m);
			\$row_m = mysqli_fetch_row(\$result_m);
			list(\$get_module_read_id) = \$row_m;


			if(\$get_module_read_id == \"\" && \$get_module_id == \"\$module_id\"){
				\$inp_course_title_mysql = quote_smart(\$link, \$get_current_course_title);
				\$inp_module_title_mysql = quote_smart(\$link, \$get_module_title);

				mysqli_query(\$link, \"INSERT INTO \$t_courses_modules_read 
				(module_read_id, read_course_id, read_course_title, read_module_id, read_module_title, read_user_id) 
				VALUES 
				(NULL, \$get_current_course_id, \$inp_course_title_mysql, \$get_module_id, \$inp_module_title_mysql, \$my_user_id_mysql)\");

				// Give point
				\$query_u = \"SELECT user_id, user_name, user_alias, user_points FROM \$t_users WHERE user_id=\$my_user_id_mysql\";
				\$result_u = mysqli_query(\$link, \$query_u);
				\$row_u = mysqli_fetch_row(\$result_u);
				list(\$get_my_user_id, \$get_my_user_name, \$get_my_user_alias, \$get_my_user_points) = \$row_u;
				
				\$inp_user_points = \$get_my_user_points +1;

				\$result_u = mysqli_query(\$link, \"UPDATE \$t_users SET user_points=\$inp_user_points WHERE user_id=\$get_my_user_id\");
			}

		}
		else{
			\$get_module_read_id = \"\";
		}

		echo\"
\";
		echo\"					\";

		if(\$get_module_number == \"1\"){
			echo\"<li class=\\\"header_home\\\"><a href=\\\"\$root/\$get_current_course_title_clean/\$get_module_title_clean/index.php?course_id=\$get_current_course_id&amp;module_id=\$get_module_id&amp;l=\$l\\\" id=\\\"navigation_module_id_\$get_module_id\\\">\$get_module_course_title</a></li>\n\";


			echo\"					\";
			echo\"<li><a href=\\\"\$root/\$get_current_course_title_clean/index.php?l=\$l\\\">\";
			if(\$get_current_enrolled_id != \"\"){
				echo\"<img src=\\\"\$root/courses/_images/icons/navigation_lesson_read.png\\\" alt=\\\"navigation_lesson_read.png\\\" />\"; 
			}
			echo\"\$l_index</a></li>\n\";
		}
		else{
			echo\"<li class=\\\"header_up\\\"><a href=\\\"\$root/\$get_current_course_title_clean/\$get_module_title_clean/index.php?course_id=\$get_current_course_id&amp;module_id=\$get_module_id&amp;l=\$l\\\" id=\\\"navigation_module_id_\$get_module_id\\\">\$get_module_title</a></li>\n\";


		}
		// Get lessons
		\$query_lessons = \"SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_title_length, lesson_title_short FROM \$t_courses_lessons WHERE lesson_module_id=\$get_module_id ORDER BY lesson_number ASC\";
		\$result_lessons = mysqli_query(\$link, \$query_lessons);
		while(\$row_lessons = mysqli_fetch_row(\$result_lessons)) {
			list(\$get_lesson_id, \$get_lesson_number, \$get_lesson_title, \$get_lesson_title_clean, \$get_lesson_title_length, \$get_lesson_title_short) = \$row_lessons;

			// Did I complete this lesson?
			if(isset(\$my_user_id)){
				\$query_m = \"SELECT lesson_read_id FROM \$t_courses_lessons_read WHERE read_course_id=\$get_current_course_id AND read_lesson_id=\$get_lesson_id AND read_user_id=\$my_user_id_mysql\";
				\$result_m = mysqli_query(\$link, \$query_m);
				\$row_m = mysqli_fetch_row(\$result_m);
				list(\$get_lesson_read_id) = \$row_m;

				if(\$get_lesson_read_id == \"\" && \$get_lesson_id == \"\$lesson_id\"){
					\$inp_course_title_mysql = quote_smart(\$link, \$get_current_course_title);
					\$inp_module_title_mysql = quote_smart(\$link, \$get_module_course_title);
					\$inp_lesson_title_mysql = quote_smart(\$link, \$get_lesson_title);

					mysqli_query(\$link, \"INSERT INTO \$t_courses_lessons_read 
					(lesson_read_id, read_course_id, read_course_title, read_module_id, read_module_title, read_lesson_id, read_lesson_title, read_user_id) 
					VALUES 
					(NULL, \$get_current_course_id, \$inp_course_title_mysql, \$get_module_id, \$inp_module_title_mysql, \$get_lesson_id, \$inp_lesson_title_mysql, \$my_user_id_mysql)\");
					
					// Read ID
					\$query_m = \"SELECT lesson_read_id FROM \$t_courses_lessons_read WHERE read_course_id=\$get_current_course_id AND read_lesson_id=\$get_lesson_id AND read_user_id=\$my_user_id_mysql\";
					\$result_m = mysqli_query(\$link, \$query_m);
					\$row_m = mysqli_fetch_row(\$result_m);
					list(\$get_lesson_read_id) = \$row_m;

					// Give point
					\$query_u = \"SELECT user_id, user_name, user_alias, user_points FROM \$t_users WHERE user_id=\$my_user_id_mysql\";
					\$result_u = mysqli_query(\$link, \$query_u);
					\$row_u = mysqli_fetch_row(\$result_u);
					list(\$get_my_user_id, \$get_my_user_name, \$get_my_user_alias, \$get_my_user_points) = \$row_u;

					\$inp_user_points = \$get_my_user_points+1;

					\$result_u = mysqli_query(\$link, \"UPDATE \$t_users SET user_points=\$inp_user_points WHERE user_id=\$get_my_user_id\");

				}

			}
			else{
				\$get_lesson_read_id = \"\";
			}


			echo\"					\";
			echo\"<li><a href=\\\"\$root/\$get_current_course_title_clean/\$get_module_title_clean/\$get_lesson_title_clean.php?course_id=\$get_current_course_id&amp;module_id=\$get_module_id&amp;lesson_id=\$get_lesson_id&amp;l=\$l\\\" \"; if(\$get_lesson_id == \"\$lesson_id\"){ echo\" class=\\\"navigation_active\\\"\"; } echo\" id=\\\"navigation_lesson_id_\$get_lesson_id\\\"\";
			if(\$get_lesson_title_length  > 30){
				echo\" title=\\\"\$get_lesson_title\\\"\"; 
			}
			echo\">\";
			if(\$get_lesson_read_id != \"\"){ 
				echo\"<img src=\\\"\$root/courses/_images/icons/navigation_lesson_read.png\\\" alt=\\\"navigation_lesson_read.png\\\" />\"; 
			}
			if(\$get_lesson_title_length  > 30){
				echo\"\$get_lesson_title_short\";
			}
			else{
				echo\"\$get_lesson_title\";
			}
			echo\"</a></li>
\";
		} // Lessons
	} // modules
	// Nav stop

	// Find exam
	\$query = \"SELECT exam_id, exam_course_id, exam_course_title, exam_language, exam_total_questions, exam_total_points, exam_points_needed_to_pass FROM \$t_courses_exams_index WHERE exam_course_id=\$get_current_course_id\";
	\$result = mysqli_query(\$link, \$query);
	\$row = mysqli_fetch_row(\$result);
	list(\$get_current_exam_id, \$get_current_exam_course_id, \$get_current_exam_course_title, \$get_current_exam_language, \$get_current_exam_total_questions, \$get_current_exam_total_points, \$get_current_exam_points_needed_to_pass) = \$row;
	if(\$get_current_exam_id != \"\"){

		// Check if I've completed exam
		\$get_current_try_id = \"\";
		if(isset(\$my_user_id)){
			\$query_m = \"SELECT try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM \$t_courses_exams_user_tries WHERE try_course_id=\$get_current_course_id AND try_user_id=\$my_user_id_mysql AND try_passed=1\";
			\$result_m = mysqli_query(\$link, \$query_m);
			\$row_m = mysqli_fetch_row(\$result_m);
			list(\$get_current_try_id, \$get_current_try_course_id, \$get_current_try_course_title, \$get_current_try_exam_id, \$get_current_try_user_id, \$get_current_try_started_datetime, \$get_current_try_started_time, \$get_current_try_started_saying, \$get_current_try_is_closed, \$get_current_try_ended_datetime, \$get_current_try_ended_time, \$get_current_try_ended_saying, \$get_current_try_finished_saying, \$get_current_try_time_used, \$get_current_try_percentage, \$get_current_try_passed) = \$row_m;
		}

		echo\"
\";
		echo\"					\";
		echo\"<li class=\\\"header_up\\\"><a href=\\\"\$root/\$get_current_course_title_clean/exam.php?course_id=\$get_current_course_id&amp;module_id=\$get_module_id&amp;l=\$l\\\">\$l_exam</a></li>\n\";

		// Take exam
		echo\"					\";
		echo\"<li><a href=\\\"\$root/\$get_current_course_title_clean/exam.php?course_id=\$get_current_course_id&amp;module_id=\$get_module_id&amp;l=\$l\\\" id=\\\"navigation_exam\\\">\";
		if(\$get_current_try_id != \"\"){ 
			echo\"<img src=\\\"\$root/courses/_images/icons/navigation_lesson_read.png\\\" alt=\\\"navigation_lesson_read.png\\\" />\"; 
		}
		echo\"\$l_exam_index</a></li>\n\";


	}

	echo\"
				</ul> <!-- //toc -->
	\";

	// Scroll to module
	if(\$module_id !=\"\"){
		echo\"
		<script> 
		\\\$(document).ready(function(){
			var elmnt = document.getElementById(\\\"navigation_module_id_\$module_id\\\");
			elmnt.scrollIntoView();
		});
		</script>
		\";
	}
} // course found
?>

			";
			$fh = fopen("../$get_current_course_title_clean/navigation.php", "w+") or die("can not open file");
			fwrite($fh, $inp_navigation);
			fclose($fh);
?>