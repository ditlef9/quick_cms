<?php
/**
*
* File: _admin/_inc/comments/open_main_category.php
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
/*- Tables ---------------------------------------------------------------------------- */
$t_courses_liquidbase 	 = $mysqlPrefixSav . "courses_liquidbase";


$t_courses_title_translations	 = $mysqlPrefixSav . "courses_title_translations";
$t_courses_index		 = $mysqlPrefixSav . "courses_index";
$t_courses_users_enrolled 	 = $mysqlPrefixSav . "courses_users_enrolled";

$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
$t_courses_categories_sub 	 = $mysqlPrefixSav . "courses_categories_sub";
$t_courses_modules		 = $mysqlPrefixSav . "courses_modules";
$t_courses_modules_read		 = $mysqlPrefixSav . "courses_modules_read";

$t_courses_modules_contents 	 = $mysqlPrefixSav . "courses_modules_contents";
$t_courses_modules_contents_read = $mysqlPrefixSav . "courses_modules_contents_read";
$t_courses_modules_contents_comments	= $mysqlPrefixSav . "courses_modules_contents_comments";

$t_courses_modules_quizzes_index  	= $mysqlPrefixSav . "courses_modules_quizzes_index";
$t_courses_modules_quizzes_qa 		= $mysqlPrefixSav . "courses_modules_quizzes_qa";
$t_courses_modules_quizzes_user_records	= $mysqlPrefixSav . "courses_modules_quizzes_user_records";

$t_courses_exams_index  		= $mysqlPrefixSav . "courses_exams_index";
$t_courses_exams_qa			= $mysqlPrefixSav . "courses_exams_qa";
$t_courses_exams_user_tries		= $mysqlPrefixSav . "courses_exams_user_tries";
$t_courses_exams_user_tries_qa		= $mysqlPrefixSav . "courses_exams_user_tries_qa";

/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;

if(isset($_GET['where'])){
	$where = $_GET['where'];
	$where = output_html($where);
}
else {
	$where = "comment_approved != '-1'";
}

if(isset($_GET['main_category_id'])){
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
$main_category_id_mysql = quote_smart($link, $main_category_id);


if($action == ""){
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{
		echo"
		<h1>$get_current_main_category_title</h1>
				

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
			<a href=\"index.php?open=courses&amp;page=open_main_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Menu -->
			<p>
			<a href=\"index.php?open=courses&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New course</a>
			<a href=\"index.php?open=courses&amp;page=categories_main_open&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Sub categories</a>
			</p>
		<!-- //Menu -->

		<!-- Left and right -->
			<table>
			 <tr>
			  <td style=\"vertical-align:top;padding-right: 20px;\">
				<!-- Left: Categories -->
					<table class=\"hor-zebra\">
					 <tbody>
					  <tr>
					   <td>
						<p style=\"padding:4px 0px 4px 0px;margin:0;\">";
						$editor_language_mysql = quote_smart($link, $editor_language);
						$query = "SELECT main_category_id, main_category_title FROM $t_courses_categories_main WHERE main_category_language=$editor_language_mysql ORDER BY main_category_title ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_main_category_id, $get_main_category_title) = $row;
							echo"
							<a href=\"index.php?open=$open&amp;page=open_main_category&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language&amp;l=$l\""; if($get_main_category_id == "$get_current_main_category_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_main_category_title</a><br />
							";

							if($get_main_category_id == "$get_current_main_category_id"){
								$query_sub = "SELECT sub_category_id, sub_category_title FROM $t_courses_categories_sub WHERE sub_category_main_category_id=$get_main_category_id ORDER BY sub_category_title ASC";
								$result_sub = mysqli_query($link, $query_sub);
								while($row_sub = mysqli_fetch_row($result_sub)) {
									list($get_sub_category_id, $get_sub_category_title) = $row_sub;

									echo"
									&nbsp; &nbsp; <a href=\"index.php?open=$open&amp;page=open_sub_category&amp;sub_category_id=$get_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_sub_category_title</a><br />
									";
								} // while sub categories
							}
						} // while main categories
						echo"
						</p>
					   </td>
					  </tr>
					 </tbody>
					</table>
				<!-- //Left: Categories -->
			  </td>
			  <td style=\"vertical-align:top;\">
				<!-- Right: Courses -->";

				$editor_language_mysql = quote_smart($link, $editor_language);
				$query = "SELECT course_id, course_title, course_title_clean, course_front_page_intro, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_image_file, course_image_thumb, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count FROM $t_courses_index WHERE course_main_category_id=$get_current_main_category_id AND course_language=$editor_language_mysql ORDER BY course_users_enrolled_count ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_course_id, $get_course_title, $get_course_title_clean, $get_course_front_page_intro, $get_course_main_category_id, $get_course_main_category_title, $get_course_sub_category_id, $get_course_sub_category_title, $get_course_image_file, $get_course_image_thumb, $get_course_modules_count, $get_course_lessons_count, $get_course_quizzes_count, $get_course_users_enrolled_count) = $row;
					echo"
					<table style=\"width: 100%;\">
					  <tr>";
					if(file_exists("../$get_course_title_clean/_gfx/$get_course_image_thumb")){
					echo"
					   <td style=\"width: 210px;vertical-align:top;padding-right: 15px;\">
						<p>
						<a href=\"index.php?open=$open&amp;page=courses_open&amp;course_id=$get_course_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"../$get_course_title_clean/_gfx/$get_course_image_thumb\" alt=\"$get_course_image_thumb\" class=\"courses_img_thumb\" /></a>
						</p>
					   </td>
					";
					}
					echo"
					   <td style=\"vertical-align:top;padding-right: 10px;\">

						<p class=\"courses_course_title\">
						<a href=\"index.php?open=$open&amp;page=courses_open&amp;course_id=$get_course_id&amp;editor_language=$editor_language&amp;l=$l\">$get_course_title</a>
						</p>

						<p class=\"courses_course_numbers\">
						$get_course_modules_count modules
						&middot; 
						$get_course_lessons_count lessons 
						</p>
					   </td>
		 			  <td style=\"vertical-align:top;padding-right: 10px;\">
						<div class=\"courses_users_enrolled\">
							<p class=\"courses_users_enrolled_number\">$get_course_users_enrolled_count</p>
							<p class=\"courses_users_enrolled_text\">users enrolled</p>
						</div>
					   </td>
					 </tr>
					</table>
					<div class=\"courses_after\"></div>
					";

				}
				echo"
			<!-- //Right: Courses -->
		  </td>
		 </tr>
		</table>
		<!-- //Left and right -->
		";
	} // action == ""
} // main category found
?>