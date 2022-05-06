<?php
/**
*
* File: courses/_includes/exam_certificate.php
* Version 2.0.0
* Date 22:38 03.05.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


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




// Translation
include("$root/_admin/_translations/site/$l/courses/ts_exam_certificate.php");

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
	include("$root/_webdesign/footer.php");
}
else{

	/*- Header ----------------------------------------------------------- */
	$website_title = "$get_current_course_title $l_certificate";
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

			$query = "SELECT try_id, try_course_id, try_course_title, try_exam_id, try_user_id, try_started_datetime, try_started_time, try_started_saying, try_is_closed, try_ended_datetime, try_ended_time, try_ended_saying, try_finished_saying, try_time_used, try_percentage, try_passed FROM $t_courses_exams_user_tries WHERE try_course_id=$get_current_course_id AND try_user_id=$my_user_id_mysql AND try_passed=1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_try_id, $get_current_try_course_id, $get_current_try_course_title, $get_current_try_exam_id, $get_current_try_user_id, $get_current_try_started_datetime, $get_current_try_started_time, $get_current_try_started_saying, $get_current_try_is_closed, $get_current_try_ended_datetime, $get_current_try_ended_time, $get_current_try_ended_saying, $get_current_try_finished_saying, $get_current_try_time_used, $get_current_try_percentage, $get_current_try_passed) = $row;
			if($get_current_try_id != ""){
				
				// Course contents
				$get_current_course_contents = str_replace("<br />", ", ", $get_current_course_contents);

				echo"<!DOCTYPE html>
<html lang=\"en\">
<head>
	<title>$get_current_course_title $l_certificate</title>
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>

	<link rel=\"stylesheet\" href=\"$root/courses/_exam_certificate/exam_certificate.css\" type=\"text/css\" />
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UFT-8\" />
</head>
<body onload=\"window.print()\">
	<div class=\"header\">
		<img src=\"$root/courses/_exam_certificate/_gfx/header.png\" alt=\"header.png\" />
		<!-- <p>V</p> -->
	</div>
	<div class=\"main\">
		<h1>$get_current_course_title $l_certificate</h1>

		<p class=\"this_certificate_is_awarded_to\">$l_this_certificate_is_awarded_to</p>
		<span class=\"name\">$get_my_profile_first_name $get_my_profile_middle_name $get_my_profile_last_name</span>

		
		<p class=\"course_contents\">$get_current_course_contents</p>
	</div>
	<div class=\"footer_wrapper\">
		<div class=\"footer_inner\">
			
			<span class=\"signature_name\">$configWebsiteWebmasterSav<br /></span>
			<p class=\"signature_date\">
			$get_current_try_finished_saying<br />
			$configWebsiteTitleSav</p>

			<img src=\"$root/courses/_exam_certificate/_gfx/footer.png\" alt=\"footer.png\" />
		</div>
	</div>
</body>
</html>";
			} // exam passed

		} // logged in
	} // exam found
} // Course found
/*- Footer ----------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>