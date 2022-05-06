<?php
/**
*
* File: courses/_includes/content_after_content.php
* Version 2.0.0
* Date 22:38 03.05.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Course content --------------------------------------------------------------------------- */
// Usage:
// include("$root/courses/_includes/content_after_content.php");

/*- Tables ------------------------------------------------------------------------------ */
include("$root/courses/_tables_courses.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/courses/ts_content_after_content.php");


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['course_id'])) {
	$course_id = $_GET['course_id'];
	$course_id = strip_tags(stripslashes($course_id));
}
else{
	$course_id = "";
}
if(isset($_GET['module_id'])) {
	$module_id = $_GET['module_id'];
	$module_id = strip_tags(stripslashes($module_id));
}
else{
	$module_id = "";
}
if(isset($_GET['lesson_id'])) {
	$lesson_id = $_GET['lesson_id'];
	$lesson_id = strip_tags(stripslashes($lesson_id));
}
else{
	$lesson_id = "";
}
if(isset($_GET['course_action'])) {
	$course_action = $_GET['course_action'];
	$course_action = strip_tags(stripslashes($course_action));
}
else{
	$course_action = "";
}

if($lesson_id != ""){
	// Search for content
	$course_id_mysql = quote_smart($link, $course_id);
	$module_id_mysql = quote_smart($link, $module_id);
	$lesson_id_mysql = quote_smart($link, $lesson_id);
	$query = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id=$lesson_id_mysql AND lesson_course_id=$course_id_mysql AND lesson_module_id=$module_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;

	if($get_current_lesson_id != ""){
		// Last read
		$datetime = date("Y-m-d H:i:s");
		$date_formatted = date("j M Y");
		$result = mysqli_query($link, "UPDATE $t_courses_lessons SET lesson_last_read_datetime='$datetime', lesson_last_read_date_formatted='$date_formatted' WHERE lesson_id=$get_current_lesson_id");

		// Visits
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		
		$ipblock_array = explode("\n", $get_current_lesson_read_times_ipblock);
		$size = sizeof($ipblock_array);
		$i_have_visited_before = "false";
		for($x=0;$x<$size;$x++){
			if($ipblock_array[$x] == "$my_ip"){
				$i_have_visited_before = "true";
			}
		}
		if($i_have_visited_before == "false"){
			$inp_read_times = $get_current_lesson_read_times+1;
			
			if($get_current_lesson_read_times_ipblock == ""){
				$inp_read_times_ipblock = "$my_ip";
			}
			else{
				$inp_read_times_ipblock = "$my_ip\n" . substr($get_current_lesson_read_times_ipblock, 0, 400);
			}
			$inp_read_times_ipblock_mysql = quote_smart($link, $inp_read_times_ipblock);

			$result = mysqli_query($link, "UPDATE $t_courses_lessons SET lesson_read_times=$inp_read_times, lesson_read_times_ipblock=$inp_read_times_ipblock_mysql WHERE lesson_id=$get_current_lesson_id") or die(mysqli_error($link));


		}


		// Read status
		if(isset($_SESSION['user_id'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			
			// Check if I have read it
			$query_m = "SELECT lesson_read_id FROM $t_courses_lessons_read WHERE read_course_id=$get_current_lesson_course_id AND read_lesson_id=$get_current_lesson_id AND read_user_id=$my_user_id_mysql";
			$result_m = mysqli_query($link, $query_m);
			$row_m = mysqli_fetch_row($result_m);
			list($get_lesson_read_id) = $row_m;

			if($get_lesson_read_id == ""){
				$inp_course_title_mysql = quote_smart($link, $get_current_lesson_course_title);
				$inp_module_title_mysql = quote_smart($link, $get_current_lesson_module_title);
				$inp_lesson_title_mysql = quote_smart($link, $get_current_lesson_title);

				mysqli_query($link, "INSERT INTO $t_courses_lessons_read 
				(lesson_read_id, read_course_id, read_course_title, read_module_id, read_module_title, read_lesson_id, read_lesson_title, read_user_id) 
				VALUES 
				(NULL, $get_current_lesson_course_id, $inp_course_title_mysql, $get_current_lesson_module_id, $inp_module_title_mysql, $get_current_lesson_id, $inp_lesson_title_mysql, $my_user_id_mysql)");



				// Give point
				$query = "SELECT user_id, user_name, user_alias, user_points FROM $t_users WHERE user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_points) = $row;
				$inp_user_points = $get_my_user_points+1;
				$result = mysqli_query($link, "UPDATE $t_users SET user_points=$inp_user_points WHERE user_id=$get_my_user_id");

				echo"
				<div class=\"info\" style=\"margin: 10px 0px 10px 0px;\">$l_congratulations_youve_finished_this_content_and_earned_a_extra_point<br />
				$l_points: $get_my_user_points
				</div>
				";

			}
		} // read

		// Comments
		echo"
		
		<!-- Comments -->
			<a id=\"comments\"></a>

			<h2>$l_comments</h2>


			<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- Write comment -->";
				if(isset($_SESSION['user_id'])){
					echo"
					<p>
					<a href=\"$root/courses/new_comment_to_lesson.php?course_id=$get_current_lesson_course_id&amp;module_id=$get_current_lesson_module_id&amp;lesson_id=$get_current_lesson_id&amp;l=$l\" class=\"btn_default\">$l_write_a_comment</a>	
					</p>
					";
				}
				else{
					echo"
					<p>
					<a href=\"$root/users/login.php?l=$l&amp;referer=$root/courses/new_comment_to_lesson.php?course_id=$get_current_lesson_course_id&amp;module_id=$get_current_lesson_module_id&amp;lesson_id=$get_current_lesson_id&amp;l=$\" class=\"btn_default\">$l_write_a_comment</a>	
					</p>
					";
				}
				echo"
			<!-- //Write comment -->

			<!-- View comments -->
			";

				// me
				if(isset($_SESSION['user_id'])){
					$my_user_id = $_SESSION['user_id'];
					$my_user_id = output_html($my_user_id);
					$my_user_id_mysql = quote_smart($link, $my_user_id);
					$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
				}


				$query_groups = "SELECT comment_id, comment_course_id, comment_course_title, comment_module_id, comment_module_title, comment_lesson_id, comment_lesson_title, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment FROM $t_courses_lessons_comments WHERE comment_course_id=$get_current_lesson_course_id AND comment_module_id=$get_current_lesson_module_id AND comment_lesson_id=$get_current_lesson_id ORDER BY comment_id ASC";
				$result_groups = mysqli_query($link, $query_groups);
				while($row_groups = mysqli_fetch_row($result_groups)) {
					list($get_comment_id, $get_comment_course_id, $get_comment_course_title, $get_comment_module_id, $get_comment_module_title, $get_comment_lesson_id, $get_comment_lesson_title, $get_comment_language, $get_comment_approved, $get_comment_datetime, $get_comment_time, $get_comment_date_print, $get_comment_user_id, $get_comment_user_alias, $get_comment_user_image_path, $get_comment_user_image_file, $get_comment_user_ip, $get_comment_user_hostname, $get_comment_user_agent, $get_comment_title, $get_comment_text, $get_comment_rating, $get_comment_helpful_clicks, $get_comment_useless_clicks, $get_comment_marked_as_spam, $get_comment_spam_checked, $get_comment_spam_checked_comment) = $row_groups;
		
					echo"
					<a id=\"comment$get_comment_id\"></a>
					<div class=\"clear\" style=\"height:14px;\"></div>

					<div class=\"comment_item\">
					<table style=\"width: 100%;\">
					 <tr>
					  <td style=\"width: 80px;vertical-align:top;\">
						<!-- Image -->
							<p style=\"padding: 10px 0px 10px 0px;margin:0;\">
							<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\">";
							if($get_comment_user_image_file == "" OR !(file_exists("$root/$get_comment_user_image_path/$get_comment_user_image_file"))){ 
								echo"<img src=\"$root/comments/_gfx/avatar_blank_65.png\" alt=\"avatar_blank_65.png\" class=\"comment_avatar\" />";
							} 
							else{ 
								$inp_new_x = 65; // 950
								$inp_new_y = 65; // 640
								$thumb_full_path = "$root/$get_comment_user_image_path/user_" . $get_comment_user_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
								if(!(file_exists("$thumb_full_path"))){
									resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_comment_user_id/$get_comment_user_image_file", "$thumb_full_path");
								}

								echo"	<img src=\"$thumb_full_path\" alt=\"$get_comment_user_image_file\" class=\"comment_view_avatar\" />"; 
							} 
							echo"</a>
							</p>
							<!-- //Image -->
					  </td>
					  <td style=\"vertical-align:top;\">

						<!-- menu -->
						<table style=\"width: 100%;\">
						 <tr>
						  <td style=\"text-align: right;\">


							<!-- Menu -->
							";
							if(isset($my_user_id)){
								if($get_comment_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
									echo"
									<a href=\"$root/courses/edit_comment_to_lesson.php?comment_id=$get_comment_id&amp;course_id=$get_comment_course_id&amp;module_id=$get_comment_module_id&amp;lesson_id=$get_current_lesson_id&amp;l=$l#comments\"><img src=\"$root/users/_gfx/edit.png\" alt=\"edit.png\" title=\"$l_edit\" /></a>
									<a href=\"$root/courses/delete_comment_to_lesson.php?comment_id=$get_comment_id&amp;course_id=$get_comment_course_id&amp;module_id=$get_comment_module_id&amp;lesson_id=$get_current_lesson_id&amp;l=$l#comments\"><img src=\"$root/users/_gfx/delete.png\" alt=\"delete.png\" title=\"$l_delete\" /></a>
									";
								}
								else{
									echo"
									<a href=\"$root/courses/report_comment_to_lesson.php?comment_id=$get_comment_id&amp;course_id=$get_comment_course_id&amp;module_id=$get_comment_module_id&amp;lesson_id=$get_current_lesson_id&amp;l=$l#comments\"><img src=\"$root/comments/_gfx/report_grey.png\" alt=\"report_grey.png\" title=\"$l_report\" /></a>
									";
								}
							}
							echo"
							<!-- //Menu -->
						  </td>
						 </tr>
						</table>
						<!-- //menu -->


						<!-- Author + date -->
						<p style=\"margin:0;padding:0;\">
						<span class=\"course_comment_by\">$l_by</span>
						<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\" class=\"course_comment_author\">$get_comment_user_alias</a>
						<span class=\"course_comment_at\">$l_special_translation_at_date_lowercase</span>
						<a href=\"#comment$get_comment_id\" class=\"course_comment_date\">$get_comment_date_print</a></span>
						</p>

						<!-- //Author + date -->

						<!-- Comment -->
							<p style=\"margin-top: 0px;padding-top: 0;\">$get_comment_text</p>
						<!-- Comment -->
					  </td>
					 </tr>
					</table>
					</div>
					";
				}
			echo"
			<!-- //View comments -->

		<!-- //Comments -->
		";
		
	} // Content found
} // lesson
else{
	if($course_id != "" && $module_id != ""){
		// Search for module
		$course_id_mysql = quote_smart($link, $course_id);
		$module_id_mysql = quote_smart($link, $module_id);
		$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=$module_id_mysql AND module_course_id=$course_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;

		if($get_current_module_id != ""){
			// Last read
			$datetime = date("Y-m-d H:i:s");
			$date_formatted = date("j M Y");
			$result = mysqli_query($link, "UPDATE $t_courses_modules SET module_last_read_datetime='$datetime', module_last_read_date_formatted='$date_formatted' WHERE module_id=$get_current_module_id");

			// Visits
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
		
			$ipblock_array = explode("\n", $get_current_module_read_ipblock);
			$size = sizeof($ipblock_array);
			$i_have_visited_before = "false";
			for($x=0;$x<$size;$x++){
				if($ipblock_array[$x] == "$my_ip"){
					$i_have_visited_before = "true";
				}
			}
			
			if($i_have_visited_before == "false"){
				$inp_module_read_times = $get_current_module_read_times+1;
			
				if($get_current_module_read_ipblock == ""){
					$inp_module_read_ipblock = "$my_ip";
				}
				else{
					$inp_module_read_ipblock = "$my_ip\n" . substr($get_current_module_read_ipblock, 0, 400);
				}
				$inp_module_read_ipblock_mysql = quote_smart($link, $inp_module_read_ipblock);

				$result = mysqli_query($link, "UPDATE $t_courses_modules SET module_read_times=$inp_module_read_times, module_read_ipblock=$inp_module_read_ipblock_mysql WHERE module_id=$get_current_module_id");

			}


			// Read status
			if(isset($_SESSION['user_id'])){
				$my_user_id = $_SESSION['user_id'];
				$my_user_id = output_html($my_user_id);
				$my_user_id_mysql = quote_smart($link, $my_user_id);

			
				// Check if I have read it
				$query = "SELECT module_read_id FROM $t_courses_modules_read WHERE read_course_id=$get_current_module_course_id AND read_module_id=$get_current_module_id AND read_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_module_read_id) = $row;
				if($get_module_read_id == ""){
					$inp_course_title_mysql = quote_smart($link, $get_current_module_course_title);
					$inp_module_title_mysql = quote_smart($link, $get_current_module_title);

					mysqli_query($link, "INSERT INTO $t_courses_modules_read 
					(module_read_id, read_course_id, read_course_title, read_module_id, read_module_title, read_user_id) 
					VALUES 
					(NULL, $get_current_module_course_id, $inp_course_title_mysql, $get_current_module_id, $inp_module_title_mysql, $my_user_id_mysql)");

					// Give point
					$query = "SELECT user_id, user_name, user_alias, user_points FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_points) = $row;
				
					$inp_user_points = $get_my_user_points +1;

					$result = mysqli_query($link, "UPDATE $t_users SET user_points=$inp_user_points WHERE user_id=$get_my_user_id");

					
					echo"
					<div class=\"info\" style=\"margin: 10px 0px 10px 0px;\">$l_congratulations_youve_finished_this_module_introduction<br />
					$l_you_earned_one_extra_point<br />
					$l_points: $get_my_user_points
					</div>
					";
				}
			} // read
		} // module found
	} // module
} // not content






?>