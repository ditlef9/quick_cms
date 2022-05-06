<?php
/**
*
* File: _admin/_inc/comments/courses_write_to_file.php
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
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_icon_path, main_category_icon_16x16, main_category_icon_18x18, main_category_icon_24x24, main_category_icon_32x32, main_category_icon_36x36, main_category_icon_48x48, main_category_icon_96x96, main_category_icon_260x260, main_category_header_logo, main_category_webdesign, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=$get_current_course_main_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_icon_path, $get_current_main_category_icon_16x16, $get_current_main_category_icon_18x18, $get_current_main_category_icon_24x24, $get_current_main_category_icon_32x32, $get_current_main_category_icon_36x36, $get_current_main_category_icon_48x48, $get_current_main_category_icon_96x96, $get_current_main_category_icon_260x260, $get_current_main_category_header_logo, $get_current_main_category_webdesign, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_courses_categories_sub WHERE sub_category_id=$get_current_course_sub_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;



	if($action == ""){
		if($process == "1"){
			
			include("_inc/courses/courses_write_to_file_include.php");

			$url = "index.php?open=courses&page=courses_write_to_file&course_id=$get_current_course_id&editor_language=$editor_language&l=$l&ft=success&fm=data_saved_as_text_files";
			header("Location: $url");
			exit;
		} // process == 1
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
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Course navigation -->

		<!-- Files -->
			<p><b>Files:</b></p>
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
			 <tr>
			  <td style=\"padding-right: 6px;\">
				<span>exam.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_course_title_clean/exam.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_course_title_clean/exam.php"));
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
				<span>exam_certificate.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_course_title_clean/exam_certificate.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_course_title_clean/exam_certificate.php"));
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
				<span>index.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_course_title_clean/index.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_course_title_clean/index.php"));
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
				<span>navigation.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_course_title_clean/navigation.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_course_title_clean/navigation.php"));
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
			Do you want to write to file?
			</p>

			<p>
			<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Write to file</a>
			</p>
		<!-- //Actions -->

		";
	} // action == ""
} // found
?>