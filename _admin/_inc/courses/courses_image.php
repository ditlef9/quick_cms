<?php
/**
*
* File: _admin/_inc/comments/courses_image.php
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

/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");

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

$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=$course_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

if($get_current_course_id == ""){
	echo"<p>Server error 404.</p>";
}
else{
	// Find category
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=$get_current_course_main_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_courses_categories_sub WHERE sub_category_id=$get_current_course_sub_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;


	if($action == ""){
		if($process == "1"){
			// Folder
			if(!(is_dir("../$get_current_course_title_clean"))){
				mkdir("../$get_current_course_title_clean");
			}
			if(!(is_dir("../$get_current_course_title_clean/_gfx"))){
				mkdir("../$get_current_course_title_clean/_gfx");
			}

			$image_name = stripslashes($_FILES['inp_image']['name']);
			$extension = get_extension($image_name);
			$extension = strtolower($extension);

			if($image_name){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft = "warning";
					$fm = "unknown_file_extension_$extension";

					$url = "index.php?open=courses&page=courses_image&course_id=$get_current_course_id&editor_language=en&l=en&ft=$ft&fm=$fm";
					header("Location: $url");
					exit;
				}
				else{
 
					// Give new name
					$inp_image_file = $get_current_course_title_clean . "_800x453.$extension";
					$new_path = "../$get_current_course_title_clean/_gfx";
					$uploaded_file = $new_path . "/" . $inp_image_file;
					// Upload file
					if (move_uploaded_file($_FILES['inp_image']['tmp_name'], $uploaded_file)) {

						// Get image size
						$file_size = filesize($uploaded_file);
						
						// Check with and height
						list($width,$height) = getimagesize($uploaded_file);
	
						if($width == "" OR $height == ""){
							unlink("$uploaded_file");

							$ft = "warning";
							$fm = "getimagesize_failed";

							$url = "index.php?open=courses&page=courses_image&course_id=$get_current_course_id&editor_language=$editor_language&l=$l&ft=$ft&fm=$fm";
							header("Location: $url");
							exit;
						}
						else{
							// All ok
							$inp_image_file_mysql = quote_smart($link, $inp_image_file);
							
							$inp_image_thumb = $get_current_course_title_clean . "_800x453_thumb.$extension";
							$inp_image_thumb_mysql = quote_smart($link, $inp_image_thumb);

							
							$datetime = date("Y-m-d H:i:s");
							$result = mysqli_query($link, "UPDATE $t_courses_index SET 
										course_image_file=$inp_image_file_mysql, 
										course_image_thumb=$inp_image_thumb_mysql, 
										course_updated='$datetime'
										WHERE course_id=$get_current_course_id") or die(mysqli_error($link));

							// Delete old thumb
							if(file_exists("$new_path/$inp_image_thumb")){
								unlink("$new_path/$inp_image_thumb");
							}

							// Make new thumb
							resize_crop_image(210, 119, "../$get_current_course_title_clean/_gfx/$inp_image_file", "../$get_current_course_title_clean/_gfx/$inp_image_thumb");
					



							// Get new data
							$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=$course_id_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

							// Write to files
							include("_inc/courses/courses_write_to_file_include.php");



							$ft = "success";
							$fm = "image_uploaded";

							$url = "index.php?open=courses&page=courses_image&course_id=$get_current_course_id&editor_language=$editor_language&l=$l&ft=$ft&fm=$fm";
							header("Location: $url");
							exit;
						}

					}
					else{
						switch ($_FILES['inp_food_image']['error']) {
							case UPLOAD_ERR_OK:
           							$fm = "There is no error, the file uploaded with success.";
								break;
							case UPLOAD_ERR_NO_FILE:
           							$fm = "no_file_uploaded";
								break;
							case UPLOAD_ERR_INI_SIZE:
           							$fm = "to_big_size_in_configuration";
								break;
							case UPLOAD_ERR_FORM_SIZE:
           							$fm = "to_big_size_in_form";
								break;
							default:
           							$fm = "unknown_error";
								break;
						}	
						$ft = "warning";
						

						$url = "index.php?open=courses&page=courses_image&course_id=$get_current_course_id&editor_language=en&l=en&ft=$ft&fm=$fm";
						header("Location: $url");
						exit;
					}
				}
			}
			else{
				$ft = "warning";
				$fm = "could_not_upload_image";

				$url = "index.php?open=courses&page=courses_image&course_id=$get_current_course_id&editor_language=en&l=en&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;
			}

		} // process

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
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Course navigation -->

		<!-- Existing image -->
			";
			if($get_current_course_image_file != "" && file_exists("../$get_current_course_title_clean/_gfx/$get_current_course_image_file")){
				echo"
				<p><img src=\"../$get_current_course_title_clean/_gfx/$get_current_course_image_file\" alt=\"$get_current_course_image_file\" />
				</p>
				";
			}
			echo"
		<!-- //Existing image -->

		<!-- Form -->
			<form method=\"post\" action=\"index.php?open=courses&amp;page=$page&amp;course_id=$get_current_course_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>New image (800x453)</b><br />
			<input type=\"file\" name=\"inp_image\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> <br />
			</p>

			<p>
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
		<!-- //Form -->
		";
	} // action ==""
} // found
?>