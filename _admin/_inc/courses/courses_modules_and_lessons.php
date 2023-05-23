<?php
/**
*
* File: _admin/_inc/comments/courses_modules_and_lessons.php
* Version 2
* Copyright (c) 2019-2023 Sindre Andre Ditlefsen
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


// Get course
$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
$stmt->bind_param("s", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;


if($get_current_course_id == ""){
	echo"<p>Server error 404.</p>";
}
else{
	// Find category
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_icon_path, main_category_icon_16x16, main_category_icon_18x18, main_category_icon_24x24, main_category_icon_32x32, main_category_icon_36x36, main_category_icon_48x48, main_category_icon_96x96, main_category_icon_260x260, main_category_header_logo, main_category_webdesign, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=$get_current_course_main_category_id";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_icon_path, $get_current_main_category_icon_16x16, $get_current_main_category_icon_18x18, $get_current_main_category_icon_24x24, $get_current_main_category_icon_32x32, $get_current_main_category_icon_36x36, $get_current_main_category_icon_48x48, $get_current_main_category_icon_96x96, $get_current_main_category_icon_260x260, $get_current_main_category_header_logo, $get_current_main_category_webdesign, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_courses_categories_sub WHERE sub_category_id=$get_current_course_sub_category_id";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

	// Title
	$stmt = $mysqli->prepare("SELECT courses_title_translation_id, courses_title_translation_title FROM $t_courses_title_translations WHERE courses_title_translation_language=?"); 
	$stmt->bind_param("s", $get_current_course_language);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_courses_title_translation_id, $get_current_courses_title_translation_title) = $row;
	if($get_current_courses_title_translation_id == ""){

		$inp_translation_title = "Courses";
		$stmt = $mysqli->prepare("INSERT INTO $t_courses_title_translations
		(courses_title_translation_id, courses_title_translation_title, courses_title_translation_language) 
		VALUES 
		(NULL,?,?,?)");
		$stmt->bind_param("sss", $inp_translation_title, $get_current_course_language); 
		$stmt->execute();


		$get_current_courses_title_translation_title = "Courses";
	}


	if($action == ""){
		if($process == "1"){
			$inp_points_needed_to_pass = $_POST['inp_points_needed_to_pass'];
			$inp_points_needed_to_pass = output_html($inp_points_needed_to_pass);

			// Update
			$stmt = $mysqli->prepare("UPDATE $t_courses_exams_index SET exam_points_needed_to_pass=? WHERE exam_id=?");
			$stmt->bind_param("ss", $inp_points_needed_to_pass, $get_current_exam_id); 
			$stmt->execute();
				
			$url = "index.php?open=$open&page=$page&course_id=$course_id&editor_language=$editor_language&ft=success&fm=changes_saved";
			header("Location: $url");
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
					<li><a href=\"index.php?open=courses&amp;page=courses_modules_and_lessons&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Modules and lessons</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Course navigation -->

		<!-- Actions -->
			<p>
			<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=new_module&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New module</a>
			<a href=\"../$get_current_course_title_clean/index.php?l=$get_current_course_language\" class=\"btn_default\">View course</a>
			</p>
		<!-- //Actions -->


		<!-- Modules and lessons -->

			

			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>No</span>
			   </th>
			   <th scope=\"col\">
				<span>Title</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";
			$total_modules = 0;
			$total_lessons = 0;
			$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean FROM $t_courses_modules WHERE module_course_id=$get_current_course_id ORDER BY module_number ASC";
			$result = $mysqli->query($query);
			while($row = $result->fetch_row()) {
				list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean) = $row;


				// Question number
				$total_modules = $total_modules+1;
				if($total_modules != "$get_module_number"){
					$result_update = mysqli_query($link, "UPDATE $t_courses_modules SET module_number=$total_modules WHERE module_id=$get_module_id");
					$get_module_number = "$total_modules";
				}

				echo"
				  <tr>
				   <td>
					<span><b>$get_module_number</b></span>
				   </td>
				   <td>
					<span><a href=\"index.php?open=courses&amp;page=open_module&amp;course_id=$course_id&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;\">$get_module_title</a></span>
				   </td>
				   <td>
					<span><b>
					<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=move_module_up&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Move up</a>
					&middot;
					<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=new_lesson&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">New lesson</a>
					&middot;
					<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=edit_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
					&middot;
					<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=delete_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
					</b></span>
				   </td>
				  </tr>";

				
				$query_lessons = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean FROM $t_courses_lessons WHERE lesson_module_id=$get_module_id ORDER BY lesson_number ASC";
				$result_lessons = $mysqli->query($query_lessons);
				while($row_lessons = $result_lessons->fetch_row()) {
					list($get_lesson_id, $get_lesson_number, $get_lesson_title, $get_lesson_title_clean) = $row_lessons;

					// Lesson number
					$total_lessons = $total_lessons+1;
					if($total_lessons != "$get_lesson_number"){

						if ($mysqli->query("UPDATE $t_courses_lessons SET lesson_number=$total_lessons WHERE lesson_id=$get_lesson_id") !== TRUE) {
							echo "Error MySQLi update: " . $mysqli->error; die;
						}

						$get_lesson_number = "$total_lessons";
					}

					echo"
					 <tr>
					  <td class=\"odd\" style=\"padding-left: 10px;\">
						<span>$get_lesson_number</span>
					  </td>
					  <td class=\"odd\">
						<span><a href=\"index.php?open=courses&amp;page=open_lesson&amp;course_id=$course_id&amp;module_id=$get_module_id&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">$get_lesson_title</a></span>
					  </td>
					  <td class=\"odd\">
						<span>
						<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=move_lesson_up&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Move up</a>
						&middot;
						<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=move_lesson_down&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Move down</a>
						&middot;
						<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=edit_lesson&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
						&middot;
						<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=delete_lesson&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
						</span>
					  </td>
					 </tr>";

				} // while lessons

			} // while modules
			if($total_modules != "$get_current_course_modules_count" OR $total_lessons != "$get_current_course_lessons_count"){
				if ($mysqli->query("UPDATE $t_courses_index SET course_modules_count=$total_modules, course_lessons_count=$total_lessons WHERE course_id=$get_current_course_id") !== TRUE) {
					echo "Error MySQLi update: " . $mysqli->error; die;
				}
			}
			echo"
			 </tbody>
			</table>
		<!-- //Modules and lessons -->
		";
	} // action ==""
	elseif($action == "new_module"){
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			

			$inp_title_clean = clean($inp_title);
			

			$inp_course_title = "$get_current_course_title";

			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j M Y H:i");

			$inp_module_number = 99;
			$inp_module_read_times = 0;

			$stmt = $mysqli->prepare("INSERT INTO $t_courses_modules 
				(module_id, module_course_id, module_course_title, module_number, module_title, 
				module_title_clean, module_read_times, module_created) 
				VALUES 
				(NULL,?,?,?,?,
				?,?)");
			$stmt->bind_param("ssssss", $get_current_course_id, $inp_course_title, $inp_module_number, $inp_title, 
				$inp_title_clean, $inp_module_read_times, $datetime); 
			$stmt->execute();


			// Get ID
			$stmt = $mysqli->prepare("SELECT module_id FROM $t_courses_modules WHERE module_title=? AND module_created=?"); 
			$stmt->bind_param("ss", $inp_title, $datetime);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_row();
			list($get_current_module_id) = $row;



			// Search engine
			$inp_index_title = "$inp_title | $get_current_course_title | $get_current_courses_title_translation_title";

			$inp_index_url = "$get_current_course_title_clean/$inp_title_clean/index.php?course_id=$get_current_course_id&module_id=$get_current_module_id";

			$inp_index_short_description = "";
			$inp_index_keywords = "";
			$inp_index_module_name = "courses";
			$inp_index_module_part_name = "module";
			$inp_index_module_part_id = 0;
			$inp_index_reference_name = "module_id";
			$inp_index_has_access_control = 0;
			$inp_index_is_ad = 0;

			$inp_index_language = "$get_current_course_language";
			$inp_index_unique_hits = 0;

			$stmt = $mysqli->prepare("INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits)
				VALUES 
				(NULL,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?)");
			$stmt->bind_param("sssssssssssssss", $inp_index_title, $inp_index_url, $inp_index_short_description, $inp_index_keywords, 
				$inp_index_module_name, $inp_index_module_part_name, $inp_index_module_part_id, $inp_index_reference_name, $get_current_module_id,
				$inp_index_has_access_control, $inp_index_is_ad, $datetime, $datetime_saying, $inp_index_language,
				$inp_index_unique_hits0); 
			$stmt->execute();


			// Get new data
			$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
			$stmt->bind_param("s", $course_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_row();
			list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

			// Write to files
			include("_inc/courses/courses_write_to_file_include.php");


			$url = "index.php?open=$open&page=$page&course_id=$course_id&action=new_module&editor_language=$editor_language&ft=success&fm=module_$inp_title" . "_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_course_title</h1>
				
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
			&gt;
			<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l\">New module</a>
			</p>
		<!-- //Where am I? -->

		<!-- Course navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_modules_and_lessons&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Modules and lessons</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Course navigation -->


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

		<!-- Left and right -->
			<table>
			 <tr>
			  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;width: 50%;\">
				<!-- New module form -->
					<h2>New module</h2>
					<script>
					window.onload = function() {
						document.getElementById(\"inp_title\").focus();
					}
					</script>
			
					<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

					<p><b>Module title:</b><br />
					<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 60%;\" />
					
					<input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				<!-- //New module form -->
			
			  </td>
			  <td style=\"vertical-align: top;\">	
				<!-- Modules and lessons -->
					<h2>Modules and lessons</h2>
			

					<table class=\"hor-zebra\">
					 <thead>
					  <tr>
					   <th scope=\"col\">
						<span>No</span>
					   </th>
					   <th scope=\"col\">
						<span>Title</span>
					   </th>
					   <th scope=\"col\">
						<span>Actions</span>
					   </th>
					  </tr>
					 </thead>
					 <tbody>";
					$total_modules = 0;
					$total_lessons = 0;
					$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean FROM $t_courses_modules WHERE module_course_id=$get_current_course_id ORDER BY module_number ASC";
					$result = $mysqli->query($query);
					while($row = $result->fetch_row()) {
						list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean) = $row;


						// Question number
						$total_modules = $total_modules+1;
						if($total_modules != "$get_module_number"){
							if ($mysqli->query("UPDATE $t_courses_modules SET module_number=$total_modules WHERE module_id=$get_module_id") !== TRUE) {
								echo "Error MySQLi update: " . $mysqli->error; die;
							}
							$get_module_number = "$total_modules";
						}
		
						echo"
						  <tr>
						   <td>
							<span><b>$get_module_number</b></span>
						   </td>
						   <td>
							<span><b>$get_module_title</b></span>
						   </td>
						   <td>
							<span><b>
							<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=new_lesson&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">New lesson</a>
							&middot;
							<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=edit_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
							&middot;
							<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=delete_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
							</b></span>
						   </td>
						  </tr>";

					} // while modules
					if($total_modules != "$get_current_course_modules_count" OR $total_lessons != "$get_current_course_lessons_count"){
						
						if ($mysqli->query("UPDATE $t_courses_index SET course_modules_count=$total_modules, course_lessons_count=$total_lessons WHERE course_id=$get_current_course_id") !== TRUE) {
							echo "Error MySQLi update: " . $mysqli->error; die;
						}
					}
					echo"
					 </tbody>
					</table>
				<!-- //Modules and lessons -->
			  </td>
			 </tr>
			</table>
		<!-- //Left and right -->
		
		";
	} // action == new module
	elseif($action == "edit_module"){
		if(isset($_GET['module_id'])){
			$module_id = $_GET['module_id'];
			$module_id = strip_tags(stripslashes($module_id));
		}
		else{
			$module_id = "";
		}

		$stmt = $mysqli->prepare("SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=? AND module_course_id=?"); 
		$stmt->bind_param("ss", $module_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;

		if($get_current_module_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);

				$inp_title_clean = clean($inp_title);

				$inp_course_title = "$get_current_course_title";

				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");

				$stmt = $mysqli->prepare("UPDATE $t_courses_modules SET 
							module_course_title=?,
							module_title=?, 
							module_title_clean=?,
							module_updated=?
							WHERE module_id=?");
				$stmt->bind_param("sssss", $inp_course_title, $inp_title, $inp_title_clean, $datetime, $get_current_module_id); 
				$stmt->execute();


				// Search engine
				$inp_index_title = "$inp_title | $get_current_course_title | $get_current_courses_title_translation_title";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_current_course_title_clean/$inp_title_clean/index.php?course_id=$get_current_course_id&module_id=$get_current_module_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);
			

				$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='courses' AND index_reference_name='module_id' AND index_reference_id=$get_current_module_id";
				$result_exists = mysqli_query($link, $query_exists);
				$row_exists = mysqli_fetch_row($result_exists);
				list($get_index_id) = $row_exists;
				if($get_index_id != ""){

					$stmt = $mysqli->prepare("UPDATE $t_search_engine_index SET 
								index_title=?, 
								index_url=?, 
								index_updated_datetime=?,
								index_updated_datetime_print=?
								WHERE index_id=?");
					$stmt->bind_param("sssss", $inp_index_title, $inp_index_url, $datetime, $datetime_saying, $get_index_id); 
					$stmt->execute();

				}

				// Get new data
				$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
				$stmt->bind_param("s", $course_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_row();
				list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

				// Write to files
				include("_inc/courses/courses_write_to_file_include.php");


				$url = "index.php?open=$open&page=$page&course_id=$course_id&action=$action&module_id=$get_current_module_id&editor_language=$editor_language&ft=success&fm=changes_saved";
				header("Location: $url");
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
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l\">Edit module</a>
				</p>
			<!-- //Where am I? -->
	
			<!-- Course navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_modules_and_lessons&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Modules and lessons</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
			<!-- //Course navigation -->


			<!-- Left and right -->
				<table>
				 <tr>
				  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;width: 50%;\">
					<!-- Edit module form -->
						<h2>Edit module</h2>
						<script>
						window.onload = function() {
							document.getElementById(\"inp_title\").focus();
						}
						</script>
			
						<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

						<p><b>Title:</b><br />
						<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"$get_current_module_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						
						<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					<!-- //Edit module form -->
		
				  </td>
				  <td style=\"vertical-align: top;\">	
					<!-- Modules and lessons -->
						<h2>Modules and lessons</h2>
			

						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>No</span>
						   </th>
						   <th scope=\"col\">
							<span>Title</span>
						   </th>
						   <th scope=\"col\">
							<span>Actions</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";
						$total_modules = 0;
						$total_lessons = 0;
						$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean FROM $t_courses_modules WHERE module_course_id=$get_current_course_id ORDER BY module_number ASC";
						$result = $mysqli->query($query);
						while($row = $result->fetch_row()) {
							list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean) = $row;


							// Question number
							$total_modules = $total_modules+1;
							if($total_modules != "$get_module_number"){
								if ($mysqli->query("UPDATE $t_courses_modules SET module_number=$total_modules WHERE module_id=$get_module_id") !== TRUE) {
									echo "Error MySQLi update: " . $mysqli->error; die;
								}
								$get_module_number = "$total_modules";
							}
		
							echo"
							  <tr>
							   <td>
								<span><b>$get_module_number</b></span>
							   </td>
							   <td>
								<span><b>$get_module_title</b></span>
							   </td>
							   <td>
								<span><b>
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=new_lesson&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">New lesson</a>
								&middot;
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=edit_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
								&middot;
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=delete_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
								</b></span>
							   </td>
							  </tr>";

				

						} // while modules
						if($total_modules != "$get_current_course_modules_count" OR $total_lessons != "$get_current_course_lessons_count"){							
							if ($mysqli->query("UPDATE $t_courses_index SET course_modules_count=$total_modules, course_lessons_count=$total_lessons WHERE course_id=$get_current_course_id") !== TRUE) {
								echo "Error MySQLi update: " . $mysqli->error; die;
							}
						}
						echo"
						 </tbody>
						</table>
					<!-- //Modules and lessons -->
				  </td>
				 </tr>
				</table>
			<!-- //Left and right -->
			";
		} // module found
	} // action == edit module 
	elseif($action == "delete_module"){
		if(isset($_GET['module_id'])){
			$module_id = $_GET['module_id'];
			$module_id = strip_tags(stripslashes($module_id));
		}
		else{
			$module_id = "";
		}

		$stmt = $mysqli->prepare("SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=? AND module_course_id=?"); 
		$stmt->bind_param("ss", $module_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;

		if($get_current_module_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				$mysqli->query("DELETE FROM $t_courses_modules WHERE module_id=$get_current_module_id") or die($mysqli->error);


				// Search engine module
				$mysqli->query("DELETE FROM $t_search_engine_index WHERE index_module_name='courses' AND index_reference_name='module_id' AND index_reference_id=$get_current_module_id") or die($mysqli->error);

				
				// TODO: Search engine lessons

				// Get new data
				$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
				$stmt->bind_param("s", $course_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_row();
				list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

				// Write to files
				include("_inc/courses/courses_write_to_file_include.php");

				$url = "index.php?open=$open&page=$page&course_id=$course_id&editor_language=$editor_language&ft=success&fm=module_deleted";
				header("Location: $url");
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
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l\">Delete module</a>
				</p>
			<!-- //Where am I? -->

			<!-- Delete module form -->
				<h2>Delete module</h2>
				
				<p>Are you sure you want to delete the module?</p>
			
			
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm</a>
				
				</p>
			<!-- //Delete module form -->
		
			";
		} // module found
	} // action == delete module
	elseif($action == "move_module_up"){
		if(isset($_GET['module_id'])){
			$module_id = $_GET['module_id'];
			$module_id = strip_tags(stripslashes($module_id));
		}
		else{
			$module_id = "";
		}
		$stmt = $mysqli->prepare("SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=? AND module_course_id=?"); 
		$stmt->bind_param("ss", $module_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;


		if($get_current_module_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{

			// Find the module to change with
			$current_module_number_minus_one = $get_current_module_number-1;
	
			$stmt = $mysqli->prepare("SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_number=? AND module_course_id=?"); 
			$stmt->bind_param("ss", $current_module_number_minus_one, $get_current_module_course_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_row();
			list($get_change_module_id, $get_change_module_course_id, $get_change_module_course_title, $get_change_module_number, $get_change_module_title, $get_change_module_title_clean, $get_change_module_read_times, $get_change_module_read_ipblock, $get_change_module_created, $get_change_module_updated, $get_change_module_last_read_datetime, $get_change_module_last_read_date_formatted) = $row;
	

			if($get_change_module_id == ""){
				$url = "index.php?open=courses&page=courses_modules_and_lessons&course_id=$get_current_module_course_id&editor_language=$editor_language&l=$l&ft=error&fm=change_module_not_found#module$get_current_module_id";
				header("Location: $url");
				exit;
			}
			else{
				// Update current
				$mysqli->query("UPDATE $t_courses_modules SET 
							module_number=$current_module_number_minus_one
							WHERE module_id=$get_current_module_id") or die($mysqli->error);

				// Update change
				$mysqli->query("UPDATE $t_courses_modules SET 
							module_number=$get_current_module_number
							WHERE module_id=$get_change_module_id") or die($mysqli->error);


				// Get new data
				$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
				$stmt->bind_param("s", $course_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_row();
				list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

				// Write to files
				include("_inc/courses/courses_write_to_file_include.php");


				$url = "index.php?open=courses&page=courses_modules_and_lessons&course_id=$get_current_module_course_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved#module$get_current_module_id";
				header("Location: $url");
				exit;
			}

		} // module found
	} // action == move_module_up
	elseif($action == "new_lesson"){
		if(isset($_GET['module_id'])){
			$module_id = $_GET['module_id'];
			$module_id = strip_tags(stripslashes($module_id));
		}
		else{
			$module_id = "";
		}

		$stmt = $mysqli->prepare("SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=? AND module_course_id=?"); 
		$stmt->bind_param("ss", $module_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;

		if($get_current_module_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				

				$inp_title_clean = clean($inp_title);
				

				$inp_title_length = strlen($inp_title);
				

				if($inp_title_length  > 27){
					$inp_title_short = substr($inp_title, 0, 24);
					$inp_title_short = $inp_title_short . "...";
				}
				else{
					$inp_title_short = "";
				}


				$inp_course_title = "$get_current_course_title";
				
				$inp_module_title = "$get_current_module_title";


				$datetime = date("Y-m-d H:i:s");
				$date_formatted = date("j. M Y");

				$inp_lesson_number = 99;
				$inp_lesson_description = "";
				$inp_lesson_content = "";
				$inp_lesson_read_times = 0;

				$stmt = $mysqli->prepare("INSERT INTO $t_courses_lessons
					(lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_title_length, 
					lesson_title_short, lesson_description, lesson_content, lesson_course_id, lesson_course_title, 
					lesson_module_id, lesson_module_title, lesson_read_times, lesson_created_datetime, lesson_created_date_formatted)
					VALUES 
					(NULL,?,?,?,?,
					?,?,?,?,?,
					?,?,?,?,?)");
				$stmt->bind_param("ssssssssssssss", $inp_lesson_number, $inp_title, $inp_title_clean, $inp_title_length, 
					$inp_title_short, $inp_lesson_description, $inp_lesson_content, $get_current_course_id, $inp_course_title, 
					$get_current_module_id, $inp_module_title, $inp_lesson_read_times, $datetime, $date_formatted); 
				$stmt->execute();


				// Get ID
				$query = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_created_datetime='$datetime'";
				$result = $mysqli->query($query);
				$row = $result->fetch_row();
				list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;


				// Search engine
				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");

				$inp_index_title = "$inp_title | $get_current_module_title | $get_current_course_title | $get_current_courses_title_translation_title";


				$inp_index_url = "$get_course_title_clean/$get_current_module_title_clean/$get_current_lesson_title_clean.php?course_id=$get_current_course_id&module_id=$get_current_module_id&lesson_id=$get_current_lesson_id";
			
				$inp_index_short_description = "";
				$inp_index_keywords = "";
				$inp_index_module_name = "courses";
				$inp_index_module_part_name = "lesson";
				$inp_index_module_part_id = 0;
				$inp_index_reference_name = "lesson_id";
				$inp_index_has_access_control = 0;
				$inp_index_is_ad = 0;
				$inp_index_language = "$get_current_course_language";
				$inp_index_unique_hits = 0;

				$stmt = $mysqli->prepare("INSERT INTO $t_search_engine_index 
					(index_id, index_title, index_url, index_short_description, index_keywords, 
					index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
					index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
					index_unique_hits) 
					VALUES 
					(NULL,?,?,?,?,
					?,?,?,?,?,
					?,?,?,?,?,
					?)");
				$stmt->bind_param("ssssss", inp_index_title, $inp_index_url, $inp_index_short_description, $inp_index_keywords, 
					$inp_index_module_name, $inp_index_module_part_name, $inp_index_module_part_id, $inp_index_reference_name, $get_current_lesson_id,
					$inp_index_has_access_control, $inp_index_is_ad, $datetime, $datetime_saying, $inp_index_language,
					$inp_index_unique_hits); 
				$stmt->execute();


				// Get new data
				$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
				$stmt->bind_param("s", $course_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_row();
				list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

				// Write to files
				include("_inc/courses/courses_write_to_file_include.php");


				$url = "index.php?open=$open&page=$page&course_id=$course_id&action=$action&module_id=$get_current_module_id&editor_language=$editor_language&ft=success&fm=creaded_lesson_$inp_title" . "_saved";
				header("Location: $url");
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
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l\">New lesson</a>
				</p>
			<!-- //Where am I? -->
	
			<!-- Course navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_modules_and_lessons&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Modules and lessons</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
			<!-- //Course navigation -->

			<!-- Left and right -->
				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;width: 50%;\">
					<!-- New lesson form -->
						<h2>New lesson to module <em>$get_current_module_title</em></h2>
						<script>
						window.onload = function() {
							document.getElementById(\"inp_title\").focus();
						}
						</script>
			
						<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

						<p><b>Title:</b><br />
						<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 50%;\" />
						
						<input type=\"submit\" value=\"Create lesson\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					<!-- //New lesson form -->
		
				  </td>
				  <td style=\"vertical-align: top;\">	
					<!-- Modules and lessons -->
						<h2>Modules and lessons</h2>
			

						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>No</span>
						   </th>
						   <th scope=\"col\">
							<span>Title</span>
						   </th>
						   <th scope=\"col\">
							<span>Actions</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";
						$total_modules = 0;
						$total_lessons = 0;
						$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean FROM $t_courses_modules WHERE module_course_id=$get_current_course_id ORDER BY module_number ASC";
						$result = $mysqli->query($query);
						while($row = $result->fetch_row()) {
							list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean) = $row;


							// Question number
							$total_modules = $total_modules+1;
							if($total_modules != "$get_module_number"){
								$mysqli->query("UPDATE $t_courses_modules SET module_number=$total_modules WHERE module_id=$get_module_id") or die($mysqli->error);

								$get_module_number = "$total_modules";
							}
		
							echo"
							  <tr>
							   <td>
								<span><b>$get_module_number</b></span>
							   </td>
							   <td>
								<span><b>$get_module_title</b></span>
							   </td>
							   <td>
								<span><b>
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=new_lesson&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">New lesson</a>
								&middot;
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=edit_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
								&middot;
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=delete_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
								</b></span>
							   </td>
							  </tr>";

							if($module_id == "$get_module_id"){
								$query_lessons = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean FROM $t_courses_lessons WHERE lesson_module_id=$get_module_id ORDER BY lesson_number ASC";
								$result = $mysqli->query($query);
								while($row = $result->fetch_row()) {
									list($get_lesson_id, $get_lesson_number, $get_lesson_title, $get_lesson_title_clean) = $row_lessons;

									// Lesson number
									$total_lessons = $total_lessons+1;
									if($total_lessons != "$get_lesson_number"){
										$mysqli->query("UPDATE $t_courses_lessons SET lesson_number=$total_lessons WHERE lesson_id=$get_lesson_id") or die($mysqli->error);

										$get_lesson_number = "$total_lessons";
									}

									echo"
									 <tr>
									  <td class=\"odd\" style=\"padding-left: 10px;\">
										<span>$get_lesson_number</span>
									  </td>
									  <td class=\"odd\">
										<span>$get_lesson_title</span>
									  </td>
									  <td class=\"odd\">
										<span>
										<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=edit_lesson&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
										&middot;
										<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=delete_lesson&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
										</span>
									  </td>
									 </tr>";

								} // while lessons
							}

						} // while modules
						if($total_modules != "$get_current_course_modules_count" OR $total_lessons != "$get_current_course_lessons_count"){
							$mysqli->query("UPDATE $t_courses_index SET course_modules_count=$total_modules, course_lessons_count=$total_lessons WHERE course_id=$get_current_course_id") or die($mysqli->error);
						}
						echo"
						 </tbody>
						</table>
					<!-- //Modules and lessons -->
				  </td>
				 </tr>
				</table>
			<!-- //Left and right -->
			";
		} // module found
	} // action == new lesson
	elseif($action == "move_lesson_up"){
		if(isset($_GET['lesson_id'])){
			$lesson_id = $_GET['lesson_id'];
			$lesson_id = strip_tags(stripslashes($lesson_id));
		}
		else{
			$lesson_id = "";
		}

		$stmt = $mysqli->prepare("SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id=? AND lesson_course_id=?"); 
		$stmt->bind_param("ss", $lesson_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;

		if($get_current_lesson_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){

				// Find the lesson to change with
				$current_lesson_number_minus_one = $get_current_lesson_number-1;

				$stmt = $mysqli->prepare("SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_number=? AND lesson_course_id=?"); 
				$stmt->bind_param("ss", $current_lesson_number_minus_one, $get_current_course_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_row();
				list($get_change_lesson_id, $get_change_lesson_number, $get_change_lesson_title, $get_change_lesson_title_clean, $get_change_lesson_description, $get_change_lesson_content, $get_change_lesson_course_id, $get_change_lesson_course_title, $get_change_lesson_module_id, $get_change_lesson_module_title, $get_change_lesson_read_times, $get_change_lesson_read_times_ipblock, $get_change_lesson_created_datetime, $get_change_lesson_created_date_formatted, $get_change_lesson_last_read_datetime, $get_change_lesson_last_read_date_formatted) = $row;
				
				if($get_change_lesson_id == ""){
					$url = "index.php?open=courses&page=courses_modules_and_lessons&course_id=$get_current_lesson_course_id&editor_language=$editor_language&l=$l&ft=error&fm=change_lesson_not_found#module$get_current_lesson_module_id";
					header("Location: $url");
					exit;
				}
				else{
					// Update current
					$mysqli->query("UPDATE $t_courses_lessons SET 
							lesson_number=$current_lesson_number_minus_one
							WHERE lesson_id=$get_current_lesson_id") or die($mysqli->error);


					// Update change
					$mysqli->query("UPDATE $t_courses_lessons SET 
							lesson_number=$get_current_lesson_number
							WHERE lesson_id=$get_change_lesson_id") or die($mysqli->error);

					
					// Get new data
					$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
					$stmt->bind_param("s", $course_id);
					$stmt->execute();
					$result = $stmt->get_result();
					$row = $result->fetch_row();
					list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;
					
					// Write to files
					include("_inc/courses/courses_write_to_file_include.php");


					$url = "index.php?open=courses&page=courses_modules_and_lessons&course_id=$get_current_lesson_course_id&editor_language=$editor_language&l=$l&ft=success&fm=moved#module$get_current_lesson_module_id";
					header("Location: $url");
					exit;
				}
				
			}
			else{
				echo"Not prosessing";
			}
		}
	} // action == move_lesson_up
	elseif($action == "move_lesson_down"){
		if(isset($_GET['lesson_id'])){
			$lesson_id = $_GET['lesson_id'];
			$lesson_id = strip_tags(stripslashes($lesson_id));
		}
		else{
			$lesson_id = "";
		}

		$stmt = $mysqli->prepare("SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id=? AND lesson_course_id=?"); 
		$stmt->bind_param("ss", $lesson_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;

		if($get_current_lesson_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){

				// Find the lesson to change with
				$current_lesson_number_plus_one = $get_current_lesson_number+1;

				
				$stmt = $mysqli->prepare("SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_number=? AND lesson_course_id=?"); 
				$stmt->bind_param("ss", $current_lesson_number_plus_one, $get_current_course_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_row();
				list($get_change_lesson_id, $get_change_lesson_number, $get_change_lesson_title, $get_change_lesson_title_clean, $get_change_lesson_description, $get_change_lesson_content, $get_change_lesson_course_id, $get_change_lesson_course_title, $get_change_lesson_module_id, $get_change_lesson_module_title, $get_change_lesson_read_times, $get_change_lesson_read_times_ipblock, $get_change_lesson_created_datetime, $get_change_lesson_created_date_formatted, $get_change_lesson_last_read_datetime, $get_change_lesson_last_read_date_formatted) = $row;
				

				if($get_change_lesson_id == ""){
					$url = "index.php?open=courses&page=courses_modules_and_lessons&course_id=$get_current_lesson_course_id&editor_language=$editor_language&l=$l&ft=error&fm=change_lesson_not_found#module$get_current_lesson_module_id";
					header("Location: $url");
					exit;
				}
				else{
					// Update current
					$mysqli->query("UPDATE $t_courses_lessons SET 
							lesson_number=$current_lesson_number_plus_one
							WHERE lesson_id=$get_current_lesson_id") or die($mysqli->error);


					// Update change
					$mysqli->query("UPDATE $t_courses_lessons SET 
							lesson_number=$get_current_lesson_number
							WHERE lesson_id=$get_change_lesson_id") or die($mysqli->error);


					

					// Write to files
					include("_inc/courses/courses_write_to_file_include.php");


					$url = "index.php?open=courses&page=courses_modules_and_lessons&course_id=$get_current_lesson_course_id&editor_language=$editor_language&l=$l&ft=success&fm=moved#module$get_current_lesson_module_id";
					header("Location: $url");
					exit;
				}
				
			}
			else{
				echo"Not prosessing";
			}
		}
	} // action == move_lesson_down
	elseif($action == "edit_lesson"){
		if(isset($_GET['lesson_id'])){
			$lesson_id = $_GET['lesson_id'];
			$lesson_id = strip_tags(stripslashes($lesson_id));
		}
		else{
			$lesson_id = "";
		}

		$stmt = $mysqli->prepare("SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id=? AND lesson_course_id=?"); 
		$stmt->bind_param("ss", $lesson_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;

		if($get_current_lesson_id == ""){
			echo"<p>Server error 404, lesson not found.</p>";

			// Check if there are lesson without this course (because server error 404)
			
			$stmt = $mysqli->prepare("SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id=?"); 
			$stmt->bind_param("s", $lesson_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_row();
			list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;
			if($get_current_lesson_id != ""){
				echo"<p style=\"color:red;\">Found lesson outside this course... Updating it to correct course. Please refresh this site.</p>";
				$mysqli->query("UPDATE $t_courses_lessons SET lesson_course_id=$get_current_course_id WHERE lesson_id=$get_current_lesson_id") or die($mysqli->error);
				
			}
		}
		else{
			// Get module
			$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=$get_current_lesson_module_id";
			$result = $mysqli->query($query);
			$row = $result->fetch_row();
			list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;

			// Get course
			$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=$get_current_lesson_course_id";
			$result = $mysqli->query($query);
			$row = $result->fetch_row();
			list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;



			if($process == "1"){
				// Dates
				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");



				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);

				$inp_title_clean = clean($inp_title);

				$inp_title_length = strlen($inp_title);

				if($inp_title_length  > 27){
					$inp_title_short = substr($inp_title, 0, 24);
					$inp_title_short = $inp_title_short . "...";
				}
				else{
					$inp_title_short = "";
				}

				$stmt = $mysqli->prepare("UPDATE $t_courses_lessons SET 
						lesson_title=?, 
						lesson_title_clean=?, 
						lesson_title_length=?, 
						lesson_title_short=?
						WHERE lesson_id=?");
				$stmt->bind_param("sssss", $inp_title, $inp_title_clean, $inp_title_length, $inp_title_short, $get_current_lesson_id); 
				$stmt->execute();
				

				// Get new data
				$query = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id='$get_current_lesson_id'";
				$result = $mysqli->query($query);
				$row = $result->fetch_row();
				list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;


				// Search engine
				$inp_index_title = "$inp_title | $get_current_module_title | $get_current_course_title | $get_current_courses_title_translation_title";

				$inp_index_url = "$get_current_course_title_clean/$get_current_module_title_clean/$get_current_lesson_title_clean.php?course_id=$get_current_course_id&module_id=$get_current_module_id&lesson_id=$get_current_lesson_id";
		

				$query = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='courses' AND index_reference_name='lesson_id' AND index_reference_id=$get_current_lesson_id";
				$result = $mysqli->query($query);
				$row = $result->fetch_row();
				list($get_index_id) = $row;
				if($get_index_id != ""){
					$stmt = $mysqli->prepare("UPDATE $t_search_engine_index SET 
								index_title=?,
								index_url=?,
								index_updated_datetime=?,
								index_updated_datetime_print=?
								WHERE index_id=?");
					$stmt->bind_param("sssss", $inp_index_title, inp_index_url, $datetime, $datetime_saying, $get_index_id); 
					$stmt->execute();
				}

	
				// Write to files
				include("_inc/courses/courses_write_to_file_include.php");


				$url = "index.php?open=$open&page=$page&course_id=$course_id&action=edit_lesson&lesson_id=$get_current_lesson_id&editor_language=$editor_language&ft=success&fm=changes_saved";
				header("Location: $url");
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
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;lesson_id=$get_current_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">Edit lesson</a>
				</p>
			<!-- //Where am I? -->

			<!-- Course navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_modules_and_lessons&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Modules and lessons</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
			<!-- //Course navigation -->

			<!-- Left and right -->
				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;width:50%;\">
					
					<!-- Edit module form -->
						<h2>Edit lesson</h2>
						<script>
						window.onload = function() {
							document.getElementById(\"inp_title\").focus();
						}
						</script>
						<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;lesson_id=$get_current_lesson_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		
						<p><b>Title:</b><br />
						<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"$get_current_lesson_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 90%;\" />
						
						<input type=\"submit\" value=\"Save lesson\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					<!-- //New module form -->
		
				  </td>
				  <td style=\"vertical-align: top;\">	
					<!-- Modules and lessons -->
						<h2>Modules and lessons</h2>
			

						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>No</span>
						   </th>
						   <th scope=\"col\">
							<span>Title</span>
						   </th>
						   <th scope=\"col\">
							<span>Actions</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";
						$total_modules = 0;
						$total_lessons = 0;
						$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean FROM $t_courses_modules WHERE module_course_id=$get_current_course_id ORDER BY module_number ASC";
						$result = $mysqli->query($query);
						while($row = $result->fetch_row()) {
							list($get_module_id, $get_module_course_id, $get_module_course_title, $get_module_number, $get_module_title, $get_module_title_clean) = $row;


							// Question number
							$total_modules = $total_modules+1;
							if($total_modules != "$get_module_number"){
								$mysqli->query("UPDATE $t_courses_modules SET module_number=$total_modules WHERE module_id=$get_module_id") or die($mysqli->error);
								$get_module_number = "$total_modules";
							}
		
							echo"
							  <tr>
							   <td>
								<span><b>$get_module_number</b></span>
							   </td>
							   <td>
								<span><b>$get_module_title</b></span>
							   </td>
							   <td>
								<span><b>
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=new_lesson&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">New lesson</a>
								&middot;
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=edit_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
								&middot;
								<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=delete_module&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
								</b></span>
							   </td>
							  </tr>";

							if($get_current_lesson_module_id == "$get_module_id"){
								$query_lessons = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean FROM $t_courses_lessons WHERE lesson_module_id=$get_module_id ORDER BY lesson_number ASC";
								$result_lessons = $mysqli->query($query_lessons);
								while($row_lessons = $result_lessons->fetch_row()) {
									list($get_lesson_id, $get_lesson_number, $get_lesson_title, $get_lesson_title_clean) = $row_lessons;

									// Lesson number
									$total_lessons = $total_lessons+1;
									if($total_lessons != "$get_lesson_number"){
										$mysqli->query("UPDATE $t_courses_lessons SET lesson_number=$total_lessons WHERE lesson_id=$get_lesson_id") or die($mysqli->error);
										$get_lesson_number = "$total_lessons";
									}

									echo"
									 <tr>
									  <td class=\"odd\" style=\"padding-left: 10px;\">
										<span>$get_lesson_number</span>
									  </td>
									  <td class=\"odd\">
										<span>$get_lesson_title</span>
									  </td>
									  <td class=\"odd\">
										<span>
										<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=edit_lesson&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
										&middot;
										<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=delete_lesson&amp;lesson_id=$get_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
										</span>
									  </td>
									 </tr>";

								} // while lessons
							}

						} // while modules
						if($total_modules != "$get_current_course_modules_count" OR $total_lessons != "$get_current_course_lessons_count"){
							$mysqli->query("UPDATE $t_courses_index SET course_modules_count=$total_modules, course_lessons_count=$total_lessons WHERE course_id=$get_current_course_id") or die($mysqli->error);
										
						}
						echo"
						 </tbody>
						</table>
					<!-- //Modules and lessons -->
				  </td>
				 </tr>
				</table>
			<!-- //Left and right -->
			";
		} // module found
	} // action == edit lesson
	elseif($action == "delete_lesson"){
		if(isset($_GET['lesson_id'])){
			$lesson_id = $_GET['lesson_id'];
			$lesson_id = strip_tags(stripslashes($lesson_id));
		}
		else{
			$lesson_id = "";
		}
		$stmt = $mysqli->prepare("SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id=? AND lesson_course_id=?"); 
		$stmt->bind_param("ss", $lesson_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;

		if($get_current_lesson_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				$mysqli->query("DELETE FROM $t_courses_lessons WHERE lesson_id=$get_current_lesson_id") or die($mysqli->error);

				// Search engine
				$mysqli->query("DELETE FROM $t_search_engine_index WHERE index_module_name='courses' AND index_reference_name='lesson_id' AND index_reference_id=$get_current_lesson_id") or die($mysqli->error);


		
				// Write to files
				include("_inc/courses/courses_write_to_file_include.php");

				$url = "index.php?open=$open&page=$page&course_id=$course_id&editor_language=$editor_language&ft=success&fm=lesson_deleted";
				header("Location: $url");
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
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;lesson_id=$get_current_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">Delete lesson</a>
				</p>
			<!-- //Where am I? -->

			<!-- Edit module form -->
				<h2>Delete lesson</h2>
				
				<p>
				Are you sure you want to delete the lesson?
				</p>
			
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=$action&amp;lesson_id=$get_current_lesson_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm</a>
				</p>
			<!-- //New module form -->
		
			";
		} // module found
	} // action == delete lesson
} // found
?>