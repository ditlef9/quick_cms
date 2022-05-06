<?php
/**
*
* File: _admin/_inc/comments/courses_open.php
* Version 
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

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

$query = "SELECT course_id, course_title, course_short_introduction, course_long_introduction, course_contents, course_language, course_dir_name, course_category_id, course_intro_video_embedded, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_created, course_updated FROM $t_courses_index WHERE course_id=$course_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_course_id, $get_current_course_title, $get_current_course_short_introduction, $get_current_course_long_introduction, $get_current_course_contents, $get_current_course_language, $get_current_course_dir_name, $get_current_course_category_id, $get_current_course_intro_video_embedded, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_created, $get_current_course_updated) = $row;

if($get_current_course_id == ""){
	echo"<p>Server error 404.</p>";
}
else{
	// Find category
	$query = "SELECT category_id, category_title, category_dir_name, category_description, category_language, category_created, category_updated FROM $t_courses_categories WHERE category_id=$get_current_course_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_category_id, $get_current_category_title, $get_current_category_dir_name, $get_current_category_description, $get_current_category_language, $get_current_category_created, $get_current_category_updated) = $row;


	if($action == ""){


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
			<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_category&amp;category_id=$get_current_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_category_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_course&amp;course_id=$get_current_course_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Menu -->
			<p>
			<a href=\"../$get_current_course_dir_name/index.php?action=look_for_changes_in_modules_and_content&amp;course=$get_current_course_dir_name&amp;l=$l\" class=\"btn_default\" target=\"_blank\">Scan for modules and lessons</a>
			</p>
		<!-- //Menu -->


		";
	} // action ==""
} // found
?>