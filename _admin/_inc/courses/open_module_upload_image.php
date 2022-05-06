<?php
/**
*
* File: _admin/_inc/courses/open_module_upload_image.php
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
$t_courses_modules_images	 = $mysqlPrefixSav . "courses_modules_images";

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


/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['course_id'])){
	$course_id = $_GET['course_id'];
	$course_id = strip_tags(stripslashes($course_id));
	if(!(is_numeric($course_id))){
		echo"Course id not numeric";
		die;
	}
}
else{
	$course_id = "";
}
$course_id_mysql = quote_smart($link, $course_id);
if(isset($_GET['module_id'])){
	$module_id = $_GET['module_id'];
	$module_id = strip_tags(stripslashes($module_id));
	if(!(is_numeric($module_id))){
		echo"Module id not numeric";
		die;
	}
}
else{
	$module_id = "";
}
$module_id_mysql = quote_smart($link, $module_id);

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

	// Title
	$l_mysql = quote_smart($link, $get_current_course_language);
	$query = "SELECT courses_title_translation_id, courses_title_translation_title FROM $t_courses_title_translations WHERE courses_title_translation_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_courses_title_translation_id, $get_current_courses_title_translation_title) = $row;
	if($get_current_courses_title_translation_id == ""){
		mysqli_query($link, "INSERT INTO $t_courses_title_translations
		(courses_title_translation_id, courses_title_translation_title, courses_title_translation_language) 
		VALUES 
		(NULL, 'Courses', $l_mysql)")
		or die(mysqli_error($link));
		$get_current_courses_title_translation_title = "Courses";
	}

	// Find module
	$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=$module_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;
	if($get_current_module_id == ""){
		echo"<p>Module not found</p>";
	}
	else{
		if($process == "1"){
			// Get my user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_email, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	
			// Mkdir
			if(!(is_dir("../$get_current_course_title_clean"))){
				mkdir("../$get_current_course_title_clean");
			}
			if(!(is_dir("../$get_current_course_title_clean/$get_current_module_title_clean"))){
				mkdir("../$get_current_course_title_clean/$get_current_module_title_clean");
			}
			if(!(is_dir("../$get_current_course_title_clean/$get_current_module_title_clean/_images"))){
				mkdir("../$get_current_course_title_clean/$get_current_module_title_clean/_images");
			}
			if(!(is_dir("../$get_current_course_title_clean/$get_current_module_title_clean/_images/$get_current_module_title_clean"))){
				mkdir("../$get_current_course_title_clean/$get_current_module_title_clean/_images/$get_current_module_title_clean");
			}

			// Upload image

			// Image folder
			$imageFolder = "../$get_current_course_title_clean/$get_current_module_title_clean/_images/$get_current_module_title_clean/";

			reset ($_FILES);
			$temp = current($_FILES);
			if (is_uploaded_file($temp['tmp_name'])){
				if (isset($_SERVER['HTTP_ORIGIN'])) {
					// same-origin requests won't set an origin. If the origin is set, it must be valid.
				}

				// Sanitize input
				if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
					header("HTTP/1.1 400 Invalid file name.");
					return;
				}

				// Verify extension
				if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
					header("HTTP/1.1 400 Invalid extension.");
					return;
				}


				list($width,$height) = @getimagesize($temp['tmp_name']);
				if($width == "" OR $height == ""){
					header("HTTP/1.1 400 Invalid extension.");
					return;
				}

				// Get extension
				$inp_ext = get_extension($temp['name']);
				$inp_ext = output_html($inp_ext);
				$inp_ext_mysql = quote_smart($link, $inp_ext);

				// New name
				$name = $temp['name'];
				$new_name = str_replace(".$inp_ext", "", $name);
				$new_name = output_html($new_name);
				$new_name = clean($new_name);
				$new_name = $new_name . ".$inp_ext";

				// Accept upload if there was no origin, or if it is an accepted origin
				$filetowrite = $imageFolder . $new_name;

				// Move it
				move_uploaded_file($temp['tmp_name'], $filetowrite);


				$inp_title = $temp['name'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_file_path = "$get_current_course_title_clean/$get_current_module_title_clean/_images/$get_current_module_title_clean";
				$inp_file_path_mysql = quote_smart($link, $inp_file_path);

				$datetime = date("Y-m-d H:i:s");

				$inp_file_name = output_html($new_name);
				$inp_file_name_mysql = quote_smart($link, $inp_file_name);

				$inp_file_thumb_a = str_replace(".$inp_ext", "", $name);
				$inp_file_thumb_a = $inp_file_thumb_a . "_thumb_200x113" . "." . $inp_ext;
				$inp_file_thumb_a_mysql = quote_smart($link, $inp_file_thumb_a);

				// IP
				$my_ip = "";
				$my_ip = $_SERVER['REMOTE_ADDR'];
				$my_ip = output_html($my_ip);
				$my_ip_mysql = quote_smart($link, $my_ip);

				mysqli_query($link, "INSERT INTO $t_courses_modules_images
				(image_id, image_course_id, image_module_id, image_title, image_text, 
				image_path, image_file, image_thumb_200x113, image_photo_by_name, image_photo_by_website, 
				image_uploaded_datetime, image_uploaded_user_id, image_uploaded_ip) 
				VALUES 
				(NULL, $get_current_course_id, $get_current_module_id, $inp_title_mysql, '', 
				$inp_file_path_mysql, $inp_file_name_mysql, $inp_file_thumb_a_mysql, '', '', 
				'$datetime', $get_my_user_id, $my_ip_mysql)")
				or die(mysqli_error($link));


				// Respond to the successful upload with JSON.
				// Use a location key to specify the path to the saved image resource.
				// { location : '/your/uploaded/image/file'}
				echo json_encode(array('location' => $filetowrite));
				exit;
			} 
			else {
				// Notify editor that the upload failed
				switch ($temp['name']['error']) {
						case UPLOAD_ERR_OK:
							$fm = "photo_unknown_error";
							break;
						case UPLOAD_ERR_NO_FILE:
       							$fm = "no_file_selected";
							break;
						case UPLOAD_ERR_INI_SIZE:
           						$fm = "photo_exceeds_filesize";
							break;
						case UPLOAD_ERR_FORM_SIZE:
           						$fm = "photo_exceeds_filesize_form";
							break;
						default:
           						$fm = "unknown_upload_error";
							break;
				}
				header("HTTP/1.1 500 Server Error $fm");
			}
			
		} // process == 1
	} // module found
	
} // found course
?>