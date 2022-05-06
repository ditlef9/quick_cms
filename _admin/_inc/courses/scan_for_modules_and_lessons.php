<?php
/**
*
* File: _admin/_inc/comments/courses_scan_for_modules_and_lessons.php
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

$query = "SELECT course_id, course_title, course_description, course_language, course_dir_name, course_category_id, course_intro_video_embedded, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_created, course_updated FROM $t_courses_index WHERE course_id=$course_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_course_id, $get_current_course_title, $get_current_course_description, $get_current_course_language, $get_current_course_dir_name, $get_current_course_category_id, $get_current_course_intro_video_embedded, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_created, $get_current_course_updated) = $row;

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
		<h1>$get_current_category_title</h1>
				

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
			&gt;
			<a href=\"index.php?open=courses&amp;page=scan_for_modules_and_lessons&amp;course_id=$get_current_course_id&amp;editor_language=$editor_language&amp;l=$l\">Scan for modules and lessons</a>
			</p>
		<!-- //Where am I? -->

	
		<!-- About -->
			<div style=\"border: #ccc 1px solid;margin-bottom: 20px;\">
				<p>This looks for file <a href=\"../$get_current_course_dir_name/_modules_and_lessons.txt\">../$get_current_course_dir_name/_modules_and_lessons.txt</a>.
				The pattern should be like this:<br /><br />
				module_title | module_url<br />
				- lesson: lesson_title | lesson_description | lesson_url<br />
				- lesson: lesson_title | lesson_description | lesson_url<br />
				- lesson: lesson_title | lesson_description | lesson_url<br />
				- lesson: lesson_title | lesson_description | lesson_url<br />
				- quiz: quiz_title | quiz_description | quiz_url<br />
				- exam: exam_title | exam_description | exam_url<br />
				</p>
			</div>
		<!-- //About -->

		<!-- Look for file -->
		";
		if(file_exists("../$get_current_course_dir_name/_modules_and_lessons.txt")){
			$fh = fopen("../$get_current_course_dir_name/_modules_and_lessons.txt", "r");
			$data = fread($fh, filesize("../$get_current_course_dir_name/_modules_and_lessons.txt"));
			fclose($fh);

			// Delete old entries
			// $result = mysqli_query($link, "DELETE FROM $t_courses_modules WHERE course_id=$get_current_course_id");
			// $result = mysqli_query($link, "DELETE FROM $t_courses_modules_lessons WHERE course_id=$get_current_course_id");


			// Write new
			$data_array = explode("\n", $data);
			$size = sizeof($data_array);


			// vars
			$datetime = date("Y-m-d H:i:s");
			$date_formatted = date("j M Y");
			$module_counter = 0;
			$content_counter = 0;
			for($x=0;$x<$size;$x++){
				$temp = explode("|", $data_array[$x]);
				$temp_size = sizeof($temp);

				$type = explode(":", $temp[0]); // "- less" "- quiz" "- exam"
				$type_size = sizeof($type);
				if($type_size == 1 && $temp[0] != ""){
					$inp_module_title = trim($temp[0]);
					$inp_module_title = output_html($inp_module_title);
					$inp_module_title_mysql = quote_smart($link, $inp_module_title);


					$inp_module_url = trim($temp[1]);
					$inp_module_url = output_html($inp_module_url);
					$inp_module_url_mysql = quote_smart($link, $inp_module_url);

					$inp_title_clean = str_replace(".php", "", $inp_module_url);
					$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);


					// Check if it exists
					$module_course_dir_name_mysql = quote_smart($link, $get_current_course_dir_name);
					$query = "SELECT module_id, module_title_clean FROM $t_courses_modules WHERE module_course_dir_name=$module_course_dir_name_mysql AND module_title=$inp_module_title_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_module_id, $get_module_title_clean) = $row;

					if($get_module_id == ""){

						$module_counter = $module_counter+1;			

						$inp_course_dir_name_mysql = quote_smart($link, $get_current_course_dir_name);


						mysqli_query($link, "INSERT INTO $t_courses_modules
						(module_id, module_course_id, module_course_dir_name, module_number, module_title, module_title_clean, module_url, module_read_times, module_created, module_updated) 
						VALUES 
						(NULL, $get_current_course_id, $inp_course_dir_name_mysql, $module_counter, $inp_module_title_mysql, $inp_title_clean_mysql, $inp_module_url_mysql, 0, '$datetime', '$datetime')")
						or die(mysqli_error($link));
					
						// Get ID
						$query = "SELECT module_id, module_title_clean FROM $t_courses_modules WHERE module_course_dir_name=$module_course_dir_name_mysql AND module_title=$inp_module_title_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_module_id, $get_module_title_clean) = $row;
					}
					echo"<h2>$inp_module_title &middot; $get_module_title_clean &middot; $get_module_id </h2>";


					
				} // end module
				else{
					if(isset($type[1]) && !(empty(trim($type[1])))){
						
						$inp_type = trim($type[0]);
						$inp_type = str_replace("- ", "", $inp_type);
						$inp_type = output_html($inp_type);
						$inp_type_mysql = quote_smart($link, $inp_type);

						$content_counter = $content_counter+1;

						$inp_title = trim($type[1]);
						$inp_title = output_html($inp_title);
						$inp_title_mysql = quote_smart($link, $inp_title);

						$inp_title_clean = clean($inp_title);
						$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

						$inp_description = trim($temp[1]);
						if($inp_description == "-"){
							$inp_description = "";
						}
						$inp_description = output_html($inp_description);
						$inp_description_mysql = quote_smart($link, $inp_description);

						$inp_url = trim($temp[2]);
						$inp_url = output_html($inp_url);
						$inp_url_mysql = quote_smart($link, $inp_url);
						
						if($inp_title != ""){
							// Check if it exists
							$content_course_dir_name_mysql = quote_smart($link, $get_current_course_dir_name);
							$content_module_title_clean_mysql = quote_smart($link, $get_module_title_clean);
							$query = "SELECT content_id FROM $t_courses_modules_contents WHERE content_course_dir_name=$content_course_dir_name_mysql AND content_module_title_clean=$content_module_title_clean_mysql AND content_title=$inp_title_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_content_id) = $row;

							if($get_content_id == ""){
								mysqli_query($link, "INSERT INTO $t_courses_modules_contents 
								(content_id, content_course_id, content_course_dir_name, content_module_id, content_module_title_clean, content_type, content_number, content_title, content_title_clean, content_description, content_url, content_read_times, content_read_times_ipblock, content_created_datetime, content_created_date_formatted) 
								VALUES 
								(NULL, $get_current_course_id, $content_course_dir_name_mysql, $get_module_id, $content_module_title_clean_mysql, $inp_type_mysql, $content_counter, $inp_title_mysql, $inp_title_clean_mysql, $inp_description_mysql, $inp_url_mysql, '0', '', '$datetime', '$date_formatted')")
								or die(mysqli_error($link));

								$query = "SELECT content_id FROM $t_courses_modules_contents WHERE content_course_dir_name=$content_course_dir_name_mysql AND content_module_title_clean=$content_module_title_clean_mysql AND content_title=$inp_title_mysql";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_content_id) = $row;
							}
							echo"<span>$inp_title &middot; $get_content_id<br /></span>";
						} // not empty title
					} //isset $type[1]
				} // is content, not module			
			} // for
		} // file exists

		echo"
		<!-- Look for file -->
		";
		

	} // action ==""
} // found
?>