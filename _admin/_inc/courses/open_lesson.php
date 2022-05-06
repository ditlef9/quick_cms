<?php
/**
*
* File: _admin/_inc/comments/courses_modules_and_lessons.php
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
$t_courses_lessons_images	= $mysqlPrefixSav . "courses_lessons_images";

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

if(isset($_GET['lesson_id'])){
	$lesson_id = $_GET['lesson_id'];
	$lesson_id = strip_tags(stripslashes($lesson_id));
	if(!(is_numeric($lesson_id))){
		echo"lesson id not numeric";
		die;
	}
}
else{
	$lesson_id = "";
}
$lesson_id_mysql = quote_smart($link, $lesson_id);

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
	$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_content, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=$module_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_content, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;
	if($get_current_module_id == ""){
		echo"<p>Module not found</p>";
	}
	else{
		// Find lesson
		$query = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_title_length, lesson_title_short, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id=$lesson_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_title_length, $get_current_lesson_title_short, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;
		if($get_current_lesson_id == ""){
			echo"<p>Lesson not found</p>";
		}
		else{
			if($process == "1"){
				// Dates
				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");


				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_title_clean = clean($inp_title);
				$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

				$inp_title_length = strlen($inp_title);
				$inp_title_length_mysql = quote_smart($link, $inp_title_length);

				if($inp_title_length  > 27){
					$inp_title_short = substr($inp_title, 0, 27);
					$inp_title_short = $inp_title_short . "...";
				}
				else{
					$inp_title_short = "";
				}
				$inp_title_short_mysql = quote_smart($link, $inp_title_short);

				$result = mysqli_query($link, "UPDATE $t_courses_lessons SET 
								lesson_title=$inp_title_mysql, 
								lesson_title_clean=$inp_title_clean_mysql, 
								lesson_title_length=$inp_title_length_mysql, 
								lesson_title_short=$inp_title_short_mysql
								WHERE lesson_id=$get_current_lesson_id");

				$inp_content = $_POST['inp_content'];
				$sql = "UPDATE $t_courses_lessons  SET lesson_content=? WHERE lesson_id=$get_current_lesson_id";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("s", $inp_content);
				$stmt->execute();
				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error; die;
				}


				// Search engine
				$inp_index_title = "$inp_title | $get_current_module_title | $get_current_course_title | $get_current_courses_title_translation_title";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_current_course_title_clean/$get_current_module_title_clean/$get_current_lesson_title_clean.php?course_id=$get_current_course_id&module_id=$get_current_module_id&lesson_id=$get_current_lesson_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);
			

				$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='courses' AND index_reference_name='lesson_id' AND index_reference_id=$get_current_lesson_id";
				$result_exists = mysqli_query($link, $query_exists);
				$row_exists = mysqli_fetch_row($result_exists);
				list($get_index_id) = $row_exists;
				if($get_index_id != ""){
					$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_title=$inp_index_title_mysql, 
								index_url=$inp_index_url_mysql, 
								index_updated_datetime='$datetime',
								index_updated_datetime_print='$datetime_saying'
								WHERE index_id=$get_index_id") or die(mysqli_error($link));
				}


				// Write to flat file
				$input="<?php
/*- Configuration ---------------------------------------------------------------------------- */
\$layoutNumberOfColumn = \"2\";
\$layoutCommentsActive = \"1\";

/*- Header ----------------------------------------------------------- */
\$website_title = \"$get_current_course_title - $get_current_module_title - $inp_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/*- Content ---------------------------------------------------------- */
?>

$inp_content

<?php
/*- Course ---------------------------------------------------------- */
include(\"\$root/courses/_includes/content_after_content.php\");

/*- Footer ----------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";
			
			if(!(is_dir("../$get_current_course_title_clean"))){
				mkdir("../$get_current_course_title_clean");
			}
			if(!(is_dir("../$get_current_course_title_clean/$get_current_module_title_clean"))){
				mkdir("../$get_current_course_title_clean/$get_current_module_title_clean");
			}
			$fh = fopen("../$get_current_course_title_clean/$get_current_module_title_clean/$get_current_lesson_title_clean.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);
	

			$url = "index.php?open=courses&page=$page&course_id=$course_id&module_id=$get_current_module_id&lesson_id=$get_current_lesson_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		} // process
		echo"
		<h1>$get_current_lesson_title</h1>
				

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
			<a href=\"index.php?open=courses&amp;page=courses_modules_and_lessons&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_module&amp;course_id=$course_id&amp;module_id=$get_current_module_id&amp;lesson_id=$get_current_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_module_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$course_id&amp;module_id=$get_current_module_id&amp;lesson_id=$get_current_lesson_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_lesson_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Edit lesson form -->

			<!-- TinyMCE -->
				<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
						'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
						'//www.tiny.cloud/css/codepen.min.css'
					],
					link_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
					image_list: [";
					$x = 0;
					$query_i = "SELECT image_id, image_title, image_path, image_file FROM $t_courses_lessons_images WHERE image_lesson_id=$get_current_lesson_id ORDER BY image_title ASC";
					$result_i = mysqli_query($link, $query_i);
					while($row_i = mysqli_fetch_row($result_i)) {
						list($get_image_id, $get_image_title, $get_image_path, $get_image_file) = $row_i;
						if($x > 0){
							echo",";
						}
						echo"
						{ title: '$get_image_title', value: '../$get_image_path/$get_image_file' }";
						$x++;
					}

					echo"
					],
						image_class_list: [
						{ title: 'None', value: '' },
						{ title: 'Some class', value: 'class-name' }
					],
					importcss_append: true,
					height: 500,
					/* without images_upload_url set, Upload tab won't show up*/
					images_upload_url: 'index.php?open=$open&page=open_lesson_upload_image&course_id=$course_id&module_id=$get_current_module_id&lesson_id=$get_current_lesson_id&process=1',
				});
				</script>
			<!-- //TinyMCE -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;module_id=$get_current_module_id&amp;lesson_id=$get_current_lesson_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		
			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_lesson_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 90%;\" />
			</p>

			
			<p>
			<textarea name=\"inp_content\" rows=\"40\" cols=\"120\" class=\"editor\">$get_current_lesson_content</textarea>
			</p>

			<p>
			<input type=\"submit\" value=\"Save lesson\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			<!-- //Edit lesson form -->
			";
		} // lesson found
	} // module found
	
} // found course
?>