<?php
/**
*
* File: courses/_includes/exam.php
* Version 2.0.0
* Date 21:23 26.09.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Translation
include("$root/_admin/_translations/site/$l/courses/ts_exam.php");

// Title
$l_mysql = quote_smart($link, $l);
$query = "SELECT courses_title_translation_id, courses_title_translation_title FROM $t_courses_title_translations WHERE courses_title_translation_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_courses_title_translation_id, $get_current_courses_title_translation_title) = $row;


// Course
$course_title_mysql = quote_smart($link, $courseTitleSav);
$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_title=$course_title_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;


if($get_current_course_id == ""){
	/*- Header ----------------------------------------------------------- */
	$website_title = "$get_current_courses_title_translation_title - Server error 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"<p>Server error 404.</p>";
}
else{

	/*- Header ----------------------------------------------------------- */
	$website_title = "$get_current_courses_title_translation_title - $get_current_course_title - $l_exam";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	/*- Look for exam ----------------------------------------------------- */
	$query = "SELECT exam_id, exam_course_id, exam_course_title, exam_language, exam_total_questions, exam_total_points, exam_points_needed_to_pass FROM $t_courses_exams_index WHERE exam_course_id=$get_current_course_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_exam_id, $get_current_exam_course_id, $get_current_exam_course_title, $get_current_exam_language, $get_current_exam_total_questions, $get_current_exam_total_points, $get_current_exam_points_needed_to_pass) = $row;
	if($get_current_exam_id == ""){
		echo"Exam not found";
	}
	else{
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			// Get user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			$query = "SELECT user_id, user_name, user_alias, user_language, user_gender, user_dob, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_gender, $get_my_user_dob, $get_my_user_rank) = $row;

			$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_profile_id, $get_my_profile_user_id, $get_my_profile_first_name, $get_my_profile_middle_name, $get_my_profile_last_name, $get_my_profile_address_line_a, $get_my_profile_address_line_b, $get_my_profile_zip, $get_my_profile_city, $get_my_profile_country, $get_my_profile_phone, $get_my_profile_work, $get_my_profile_university, $get_my_profile_high_school, $get_my_profile_languages, $get_my_profile_website, $get_my_profile_interested_in, $get_my_profile_relationship, $get_my_profile_about, $get_my_profile_newsletter) = $row;


			if($action == ""){
				$question_needed_to_pass = (90*$get_current_exam_total_questions)/100;
				$question_needed_to_pass = round($question_needed_to_pass, 0);
				echo"
				<h1>$get_current_course_title $l_exam</h1>

				<!-- Where am I ?? -->
					<p><b>$l_you_are_here</b><br />
					<a href=\"$root/$get_current_course_title_clean/index.php?l=$l\">$get_current_course_title</a>
					&gt;
					<a href=\"exam.php?course_id=$get_current_course_id&amp;l=$l\">$l_exam</a>
					</p>
				<!-- //Where am I ?? -->

				";
				// name empty?
				if($get_my_profile_first_name == ""){
					echo"
					<h2>$l_what_is_your_name</h2>
					<p>$l_we_need_your_name_to_put_on_the_certificate $l_please_enter_it_in_the_form </p>
					<form method=\"post\" action=\"exam.php?course_id=$get_current_course_id&amp;action=set_my_name&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
						
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_profile_first_name\"]').focus();
					});
					</script>
					<p>
					$l_first_name:<br />
					<input type=\"text\" name=\"inp_profile_first_name\" size=\"40\" value=\"$get_my_profile_first_name\" /><br />
					</p>

					<p>
					$l_middle_name:<br />
					<input type=\"text\" name=\"inp_profile_middle_name\" size=\"40\" value=\"$get_my_profile_middle_name\" /><br />
					</p>

					<p>
					$l_last_name:<br />
					<input type=\"text\" name=\"inp_profile_last_name\" size=\"40\" value=\"$get_my_profile_last_name\" /><br />
					</p>

					<p><input type=\"submit\" value=\"$l_save_my_name\" class=\"btn\" /></p>
					</form>	
					";
				}
				else{
					echo"
					<h2>$l_exam</h2>
					<p>
					$l_the_exam_consists_of <b>$get_current_exam_total_questions $l_questions_lowercase</b>.
					$l_you_will_need_nity_percentage_correct_to_pass_the_exam ($question_needed_to_pass $l_quesions_correct_lowercase)
					</p>

					<p>
					$l_questions_with_checkboxes_may_have_more_than_one_correct_answer 
					$l_if_you_pick_wrong_answer_on_questions_with_checkboxes_then_you_will_get_negative_points
					</p>

					<p>
					$l_you_can_take_the_exam_as_many_times_as_you_want 
					</p>

					<!-- Start Exam -->
					<p><a href=\"exam.php?course_id=$get_current_course_id&amp;action=start_exam&amp;l=$l\" class=\"btn_default\">$l_start_exam</a></p>
					";

					echo"
					<!-- Diploma -->";
					$query = "SELECT try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM $t_courses_exams_user_tries WHERE try_course_id=$get_current_course_id AND try_user_id=$my_user_id_mysql AND try_passed=1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_try_id, $get_current_try_course_id, $get_current_try_course_title, $get_current_try_exam_id, $get_current_try_user_id, $get_current_try_started_datetime, $get_current_try_started_time, $get_current_try_started_saying, $get_current_try_is_closed, $get_current_try_ended_datetime, $get_current_try_ended_time, $get_current_try_ended_saying, $get_current_try_finished_saying, $get_current_try_time_used, $get_current_try_percentage, $get_current_try_passed) = $row;
					if($get_current_try_id != ""){
						echo"
						<p>
						<a href=\"exam_certificate.php?course_id=$get_current_course_id&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_print_certificate</a></p>
						";
					}

					echo"
					<!-- //Diploma -->

					<!-- Tries -->
						<h2>$l_your_tries</h2>
						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>$l_try</span>
						   </th>
						   <th scope=\"col\">
							<span>$l_started</span>
						   </th>
						   <th scope=\"col\">
							<span>$l_ended</span>
						   </th>
						   <th scope=\"col\">
							<span>$l_percentage</span>
						   </th>
						   <th scope=\"col\">
							<span>$l_passed</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";

						$query = "SELECT  try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM $t_courses_exams_user_tries WHERE try_course_id=$get_current_course_id AND try_user_id=$my_user_id_mysql ORDER BY try_id DESC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_try_id, $get_try_course_id, $get_try_course_title, $get_try_exam_id, $get_try_user_id, $get_try_started_datetime, $get_try_started_time, $get_try_started_saying, $get_try_is_closed, $get_try_ended_datetime, $get_try_ended_time, $get_try_ended_saying, $get_try_finished_saying, $get_try_time_used, $get_try_percentage, $get_try_passed) = $row;

							if(isset($odd) && $odd == false){
								$odd = true;
							}
							else{
								$odd = false;
							}
		
							echo"
							 <tr>
							  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
								<span>$get_try_id</span>
							   </td>
							   <td"; if($odd == true){ echo" class=\"odd\""; } echo">
								<span>$get_try_started_saying</span>
							   </td>
							   <td"; if($odd == true){ echo" class=\"odd\""; } echo">
								<span>$get_try_ended_saying</span>
							   </td>
							   <td"; if($odd == true){ echo" class=\"odd\""; } echo">
								<span>$get_try_percentage</span>
							   </td>
							   <td"; if($odd == true){ echo" class=\"odd\""; } echo">
								";
								if($get_try_passed == "1"){
									echo"<span style=\"color:green\">$l_passed</span>";
								}
								else{
									echo"<span style=\"color:red\">$l_failed</span>";
								}
								echo"
							   </td>
							  </tr>
							";
						}
						echo"
						 </tbody>
						</table>
						<div style=\"height: 10px;\"></div>
					<!-- //Tries -->
					
					";
				} // name present
			} // action == ""
			elseif($action == "set_my_name"){
				
				$inp_profile_first_name = $_POST['inp_profile_first_name'];
				$inp_profile_first_name = output_html($inp_profile_first_name);
				$inp_profile_first_name = ucwords($inp_profile_first_name);
				$inp_profile_first_name_mysql = quote_smart($link, $inp_profile_first_name);

				$inp_profile_middle_name = $_POST['inp_profile_middle_name'];
				$inp_profile_middle_name = output_html($inp_profile_middle_name);
				$inp_profile_middle_name = ucwords($inp_profile_middle_name);
				$inp_profile_middle_name_mysql = quote_smart($link, $inp_profile_middle_name);
		
				$inp_profile_last_name = $_POST['inp_profile_last_name'];
				$inp_profile_last_name = output_html($inp_profile_last_name);
				$inp_profile_last_name = ucwords($inp_profile_last_name);
				$inp_profile_last_name_mysql = quote_smart($link, $inp_profile_last_name);

				$result = mysqli_query($link, "UPDATE $t_users_profile SET profile_first_name=$inp_profile_first_name_mysql, profile_middle_name=$inp_profile_middle_name_mysql, profile_last_name=$inp_profile_last_name_mysql WHERE profile_user_id=$my_user_id_mysql");
					
				$url = "exam.php?course_id=$get_current_course_id&l=$l&ft=success&fm=changes_saved"; 
				header("Location: $url");
				exit;
			}
			elseif($action == "start_exam"){
				// Insert this try
				$inp_course_title_mysql = quote_smart($link, $get_current_course_title);
				$datetime = date("Y-m-d H:i:s");
				$time = time();
				$datetime_saying = date("j M Y H:i");
				mysqli_query($link, "INSERT INTO $t_courses_exams_user_tries
				(try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_passed) 
				VALUES 
				(NULL, $get_current_course_id, $inp_course_title_mysql, $get_current_exam_id, $my_user_id_mysql, '$datetime', '$time', '$datetime_saying', 0, 0)")
				or die(mysqli_error($link));

				// Fetch this try
				$query = "SELECT try_id FROM $t_courses_exams_user_tries WHERE try_exam_id=$get_current_exam_id AND try_user_id=$my_user_id_mysql AND try_started_time='$time'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_try_id) = $row;

				echo"
				<h1><img src=\"$root/courses/_images/loading_22.gif\" alt=\"loading_22.gif\" /> $l_starting_exam</h1>
			
				<p><a href=\"exam.php?course_id=$get_current_course_id&amp;action=exam_questions&amp;question_number=1&amp;try_id=$get_current_try_id&amp;l=$l\" class=\"btn_default\">$l_start</a></p>
				<meta http-equiv=\"refresh\" content=\"1; url=exam.php?course_id=$get_current_course_id&amp;action=exam_questions&amp;question_number=1&amp;try_id=$get_current_try_id&amp;l=$l\">
				";
			} // start exam
			elseif($action == "exam_questions" && isset($_GET['question_number']) && isset($_GET['try_id'])){
				// Get try
				$try_id = $_GET['try_id'];
				$try_id = strip_tags(stripslashes($try_id));
				$try_id_mysql = quote_smart($link, $try_id);
				
				$question_number = $_GET['question_number'];
				$question_number = strip_tags(stripslashes($question_number));
				$question_number_mysql = quote_smart($link, $question_number);

				
				// Fetch this try
				$query = "SELECT try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM $t_courses_exams_user_tries WHERE try_id=$try_id_mysql AND try_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_try_id, $get_current_try_course_id, $get_current_try_course_title, $get_current_try_exam_id, $get_current_try_user_id, $get_current_try_started_datetime, $get_current_try_started_time, $get_current_try_started_saying, $get_current_try_is_closed, $get_current_try_ended_datetime, $get_current_try_ended_time, $get_current_try_ended_saying, $get_current_try_finished_saying, $get_current_try_time_used, $get_current_try_percentage, $get_current_try_passed) = $row;
				if($get_current_try_id == ""){
					echo"<p>Try not found</p>";
				}
				else{
					// Find question
					$query = "SELECT qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, qa_explanation FROM $t_courses_exams_qa WHERE qa_course_id=$get_current_course_id AND qa_exam_id=$get_current_exam_id AND qa_question_number=$question_number_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_qa_id, $get_current_qa_course_id, $get_current_qa_course_title, $get_current_qa_exam_id, $get_current_qa_question_number, $get_current_qa_question, $get_current_qa_text, $get_current_qa_type, $get_current_qa_alt_a, $get_current_qa_alt_b, $get_current_qa_alt_c, $get_current_qa_alt_d, $get_current_qa_alt_e, $get_current_qa_alt_f, $get_current_qa_alt_g, $get_current_qa_alt_h, $get_current_qa_alt_i, $get_current_qa_alt_j, $get_current_qa_alt_k, $get_current_qa_alt_l, $get_current_qa_alt_m, $get_current_qa_alt_n, $get_current_qa_correct_alternatives, $get_current_qa_points, $get_current_qa_hint, $get_current_qa_explanation) = $row;
					if($get_current_qa_id == ""){
						echo"<p>Question not found.</p>";
					}
					else{
						// Did I take this question before?
						$query = "SELECT try_qa_id, try_qa_course_id, try_qa_course_title, try_qa_exam_id, try_qa_try_id, try_qa_user_id, try_qa_qa_id, try_qa_alt_a, try_qa_alt_b, try_qa_alt_c, try_qa_alt_d, try_qa_alt_e, try_qa_alt_f, try_qa_alt_g, try_qa_alt_h, try_qa_alt_i, try_qa_alt_j, try_qa_alt_k, try_qa_alt_l, try_qa_alt_m, try_qa_alt_n, try_qa_points_awarded, try_qa_is_correct FROM $t_courses_exams_user_tries_qa WHERE try_qa_course_id=$get_current_course_id AND try_qa_exam_id=$get_current_exam_id AND try_qa_try_id=$get_current_try_id AND try_qa_user_id=$my_user_id_mysql AND try_qa_qa_id=$get_current_qa_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_try_qa_id, $get_current_try_qa_course_id, $get_current_try_qa_course_title, $get_current_try_qa_exam_id, $get_current_try_qa_try_id, $get_current_try_qa_user_id, $get_current_try_qa_qa_id, $get_current_try_qa_alt_a, $get_current_try_qa_alt_b, $get_current_try_qa_alt_c, $get_current_try_qa_alt_d, $get_current_try_qa_alt_e, $get_current_try_qa_alt_f, $get_current_try_qa_alt_g, $get_current_try_qa_alt_h, $get_current_try_qa_alt_i, $get_current_try_qa_alt_j, $get_current_try_qa_alt_k, $get_current_try_qa_alt_l, $get_current_try_qa_alt_m, $get_current_try_qa_alt_n, $get_current_try_qa_points_awarded, $get_current_try_qa_is_correct) = $row;
						

						
						echo"
						<h1>$get_current_course_title $l_exam</h1>
						

						<!-- Questions browser -->
							<div style=\"margin: 10px 0px 10px 0px;\">";
							for($x=1;$x<$get_current_exam_total_questions+1;$x++){
								if($x == "$get_current_qa_question_number"){
									echo"
									<a href=\"exam.php?course_id=$get_current_course_id&amp;action=exam_questions&amp;question_number=$x&amp;try_id=$get_current_try_id&amp;l=$l\" class=\"btn\">$x</a>
									";
								}
								else{
									echo"
									<a href=\"exam.php?course_id=$get_current_course_id&amp;action=exam_questions&amp;question_number=$x&amp;try_id=$get_current_try_id&amp;l=$l\" class=\"btn_default\">$x</a>
									";
								}
							}
							echo"
								<a href=\"exam.php?course_id=$get_current_course_id&amp;action=exam_questions_confirm_complete&amp;try_id=$get_current_try_id&amp;l=$l\" class=\"btn_default\">$l_submit</a>
							</div>
						<!-- //Questions browser -->

						<h2>$get_current_qa_question</h2>
						$get_current_qa_text
						
						
						<form method=\"post\" action=\"exam.php?course_id=$get_current_course_id&amp;action=answer_exam_question&amp;question_number=$get_current_qa_question_number&amp;try_id=$get_current_try_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
						<p>
						";
						// All options
							if($get_current_qa_alt_a != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_a";
								}
								echo"\" value=\"a\" "; if($get_current_try_qa_alt_a == "checked" OR $get_current_try_qa_alt_a == "a"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_a<br />\n";
							}
							if($get_current_qa_alt_b != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_b";
								}
								echo"\" value=\"b\" "; if($get_current_try_qa_alt_b == "checked" OR $get_current_try_qa_alt_b == "b"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_b<br />\n";
							}
							if($get_current_qa_alt_c != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_c";
								}
								echo"\" value=\"c\" "; if($get_current_try_qa_alt_c == "checked" OR $get_current_try_qa_alt_c == "c"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_c<br />\n";
							}
							if($get_current_qa_alt_d != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_d";
								}
								echo"\" value=\"d\" "; if($get_current_try_qa_alt_d == "checked" OR $get_current_try_qa_alt_d == "d"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_d<br />\n";
							}
							if($get_current_qa_alt_e != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_e";
								}
								echo"\" value=\"e\" "; if($get_current_try_qa_alt_e == "checked" OR $get_current_try_qa_alt_e == "e"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_e<br />\n";
							}
							if($get_current_qa_alt_f != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_f";
								}
								echo"\" value=\"f\" "; if($get_current_try_qa_alt_f == "checked" OR $get_current_try_qa_alt_f == "f"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_f<br />\n";
							}
							if($get_current_qa_alt_g != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_g";
								}
								echo"\" value=\"g\" "; if($get_current_try_qa_alt_g == "checked" OR $get_current_try_qa_alt_g == "g"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_g<br />\n";
							}
							if($get_current_qa_alt_h != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_h";
								}
								echo"\" value=\"h\" "; if($get_current_try_qa_alt_h == "checked" OR $get_current_try_qa_alt_h == "h"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_h<br />\n";
							}
							if($get_current_qa_alt_i != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_i";
								}
								echo"\" value=\"i\" "; if($get_current_try_qa_alt_i == "checked" OR $get_current_try_qa_alt_i == "i"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_i<br />\n";
							}
							if($get_current_qa_alt_j != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_j";
								}
								echo"\" value=\"j\" "; if($get_current_try_qa_alt_j == "checked" OR $get_current_try_qa_alt_j == "j"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_j<br />\n";
							}
							if($get_current_qa_alt_k != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_k";
								}
								echo"\" value=\"k\" "; if($get_current_try_qa_alt_k == "checked" OR $get_current_try_qa_alt_k == "k"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_k<br />\n";
							}
							if($get_current_qa_alt_l != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_l";
								}
								echo"\" value=\"l\" "; if($get_current_try_qa_alt_l == "checked" OR $get_current_try_qa_alt_l == "l"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_l<br />\n";
							}
							if($get_current_qa_alt_m != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_m";
								}
								echo"\" value=\"m\" "; if($get_current_try_qa_alt_m == "checked" OR $get_current_try_qa_alt_m == "m"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_m<br />\n";
							}
							if($get_current_qa_alt_n != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_n";
								}
								echo"\" value=\"n\" "; if($get_current_try_qa_alt_n == "checked" OR $get_current_try_qa_alt_n == "n"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_n<br />\n";
							}
						echo"
						</p>
						<p>
						<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
						</p>
						</form>
						
						";
					} // question found
				} // try found
			} // actio == "exam_questions"
			elseif($action == "answer_exam_question" && isset($_GET['question_number']) && isset($_GET['try_id'])){
				// Get try
				$try_id = $_GET['try_id'];
				$try_id = strip_tags(stripslashes($try_id));
				$try_id_mysql = quote_smart($link, $try_id);
				
				$question_number = $_GET['question_number'];
				$question_number = strip_tags(stripslashes($question_number));
				$question_number_mysql = quote_smart($link, $question_number);

				
				// Fetch this try
				$query = "SELECT try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM $t_courses_exams_user_tries WHERE try_id=$try_id_mysql AND try_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_try_id, $get_current_try_course_id, $get_current_try_course_title, $get_current_try_exam_id, $get_current_try_user_id, $get_current_try_started_datetime, $get_current_try_started_time, $get_current_try_started_saying, $get_current_try_is_closed, $get_current_try_ended_datetime, $get_current_try_ended_time, $get_current_try_ended_saying, $get_current_try_finished_saying, $get_current_try_time_used, $get_current_try_percentage, $get_current_try_passed) = $row;
				if($get_current_try_id == ""){
					echo"<p>Try not found</p>";
				}
				else{
					// Find question
					$query = "SELECT qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, qa_explanation FROM $t_courses_exams_qa WHERE qa_course_id=$get_current_course_id AND qa_exam_id=$get_current_exam_id AND qa_question_number=$question_number_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_qa_id, $get_current_qa_course_id, $get_current_qa_course_title, $get_current_qa_exam_id, $get_current_qa_question_number, $get_current_qa_question, $get_current_qa_text, $get_current_qa_type, $get_current_qa_alt_a, $get_current_qa_alt_b, $get_current_qa_alt_c, $get_current_qa_alt_d, $get_current_qa_alt_e, $get_current_qa_alt_f, $get_current_qa_alt_g, $get_current_qa_alt_h, $get_current_qa_alt_i, $get_current_qa_alt_j, $get_current_qa_alt_k, $get_current_qa_alt_l, $get_current_qa_alt_m, $get_current_qa_alt_n, $get_current_qa_correct_alternatives, $get_current_qa_points, $get_current_qa_hint, $get_current_qa_explanation) = $row;
					if($get_current_qa_id == ""){
						echo"<p>Question not found.</p>";
					}
					else{
						// Did I take this question before?
						$query = "SELECT try_qa_id, try_qa_course_id, try_qa_course_title, try_qa_exam_id, try_qa_try_id, try_qa_user_id, try_qa_qa_id, try_qa_alt_a, try_qa_alt_b, try_qa_alt_c, try_qa_alt_d, try_qa_alt_e, try_qa_alt_f, try_qa_alt_g, try_qa_alt_h, try_qa_alt_i, try_qa_alt_j, try_qa_alt_k, try_qa_alt_l, try_qa_alt_m, try_qa_alt_n, try_qa_points_awarded, try_qa_is_correct FROM $t_courses_exams_user_tries_qa WHERE try_qa_course_id=$get_current_course_id AND try_qa_exam_id=$get_current_exam_id AND try_qa_try_id=$get_current_try_id AND try_qa_user_id=$my_user_id_mysql AND try_qa_qa_id=$get_current_qa_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_try_qa_id, $get_current_try_qa_course_id, $get_current_try_qa_course_title, $get_current_try_qa_exam_id, $get_current_try_qa_try_id, $get_current_try_qa_user_id, $get_current_try_qa_qa_id, $get_current_try_qa_alt_a, $get_current_try_qa_alt_b, $get_current_try_qa_alt_c, $get_current_try_qa_alt_d, $get_current_try_qa_alt_e, $get_current_try_qa_alt_f, $get_current_try_qa_alt_g, $get_current_try_qa_alt_h, $get_current_try_qa_alt_i, $get_current_try_qa_alt_j, $get_current_try_qa_alt_k, $get_current_try_qa_alt_l, $get_current_try_qa_alt_m, $get_current_try_qa_alt_n, $get_current_try_qa_points_awarded, $get_current_try_qa_is_correct) = $row;
						
						// If not then insert and get and ID
						if($get_current_try_qa_id == ""){
							$inp_course_title_mysql = quote_smart($link, $get_current_course_title);
							mysqli_query($link, "INSERT INTO $t_courses_exams_user_tries_qa 
							(try_qa_id, try_qa_course_id, try_qa_course_title, try_qa_exam_id, try_qa_try_id, try_qa_user_id, try_qa_qa_id) 
							VALUES 
							(NULL, $get_current_course_id, $inp_course_title_mysql, $get_current_exam_id, $get_current_try_id, $my_user_id_mysql, $get_current_qa_id)")
							or die(mysqli_error($link));

							$query = "SELECT try_qa_id, try_qa_course_id, try_qa_course_title, try_qa_exam_id, try_qa_try_id, try_qa_user_id, try_qa_qa_id, try_qa_alt_a, try_qa_alt_b, try_qa_alt_c, try_qa_alt_d, try_qa_alt_e, try_qa_alt_f, try_qa_alt_g, try_qa_alt_h, try_qa_alt_i, try_qa_alt_j, try_qa_alt_k, try_qa_alt_l, try_qa_alt_m, try_qa_alt_n, try_qa_points_awarded, try_qa_is_correct FROM $t_courses_exams_user_tries_qa WHERE try_qa_course_id=$get_current_course_id AND try_qa_exam_id=$get_current_exam_id AND try_qa_try_id=$get_current_try_id AND try_qa_user_id=$my_user_id_mysql AND try_qa_qa_id=$get_current_qa_id";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_try_qa_id, $get_current_try_qa_course_id, $get_current_try_qa_course_title, $get_current_try_qa_exam_id, $get_current_try_qa_try_id, $get_current_try_qa_user_id, $get_current_try_qa_qa_id, $get_current_try_qa_alt_a, $get_current_try_qa_alt_b, $get_current_try_qa_alt_c, $get_current_try_qa_alt_d, $get_current_try_qa_alt_e, $get_current_try_qa_alt_f, $get_current_try_qa_alt_g, $get_current_try_qa_alt_h, $get_current_try_qa_alt_i, $get_current_try_qa_alt_j, $get_current_try_qa_alt_k, $get_current_try_qa_alt_l, $get_current_try_qa_alt_m, $get_current_try_qa_alt_n, $get_current_try_qa_points_awarded, $get_current_try_qa_is_correct) = $row;
						


						}



						// All options
						$inp_try_qa_alt_a = "";
						$inp_try_qa_alt_b = "";
						$inp_try_qa_alt_c = "";
						$inp_try_qa_alt_d = "";
						$inp_try_qa_alt_e = "";
						$inp_try_qa_alt_f = "";
						$inp_try_qa_alt_g = "";
						$inp_try_qa_alt_h = "";
						$inp_try_qa_alt_i = "";
						$inp_try_qa_alt_j = "";
						$inp_try_qa_alt_k = "";
						$inp_try_qa_alt_l = "";
						$inp_try_qa_alt_m = "";
						$inp_try_qa_alt_n = "";

						$inp_try_qa_points_awarded = 0;
						$inp_try_qa_is_correct = 0;

						if($get_current_qa_type == "radio"){
							$inp_answer = $_POST['inp_alternative'];
							$inp_answer = output_html($inp_answer);
							if($inp_answer == "a"){
								$inp_try_qa_alt_a = "checked";
								if($get_current_qa_correct_alternatives == "a"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "b"){
								$inp_try_qa_alt_b = "checked";
								if($get_current_qa_correct_alternatives == "b"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "c"){
								$inp_try_qa_alt_c = "checked";
								if($get_current_qa_correct_alternatives == "c"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "d"){
								$inp_try_qa_alt_d = "checked";
								if($get_current_qa_correct_alternatives == "d"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "e"){
								$inp_try_qa_alt_e = "checked";
								if($get_current_qa_correct_alternatives == "e"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "f"){
								$inp_try_qa_alt_f = "checked";
								if($get_current_qa_correct_alternatives == "f"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "g"){
								$inp_try_qa_alt_g = "checked";
								if($get_current_qa_correct_alternatives == "g"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "h"){
								$inp_try_qa_alt_h = "checked";
								if($get_current_qa_correct_alternatives == "h"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "i"){
								$inp_try_qa_alt_i = "checked";
								if($get_current_qa_correct_alternatives == "i"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "j"){
								$inp_try_qa_alt_j = "checked";
								if($get_current_qa_correct_alternatives == "j"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "k"){
								$inp_try_qa_alt_k = "checked";
								if($get_current_qa_correct_alternatives == "k"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "l"){
								$inp_try_qa_alt_l = "checked";
								if($get_current_qa_correct_alternatives == "l"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "m"){
								$inp_try_qa_alt_m = "checked";
								if($get_current_qa_correct_alternatives == "m"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							elseif($inp_answer == "n"){
								$inp_try_qa_alt_n = "checked";
								if($get_current_qa_correct_alternatives == "n"){
									$inp_try_qa_points_awarded = $get_current_qa_points;
									$inp_try_qa_is_correct = 1;
								}
							}
							
							// Update
							$result = mysqli_query($link, "UPDATE $t_courses_exams_user_tries_qa SET 
											try_qa_alt_a='$inp_try_qa_alt_a',
											try_qa_alt_b='$inp_try_qa_alt_b',
											try_qa_alt_c='$inp_try_qa_alt_c',
											try_qa_alt_d='$inp_try_qa_alt_d',
											try_qa_alt_e='$inp_try_qa_alt_e',
											try_qa_alt_f='$inp_try_qa_alt_f',
											try_qa_alt_g='$inp_try_qa_alt_g',
											try_qa_alt_h='$inp_try_qa_alt_h',
											try_qa_alt_i='$inp_try_qa_alt_i',
											try_qa_alt_j='$inp_try_qa_alt_j',
											try_qa_alt_k='$inp_try_qa_alt_k',
											try_qa_alt_l='$inp_try_qa_alt_l',
											try_qa_alt_m='$inp_try_qa_alt_m',
											try_qa_alt_n='$inp_try_qa_alt_n',

											try_qa_points_awarded=$inp_try_qa_points_awarded,
											try_qa_is_correct=$inp_try_qa_is_correct
											 WHERE try_qa_id=$get_current_try_qa_id") or die(mysqli_error($link));


						} // radio
						elseif($get_current_qa_type == "checkbox"){
							

							// Calculate how many points each alternative gives
							$number_of_alternatives = 0;
							if($get_current_qa_alt_a != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_b != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_c != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_d != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_e != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_f != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_g != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_h != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_i != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_j != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_k != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_l != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_m != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}
							if($get_current_qa_alt_n != ""){
								$number_of_alternatives = $number_of_alternatives+1;
							}

							$correct_alternatives_array = explode(",", $get_current_qa_correct_alternatives);
							$points_per_alternative = $get_current_qa_points/sizeof($correct_alternatives_array);
							
							// Calculate points
							if($get_current_qa_alt_a != ""){
								if(isset($_POST['inp_alternative_a'])){
									$inp_try_qa_alt_a = $_POST['inp_alternative_a'];
								}
								else{
									$inp_try_qa_alt_a = "";
								}
								$inp_try_qa_alt_a = output_html($inp_try_qa_alt_a);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "a"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_a == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_a != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_a != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}

							}
							if($get_current_qa_alt_b != ""){
								if(isset($_POST['inp_alternative_b'])){
									$inp_try_qa_alt_b = $_POST['inp_alternative_b'];
								}
								else{
									$inp_try_qa_alt_b = "";
								}
								$inp_try_qa_alt_b = output_html($inp_try_qa_alt_b);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "b"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_b == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_b != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_b != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_c != ""){
								if(isset($_POST['inp_alternative_c'])){
									$inp_try_qa_alt_c = $_POST['inp_alternative_c'];
								}
								else{
									$inp_try_qa_alt_c = "";
								}
								$inp_try_qa_alt_c = output_html($inp_try_qa_alt_c);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "c"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_c == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_c != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_c != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_d != ""){
								if(isset($_POST['inp_alternative_d'])){
									$inp_try_qa_alt_d = $_POST['inp_alternative_d'];
								}
								else{
									$inp_try_qa_alt_d = "";
								}
								$inp_try_qa_alt_d = output_html($inp_try_qa_alt_d);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "d"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_d == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_d != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_d != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_e != ""){
								if(isset($_POST['inp_alternative_e'])){
									$inp_try_qa_alt_e = $_POST['inp_alternative_e'];
								}
								else{
									$inp_try_qa_alt_e = "";
								}
								$inp_try_qa_alt_e = output_html($inp_try_qa_alt_e);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "e"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_e == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_e != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_e != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_f != ""){
								if(isset($_POST['inp_alternative_f'])){
									$inp_try_qa_alt_f = $_POST['inp_alternative_f'];
								}
								else{
									$inp_try_qa_alt_f = "";
								}
								$inp_try_qa_alt_f = output_html($inp_try_qa_alt_f);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "f"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_f == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_f != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_f != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_g != ""){
								if(isset($_POST['inp_alternative_g'])){
									$inp_try_qa_alt_g = $_POST['inp_alternative_g'];
								}
								else{
									$inp_try_qa_alt_g = "";
								}
								$inp_try_qa_alt_g = output_html($inp_try_qa_alt_g);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "g"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_g == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_g != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_g != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_h != ""){
								if(isset($_POST['inp_alternative_h'])){
									$inp_try_qa_alt_h = $_POST['inp_alternative_h'];
								}
								else{
									$inp_try_qa_alt_h = "";
								}
								$inp_try_qa_alt_h = output_html($inp_try_qa_alt_h);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "h"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_h == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_h != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_h != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_i != ""){
								if(isset($_POST['inp_alternative_i'])){
									$inp_try_qa_alt_i = $_POST['inp_alternative_i'];
								}
								else{
									$inp_try_qa_alt_i = "";
								}
								$inp_try_qa_alt_i = output_html($inp_try_qa_alt_i);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "i"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_i == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_i != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_i != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_j != ""){
								if(isset($_POST['inp_alternative_j'])){
									$inp_try_qa_alt_j = $_POST['inp_alternative_j'];
								}
								else{
									$inp_try_qa_alt_j = "";
								}
								$inp_try_qa_alt_j = output_html($inp_try_qa_alt_j);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "j"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_j == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_j != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_j != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_k != ""){
								if(isset($_POST['inp_alternative_k'])){
									$inp_try_qa_alt_k = $_POST['inp_alternative_k'];
								}
								else{
									$inp_try_qa_alt_k = "";
								}
								$inp_try_qa_alt_k = output_html($inp_try_qa_alt_k);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "k"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_k == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_k != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_k != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_l != ""){
								if(isset($_POST['inp_alternative_l'])){
									$inp_try_qa_alt_l = $_POST['inp_alternative_l'];
								}
								else{
									$inp_try_qa_alt_l = "";
								}
								$inp_try_qa_alt_l = output_html($inp_try_qa_alt_l);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "l"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_l == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_l != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_l != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_m != ""){
								if(isset($_POST['inp_alternative_m'])){
									$inp_try_qa_alt_m = $_POST['inp_alternative_m'];
								}
								else{
									$inp_try_qa_alt_m = "";
								}
								$inp_try_qa_alt_m = output_html($inp_try_qa_alt_m);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "m"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_m == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_m != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_m != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}
							if($get_current_qa_alt_n != ""){
								if(isset($_POST['inp_alternative_n'])){
									$inp_try_qa_alt_n = $_POST['inp_alternative_n'];
								}
								else{
									$inp_try_qa_alt_n = "";
								}
								$inp_try_qa_alt_n = output_html($inp_try_qa_alt_n);

								// Is this correct?
								$this_is_correct_anwer = "false";
								for($x=0;$x<sizeof($correct_alternatives_array);$x++){
									$temp = trim($correct_alternatives_array[$x]);
									if($temp == "n"){
										$this_is_correct_anwer = "true";
									}
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_n == ""){
									// I did not check this, but it was true. No point awarded
								}
								if($this_is_correct_anwer == "false" && $inp_try_qa_alt_n != ""){
									// I checked this, but it is false. Negative point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded-$points_per_alternative;
								}
								if($this_is_correct_anwer == "true" && $inp_try_qa_alt_n != ""){
									// I checked this, and it was correct. Positive point awarded
									$inp_try_qa_points_awarded = $inp_try_qa_points_awarded+$points_per_alternative;
								}
							}


							$inp_try_qa_alt_a_mysql  = quote_smart($link, $inp_try_qa_alt_a);
							$inp_try_qa_alt_b_mysql  = quote_smart($link, $inp_try_qa_alt_b);
							$inp_try_qa_alt_c_mysql  = quote_smart($link, $inp_try_qa_alt_c);
							$inp_try_qa_alt_d_mysql  = quote_smart($link, $inp_try_qa_alt_d);
							$inp_try_qa_alt_e_mysql  = quote_smart($link, $inp_try_qa_alt_e);
							$inp_try_qa_alt_f_mysql  = quote_smart($link, $inp_try_qa_alt_f);
							$inp_try_qa_alt_g_mysql  = quote_smart($link, $inp_try_qa_alt_g);
							$inp_try_qa_alt_h_mysql  = quote_smart($link, $inp_try_qa_alt_h);
							$inp_try_qa_alt_i_mysql  = quote_smart($link, $inp_try_qa_alt_i);
							$inp_try_qa_alt_j_mysql  = quote_smart($link, $inp_try_qa_alt_j);
							$inp_try_qa_alt_k_mysql  = quote_smart($link, $inp_try_qa_alt_k);
							$inp_try_qa_alt_l_mysql  = quote_smart($link, $inp_try_qa_alt_l);
							$inp_try_qa_alt_m_mysql  = quote_smart($link, $inp_try_qa_alt_m);
							$inp_try_qa_alt_n_mysql  = quote_smart($link, $inp_try_qa_alt_n);

							// Did I finish this completly?
							if($inp_try_qa_points_awarded == "$get_current_qa_points"){
								$inp_try_qa_is_correct = 1;
							}
							else{
								$inp_try_qa_is_correct = 0;
							}

							// Update
							$result = mysqli_query($link, "UPDATE $t_courses_exams_user_tries_qa SET 
											try_qa_alt_a=$inp_try_qa_alt_a_mysql,
											try_qa_alt_b=$inp_try_qa_alt_b_mysql, 
											try_qa_alt_c=$inp_try_qa_alt_c_mysql,
											try_qa_alt_d=$inp_try_qa_alt_d_mysql, 
											try_qa_alt_e=$inp_try_qa_alt_e_mysql,
											try_qa_alt_f=$inp_try_qa_alt_f_mysql,
											try_qa_alt_g=$inp_try_qa_alt_g_mysql,
											try_qa_alt_h=$inp_try_qa_alt_h_mysql,
											try_qa_alt_i=$inp_try_qa_alt_i_mysql,
											try_qa_alt_j=$inp_try_qa_alt_j_mysql,
											try_qa_alt_k=$inp_try_qa_alt_k_mysql,
											try_qa_alt_l=$inp_try_qa_alt_l_mysql,
											try_qa_alt_m=$inp_try_qa_alt_m_mysql,
											try_qa_alt_n=$inp_try_qa_alt_n_mysql,

											try_qa_points_awarded=$inp_try_qa_points_awarded,
											try_qa_is_correct=$inp_try_qa_is_correct
											 WHERE try_qa_id=$get_current_try_qa_id") or die(mysqli_error($link));


						} // Checkbox

						// Transfer
						$next_question_number = $question_number+1;
						if($next_question_number > $get_current_exam_total_questions){
							$url = "exam.php?course_id=$get_current_course_id&action=exam_questions_confirm_complete&try_id=$try_id&l=$l&ft=success&fm=answer_saved";
						}
						else{
							$url = "exam.php?course_id=$get_current_course_id&action=exam_questions&question_number=$next_question_number&try_id=$try_id&l=$l&ft=success&fm=answer_saved";
						}
						header("Location: $url");
						exit;


					} // question found
				} // try found

			} // action== "answer_exam_question"
			elseif($action == "exam_questions_confirm_complete" && isset($_GET['try_id'])){
				// Get try
				$try_id = $_GET['try_id'];
				$try_id = strip_tags(stripslashes($try_id));
				$try_id_mysql = quote_smart($link, $try_id);
				
				// Fetch this try
				$query = "SELECT try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM $t_courses_exams_user_tries WHERE try_id=$try_id_mysql AND try_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_try_id, $get_current_try_course_id, $get_current_try_course_title, $get_current_try_exam_id, $get_current_try_user_id, $get_current_try_started_datetime, $get_current_try_started_time, $get_current_try_started_saying, $get_current_try_is_closed, $get_current_try_ended_datetime, $get_current_try_ended_time, $get_current_try_ended_saying, $get_current_try_finished_saying, $get_current_try_time_used, $get_current_try_percentage, $get_current_try_passed) = $row;
				if($get_current_try_id == ""){
					echo"<p>Try not found</p>";
				}
				else{


						
					echo"
					<h1>$get_current_course_title $l_exam</h1>
						

					<!-- Questions browser -->
							<div style=\"margin: 10px 0px 10px 0px;\">";
							for($x=1;$x<$get_current_exam_total_questions+1;$x++){
								echo"
								<a href=\"exam.php?course_id=$get_current_course_id&amp;action=exam_questions&amp;question_number=$x&amp;try_id=$get_current_try_id&amp;l=$l\" class=\"btn_default\">$x</a>
								";
							}
							echo"
								<a href=\"exam.php?course_id=$get_current_course_id&amp;action=exam_questions_confirm_complete&amp;try_id=$get_current_try_id&amp;l=$l\" class=\"btn\">$l_submit</a>
							</div>
					<!-- //Questions browser -->

					<h2>$l_confirm_submit_of_exam</h2>
						
					<p>
					$l_do_you_want_to_submit_the_exam_now
					</p>

					<p>
					<a href=\"exam.php?course_id=$get_current_course_id&amp;action=exam_questions_confirm_complete_confirm_and_show_result&amp;try_id=$get_current_try_id&amp;l=$l\" class=\"btn\">$l_submit_exam</a>
					</p>
						
						
						
						
					";
				} // try found
			} // actio == "exam_questions_confirm_complete"
			elseif($action == "exam_questions_confirm_complete_confirm_and_show_result" && isset($_GET['try_id'])){
				// Get try
				$try_id = $_GET['try_id'];
				$try_id = strip_tags(stripslashes($try_id));
				$try_id_mysql = quote_smart($link, $try_id);
				
				
				// Fetch this try
				$query = "SELECT try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM $t_courses_exams_user_tries WHERE try_id=$try_id_mysql AND try_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_try_id, $get_current_try_course_id, $get_current_try_course_title, $get_current_try_exam_id, $get_current_try_user_id, $get_current_try_started_datetime, $get_current_try_started_time, $get_current_try_started_saying, $get_current_try_is_closed, $get_current_try_ended_datetime, $get_current_try_ended_time, $get_current_try_ended_saying, $get_current_try_finished_saying, $get_current_try_time_used, $get_current_try_percentage, $get_current_try_passed) = $row;
				if($get_current_try_id == ""){
					echo"<p>Try not found</p>";
				}
				else{


						
					echo"
					<h1>$get_current_course_title $l_exam</h1>
						

					<!-- Questions -->
						<form>";
						$inp_try_points = 0;

						// Loop trough questions
						$query = "SELECT qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, qa_explanation FROM $t_courses_exams_qa WHERE qa_course_id=$get_current_course_id AND qa_exam_id=$get_current_exam_id";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_current_qa_id, $get_current_qa_course_id, $get_current_qa_course_title, $get_current_qa_exam_id, $get_current_qa_question_number, $get_current_qa_question, $get_current_qa_text, $get_current_qa_type, $get_current_qa_alt_a, $get_current_qa_alt_b, $get_current_qa_alt_c, $get_current_qa_alt_d, $get_current_qa_alt_e, $get_current_qa_alt_f, $get_current_qa_alt_g, $get_current_qa_alt_h, $get_current_qa_alt_i, $get_current_qa_alt_j, $get_current_qa_alt_k, $get_current_qa_alt_l, $get_current_qa_alt_m, $get_current_qa_alt_n, $get_current_qa_correct_alternatives, $get_current_qa_points, $get_current_qa_hint, $get_current_qa_explanation) = $row;

							// Find my answer
							$query_answer = "SELECT try_qa_id, try_qa_course_id, try_qa_course_title, try_qa_exam_id, try_qa_try_id, try_qa_user_id, try_qa_qa_id, try_qa_alt_a, try_qa_alt_b, try_qa_alt_c, try_qa_alt_d, try_qa_alt_e, try_qa_alt_f, try_qa_alt_g, try_qa_alt_h, try_qa_alt_i, try_qa_alt_j, try_qa_alt_k, try_qa_alt_l, try_qa_alt_m, try_qa_alt_n, try_qa_points_awarded, try_qa_is_correct FROM $t_courses_exams_user_tries_qa WHERE try_qa_course_id=$get_current_course_id AND try_qa_exam_id=$get_current_exam_id AND try_qa_try_id=$get_current_try_id AND try_qa_user_id=$my_user_id_mysql AND try_qa_qa_id=$get_current_qa_id";
							$result_answer = mysqli_query($link, $query_answer);
							$row_answer = mysqli_fetch_row($result_answer);
							list($get_current_try_qa_id, $get_current_try_qa_course_id, $get_current_try_qa_course_title, $get_current_try_qa_exam_id, $get_current_try_qa_try_id, $get_current_try_qa_user_id, $get_current_try_qa_qa_id, $get_current_try_qa_alt_a, $get_current_try_qa_alt_b, $get_current_try_qa_alt_c, $get_current_try_qa_alt_d, $get_current_try_qa_alt_e, $get_current_try_qa_alt_f, $get_current_try_qa_alt_g, $get_current_try_qa_alt_h, $get_current_try_qa_alt_i, $get_current_try_qa_alt_j, $get_current_try_qa_alt_k, $get_current_try_qa_alt_l, $get_current_try_qa_alt_m, $get_current_try_qa_alt_n, $get_current_try_qa_points_awarded, $get_current_try_qa_is_correct) = $row_answer;

							$inp_try_points = $inp_try_points+$get_current_try_qa_points_awarded;
							echo"
							<div style=\"border: #ccc 1px solid;margin-bottom: 10px;padding: 4px;\">
								<table style=\"width: 100%;\">
								 <tr>
								  <td>
									<span><b>$get_current_qa_question</b></span>
								  </td>
								  <td style=\"text-align: right;\">
									<span>$get_current_try_qa_points_awarded / $get_current_qa_points</span>
								  </td>
								 </tr>
								</table>
								";
							if($get_current_qa_alt_a != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_a";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"a\" "; if($get_current_try_qa_alt_a == "checked" OR $get_current_try_qa_alt_a == "a"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_a<br />\n";
							}
							if($get_current_qa_alt_b != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_b";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"b\" "; if($get_current_try_qa_alt_b == "checked" OR $get_current_try_qa_alt_b == "b"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_b<br />\n";
							}
							if($get_current_qa_alt_c != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_c";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"c\" "; if($get_current_try_qa_alt_c == "checked" OR $get_current_try_qa_alt_c == "c"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_c<br />\n";
							}
							if($get_current_qa_alt_d != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_d";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"d\" "; if($get_current_try_qa_alt_d == "checked" OR $get_current_try_qa_alt_d == "d"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_d<br />\n";
							}
							if($get_current_qa_alt_e != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_e";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"e\" "; if($get_current_try_qa_alt_e == "checked" OR $get_current_try_qa_alt_e == "e"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_e<br />\n";
							}
							if($get_current_qa_alt_f != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_f";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"f\" "; if($get_current_try_qa_alt_f == "checked" OR $get_current_try_qa_alt_f == "f"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_f<br />\n";
							}
							if($get_current_qa_alt_g != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_g";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"g\" "; if($get_current_try_qa_alt_g == "checked" OR $get_current_try_qa_alt_g == "g"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_g<br />\n";
							}
							if($get_current_qa_alt_h != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_h";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"h\" "; if($get_current_try_qa_alt_h == "checked" OR $get_current_try_qa_alt_h == "h"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_h<br />\n";
							}
							if($get_current_qa_alt_i != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_i";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"i\" "; if($get_current_try_qa_alt_i == "checked" OR $get_current_try_qa_alt_i == "i"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_i<br />\n";
							}
							if($get_current_qa_alt_j != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_j";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"j\" "; if($get_current_try_qa_alt_j == "checked" OR $get_current_try_qa_alt_j == "j"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_j<br />\n";
							}
							if($get_current_qa_alt_k != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_k";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"k\" "; if($get_current_try_qa_alt_k == "checked" OR $get_current_try_qa_alt_k == "k"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_k<br />\n";
							}
							if($get_current_qa_alt_l != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_l";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"l\" "; if($get_current_try_qa_alt_l == "checked" OR $get_current_try_qa_alt_l == "l"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_l<br />\n";
							}
							if($get_current_qa_alt_m != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_m";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"m\" "; if($get_current_try_qa_alt_m == "checked" OR $get_current_try_qa_alt_m == "m"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_m<br />\n";
							}
							if($get_current_qa_alt_n != ""){
								echo"						";
								echo"<input type=\"$get_current_qa_type\" name=\"inp_alternative";
								if($get_current_qa_type == "checkbox"){
									echo"_n";
								}
								echo"$get_current_qa_id";
								echo"\" value=\"n\" "; if($get_current_try_qa_alt_n == "checked" OR $get_current_try_qa_alt_n == "n"){ echo" checked=\"checked\""; } echo" /> $get_current_qa_alt_n<br />\n";
							}
								echo"
							</div>
							";

						} // while questions
						echo"</form>
					<!-- //Questions -->

					<!-- Result -->
						<hr />
						<h2>$l_your_result</h2>
						<table>
						 <tr>
						  <td style=\"padding-right: 10px;\">
							<span><b>$l_points_needed:</b></span>
						  </td>
						  <td>
							<span>$get_current_exam_points_needed_to_pass (90 %)</span>
						  </td>
						 </tr>
						 <tr>
						  <td style=\"padding-right: 10px;\">
							<span><b>$l_points_awared:</b></span>
						  </td>
						  <td>
							<span>$inp_try_points";

							$inp_try_percentage = round(($inp_try_points/$get_current_exam_points_needed_to_pass)*100, 0);

							echo" ($inp_try_percentage %)</span>
						  </td>
						 </tr>
						 <tr>
						  <td style=\"padding-right: 10px;\">
							<span><b>Status:</b></span>
						  </td>
						  <td>
							";
							$inp_try_passed = 0;
							if($inp_try_points >= $get_current_exam_points_needed_to_pass){
								echo"<span style=\"color:green\">$l_passed</span>";
								$inp_try_passed = 1;

								echo"
								<p>
								<a href=\"exam_certificate.php?course_id=$get_current_course_id&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_print_certificate</a>
								</p>
								";
							}
							else{
								echo"<span style=\"color:red\">$l_failed</span>";
							}
							echo"
						  </td>
						 </tr>
						</table>
						<hr />
					<!-- Result -->
						
					<!-- Insert into MySQL -->
						";
						if($get_current_try_is_closed != "1"){
							$inp_ended_datetime = date("Y-m-d H:i:s");
							$inp_ended_time = time();
							$ended_saying = date("j M Y H:i");

							$try_finished_saying = date("j M Y");

							$time_used_seconds = $inp_ended_time-$get_current_try_started_time;
							$time_used_hour_minutes = gmdate("H:i", $time_used_seconds);

							// Update
							$result = mysqli_query($link, "UPDATE $t_courses_exams_user_tries SET 
											try_is_closed='1',
											try_ended_datetime='$inp_ended_datetime',
											try_ended_time='$inp_ended_time',
											try_ended_saying='$ended_saying',
											try_finished_saying='$try_finished_saying',
											try_time_used='$time_used_hour_minutes',
											try_percentage='$inp_try_percentage',
											try_passed='$inp_try_passed'
											 WHERE try_id=$get_current_try_id") or die(mysqli_error($link));


						}
						echo"
					<!-- //Insert into MySQL -->
						
					<p>
					<a href=\"exam.php?course_id=$get_current_course_id\" class=\"btn_default\">$l_exam_home</a>
					</p>
						
					";
				} // try found
			} // actio == "exam_questions_confirm_complete"
		} // logged in
		else{
			echo"
			<h1><img src=\"_images/loading_22.gif\" alt=\"loading_22.gif\" /> $l_please_log_in</h1>
			

			<meta http-equiv=\"refresh\" content=\"1; url=$root/users/login.php?l=$l&amp;referer=$root/courses/exam.php?course_id=$get_current_course_id";echo"amp;action=start_exam\">
			";
		} // not logged in
	} // _exam found
} // Course found
/*- Footer ----------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>