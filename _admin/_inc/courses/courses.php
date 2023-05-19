<?php
/**
*
* File: _admin/_inc/courses/courses.php
* Version 2
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
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


/*- Check if setup is run ------------------------------------------------------------- */
$liquidbase_exists = false;
$query = "SHOW TABLES";
$result = $mysqli->query($query);
if($result !== false) {
	if($result->num_rows > 0) {
		while($row = $result->fetch_row()) {
			if($row[0] == "$t_courses_liquidbase"){
				$liquidbase_exists = true;
				break;
			}
    	}
  	}
}
else echo "Error Unable to check tables " . $mysqli->error;

if($liquidbase_exists){
	echo"
	<h1>Courses</h1>
				

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
		<a href=\"index.php?open=courses&amp;page=courses&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
		</p>
	<!-- //Where am I? -->

	<!-- Menu + language selector -->
		<table>
		 <tr>
		  <td style=\"vertical-align:top;padding-right: 20px;\">
			<p>
			";
			// Navigation
			$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='courses/index.php'";
			$result = $mysqli->query($query);
			$row = $result->fetch_row();
			list($get_navigation_id) = $row;
			if($get_navigation_id == ""){
				echo"
				<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=courses&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
				";
			}
			echo"
			<a href=\"index.php?open=courses&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New course</a>
			
			<a href=\"index.php?open=courses&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Categories</a>
			
			<a href=\"index.php?open=courses&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">DB Backup</a>
			</p>
		  </td>
		  <td style=\"vertical-align:top;\">
			<p>
			";
			$found_editor_language = "";
			$query = "SELECT language_active_id, language_active_name, language_active_slug, language_active_native_name, language_active_iso_two, language_active_iso_three, language_active_iso_four, language_active_flag_path_16x16, language_active_flag_active_16x16, language_active_flag_inactive_16x16, language_active_flag_path_18x18, language_active_flag_active_18x18, language_active_flag_inactive_18x18, language_active_flag_path_24x24, language_active_flag_active_24x24, language_active_flag_inactive_24x24, language_active_flag_path_32x32, language_active_flag_active_32x32, language_active_flag_inactive_32x32, language_active_charset, language_active_default FROM $t_languages_active";
			$result = $mysqli->query($query);
			while($row = $result->fetch_row()) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_slug, $get_language_active_native_name, $get_language_active_iso_two, $get_language_active_iso_three, $get_language_active_iso_four, $get_language_active_flag_path_16x16, $get_language_active_flag_active_16x16, $get_language_active_flag_inactive_16x16, $get_language_active_flag_path_18x18, $get_language_active_flag_active_18x18, $get_language_active_flag_inactive_18x18, $get_language_active_flag_path_24x24, $get_language_active_flag_active_24x24, $get_language_active_flag_inactive_24x24, $get_language_active_flag_path_32x32, $get_language_active_flag_active_32x32, $get_language_active_flag_inactive_32x32, $get_language_active_charset, $get_language_active_default) = $row;
				echo"	<a href=\"index.php?open=$open&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"><img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_active_16x16\" alt=\"$get_language_active_flag_active_16x16\" /></a>\n";

				if($editor_language == "$get_language_active_iso_two"){
					$found_editor_language = "1";
				}
			}
			if($found_editor_language == ""){
				// Editor language not found
				if(isset($get_language_active_iso_two)){
					$editor_language = "$get_language_active_iso_two";
				}
				else{
					echo"<p>Editor language not found</p>";
				}
			}
			echo"
			</p>
		  </td>
		 </tr>
		</table>
	<!-- //Menu + language selector  -->

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
				$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title FROM $t_courses_categories_main WHERE main_category_language=?"); 
				$stmt->bind_param("s", $editor_language);
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_row()) {
					list($get_main_category_id, $get_main_category_title) = $row;
					echo"
					<a href=\"index.php?open=$open&amp;page=open_main_category&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_main_category_title</a><br />
					";
				}
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

			
			$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_front_page_intro, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_image_file, course_image_thumb, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count FROM $t_courses_index WHERE course_language=? ORDER BY course_users_enrolled_count ASC"); 
			$stmt->bind_param("s", $editor_language);
			$stmt->execute();
			$result = $stmt->get_result();
			while($row = $result->fetch_row()) {
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

						<p class=\"courses_sub_category\">
						<a href=\"index.php?open=$open&amp;page=courses_open&amp;course_id=$get_course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"courses_sub_category\">$get_course_main_category_title</a>
						&gt; <a href=\"index.php?open=$open&amp;page=courses_open&amp;course_id=$get_course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"courses_sub_category\">$get_course_sub_category_title</a>
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
}
else{
	// Setup not runned
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
}

?>