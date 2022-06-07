<?php
/**
*
* File: courses/_includes/course.php
* Version 2.0.0
* Date 22:38 03.05.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Functions ------------------------------------------------------------------------- */

/*- Translations ---------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/courses/ts_course.php");


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



// Find course
$course_title_mysql = quote_smart($link, $courseTitleSav);
$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_title=$course_title_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

if($get_current_course_id != ""){
	// Find me
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		// Get user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$my_security = $_SESSION['security'];
		$my_security_mysql = quote_smart($link, $my_security);
	}
	


	if($action == ""){
		// Headline
		echo"
		<h1>$get_current_course_title</h1>
		";


		
		// Course read times
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		
		$ipblock_array = explode("\n", $get_current_course_read_times_ip_block);
		$size = sizeof($ipblock_array);
		$i_have_visited_before = "false";
		for($x=0;$x<$size;$x++){
			if($ipblock_array[$x] == "$my_ip"){
				$i_have_visited_before = "true";
			}
		}
			
		if($i_have_visited_before == "false"){
			$inp_course_read_times = $get_current_course_read_times+1;
		
			if($get_current_course_read_times_ip_block == ""){
				$inp_course_read_times_ip_block = "$my_ip";
			}
			else{
				$inp_course_read_times_ip_block = "$my_ip\n" . substr($get_current_course_read_times_ip_block, 0, 400);
			}
			$inp_course_read_times_ip_block_mysql = quote_smart($link, $inp_course_read_times_ip_block);
			$result = mysqli_query($link, "UPDATE $t_courses_index SET course_read_times=$inp_course_read_times, course_read_times_ip_block=$inp_course_read_times_ip_block_mysql WHERE course_id=$get_current_course_id") or die(mysqli_error($link));
		}

		// Enrolled?
		if(isset($my_user_id_mysql)){
			$query = "SELECT enrolled_id, enrolled_course_id, enrolled_course_title, enrolled_course_title_clean, enrolled_user_id, enrolled_started_datetime, enrolled_started_saying, enrolled_percentage_done, enrolled_has_completed_exam, enrolled_exam_total_questions, enrolled_exam_correct_answers, enrolled_exam_correct_percentage, enrolled_completed_datetime, enrolled_completed_saying FROM $t_courses_users_enrolled WHERE enrolled_course_id=$get_current_course_id AND enrolled_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_enrolled_id, $get_current_enrolled_course_id, $get_current_enrolled_course_title, $get_current_enrolled_course_title_clean, $get_current_enrolled_user_id, $get_current_enrolled_started_datetime, $get_current_enrolled_started_saying, $get_current_enrolled_percentage_done, $get_current_enrolled_has_completed_exam, $get_current_enrolled_exam_total_questions, $get_current_enrolled_exam_correct_answers, $get_current_enrolled_exam_correct_percentage, $get_current_enrolled_completed_datetime, $get_current_enrolled_completed_saying) = $row;
			if($get_current_enrolled_id == ""){
				// Insert
				
				$datetime = date("Y-m-d H:i:s");
				$date_saying = date("j. M Y");
				$inp_course_title_mysql = quote_smart($link, $get_current_course_title);
				$inp_course_title_clean_mysql = quote_smart($link, $get_current_course_title_clean);

				mysqli_query($link, "INSERT INTO $t_courses_users_enrolled 
				(enrolled_id, enrolled_course_id, enrolled_course_title, enrolled_course_title_clean, enrolled_user_id, enrolled_started_datetime, enrolled_started_saying, enrolled_percentage_done, enrolled_has_completed_exam) 
				VALUES 
				(NULL, $get_current_course_id, $inp_course_title_mysql, $inp_course_title_clean_mysql, $my_user_id_mysql, '$datetime', '$date_saying', '0', '0')")
				or die(mysqli_error($link));

				// Endrolled in course
				$inp_course_users_enrolled_count = $get_current_course_users_enrolled_count + 1;
				$result = mysqli_query($link, "UPDATE $t_courses_index SET course_users_enrolled_count=$inp_course_users_enrolled_count WHERE course_id=$get_current_course_id");


				echo"
				<div class=\"info\"><p>$l_you_are_now_enrolled_in_this_course</p></div>
				";
			}

		}


		echo"

		<!-- About course -->
			<div style=\"height:20px;\"></div>
			<div class=\"course_overview\">
				<a href=\"index.php?l=$l\"><img src=\"_gfx/$get_current_course_icon_96\" alt=\"$get_current_course_icon_96\" class=\"course_icon\" /></a>
		
				<div class=\"course_text\">
					<h1 style=\"margin: 0px 0px 0px 0px;padding: 0px 0px 0px 0px;\">$get_current_course_title</h1> 
			
					$get_current_course_description
				</div>
				<div class=\"clear\"></div>
			</div>
			<div style=\"height:20px;\"></div>
		<!-- //About course -->

		";
		if(!(isset($_SESSION['user_id']))){
			
			echo"
			<form method=\"POST\" action=\"$root/users/login.php?action=check&amp;process=1&amp;l=en&amp;referer=$get_current_course_title_clean\" enctype=\"multipart/form-data\" name=\"nameform\">
	
			<div class=\"course_quick_login\">
				<div class=\"quick_login_headerspace\">
					<p>
					<a href=\"$root/users/login.php?l=$l\">$l_login_to_our_site</a>
					</p>
				</div>
			
				<div class=\"course_quick_login_username\">
					<p>
					$l_email:
					<input type=\"text\" name=\"inp_email\" size=\"10\" value=\""; if(isset($inp_email)){ echo"$inp_email"; } echo"\" />
					</p>
				</div>
				<div class=\"course_quick_login_password\">
					<p>
					$l_password: <input type=\"password\" name=\"inp_password\" size=\"10\" value=\""; if(isset($inp_password)){ echo"$inp_password"; } echo"\" />
					</p>
				</div>
				<div class=\"course_quick_login_autologin\">
					<p>
					$l_remember_me  <input style=\"margin-top: -3px;\" type=\"checkbox\" name=\"inp_remember\" "; if(isset($inp_remember)){ if($inp_remember == "on"){ echo" checked=\"checked\""; } } else{ echo" checked=\"checked\""; } echo" />
					</p>
				</div>
				<div class=\"course_quick_login_submit\">
					<input type=\"submit\" value=\"$l_login\" class=\"btn_default\" />
				</div>
				<div class=\"course_quick_login_forgot_password\">
					<p>
					<a href=\"$root/users/create_free_account.php?l=$l\">$l_create_a_free_account</a>
					|
					<a href=\"$root/users/forgot_password.php?l=$l\">$l_i_forgot_my_password</a>
					</p>
				</div>

			</div>
			</form>



			";
		}



		// Get modules
		$total_modules = 0;
		$total_lessons = 0;
		$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean FROM $t_courses_modules WHERE module_course_id=$get_current_course_id ORDER BY module_number ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean) = $row;


			// Did I complete this module?
			if(isset($my_user_id)){
				$query_m = "SELECT module_read_id FROM $t_courses_modules_read WHERE read_course_id=$get_current_course_id AND read_module_id=$get_module_id AND read_user_id=$my_user_id_mysql";
				$result_m = mysqli_query($link, $query_m);
				$row_m = mysqli_fetch_row($result_m);
				list($get_module_read_id) = $row_m;
			
			}
			else{
				$get_module_read_id = "";
			}

			echo"
			<div class=\"course_module\">
				<a href=\"$root/$get_current_course_title_clean/$get_module_title_clean/index.php?course_id=$get_current_course_id&amp;main_category_id=$get_current_course_main_category_id&amp;module_id=$get_module_id&amp;l=$l\">
				<div class=\"module_left\">
					<p><span>$l_module $get_module_number:</span> $get_module_title</p>
				</div>
				
				<div class=\"module_right\">
                        		";
					if($get_module_read_id == ""){
						echo"<p><img src=\"$root/courses/_images/icons/checked_grey.png\" alt=\"checked_grey.png\" /></p>";
					}
					else{
						echo"<p><img src=\"$root/courses/_images/icons/checked_color.png\" alt=\"checked_color.png\" /></p>";
					}
					echo"
				</div>
				</a>
			</div>
			";

			// Get lessons
			$query_lessons = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean FROM $t_courses_lessons WHERE lesson_module_id=$get_module_id ORDER BY lesson_number ASC";
			$result_lessons = mysqli_query($link, $query_lessons);
			while($row_lessons = mysqli_fetch_row($result_lessons)) {
				list($get_lesson_id, $get_lesson_number, $get_lesson_title, $get_lesson_title_clean) = $row_lessons;


				// Did I complete this lesson?
				if(isset($my_user_id)){
					$query_m = "SELECT lesson_read_id FROM $t_courses_lessons_read WHERE read_course_id=$get_current_course_id AND read_lesson_id=$get_lesson_id AND read_user_id=$my_user_id_mysql";
					$result_m = mysqli_query($link, $query_m);
					$row_m = mysqli_fetch_row($result_m);
					list($get_lesson_read_id) = $row_m;
				}
				else{
					$get_lesson_read_id = "";
				}


				echo"
				<div class=\"course_content\">
                        		<a href=\"$root/$get_current_course_title_clean/$get_module_title_clean/$get_lesson_title_clean.php?course_id=$get_current_course_id&amp;main_category_id=$get_current_course_main_category_id&amp;module_id=$get_module_id&amp;lesson_id=$get_lesson_id&amp;l=$l\">
					<div class=\"course_content_left\">
                               			 <span class=\"course_content_number\">$get_lesson_number</span> <span class=\"course_content_title\">$get_lesson_title</span>
					</div>
					<div class=\"course_content_right\">
                        			";
						if($get_lesson_read_id == ""){
							echo"<p><img src=\"$root/courses/_images/icons/checked_grey.png\" alt=\"checked_grey.png\" /></p>";
						}
						else{
							echo"<p><img src=\"$root/courses/_images/icons/checked_color.png\" alt=\"checked_color.png\" /></p>";
						}
						echo"
					</div>
					</a>
				</div>
				";

			} // while content


		} // while modules


		// Exam
		// Find exam
		$query = "SELECT exam_id, exam_course_id, exam_course_title, exam_language, exam_total_questions, exam_total_points, exam_points_needed_to_pass FROM $t_courses_exams_index WHERE exam_course_id=$get_current_course_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_exam_id, $get_current_exam_course_id, $get_current_exam_course_title, $get_current_exam_language, $get_current_exam_total_questions, $get_current_exam_total_points, $get_current_exam_points_needed_to_pass) = $row;
		if($get_current_exam_id != ""){

			echo"
			<div class=\"course_module\">
				<a href=\"exam.php?course_id=$get_current_course_id&amp;main_category_id=$get_current_course_main_category_id&amp;l=$l\">
				<div class=\"module_left\">
					<p><span></span> $l_exam</span></p>
				</div>
				
				<div class=\"module_right\">
                        		";
					// Did I complete the exam?
					if(isset($my_user_id)){
						$query = "SELECT try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM $t_courses_exams_user_tries WHERE try_course_id=$get_current_course_id AND try_user_id=$my_user_id_mysql AND try_passed=1";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_try_id, $get_try_course_id, $get_try_course_title, $get_try_exam_id, $get_try_user_id, $get_try_started_datetime, $get_try_started_time, $get_try_started_saying, $get_try_is_closed, $get_try_ended_datetime, $get_try_ended_time, $get_try_ended_saying, $get_try_finished_saying, $get_try_time_used, $get_try_percentage, $get_try_passed) = $row;

						if($get_try_id == ""){
							echo"<p><img src=\"$root/courses/_images/icons/checked_grey.png\" alt=\"checked_grey.png\" /></p>";
						}
						else{
							echo"<p><img src=\"$root/courses/_images/icons/checked_color.png\" alt=\"checked_color.png\" /></p>";
						}
					}
					else{
						echo"<p><img src=\"$root/courses/_images/icons/checked_grey.png\" alt=\"checked_grey.png\" /></p>";
					}
					echo"
				</div>
				</a>
			</div>
			";
		} // exam exists

	} // action == ""
} // course found

?>