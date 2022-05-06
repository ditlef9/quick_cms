<?php
/**
*
* File: _admin/_inc/comments/courses_icon.php
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

$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=$course_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

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


			$ft = "info";
           		$fm = "nothing";
			// $icon_sizes = array('16', '32', '48', '64', '96', '260');
			$icon_sizes = array('16', '32', '96', '260');
			for($x=0;$x<sizeof($icon_sizes);$x++){
				$icon_size = $icon_sizes[$x] . "x" . $icon_sizes[$x];
				


				$image_name = stripslashes($_FILES["inp_icon_$icon_size"]['name']);
				$extension = get_extension($image_name);
				$extension = strtolower($extension);

				if($image_name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft = "warning";
						$fm = "unknown_file_extension_$extension";

					}
					else{
 
						// Give new name
						$inp_name = $get_current_course_title_clean . "_icon_" . $icon_size . ".$extension";
						$new_path = "../$get_current_course_title_clean/_gfx";
						$uploaded_file = $new_path . "/" . $inp_name;

						// Upload file
						if (move_uploaded_file($_FILES["inp_icon_$icon_size"]['tmp_name'], $uploaded_file)) {

							// Get image size
							$file_size = filesize($uploaded_file);
						
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								unlink("$uploaded_file");

								$ft = "warning";
								$fm = "getimagesize_failed";

							
							}
							else{
								// All ok
								$inp_icon_mysql = quote_smart($link, $inp_name);
							
							
								$datetime = date("Y-m-d H:i:s");


								if($icon_sizes[$x] == "16"){
									$result = mysqli_query($link, "UPDATE $t_courses_index SET 
										course_icon_16=$inp_icon_mysql, 
										course_updated='$datetime'
										WHERE course_id=$get_current_course_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "32"){
									$result = mysqli_query($link, "UPDATE $t_courses_index SET 
										course_icon_32=$inp_icon_mysql, 
										course_updated='$datetime'
										WHERE course_id=$get_current_course_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "48"){
									$result = mysqli_query($link, "UPDATE $t_courses_index SET 
										course_icon_48=$inp_icon_mysql, 
										course_updated='$datetime'
										WHERE course_id=$get_current_course_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "64"){
									$result = mysqli_query($link, "UPDATE $t_courses_index SET 
										course_icon_64=$inp_icon_mysql, 
										course_updated='$datetime'
										WHERE course_id=$get_current_course_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "96"){
									$result = mysqli_query($link, "UPDATE $t_courses_index SET 
										course_icon_96=$inp_icon_mysql, 
										course_updated='$datetime'
										WHERE course_id=$get_current_course_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "260"){
									$result = mysqli_query($link, "UPDATE $t_courses_index SET 
										course_icon_260=$inp_icon_mysql, 
										course_updated='$datetime'
										WHERE course_id=$get_current_course_id") or die(mysqli_error($link));
								}

								$ft = "success";
								$fm = "icon_uploaded";

						
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
						
						}
					}
				}
			} // for



			// Get new data
			$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=$course_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

			// Write to files
			include("_inc/courses/courses_write_to_file_include.php");

			// Header
			$url = "index.php?open=courses&page=$page&course_id=$get_current_course_id&editor_language=en&l=en&ft=$ft&fm=$fm";
			header("Location: $url");
			exit;

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
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Course navigation -->
		

		<!-- Icon 48, 64, 96 -->
			<form method=\"post\" action=\"index.php?open=courses&amp;page=$page&amp;course_id=$get_current_course_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			";

			$icon_sizes = array('16', '32', '96', '260');
			// $icon_sizes = array('16', '32', '48', '64', '96', '260');
			for($x=0;$x<sizeof($icon_sizes);$x++){
				$icon_size = $icon_sizes[$x] . "x" . $icon_sizes[$x];
				echo"
				<!-- Icon x -->
					<h2>$icon_size</h2>
		
					<table>
					 <tr>
					  <td style=\"vertical-align:top;padding-right: 20px;\">
						<p><b>New icon ($icon_size)</b><br />";
						if($icon_size == "16x16" OR $icon_size == "32x32" OR $icon_size == "260x260"){
							echo"						";
							echo"<span class=\"smal\">Used for favicon</span><br />\n";
						}
						elseif($icon_size == "96x96"){
							echo"						";
							echo"<span class=\"smal\">Used for courses overview site</span><br />\n";
						}
						echo"
						<input type=\"file\" name=\"inp_icon_$icon_size\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					  </td>
					  <td style=\"vertical-align:top;padding-right: 20px;\">
						";
						if($icon_sizes[$x] == "16" && $get_current_course_icon_16 != "" && file_exists("../$get_current_course_title_clean/_gfx/$get_current_course_icon_16")){
							echo"<p><img src=\"../$get_current_course_title_clean/_gfx/$get_current_course_icon_16\" alt=\"$get_current_course_icon_16\" /></p>\n";
						}
						if($icon_sizes[$x] == "32" && $get_current_course_icon_32 != "" && file_exists("../$get_current_course_title_clean/_gfx/$get_current_course_icon_32")){
							echo"<p><img src=\"../$get_current_course_title_clean/_gfx/$get_current_course_icon_32\" alt=\"$get_current_course_icon_32\" /></p>\n";
						}
						if($icon_sizes[$x] == "48" && $get_current_course_icon_48 != "" && file_exists("../$get_current_course_title_clean/_gfx/$get_current_course_icon_48")){
							echo"<p><img src=\"../$get_current_course_title_clean/_gfx/$get_current_course_icon_48\" alt=\"$get_current_course_icon_48\" /></p>\n";
						}
						if($icon_sizes[$x] == "64" && $get_current_course_icon_64 != "" && file_exists("../$get_current_course_title_clean/_gfx/$get_current_course_icon_64")){
							echo"<p><img src=\"../$get_current_course_title_clean/_gfx/$get_current_course_icon_64\" alt=\"$get_current_course_icon_64\" /></p>\n";
						}
						if($icon_sizes[$x] == "96" && $get_current_course_icon_96 != "" && file_exists("../$get_current_course_title_clean/_gfx/$get_current_course_icon_96")){
							echo"<p><img src=\"../$get_current_course_title_clean/_gfx/$get_current_course_icon_96\" alt=\"$get_current_course_icon_96\" /></p>\n";
						}
						if($icon_sizes[$x] == "260" && $get_current_course_icon_260 != "" && file_exists("../$get_current_course_title_clean/_gfx/$get_current_course_icon_260")){
							echo"<p><img src=\"../$get_current_course_title_clean/_gfx/$get_current_course_icon_260\" alt=\"$get_current_course_icon_260\" /></p>\n";
						}
						echo"
					  </td>
					 </tr>
					</table>
					
				<!-- //Icon x -->
				";
			}
			echo"
			<p>
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
		<!-- //Icon 48, 64, 96 -->
		";
	} // action ==""
} // found
?>